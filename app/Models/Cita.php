<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $primaryKey = 'id_cita';

    protected $fillable = [
        'fecha_cita',
        'hora_cita',
        'id_paciente',
        'medico',
        'motivo',
        'estado'
    ];

    public function paciente()
    {
        return $this->belongsTo(User::class, 'id_paciente');
    }

    public function medico()
    {
        return $this->belongsTo(User::class, 'medico');
    }
}
