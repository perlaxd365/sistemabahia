<?php

namespace App\Livewire\Imagen;

use App\Models\ImagenOrden;
use Livewire\Component;

class Ordenes extends Component
{


    public $search = '';

    public function render()
    {
        $ordenes = ImagenOrden::with([
            'atencion.paciente',
            'atencion.historia',
            'detalles.estudio.area'
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

        return view('livewire.imagen.ordenes', compact('ordenes'));
    }
}
