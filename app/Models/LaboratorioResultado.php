<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaboratorioResultado extends Model
{
    protected $table = 'laboratorio_resultados';
    protected $primaryKey = 'id_resultado';

    protected $fillable = [
        'id_detalle_laboratorio',
        'resultado',
        'observacion',
        'fecha_resultado'
    ];
}
