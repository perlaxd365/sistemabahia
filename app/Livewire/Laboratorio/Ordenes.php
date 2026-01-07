<?php

namespace App\Livewire\Laboratorio;

use App\Models\OrdenLaboratorio;
use Livewire\Component;

class Ordenes extends Component
{

    public $search = '';

    public function render()
    {
        $ordenes = OrdenLaboratorio::with([
            'atencion.paciente',
            'atencion.historia',
            'detalles.examenes.areas'
        ])
            ->whereHas('atencion.paciente', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('dni', 'like', '%' . $this->search . '%');
            })
            ->whereHas('atencion.historia', function ($q) {
                $q->orWhere('nro_historia', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.laboratorio.ordenes', compact('ordenes'));
    }
}
