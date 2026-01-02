<?php

namespace App\Livewire\Farmacia;

use App\Models\KardexMedicamento;
use App\Models\Medicamento;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Kardex extends Component
{
    use WithPagination;

    public $buscarMedicamento = '';
    public $fechaInicio;
    public $fechaFin;
    public $tipoMovimiento = ''; // ENTRADA | SALIDA
    public $perPage = 10;


    public function updated($property)
    {
        if (in_array($property, [
            'buscarMedicamento',
            'fechaInicio',
            'fechaFin',
            'tipoMovimiento'
        ])) {
            $this->resetPage();
        }
    }
    public function render()
    {
        $movimientos = KardexMedicamento::with('medicamento')
            ->when($this->buscarMedicamento, function ($q) {
                $q->whereHas('medicamento', function ($m) {
                    $m->where('nombre', 'like', '%' . $this->buscarMedicamento . '%');
                });
            })
            ->when($this->tipoMovimiento, function ($q) {
                $q->where('tipo_movimiento', $this->tipoMovimiento);
            })
            ->when($this->fechaInicio, function ($q) {
                $q->whereDate('created_at', '>=', $this->fechaInicio);
            })
            ->when($this->fechaFin, function ($q) {
                $q->whereDate('created_at', '<=', $this->fechaFin);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.farmacia.kardex', compact('movimientos'));
    }

    /**
     * 🖨️ IMPRIMIR KARDEX
     */
    public function imprimir()
    {
        $kardex = KardexMedicamento::with('medicamento')
            ->when($this->buscarMedicamento, function ($q) {
                $q->whereHas('medicamento', function ($m) {
                    $m->where('nombre', 'like', '%' . $this->buscarMedicamento . '%');
                });
            })
            ->when($this->tipoMovimiento, function ($q) {
                $q->where('tipo_movimiento', $this->tipoMovimiento);
            })
            ->when($this->fechaInicio, function ($q) {
                $q->whereDate('created_at', '>=', $this->fechaInicio);
            })
            ->when($this->fechaFin, function ($q) {
                $q->whereDate('created_at', '<=', $this->fechaFin);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $fecha = Carbon::now()->format('d/m/Y H:i');

            //imagen
            $path = public_path('images/logo-clinica.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $fechaInicio= $this->fechaInicio;
        $fechaFin= $this->fechaFin;
        $tipoMovimiento= $this->tipoMovimiento;
        $medicamentoSeleccionado = $this->buscarMedicamento;
        $pdf = Pdf::loadView(
            'reportes.print-kardex',
            compact('kardex', 'fecha','fechaInicio', 'fechaFin','tipoMovimiento','medicamentoSeleccionado', 'logoBase64')
        )->setPaper('A4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'kardex_farmacia_' . now()->format('Ymd_His') . '.pdf'
        );
    }
}
