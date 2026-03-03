<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\User;
use Livewire\Component;

class Finalizar extends Component
{
    public $atencion, $id_atencion;
    public $nombre_paciente, $fecha_nacimiento;
    public $errores = [];
    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;


        $this->atencion = Atencion::with([
            'paciente',
            'medico',
            'diagnosticos.cie10'
        ])->findOrFail($this->id_atencion);
        $paciente = User::find($this->atencion->id_paciente);
        $this->nombre_paciente = $paciente->name;
        $this->fecha_nacimiento = $paciente->fecha_nacimiento;

        $this->validar();
    }
    public function validar()
    {
        $this->errores = $this->atencion->puedeFinalizar();
    }

    public function finalizar()
    {
        $this->validar();

        if (!empty($this->errores)) {
            return;
        }

        $this->atencion->estado = 'FINALIZADO';
        $this->atencion->fecha_fin_atencion = now();
        $this->atencion->save();

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Atención finalizada',
            'message' => 'Atención finalizada correctamente.'
        ]);
    }
    public function render()
    {
        return view('livewire.atencion.finalizar');
    }
}
