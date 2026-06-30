<?php

namespace App\Livewire\Atencion;

use App\Models\Atencion;
use App\Models\AtencionProcedimiento;
use App\Models\CatalogoProcedimiento;
use Livewire\Component;
use App\Models\CatalogoUps;

class Procedimiento extends Component
{
    public $id_atencion;
    public $atencion;

    public $buscar = '';

    public $resultados = [];

    public $tieneProcedimientos = false;

    /////////////////// UPS 
    public $buscarUps = '';
    public $resultadosUps = [];

    public $id_catalogo_ups = null;
    public $upsSeleccionada = null;

    public $cantidad = 1;

    public $id_catalogo_procedimiento = null;
    public $procedimientoSeleccionado = null;

    public function mount($id_atencion)
    {
        $this->id_atencion = $id_atencion;

        $this->atencion = Atencion::findOrFail($id_atencion);

        // Si ya existen procedimientos registrados,
        // activar automáticamente el switch.
        $this->tieneProcedimientos =
            AtencionProcedimiento::where(
                'id_atencion',
                $this->id_atencion
            )->exists();
    }

    protected $listeners = [
        'refreshProcedimientos' => '$refresh'
    ];

    /**
     * ======================================
     * BUSCADOR
     * ======================================
     */
    public function updatedBuscar()
    {
        if (strlen($this->buscar) < 2) {

            $this->resultados = [];

            return;
        }

        $this->resultados = CatalogoProcedimiento::query()

            ->where('situacion', 'ACTIVO')

            ->where(function ($q) {

                $q->where('codigo', 'like', "%{$this->buscar}%")
                    ->orWhere('denominacion', 'like', "%{$this->buscar}%")
                    ->orWhere('grupo_nombre', 'like', "%{$this->buscar}%")
                    ->orWhere('seccion_nombre', 'like', "%{$this->buscar}%")
                    ->orWhere('subseccion_nombre', 'like', "%{$this->buscar}%");
            })

            ->orderBy('codigo')

            ->limit(15)

            ->get();
    }

    /**
     * ======================================
     * AGREGAR PROCEDIMIENTO
     * ======================================
     */
    public function seleccionar($id)
    {
        $procedimiento = CatalogoProcedimiento::findOrFail($id);

        $this->id_catalogo_procedimiento = $procedimiento->id;

        $this->procedimientoSeleccionado = $procedimiento;

        $this->buscar =

            $procedimiento->codigo .
            ' - ' .
            $procedimiento->denominacion;

        $this->resultados = [];
    }

    /**
     * bUSCADOR UPS
     */
    public function updatedBuscarUps()
    {
        if (strlen($this->buscarUps) < 2) {

            $this->resultadosUps = [];

            return;
        }

        $this->resultadosUps =

            CatalogoUps::query()

            ->where('tabla_g', 1)

            ->where(function ($q) {

                $q->where('codigo', 'like', "%{$this->buscarUps}%")

                    ->orWhere('nombre', 'like', "%{$this->buscarUps}%");
            })

            ->orderBy('codigo')

            ->limit(15)

            ->get();
    }

    /**
     * sELECCIONADOR UPS
     */
    public function seleccionarUps($id)
    {
        $ups = CatalogoUps::findOrFail($id);

        $this->id_catalogo_ups = $ups->id;

        $this->upsSeleccionada = $ups;

        $this->buscarUps =

            $ups->codigo .
            ' - ' .
            $ups->nombre;

        $this->resultadosUps = [];
    }
/**
 * aGREGAR UPS
 */

    public function agregar()
    {
        $this->validate([

            'id_catalogo_procedimiento' => 'required',

            'id_catalogo_ups' => 'required',

            'cantidad' => 'required|integer|min:1'

        ]);

        AtencionProcedimiento::create([

            'id_atencion' => $this->id_atencion,

            'id_catalogo_procedimiento' => $this->id_catalogo_procedimiento,

            'id_catalogo_ups' => $this->id_catalogo_ups,

            'cantidad' => $this->cantidad

        ]);

        $this->reset([

            'buscar',

            'buscarUps',

            'cantidad',

            'id_catalogo_procedimiento',

            'id_catalogo_ups',

            'procedimientoSeleccionado',

            'upsSeleccionada'

        ]);

        $this->cantidad = 1;
    }
    /**
     * ======================================
     * ELIMINAR
     * ======================================
     */
    public function eliminar($id)
    {
        AtencionProcedimiento::find($id)?->delete();

        $this->tieneProcedimientos =
            AtencionProcedimiento::where(
                'id_atencion',
                $this->id_atencion
            )->exists();
    }

    /**
     * ======================================
     * SWITCH
     * ======================================
     */
    public function updatedTieneProcedimientos($valor)
    {
        if (!$valor) {

            AtencionProcedimiento::where(
                'id_atencion',
                $this->id_atencion
            )->delete();

            $this->buscar = '';

            $this->resultados = [];
        }
    }

    /**
     * ======================================
     * RENDER
     * ======================================
     */
    public function render()
    {
        return view(
            'livewire.atencion.procedimiento',
            [

                'procedimientos' => AtencionProcedimiento::where(
                    'id_atencion',
                    $this->id_atencion
                )

                    ->with([
                        'procedimiento.ups'
                    ])

                    ->get()

            ]
        );
    }
}
