<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comprobante extends Model
{
    //  
    use HasFactory;
    protected $name = "comprobantes";
    protected $primaryKey = 'id_comprobante';
    protected $fillable = [
        'tipo_comprobante',
        'serie',
        'numero',
        'id_atencion',
        'id_paciente',
        'id_cliente',
        'fecha_emision',
        'subtotal',
        'igv',
        'total',
        'estado',
        'sunat_codigo',
        'sunat_descripcion',
        'sunat_hash',
        'sunat_qr',
        'xml_url',
        'cdr_url',
        'pdf_url'
    ];
    //
}
