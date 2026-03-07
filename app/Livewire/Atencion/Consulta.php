<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\Consulta as ModelsConsulta;
use App\Models\Historia;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use DateUtil;
use Livewire\Component;
use UserUtil;

class Consulta extends Component
{
    public $id_consulta,
        $id_atencion,
        $id_paciente,
        $atencion,

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

    public $medico_responsable = false;
    public $nombre_paciente;
    public $dni;

    public $guardadoAutomatico = false;

    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;
        $this->atencion = Atencion::find($id_atencion);

        $paciente = User::find($this->atencion->id_paciente);
        $this->nombre_paciente = $paciente->name;
        $this->dni = $paciente->dni;
        $this->id_paciente = $this->atencion->id_paciente;

        $consulta = ModelsConsulta::where("id_atencion", $id_atencion)->first();

        if ($consulta) {
            $this->id_consulta = $consulta->id_consulta;

            foreach ($consulta->getAttributes() as $campo => $valor) {
                if (property_exists($this, $campo)) {
                    $this->$campo = $valor;
                }
            }
        }

        if ($this->atencion->id_medico == auth()->id()) {
            $this->medico_responsable = true;
        }
    }

    public function render()
    {
        return view('livewire.atencion.consulta');
    }

    /*
    |--------------------------------------------------------------------------
    | AUTOSAVE POR CAMPO
    |--------------------------------------------------------------------------
    */

    public function updated($property)
    {
        // Solo campos de consulta
        if (!str_ends_with($property, '_consulta')) {
            return;
        }

        if ($this->atencion->estaFinalizada()) {
            return;
        }

        // Crear consulta si no existe
        if (!$this->id_consulta) {
            $consulta = ModelsConsulta::create([
                'id_atencion' => $this->id_atencion,
                'id_paciente' => $this->id_paciente,
                'id_responsable' => auth()->id(),
                'fecha_consulta' => now(),
                'estado_consulta' => true,
            ]);

            $this->id_consulta = $consulta->id_consulta;
        }

        // Actualizar solo el campo modificado
        $consulta = ModelsConsulta::find($this->id_consulta);

        $consulta->$property = $this->$property;


        $consulta->save();

        $this->guardadoAutomatico = true;
    }

    /*
    |--------------------------------------------------------------------------
    | CALCULAR IMC AUTOMÁTICO
    |--------------------------------------------------------------------------
    */

    public function updatedPesoConsulta()
    {
        $this->calcularImc();
    }

    public function updatedTallaConsulta()
    {
        $this->calcularImc();
    }

    public function calcularImc()
    {
        if ($this->peso_consulta > 0 && $this->talla_consulta > 0) {

            $talla_m = $this->talla_consulta / 100;

            $this->imc_consulta = round(
                $this->peso_consulta / ($talla_m * $talla_m),
                2
            );
            $this->updated('imc_consulta');
        } else {
            $this->imc_consulta = null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDADO MANUAL (RESPALDO)
    |--------------------------------------------------------------------------
    */

    public function agregarConsulta()
    {
        if (!$this->id_consulta) {
            $this->updated('molestia_consulta');
        }

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Consulta guardada',
            'message' => 'Datos guardados correctamente.'
        ]);
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

            //firma de doctor
            $profesional = UserUtil::getUserByID($atencion->id_medico);

            $firma_img = null;

            if ($profesional && $profesional->firma_url) {

                try {
                    $url = $profesional->firma_url;

                    $data_firma = file_get_contents($url);

                    $type_firma = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);

                    $firma_img = 'data:image/' . $type_firma . ';base64,' . base64_encode($data_firma);
                } catch (\Exception $e) {
                    $firma_img = null;
                }
            }
            $pdf = Pdf::loadView('reportes.print-consulta', compact('base64', 'consulta', 'paciente', 'historia', 'firma_img', 'profesional'));

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


    public function printReceta()
    {
        if (!$this->id_consulta) {
            $this->dispatch('alert', [
                'type' => 'info',
                'title' => 'No existe consulta registrada.',
                'message' => 'Guarde primero la consulta.'
            ]);
            return;
        }


        $consulta = ModelsConsulta::find($this->id_consulta);
        $paciente = User::find($this->id_paciente);
        $atencion = Atencion::find($this->id_atencion);

        // LOGO
        $path = public_path('images/logo-clinica.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        // DOCTOR
        $profesional = UserUtil::getUserByID($atencion->id_medico);

        $firma_img = null;

        if ($profesional && $profesional->firma_url) {
            try {
                $url = $profesional->firma_url;
                $data_firma = file_get_contents($url);
                $type_firma = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
                $firma_img = 'data:image/' . $type_firma . ';base64,' . base64_encode($data_firma);
            } catch (\Exception $e) {
                $firma_img = null;
            }
        }

        $pdf = Pdf::loadView('reportes.print-receta', compact(
            'consulta',
            'paciente',
            'base64',
            'firma_img',
            'profesional'
        ))->setPaper([0, 0, 226.77, 600], 'portrait'); // tamaño ticket 80mm

        $pdf->getDomPDF()->set_option("defaultFont", "DejaVu Sans");

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'receta_' . $paciente->name . '.pdf'
        );
    }
}
