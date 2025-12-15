<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Persona;
use App\Models\Administrador;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            ['nombre' => 'Admin 1', 'mail' => 'admin1@gmail.com'],
            ['nombre' => 'Admin 2', 'mail' => 'admin2@gmail.com'],
            ['nombre' => 'Admin 3', 'mail' => 'admin3@gmail.com'],
            ['nombre' => 'Admin 4', 'mail' => 'admin4@gmail.com'],
            ['nombre' => 'Admin 5', 'mail' => 'admin5@gmail.com'],
        ];

        foreach ($admins as $a) {
            $persona = Persona::updateOrCreate(
                ['Mail' => $a['mail']],
                [
                    'Nombre'   => $a['nombre'],
                    'Estado'   => 'Activo',
                    'password' => Hash::make('secret123'),
                ]
            );

            Administrador::firstOrCreate(['IDPersona' => $persona->IDPersona]);
        }
    }
}
