<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtencionServicio extends Model
{
    //  //
    use HasFactory;
    protected $name = "atencion_servicios";
    protected $primaryKey = 'id_atencion_servicio';
    protected $fillable = [
        'id_atencion_servicio',
        'id_atencion',
        'id_servicio',
        'id_profesional',
        'id_responsable',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'estado'
    ];
}
