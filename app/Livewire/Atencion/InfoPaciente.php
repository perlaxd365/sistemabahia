<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\Consulta;
use App\Models\Historia;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateUtil;
use Livewire\Component;
use UserUtil;

class InfoPaciente extends Component
{
    public $id_atencion;
    public $tipo_atencion;
    public $relato_consulta;
    public $atencion;

    public $modo_atencion;

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
            $this->relato_consulta,
            $this->modo_atencion
        );


        $atencion->save();

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Actualizado',
            'message' => 'Datos de atención actualizados.'
        ]);
    }

    public function printConsulta($id_consulta)
    {

        $consulta = Consulta::find($id_consulta);
        if ($consulta) {
            //imagen
            $path = public_path('images/logo-clinica.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

            //paciente
            $paciente = User::find($consulta->id_paciente);
            $paciente->fecha_nacimiento = DateUtil::getFechaSimple($paciente->fecha_nacimiento);
            $atencion = Atencion::find($consulta->id_atencion);
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
                'consulta_' . UserUtil::getUserByID($atencion->id_medico)->name . '.pdf'
            );
        } else {

            $this->dispatch(
                'alert',
                ['type' => 'info', 'title' => 'No se resgitró historia.', 'message' => 'Sin registro']
            );
        }
    }

    public function render()
    {
        $atencion = Atencion::find($this->id_atencion);
        $this->relato_consulta = $atencion->relato_consulta;
        $this->tipo_atencion = $atencion->tipo_atencion;
        $this->modo_atencion = $atencion->modo_atencion;

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
        $consultas = [];
        $consultas = Consulta::where('id_paciente', $atencion->id_paciente)
            ->orderBy('fecha_consulta', 'desc')
            ->get();
        return view('livewire.atencion.info-paciente', compact('paciente', 'historia', 'responsable', 'atencion', 'edad', 'consultas'));
    }
}
