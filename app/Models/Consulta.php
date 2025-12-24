<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    //
    use HasFactory;
    protected $name = "consultas";
    protected $primaryKey = 'id_consulta';
    protected $fillable = [
        'id_consulta',
        'id_atencion',
        'id_paciente',
        'id_responsable',

        'molestia_consulta',
        'tiempo_consulta',
        'inicio_consulta',
        'curso_consulta',
        'enfermedad_consulta',
        'atecedente_familiar_consulta',
        'atecedente_patologico_consulta',

        'peso_consulta',
        'talla_consulta',
        'imc_consulta',

        'temperatura_consulta',
        'presion_consulta',
        'frecuencia_consulta',
        'saturacion_consulta',
        'examen_consulta',

        'impresion_consulta',
        'examen_auxiliar_consulta',
        'tratamiento_consulta',
        'fecha_consulta',
        'estado_consulta',
    ];
}