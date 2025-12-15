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
                'imagen_url' => 'http://localhost:8000/images/consoles/ps5.jpg',
                'disponible' => true,
                'valoracion' => 4.5
            ],
            [
                'id' => '889842659274',
                'nombre' => 'XBOX Series X',
                'precio_actual' => 599.99,
                'precio_original' => 699.99,
                'categoria' => 'consoles',
                'enlace_producto' => '/product/2',
                'imagen_url' => 'http://localhost:8000/images/consoles/xboxsx.jpg',
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
                'imagen_url' => 'http://localhost:8000/images/phones/iphone15.jpg',
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
                'imagen_url' => 'http://localhost:8000/images/phones/samsunggalaxy23.jpg',
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
                'imagen_url' => 'http://localhost:8000/images/consoles/zeebo.jpg',
                'disponible' => true,
                'valoracion' => 5.0
            ],
            [
                'id' => '444456789012',
                'nombre' => 'Xbox',
                'precio_actual' => 199.99,
                'precio_original' => 299.99,
                'categoria' => 'consoles',
                'enlace_producto' => '/product/6',
                'imagen_url' => 'http://localhost:8000/images/consoles/xbox.jpg',
                'disponible' => true,
                'valoracion' => 5.0
            ],
            [
                'id' => '555556789012',
                'nombre' => 'Xbox 360',
                'precio_actual' => 299.99,
                'precio_original' => 399.99,
                'categoria' => 'consoles',
                'enlace_producto' => '/product/7',
                'imagen_url' => 'http://localhost:8000/images/consoles/xbox360.jpg',
                'disponible' => true,
                'valoracion' => 4.5
            ],
            [
                'id' => '555506789012',
                'nombre' => 'Game Boy Advance',
                'precio_actual' => 199.99,
                'precio_original' => 399.99,
                'categoria' => 'consoles',
                'enlace_producto' => '/product/8',
                'imagen_url' => 'http://localhost:8000/images/consoles/gba.jpg',
                'disponible' => true,
                'valoracion' => 5.0
            ],
            [
                'id' => '555506789992',
                'nombre' => 'Game Boy Advance SP Gengar',
                'precio_actual' => 799.99,
                'precio_original' => 799.99,
                'categoria' => 'consoles',
                'enlace_producto' => '/product/9',
                'imagen_url' => 'http://localhost:8000/images/consoles/gbaspgengar.jpg',
                'disponible' => true,
                'valoracion' => 3.1
            ],
            // mas productos
        ];

        foreach ($productos as $productoData) {

            Product::create($productoData);

        }
    }
}