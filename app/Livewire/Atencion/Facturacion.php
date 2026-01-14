<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\Cliente;
use App\Models\Comprobante;
use App\Models\ComprobanteDetalle;
use App\Services\NubeFactService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Facturacion extends Component
{

    public Atencion $atencion;
    public ?Comprobante $comprobante = null;
    public $tipo_comprobante = 'TICKET';
    public $con_igv = true;
    public function mount($id_atencion)
    {
        $this->atencion = Atencion::with([
            'servicios',
            'medicamentos'
        ])->findOrFail($id_atencion);

        $this->comprobante = Comprobante::where('id_atencion', $id_atencion)
            ->with('detalles')
            ->first();
    }

    public function crearBorrador()
    {
        // 1️⃣ Evitar duplicar comprobante
        if ($this->comprobante) {
            return;
        }

        DB::transaction(function () {

            // 2️⃣ Crear comprobante BORRADOR
            $this->comprobante = Comprobante::create([
                'id_atencion'   => $this->atencion->id_atencion,
                'id_paciente'   => $this->atencion->id_paciente,
                'tipo_comprobante' => 'BOLETA',
                'serie'         => 'B001',
                'numero'        => null,
                'fecha_emision' => now(),
                'subtotal'      => 0,
                'igv'           => 0,
                'total'         => 0,
                'estado'        => 'BORRADOR',
            ]);

            // 3️⃣ SERVICIOS (precio_unitario desde atencion_servicios)
            foreach ($this->atencion->servicios as $servicio) {

                $precio = $servicio->pivot->precio_unitario;
                $cantidad = $servicio->pivot->cantidad ?? 1;

                $subtotal = $precio * $cantidad;
                $igv = round($subtotal * 0.18, 2);

                ComprobanteDetalle::create([
                    'id_comprobante' => $this->comprobante->id_comprobante,
                    'descripcion'    => $servicio->nombre_servicio,
                    'cantidad'       => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal'       => $subtotal,
                    'igv'            => $igv,
                    'unidad'         => 'NIU',
                ]);
            }

            // 4️⃣ MEDICAMENTOS (precio desde atencion_medicamentos)
            foreach ($this->atencion->medicamentos as $medicamento) {

                $precio = $medicamento->pivot->precio;
                $cantidad = $medicamento->pivot->cantidad ?? 1;

                $subtotal = $precio * $cantidad;
                $igv = round($subtotal * 0.18, 2);

                ComprobanteDetalle::create([
                    'id_comprobante' => $this->comprobante->id_comprobante,
                    'descripcion'    => $medicamento->nombre,
                    'cantidad'       => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal'       => $subtotal,
                    'igv'            => $igv,
                    'unidad'         => 'NIU',
                ]);
            }

            // 5️⃣ Recalcular totales
            $this->recalcularTotales();

            // 6️⃣ Refrescar relación
            $this->comprobante->load('detalles');
        });
    }
    public function cargarItems()
    {
        // 1️⃣ Debe existir comprobante
        if (!$this->comprobante) {
            return;
        }

        // 2️⃣ Solo si está en BORRADOR
        if ($this->comprobante->estado !== 'BORRADOR') {
            return;
        }

        DB::transaction(function () {

            // 3️⃣ Eliminar detalles anteriores
            ComprobanteDetalle::where(
                'id_comprobante',
                $this->comprobante->id_comprobante
            )->delete();

            // 4️⃣ Cargar SERVICIOS
            foreach ($this->atencion->servicios as $servicio) {

                $precio = $servicio->pivot->precio;
                $cantidad = $servicio->pivot->cantidad ?? 1;

                $subtotal = $precio * $cantidad;
                $igv = round($subtotal * 0.18, 2);

                ComprobanteDetalle::create([
                    'id_comprobante' => $this->comprobante->id_comprobante,
                    'descripcion'    => $servicio->nombre,
                    'cantidad'       => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal'       => $subtotal,
                    'igv'            => $igv,
                    'unidad'         => 'NIU',
                ]);
            }

            // 5️⃣ Cargar MEDICAMENTOS
            foreach ($this->atencion->medicamentos as $medicamento) {

                $precio     = $medicamento->pivot->precio;
                $cantidad   = $medicamento->pivot->cantidad ?? 1;

                $subtotal   = $precio * $cantidad;
                $igv        = round($subtotal * 0.18, 2);

                ComprobanteDetalle::create([
                    'id_comprobante' => $this->comprobante->id_comprobante,
                    'descripcion'    => $medicamento->nombre,
                    'cantidad'       => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal'       => $subtotal,
                    'igv'            => $igv,
                    'unidad'         => 'NIU',
                ]);
            }


            // 6️⃣ cliente

            // 6️⃣ Recalcular totales
            $this->recalcularTotales();

            // 7️⃣ Refrescar relación
            $this->comprobante->load('detalles');
        });
    }
    protected function recalcularTotales()
    {

        $detalles = $this->comprobante->detalles ?? collect();

        if ($this->con_igv) {
            $subtotal = $detalles->sum('subtotal');
            $igv = round($subtotal * 0.18, 2);
            $total = $subtotal + $igv;
        } else {
            $subtotal = $detalles->sum('subtotal');
            $igv = 0;
            $total = $subtotal;
        }

        $this->comprobante->update([
            'subtotal' => $subtotal,
            'igv' => $igv,
            'total' => $total,
            'con_igv' => $this->con_igv,
        ]);
    }

    public function actualizarTipoComprobante()
    {
        // Factura siempre con IGV
        if ($this->tipo_comprobante === 'FACTURA') {
            $this->con_igv = true;
        }

        // Guardar en comprobante si existe
        if ($this->comprobante) {
            $this->comprobante->update([
                'tipo_comprobante' => $this->tipo_comprobante,
                'con_igv' => $this->con_igv,
            ]);

            $this->recalcularTotales();
        }
    }
    public function updatedConIgv($value)
    {
        if (!$this->comprobante) return;

        $this->comprobante->update([
            'con_igv' => (bool) $value,
        ]);

        $this->recalcularTotales();
    }

    public $cliente_ruc;
    public $cliente_razon;
    public $cliente_direccion;
    public $cliente_estado;
    protected function guardarCliente()
    {
        if ($this->tipo_comprobante === 'FACTURA') {

            $cliente = Cliente::updateOrCreate(
                [
                    'tipo_documento'   => 'RUC',
                    'numero_documento' => $this->cliente_ruc,
                ],
                [
                    'razon_social' => $this->cliente_razon,
                    'direccion'    => $this->cliente_direccion,
                ]
            );

            $this->comprobante->update([
                'id_cliente' => $cliente->id_cliente
            ]);
        }

        if ($this->tipo_comprobante === 'BOLETA') {

            $cliente = Cliente::updateOrCreate(
                [
                    'tipo_documento'   => 'DNI',
                    'numero_documento' => $this->cliente_dni,
                ],
                [
                    'nombres' => $this->cliente_nombre,
                ]
            );

            $this->comprobante->update([
                'id_cliente' => $cliente->id_cliente
            ]);
        }
    }


    public function buscarRuc()
    {
        $this->validate([
            'cliente_ruc' => 'required|digits:11',
        ]);
        $data = app(NubeFactService::class)->consultarRuc($this->cliente_ruc);

        if (!$data) {
            $this->addError('cliente_ruc', 'RUC no encontrado');
            return;
        }
        if ($data['estado'] !== 'ACTIVO') {

            $this->addError('cliente_ruc', 'RUC no activo en SUNAT');
            $this->dispatch(
                'alert',
                ['type' => 'error', 'title' => 'RUC no activo en SUNAT', 'message' => 'Exito']
            );
        }
        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Cliente encontrado con éxito "' . $data['razon_social'] . '"', 'message' => 'Exito']
        );
        $this->cliente_estado = $data['razon_social'];
        $this->cliente_razon = $data['razon_social'];
        $this->cliente_direccion = $data['direccion'];
    }

    //enviara a sunat

    public function emitir()
    {
        if (!$this->comprobante) {
            $this->addError('general', 'No existe comprobante');
            return;
        }

        if ($this->comprobante->estado === 'EMITIDO') {
            $this->addError('general', 'El comprobante ya fue emitido');
            return;
        }

        DB::beginTransaction();

        try {

            // 1️⃣ Validaciones por tipo
            $this->validarAntesDeEmitir();

            // 2️⃣ Guardar / asignar cliente
            $this->guardarCliente();

            // 3️⃣ Recalcular totales (por seguridad)
            $this->recalcularTotales();

            // 4️⃣ Construir JSON para NubeFact
            $payload = $this->construirJsonNubeFact();

            // 5️⃣ Enviar a NubeFact
            $respuesta = app(NubeFactService::class)->emitir($payload);

            if (!$respuesta || !isset($respuesta['aceptada_por_sunat'])) {
                throw new \Exception('Respuesta inválida de NubeFact');
            }

            if ($respuesta['aceptada_por_sunat'] !== true) {
                throw new \Exception($respuesta['sunat_description'] ?? 'SUNAT rechazó el comprobante');
            }

            // 6️⃣ Guardar respuesta y marcar emitido
            $this->comprobante->update([
                'estado'            => 'EMITIDO',
                'serie'             => $respuesta['serie'] ?? null,
                'numero'            => $respuesta['numero'] ?? null,
                'hash'              => $respuesta['hash'] ?? null,
                'enlace_pdf'        => $respuesta['enlace'] ?? null,
                'respuesta_sunat'   => json_encode($respuesta),
            ]);

            DB::commit();

            session()->flash('success', 'Comprobante emitido correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', $e->getMessage());
        }
    }

    /**
     * Validacion de factura
     */
    protected function validarAntesDeEmitir()
    {
        if ($this->tipo_comprobante === 'FACTURA') {
            $this->validate([
                'cliente_ruc'   => 'required|digits:11',
                'cliente_razon' => 'required|min:3',
            ]);
        }
    }

    //prepara datos para nubefact
    protected function construirJsonNubeFact(): array
    {
        $cliente = $this->comprobante->cliente;

        $items = [];

        foreach ($this->comprobante->detalles as $detalle) {
            $items[] = [
                'unidad_de_medida' => $detalle->unidad,
                'codigo'           => '',
                'descripcion'      => $detalle->descripcion,
                'cantidad'         => $detalle->cantidad,
                'valor_unitario'   => $this->con_igv
                    ? round($detalle->precio_unitario / 1.18, 2)
                    : $detalle->precio_unitario,
                'precio_unitario' => $detalle->precio_unitario,
                'subtotal'        => $detalle->subtotal,
                'tipo_de_igv'     => $this->con_igv ? '1' : '8',
                'igv'             => $this->con_igv ? round($detalle->subtotal * 0.18, 2) : 0,
                'total'           => $detalle->subtotal + ($this->con_igv ? round($detalle->subtotal * 0.18, 2) : 0),
            ];
        }

        return [
            'operacion'                  => 'generar_comprobante',
            'tipo_de_comprobante'        => $this->tipo_comprobante === 'FACTURA' ? '1' : '2',
            'serie'                      => null,
            'numero'                     => null,
            'cliente_tipo_de_documento'  => $this->tipo_comprobante === 'FACTURA' ? '6' : '1',
            'cliente_numero_de_documento' => $cliente->numero_documento,
            'cliente_denominacion'       => $cliente->razon_social ?? $cliente->nombres,
            'cliente_direccion'          => $cliente->direccion ?? '',
            'moneda'                     => '1',
            'total_gravada'              => $this->con_igv ? $this->comprobante->subtotal : 0,
            'total_igv'                  => $this->comprobante->igv,
            'total'                      => $this->comprobante->total,
            'items'                      => $items,
        ];
    }

    public function render()
    {
        return view('livewire.atencion.facturacion');
    }
}
