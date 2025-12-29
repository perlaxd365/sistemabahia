<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
   
    //  
    use HasFactory;
    protected $name = "compras";
    protected $primaryKey = 'id_compra';
    protected $fillable = [
        'id_proveedor',
        'fecha_compra',
        'tipo_documento',
        'nro_documento',
        'motivo_anulacion',
        'fecha_anulacion',
        'user_anulacion',
        'estado',
        'total'
    ];

     public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'id_compra', 'id_compra');
    }
}
