<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    // 
    use HasFactory;
    protected $name = "series";
    protected $primaryKey = 'id_serie';
    protected $fillable = [
        'tipo_comprobante',
        'serie',
        'correlativo',
        'activo'
    ];
}
