<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaMovimiento extends Model
{
    protected $primaryKey = 'id_caja_movimiento';

    protected $fillable = [
        'id_caja_turno',
        'tipo',
        'origen',
        'descripcion',
        'monto',
        'id_referencia',
        'responsable',
        'id_usuario',
    ];

    public function cajaTurno()
    {
        return $this->belongsTo(CajaTurno::class, 'id_caja_turno');
    }
}
