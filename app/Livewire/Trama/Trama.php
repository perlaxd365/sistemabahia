<?php

namespace App\Livewire\Trama;

use App\Models\Atencion;
use App\Models\Ipress;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Trama extends Component
{

    public $fecha_inicio;
    public $fecha_fin;
    public $atencionesFinalizada;
    public $atenciones;

    public $total = 0;
    public $validas = 0;
    public $incompletas = 0;
    public $enviadas = 0;

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
            'diagnosticos'
        ])
            ->whereBetween('fecha_inicio_atencion', [
                $this->fecha_inicio,
                $this->fecha_fin
            ])
            ->orWhere('estado', ["FINALIZADO", "PROCESO"])
            ->get();

        $this->calcularResumen();
    }

    private function calcularResumen()
    {
        $this->total = $this->atenciones->count();

        $this->validas = $this->atenciones->filter(function ($a) {
            return $this->esValidaParaSusalud($a) && !$a->enviado_susalud;
        })->count();

        $this->incompletas = $this->atenciones->filter(function ($a) {
            return !$this->esValidaParaSusalud($a);
        })->count();

        $this->enviadas = $this->atenciones
            ->where('enviado_susalud', true)
            ->count();
    }

    private function esValidaParaSusalud($a)
    {
        return
            $a->paciente &&
            !empty($a->paciente->dni) &&
            $a->medico &&
            !empty($a->medico->colegiatura_cargo) &&
            $a->diagnosticos->where('tipo', 'PRINCIPAL')->count() === 1;
    }

    public function generarTrama()
    {
        if ($this->validas == 0) {

            $this->dispatch('alert', [
                'type' => 'info',
                'title' => 'Sin atenciones válidas',
                'message' => 'No hay registros válidos para exportar.'
            ]);
            return;
        }

        // 🔹 Obtener IPRESS
        $ipress = Ipress::first();

        if (!$ipress) {
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'IPRESS no configurada',
                'message' => 'Debe registrar RENIPRESS y RUC.'
            ]);
            return;
        }

        $registros = $this->atenciones->filter(function ($a) {
            return !$a->enviado_susalud && $this->esValidaParaSusalud($a);
        });

        if (!Storage::exists('tramas')) {
            Storage::makeDirectory('tramas');
        }

        $contenido = '';

        foreach ($registros as $a) {
            $diagnostico = $a->diagnosticos
                ->where('tipo', 'PRINCIPAL')
                ->first();

            $ciePrincipal = optional($diagnostico->cie10)->codigo ?? '';
            $cieSecundarios = $a->diagnosticos
                ->where('tipo', 'SECUNDARIO')
                ->map(function ($diag) {
                    return optional($diag->cie10)->codigo;
                })
                ->filter()        // elimina null o vacíos
                ->values();
            $monto = 0;

            if ($a->comprobante && $a->comprobante->pagos) {
                $monto = $a->comprobante->pagos
                    ->where('estado', 'REGISTRADO')
                    ->sum('monto');
            }
            $linea = implode('|', [

                $ipress->renipress,
                $ipress->ruc,

                $a->paciente->tipo_documento, // TIPO_DOC (1 = DNI)
                $a->paciente->dni,

                strtoupper($a->paciente->apellido_paterno ?? ''),
                strtoupper($a->paciente->apellido_materno ?? ''),
                strtoupper($a->paciente->nombres ?? ''),

                strtoupper($a->paciente->sexo ?? 'M'),

                optional($a->paciente->fecha_nacimiento)
                    ? \Carbon\Carbon::parse($a->paciente->fecha_nacimiento)->format('Ymd')
                    : '',

                \Carbon\Carbon::parse($a->fecha_inicio_atencion)->format('Ymd'),

                $a->tipo_atencion, // TIPO_ATENCION (Ambulatoria)
                'MEDI', // Puedes luego hacerlo dinámico

                $ciePrincipal,
                $cieSecundarios, // CIE10_SECUNDARIO

                1, // TIPO_DIAG
                $a->medico->colegiatura_cargo,

                1, // TIPO_PROF
                1, // MODALIDAD

                $monto
            ]);

            $contenido .= $linea . "\n";
        }

        $nombreArchivo = 'susalud_' . now()->format('Ymd_His') . '.txt';

        Storage::put("tramas/" . $nombreArchivo, $contenido);

        // 🔹 Marcar como enviados automáticamente
        foreach ($registros as $a) {
            $a->update([
                'enviado_susalud' => true
            ]);
        }

        $this->filtrar();

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Trama generada',
            'message' => 'Trama generada correctamente.'
        ]);

        return Storage::download("tramas/" . $nombreArchivo);
    }

    public function marcarNoEnviado()
    {
        Atencion::whereBetween('fecha_inicio_atencion', [
            $this->fecha_inicio,
            $this->fecha_fin
        ])
            ->where('estado', 'FINALIZADO')
            ->where('enviado_susalud', true)
            ->update([
                'enviado_susalud' => false
            ]);

        $this->filtrar();

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Registros actualizado',
            'message' => 'Registros marcados como no enviados.'
        ]);
    }


    public function render()
    {
        return view('livewire.trama.trama');
    }
}
