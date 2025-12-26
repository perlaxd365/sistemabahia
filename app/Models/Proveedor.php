<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    //
    use HasFactory;
    protected $name = "proveedors";
    protected $primaryKey = 'id_proveedor';
    protected $fillable = [
        'id_proveedor',
        'razon_social',
        'ruc',
        'telefono',
        'email',
        'direccion',
        'contacto',
        'estado',
    ];
}
