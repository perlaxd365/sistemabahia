<?php

namespace App\Livewire\Trama;

use App\Models\Atencion;
use App\Models\Infraestructura;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class TramaTac2 extends Component
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
            'diagnosticos.cie10'
        ])
            ->where('tipo_atencion', '02') // 🚨 EMERGENCIA
            ->whereBetween('fecha_inicio_atencion', [
                $this->fecha_inicio,
                $this->fecha_fin
            ])
            ->get();
    }

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

    public function exportarTxtTAC2()
    {
        $ipress = Infraestructura::first();

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

                // 🔥 FORMATEAR CIE10
                $codigo = strtoupper(trim($diag->cie10->codigo));

                if (!str_contains($codigo, '.') && strlen($codigo) >= 3) {
                    $cie10 = substr($codigo, 0, 3) . '.' . substr($codigo, 3);
                } else {
                    $cie10 = $codigo;
                }

                $key = $periodo . '-' . $sexo . '-' . $grupo . '-' . $cie10;

                if (!isset($data[$key])) {
                    $data[$key] = [
                        'periodo' => $periodo,
                        'ipress' => $ipress->codigo_ipress,
                        'ugipress' => $ipress->codigo_ipress,
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

        // 🔥 ORDEN
        $data = collect($data)
            ->sortBy([
                ['sexo', 'asc'],
                ['grupo', 'asc'],
                ['cie10', 'asc']
            ])
            ->values()
            ->toArray();

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

        $anio = $fecha->format('Y');
        $mes = $fecha->format('m');

        $nombre = "{$ipress->codigo_ipress}_{$anio}_{$mes}_TAC2.txt";

        Storage::put("tramas/$nombre", $contenido);

        return Storage::download("tramas/$nombre");
    }

    public function render()
    {
        return view('livewire.trama.trama-tac2');
    }
}
