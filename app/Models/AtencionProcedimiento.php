<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtencionProcedimiento extends Model
{
    protected $table = 'atencion_procedimientos';

    protected $fillable = [

        'id_atencion',

        'id_catalogo_procedimiento',
        'id_catalogo_ups',

        'cantidad'

    ];

    /**
     * Relación con la atención.
     */
    public function procedimiento()
    {
        return $this->belongsTo(
            CatalogoProcedimiento::class,
            'id_catalogo_procedimiento'
        );
    }
    public function atencion()
    {
        return $this->belongsTo(
            Atencion::class,
            'id_atencion',
            'id_atencion'
        );
    }
    public function ups()
    {
        return $this->belongsTo(
            CatalogoUps::class,
            'id_catalogo_ups'
        );
    }
}
