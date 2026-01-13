<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprobanteDetalle extends Model
{
    //
    //  
    use HasFactory;
    protected $name = "comprobante_detalles";
    protected $primaryKey = 'id_detalle_comprobante';
    protected $fillable = [
        'id_comprobante',
        'descripcion',
        'codigo',
        'unidad',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'tipo_afectacion_igv',
        'igv',
    ];
}
