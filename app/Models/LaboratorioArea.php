<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratorioArea extends Model
{
  
   use HasFactory;

    protected $table = 'laboratorio_areas';
    protected $primaryKey = 'id_area';

    protected $fillable = [
        'nombre',
        'codigo'
    ];

    public function examenes()
    {
        return $this->hasMany(LaboratorioExamen::class, 'id_area', 'id_area');
    }

}
