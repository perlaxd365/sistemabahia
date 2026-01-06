<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenInforme extends Model
{

    use HasFactory;

    protected $table = 'imagen_informes';
    protected $primaryKey = 'id_informe';

    protected $fillable = [
        'id_detalle_imagen',
        'informe',
        'archivo',
        'fecha_informe'
    ];

     public function detalle()
    {
        return $this->belongsTo(ImagenOrdenDetalle::class, 'id_detalle_imagen');
    }
}
