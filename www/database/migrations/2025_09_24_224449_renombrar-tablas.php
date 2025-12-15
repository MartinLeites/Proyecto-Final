<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('administrador', 'administradors');
        Schema::rename('usuario', 'usuarios');
        Schema::rename('tienda', 'tiendas');
        Schema::rename('credencialesTienda', 'credencialesTiendas');
        Schema::rename('productoInterno', 'productoInternos');
        Schema::rename('productoTienda', 'productoTiendas');
        Schema::rename('categoria', 'categorias');
        Schema::rename('wishlist', 'wishlists');
        Schema::rename('pertenece', 'perteneces');
        Schema::rename('tiene', 'tienes');
    }

    public function down(): void
    {
        Schema::rename('administradors', 'administrador');
        Schema::rename('usuarios', 'usuario');
        Schema::rename('tiendas', 'tienda');
        Schema::rename('credencialesTiendas', 'credencialesTienda');
        Schema::rename('productoInternos', 'productoInterno');
        Schema::rename('productoTiendas', 'productoTienda');
        Schema::rename('categorias', 'categoria');
        Schema::rename('wishlists', 'wishlist');
        Schema::rename('perteneces', 'pertenece');
        Schema::rename('tienes', 'tiene');
    }
};
