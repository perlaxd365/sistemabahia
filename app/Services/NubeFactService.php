<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Comprobante;
use App\Models\NubefactConfig;
use DateUtil;
use Exception;

class NubeFactService
{
    protected string $ruta;
    protected string $token;

    protected $config;
    public function __construct()
    {
        $this->config = NubefactConfig::where('activo', true)->first();

        if (!$this->config) {
            throw new Exception('No existe configuración activa de NubeFact');
        }

        $this->ruta  = $this->config->ruta;
        $this->token = $this->config->token;
    }

    /**
     * Emitir comprobante en SUNAT vía NubeFact
     */
    public function emitir(Comprobante $comprobante): array
    {
        if ($comprobante->estado !== 'BORRADOR') {
            throw new Exception('El comprobante no está en estado BORRADOR');
        }

        $payload = $this->buildJson($comprobante);

        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $this->token,
            'Content-Type'  => 'application/json',
        ])->post($this->ruta, $payload);

        return $this->procesarRespuesta($response->json(), $comprobante);
    }

    /**
     * Construir JSON oficial NubeFact
     */
    protected function buildJson(Comprobante $c): array
    {
        

        $items   = $this->buildItems($c);
        $totales = $this->calcularTotales($items);
        return [
            'operacion' => 'generar_comprobante',

            // 1 = Factura | 2 = Boleta
            'tipo_de_comprobante' =>
            $c->tipo_comprobante === 'FACTURA' ? 1 : 2,
            // ✅ SERIES REALES DE TU CUENTA
            'serie' => $c->tipo_comprobante === 'FACTURA'
                ? 'FFF1'
                : 'BBB1',
            'numero' => null,
            'codigo_unico' => 'CB-' . $c->id_comprobante,
            'sunat_transaction' => 1,

            // 🔴 CLIENTE CORRECTO
            'cliente_tipo_de_documento' =>
            $c->tipo_comprobante === 'FACTURA' ? 6 : 1,

            'cliente_numero_de_documento' =>
            $c->cliente->numero_documento,

            'cliente_denominacion' =>
            $c->cliente->razon_social ?? $c->cliente->nombres,

            'cliente_direccion' => $c->cliente->direccion ?? '',
            'cliente_email'     => $c->cliente->email ?? '',

            // Fechas
            'fecha_de_emision' => DateUtil::getFechaSimpleGuion($c->fecha_emision),
            'moneda' => 1,

            // 🔴 TOTALES CALCULADOS (NO BD)
            'porcentaje_de_igv' => 18.00,
            'total_gravada'     => $totales['total_gravada'],
            'total_igv'         => $totales['total_igv'],
            'total'             => $totales['total'],

            'enviar_automaticamente_a_la_sunat' => true,
            'enviar_automaticamente_al_cliente' => false,

            'items' => $items,
        ];
    }

    /**
     * Construir items
     */
    protected function buildItems(Comprobante $c): array
    {
        return $c->detalles->map(function ($d) {

            $cantidad = (float) $d->cantidad;
            $precioConIgv = (float) $d->precio_unitario;

            $valorUnitario = round($precioConIgv / 1.18, 2);
            $subtotal      = round($valorUnitario * $cantidad, 2);
            $igv           = round($subtotal * 0.18, 2);
            $total         = $subtotal + $igv;

            return [
                'unidad_de_medida' => $d->unidad ?? 'NIU',
                'codigo'           => $d->codigo ?? '',
                'descripcion'      => $d->descripcion,
                'cantidad'         => $cantidad,

                'valor_unitario'   => $valorUnitario,
                'subtotal'         => $subtotal,
                'precio_unitario'  => $precioConIgv,
                'igv'              => $igv,
                'total'            => $total,

                'tipo_de_igv'    => 1,
                'codigo_tributo' => '1000',
                'nombre_tributo' => 'IGV',
                'tipo_tributo'   => 'VAT',
            ];
        })->toArray();
    }

    protected function calcularTotales(array $items): array
    {
        $gravada = 0;
        $igv = 0;

        foreach ($items as $item) {
            $gravada += $item['subtotal'];
            $igv     += $item['igv'];
        }

        $gravada = round($gravada, 2);
        $igv     = round($igv, 2);

        return [
            'total_gravada' => $gravada,
            'total_igv'     => $igv,
            'total'         => $gravada + $igv,
        ];
    }

    protected function getSerie(Comprobante $c): string
    {
        return $c->tipo_comprobante === 'FACTURA'
            ? 'FFF1'
            : 'BBB1';
    }

    protected function buildPayload(Comprobante $c): array
    {
        $items = $this->buildItems($c);
        $totales = $this->calcularTotales($items);
        $cliente = $c->cliente;

        return [
            'operacion'           => 'generar_comprobante',
            'tipo_de_comprobante' => $c->tipo_comprobante === 'FACTURA' ? '1' : '2',

            'serie'  => $this->getSerie($c),
            'numero' => null, // 🔴 deja que NubeFact maneje correlativo

            // CLIENTE
            'cliente_tipo_de_documento'   => $c->tipo_comprobante === 'FACTURA' ? '6' : '1',
            'cliente_numero_de_documento' => $cliente->numero_documento,
            'cliente_denominacion'        => $cliente->razon_social ?? $cliente->nombres,
            'cliente_direccion'           => $cliente->direccion ?? '',

            'moneda' => '1',

            // 🔴 TOTALES CORRECTOS
            'total_gravada' => $totales['total_gravada'],
            'total_igv'     => $totales['total_igv'],
            'total'         => $totales['total'],

            'items' => $items,
        ];
    }



    /**
     * Procesar respuesta SUNAT
     */
    protected function procesarRespuesta(array $res, Comprobante $c): array
    {
        if (isset($res['errors'])) {
            $c->update([
                'estado' => 'RECHAZADO',
                'sunat_descripcion' => json_encode($res['errors']),
            ]);
            return $res;
        }

        $c->update([
            'estado' => 'EMITIDO',
            'sunat_codigo' => $res['codigo'] ?? null,
            'sunat_descripcion' => ($res['aceptada_por_sunat'] ?? false)
                ? 'ACEPTADO'
                : 'OBSERVADO',
            'sunat_hash' => $res['codigo_hash'] ?? null,
            'sunat_qr'   => $res['cadena_para_codigo_qr'] ?? null,
            'xml_url'    => $res['enlace_del_xml'] ?? null,
            'cdr_url'    => $res['enlace_del_cdr'] ?? null,
            'pdf_url'    => $res['enlace_del_pdf'] ?? null,
        ]);

        return $res;
    }
    /**
     * Consultar RUC en SUNAT vía NubeFact
     */
    public function consultarRuc(string $ruc): ?array
    {
        $token = 'sk_12854.9I7yGiw8UMPISAyV9OThCBRQBC8KZkqF';
        // Iniciar llamada a API
        $curl = curl_init();

        // Buscar ruc sunat
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.decolecta.com/v1/sunat/ruc?numero=' . $ruc,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: http://apis.net.pe/api-ruc',
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // Datos de empresas según padron reducido
        $empresa = json_decode($response);

        return [
            'razon_social' => $empresa->razon_social ?? null,
            'direccion'    => $empresa->direccion ?? null,
            'estado'    => $empresa->estado ?? null,
        ];
    }
    /**CONSULTA DNI */
    public function consultarDni(string $dni): ?array
    {
        $token = 'sk_12854.9I7yGiw8UMPISAyV9OThCBRQBC8KZkqF';
        // Iniciar llamada a API
        $curl = curl_init();

        // Buscar ruc sunat
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.decolecta.com/v1/reniec/dni?numero=' . $dni,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: http://apis.net.pe/api-ruc',
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // Datos de empresas según padron reducido
        $persona = json_decode($response);
        return [
            'nombres' => $persona->full_name ?? null,
        ];
    }
}
