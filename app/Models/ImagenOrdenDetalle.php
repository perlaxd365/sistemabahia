<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenOrdenDetalle extends Model
{
    use HasFactory;

    protected $table = 'orden_imagen_detalles';
    protected $primaryKey = 'id_detalle_imagen';

    protected $fillable = [
        'id_orden_imagen',
        'id_estudio',
        'descripcion_manual'
    ];

    public function orden()
    {
        return $this->belongsTo(ImagenOrden::class, 'id_orden_imagen');
    }

    public function estudio()
    {
        return $this->belongsTo(ImagenEstudio::class, 'id_estudio');
    }

    public function informe()
    {
        return $this->hasOne(ImagenInforme::class, 'id_detalle_imagen');
    }


    // 🔥 Helper clínico
    public function getNombreAttribute()
    {
        return $this->estudio
            ? $this->estudio->nombre
            : $this->descripcion_manual;
    }
}
