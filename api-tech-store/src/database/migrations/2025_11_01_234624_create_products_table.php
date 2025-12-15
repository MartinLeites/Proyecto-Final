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
    Schema::create('products', function (Blueprint $table) {
        $table->string('id')->primary();
        $table->string('nombre'); // 
        $table->decimal('precio_actual', 8, 2); 
        $table->decimal('precio_original', 8, 2);
        $table->string('categoria'); //
        $table->string('enlace_producto'); 
        $table->string('imagen_url'); //
        $table->boolean('disponible')->default(true);
        $table->decimal('valoracion', 3, 1)->default(5.0); 
        $table->timestamps(); // 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
