<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class Cie10Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/CIE10_MINSA_OFICIAL.csv');

        if (!File::exists($path)) {
            $this->command->error('No se encontró el archivo CSV');
            return;
        }

        $file = fopen($path, 'r');

        $header = fgetcsv($file, 0, ';'); // Cambia a ',' si tu CSV usa coma

        $data = [];
        while (($row = fgetcsv($file, 0, ';')) !== false) {

            $linea = trim($row[0] ?? '');

            // Ignorar líneas vacías
            if (empty($linea)) {
                continue;
            }

            // Buscar patrón: CODIGO - DESCRIPCION
            if (preg_match('/^([A-Z][0-9]{2,4})\s*-\s*(.+)$/', $linea, $matches)) {

                $codigo = $matches[1];
                $descripcion = $matches[2];

                $data[] = [
                    'codigo' => $codigo,
                    'descripcion' => $descripcion,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($data) == 500) {
                    DB::table('cie10')->insert($data);
                    $data = [];
                }
            }
        }

        // Insertar los restantes
        if (!empty($data)) {
            DB::table('cie10')->insert($data);
        }

        fclose($file);

        $this->command->info('CIE10 importado correctamente');
    }
}
