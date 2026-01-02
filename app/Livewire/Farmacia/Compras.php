<?php

namespace App\Livewire\Farmacia;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\KardexMedicamento;
use App\Models\Medicamento;
use App\Models\Proveedor;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Compras extends Component
{
    public $mostrarFormulario = false;
    public $id_compra;
    public  $detalle = [];
    public  $total = 0;
    public $listado = true;
    public $search, $show;
    use  WithPagination, WithoutUrlPagination;
    protected $paginationTheme = "bootstrap";
    public function mount()
    {
        $this->show = 5;
        // compra nueva
    }
    public function render()
    {
        $proveedores = Proveedor::where('estado', true)->get();
        $medicamentos = [];
        $compras = Compra::with([
            'proveedor',
            'detalles.medicamento'
        ])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('proveedor', function ($p) {
                        $p->where('razon_social', 'like', '%' . $this->search . '%');
                    })
                        ->orWhere('fecha_compra', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('compras.created_at', 'desc')
            ->paginate($this->show);
        return view('livewire.farmacia.compras', compact('proveedores', 'medicamentos', 'compras'));
    }

    public $buscarMedicamento = '';
    public $resultados = [];

    public function updatedBuscarMedicamento()
    {
        if (strlen($this->buscarMedicamento) < 2) {
            $this->resultados = [];
            return;
        }

        $this->resultados = Medicamento::where('nombre', 'like', '%' . $this->buscarMedicamento . '%')
            ->limit(10)
            ->get();
    }
    public function seleccionarMedicamento($id)
    {
        // evitar duplicados
        foreach ($this->detalle as $item) {
            if ($item['id_medicamento'] == $id) {
                return;
            }
        }

        $med = Medicamento::find($id);

        $this->detalle[] = [
            'id_medicamento' => $med->id_medicamento,
            'nombre' => $med->nombre,
            'presentacion' => $med->presentacion,
            'marca' => $med->marca,
            'concentracion' => $med->concentracion,
            'cantidad' => 0,
            'precio' => 0,
            'subtotal' => 0,
        ];
        $this->buscarMedicamento = '';
        $this->resultados = [];
    }

    public function removeItem($index)
    {
        if (!isset($this->detalle[$index])) {
            return;
        }

        unset($this->detalle[$index]);

        // reindexar array
        $this->detalle = array_values($this->detalle);
    }
    public function updatedDetalle()
    {
        $this->calcularTotales();
    }

    public function calcularTotales()
    {
        $this->total = 0;

        foreach ($this->detalle as $i => $item) {
            $cantidad = max(1, (int) $item['cantidad']);
            $precio   = max(0, (float) $item['precio']);

            $subtotal = $cantidad * $precio;

            $this->detalle[$i]['subtotal'] = $subtotal;
            $this->total += $subtotal;
        }
    }

    public $id_proveedor;
    public $fecha_compra;
    public $tipo_documento;
    public $nro_documento;

    public function guardarCompra()
    {
        foreach ($this->detalle as $i => $item) {

            $cantidad = (int) $item['cantidad'];
            $precio   = (float) $item['precio'];

            if ($cantidad <= 0 || $precio <= 0) {
                $this->dispatch(
                    'alert',
                    [
                        'type' => 'warning',
                        'title' => 'Datos inválidos',
                        'message' => 'La cantidad y el precio deben ser mayores a cero'
                    ]
                );
                return;
            }
        }

        $this->validate([
            'id_proveedor' => 'required',
            'fecha_compra' => 'required',
            'detalle'      => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {

            // 1️⃣ CABECERA COMPRA
            $compra = Compra::create([
                'id_proveedor'   => $this->id_proveedor,
                'fecha_compra'   => $this->fecha_compra,
                'tipo_documento' => $this->tipo_documento,
                'nro_documento'  => $this->nro_documento,
                'total'          => $this->total,
                'estado'         => 'ACTIVA',
            ]);

            // 2️⃣ DETALLE + STOCK
            foreach ($this->detalle as $item) {
                DetalleCompra::create([
                    'id_compra'      => $compra->id_compra,
                    'id_medicamento' => $item['id_medicamento'],
                    'cantidad'       => $item['cantidad'],
                    'precio'         => $item['precio'],
                    'subtotal'       => $item['subtotal'],
                ]);

                $medicamento = Medicamento::find($item['id_medicamento']);


                $stockAnterior = $medicamento->stock;
                $stockNuevo    = $stockAnterior + $item['cantidad'];


                // 3️⃣ ACTUALIZAR STOCK
                Medicamento::where('id_medicamento', $item['id_medicamento'])
                    ->increment('stock', $item['cantidad']);

                // Registrar kardex
                KardexMedicamento::create([
                    'id_medicamento'  => $item['id_medicamento'],
                    'id_compra'       => $compra->id_compra,
                    'tipo_movimiento' => 'ENTRADA',
                    'cantidad'        => $cantidad,
                    'stock_anterior'  => $stockAnterior,
                    'stock_actual'    => $stockNuevo,
                    'descripcion'     => 'Ingreso por compra',
                    'user_id'         => auth()->user()->id,
                ]);
            }

            // ACTUALIZACION DE KARDEX


            DB::commit();

            $this->resetFormulario();

            $this->dispatch(
                'alert',
                ['type' => 'success', 'title' => 'Compra registrada correctamente', 'message' => 'Exito']
            );
        } catch (\Throwable $e) {

            DB::rollBack();

            $this->dispatch(
                'alert',
                ['type' => 'info', 'title' => $e, 'message' => 'Exito']
            );
            throw $e; // para debug mientras desarrollas
        }
    }
    public function resetFormulario()
    {
        $this->id_proveedor = null;
        $this->fecha_compra = null;
        $this->tipo_documento = null;
        $this->nro_documento = null;

        $this->detalle = [];
        $this->total = 0;
    }

    public $verDetalle = [];

    public function toggleDetalle($id_compra)
    {
        $this->verDetalle[$id_compra] = !($this->verDetalle[$id_compra] ?? false);
    }

    public function printCompra($id_compra)
    {

        $compra = Compra::find($id_compra);
        if ($compra) {
            //imagen
            $path = public_path('images/logo-clinica.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);


            $pdf = Pdf::loadView('reportes.print-compra', compact('base64', 'compra'));

            return response()->streamDownload(
                fn() => print($pdf->output()),
                'compra_' . $compra->fecha_compra . '.pdf'
            );
        }
    }


    public $mostrarModalAnular = false;
    public $compraIdAnular;
    public $motivoAnulacion = '';
    public $compraAnular;
    public $detalleAnular = [];

    public function abrirModalAnular($idCompra)
    {
        $this->id_compra = $idCompra;
        $this->compraAnular = Compra::with('detalles.medicamento')
            ->where('id_compra', $idCompra)
            ->firstOrFail();

        $this->detalleAnular = $this->compraAnular->detalles;
        $this->motivoAnulacion = '';
        $this->mostrarModalAnular = true;
    }



    public function confirmarAnulacion()
    {
        if (!$this->motivoAnulacion) {
            $this->dispatch('alert', [
                'type' => 'warning',
                'title' => 'Motivo requerido',
                'message' => 'Debe ingresar un motivo de anulación'
            ]);
            return;
        }

        DB::transaction(function () {


            foreach ($this->compraAnular->detalles as $det) {

                $med = $det->medicamento;

                $med->stock -= $det->cantidad; // ✅ RESTAR


                
            //kardex 
            $medicamento = Medicamento::find($med->id_medicamento);

            $stockAnterior = $medicamento->stock;

            if ($stockAnterior < $det->cantidad) {
                throw new Exception('Stock insuficiente');
            }

            $stockNuevo = $stockAnterior - $det->cantidad;

            // Actualizar stock
           
            $stockAnterior = $medicamento->stock;
            // Registrar kardex
            KardexMedicamento::create([
                'id_medicamento'  => $med->id_medicamento,
                'id_compra'     => $this->id_compra,
                'tipo_movimiento' => 'SALIDA',
                'cantidad'        => $det->cantidad,
                'stock_anterior'  => $stockAnterior,
                'stock_actual'     => $stockNuevo,
                'descripcion'     => 'Anulacion de compra',
                'user_id'         => auth()->user()->id,
            ]);


                $med->save();
            }

            $this->compraAnular->estado = 'ANULADA';
            $this->compraAnular->motivo_anulacion = $this->motivoAnulacion;
            $this->compraAnular->fecha_anulacion = now();
            $this->compraAnular->user_anulacion = auth()->user()->id;
            $this->compraAnular->save();
        });

        $this->mostrarModalAnular = false;

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Compra anulada',
            'message' => 'El stock fue revertido correctamente'
        ]);
    }
}
