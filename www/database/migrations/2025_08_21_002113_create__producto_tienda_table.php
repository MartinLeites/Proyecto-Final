<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productoTienda', function (Blueprint $table) {
            $table->id('IDProductoT');
            $table->unsignedBigInteger('IDProductoI');
            $table->foreign('IDProductoI')->references('IDProductoI')->on('productoInterno')->onDelete('cascade');;
            $table->string('Nombre');
            $table->decimal('Precio', 10, 2);
            $table->string('URL');
            $table->string('Afiliado');
            $table->dateTime('FechaActualizacion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productoTienda');
    }
};
