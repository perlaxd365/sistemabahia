<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Comprobante;
use App\Models\NubefactConfig;
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
        $cliente = $c->tipo_comprobante === 'FACTURA'
            ? $c->cliente
            : $c->paciente;

        return [
            'operacion' => 'generar_comprobante',

            // 1 = Factura | 2 = Boleta | 3 = Nota de crédito
            'tipo_de_comprobante' =>
            $c->tipo_comprobante === 'FACTURA' ? 1 : ($c->tipo_comprobante === 'BOLETA' ? 2 : 3),

            'serie'  => $c->serie,
            'numero' => $c->numero,

            'sunat_transaction' => 1,

            // Cliente
            'cliente_tipo_de_documento' =>
            $c->tipo_comprobante === 'FACTURA' ? 6 : 1,

            'cliente_numero_de_documento' =>
            $c->tipo_comprobante === 'FACTURA'
                ? $cliente->ruc
                : $cliente->dni,

            'cliente_denominacion' =>
            $cliente->razon_social ?? $cliente->nombre_completo,

            'cliente_direccion' => $cliente->direccion ?? '',
            'cliente_email'     => $cliente->email ?? '',

            // Fechas y moneda
            'fecha_de_emision' => $c->fecha_emision->format('d-m-Y'),
            'moneda' => 1, // PEN

            // Totales
            'porcentaje_de_igv' => 18.00,
            'total_gravada' => $c->subtotal,
            'total_igv'     => $c->igv,
            'total'         => $c->total,

            // Envíos
            'enviar_automaticamente_a_la_sunat' => true,
            'enviar_automaticamente_al_cliente' => false,

            'items' => $this->buildItems($c),
        ];
    }

    /**
     * Construir items
     */
    protected function buildItems(Comprobante $c): array
    {
        return $c->detalles->map(function ($d) {
            return [
                'unidad_de_medida' => $d->unidad,
                'codigo' => $d->codigo,
                'descripcion' => $d->descripcion,
                'cantidad' => $d->cantidad,

                // Valores SIN IGV
                'valor_unitario' => round($d->precio_unitario / 1.18, 2),
                'subtotal' => round($d->subtotal / 1.18, 2),

                // Valores CON IGV
                'precio_unitario' => $d->precio_unitario,
                'igv' => $d->igv,
                'total' => $d->subtotal + $d->igv,

                'tipo_de_igv' => 1, // Gravado
                'anticipo_regularizacion' => false,
            ];
        })->toArray();
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

    
}
