<?php

namespace App\Livewire\Trama;

use App\Models\Atencion;
use App\Models\Infraestructura;
use App\Models\Ipress;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class TramaTab1 extends Component
{


    public $fecha_inicio;
    public $fecha_fin;
    public $atenciones = [];
    public $total = 0;

    public function mount()
    {
        $this->fecha_inicio = now()->startOfMonth()->format('Y-m-d');
        $this->fecha_fin = now()->endOfMonth()->format('Y-m-d');

        $this->filtrar();
    }

    public function filtrar()
    {
        $this->atenciones = Atencion::with(['paciente', 'medico'])
            ->where('tipo_atencion', '05') // 🔥 CLAVE AMBULATORIO
            ->whereBetween('fecha_inicio_atencion', [
                $this->fecha_inicio,
                $this->fecha_fin
            ])
            ->get();

        // ✅ calcular totales aquí
        $this->total = $this->atenciones->sum('total'); // ajusta campo
        $this->total_importe = $this->atenciones->sum('importe'); // si existe
        $this->cantidad = $this->atenciones->count();
    }

    // 🔥 GRUPO EDAD SUSALUD
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

    // 🔥 EXPORTAR TXT B1
    public function exportarTxtB1()
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

            if (!$a->paciente || !$a->paciente->fecha_nacimiento) continue;

            $fecha = Carbon::parse($a->fecha_inicio_atencion);

            $periodo = $fecha->format('Ym');

            $edad = $a->paciente->edad ?? 0;

            $sexo = strtoupper(substr($a->paciente->genero ?? 'M', 0, 1)) == 'M' ? 1 : 2;

            $grupo = $this->grupoEdad($edad);

            // 🔥 CLAVE DE AGRUPACIÓN
            $key = $periodo . '-' . $sexo . '-' . $grupo;

            if (!isset($data[$key])) {
                $data[$key] = [
                    'periodo' => $periodo,
                    'ipress' => $ipress->renipress,
                    'ugipress' => $ipress->renipress,
                    'sexo' => $sexo,
                    'grupo' => $grupo,
                    'medicas' => 0,
                    'no_medicas' => 0,
                    'pacientes' => []
                ];
            }

            // 👉 sumar atenciones
            if ($a->medico && !empty($a->medico->colegiatura_cargo)) {
                $data[$key]['medicas']++;
            } else {
                $data[$key]['no_medicas']++;
            }

            // 👉 pacientes únicos
            $dni = $a->paciente->dni;
            if ($dni && !in_array($dni, $data[$key]['pacientes'])) {
                $data[$key]['pacientes'][] = $dni;
            }
        }

        // 🔥 GENERAR TXT
        $contenido = '';
        $data = collect($data)
            ->sortBy([
                ['sexo', 'asc'],
                ['grupo', 'asc']
            ])
            ->values()
            ->toArray();
        foreach ($data as $row) {

            $atendidos = count($row['pacientes']);

            $linea = implode('|', [
                $row['periodo'],
                $row['ipress'],
                $row['ugipress'],
                $row['sexo'],
                $row['grupo'],
                $row['medicas'],
                $row['no_medicas'],
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

        $nombre = "{$ipress->codigo_ipress}_{$anio}_{$mes}_TAB1.txt";

        Storage::put("tramas/$nombre", $contenido);

        return Storage::download("tramas/$nombre");
    }
    public $total_importe = 0;
    public $cantidad = 0;

    public function calcularTotales()
    {
        $this->total_importe = $this->atenciones->sum('importe');
        $this->cantidad = $this->atenciones->count();
    }
    public function render()
    {

        return view('livewire.trama.trama-tab1');
    }
}
