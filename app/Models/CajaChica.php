<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaChica extends Model
{
    protected $table = 'caja_chicas';
    protected $primaryKey = 'id_caja_chica';

    protected $fillable = [
        'id_caja_turno',
        'descripcion',
        'monto',
        'responsable',
        'fecha_movimiento',
        'estado',
        'user_id',
    ];

    /* =====================
     | RELACIONES
     ===================== */

    // Turno de caja al que pertenece el egreso
    public function cajaTurno()
    {
        return $this->belongsTo(CajaTurno::class, 'id_caja_turno');
    }

    // Usuario que registró el egreso
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /* =====================
     | SCOPES
     ===================== */

    public function scopeActivos($query)
    {
        return $query->where('estado', 'REGISTRADO');
    }

    public function scopeDelTurno($query, $idCajaTurno)
    {
        return $query->where('id_caja_turno', $idCajaTurno);
    }

    /* =====================
     | HELPERS
     ===================== */

    public function anular()
    {
        $this->update([
            'estado' => 'ANULADO',
        ]);
    }

    public function esActivo(): bool
    {
        return $this->estado === 'REGISTRADO';
    }
}
