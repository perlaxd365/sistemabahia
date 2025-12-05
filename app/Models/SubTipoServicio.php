<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTipoServicio extends Model
{
    //
    use HasFactory;
    protected $name = "sub_tipo_servicios";
    protected $primaryKey = 'id_subtipo_servicio';
    protected $fillable = [
        'id_subtipo_servicio',
        'id_tipo_servicio',
        'nombre_subtipo_servicio',
        'estado_subtipo_servicio'
    ];
}
