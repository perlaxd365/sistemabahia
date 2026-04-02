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
        'modo_atencion',
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
        return $this->hasMany(
            AtencionServicio::class,
            'id_atencion',
            'id_atencion'
        );
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

    public function comprobantes()
    {
        return $this->hasMany(
            Comprobante::class,
            'id_atencion',
            'id_atencion'
        );
    }

    public function estaBloqueada(): bool
    {
        return $this->estado === 'FINALIZADO';
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

        /*
    |--------------------------------------------------------------------------
    | 1️⃣ MÉDICO
    |--------------------------------------------------------------------------
    */
        if ($this->modo_atencion == 'MEDICA' && !$this->id_medico) {
            $errores[] = 'No tiene médico asignado.';
        }

        if ($this->medico && empty($this->medico->colegiatura_cargo)) {
            $errores[] = "El médico no tiene CMP registrado.";
        }

        /*
    |--------------------------------------------------------------------------
    | 2️⃣ DIAGNÓSTICOS
    |--------------------------------------------------------------------------
    */


        if ($this->modo_atencion == 'MEDICA') {

            if (!$this->diagnosticos()->exists()) {
                $errores[] = "No tiene diagnósticos registrados.";
            }
            if ($this->diagnosticos()->where('tipo', 'PRINCIPAL')->count() !== 1) {
                $errores[] = "Debe tener exactamente 1 diagnóstico PRINCIPAL.";
            }
        }




        /*
    |--------------------------------------------------------------------------
    | 3️⃣ PACIENTE
    |--------------------------------------------------------------------------
    */

        if (!$this->paciente || empty($this->paciente->dni)) {
            $errores[] = "Paciente sin DNI válido.";
        }

        /*
    |--------------------------------------------------------------------------
    | 4️⃣ COMPROBANTE (ADAPTADO A MÚLTIPLES)
    |--------------------------------------------------------------------------
    */

        $comprobantes = $this->comprobantes()
            ->whereIn('tipo_comprobante', ['TICKET', 'BOLETA', 'FACTURA'])
            ->get();

        if ($comprobantes->isEmpty()) {
            $errores[] = "No se ha generado comprobante de pago.";
        } else {

            $emitido = $comprobantes
                ->where('estado', 'EMITIDO')
                ->first();

            if (!$emitido) {
                $errores[] = "El comprobante no está emitido.";
            }

            $anulado = $comprobantes
                ->where('estado', 'ANULADO')
                ->first();

            if ($anulado && !$emitido) {
                $errores[] = "El comprobante está anulado.";
            }
        }

        return $errores;
    }
    public function totalFacturado()
    {
        return $this->comprobantes()
            ->where('estado', 'EMITIDO')
            ->sum('total');
    }
    public function totalAtencion()
    {
        $servicios = $this->servicios->sum(
            fn($s) =>
            $s->pivot->precio_unitario * $s->pivot->cantidad
        );

        $medicamentos = $this->medicamentos->sum(
            fn($m) =>
            $m->pivot->precio * $m->pivot->cantidad
        );

        return $servicios + $medicamentos;
    }

    public function tieneServiciosPendientes()
    {
        return $this->servicios()
            ->where('estado', true)
            ->where(function ($q) {
                $q->whereNull('facturado')
                    ->orWhere('facturado', false);
            })
            ->exists();
    }

    public function tieneMedicamentosPendientes()
    {
        return $this->medicamentos()
            ->whereIn('tipo', ['VENTA', 'RECETA'])
            ->where(function ($q) {
                $q->whereNull('facturado')
                    ->orWhere('facturado', false);
            })
            ->exists();
    }

    public function tienePendienteFacturar()
    {
        return $this->servicios()
            ->where('estado', true)
            ->where(function ($q) {
                $q->whereNull('facturado')
                    ->orWhere('facturado', false);
            })
            ->exists()

            || $this->medicamentos()
            ->where('estado', true)
            ->whereIn('tipo', ['VENTA', 'RECETA'])
            ->where(function ($q) {
                $q->whereNull('facturado')
                    ->orWhere('facturado', false);
            })
            ->exists();
    }
    public function consulta()
    {
        return $this->hasOne(Consulta::class, 'id_atencion', 'id_atencion');
    }
    public function actualizarDatosIniciales($tipo, $relato, $modo_atencion)
    {
        if ($this->estaFinalizada()) {
            throw new \Exception("La atención ya está finalizada.");
        }

        $this->update([
            'tipo_atencion' => $tipo,
            'relato_consulta' => $relato,
            'modo_atencion' => $modo_atencion,
        ]);
    }
}
