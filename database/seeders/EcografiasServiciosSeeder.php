<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\SubTipoServicio;
use App\Models\TipoServicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EcografiasServiciosSeeder extends Seeder
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
            ['nombre_tipo_servicio' => 'ECOGRAFIAS'],
            ['estado_tipo_servicio' => true]
        );

        // ============================
        // SUBTIPOS
        // ============================

        $subAlquiler = SubTipoServicio::firstOrCreate(
            ['nombre_subtipo_servicio' => 'ALQUILER EQUIPO ECOGRAFIA', 'id_tipo_servicio' => $tipo->id_tipo_servicio],
            ['estado_subtipo_servicio' => true]
        );

        $subGine = SubTipoServicio::firstOrCreate(
            ['nombre_subtipo_servicio' => 'ECOGRAFIAS GINECOLOGICAS', 'id_tipo_servicio' => $tipo->id_tipo_servicio],
            ['estado_subtipo_servicio' => true]
        );

        $subEspecial = SubTipoServicio::firstOrCreate(
            ['nombre_subtipo_servicio' => 'ECOGRAFIAS ESPECIALES', 'id_tipo_servicio' => $tipo->id_tipo_servicio],
            ['estado_subtipo_servicio' => true]
        );

        // ============================
        // ALQUILER ECOGRAFO
        // ============================

        $alquiler = [
            ['ALQUILER DE ECOGRAFO DOMINGO Y FERIADOS', 30],
            ['ALQUILER POR ECO DOPPLER GESTACIONAL GENETICA 3D 4D 5D', 50],
            ['ALQUILER POR ECO DOPPLER ARTERIAL O VENOSO', 60],
        ];

        foreach ($alquiler as $item) {
            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => $item[0],
                    'id_subtipo_servicio' => $subAlquiler->id_subtipo_servicio
                ],
                [
                    'precio_servicio' => $item[1],
                    'estado_servicio' => true,
                    'unidad_sunat' => 'NIU'
                ]
            );
        }

        // ============================
        // ECOGRAFIAS GINECOLOGICAS
        // ============================

        $gine = [

            ['ECOGRAFIA 3D 4D 5D 24-28 SEM', 190],
            ['ECOGRAFIA DOPPLER GESTACIONAL DESDE 18 SEM', 180],
            ['ECOGRAFIA GENETICA 12-14 SEM', 150],
            ['ECOGRAFIA MORFOLOGICA 20-24 SEM', 150],
            ['ECOGRAFIA OBSTETRICA 2D', 100],
            ['ECOGRAFIA TRANSVAGINAL', 100],
            ['ECOGRAFIA PELVICA', 100],
            ['ECOGRAFIA MAMARIA BILATERAL', 180],

        ];

        foreach ($gine as $item) {
            Servicio::firstOrCreate(
                [
                    'nombre_servicio' => $item[0],
                    'id_subtipo_servicio' => $subGine->id_subtipo_servicio
                ],
                [
                    'precio_servicio' => $item[1],
                    'estado_servicio' => true,
                    'unidad_sunat' => 'NIU'
                ]
            );
        }

        // ============================
        // ECOGRAFIAS ESPECIALES
        // ============================

        $especiales = [

            ['ECOGRAFIA DE TIROIDES', 180],
            ['ECOGRAFIA PARTES BLANDAS NO ARTICULARES', 120],
            ['ECOGRAFIA ARTICULAR HOMBRO RODILLA CODO TOBILLO MUÑECA', 180],
            ['ECOGRAFIA ABDOMINAL ALTA O BAJA', 100],
            ['ECOGRAFIA ABDOMINAL COMPLETA', 150],
            ['ECOGRAFIA ABDOMINO PELVICA', 200],
            ['ECOGRAFIA TESTICULAR BILATERAL', 130],
            ['ECOGRAFIA RENAL BILATERAL', 100],
            ['ECOGRAFIA RENOVESICAL', 120],
            ['ECOGRAFIA RENOVESICAL PEDIATRICA', 180],
            ['ECOGRAFIA RENOVESICO PROSTATICA', 140],
            ['ECOGRAFIA DOPPLER TESTICULAR', 180],
            ['ECOGRAFIA DOPPLER VENOSO POR MIEMBRO', 250],
            ['ECOGRAFIA DOPPLER ARTERIAL POR MIEMBRO', 250],

            ['ECOGRAFIA CADERAS BILATERAL BEBES', 350],
            ['ECOGRAFIA TRANSFONTANELAR', 300],
            ['ECOGRAFIA ECOFAST', 100],
            ['ECOGRAFIA TUMORACION MAXILAR', 120],
            ['ECOGRAFIA COLUMNA LUMBAR', 180],
            ['ECOGRAFIA CERVICAL BOCIO', 150],
            ['ECOGRAFIA CADERAS BILATERALES', 180],
            ['ECOGRAFIA TORAX CON MARCAJE', 200],
            ['ECOGRAFIA PROSTATA', 100],
            ['ECOGRAFIA TABIQUE NASAL PARTES BLANDAS', 150],
            ['ECOGRAFIA ANTEBRAZO EPICONDILITIS', 120],
            ['ECOGRAFIA PARTES BLANDAS CABEZA Y CUELLO', 140],
            ['ECOGRAFIA DOPPLER CAROTIDAS', 200],
            // ============================
            // NUEVAS ECOGRAFIAS AGREGADAS
            // ============================

            ['ECODOPPLER ARTERIAL MMII', 250],
            ['ECODOPPLER ARTERIAL MMII BILATERAL', 300],
            ['ECODOPPLER VENOSO MMII', 250],
            ['ECODOPPLER VENOSO MMII BILATERAL', 300],
            ['ECODOPPLER ARTERIAL Y VENOSO MMII BILATERAL', 400],
            ['ECO RENAL', 120],
            ['ECO RENOVESICAL', 140],
            ['ECO RENOVESICOPROSTATICO', 160],
            ['ECO PARTES BLANDAS', 120],

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
    }
}
