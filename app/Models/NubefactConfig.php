<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NubefactConfig extends Model
{
    protected $table = 'nubefact_config';
    protected $primaryKey = 'id_nubefact';

    protected $fillable = [
        'ruta',
        'token',
        'produccion',
        'activo',
    ];

    public $timestamps = true;
}
