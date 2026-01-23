<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SunatSerie extends Model
{
    //
    use HasFactory;
    protected $name = "sunat_series";
    protected $primaryKey = 'id';
    protected $fillable = [
        'tipo_comprobante',
        'serie',
        'ultimo_numero'
    ];
}
