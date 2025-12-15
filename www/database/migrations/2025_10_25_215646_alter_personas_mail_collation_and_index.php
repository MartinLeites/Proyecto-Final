<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("
            ALTER TABLE personas
            MODIFY Mail VARCHAR(255)
            CHARACTER SET utf8mb4
            COLLATE utf8mb4_0900_ai_ci
            NOT NULL
        ");


        DB::statement("CREATE UNIQUE INDEX ux_personas_mail ON personas (Mail)");

    }

    public function down(): void
    {
        
        DB::statement("DROP INDEX ux_personas_mail ON personas");

        DB::statement("
            ALTER TABLE personas
            MODIFY Mail VARCHAR(255)
            CHARACTER SET utf8mb4
            COLLATE utf8mb4_unicode_ci
            NOT NULL
        ");
    }
};
