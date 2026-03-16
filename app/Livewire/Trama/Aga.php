<?php

namespace App\Livewire\Trama;

use App\Models\Infraestructura;
use InfraestructuraTramaService;
use Livewire\Component;

class Aga extends Component
{
    public function render()
    {

        $datos = Infraestructura::all();

        return view('livewire.trama.aga', compact('datos'));
    }

    public function exportarTxt()
    {

        $periodo = now()->format('Ym');

        $trama = $this->generar($periodo);

        $nombre = "TRAMA_INFRAESTRUCTURA_{$periodo}.txt";

        return response()->streamDownload(function () use ($trama) {

            echo $trama;
        }, $nombre);
    }

    public function generar($periodo)
    {

        $data = Infraestructura::first();

        return implode('|', [

            $periodo,
            $data->codigo_ipress,
            $data->codigo_ugipress,
            $data->consultorios_fisicos,
            $data->consultorios_funcionales,
            $data->camas_hospitalarias,
            $data->total_medicos,
            $data->medicos_serums,
            $data->medicos_residentes,
            $data->enfermeras,
            $data->odontologos,
            $data->psicologos,
            $data->nutricionistas,
            $data->tecnologos_medicos,
            $data->obstetrices,
            $data->farmaceuticos,
            $data->auxiliares_tecnicos,
            $data->otros_profesionales,
            $data->ambulancias

        ]);
    }
}
