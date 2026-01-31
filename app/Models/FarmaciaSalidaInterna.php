<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmaciaSalidaInterna extends Model
{
    protected $table = 'farmacia_salidas_internas';

    protected $primaryKey = 'id_salida_interna';

    protected $fillable = [
        'fecha',
        'id_medicamento',
        'cantidad',
        'motivo',
        'id_atencion',
        'area',
        'observacion',
        'id_usuario',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'cantidad' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'id_medicamento');
    }

    public function atencion()
    {
        return $this->belongsTo(Atencion::class, 'id_atencion');
    }

    

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
