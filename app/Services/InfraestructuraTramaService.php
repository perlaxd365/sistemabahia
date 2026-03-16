<?php

use App\Models\Infraestructura;

class InfraestructuraTramaService
{

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
