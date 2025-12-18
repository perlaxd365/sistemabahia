<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\User;
use Livewire\Component;

class SignosVitales extends Component
{
    public $id_atencion;

    public $nombre_paciente = '';

    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;
        $atencion = Atencion::find($id_atencion);
        $paciente = User::find($atencion->id_paciente);
        $this->nombre_paciente = $paciente->name;
    }



    public function render()
    {
        return view('livewire.atencion.signos-vitales');
    }
}
