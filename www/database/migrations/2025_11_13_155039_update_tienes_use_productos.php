<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('tienes');

        Schema::create('tienes', function (Blueprint $table) {
            $table->unsignedBigInteger('IDTienda');
            $table->string('IDProducto', 150);
            $table->timestamps();

            $table->primary(['IDTienda', 'IDProducto']);

            $table->foreign('IDTienda', 'tienes_idtienda_foreign')
                  ->references('IDTienda')
                  ->on('tiendas')
                  ->onDelete('cascade');

            $table->foreign('IDProducto', 'tienes_idproducto_foreign')
                  ->references('IDProducto')
                  ->on('productos')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tienes');
    }
};
