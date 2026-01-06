<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenEstudio extends Model
{
    use HasFactory;

    protected $table = 'imagen_estudios';
    protected $primaryKey = 'id_estudio';

    protected $fillable = [
        'id_area_imagen',
        'nombre',
        'activo'
    ];

    public function area()
    {
        return $this->belongsTo(ImagenArea::class, 'id_area_imagen');
    }

    public function detallesOrden()
    {
        return $this->belongsTo(ImagenOrdenDetalle::class, 'id_estudio');
    }
}
