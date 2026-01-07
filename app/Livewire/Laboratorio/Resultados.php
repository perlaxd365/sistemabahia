<?php

namespace App\Livewire\Laboratorio;

use App\Models\Atencion;
use App\Models\LaboratorioResultado;
use App\Models\OrdenLaboratorio;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;

class Resultados extends Component
{
    public $id_orden;
    public $orden;
    public $resultados = [];
    public $paciente;
    public $atencion;
    public $historia;
    //paciente
    public function mount($id_orden)
    {
        $this->id_orden = $id_orden;
        $this->orden = OrdenLaboratorio::with('detalles.examenes')
            ->with('detalles.resultados')
            ->findOrFail($id_orden);


        //paciente
        $this->atencion = $this->orden->atencion;
        $this->paciente = $this->atencion->paciente;
        $this->historia = $this->atencion->historia;



        foreach ($this->orden->detalles as $det) {
            $this->resultados[$det->id_detalle_laboratorio] =
                $det->resultados->resultado ?? '';
        }
    }
    public function guardar()
    {
        foreach ($this->resultados as $id_detalle => $data) {

            LaboratorioResultado::updateOrCreate(
                ['id_detalle_laboratorio' => $id_detalle],
                [
                    'resultado'       => $data,
                    'observacion'     => null,
                    'fecha_resultado' => Carbon::now(),
                ]
            );
        }

        $this->orden->update(['estado' => 'PROCESO']);

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Resultados actualizados correctamente',
            'message' => 'Resultados agregados'
        ]);
    }

    public function finalizar()
    {
        foreach ($this->resultados as $id_detalle => $data) {

            LaboratorioResultado::updateOrCreate(
                ['id_detalle_laboratorio' => $id_detalle],
                [
                    'resultado'       => $data,
                    'observacion'     => null,
                    'fecha_resultado' => Carbon::now(),
                ]
            );
        }
        $this->orden->update(['profesional' => auth()->user()->id]);
        $this->orden->update(['estado' => 'FINALIZADO']);
        // ✅ Redireccionar al listado de órdenes
        return redirect()->route('laboratorio.ordenes');
    }

    public function vista_previa()
    {
        $ordenes = OrdenLaboratorio::with([

            'detalles.examenes.areas',
            'detalles.resultados'
        ])
            ->where('id_atencion', $this->atencion->id_atencion)
            ->where('estado', 'FINALIZADO')
            ->orderBy('fecha', 'desc')
            ->get();
        $paciente = $this->atencion->paciente;
        //imagen
        $path = public_path('images/logo-clinica.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);


        $pdf = Pdf::loadView(
            'reportes.resultados-laboratorio',
            compact('ordenes', 'base64', 'paciente', 'base64')
        )->setPaper('A4', 'landscape');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'resultados_laboratorio_atencion_' . $this->atencion->id_atencion . '.pdf'
        );
    }


    public function render()
    {
        return view('livewire.laboratorio.resultados');
    }
}
