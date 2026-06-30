<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CatalogoUpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/CATALOGO_UPS.csv');

        if (!File::exists($path)) {
            $this->command->error('No se encontró el archivo CATALOGO_UPS.csv');
            return;
        }

        $file = fopen($path, 'r');

        // Saltar cabecera
        fgetcsv($file, 0, ';');

        $data = [];

        while (($row = fgetcsv($file, 0, ';')) !== false) {
            $codigo = trim($row[0]);

            if (isset($codigos[$codigo])) {
                continue;
            }

            $codigos[$codigo] = true;
            
            if (count($row) < 5) {
                continue;
            }

            $codigo = trim($row[0]);
            $nombre = trim($row[1]);

            if ($codigo == '' || $nombre == '') {
                continue;
            }

            $data[] = [
                'codigo'      => $codigo,
                'nombre'      => $nombre,
                'tabla_d1'    => (int) $row[2],
                'tabla_g'     => (int) $row[3],
                'tabla_i'     => (int) $row[4],
                'created_at'  => now(),
                'updated_at'  => now(),
            ];

            if (count($data) >= 500) {
                DB::table('catalogo_ups')->insert($data);
                $data = [];
            }
        }

        if (!empty($data)) {
            DB::table('catalogo_ups')->insert($data);
        }

        fclose($file);

        $this->command->info('Catálogo UPS importado correctamente.');
    }
}
