<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogoProcedimiento extends Model
{

    protected $table = 'catalogo_procedimientos';

    protected $fillable = [
        'codigo',
        'denominacion',
        'grupo_codigo',
        'grupo_nombre',
        'seccion_codigo',
        'seccion_nombre',
        'subseccion_codigo',
        'subseccion_nombre',
        'catalogo_ups_id',
        'situacion',
    ];

    /**
     * UPS a la que pertenece el procedimiento.
     */
    public function ups()
    {
        return $this->belongsTo(CatalogoUps::class, 'catalogo_ups_id');
    }

    /**
     * Procedimientos registrados en las atenciones.
     */
    public function atenciones()
    {
        return $this->hasMany(AtencionProcedimiento::class, 'id_catalogo_procedimiento');
    }
}
