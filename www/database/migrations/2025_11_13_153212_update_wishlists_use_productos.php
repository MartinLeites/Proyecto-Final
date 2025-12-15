<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('wishlists');

        Schema::create('wishlists', function (Blueprint $table) {
            $table->unsignedBigInteger('IDPersona');
            $table->string('IDProducto', 150);
            $table->timestamps();

            $table->primary(['IDPersona', 'IDProducto']);

            $table->foreign('IDPersona', 'wishlist_idpersona_foreign')
                  ->references('IDPersona')
                  ->on('usuarios')
                  ->onDelete('cascade');

            $table->foreign('IDProducto', 'wishlists_idproducto_foreign')
                  ->references('IDProducto')
                  ->on('productos')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
