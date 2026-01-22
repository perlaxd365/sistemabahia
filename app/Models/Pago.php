<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    //  
    use HasFactory;
    protected $name = "pagos";
    protected $primaryKey = 'id_pago';
    protected $fillable = [
        'id_pago',
        'id_comprobante',
        'id_atencion',
        'id_caja_turno',
        'tipo_pago',
        'monto',
        'fecha_pago',
        'user_id',
        'estado'
    ];
    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class, 'id_comprobante');
    }

    public function atencion()
    {
        return $this->belongsTo(Atencion::class, 'id_atencion');
    }

    public function cajaTurno()
    {
        return $this->belongsTo(CajaTurno::class, 'id_caja_turno');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
