<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signo extends Model
{
     //
    use HasFactory;
    protected $name = "signos";
    protected $primaryKey = 'id_signo';
    protected $fillable = [
        'id_signo',
        'id_atencion',
        'id_paciente',
        'sistolica_derecha',
        'diastolica_derecha',
        'sistolica_izquierda',
        'diastolica_izquierda',
        'frecuencia_cardiaca',
        'fecha_signo',
        'estado_signo'
    ];
}
