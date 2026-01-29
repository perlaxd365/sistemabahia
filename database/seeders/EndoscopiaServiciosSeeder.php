<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\SubTipoServicio;
use App\Models\TipoServicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EndoscopiaServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================
        // TIPO
        // ============================

        $tipo = TipoServicio::firstOrCreate(
            ['nombre_tipo_servicio' => 'ENDOSCOPIA'],
            ['estado_tipo_servicio' => true]
        );

        // ============================
        // SUBTIPO
        // ============================

        $subtipo = SubTipoServicio::firstOrCreate(
            [
                'nombre_subtipo_servicio' => 'ENDOSCOPIA',
                'id_tipo_servicio' => $tipo->id_tipo_servicio
            ],
            ['estado_subtipo_servicio' => true]
        );

        // ============================
        // SERVICIOS
        // ============================

        $servicios = [
            ['ENDOSCOPIA (EDA)', 350.00],
            ['COLONOSCOPIA', 400.00],
            ['COLONOSCOPIA IZQUIER', 300.00],
            ['LIGADURA DE HEMORROIDES', 500.00],
            ['PROCTOSCOPIA', 250.00],
            ['PARACENTESIS', 300.00],
        ];

        foreach ($servicios as $srv) {
            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => $srv[0],
                    'id_subtipo_servicio' => $subtipo->id_subtipo_servicio
                ],
                [
                    'precio_servicio' => $srv[1],
                    'estado_servicio' => true,
                    'codigo_sunat' => null,
                    'unidad_sunat' => 'NIU'
                ]
            );
        }
    }
}
