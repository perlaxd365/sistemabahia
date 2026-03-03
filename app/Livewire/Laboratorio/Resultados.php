<?php

namespace App\Livewire\Laboratorio;

use App\Models\Atencion;
use App\Models\LaboratorioResultado;
use App\Models\OrdenLaboratorio;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Termwind\Components\Dd;
use UserUtil;

class Resultados extends Component
{
    public $id_orden;
    public $orden;
    public $resultados = [];
    public $paciente;
    public $atencion;
    public $historia;
    use WithFileUploads;

    public $pdfResultado;
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


    public function subirResultado()
    {
        if ($this->orden->estado === 'ANULADO') {
            return;
        }

        $this->validate([
            'pdfResultado' => 'required|mimes:pdf|max:5120'
        ]);

        DB::beginTransaction();

        try {

            $path = Storage::disk('cloudinary')->putFileAs(
                'informes_pdf',
                $this->pdfResultado,
                'orden_' . $this->orden->id_orden . '_' . time() . '.pdf',
                [
                    'resource_type' => 'raw'
                ]
            );

            $this->orden->update([
                'ruta_pdf_resultado' => $path,
                'fecha_subida_pdf' => now(),
                'id_usuario_subida_pdf' => auth()->id(),
                'estado' => 'PROCESO' // 🔥 YA NO FINALIZA
            ]);

            DB::commit();

            $this->dispatch('alert', [
                'type' => 'success',
                'title' => 'Documento actualizado correctamente',
                'message' => 'Puede seguir modificándolo'
            ]);

            $this->orden->refresh();
            $this->reset('pdfResultado');
        } catch (\Exception $e) {

            DB::rollBack();

            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Error al subir documento',
                'message' => 'Comunicar a sistemas'
            ]);
        }
    }
    public function finalizarInforme()
    {
        if (!$this->orden->ruta_pdf_resultado) {

            $this->dispatch('alert', [
                'type' => 'warning',
                'title' => 'Debe subir un informe primero',
                'message' => 'No puede finalizar vacío'
            ]);

            return;
        }

        $this->orden->update([
            'estado' => 'FINALIZADO'
        ]);

        $this->orden->refresh();

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Informe finalizado correctamente',
            'message' => 'El documento queda bloqueado'
        ]);
    }

    public function render()
    {
        return view('livewire.laboratorio.resultados');
    }
}
