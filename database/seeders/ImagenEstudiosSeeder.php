<?php

namespace Database\Seeders;

use App\Models\ImagenArea;
use App\Models\ImagenEstudio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImagenEstudiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = ImagenArea::pluck('id_area_imagen', 'nombre');

        $estudios = [

            // 🩻 RAYOS X
            'Rayos X' => [
                'RX Cráneo',
                'RX Senos Paranasales',
                'RX Tórax',
                'RX Columna Cervical',
                'RX Columna Dorsal',
                'RX Columna Lumbar',
                'RX Columna Lumbosacra',
                'RX Pelvis',
                'RX Cadera',
                'RX Hombro',
                'RX Clavícula',
                'RX Brazo',
                'RX Antebrazo',
                'RX Muñeca',
                'RX Mano',
                'RX Fémur',
                'RX Rodilla',
                'RX Pierna',
                'RX Tobillo',
                'RX Pie',
            ],

            // 🫃 ECOGRAFÍA
            'Ecografía' => [
                'Ecografía Abdominal',
                'Ecografía Abdominal Completa',
                'Ecografía Hepatobiliar',
                'Ecografía Renal',
                'Ecografía Prostática',
                'Ecografía Testicular',
                'Ecografía Ginecológica',
                'Ecografía Obstétrica',
                'Ecografía Obstétrica Doppler',
                'Ecografía Mamaria',
                'Ecografía Partes Blandas',
                'Ecografía Tiroides',
            ],

            // 🧠 TOMOGRAFÍA
            'Tomografía' => [
                'TAC Cerebro',
                'TAC Tórax',
                'TAC Abdomen',
                'TAC Abdomen y Pelvis',
                'TAC Columna',
                'TAC Senos Paranasales',
            ],

            // 🧲 RESONANCIA
            'Resonancia Magnética' => [
                'RM Cerebro',
                'RM Columna Cervical',
                'RM Columna Lumbar',
                'RM Rodilla',
                'RM Hombro',
            ],

            // 🩷 MAMOGRAFÍA
            'Mamografía' => [
                'Mamografía Bilateral',
                'Mamografía Unilateral',
            ],

            // 🦴 DENSITOMETRÍA
            'Densitometría Ósea' => [
                'Densitometría Ósea Columna',
                'Densitometría Ósea Cadera',
            ],
        ];

        foreach ($estudios as $areaNombre => $lista) {

            if (!isset($areas[$areaNombre])) {
                continue;
            }

            foreach ($lista as $nombre) {
                ImagenEstudio::firstOrCreate([
                    'id_area_imagen' => $areas[$areaNombre],
                    'nombre' => $nombre,
                ], [
                    'activo' => true,
                ]);
            }
        }
    }
}
