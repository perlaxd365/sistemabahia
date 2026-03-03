<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenLaboratorio extends Model
{
    protected $table = 'orden_laboratorios';
    protected $primaryKey = 'id_orden';

    protected $fillable = [
        'id_atencion',
        'fecha',
        'diagnostico',
        'solicitante',
        'profesional',

        'ruta_pdf_resultado',
        'fecha_subida_pdf',
        'id_usuario_subida_pdf',
        'estado'
    ];

    public function atencion()
    {
        return $this->belongsTo(
            Atencion::class,
            'id_atencion'
        );
    }


    public function detalles()
    {
        return $this->hasMany(
            OrdenLaboratorioDetalle::class,
            'id_orden'
        );
    }

    // ✅ Helper producción
    public function puedeActualizarPdf()
    {
        return $this->estado !== 'FINALIZADO';
    }
}
