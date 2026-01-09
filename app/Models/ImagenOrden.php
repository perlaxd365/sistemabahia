<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenOrden extends Model
{

    use HasFactory;

    protected $table = 'orden_imagenes';
    protected $primaryKey = 'id_orden_imagen';

    protected $fillable = [
        'id_atencion',
        'fecha',
        'diagnostico',
        'solicitante',
        'profesional',
        'estado'
    ];

    public function atencion()
    {
        return $this->belongsTo(
            Atencion::class,
            'id_atencion'
        );
    }
    
    public function detalles()
    {
        return $this->hasMany(ImagenOrdenDetalle::class, 'id_orden_imagen');
    }
}
