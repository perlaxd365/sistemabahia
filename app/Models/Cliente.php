<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //  
    use HasFactory;
    protected $name = "clientes";
    protected $primaryKey = 'id_cliente';
    protected $fillable = [
        'ruc',
        'razon_social',
        'direccion',
        'email'
    ];
}
