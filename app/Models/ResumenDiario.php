<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumenDiario extends Model
{
    //
    // 
    use HasFactory;
    protected $name = "resumen_diarios";
    protected $primaryKey = 'id_resumen';
    protected $fillable = [
        'fecha',
        'ticket',
        'estado',
        'respuesta_sunat'
    ];
} 
