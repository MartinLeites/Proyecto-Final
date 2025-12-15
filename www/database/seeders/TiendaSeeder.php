<?php

namespace Database\Seeders;

use App\Models\Tienda;
use App\Models\CredencialTienda;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiendaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('credencialesTiendas')->delete();
        DB::table('tiendas')->delete();

        // Seeder de tiendas, pensadas para futuras integraciones reales
        // 1. Insertar Toys Store
        $toysId = DB::table('tiendas')->insertGetId([
            'NombreTienda' => 'Toys Store',
            'URLTienda' => 'http://api-web-toy/api',
            'EstadoTienda' => 1,
        ]);

        // Insertar credenciales para Toys Store
        DB::table('credencialesTiendas')->insert([
            'IDTienda' => $toysId,
            'Tipo' => 'api_token',
            'Valor' => 'no_requerido',
        ]);

        // 2. Insertar Tech Store
        $techId = DB::table('tiendas')->insertGetId([
            'NombreTienda' => 'Tech Store',
            'URLTienda' => 'http://api-web-tech/api',
            'EstadoTienda' => 1,
        ]);

        // Insertar credenciales para Tech Store
        DB::table('credencialesTiendas')->insert([
            'IDTienda' => $techId,
            'Tipo' => 'api_token',
            'Valor' => 'no_requerido',
        ]);
        
    }
}
