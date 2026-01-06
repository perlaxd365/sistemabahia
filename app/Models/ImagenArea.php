<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenArea extends Model
{
    use HasFactory;

    protected $table = 'imagen_areas';
    protected $primaryKey = 'id_area_imagen';

    protected $fillable = [
        'nombre',
        'codigo'
    ];

    public function estudios()
    {
        return $this->hasMany(ImagenEstudio::class, 'id_area_imagen');
    }
}
