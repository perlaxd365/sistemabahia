<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\User;
use Livewire\Component;

class Medico extends Component
{

    public $id_atencion;
    public $id_medico;
    public $atencion;
    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;

        $this->atencion = Atencion::findOrFail($id_atencion);
        $this->id_medico = $this->atencion->id_medico;
    }

    public function asignar()
    {
        $this->validate([
            'id_medico' => 'required|exists:users,id'
        ]);

        $atencion = Atencion::findOrFail($this->id_atencion);
        $atencion->id_medico = $this->id_medico;
        $atencion->save();
        $this->atencion = Atencion::findOrFail($atencion->id_atencion);
        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Medico Asignado correctamente',
            'message' => 'Exito'
        ]);
    }

    public function render()
    {
        //doctorees 2
        $medicos = User::orderBy('name')->where('privilegio_cargo', 2)->get();
        return view('livewire.atencion.medico', compact('medicos'));
    }
}
