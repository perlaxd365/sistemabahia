<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenLaboratorio extends Model
{
    protected $table = 'orden_laboratorios';
    protected $primaryKey = 'id_orden';

    protected $fillable = [
        'id_atencion',
        'fecha',
        'diagnostico',
        'solicitante',
        'profesional',
        'estado'
    ];

    public function atencion()
    {
        return $this->belongsTo(
            Atencion::class,
            'id_atencion'
        );
    }

    
    public function detalles()
    {
        return $this->hasMany(
            OrdenLaboratorioDetalle::class,
            'id_orden'
        );
    }
}

