<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CatalogoProcedimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/CATALOGO_PROCEDIMIENTOS.csv');

        if (!File::exists($path)) {
            $this->command->error('No se encontró el archivo CATALOGO_PROCEDIMIENTOS.csv');
            return;
        }

        $file = fopen($path, 'r');

        // Saltar cabecera
        fgetcsv($file, 0, ';');

        $data = [];

        while (($row = fgetcsv($file, 0, ';')) !== false) {

            if (count($row) < 9) {
                continue;
            }

            $codigo = trim($row[0]);

            if ($codigo == '') {
                continue;
            }

            $data[] = [
                'codigo'               => $codigo,
                'denominacion'         => trim($row[1]),
                'grupo_codigo'         => trim($row[2]),
                'grupo_nombre'         => trim($row[3]),
                'seccion_codigo'       => trim($row[4]),
                'seccion_nombre'       => trim($row[5]),
                'subseccion_codigo'    => trim($row[6]),
                'subseccion_nombre'    => trim($row[7]),
                'situacion'            => strtoupper(trim($row[8])),
                'created_at'           => now(),
                'updated_at'           => now(),
            ];

            if (count($data) >= 500) {

                DB::table('catalogo_procedimientos')->upsert(
                    $data,
                    ['codigo'],
                    [
                        'denominacion',
                        'grupo_codigo',
                        'grupo_nombre',
                        'seccion_codigo',
                        'seccion_nombre',
                        'subseccion_codigo',
                        'subseccion_nombre',
                        'situacion',
                        'updated_at'
                    ]
                );

                $data = [];
            }
        }

        if (!empty($data)) {

            DB::table('catalogo_procedimientos')->upsert(
                $data,
                ['codigo'],
                [
                    'denominacion',
                    'grupo_codigo',
                    'grupo_nombre',
                    'seccion_codigo',
                    'seccion_nombre',
                    'subseccion_codigo',
                    'subseccion_nombre',
                    'situacion',
                    'updated_at'
                ]
            );
        }

        fclose($file);

        $this->command->info('Catálogo de procedimientos importado correctamente.');
    }
}
