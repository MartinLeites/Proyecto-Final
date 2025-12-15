<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{ 

    public function up(): void
    {
         
        Schema::dropIfExists('perteneces');        
        Schema::dropIfExists('productoTiendas');   

        Schema::dropIfExists('productoInternos');
        Schema::dropIfExists('categorias');
    }


    public function down(): void
    {
         Schema::create('categorias', function (Blueprint $table) {
            $table->bigIncrements('IDCategoria');
            $table->string('Nombre', 255);
            $table->timestamps();
        });

         Schema::create('productoInternos', function (Blueprint $table) {
            $table->bigIncrements('IDProductoI');
            $table->string('Nombre', 255);
            $table->string('Marca', 255);
            $table->string('Modelo', 255);
            $table->timestamps();
        });

         Schema::create('productoTiendas', function (Blueprint $table) {
            $table->bigIncrements('IDProductoT');
            $table->unsignedBigInteger('IDProductoI');
            $table->string('Nombre', 255);
            $table->decimal('Precio', 10, 2);
            $table->string('URL', 255);
            $table->string('Afiliado', 255);
            $table->dateTime('FechaActualizacion');
            $table->timestamps();

             $table->foreign('IDProductoI', 'productotienda_idproductoi_foreign')
                  ->references('IDProductoI')
                  ->on('productoInternos')
                  ->onDelete('cascade');
        });


        Schema::create('perteneces', function (Blueprint $table) {
            $table->unsignedBigInteger('IDProductoI');
            $table->unsignedBigInteger('IDCategoria');
            $table->timestamps();

            $table->primary('IDProductoI'); 

            $table->foreign('IDProductoI', 'pertenece_idproductoi_foreign')
                  ->references('IDProductoI')
                  ->on('productoInternos')
                  ->onDelete('cascade');

            $table->foreign('IDCategoria', 'pertenece_idcategoria_foreign')
                  ->references('IDCategoria')
                  ->on('categorias')
                  ->onDelete('cascade');
        });
    }
};
