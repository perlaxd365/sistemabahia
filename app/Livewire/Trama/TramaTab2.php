<?php

namespace App\Livewire\Trama;

use App\Models\Atencion;
use App\Models\Infraestructura;
use App\Models\Ipress;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class TramaTab2 extends Component
{


    public $fecha_inicio;
    public $fecha_fin;
    public $atenciones = [];

    public function mount()
    {
        $this->fecha_inicio = now()->startOfMonth()->format('Y-m-d');
        $this->fecha_fin = now()->endOfMonth()->format('Y-m-d');

        $this->filtrar();
    }

    public function filtrar()
    {
        $this->atenciones = Atencion::with([
            'paciente',
            'medico',
            'diagnosticos.cie10' // 🔥 CLAVE
        ])
            ->where('tipo_atencion', '05') // 🔥 CLAVE AMBULATORIO
            ->whereBetween('fecha_inicio_atencion', [
                $this->fecha_inicio,
                $this->fecha_fin
            ])
            ->get();
    }

    // 🔥 GRUPO EDAD
    private function grupoEdad($edad)
    {
        return match (true) {
            $edad < 1 => 1,
            $edad <= 4 => 2,
            $edad <= 9 => 3,
            $edad <= 14 => 4,
            $edad <= 19 => 5,
            $edad <= 24 => 6,
            $edad <= 29 => 7,
            $edad <= 34 => 8,
            $edad <= 39 => 9,
            $edad <= 44 => 10,
            $edad <= 49 => 11,
            $edad <= 54 => 12,
            $edad <= 59 => 13,
            $edad <= 64 => 14,
            default => 15,
        };
    }

    // 🔥 EXPORTAR B2
    public function exportarTxtB2()
    {
        $ipress = Ipress::first();

        if (!$ipress) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => 'Configurar IPRESS'
            ]);
            return;
        }

        $data = [];

        foreach ($this->atenciones as $a) {

            if (
                !$a->paciente ||
                !$a->paciente->fecha_nacimiento ||
                !$a->diagnosticos->count()
            ) continue;

            $fecha = Carbon::parse($a->fecha_inicio_atencion);
            $periodo = $fecha->format('Ym');

            $edad = $a->paciente->edad ?? 0;

            $sexo = strtoupper(substr($a->paciente->genero ?? 'M', 0, 1)) == 'M' ? 1 : 2;

            $grupo = $this->grupoEdad($edad);

            foreach ($a->diagnosticos as $diag) {

                if ($diag->tipo !== 'PRINCIPAL') continue;

                if (!$diag->cie10) continue;

                $codigo = strtoupper(trim($diag->cie10->codigo));

                // 👉 agregar punto si no lo tiene
                if (!str_contains($codigo, '.') && strlen($codigo) >= 3) {
                    $cie10 = substr($codigo, 0, 3) . '.' . substr($codigo, 3);
                } else {
                    $cie10 = $codigo;
                }

                if (!$cie10) continue;

                $key = $periodo . '-' . $sexo . '-' . $grupo . '-' . $cie10;

                if (!isset($data[$key])) {
                    $data[$key] = [
                        'periodo' => $periodo,
                        'ipress' => $ipress->renipress,
                        'ugipress' => $ipress->renipress,
                        'sexo' => $sexo,
                        'grupo' => $grupo,
                        'cie10' => $cie10,
                        'pacientes' => []
                    ];
                }

                $dni = $a->paciente->dni;

                if ($dni && !in_array($dni, $data[$key]['pacientes'])) {
                    $data[$key]['pacientes'][] = $dni;
                }
            }
        }

        // 🔥 ORDENAR
        $data = collect($data)
            ->sortBy([
                ['sexo', 'asc'],
                ['grupo', 'asc'],
                ['cie10', 'asc']
            ])
            ->values()
            ->toArray();

        // 🔥 GENERAR TXT
        $contenido = '';

        foreach ($data as $row) {

            $atendidos = count($row['pacientes']);

            $linea = implode('|', [
                $row['periodo'],
                $row['ipress'],
                $row['ugipress'],
                $row['sexo'],
                $row['grupo'],
                $row['cie10'],
                $atendidos
            ]);

            $contenido .= $linea . "\n";
        }

        if (!Storage::exists('tramas')) {
            Storage::makeDirectory('tramas');
        }

        $anio = $fecha->format('Y');
        $mes = $fecha->format('m');

        $ipress = Infraestructura::first();

        $nombre = "{$ipress->codigo_ipress}_{$anio}_{$mes}_TAB2.txt";

        Storage::put("tramas/$nombre", $contenido);

        return Storage::download("tramas/$nombre");
    }
    public function render()
    {
        return view('livewire.trama.trama-tab2');
    }
}
