<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            // Renombrar Contraseña a password
            $table->renameColumn('Contraseña', 'password');
            // Agregar remember_token
            $table->string('remember_token', 100)->nullable()->after('SecretKey');
        });
    }

    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            // Revertir los cambios
            $table->renameColumn('password', 'Contraseña');
            $table->dropColumn('remember_token');
        });
    }
};