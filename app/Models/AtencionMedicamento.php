<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtencionMedicamento extends Model
{
    use HasFactory;

    protected $table = "atencion_medicamentos"; // ✅ corregido
    protected $primaryKey = 'id_atencion_medicamento';

    protected $fillable = [
        'id_atencion',
        'id_medicamento',
        'cantidad',
        'precio',
        'subtotal',
        'facturado',
        'tipo'
    ];

    public function medicamento() // ✅ singular
    {
        return $this->belongsTo(
            Medicamento::class,
            'id_medicamento',
            'id_medicamento'
        );
    }
}
