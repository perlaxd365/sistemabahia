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
    public $tipo_atencion;
    public $relato_consulta;
    public $atencion;

    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;
        $this->atencion = Atencion::find($id_atencion);
    }
    public function actualizarAtencion()
    {
        if ($this->atencion->estaBloqueada()) {

            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Atención finalizada',
                'message' => 'Esta atención ya emitió comprobante, por favor apertura una nueva atención, el DNI ES : ' . $this->dni
            ]);
            return;
        }
        $atencion = Atencion::find($this->id_atencion);

        $atencion->actualizarDatosIniciales(
            $this->tipo_atencion,
            $this->relato_consulta
        );

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Actualizado',
            'message' => 'Datos de atención actualizados.'
        ]);
    }

    public function render()
    {
        $atencion = Atencion::find($this->id_atencion);
        $this->relato_consulta = $atencion->relato_consulta;
        $this->tipo_atencion = $atencion->tipo_atencion;


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
