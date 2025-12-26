<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\Signo;
use App\Models\User;
use Livewire\Component;

class SignosVitales extends Component
{
    public $id_atencion;

    public $nombre_paciente = '';

    // datos del componente
    public $id_signo,
        $id_paciente,
        $sistolica_derecha,
        $diastolica_derecha,
        $sistolica_izquierda,
        $diastolica_izquierda,
        $frecuencia_cardiaca;

    public $signos;
    public $datos = [];

    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;
        $atencion = Atencion::find($id_atencion);
        $paciente = User::find($atencion->id_paciente);
        $this->nombre_paciente = $paciente->name;
        $this->id_paciente = $paciente->id;
        $this->cargarDatos();
    }

    public function render()
    {
        $this->signos = Signo::join('atencions', 'atencions.id_atencion', 'signos.id_atencion')
            ->where('atencions.id_atencion', $this->id_atencion)->get();
        $signos = $this->signos;
        return view('livewire.atencion.signos-vitales', compact('signos'));
    }

    public function guardar()
    {


        Signo::create([
            'id_atencion' => $this->id_atencion,
            'id_paciente' => $this->id_paciente,
            'sistolica_derecha' => $this->sistolica_derecha,
            'diastolica_derecha' => $this->diastolica_derecha,
            'sistolica_izquierda' => $this->sistolica_izquierda,
            'diastolica_izquierda' => $this->diastolica_izquierda,
            'frecuencia_cardiaca' => $this->frecuencia_cardiaca,
            'fecha_signo' => now(),
            'estado_signo' => true,
        ]);

        // show alert

        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se agregaron los signos vitales a la atención de ' . $this->nombre_paciente, 'message' => 'Exito']
        );
        $this->datos = Signo::join('atencions', 'atencions.id_atencion', 'signos.id_atencion')
            ->where('atencions.id_atencion', $this->id_atencion)->get()->toArray();


        //actualizar chart
        $this->cargarDatosGrafico();

        // 📢 Avisar a JS que se actualice
        $this->dispatch('actualizarGrafico', [
            'datos' => $this->datos
        ]);
        


        $this->default();
    }

    public function default()
    {
        $this->sistolica_derecha = "";
        $this->diastolica_derecha = "";
        $this->sistolica_izquierda = "";
        $this->diastolica_izquierda = "";
        $this->frecuencia_cardiaca = "";
    }

    public function eliminar_signo($id)
    {
        $this->id_signo = $id;
        $signo = Signo::find($id);
        $signo->delete();

        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se eliminó el signo correctamente', 'message' => 'Exito']
        );
        $this->default();
    }

    public function cargarDatos()
    {
        $this->datos = Signo::join('atencions', 'atencions.id_atencion', 'signos.id_atencion')
            ->where('atencions.id_atencion', $this->id_atencion)->get()->toArray();

        $this->dispatch('get-grafico-signo');
    }

    public function cargarDatosGrafico()
    {
        $this->datos = Signo::join('atencions', 'atencions.id_atencion', 'signos.id_atencion')
            ->where('atencions.id_atencion', $this->id_atencion)->get()
            ->map(fn($s) => [
                'fecha_signo' => $s->fecha_signo,
                'sistolica_derecha' => $s->sistolica_derecha,
                'diastolica_derecha' => $s->diastolica_derecha,
            ])
            ->toArray();
    }
}
