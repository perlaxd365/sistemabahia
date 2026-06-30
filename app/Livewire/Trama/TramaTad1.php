<?php

namespace App\Livewire\Trama;

use Livewire\Component;
use App\Models\Infraestructura;
use App\Models\AtencionProcedimiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TramaTG extends Component
{
    public $fecha_inicio;
    public $fecha_fin;

    public $data = [];

    public function mount()
    {
        $this->fecha_inicio = now()->startOfMonth()->format('Y-m-d');
        $this->fecha_fin    = now()->endOfMonth()->format('Y-m-d');

        $this->cargarDatos();
    }

    public function updatedFechaInicio()
    {
        $this->cargarDatos();
    }

    public function updatedFechaFin()
    {
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        $this->data = AtencionProcedimiento::query()

            ->join(
                'atencions',
                'atencions.id_atencion',
                '=',
                'atencion_procedimientos.id_atencion'
            )

            ->join(
                'catalogo_procedimientos',
                'catalogo_procedimientos.id',
                '=',
                'atencion_procedimientos.id_catalogo_procedimiento'
            )

            ->join(
                'catalogo_ups',
                'catalogo_ups.id',
                '=',
                'atencion_procedimientos.id_catalogo_ups'
            )

            ->whereBetween(
                'atencions.fecha_inicio_atencion',
                [
                    $this->fecha_inicio,
                    $this->fecha_fin
                ]
            )

            ->select(

                DB::raw("
                    DATE_FORMAT(
                        atencions.fecha_inicio_atencion,
                        '%Y%m'
                    ) as periodo
                "),

                'catalogo_procedimientos.codigo as procedimiento',

                'catalogo_procedimientos.denominacion',

                'catalogo_ups.codigo as ups',

                'catalogo_ups.nombre as nombre_ups',

                DB::raw('SUM(atencion_procedimientos.cantidad) as cantidad')

            )

            ->groupBy(

                DB::raw("
                    DATE_FORMAT(
                        atencions.fecha_inicio_atencion,
                        '%Y%m'
                    )
                "),

                'catalogo_procedimientos.codigo',
                'catalogo_procedimientos.denominacion',
                'catalogo_ups.codigo',
                'catalogo_ups.nombre'

            )

            ->orderBy('catalogo_procedimientos.codigo')

            ->orderBy('catalogo_ups.codigo')

            ->get()

            ->toArray();
    }
    public function exportarTxtTG()
    {
        $ipress = Infraestructura::first();

        if (!$ipress) {

            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Debe configurar la Infraestructura.'
            ]);

            return;
        }

        // Recargar datos antes de exportar
        $this->cargarDatos();

        $contenido = '';

        foreach ($this->data as $row) {

            $linea = implode('|', [

                $row['periodo'],

                $ipress->codigo_ipress,

                $ipress->codigo_ugipress,

                $row['procedimiento'],

                $row['cantidad'],

                $row['ups']

            ]);

            $contenido .= $linea . PHP_EOL;
        }

        if (!Storage::exists('tramas')) {
            Storage::makeDirectory('tramas');
        }

        $anio = Carbon::parse($this->fecha_inicio)->format('Y');
        $mes  = Carbon::parse($this->fecha_inicio)->format('m');

        $nombre = "{$ipress->codigo_ipress}_{$anio}_{$mes}_TADG.txt";

        Storage::put("tramas/{$nombre}", $contenido);

        return Storage::download("tramas/{$nombre}");
    }

    public function render()
    {
        return view('livewire.trama.trama-tg');
    }
}
