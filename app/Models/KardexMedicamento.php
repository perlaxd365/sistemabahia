<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KardexMedicamento extends Model
{
      //  
    use HasFactory;
    protected $name = "kardex_medicamentos";
    protected $primaryKey = 'id_kardex';
    protected $fillable = [
        'id_kardex',
        'id_medicamento',
        'id_compra',
        'id_atencion',
        'tipo_movimiento',
        'cantidad',
        'stock_anterior',
        'stock_actual',
        'descripcion',
        'user_id',
    ];

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento');
    }
}