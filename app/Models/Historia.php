<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historia extends Model
{
    //
    use HasFactory;
    protected $name = "historias";
    protected $primaryKey = 'id_historia';
    protected $fillable = [
        'id_historia',
        'id_paciente',
        'fecha_historia',
        'estado_historia'
    ];
}
