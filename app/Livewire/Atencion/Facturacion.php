<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
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
    public ?Comprobante $comprobante = null;
    public $tipo_comprobante = 'TICKET';
    public $con_igv = true;
    public function mount($id_atencion)
    {
        ///FACTURA

        // 3️⃣.1️⃣ Aplicar recargo si corresponde
        $this->atencion = Atencion::with([
            'servicios',
            'medicamentos'
        ])->findOrFail($id_atencion);

        $this->comprobante = Comprobante::where('id_atencion', $id_atencion)
            ->with('detalles')
            ->first();
        // 3️⃣.1️⃣ Aplicar recargo si corresponde
    }

    /**
     * Se crea un borrador de comienzo 
     */
    public function crearBorrador()
    {
        // 1️⃣ Evitar duplicar comprobante
        if ($this->comprobante) {
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

        DB::transaction(function () {

            // 2️⃣ Crear comprobante BORRADOR
            $this->comprobante = Comprobante::create([
                'id_atencion'      => $this->atencion->id_atencion,
                'id_paciente'      => $this->atencion->id_paciente,
                'tipo_comprobante' => 'TICKET',
                'serie'            => 'T001',
                "metodo_pago"       => "EFECTIVO",
                "recargo"           => 0,
                "total_cobrado"     => 0,
                'numero'           => null,
                'fecha_emision'    => now(),
                'subtotal'         => 0, // gravada
                'igv'              => 0,
                'total'            => 0,
                'id_caja_turno'    => session('id_caja_turno'),
                'estado'           => 'BORRADOR',
            ]);

            // 3️⃣ SERVICIOS
            foreach ($this->atencion->servicios as $servicio) {

                $precioConIgv = (float) $servicio->pivot->precio_unitario;
                $cantidad     = (float) ($servicio->pivot->cantidad ?? 1);

                // 🔴 CÁLCULO SUNAT
                $valorUnitario = round($precioConIgv / 1.18, 2);
                $subtotal      = round($valorUnitario * $cantidad, 2); // SIN IGV
                $igv           = round($subtotal * 0.18, 2);

                ComprobanteDetalle::create([
                    'id_comprobante'  => $this->comprobante->id_comprobante,
                    'descripcion'     => $servicio->nombre_servicio,
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $precioConIgv, // CON IGV
                    'subtotal'        => $subtotal,      // SIN IGV
                    'igv'             => $igv,
                    'unidad'          => 'NIU',
                    'tipo_afectacion_igv' => '10', // Gravado
                ]);
            }

            // 4️⃣ MEDICAMENTOS
            foreach ($this->atencion->medicamentos as $medicamento) {

                $precioConIgv = (float) $medicamento->pivot->precio;
                $cantidad     = (float) ($medicamento->pivot->cantidad ?? 1);

                // 🔴 CÁLCULO SUNAT
                $valorUnitario = round($precioConIgv / 1.18, 2);
                $subtotal      = round($valorUnitario * $cantidad, 2);
                $igv           = round($subtotal * 0.18, 2);

                ComprobanteDetalle::create([
                    'id_comprobante'  => $this->comprobante->id_comprobante,
                    'descripcion'     => $medicamento->nombre,
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $precioConIgv,
                    'subtotal'        => $subtotal,
                    'igv'             => $igv,
                    'unidad'          => 'NIU',
                    'tipo_afectacion_igv' => '10',
                ]);
            }

            // 5️⃣ Recalcular totales SUNAT
            $this->recalcularTotalesSunat();

            // 6️⃣ Refrescar relación
            $this->comprobante->load('detalles');
        });
    }


    /**
     * Calcular totoales
     */
    protected function recalcularTotalesSunat()
    {
        $this->comprobante->subtotal = round(
            $this->comprobante->detalles->sum('subtotal'),
            2
        );

        $this->comprobante->igv = round(
            $this->comprobante->detalles->sum('igv'),
            2
        );

        $this->comprobante->total = round(
            $this->comprobante->subtotal + $this->comprobante->igv,
            2
        );

        $this->comprobante->save();
    }


    /**
     * Cargar items al borrador
     */
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


        if (!session('id_caja_turno')) {
            $this->dispatch(
                'alert',
                ['type' => 'error', 'title' => 'POR FAVOR ABRIR TURNO DE CAJA', 'message' => 'Error de caja']
            );
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

            // 3️⃣.1️⃣ Aplicar recargo si corresponde
            $this->recalcularTotalConRecargo();

            // 7️⃣ Refrescar relación
            $this->comprobante->load('detalles');
        });
    }

    /**
     * calcular totlaes
     */
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

            "metodo_pago"   => $this->tipo_pago,
            "recargo"     => $this->recargo,
            "total_cobrado" => $this->total_cobrado,
            'subtotal' => $subtotal,
            'igv' => $igv,
            'total' => $total,
            'con_igv' => $this->con_igv,
        ]);
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
        if ($this->comprobante) {
            $this->comprobante->update([
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
        if (!$this->comprobante) return;

        $this->comprobante->update([
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

            $this->comprobante->update([
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

            $this->comprobante->update([
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
        if (!$this->comprobante) {
            return;
        }

        // Total base SUNAT (sin recargo)
        $baseTotal = $this->comprobante->subtotal + $this->comprobante->igv;

        if ($this->tipo_pago === 'TARJETA') {
            $this->recargo = round(
                $baseTotal * ($this->porcentaje_recargo_tarjeta / 100),
                2
            );
        } else {
            $this->recargo = 0;
        }

        // Total final a cobrar (NO afecta SUNAT)
        $this->comprobante->total_cobrado = round($baseTotal + $this->recargo, 2);
        $this->comprobante->save();
    }

    /**Tipo de pago */
    public function updatedTipoPago($value)
    {
        $this->recalcularTotalConRecargo();
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
        if ($this->tipo_comprobante === "TICKET") {
            # code...
            $this->registrarTicket();
            // procede a guardar pago
            $pago = Pago::create([
                'id_comprobante' => $this->comprobante->id_comprobante,
                'id_atencion'    => $this->comprobante->atencion->id_atencion,
                'id_caja_turno'  => $cajaTurno->id_caja_turno, // si está abierto
                'tipo_pago'      => $this->tipo_pago,
                'monto'          => $this->comprobante->total_cobrado,
                'fecha_pago'     => now(),
                'user_id'        => Auth()->id(),
            ]);

            CajaMovimiento::create([
                'id_caja_turno' => $cajaTurno->id_caja_turno,
                'tipo' => 'INGRESO',
                'descripcion' => 'Pago comprobante ' . $this->comprobante->id_comprobante,
                'monto'         => $this->comprobante->total_cobrado, // 👈 correcto
                'id_referencia' => $pago->id_pago,
                'tabla_referencia' => 'caja_chicas',
                'id_usuario' => Auth::id(),
                'responsable' => auth()->user()->name,
            ]);
            $atencion = Atencion::findOrFail($this->comprobante->atencion->id_atencion);

            $this->dispatch(
                'alert',
                ['type' => 'success', 'title' => 'Ticket registrado correctamente', 'message' => 'Exito']
            );
            return;
        }


        DB::beginTransaction();
        try {

            if (!$this->comprobante) {
                $this->dispatch(
                    'alert',
                    ['type' => 'error', 'title' => 'No existe comprobante', 'message' => 'Error']
                );
                return;
            }

            if ($this->comprobante->estado === 'EMITIDO') {
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
                ->emitir($this->comprobante);
            // procede a guardar pago
            $pago = Pago::create([
                'id_comprobante' => $this->comprobante->id_comprobante,
                'id_atencion'    => $this->comprobante->atencion->id_atencion,
                'id_caja_turno'  => $cajaTurno->id_caja_turno, // si está abierto
                'tipo_pago'      => $this->tipo_pago,
                'monto'          => $this->comprobante->total_cobrado, // 👈 correcto
                'fecha_pago'     => now(),
                'user_id'        => Auth()->id(),
            ]);

            CajaMovimiento::create([
                'id_caja_turno' => $cajaTurno->id_caja_turno,
                'tipo' => 'INGRESO',
                'descripcion' => 'Pago comprobante ' . $this->comprobante->id_comprobante,
                'monto'         => $this->comprobante->total_cobrado, // 👈 correcto
                'tabla_referencia' => 'caja_chicas',
                'id_referencia' => $pago->id_pago,
                'id_usuario' => Auth::id(),
                'responsable' => auth()->user()->name,
            ]);

            $atencion = Atencion::findOrFail($this->comprobante->atencion->id_atencion);


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

                $this->comprobante->update(
                    array_merge($dataBase, [
                        'estado' => 'PENDIENTE'
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

                $this->comprobante->update(
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

                $this->comprobante->update(
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

    protected function recalcularTotalesTicket()
    {
        if ($this->comprobante->estado === 'EMITIDO') {
            return;
        }

        $total = round($this->comprobante->detalles->sum('subtotal'), 2);

        if ($this->comprobante->con_igv) {

            $base = round($total / 1.18, 2);
            $igv  = round($total - $base, 2);

            $this->comprobante->update([
                'subtotal' => $base,
                'igv'      => $igv,
                'total'    => $total,
            ]);
        } else {

            $this->comprobante->update([
                'subtotal' => $total,
                'igv'      => 0,
                'total'    => $total,
            ]);
        }
    }

    public function registrarTicket()
    {
        if (!$this->comprobante) {
            return;
        }


        // 1️⃣ Actualizar datos base (RESPETANDO con_igv)
        $this->comprobante->update([
            'tipo_comprobante' => 'TICKET',
            'serie'            => 'TCK',
            "metodo_pago"   => $this->tipo_pago,
            "recargo"     => $this->recargo,
            "total_cobrado" => $this->total_cobrado,
            'numero'           => $this->comprobante->numero
                ?? $this->siguienteNumeroTicket(),
            'fecha_emision'    => now(),
            'con_igv'          => (bool) $this->con_igv, // ✅ CLAVE
        ]);

        // 2️⃣ Limpiar detalles
        $this->comprobante->detalles()->delete();

        // 3️⃣ Servicios
        foreach ($this->atencion->servicios as $servicio) {

            $precio   = (float) $servicio->pivot->precio_unitario;
            $cantidad = (float) ($servicio->pivot->cantidad ?? 1);
            $subtotal = round($precio * $cantidad, 2);

            ComprobanteDetalle::create([
                'id_comprobante'  => $this->comprobante->id_comprobante,
                'descripcion'     => $servicio->nombre_servicio,
                'cantidad'        => $cantidad,
                'precio_unitario' => $precio,
                'subtotal'        => $subtotal,
                'igv'             => 0,
                'unidad'          => 'NIU',
            ]);
        }

        // 4️⃣ Medicamentos
        foreach ($this->atencion->medicamentos as $med) {

            $precio   = (float) $med->pivot->precio;
            $cantidad = (float) ($med->pivot->cantidad ?? 1);
            $subtotal = round($precio * $cantidad, 2);

            ComprobanteDetalle::create([
                'id_comprobante'  => $this->comprobante->id_comprobante,
                'descripcion'     => $med->nombre,
                'cantidad'        => $cantidad,
                'precio_unitario' => $precio,
                'subtotal'        => $subtotal,
                'igv'             => 0,
                'unidad'          => 'NIU',
            ]);
        }

        // 5️⃣ Recalcular totales (USA con_igv REAL)
        $this->recalcularTotalesTicket();
        // 3️⃣.1️⃣ Aplicar recargo si corresponde
        $this->recalcularTotalConRecargo();

        // 6️⃣ Emitir
        $this->comprobante->update([
            'estado' => 'EMITIDO',
        ]);

        $this->comprobante->load('detalles');
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
            'estado'                     => $this->comprobante->estado,
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

            $comprobante = Comprobante::findOrFail($this->comprobante->id_comprobante);

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

            // 6️⃣ Crear movimiento inverso en caja
            CajaMovimiento::create([
                'id_usuario'      => auth()->id(),
                'id_referencia'   => $comprobante->id_comprobante,
                'tabla_referencia' => 'comprobantes',
                'tipo'            => 'EGRESO',
                'descripcion'     => 'ANULACIÓN TICKET ' . $comprobante->serie . '-' . $comprobante->numero,
                'monto'           => $comprobante->total,
                'responsable'     => auth()->user()->name,
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
        if (!$this->comprobante) {
            return;
        }

        if ($this->comprobante->estado !== 'BORRADOR') {

            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Acción no permitida',
                'message' => 'Solo se pueden eliminar comprobantes en estado BORRADOR'
            ]);

            return;
        }

        if ($this->comprobante->total_cobrado > 0) {

            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Acción bloqueada',
                'message' => 'No se puede eliminar un comprobante que ya tiene pagos registrados'
            ]);

            return;
        }

        DB::transaction(function () {

            // Eliminar detalles primero
            $this->comprobante->detalles()->delete();

            // Eliminar comprobante
            $this->comprobante->delete();
        });

        $this->comprobante = null;

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Comprobante eliminado',
            'message' => 'El borrador fue eliminado correctamente'
        ]);
    }

    public function render()
    {
        return view('livewire.atencion.facturacion');
    }
}
