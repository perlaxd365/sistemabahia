<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\AtencionMedicamento;
use App\Models\AtencionServicio;
use App\Models\CajaMovimiento;
use App\Models\CajaTurno;
use App\Models\Cliente;
use App\Models\Comprobante;
use App\Models\ComprobanteDetalle;
use App\Models\Pago;
use App\Services\NubeFactService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Facturacion extends Component
{

    public Atencion $atencion;
    public $comprobantes;
    public ?Comprobante $comprobanteActivo = null;
    public $tipo_comprobante = 'TICKET';
    public $con_igv = true;
    public $id_atencion;
    public $cliente;
    protected $listeners = ['recargarComprobante'];

    public function mount($id_atencion)
    {
        ///FACTURA

        // 3️⃣.1️⃣ Aplicar recargo si corresponde
        $this->id_atencion = $id_atencion;
        $this->atencion = Atencion::with([
            'servicios',
            'medicamentos'
        ])->findOrFail($id_atencion);

        $this->comprobantes = Comprobante::where('id_atencion', $id_atencion)
            ->with('detalles')
            ->get();

        $this->comprobanteActivo = $this->comprobantes->firstWhere('estado', 'BORRADOR');
        if (!$this->comprobanteActivo && $this->comprobantes->count() > 0) {
            $this->comprobanteActivo = $this->comprobantes->last();
        }

        if ($this->comprobanteActivo) {
            $this->tipo_comprobante = $this->comprobanteActivo->tipo_comprobante;
            $this->con_igv = $this->comprobanteActivo->con_igv ?? true;
            $this->tipo_pago = $this->comprobanteActivo->metodo_pago ?? 'EFECTIVO';
        }
    }

    /**
     * Se crea un borrador de comienzo 
     */
    public function crearBorrador()
    {
        $existeBorrador = Comprobante::where('id_atencion', $this->atencion->id_atencion)
            ->where('estado', 'BORRADOR')
            ->exists();

        if ($existeBorrador) {
            $this->dispatch('alert', [
                'type' => 'warning',
                'title' => 'Ya existe un borrador',
                'message' => 'Debe emitir o eliminar el borrador actual antes de crear otro.'
            ]);
            return;
        }
        $totalItems = 0;

        $totalItems += $this->atencion->servicios()->count();
        $totalItems += $this->atencion->medicamentos()->count();

        if ($totalItems <= 0) {
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Atención sin cargos',
                'message' => 'Debe agregar al menos un servicio o medicamento antes de emitir comprobante.'
            ]);
            return;
        }
        /* if (!session('id_caja_turno')) {
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Abrir turno de caja',
                'message' => 'Debe abrir turno antes de facturar.'
            ]);
            return;
        } */

        DB::transaction(function () {

            $this->comprobanteActivo = Comprobante::create([
                'id_atencion'      => $this->atencion->id_atencion,
                'id_paciente'      => $this->atencion->id_paciente,
                'tipo_comprobante' => $this->tipo_comprobante,
                'serie'            => 'T001',
                'estado'           => 'BORRADOR',
                'fecha_emision'    => now(),
                'metodo_pago'      => 'EFECTIVO',
                'recargo'          => 0,
                'total_cobrado'    => 0,
                'subtotal'         => 0,
                'igv'              => 0,
                'total'            => 0,
                'con_igv'          => 1,
                'id_caja_turno'    => session('id_caja_turno'),
            ]);

            // 🔥 CARGAR ITEMS PENDIENTES
            $this->cargarItems();
            $this->atencion->load(['servicios.servicio', 'medicamentos']);
        });
        $this->comprobantes = Comprobante::where('id_atencion', $this->atencion->id_atencion)
            ->with('detalles')
            ->get();
        $this->comprobanteActivo->load('detalles');
    }




    /**
     * Cargar items al borrador
     */
    public function cargarItems()
    {

        if (!$this->comprobanteActivo) return;

        if ($this->comprobanteActivo->estado !== 'BORRADOR') return;

        DB::transaction(function () {

            $this->comprobanteActivo->detalles()->delete();

            // 🔹 SERVICIOS NO FACTURADOS
            $servicios = AtencionServicio::with('servicio')
                ->where('id_atencion', $this->atencion->id_atencion)
                ->where(function ($q) {
                    $q->where('facturado', false)
                        ->orWhereNull('facturado');
                })
                ->get();

            foreach ($servicios as $servicio) {

                $precio   = (float) $servicio->precio_unitario;
                $cantidad = (float) $servicio->cantidad;

                if ($this->con_igv) {

                    $valorUnitario = round($precio / 1.18, 2);
                    $subtotal      = round($valorUnitario * $cantidad, 2);
                    $igv           = round($subtotal * 0.18, 2);
                } else {

                    $subtotal = round($precio * $cantidad, 2);
                    $igv = 0;
                }

                ComprobanteDetalle::create([
                    'id_comprobante'  => $this->comprobanteActivo->id_comprobante,
                    'descripcion'     => $servicio->servicio->nombre_servicio,
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal'        => $subtotal,
                    'igv'             => $igv,
                    'unidad'          => 'NIU',
                    'tipo_afectacion_igv' => $this->con_igv ? '10' : '20',
                ]);
            }

            // 🔹 MEDICAMENTOS VENTA NO FACTURADOS
            $medicamentos = AtencionMedicamento::with('medicamento')
                ->where('id_atencion', $this->atencion->id_atencion)
                ->whereIn('tipo', ['VENTA', 'RECETA'])
                ->where(function ($q) {
                    $q->where('facturado', false)
                        ->orWhereNull('facturado');
                })
                ->get();

            foreach ($medicamentos as $med) {

                $precio   = (float) $med->precio;
                $cantidad = (float) $med->cantidad;

                if ($this->con_igv) {

                    $valorUnitario = round($precio / 1.18, 2);
                    $subtotal      = round($valorUnitario * $cantidad, 2);
                    $igv           = round($subtotal * 0.18, 2);
                } else {

                    $subtotal = round($precio * $cantidad, 2);
                    $igv = 0;
                }

                ComprobanteDetalle::create([
                    'id_comprobante'  => $this->comprobanteActivo->id_comprobante,
                    'descripcion'     => $med->medicamento->nombre,
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal'        => $subtotal,
                    'igv'             => $igv,
                    'unidad'          => 'NIU',
                    'tipo_afectacion_igv' => $this->con_igv ? '10' : '20',
                ]);
            }
            $this->comprobanteActivo->load('detalles');

            $this->recalcularTotales();
        });
    }
    protected function recalcularTotalesGenerales()
    {
        if (!$this->comprobanteActivo) return;

        $subtotal = round(
            $this->comprobanteActivo->detalles()->sum('subtotal'),
            2
        );

        $igv = round(
            $this->comprobanteActivo->detalles()->sum('igv'),
            2
        );

        $total = round($subtotal + $igv, 2);

        $this->comprobanteActivo->update([
            'subtotal' => $subtotal,
            'igv'      => $igv,
            'total'    => $total,
        ]);

        $this->recalcularTotalConRecargo();

        $this->comprobanteActivo->refresh();
    }




    /**
     * Actualizar el tipo de compronamte
     */
    public function actualizarTipoComprobante()
    {
        $this->numero_documento = "";
        $this->cliente_nombre = "";
        $this->cliente_direccion = "";
        $this->cliente_razon = "";
        // Factura siempre con IGV
        if ($this->tipo_comprobante === 'FACTURA') {
            $this->con_igv = true;
        }

        // Guardar en comprobante si existe
        if ($this->comprobanteActivo) {
            $this->comprobanteActivo->update([
                'tipo_comprobante' => $this->tipo_comprobante,
                'con_igv' => $this->con_igv,
            ]);

            $this->recalcularTotales();
        }
    }

    /***
     * calcular igv
     */
    public function updatedConIgv($value)
    {
        if (!$this->comprobanteActivo) return;

        $this->comprobanteActivo->update([
            'con_igv' => (bool) $value,
        ]);

        $this->recalcularTotales();
    }


    /*
    *Guardamos cliente
     * 
     */
    public $numero_documento;
    public $cliente_nombre;
    public $cliente_razon;
    public $cliente_direccion;
    public $cliente_estado;
    protected function guardarCliente()
    {
        if ($this->tipo_comprobante === 'FACTURA') {


            $cliente = Cliente::updateOrCreate(
                [
                    'tipo_documento'   => 'RUC',
                    'numero_documento' => $this->numero_documento,
                ],
                [
                    'razon_social' => $this->cliente_razon,
                    'direccion'    => $this->cliente_direccion,
                ]
            );

            $this->comprobanteActivo->update([
                'id_cliente' => $cliente->id_cliente
            ]);
        }

        if ($this->tipo_comprobante === 'BOLETA') {

            $cliente = Cliente::updateOrCreate(
                [
                    'tipo_documento'   => 'DNI',
                    'numero_documento' => $this->numero_documento,
                ],
                [
                    'nombres' => $this->cliente_nombre,
                ]
            );

            $this->comprobanteActivo->update([
                'id_cliente' => $cliente->id_cliente
            ]);
        }
    }

    /**
     * Buscamos ruc
     */
    public function buscarRuc()
    {
        $this->validate([
            'numero_documento' => 'required|digits:11',
        ]);
        $data = app(NubeFactService::class)->consultarRuc($this->numero_documento);

        if (!$data) {
            $this->addError('numero_documento', 'RUC no encontrado');
            return;
        }
        if ($data['estado'] !== 'ACTIVO') {

            $this->addError('numero_documento', 'RUC no activo en SUNAT');
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

    /**
     * Buscar Dni
     */
    public function buscarDni()
    {

        $this->validate([
            'numero_documento' => 'required|digits:8',
        ]);
        $data = app(NubeFactService::class)->consultarDni($this->numero_documento);

        if (!$data) {
            $this->addError('numero_documento', 'DNI no encontrado');
            return;
        }
        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Cliente encontrado con éxito "' . $data['nombres'] . '"', 'message' => 'Exito']
        );
        $this->cliente_nombre = $data['nombres'];
    }

    //enviara a sunat
    public $tipo_pago = 'EFECTIVO';

    /**Tipo de pago */
    public $recargo = 0;
    public $porcentaje_recargo_tarjeta = 5;
    public $total_cobrado = 0;
    protected function recalcularTotalConRecargo()
    {
        if (!$this->comprobanteActivo) {
            return;
        }

        $this->comprobanteActivo->refresh();

        $baseTotal = $this->comprobanteActivo->subtotal + $this->comprobanteActivo->igv;

        if ($this->tipo_pago === 'TARJETA') {
            $this->recargo = round(
                $baseTotal * ($this->porcentaje_recargo_tarjeta / 100),
                2
            );
        } else {
            $this->recargo = 0;
        }

        $this->comprobanteActivo->total_cobrado = round($baseTotal + $this->recargo, 2);
        $this->comprobanteActivo->save();
    }

    /**Tipo de pago */
    public function updatedTipoPago($value)
    {
        $this->recalcularTotales();
    }










    /**
     * EMITIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIR
     * 
     */
    public function emitir()
    {
        //verificar turno y pago
        $cajaTurno = CajaTurno::turnoAbierto()->first();

        if (!$cajaTurno) {
            $this->dispatch(
                'alert',
                ['type' => 'error', 'title' => 'No hay un turno de caja abierto', 'message' => 'Error']
            );
            return;
        }

        //PAGO 
        if ($this->comprobanteActivo->tipo_comprobante === "TICKET") {
            # code...
            $this->registrarTicket();
            // procede a guardar pago
            $pago = Pago::create([
                'id_comprobante' => $this->comprobanteActivo->id_comprobante,
                'id_atencion'    => $this->comprobanteActivo->atencion->id_atencion,
                'id_caja_turno'  => $cajaTurno->id_caja_turno, // si está abierto
                'tipo_pago'      => $this->tipo_pago,
                'monto'          => $this->comprobanteActivo->total_cobrado,
                'fecha_pago'     => now(),
                'user_id'        => Auth()->id(),
            ]);

            CajaMovimiento::create([
                'id_caja_turno' => $cajaTurno->id_caja_turno,
                'tipo' => 'INGRESO',
                'descripcion' =>  ' Pago de :' . $this->cliente_nombre . '|  DNI :' . $this->numero_documento . ' | Atención: ' . $this->atencion->id_atencion,
                'monto'         => $this->comprobanteActivo->total_cobrado, // 👈 correcto
                'id_referencia' => $pago->id_pago,
                'tabla_referencia' => 'pagos',
                'id_usuario' => Auth::id(),
                'responsable' => auth()->user()->name,
            ]);
            $atencion = Atencion::findOrFail($this->comprobanteActivo->atencion->id_atencion);

            $this->dispatch(
                'alert',
                ['type' => 'success', 'title' => 'Ticket registrado correctamente', 'message' => 'Exito']
            );

            AtencionServicio::where('id_atencion', $this->atencion->id_atencion)
                ->where('facturado', false)
                ->update(['facturado' => true]);

            AtencionMedicamento::where('id_atencion', $this->atencion->id_atencion)
                ->where('facturado', false)
                ->whereIn('tipo', ['VENTA', 'RECETA'])
                ->update(['facturado' => true]);

            return;
        }

        DB::beginTransaction();
        try {

            if (!$this->comprobanteActivo) {
                $this->dispatch(
                    'alert',
                    ['type' => 'error', 'title' => 'No existe comprobante', 'message' => 'Error']
                );
                return;
            }

            if ($this->comprobanteActivo->estado === 'EMITIDO') {
                $this->dispatch(
                    'alert',
                    ['type' => 'error', 'title' => 'El comprobante ya fue emitido', 'message' => 'Error']
                );
                return;
            }


            // 1️⃣ Validaciones
            $this->validarAntesDeEmitir();

            // 2️⃣ Cliente
            $this->guardarCliente();

            // 3️⃣ Totales
            $this->recalcularTotales();
            // 3️⃣.1️⃣ Aplicar recargo si corresponde
            $this->recalcularTotalConRecargo();

            // 4️⃣ Enviar a NubeFact
            $respuesta = app(NubeFactService::class)
                ->emitir($this->comprobanteActivo);
            // procede a guardar pago
            $pago = Pago::create([
                'id_comprobante' => $this->comprobanteActivo->id_comprobante,
                'id_atencion'    => $this->comprobanteActivo->atencion->id_atencion,
                'id_caja_turno'  => $cajaTurno->id_caja_turno, // si está abierto
                'tipo_pago'      => $this->tipo_pago,
                'monto'          => $this->comprobanteActivo->total_cobrado, // 👈 correcto
                'fecha_pago'     => now(),
                'user_id'        => Auth()->id(),
            ]);

            CajaMovimiento::create([
                'id_caja_turno' => $cajaTurno->id_caja_turno,
                'tipo' => 'INGRESO',
                'descripcion' =>  ' Pago de :' . $this->cliente_nombre . '|  DNI :' . $this->numero_documento . ' | Atención: ' . $this->atencion->id_atencion,
                'monto'         => $this->comprobanteActivo->total_cobrado, // 👈 correcto
                'tabla_referencia' => 'caja_chicas',
                'id_referencia' => $pago->id_pago,
                'id_usuario' => Auth::id(),
                'responsable' => auth()->user()->name,
            ]);

            AtencionServicio::where('id_atencion', $this->atencion->id_atencion)
                ->where('facturado', false)
                ->update(['facturado' => true]);

            AtencionMedicamento::where('id_atencion', $this->atencion->id_atencion)
                ->where('facturado', false)
                ->whereIn('tipo', ['VENTA', 'RECETA'])
                ->update(['facturado' => true]);
            $atencion = Atencion::findOrFail($this->comprobanteActivo->atencion->id_atencion);


            if (!$respuesta || !is_array($respuesta)) {
                throw new \Exception('Respuesta vacía o inválida de NubeFact');
            }

            // ❌ Error técnico
            if (!empty($respuesta['errors'])) {
                throw new \Exception(
                    is_array($respuesta['errors'])
                        ? implode(', ', $respuesta['errors'])
                        : $respuesta['errors']
                );
            }

            // 🔑 Datos base (SIEMPRE guardar)
            $dataBase = [
                'serie'           => $respuesta['serie'] ?? null,
                'numero'          => $respuesta['numero'] ?? null,
                'hash'            => $respuesta['codigo_hash'] ?? null,
                'enlace_pdf'      => $respuesta['enlace_del_pdf'] ?? null,
                'respuesta_sunat' => json_encode($respuesta),
            ];

            // ⏳ PENDIENTE CDR (CASO BOLETA)
            if (
                array_key_exists('aceptada_por_sunat', $respuesta)
                && $respuesta['aceptada_por_sunat'] === false
                && empty($respuesta['cdr_zip_base64'])
                && empty($respuesta['sunat_responsecode'])
                && empty($respuesta['sunat_soap_error'])
            ) {

                $this->comprobanteActivo->update(
                    array_merge($dataBase, [
                        'estado' => 'EMITIDO'
                    ])
                );

                DB::commit();

                $this->dispatch('alert', [
                    'type' => 'success',
                    'title' => 'Boleta enviada',
                    'message' => 'SUNAT aún no envía CDR (PENDIENTE)'
                ]);

                return;
            }

            // ❌ RECHAZADO REAL SUNAT
            if (
                $respuesta['aceptada_por_sunat'] === false &&
                (
                    !empty($respuesta['sunat_responsecode']) ||
                    !empty($respuesta['sunat_soap_error'])
                )
            ) {

                $this->comprobanteActivo->update(
                    array_merge($dataBase, [
                        'estado' => 'RECHAZADO'
                    ])
                );

                DB::commit();

                throw new \Exception(
                    $respuesta['sunat_description']
                        ?? $respuesta['sunat_soap_error']
                        ?? 'SUNAT rechazó el comprobante'
                );
            }

            // ✅ ACEPTADO
            if ($respuesta['aceptada_por_sunat'] === true) {

                $this->comprobanteActivo->update(
                    array_merge($dataBase, [
                        'estado' => 'EMITIDO'
                    ])
                );

                DB::commit();

                $this->dispatch('alert', [
                    'type' => 'success',
                    'title' => 'Comprobante emitido',
                    'message' => 'Aceptado por SUNAT'
                ]);

                return;
            }

            throw new \Exception('Estado SUNAT no reconocido');
        } catch (\Exception $e) {

            DB::rollBack();
            dd($e->getMessage());
        }
    }



    protected function siguienteNumeroTicket(): int
    {
        return (Comprobante::where('tipo_comprobante', 'TICKET')->max('numero') ?? 0) + 1;
    }

    protected function recalcularTotales()
    {
        if (!$this->comprobanteActivo) return;

        // 🔥 Siempre consulta real a BD
        $subtotal = round(
            $this->comprobanteActivo->detalles()->sum('subtotal'),
            2
        );

        $igv = round(
            $this->comprobanteActivo->detalles()->sum('igv'),
            2
        );

        $total = round($subtotal + $igv, 2);

        $this->comprobanteActivo->update([
            'subtotal' => $subtotal,
            'igv'      => $igv,
            'total'    => $total,
        ]);

        // 🔥 RECARGO TARJETA
        $baseTotal = $subtotal + $igv;

        if ($this->tipo_pago === 'TARJETA') {
            $this->recargo = round(
                $baseTotal * ($this->porcentaje_recargo_tarjeta / 100),
                2
            );
        } else {
            $this->recargo = 0;
        }

        $this->comprobanteActivo->update([
            'metodo_pago'  => $this->tipo_pago,
            'recargo'      => $this->recargo,
            'total_cobrado' => round($baseTotal + $this->recargo, 2),
            'con_igv'      => $this->con_igv,
        ]);

        // 🔥 SINCRONIZA LIVEWIRE
        $this->comprobanteActivo->refresh();
    }

    public function registrarTicket()
    {
        if (!$this->comprobanteActivo) {
            return;
        }


        // 1️⃣ Actualizar datos base (RESPETANDO con_igv)
        $this->comprobanteActivo->update([
            'tipo_comprobante' => $this->tipo_comprobante,
            'serie'            => 'TCK',
            "metodo_pago"   => $this->tipo_pago,
            "recargo"     => $this->recargo,
            "total_cobrado" => $this->total_cobrado,
            'numero'           => $this->comprobanteActivo->numero
                ?? $this->siguienteNumeroTicket(),
            'fecha_emision'    => now(),
            'con_igv'          => (bool) $this->con_igv, // ✅ CLAVE
        ]);

        // 2️⃣ Limpiar detalles
        $this->comprobanteActivo->detalles()->delete();
        $servicios = AtencionServicio::where('id_atencion', $this->id_atencion)
            ->where('estado', 1)
            ->where('facturado', 0)
            ->with('servicio') // 🔥 MUY IMPORTANTE
            ->get();
        // 3️⃣ Servicios

        foreach ($servicios as $servicio) {

            $precio   = (float) $servicio->precio_unitario;
            $cantidad = (float) $servicio->cantidad;
            $subtotal = round($precio * $cantidad, 2);
            ComprobanteDetalle::create([
                'id_comprobante'  => $this->comprobanteActivo->id_comprobante,
                'descripcion'     => $servicio->servicio->nombre_servicio,
                'cantidad'        => $cantidad,
                'precio_unitario' => $precio,
                'subtotal'        => $subtotal,
                'igv'             => 0,
                'unidad'          => 'NIU',
            ]);
        }

        $medicamentos = AtencionMedicamento::where('id_atencion', $this->id_atencion)
            ->where('facturado', 0)
            ->with('medicamento')
            ->get();

        foreach ($medicamentos as $med) {

            $precio   = (float) $med->precio;   // 🔥 CORREGIDO
            $cantidad = (float) $med->cantidad;
            $subtotal = round($precio * $cantidad, 2);

            ComprobanteDetalle::create([
                'id_comprobante'  => $this->comprobanteActivo->id_comprobante,
                'descripcion'     => $med->medicamento->nombre,
                'cantidad'        => $cantidad,
                'precio_unitario' => $precio,
                'subtotal'        => $subtotal,
                'igv'             => 0,
                'unidad'          => 'NIU',
            ]);
        }
        // 5️⃣ Recalcular totales (USA con_igv REAL)
        $this->recalcularTotales();

        // 6️⃣ Emitir
        $this->comprobanteActivo->update([
            'estado' => 'EMITIDO',
        ]);

        $this->comprobanteActivo->load('detalles');
    }

    /**
     * Validacion de factura
     */
    protected function validarAntesDeEmitir()
    {
        if ($this->tipo_comprobante === 'FACTURA') {
            $this->validate([
                'tipo_comprobante' => 'required',
                'numero_documento' => 'required|digits:11',
            ]);
        } elseif ($this->tipo_comprobante === 'BOLETA') {

            $this->validate([
                'tipo_comprobante' => 'required',
                'numero_documento' => 'required|digits:8',
                'cliente_nombre' => 'required',
            ]);
        }
    }

    //prepara datos para nubefact
    protected function construirJsonNubeFact(): array
    {
        $cliente = $this->comprobanteActivo->cliente;

        $items = [];

        foreach ($this->comprobanteActivo->detalles as $detalle) {
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
            'total_gravada'              => $this->con_igv ? $this->comprobanteActivo->subtotal : 0,
            'total_igv'                  => $this->comprobanteActivo->igv,
            'total'                      => $this->comprobanteActivo->total_cobrado,
            'estado'                     => $this->comprobanteActivo->estado,
            'items'                      => $items,
        ];
    }

    public function getPuedeBuscarProperty(): bool
    {
        if ($this->tipo_comprobante === 'FACTURA') {
            return strlen($this->numero_documento ?? '') === 11;
        }

        if ($this->tipo_comprobante === 'BOLETA') {
            return strlen($this->numero_documento ?? '') === 8;
        }

        return false;
    }


    public function anularComprobanteInterno()
    {
        DB::beginTransaction();

        try {

            $comprobante = $this->comprobanteActivo;

            // 1️⃣ Validar que sea TICKET
            if ($comprobante->tipo_comprobante !== 'TICKET') {

                $this->dispatch('alert', [
                    'type' => 'error',
                    'title' => 'Operación no permitida',
                    'message' => 'Solo se pueden anular TICKETS internos.'
                ]);

                return;
            }

            // 2️⃣ Validar estado
            if ($comprobante->estado === 'ANULADO') {

                $this->dispatch('alert', [
                    'type' => 'warning',
                    'title' => 'Comprobante ya anulado',
                    'message' => 'Este comprobante ya se encuentra en estado ANULADO.'
                ]);

                return;
            }

            // 3️⃣ Validar turno de caja


            // 4️⃣ Validar que el turno esté abierto (recomendado)
            $turno = $comprobante->cajaTurno;

            if ($turno && $turno->estado === 'CERRADO') {

                $this->dispatch('alert', [
                    'type' => 'error',
                    'title' => 'Turno cerrado',
                    'message' => 'No se puede anular porque el turno de caja está cerrado.'
                ]);

                return;
            }

            // 5️⃣ Cambiar estado
            $comprobante->estado = 'ANULADO';
            $comprobante->save();
            $turno = CajaTurno::where('estado', 'abierto')->first();
            // 2️⃣ Validar estado
            if (!$turno) {

                $this->dispatch('alert', [
                    'type' => 'warning',
                    'title' => 'No existe turno abierto para anular',
                    'message' => 'Por favor iniciar turno.'
                ]);

                return;
            }
            // 6️⃣ Crear movimiento inverso en caja
            CajaMovimiento::create([
                'id_usuario'      => auth()->id(),
                'id_referencia'   => $comprobante->id_comprobante,
                'tabla_referencia' => 'comprobantes',
                'tipo'            => 'EGRESO',
                'descripcion'     => 'ANULACIÓN TICKET ' . $comprobante->serie . '-' . $comprobante->numero,
                'monto'           => $comprobante->total,
                'responsable'     => auth()->user()->name,

                'id_caja_turno'    => $turno->id_caja_turno
            ]);

            DB::commit();

            $this->dispatch('alert', [
                'type' => 'success',
                'title' => 'Ticket anulado',
                'message' => 'El ticket fue anulado y la caja fue revertida correctamente.'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Error del sistema',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function eliminarComprobante()
    {
        if (!$this->comprobanteActivo) {
            return;
        }

        if ($this->comprobanteActivo->estado !== 'BORRADOR') {

            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Acción no permitida',
                'message' => 'Solo se pueden eliminar comprobantes en estado BORRADOR'
            ]);

            return;
        }

        if ($this->comprobanteActivo->pagos()->exists()) {

            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Acción bloqueada',
                'message' => 'No se puede eliminar un comprobante que ya tiene pagos registrados'
            ]);

            return;
        }

        DB::transaction(function () {

            // Eliminar detalles primero
            $this->comprobanteActivo->detalles()->delete();

            // Eliminar comprobante
            $this->comprobanteActivo->delete();
        });


        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Comprobante eliminado',
            'message' => 'El borrador fue eliminado correctamente'
        ]);

        $this->comprobantes = Comprobante::where('id_atencion', $this->atencion->id_atencion)
            ->with('detalles')
            ->get();

        $this->comprobanteActivo = $this->comprobantes->last();
    }
    public function seleccionarComprobante($id)
    {
        $this->comprobanteActivo = Comprobante::with('detalles')->find($id);
    }

    public function crearComprobanteAdicional()
    { // 🔒 No permitir más de un borrador
        if (Comprobante::where('id_atencion', $this->atencion->id_atencion)
            ->where('estado', 'BORRADOR')
            ->exists()
        ) {

            $this->dispatch('alert', [
                'type' => 'warning',
                'title' => 'Existe borrador activo',
                'message' => 'Debe emitir o eliminar el borrador actual antes de crear otro.'
            ]);

            return;
        }
        if (!$this->atencion->tienePendienteFacturar()) {
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Atención sin cargos',
                'message' => 'Debe agregar al menos un servicio o medicamento antes de emitir comprobante.'
            ]);
            return;
        }

        if ($this->comprobanteActivo && $this->comprobanteActivo->estado === 'BORRADOR') {
            $this->dispatch('alert', [
                'type' => 'warning',
                'title' => 'Existe borrador activo',
                'message' => 'Debe emitir o eliminar el borrador actual.'
            ]);
            return;
        }

        DB::transaction(function () {

            $nuevo = Comprobante::create([
                'id_atencion'      => $this->atencion->id_atencion,
                'id_paciente'      => $this->atencion->id_paciente,
                'tipo_comprobante' => $this->tipo_comprobante,
                'serie'            => 'T001',
                'estado'           => 'BORRADOR',
                'fecha_emision'    => now(),
                'metodo_pago'      => 'EFECTIVO',
                'recargo'          => 0,
                'total_cobrado'    => 0,
                'subtotal'         => 0,
                'igv'              => 0,
                'total'            => 0,
                'con_igv'          => 1,
                'id_caja_turno'    => session('id_caja_turno'),
            ]);

            // 🔹 SERVICIOS
            $servicios = AtencionServicio::with('servicio')
                ->where('id_atencion', $this->atencion->id_atencion)
                ->where('estado', true)
                ->where('facturado', false)
                ->get();

            foreach ($servicios as $servicio) {

                $precio   = (float) $servicio->precio_unitario;
                $cantidad = (float) $servicio->cantidad;

                $valorUnitario = round($precio / 1.18, 2);
                $subtotal      = round($valorUnitario * $cantidad, 2);
                $igv           = round($subtotal * 0.18, 2);

                $nuevo->detalles()->create([
                    'descripcion'     => $servicio->servicio->nombre_servicio,
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal'        => $subtotal,
                    'igv'             => $igv,
                    'unidad'          => 'NIU',
                    'tipo_afectacion_igv' => '10',
                ]);
            }

            // 🔹 MEDICAMENTOS
            $medicamentos = AtencionMedicamento::with('medicamento')
                ->where('id_atencion', $this->atencion->id_atencion)
                ->where('facturado', false)
                ->get();

            foreach ($medicamentos as $medicamento) {

                $precio   = (float) $medicamento->precio;
                $cantidad = (float) $medicamento->cantidad;

                $valorUnitario = round($precio / 1.18, 2);
                $subtotal      = round($valorUnitario * $cantidad, 2);
                $igv           = round($subtotal * 0.18, 2);

                $nuevo->detalles()->create([
                    'descripcion'     => $medicamento->medicamento->nombre, // 🔥 corregido
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal'        => $subtotal,
                    'igv'             => $igv,
                    'unidad'          => 'NIU',
                    'tipo_afectacion_igv' => '10',
                ]);
            }

            // 🔥 AHORA SÍ CALCULA TOTALES
            $this->comprobanteActivo = $nuevo;
            $this->recalcularTotales();
        });

        $this->comprobantes = Comprobante::where(
            'id_atencion',
            $this->atencion->id_atencion
        )
            ->with('detalles')
            ->orderByDesc('id_comprobante')
            ->get();
    }

    public function render()
    {
        return view('livewire.atencion.facturacion');
    }
}
