<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\SubTipoServicio;
use App\Models\TipoServicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EspecialidadesServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ============================
        // TIPO SERVICIO
        // ============================

        $tipo = TipoServicio::firstOrCreate(
            ['nombre_tipo_servicio' => 'CONSULTAS MEDICAS'],
            ['estado_tipo_servicio' => true]
        );

        // ============================
        // ESPECIALIDADES + PRECIO BASE
        // ============================

        $especialidades = [

            ['nombre' => 'ALQUILER DE CONSULTORIO', 'precio' => 20],
            ['nombre' => 'CONSULTA EMERGENCIA', 'precio' => 80],

            ['nombre' => 'CARDIOLOGIA', 'precio' => 130],
            ['nombre' => 'CARDIOVASCULAR', 'precio' => 150],

            ['nombre' => 'CIRUGIA', 'precio' => 100],
            ['nombre' => 'CIRUGIA ESTETICA', 'precio' => 100],
            ['nombre' => 'CIRUGIA ONCOLOGICA', 'precio' => 150],

            ['nombre' => 'ENDOCRINOLOGIA', 'precio' => 120],
            ['nombre' => 'GASTROENTEROLOGIA', 'precio' => 100],
            ['nombre' => 'GINECOLOGIA', 'precio' => 100],

            ['nombre' => 'MEDICINA GENERAL', 'precio' => 60],
            ['nombre' => 'MEDICINA INTERNA', 'precio' => 130],

            ['nombre' => 'NEUMOLOGIA', 'precio' => 150],
            ['nombre' => 'NEUROLOGIA', 'precio' => 150],
            ['nombre' => 'NEUROCIRUGIA', 'precio' => 220],

            ['nombre' => 'NUTRICION', 'precio' => 130],
            ['nombre' => 'ONCOLOGIA', 'precio' => 130],
            ['nombre' => 'OTORRINOLARINGOLOGIA', 'precio' => 130],

            ['nombre' => 'PEDIATRIA', 'precio' => 120],
            ['nombre' => 'PEDIATRIA NEONATOLOGIA', 'precio' => 250],

            ['nombre' => 'PSICOLOGIA', 'precio' => 100],
            ['nombre' => 'PSIQUIATRIA', 'precio' => 150],

            ['nombre' => 'REUMATOLOGIA', 'precio' => 140],
            ['nombre' => 'TRAUMATOLOGIA', 'precio' => 100],
            ['nombre' => 'UROLOGIA', 'precio' => 130],

            ['nombre' => 'VACUNAS RN', 'precio' => 70],
        ];

        // ============================
        // CREACIÓN SUBTIPO + SERVICIO
        // ============================

        foreach ($especialidades as $item) {

            $subtipo = SubTipoServicio::firstOrCreate(
                [
                    'nombre_subtipo_servicio' => $item['nombre'],
                    'id_tipo_servicio' => $tipo->id_tipo_servicio
                ],
                ['estado_subtipo_servicio' => true]
            );

            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => 'CONSULTA ' . $item['nombre'],
                    'id_subtipo_servicio' => $subtipo->id_subtipo_servicio
                ],
                [
                    'precio_servicio' => $item['precio'],
                    'estado_servicio' => true,
                    'unidad_sunat' => 'NIU'
                ]
            );
        }
    }
}
