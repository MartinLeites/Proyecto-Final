<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tiendas', function (Blueprint $table) {
            $table->renameColumn('Nombre', 'NombreTienda');
            $table->renameColumn('API', 'URLTienda');
            $table->renameColumn('Estado', 'EstadoTienda');
        });
    }

    public function down(): void
    {
        Schema::table('tiendas', function (Blueprint $table) {
            // Revertimos los nombres
            $table->renameColumn('NombreTienda', 'Nombre');
            $table->renameColumn('URLTienda', 'API');
            $table->renameColumn('EstadoTienda', 'Estado');
        });
    }
};
