<?php

namespace Database\Seeders;

use App\Models\SunatSerie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SunatSerie::insert([
            [
                'tipo_comprobante' => 'FACTURA',
                'serie' => 'FFF1',
                'ultimo_numero' => 30,
            ],
            [
                'tipo_comprobante' => 'BOLETA',
                'serie' => 'BBB1',
                'ultimo_numero' => 40,
            ],
        ]);
    }
}
