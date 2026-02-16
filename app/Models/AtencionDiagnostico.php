<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtencionDiagnostico extends Model
{

    use HasFactory;

    protected $table = 'atencion_diagnosticos';

    protected $fillable = [
        'id_atencion',
        'id_cie10',
        'tipo'
    ];

    /**
     * Relación: pertenece a una atención
     */
    public function atencion()
    {
        return $this->belongsTo(Atencion::class, 'id_atencion', 'id_atencion');
    }

    /**
     * Scope para diagnóstico principal
     */
    public function scopePrincipal($query)
    {
        return $query->where('tipo', 'PRINCIPAL');
    }

    /**
     * Scope para secundarios
     */
    public function scopeSecundario($query)
    {
        return $query->where('tipo', 'SECUNDARIO');
    }
    public function cie10()
    {
        return $this->belongsTo(Cie10::class, 'id_cie10');
    }
}
