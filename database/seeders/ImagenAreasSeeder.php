<?php

namespace Database\Seeders;

use App\Models\ImagenArea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImagenAreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            ['nombre' => 'Rayos X', 'codigo' => 'RX'],
            ['nombre' => 'Ecografía', 'codigo' => 'ECO'],
            ['nombre' => 'Tomografía', 'codigo' => 'TAC'],
            ['nombre' => 'Resonancia Magnética', 'codigo' => 'RM'],
            ['nombre' => 'Mamografía', 'codigo' => 'MAMO'],
            ['nombre' => 'Densitometría Ósea', 'codigo' => 'DMO'],
            ['nombre' => 'Otros Imágenes', 'codigo' => 'IMG-OTR'],
        ];

        foreach ($areas as $area) {
            ImagenArea::firstOrCreate(
                ['codigo' => $area['codigo']],
                $area
            );
        }
    }
}
