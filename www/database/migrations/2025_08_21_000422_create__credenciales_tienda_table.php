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
        Schema::create('credencialesTienda', function (Blueprint $table) {
            $table->id('IDCredencial');
            $table->unsignedBigInteger('IDTienda');
            $table->foreign('IDTienda')->references('IDTienda')->on('tienda')->onDelete('cascade');;
            $table->string('Tipo');
            $table->string('Valor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credencialesTienda');
    }
};
