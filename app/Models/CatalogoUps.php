<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogoUps extends Model
{
    protected $table = 'catalogo_ups';

    protected $fillable = [
        'codigo',
        'nombre',
        'tabla_d1',
        'tabla_g',
        'tabla_i',
    ];

    /**
     * Procedimientos pertenecientes a esta UPS.
     */
    public function procedimientos()
    {
        return $this->hasMany(CatalogoProcedimiento::class, 'catalogo_ups_id');
    }
}
