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
        'resumen_diario_id',
        'serie',
        'numero',
        'id_atencion',
        'id_paciente',
        'id_cliente',
        'fecha_emision',
        'subtotal',
        'igv',
        'con_igv',
        'total',
        'metodo_pago',
        'recargo',
        'total_cobrado',
        'estado',
        'sunat_codigo',
        'sunat_descripcion',
        'sunat_hash',
        'sunat_descripcion',
        'sunat_qr',
        'xml_url',
        'cdr_url',
        'pdf_url'
    ];
    //
    public function detalles()
    {
        return $this->hasMany(
            ComprobanteDetalle::class,
            'id_comprobante',
            'id_comprobante'
        );
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
    public function paciente()
    {
        return $this->belongsTo(User::class, 'id_paciente');
    }
    public function atencion()
    {
        return $this->belongsTo(Atencion::class, 'id_atencion');
    }
    //// pagos
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_comprobante');
    }

    // Pago total registrado
    public function totalPagado()
    {
        return $this->pagos()
            ->where('estado', 'REGISTRADO')
            ->sum('monto');
    }

    // ¿Está pagado?
    public function estaPagado()
    {
        return $this->totalPagado() >= $this->total;
    }
}
