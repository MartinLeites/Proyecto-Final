<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {

        $productos = [
            [
                'id' => '711719951467',
                'nombre' => 'Playstation 5',
                'precio_actual' => 699.99,
                'precio_original' => 699.99,
                'categoria' => 'consoles',
                'enlace_producto' => '/product/1',
                'imagen_url' => 'http://localhost:6677/images/consoles/ps5.jpg',
                'disponible' => true,
                'valoracion' => 4.5
            ],
            [
                'id' => '889842659274',
                'nombre' => 'XBOX Series X',
                'precio_actual' => 499.99,
                'precio_original' => 699.99,
                'categoria' => 'consoles',
                'enlace_producto' => '/product/2',
                'imagen_url' => 'http://localhost:6677/images/consoles/xboxsx.jpg',
                'disponible' => true,
                'valoracion' => 4.1
            ],
            [
                'id' => '194253431323',
                'nombre' => 'Iphone 15',
                'precio_actual' => 1599.99,
                'precio_original' => 1599.99,
                'categoria' => 'phones',
                'enlace_producto' => '/product/3',
                'imagen_url' => 'http://localhost:6677/images/phones/iphone15.jpg',
                'disponible' => true,
                'valoracion' => 4.9
            ],
            [
                'id' => '880609866049',
                'nombre' => 'Samsung Galaxy S23',
                'precio_actual' => 1099.99,
                'precio_original' => 1199.99,
                'categoria' => 'phones',
                'enlace_producto' => '/product/4',
                'imagen_url' => 'http://localhost:6677/images/phones/samsunggalaxy23.jpg',
                'disponible' => true,
                'valoracion' => 4.9
            ],
            [
                'id' => '123456789012',
                'nombre' => 'Zeebo',
                'precio_actual' => 99.99,
                'precio_original' => 199.99,
                'categoria' => 'consoles',
                'enlace_producto' => '/product/5',
                'imagen_url' => 'http://localhost:6677/images/consoles/zeebo.jpg',
                'disponible' => true,
                'valoracion' => 5.0
            ],
            [
                'id' => '194253431698',
                'nombre' => 'Playstation 1',
                'precio_actual' => 99.99,
                'precio_original' => 99.99,
                'categoria' => 'consoles',
                'enlace_producto' => '/product/6',
                'imagen_url' => 'http://localhost:6677/images/consoles/ps1.jpg',
                'disponible' => true,
                'valoracion' => 4.1
            ],
            [
                'id' => '333253431698',
                'nombre' => 'Playstation 2',
                'precio_actual' => 159.99,
                'precio_original' => 199.99,
                'categoria' => 'consoles',
                'enlace_producto' => '/product/7',
                'imagen_url' => 'http://localhost:6677/images/consoles/ps2.jpg',
                'disponible' => true,
                'valoracion' => 5.0
            ],
            [
                'id' => '555506789992',
                'nombre' => 'Game Boy Advance SP Gengar',
                'precio_actual' => 399.99,
                'precio_original' => 499.99,
                'categoria' => 'consoles',
                'enlace_producto' => '/product/8',
                'imagen_url' => 'http://localhost:6677/images/consoles/gbaspgengar.jpg',
                'disponible' => true,
                'valoracion' => 5.0
            ],
            [
                'id' => '115506789992',
                'nombre' => 'Game Boy Advance SP Rayquaza',
                'precio_actual' => 399.99,
                'precio_original' => 499.99,
                'categoria' => 'consoles',
                'enlace_producto' => '/product/9',
                'imagen_url' => 'http://localhost:6677/images/consoles/gbasprayquaza.jpg',
                'disponible' => true,
                'valoracion' => 5.0
            ],
            // mas productos
        ];

        foreach ($productos as $productoData) {
            Product::create($productoData);
            
        }
    }
}