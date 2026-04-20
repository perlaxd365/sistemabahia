<?php

namespace App\Livewire\Reportes;

use App\Exports\VentasContadorExport;
use App\Models\Comprobante;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Ventas extends Component
{
    public $fecha_inicio;
    public $fecha_fin;
    public $estado = "EMITIDO";
    public $tipo = 'TODOS';
    public $cliente;

    public function render()
    {

        $ventas = Comprobante::with([
            'cliente',
            'paciente',
            'atencion'
        ])

            ->when($this->fecha_inicio, function ($q) {
                $q->whereDate('fecha_emision', '>=', $this->fecha_inicio);
            })

            ->when($this->fecha_fin, function ($q) {
                $q->whereDate('fecha_emision', '<=', $this->fecha_fin);
            })

            ->when($this->tipo === 'BOLETA_FACTURA', function ($q) {
                $q->whereIn('tipo_comprobante', ['BOLETA', 'FACTURA']);
            })


            ->when($this->cliente, function ($q) {
                $q->whereHas('cliente', function ($sub) {
                    $sub->where('numero_documento', 'LIKE', '%' . $this->cliente . '%')
                        ->orWhere('razon_social', 'LIKE', '%' . $this->cliente . '%')
                        ->orWhere('nombres', 'LIKE', '%' . $this->cliente . '%');
                });
            })
            ->when(
                in_array($this->estado, ['EMITIDO', 'ANULADO', 'PENDIENTE']),
                function ($q) {
                    # code...
                    $q->when($this->estado === 'EMITIDO', function ($q) {
                        $q->whereIn('estado', ['EMITIDO', 'PENDIENTE']);
                    });
                    $q->when($this->estado === 'ANULADO', function ($q) {
                        $q->whereIn('estado', ['ANULADO']);
                    });
                }
            )->when(
                in_array($this->tipo, ['TICKET', 'BOLETA', 'FACTURA', 'NOTA_CREDITO']),
                function ($q) {
                    $q->where('tipo_comprobante', $this->tipo);
                }
            )

            ->orderBy('fecha_emision', 'desc')
            ->get();


        $total = $ventas->sum('total');
        $igv = $ventas->sum('igv');
        $subtotal = $ventas->sum('subtotal');

        return view('livewire.reportes.ventas', [
            'ventas' => $ventas,
            'total' => $total,
            'igv' => $igv,
            'subtotal' => $subtotal
        ]);
    }
    public function exportar()
    {
        return Excel::download(
            new VentasContadorExport(
                $this->fecha_inicio,
                $this->fecha_fin,
                $this->tipo
            ),
            'reporte_ventas.xlsx'
        );
    }
}
