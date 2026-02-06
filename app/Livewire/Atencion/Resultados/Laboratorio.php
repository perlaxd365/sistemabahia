<?php

namespace App\Livewire\Atencion\Resultados;

use App\Models\Atencion;
use App\Models\OrdenLaboratorio;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use UserUtil;

class Laboratorio extends Component
{
    public $id_atencion;
    public $ordenes = [];

    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;
        $this->ordenes = OrdenLaboratorio::with([

            'detalles.examenes.areas',
            'detalles.resultados'
        ])
            ->where('id_atencion', $id_atencion)
            ->orderBy('fecha', 'desc')
            ->get();
    }

    public function imprimirResultados($id_orden)
    {
        $orden = OrdenLaboratorio::find($id_orden);
        $paciente = Atencion::find($this->id_atencion)->paciente;
        //imagen
        $path = public_path('images/logo-clinica.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        //firma de laboratorista
        $profesional = UserUtil::getUserByID($orden->profesional);

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

        $pdf = Pdf::loadView(
            'reportes.resultados-laboratorio',
            compact('orden', 'base64', 'paciente', 'base64', 'firma_img','profesional')
        )->setPaper('A4', 'landscape' );

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'resultados_laboratorio_atencion_' . $this->id_atencion . '.pdf'
        );
    }
    public function render()
    {
        return view('livewire.atencion.resultados.laboratorio');
    }
}
