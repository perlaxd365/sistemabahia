<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    //  
    use HasFactory;
    protected $name = "detalle_compras";
    protected $primaryKey = 'id_detallecompra';
    protected $fillable = [
        'id_detallecompra',
        'id_compra',
        'id_medicamento',
        'cantidad',
        'precio',
        'subtotal'
    ];
    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento', 'id_medicamento');
    }
}
