<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //
        User::create([
            "name" => 'Cesar Raul Baca',
            "genero" => 'Masculino',
            "dni" => '73888312',
            "fecha_nacimiento" => '1998-06-02',
            "nombre_cargo" => 'Recepcionista',
            "especialidad_cargo" => 'Sin especialidad',
            "colegiatura_cargo" => 'Sin colegiatura',
            "privilegio_cargo" => 5,
            "direccion" => 'Urb santa rosa Mz 2 Lt 4',
            "foto_url" => 'https://picsum.photos/300/300',
            "telefono" => '902517849',
            "email" => 'antonioxd365@gmail.com',
            "password" => bcrypt('bacaxd365'),
            "estado_user" => true
        ]);
        //

        

    }
}
