<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtencionMedicamento extends Model
{
    // //
    use HasFactory;
    protected $name = "atencion_medicamentos";
    protected $primaryKey = 'id_atencion_medicamento';
    protected $fillable = [
        'id_atencion_medicamento',
        'id_atencion',
        'id_medicamento',
        'cantidad',
        'precio',
        'subtotal',
        'tipo'
    ];


    public function medicamentos()
    {
        return $this->belongsTo(
            Medicamento::class,
            'id_medicamento',
            'id_medicamento'
        );
    }
}
