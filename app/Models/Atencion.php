<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atencion extends Model
{
    //
    use HasFactory;
    protected $name = "atencions";
    protected $primaryKey = 'id_atencion';
    protected $fillable = [
        'id_paciente',
        'id_medico',
        'id_atencion',
        'id_historia',
        'id_responsable',
        'tipo_atencion',
        'fecha_inicio_atencion',
        'fecha_fin_atencion',
        'estado'
    ];


    public function paciente()
    {
        return $this->belongsTo(
            User::class,
            'id_paciente',
            'id'
        );
    }
    public function medico()
    {
        return $this->belongsTo(
            User::class,
            'id_medico',
            'id'
        );
    }
    public function historia()
    {
        return $this->belongsTo(
            Historia::class,
            'id_historia'
        );
    }

    // Servicios médicos
    public function servicios()
    {
        return $this->belongsToMany(
            Servicio::class,
            'atencion_servicios',
            'id_atencion',
            'id_servicio'
        )->withPivot(['precio_unitario', 'cantidad'])
            ->withTimestamps();
    }
    // Medicamentos recetados / vendidos en atención
    public function medicamentos()
    {
        return $this->belongsToMany(
            Medicamento::class,
            'atencion_medicamentos',
            'id_atencion',
            'id_medicamento'
        )->withPivot(['precio', 'cantidad'])
            ->withTimestamps();
    }

    /* =====================================================
     |  FACTURACIÓN
     ===================================================== */

    // Un comprobante por atención
    public function comprobante()
    {
        return $this->hasOne(
            Comprobante::class,
            'id_atencion',
            'id_atencion'
        );
    }
}
