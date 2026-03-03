<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\Historia;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class InfoPaciente extends Component
{
    public $id_atencion;

    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;
    }

    public function render()
    {
        $atencion = Atencion::find($this->id_atencion);

        if (!$atencion) {
            abort(404, 'Atención no encontrada');
        }
        $paciente = User::find($atencion->id_paciente);
        $historia = Historia::find($atencion->id_historia);
        $responsable = User::find($atencion->id_responsable)->name;
        // ✅ CALCULAR EDAD
        $edad = null;

        if ($paciente && $paciente->fecha_nacimiento) {
            $edad = Carbon::parse($paciente->fecha_nacimiento)->age;
        }
        return view('livewire.atencion.info-paciente', compact('paciente', 'historia', 'responsable', 'atencion', 'edad'));
    }
}
