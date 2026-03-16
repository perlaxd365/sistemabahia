<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infraestructura extends Model
{
    protected $table = 'ipress_infraestructuras';
    protected $primaryKey = 'id_infraestructura';

    protected $fillable = [

        'periodo_reporte',
        'codigo_ipress',
        'codigo_ugipress',

        'consultorios_fisicos',
        'consultorios_funcionales',
        'camas_hospitalarias',

        'total_medicos',
        'medicos_serums',
        'medicos_residentes',

        'enfermeras',
        'odontologos',
        'psicologos',
        'nutricionistas',
        'tecnologos_medicos',
        'obstetrices',
        'farmaceuticos',

        'auxiliares_tecnicos',
        'otros_profesionales',

        'ambulancias'

    ];
}
