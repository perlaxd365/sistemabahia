<?php

namespace App\Livewire\Reportes;

use App\Models\Comprobante;
use Carbon\Carbon;
use Livewire\Component;

class Ingresos extends Component
{

    public $fecha;
    public $doctor_id;

    public function mount()
    {
        $this->fecha = Carbon::today()->toDateString();
        $this->doctor_id = auth()->id(); // doctor logueado
    }

    public function getIngresosProperty()
    {
        return Comprobante::whereDate('fecha_emision', $this->fecha)
            ->where('estado', 'EMITIDO')
            ->whereHas('pagos', function ($q) {
                $q->where('estado', 'REGISTRADO');
            })
            ->with([
                'atencion.paciente',
                'pagos'
            ])
            ->get();
    }

    public function render()
    {
        return view('livewire.reportes.ingresos', [
            'ingresos' => $this->ingresos,
            'total' => $this->ingresos->sum(fn($c) => $c->totalPagado()),

        ]);
    }
}
