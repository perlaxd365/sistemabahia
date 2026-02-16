<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cie10 extends Model
{
    protected $table = 'cie10';

    protected $fillable = [
        'codigo',
        'descripcion'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function diagnosticos()
    {
        return $this->hasMany(AtencionDiagnostico::class, 'id_cie10');
    }
}
