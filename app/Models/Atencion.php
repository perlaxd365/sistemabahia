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
        'relato_consulta',
        'fecha_inicio_atencion',
        'fecha_fin_atencion',
        'enviado_susalud',
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
    public function getTipoAtencionTextoAttribute()
    {
        return match ($this->tipo_atencion) {
            '01' => 'Consulta Externa',
            '02' => 'Emergencia',
            '03' => 'Hospitalización',
            '05' => 'Procedimiento Ambulatorio',
            default => 'No definido',
        };
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

    public function estaBloqueada(): bool
    {
        return $this->comprobante()
            ->whereIn('estado', ['EMITIDO', 'PENDIENTE'])
            ->exists();
    }

    public function estaFinalizada(): bool
    {
        if ($this->estado == "PROCESO") {
            return false;
        } else {
            return true;
        }
    }
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_atencion');
    }
    public function diagnosticos()
    {
        return $this->hasMany(AtencionDiagnostico::class, 'id_atencion', 'id_atencion');
    }

    public function puedeFinalizar()
    {
        $errores = [];

        // Validar médico
        if (!$this->id_medico) {
            $errores[] = "No tiene médico asignado.";
        }

        if ($this->medico && empty($this->medico->colegiatura_cargo)) {
            $errores[] = "El médico no tiene CMP registrado.";
        }

        // Validar diagnósticos
        if (!$this->diagnosticos()->exists()) {
            $errores[] = "No tiene diagnósticos registrados.";
        }

        if ($this->diagnosticos()->where('tipo', 'PRINCIPAL')->count() !== 1) {
            $errores[] = "Debe tener exactamente 1 diagnóstico PRINCIPAL.";
        }

        // Validar paciente
        if (!$this->paciente || empty($this->paciente->dni)) {
            $errores[] = "Paciente sin DNI válido.";
        }


        // Validar comprobante
        if (!$this->comprobante) {
            $errores[] = "No se ha generado comprobante de pago.";
        }
        if ($this->comprobante && $this->comprobante->estado !== 'EMITIDO') {
            $errores[] = "El comprobante no está emitido.";
        }
        if ($this->comprobante && $this->comprobante->estado === 'ANULADO') {
            $errores[] = "El comprobante está anulado.";
        }
        return $errores;
    }
}
