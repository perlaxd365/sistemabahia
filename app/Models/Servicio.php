<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    //
    use HasFactory;
    protected $name = "servicios";
    protected $primaryKey = 'id_servicio';
    protected $fillable = [
        'id_servicio',
        'id_subtipo_servicio',
        'nombre_servicio',
        'precio_servicio',
        'estado_servicio'
    ];

    public function subtipo()
    {
        return $this->belongsTo(SubTipoServicio::class, 'id_subtipo_servicio');
    }
}
