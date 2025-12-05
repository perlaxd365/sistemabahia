<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoServicio extends Model
{
    use HasFactory;
    protected $name = "tipo_servicios";
    protected $primaryKey = 'id_tipo_servicio';
    protected $fillable = [
        'id_tipo_servicio',
        'nombre_tipo_servicio',
        'estado_tipo_servicio'
    ];
}
