<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\SubTipoServicio;
use App\Models\TipoServicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TomografiaServiciosSeeder extends Seeder
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
            ['nombre_tipo_servicio' => 'TOMOGRAFIA'],
            ['estado_tipo_servicio' => true]
        );

        // ============================
        // SUBTIPOS
        // ============================

        $subSC = SubTipoServicio::firstOrCreate(
            [
                'nombre_subtipo_servicio' => 'TOMOGRAFIA SIN CONTRASTE',
                'id_tipo_servicio' => $tipo->id_tipo_servicio
            ],
            ['estado_subtipo_servicio' => true]
        );

        $subCC = SubTipoServicio::firstOrCreate(
            [
                'nombre_subtipo_servicio' => 'TOMOGRAFIA CON CONTRASTE',
                'id_tipo_servicio' => $tipo->id_tipo_servicio
            ],
            ['estado_subtipo_servicio' => true]
        );

        $subEspecial = SubTipoServicio::firstOrCreate(
            [
                'nombre_subtipo_servicio' => 'TOMOGRAFIA ESPECIAL',
                'id_tipo_servicio' => $tipo->id_tipo_servicio
            ],
            ['estado_subtipo_servicio' => true]
        );

        $subAngio = SubTipoServicio::firstOrCreate(
            [
                'nombre_subtipo_servicio' => 'ANGIOTOMOGRAFIA',
                'id_tipo_servicio' => $tipo->id_tipo_servicio
            ],
            ['estado_subtipo_servicio' => true]
        );

        // ============================
        // TAC / TEM S/C y C/C
        // ============================

        $tacs = [

            ['TAC ABDOMEN COMPLETO', 550, 700],
            ['TAC ABDOMEN SUPERIOR O INFERIOR', 450, 600],
            ['TAC CEREBRO', 300, 450],
            ['TAC CEREBRO VENTANA OSEA', 350, 450],
            ['TAC MASTOIDES U OIDOS', 350, 450],
            ['TAC ORBITAS', 350, 450],
            ['TAC SENOS PARANASALES', 350, 450],
            ['TAC TORAX', 450, 550],
            ['TEM CODO', 350, 450],
            ['TEM COLUMNA CERVICAL', 350, 450],
            ['TEM COLUMNA DORSAL', 350, 450],
            ['TEM COLUMNA LUMBROSACRA', 350, 450],
            ['TEM RODILLA', 350, 450],
            ['TEM TOBILLO', 350, 450],
            ['TEM MACIZO FACIAL', 350, 450],
            ['TEM PELVIS', 350, 450],
            ['TEM PELVIS OSEA', 350, 450],
            ['TEM CADERA', 350, 450],
            ['TEM SILLA TURCA', 350, 450],
            ['TEM MAXILAR', 350, 450],
            ['TEM ARTICULACION TEMPOROMANDIBULAR', 350, 450],
            ['TAC CUELLO', 350, 450],
            ['TEM PARRILLA COSTAL', 350, 450],
            ['TEM BRAZO', 350, 450],
            ['TEM CLAVICULA', 350, 450],
            ['TEM FEMUR', 350, 450],
            ['TEM HOMBRO', 350, 450],
            ['TEM HOMOPLATO', 350, 450],
            ['TEM MANO', 350, 450],
            ['TEM MUÑECA', 350, 450],
            ['TEM PIE', 350, 450],
            ['TEM TALON', 350, 450],
            ['TEM SACROCOXIGEA', 350, 450],
            ['TEM TIROIDES', 350, 450],
            ['UROTEM', 550, 650],

        ];

        foreach ($tacs as $item) {

            // SIN CONTRASTE
            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => $item[0] . ' S/C',
                    'id_subtipo_servicio' => $subSC->id_subtipo_servicio
                ],
                [
                    'precio_servicio' => $item[1],
                    'estado_servicio' => true,
                    'unidad_sunat' => 'NIU'
                ]
            );

            // CON CONTRASTE
            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => $item[0] . ' C/C',
                    'id_subtipo_servicio' => $subCC->id_subtipo_servicio
                ],
                [
                    'precio_servicio' => $item[2],
                    'estado_servicio' => true,
                    'unidad_sunat' => 'NIU'
                ]
            );
        }

        // ============================
        // TOMOGRAFIA ESPECIAL
        // ============================

        $especiales = [

            ['TEM TRIFASICO SUPERIOR', 850],
            ['UROGRAFIA EXCRETORA', 450],

        ];

        foreach ($especiales as $item) {
            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => $item[0],
                    'id_subtipo_servicio' => $subEspecial->id_subtipo_servicio
                ],
                [
                    'precio_servicio' => $item[1],
                    'estado_servicio' => true,
                    'unidad_sunat' => 'NIU'
                ]
            );
        }

        // ============================
        // ANGIOTOMOGRAFIA
        // ============================

        $angios = [

            ['ANGIOTEM TORACO ABDOMINAL', 1200],
            ['ANGIOTEM EXTREMIDAD INFERIOR', 800],
            ['ANGIOTEM EXTREMIDAD SUPERIOR', 800],
            ['ANGIOTEM CEREBRAL', 800],

        ];

        foreach ($angios as $item) {
            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => $item[0],
                    'id_subtipo_servicio' => $subAngio->id_subtipo_servicio
                ],
                [
                    'precio_servicio' => $item[1],
                    'estado_servicio' => true,
                    'unidad_sunat' => 'NIU'
                ]
            );
        }
    }
}
