<?php

namespace App\Livewire\Atencion\Resultados;

use App\Models\ImagenOrden;
use Livewire\Component;

class Imagen extends Component
{
    public $id_atencion;
    public $ordenes = [];

    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;

        $this->ordenes = ImagenOrden::with([
            'detalles.estudio.area'
        ])
            ->where('id_atencion', $id_atencion)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    public function render()
    {
        return view('livewire.atencion.resultados.imagen');
    }
}
