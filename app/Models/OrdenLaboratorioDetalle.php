<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenLaboratorioDetalle extends Model
{
    protected $table = 'orden_laboratorio_detalles';
    protected $primaryKey = 'id_detalle_laboratorio';

    protected $fillable = [
        'id_orden',
        'id_examen',
        'examen_manual'
    ];

    public function examenes()
    {
        return $this->belongsTo(
            LaboratorioExamen::class,
            'id_examen'
        );
    }

    

    public function resultados()
    {
        return $this->hasOne(
            LaboratorioResultado::class,
            'id_detalle_laboratorio'
        );
    }
}
