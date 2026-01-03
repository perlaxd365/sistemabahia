<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\LaboratorioArea;
use App\Models\LaboratorioExamen;
use App\Models\LaboratorioOrden;
use App\Models\OrdenLaboratorio;
use App\Models\OrdenLaboratorioDetalle;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Laboratorio extends Component
{
    public $paciente;
    public $edad;
    public $diagnostico;

    public $examenesSeleccionados = [];

    public $id_atencion;
    public $nombre_paciente;
    public $fecha_nacimiento;

    //buscador
    public $examenes = [];
    public $seleccionados = [];

    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;
        $atencion = Atencion::find($id_atencion);
        $paciente = User::find($atencion->id_paciente);
        $this->nombre_paciente = $paciente->name;
        $this->fecha_nacimiento = $paciente->fecha_nacimiento;

        //buscador
        $this->examenesPorArea = LaboratorioExamen::orderBy('id_examen')
            ->get();

        //examenes realizados
        $this->cargarOrdenes($this->id_atencion);
    }

    public function render()
    {
        $areas = LaboratorioArea::with('examenes')
            ->orderBy('id_area')
            ->get();
        return view('livewire.atencion.laboratorio',  compact('areas'));
    }
    public $buscar = '';

    public $examenesPorArea = [];


    public function getExamenesPorAreaFiltradosProperty()
    {
        return collect($this->examenesPorArea)
            ->map(function ($examenes) {

                if ($this->buscar === '') {
                    return $examenes;
                }

                return $examenes->filter(
                    fn($e) =>
                    str_contains(
                        mb_strtolower($e->nombre),
                        mb_strtolower($this->buscar)
                    )
                );
            })
            ->filter(fn($grupo) => $grupo->count());
    }

    /**
     * seleccionar examenes
     * 
     */
    public $usarExamenManual;
    public $examenManual;
    public function guardarOrdenLaboratorio()
    {

        if (count($this->examenesSeleccionados) === 0 && !$this->usarExamenManual) {
            $this->dispatch(
                'alert',
                ['type' => 'info', 'title' => 'Debe seleccionar al menos un examen', 'message' => 'Error']
            );
            return;
        }

        DB::transaction(function () {

            $orden = OrdenLaboratorio::create([
                'id_atencion' => $this->id_atencion,
                'fecha'       => now(),
                'diagnostico' => $this->diagnostico ?? null,
                'estado'      => 'PENDIENTE'
            ]);

            foreach ($this->examenesSeleccionados as $idExamen) {
                OrdenLaboratorioDetalle::create([
                    'id_orden'  => $orden->id_orden,
                    'id_examen' => $idExamen
                ]);
            }

            if ($this->usarExamenManual) {
                $messages = [
                    'examenManual.required' =>  "Ingresar nombre del examen",
                ];

                $rules = [
                    'examenManual' => 'required',

                ];

                $this->validate($rules, $messages);

                OrdenLaboratorioDetalle::create([
                    'id_orden'       => $orden->id_orden,
                    'id_examen'      => null,
                    'examen_manual'  => $this->examenManual
                ]);
            }
        });


        $this->reset('examenesSeleccionados');
        $this->dispatch(
            'alert',
            ['type' => 'success', 'title' => 'Se solicitaron los examenes de ' . $this->nombre_paciente . ' con éxito.', 'message' => 'Exito']
        );
    }
    public $ordenesLaboratorio = [];

    public function cargarOrdenes($id_atencion)
    {
        $this->ordenesLaboratorio = OrdenLaboratorio::with([
            'detalles.examenes.areas'
        ])
            ->where('id_atencion', $id_atencion)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Eliminar
     */
    public function eliminarOrden($id)
    {
        $orden = OrdenLaboratorio::findOrFail($id);

        if ($orden->estado !== 'PENDIENTE') {
            $this->dispatch('alert', [
                'type' => 'warning',
                'title' => 'Acción no permitida',
                'message' => 'Solo se pueden eliminar órdenes pendientes'
            ]);
            return;
        }

        $orden->update(['estado' => 'ANULADO']);
        
        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Orden eliminada',
            'message' => 'La orden fue eliminada correctamente'
        ]);
    }
}
