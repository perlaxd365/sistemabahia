<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1️⃣ Crear Tipo
        $idTipo = DB::table('tipo_servicios')->insertGetId([
            'nombre_tipo_servicio' => 'PROCEDIMIENTOS',
            'estado_tipo_servicio' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2️⃣ Crear Subtipo
        $idSubtipo = DB::table('sub_tipo_servicios')->insertGetId([
            'id_tipo_servicio' => $idTipo,
            'nombre_subtipo_servicio' => 'ENFERMERÍA',
            'estado_subtipo_servicio' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3️⃣ Crear Servicio
        DB::table('servicios')->insert([
            'id_subtipo_servicio' => $idSubtipo,
            'nombre_servicio'     => 'ENDOVENOSO',
            'precio_servicio'     => 15.00,
            'estado_servicio'     => true,
            'codigo_sunat'        => null,
            'unidad_sunat'        => 'NIU',
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
    }
}
