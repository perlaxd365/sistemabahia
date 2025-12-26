<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    //  use HasFactory;
    protected $name = "medicamentos";
    protected $primaryKey = 'id_medicamento';
    protected $fillable = [
        'id_medicamento',
        'nombre',
        'presentacion',
        'concentracion',
        'stock',
        'precio_venta',
        'fecha_vencimiento',
        'lote',
        'estado',
    ];
}
