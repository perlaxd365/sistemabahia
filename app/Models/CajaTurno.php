<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaTurno extends Model
{
    protected $table = 'caja_turnos';
    protected $primaryKey = 'id_caja_turno';

    protected $fillable = [
        'id_caja_turno',
        'fecha_apertura',
        'fecha_cierre',
        'monto_apertura',
        'monto_cierre',
        'estado',
        'id_usuario_apertura',
        'id_usuario_cierre',
    ];

    protected $casts = [
        'fecha_apertura' => 'datetime',
        'fecha_cierre'   => 'datetime',
        'monto_apertura' => 'decimal:2',
        'monto_cierre'   => 'decimal:2',
    ];

    /* =====================
     | RELACIONES
     ===================== */

    public function usuarioApertura()
    {
        return $this->belongsTo(User::class, 'id_usuario_apertura');
    }

    public function usuarioCierre()
    {
        return $this->belongsTo(User::class, 'id_usuario_cierre');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_caja_turno');
    }

    public function egresos()
    {
        return $this->hasMany(CajaChica::class, 'id_caja_turno');
    }

    /* =====================
     | SCOPES
     ===================== */

    public function scopeAbierto($query)
    {
        return $query->where('estado', 'ABIERTO');
    }
    // 🔑 Turno activo (único)
    // ✅ SCOPE CORRECTO
    public function scopeTurnoAbierto($query)
    {
        return $query->where('estado', 'ABIERTO');
    }

    /* =====================
     | HELPERS
     ===================== */

    public function totalIngresos()
    {
        return $this->pagos()
            ->where('estado', 'REGISTRADO')
            ->sum('monto');
    }

    public function totalEgresos()
    {
        return $this->egresos()
            ->where('estado', 'REGISTRADO')
            ->sum('monto');
    }
    public function totalPorTipo(string $tipo)
    {
        return $this->pagos()
            ->where('tipo_pago', $tipo)
            ->sum('monto');
    }
    public function totalTurno()
    {
        return $this->pagos()->sum('monto');
    }
    public function diferenciaCierre()
    {
        if (is_null($this->monto_cierre)) {
            return null;
        }

        return $this->monto_cierre - $this->totalTurno();
    }



    public function movimientos()
    {
        return $this->hasMany(CajaMovimiento::class, 'id_caja_turno');
    }


    public function saldo()
    {
        return $this->monto_apertura
            + $this->totalIngresos()
            - $this->totalEgresos();
    }
}
