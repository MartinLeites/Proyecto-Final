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
        Schema::create('tiene', function (Blueprint $table) {
            $table->unsignedBigInteger('IDTienda');
            $table->unsignedBigInteger('IDProductoT');
            $table->foreign('IDTienda')->references('IDTienda')->on('tienda')->onDelete('cascade');
            $table->foreign('IDProductoT')->references('IDProductoT')->on('productoTienda')->onDelete('cascade');
            $table->primary('IDTienda', 'IDProductoT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiene');
    }
};
