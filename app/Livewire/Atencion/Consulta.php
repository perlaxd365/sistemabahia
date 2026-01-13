<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\Consulta as ModelsConsulta;
use App\Models\Historia;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use DateUtil;
use Livewire\Component;

class Consulta extends Component
{
    public $id_consulta,
        $id_atencion,
        $id_paciente,

        $molestia_consulta,
        $tiempo_consulta,
        $inicio_consulta,
        $curso_consulta,
        $enfermedad_consulta,
        $atecedente_familiar_consulta,
        $atecedente_patologico_consulta,

        $peso_consulta,
        $talla_consulta,
        $imc_consulta,

        $temperatura_consulta,
        $presion_consulta,
        $frecuencia_consulta,
        $saturacion_consulta,
        $examen_consulta,

        $impresion_consulta,
        $examen_auxiliar_consulta,
        $tratamiento_consulta;

    //medico responsable de la consulta

    public $medico_responsable = false;
    public $nombre_paciente;

    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;
        $atencion = Atencion::find($id_atencion);
        $paciente = User::find($atencion->id_paciente);
        $this->nombre_paciente = $paciente->name;
        $this->id_paciente = $atencion->id_paciente;
        $consulta = ModelsConsulta::where("id_atencion", $atencion->id_atencion)->first();
        if ($consulta) {
            $this->id_consulta = $consulta->id_consulta;
            $this->molestia_consulta = $consulta->molestia_consulta;
            $this->tiempo_consulta = $consulta->tiempo_consulta;
            $this->inicio_consulta = $consulta->inicio_consulta;
            $this->curso_consulta = $consulta->curso_consulta;
            $this->enfermedad_consulta = $consulta->enfermedad_consulta;
            $this->atecedente_familiar_consulta = $consulta->atecedente_familiar_consulta;
            $this->atecedente_patologico_consulta = $consulta->atecedente_patologico_consulta;

            $this->peso_consulta = $consulta->peso_consulta;
            $this->talla_consulta = $consulta->talla_consulta;
            $this->imc_consulta = $consulta->imc_consulta;

            $this->temperatura_consulta = $consulta->temperatura_consulta;
            $this->presion_consulta = $consulta->presion_consulta;
            $this->frecuencia_consulta = $consulta->frecuencia_consulta;
            $this->saturacion_consulta = $consulta->saturacion_consulta;
            $this->examen_consulta = $consulta->examen_consulta;

            $this->impresion_consulta = $consulta->impresion_consulta;
            $this->examen_auxiliar_consulta = $consulta->examen_auxiliar_consulta;
            $this->tratamiento_consulta = $consulta->tratamiento_consulta;
        }

        if ($atencion->id_medico && $atencion->id_medico == auth()->user()->id) {
            $this->medico_responsable = true;
        }
    }
    public function render()
    {

        return view('livewire.atencion.consulta');
    }

    public function agregarConsulta()
    {
        if ($this->id_consulta) {
            $consulta = ModelsConsulta::find($this->id_consulta);
            $consulta->update([
                'id_atencion' => $this->id_atencion,
                'id_paciente' => $this->id_paciente,
                'id_responsable' => auth()->user()->id,

                'molestia_consulta' => $this->molestia_consulta,
                'tiempo_consulta' => $this->tiempo_consulta,
                'inicio_consulta' => $this->inicio_consulta,
                'curso_consulta' => $this->curso_consulta,
                'enfermedad_consulta' => $this->enfermedad_consulta,
                'atecedente_familiar_consulta' => $this->atecedente_familiar_consulta,
                'atecedente_patologico_consulta' => $this->atecedente_patologico_consulta,

                'peso_consulta' => $this->peso_consulta,
                'talla_consulta' => $this->talla_consulta,
                'imc_consulta' => $this->imc_consulta,

                'temperatura_consulta' => $this->temperatura_consulta,
                'presion_consulta' => $this->presion_consulta,
                'frecuencia_consulta' => $this->frecuencia_consulta,
                'saturacion_consulta' => $this->saturacion_consulta,
                'examen_consulta' => $this->examen_consulta,

                'impresion_consulta' => $this->impresion_consulta,
                'examen_auxiliar_consulta' => $this->examen_auxiliar_consulta,
                'tratamiento_consulta' => $this->tratamiento_consulta,
                'fecha_consulta' => now(),
                'estado_consulta' => true,
            ]);
        } else {
            ModelsConsulta::create([
                'id_atencion' => $this->id_atencion,
                'id_paciente' => $this->id_paciente,
                'id_responsable' => auth()->user()->id,

                'molestia_consulta' => $this->molestia_consulta,
                'tiempo_consulta' => $this->tiempo_consulta,
                'inicio_consulta' => $this->inicio_consulta,
                'curso_consulta' => $this->curso_consulta,
                'enfermedad_consulta' => $this->enfermedad_consulta,
                'atecedente_familiar_consulta' => $this->atecedente_familiar_consulta,
                'atecedente_patologico_consulta' => $this->atecedente_patologico_consulta,

                'peso_consulta' => $this->peso_consulta,
                'talla_consulta' => $this->talla_consulta,
                'imc_consulta' => $this->imc_consulta,

                'temperatura_consulta' => $this->temperatura_consulta,
                'presion_consulta' => $this->presion_consulta,
                'frecuencia_consulta' => $this->frecuencia_consulta,
                'saturacion_consulta' => $this->saturacion_consulta,
                'examen_consulta' => $this->examen_consulta,

                'impresion_consulta' => $this->impresion_consulta,
                'examen_auxiliar_consulta' => $this->examen_auxiliar_consulta,
                'tratamiento_consulta' => $this->tratamiento_consulta,
                'fecha_consulta' => now(),
                'estado_consulta' => true,
            ]);
        }

        // show alert
        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se registró la consulta de ' . $this->nombre_paciente . ' con éxito.', 'message' => 'Exito']
        );
    }

    // Se ejecuta SOLO cuando cambia el peso
    public function updatedPesoConsulta()
    {
        $this->calcularImc();
    }

    // Se ejecuta SOLO cuando cambia la talla
    public function updatedTallaConsulta()
    {
        $this->calcularImc();
    }

    public function calcularImc()
    {
        if ($this->peso_consulta > 0 && $this->talla_consulta > 0) {
            $this->imc_consulta = round(
                $this->peso_consulta / ($this->talla_consulta * $this->talla_consulta),
                2
            );
        } else {
            $this->imc_consulta = null;
        }
    }
    public function printConsulta()
    {

        $consulta = ModelsConsulta::find($this->id_consulta);
        if ($consulta) {
            //imagen
            $path = public_path('images/logo-clinica.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

            //paciente
            $paciente = User::find($this->id_paciente);
            $paciente->fecha_nacimiento = DateUtil::getFechaSimple($paciente->fecha_nacimiento);
            $atencion = Atencion::find($this->id_atencion);
            $historia = Historia::find($atencion->id_historia);


            $pdf = Pdf::loadView('reportes.print-consulta', compact('base64', 'consulta', 'paciente', 'historia'));

            return response()->streamDownload(
                fn() => print($pdf->output()),
                'consulta_' . $this->nombre_paciente . '.pdf'
            );
        } else {

            $this->dispatch(
                'alert',
                ['type' => 'info', 'title' => 'No se resgitró historia.', 'message' => 'Sin registro']
            );
        }
    }
}
