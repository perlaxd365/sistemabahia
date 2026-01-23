<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaMovimiento extends Model
{
    protected $table = 'caja_movimientos';
    protected $primaryKey = 'id_caja_movimiento';

    protected $fillable = [
        'id_caja_turno',
        'id_usuario',
        'id_referencia',
        'tabla_referencia',
        'tipo',
        'descripcion',
        'monto',
        'responsable'
    ];

    /* =====================
     * RELACIONES
     * ===================== */

    public function turno()
    {
        return $this->belongsTo(CajaTurno::class, 'id_caja_turno');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    /* =====================
     * SCOPES
     * ===================== */

    public function scopeIngresos($query)
    {
        return $query->where('tipo', 'INGRESO');
    }

    public function scopeEgresos($query)
    {
        return $query->where('tipo', 'EGRESO');
    }
}