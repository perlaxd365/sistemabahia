<?php

namespace App\Models;

use Database\Seeders\LaboratorioAreasSeeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratorioExamen extends Model
{
    //  
    use HasFactory;
   
    protected $table = 'laboratorio_examens';
    protected $primaryKey = 'id_examen';

    protected $fillable = [
        'id_area',
        'nombre',
        'unidad',
        'valor_referencia',
        'codigo'
    ];

    public function areas()
    {
        return $this->belongsTo(LaboratorioArea::class, 'id_area', 'id_area');
    }
}
