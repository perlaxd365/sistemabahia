<?php

namespace App\Livewire\Trama;

use App\Models\Infraestructura;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class TramaTad1 extends Component
{
    public $fecha_inicio;
    public $fecha_fin;
    public $data = [];
    public function mount()
    {
        $this->fecha_inicio = now()->startOfMonth()->format('Y-m-d');
        $this->fecha_fin = now()->endOfMonth()->format('Y-m-d');
        $this->data = [
            [
                'ups' => '241800',
                'nombre' => 'MEDICINA',
                'ingresos' => 1,
                'egresos' => 1,
                'estancias' => 2,
                'pacientes_dia' => 2,
                'camas' => 1,
                'dias_cama' => 30,
                'fallecidos' => 0
            ],
        ];
    }

    public function exportarTxtTAD1()
    {
        $ipress = Infraestructura::first();

        if (!$ipress) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Configurar Infraestructura'
            ]);
            return;
        }

        $periodo = Carbon::parse($this->fecha_inicio)->format('Ym');

        // 🔥 DATOS DE PRUEBA (SIN CIRUGÍA)
        $data = [
            [
                'ups' => '241800', // MEDICINA
                'ingresos' => 1,
                'egresos' => 1,
                'estancias' => 2,
                'pacientes_dia' => 2,
                'camas' => 1,
                'dias_cama' => 30,
                'fallecidos' => 0
            ],
        ];

        $contenido = '';

        foreach ($data as $row) {

            $linea = implode('|', [
                $periodo,
                $ipress->codigo_ipress,
                $ipress->codigo_ipress,
                $row['ups'],
                $row['ingresos'],
                $row['egresos'],
                $row['estancias'],
                $row['pacientes_dia'],
                $row['camas'],
                $row['dias_cama'],
                $row['fallecidos']
            ]);

            $contenido .= $linea . "\n";
        }

        if (!Storage::exists('tramas')) {
            Storage::makeDirectory('tramas');
        }

        $anio = Carbon::parse($this->fecha_inicio)->format('Y');
        $mes = Carbon::parse($this->fecha_inicio)->format('m');

        $nombre = "{$ipress->codigo_ipress}_{$anio}_{$mes}_TAD1.txt";

        Storage::put("tramas/$nombre", $contenido);

        return Storage::download("tramas/$nombre");
    }
    public function render()
    {
        return view('livewire.trama.trama-tad1');
    }
}
