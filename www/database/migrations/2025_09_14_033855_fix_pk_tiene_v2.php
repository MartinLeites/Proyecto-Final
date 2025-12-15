<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hacemos el cambio de forma idempotente (chequeando estado real en information_schema)
        // para que no falle si ya corriste parte de esto antes.

        // 0) Detectar estado actual
        $hasPkComposite = (bool) DB::selectOne("
            SELECT 1
            FROM information_schema.table_constraints
            WHERE table_schema = DATABASE()
              AND table_name = 'tiene'
              AND constraint_type = 'PRIMARY KEY'
              AND EXISTS (
                    SELECT 1
                    FROM information_schema.key_column_usage k
                    WHERE k.table_schema = DATABASE()
                      AND k.table_name = 'tiene'
                      AND k.constraint_name = 'PRIMARY'
                    GROUP BY k.constraint_name
                    HAVING GROUP_CONCAT(k.column_name ORDER BY k.ordinal_position) = 'IDTienda,IDProductoT'
              )
        ");

        // 1) Crear índices temporales si faltan (para no romper FKs al cambiar la PK)
        //    - La FK sobre IDProductoT necesita SIEMPRE un índice que empiece por IDProductoT.
        //    - Vamos a conservar ese índice (lo renombraremos).
        $hasIdxTiendaTmp = (bool) DB::selectOne("
            SELECT 1 FROM information_schema.statistics
            WHERE table_schema = DATABASE() AND table_name = 'tiene' AND index_name = 'idx_tiene_idtienda_tmp'
        ");

        $hasIdxProdTmp = (bool) DB::selectOne("
            SELECT 1 FROM information_schema.statistics
            WHERE table_schema = DATABASE() AND table_name = 'tiene' AND index_name = 'idx_tiene_idproductot_tmp'
        ");

        if (!$hasIdxTiendaTmp) {
            Schema::table('tiene', function (Blueprint $table) {
                $table->index('IDTienda', 'idx_tiene_idtienda_tmp');
            });
        }

        if (!$hasIdxProdTmp) {
            Schema::table('tiene', function (Blueprint $table) {
                $table->index('IDProductoT', 'idx_tiene_idproductot_tmp');
            });
        }

        // 2) Cambiar la PK a compuesta si aún no lo está
        if (!$hasPkComposite) {
            DB::statement('ALTER TABLE `tiene` DROP PRIMARY KEY');
            DB::statement('ALTER TABLE `tiene` ADD PRIMARY KEY (`IDTienda`, `IDProductoT`)');
        }

        // 3) Dejar el índice en IDProductoT (necesario para la FK) y borrar SÓLO el de IDTienda
        //    Si tu MySQL soporta RENAME INDEX (8.0+), renombramos el temporal a definitivo.
        $hasIdxProdDef = (bool) DB::selectOne("
            SELECT 1 FROM information_schema.statistics
            WHERE table_schema = DATABASE() AND table_name = 'tiene' AND index_name = 'idx_tiene_idproductot'
        ");

        if (!$hasIdxProdDef) {
            // Renombrar si es posible; si no, lo dejamos con el nombre temporal y no pasa nada.
            try {
                DB::statement('ALTER TABLE `tiene` RENAME INDEX `idx_tiene_idproductot_tmp` TO `idx_tiene_idproductot`');
                $hasIdxProdTmp = false; // ya no existe con el nombre tmp
            } catch (\Throwable $e) {
                // Si tu versión no soporta RENAME INDEX, ignoramos: el nombre "tmp" puede quedar.
            }
        } else {
            // Ya existe con nombre definitivo; si el tmp también existe, lo borramos
            // (pero sólo si NO lo está usando una FK; normalmente MySQL no deja).
            try {
                DB::statement('ALTER TABLE `tiene` DROP INDEX `idx_tiene_idproductot_tmp`');
                $hasIdxProdTmp = false;
            } catch (\Throwable $e) {
                // Si falla, lo dejamos: no afecta.
            }
        }

        // Borrar el índice temporal de IDTienda (ya lo cubre la PK compuesta)
        try {
            DB::statement('ALTER TABLE `tiene` DROP INDEX `idx_tiene_idtienda_tmp`');
        } catch (\Throwable $e) {
            // Si no existe o no se puede, no pasa nada
        }
    }

    public function down(): void
    {
        // Volver a una PK de una sola columna (IDTienda).
        // OJO: mantenemos el índice sobre IDProductoT porque una FK lo puede necesitar.

        // Si la PK es compuesta, la cambiamos
        $hasPkComposite = (bool) DB::selectOne("
            SELECT 1
            FROM information_schema.table_constraints
            WHERE table_schema = DATABASE()
              AND table_name = 'tiene'
              AND constraint_type = 'PRIMARY KEY'
              AND EXISTS (
                    SELECT 1
                    FROM information_schema.key_column_usage k
                    WHERE k.table_schema = DATABASE()
                      AND k.table_name = 'tiene'
                      AND k.constraint_name = 'PRIMARY'
                    GROUP BY k.constraint_name
                    HAVING GROUP_CONCAT(k.column_name ORDER BY k.ordinal_position) = 'IDTienda,IDProductoT'
              )
        ");

        if ($hasPkComposite) {
            // Índice temporal para no romper FKs mientras cambiamos
            $hasIdxTiendaTmp2 = (bool) DB::selectOne("
                SELECT 1 FROM information_schema.statistics
                WHERE table_schema = DATABASE() AND table_name = 'tiene' AND index_name = 'idx_tiene_idtienda_tmp2'
            ");
            if (!$hasIdxTiendaTmp2) {
                Schema::table('tiene', function (Blueprint $table) {
                    $table->index('IDTienda', 'idx_tiene_idtienda_tmp2');
                });
            }

            DB::statement('ALTER TABLE `tiene` DROP PRIMARY KEY');
            DB::statement('ALTER TABLE `tiene` ADD PRIMARY KEY (`IDTienda`)');

            // Podemos intentar limpiar el índice temporal
            try {
                DB::statement('ALTER TABLE `tiene` DROP INDEX `idx_tiene_idtienda_tmp2`');
            } catch (\Throwable $e) {}
        }

        // NO tocamos el índice sobre IDProductoT, porque una FK puede requerirlo.
    }
};
