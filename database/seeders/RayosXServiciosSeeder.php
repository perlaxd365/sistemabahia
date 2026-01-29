<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\SubTipoServicio;
use App\Models\TipoServicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class RayosXServiciosSeeder extends Seeder
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
            ['nombre_tipo_servicio' => 'RAYOS X'],
            ['estado_tipo_servicio' => true]
        );

        // ============================
        // SUBTIPOS
        // ============================

        $subCabeza = SubTipoServicio::firstOrCreate(
            ['nombre_subtipo_servicio' => 'RAYOS X CABEZA', 'id_tipo_servicio' => $tipo->id_tipo_servicio],
            ['estado_subtipo_servicio' => true]
        );

        $subSup = SubTipoServicio::firstOrCreate(
            ['nombre_subtipo_servicio' => 'RAYOS X EXTREMIDADES SUPERIORES', 'id_tipo_servicio' => $tipo->id_tipo_servicio],
            ['estado_subtipo_servicio' => true]
        );

        $subInf = SubTipoServicio::firstOrCreate(
            ['nombre_subtipo_servicio' => 'RAYOS X EXTREMIDADES INFERIORES', 'id_tipo_servicio' => $tipo->id_tipo_servicio],
            ['estado_subtipo_servicio' => true]
        );

        $subTronco = SubTipoServicio::firstOrCreate(
            ['nombre_subtipo_servicio' => 'RAYOS X TRONCO', 'id_tipo_servicio' => $tipo->id_tipo_servicio],
            ['estado_subtipo_servicio' => true]
        );

        $subOtros = SubTipoServicio::firstOrCreate(
            ['nombre_subtipo_servicio' => 'RAYOS X CON CONTRASTE Y ESPECIALES', 'id_tipo_servicio' => $tipo->id_tipo_servicio],
            ['estado_subtipo_servicio' => true]
        );

        // ============================
        // CABEZA
        // ============================

        $cabeza = [
            ['RX CRANEO FRENTE Y LATERAL', 150],
            ['RX CAVUM NIÑOS', 150],
            ['RX HUESOS PROPIOS NARIZ', 210],
            ['RX MAXILAR INFERIOR OBLICUAS', 150],
            ['RX MAXILAR INFERIOR FRENTE POSTERIOR', 150],
            ['RX SENOS PARANASALES', 210],
        ];

        foreach ($cabeza as $item) {
            Servicio::firstOrCreate(
                ['nombre_servicio' => $item[0], 'id_subtipo_servicio' => $subCabeza->id_subtipo_servicio],
                ['precio_servicio' => $item[1], 'estado_servicio' => true, 'unidad_sunat' => 'NIU']
            );
        }

        // ============================
        // EXTREMIDADES SUPERIORES
        // ============================

        $sup = [
            ['RX ANTEBRAZO', 150],
            ['RX BRAZO', 150],
            ['RX CLAVICULA', 150],
            ['RX CODO', 150],
            ['RX HOMBRO', 150],
            ['RX HUMERO', 150],
            ['RX MANO', 150],
            ['RX MUÑECA', 150],
            ['RX MANO EDAD OSEA', 100],
        ];

        foreach ($sup as $item) {
            Servicio::firstOrCreate(
                ['nombre_servicio' => $item[0], 'id_subtipo_servicio' => $subSup->id_subtipo_servicio],
                ['precio_servicio' => $item[1], 'estado_servicio' => true, 'unidad_sunat' => 'NIU']
            );
        }

        // ============================
        // EXTREMIDADES INFERIORES
        // ============================

        $inf = [
            ['RX CALCANEO', 150],
            ['RX FEMUR', 150],
            ['RX PIE', 150],
            ['RX PIERNA', 150],
            ['RX RODILLA COMPARATIVA', 210],
            ['RX RODILLA', 150],
            ['RX TOBILLO', 150],
            ['RX TALON', 150],
            ['RX PIES CON PESO', 500],
            ['RX PIE APOYO PEDIATRICO', 300],
        ];

        foreach ($inf as $item) {
            Servicio::firstOrCreate(
                ['nombre_servicio' => $item[0], 'id_subtipo_servicio' => $subInf->id_subtipo_servicio],
                ['precio_servicio' => $item[1], 'estado_servicio' => true, 'unidad_sunat' => 'NIU']
            );
        }

        // ============================
        // TRONCO
        // ============================

        $tronco = [
            ['RX ABDOMEN', 150],
            ['RX COLUMNA CERVICAL', 160],
            ['RX COLUMNA LUMBAR', 160],
            ['RX PARRILLA COSTAL', 150],
            ['RX PELVIS', 100],
            ['RX SACRO COXIS', 150],
            ['RX TORAX', 150],
            ['RX TORAX PA', 70],
            ['RX TORAX DOMICILIO', 350],
            ['RX ABDOMEN SIMPLE', 120],
            ['RX ABDOMEN DECUBITO', 250],
            ['RX CADERA NIÑOS', 200],
            ['RX CADERA', 150],
            ['RX CADERAS COMPARATIVAS', 210],
        ];

        foreach ($tronco as $item) {
            Servicio::firstOrCreate(
                ['nombre_servicio' => $item[0], 'id_subtipo_servicio' => $subTronco->id_subtipo_servicio],
                ['precio_servicio' => $item[1], 'estado_servicio' => true, 'unidad_sunat' => 'NIU']
            );
        }

        // ============================
        // CONTRASTE Y ESPECIALES
        // ============================

        $otros = [
            ['RX COLON CONTRASTE', 400],
            ['RX UROGRAFIA EXCRETORA', 300],
            ['COLOSTOGRAMA', 300],
            ['ARTERIOGRAFIA', 1300],
            ['HISTEROSALPINGOGRAFIA', 500],
            ['FISTOLOGRAFIA', 350],
            ['RX ESOFAGO BARITADO', 500],
            ['ANGIOGRAFIA PIE', 1000],
        ];

        foreach ($otros as $item) {
            Servicio::firstOrCreate(
                ['nombre_servicio' => $item[0], 'id_subtipo_servicio' => $subOtros->id_subtipo_servicio],
                ['precio_servicio' => $item[1], 'estado_servicio' => true, 'unidad_sunat' => 'NIU']
            );
        }
    }
}
