<?php

namespace Database\Seeders;

use App\Models\LaboratorioArea;
use App\Models\LaboratorioExamen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LaboratorioRayosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        
        $area = LaboratorioArea::firstOrCreate([
            'nombre' => 'Rayos X',
            'codigo' => 'RX',
        ]);

        $examenes = [
            'RX Cráneo',
            'RX Huesos propios de la nariz',
            'RX Maxilares',
            'RX Senos paranasales',
            'RX Tórax',
            'RX Esternón',
            'RX Parrilla costal',
            'RX Mano',
            'RX Muñeca',
            'RX Antebrazo',
            'RX Codo',
            'RX Columna vertebral',
            'RX Columna dorsal',
            'RX Columna cervical',
            'RX Fémur',
            'RX Húmero',
            'RX Hombro y clavícula',
            'RX Omóplato y clavícula',
            'RX Pie',
            'RX Tobillo',
            'RX Rodilla',
            'RX Rodilla COM',
            'RX Pelvis',
            'RX Cadera',
            'RX Lumbosacro',
            'RX Sacrococcígea',
        ];

        foreach ($examenes as $nombre) {
            LaboratorioExamen::firstOrCreate([
                'id_area' => $area->id_area,
                'nombre' => $nombre
            ]);
        }
    }
}
