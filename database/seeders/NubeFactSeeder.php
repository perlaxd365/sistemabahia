<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NubeFactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('nubefact_config')->insert([
            'ruta' => 'https://api.nubefact.com/api/v1/efb54a21-b352-4e92-b7f4-66150220ebe5',
            'token' => '50a5915c508346c19b03ebe224bee2ffa8079d98cb3f49f69f8e6ee686c75d7b',
            'produccion' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
