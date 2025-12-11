<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atencion extends Model
{
    //
    use HasFactory;
    protected $name = "atencions";
    protected $primaryKey = 'id_atencion';
    protected $fillable = [
        'id_atencion',
        'id_historia',
        'tipo_atencion',
        'fecha_inicio_atencion',
        'fecha_fin_atencion',
        'estado_atencion'
    ];
}
