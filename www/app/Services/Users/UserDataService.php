<?php

namespace App\Services\Users;

use App\Models\Persona;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class UserDataService
{
    private function callSp(string $sql, array $params = []): array
    {
        $pdo  = DB::connection()->getPdo();

        $orig = $pdo->getAttribute(\PDO::ATTR_EMULATE_PREPARES) ?? null;
        $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

        if (defined('PDO::MYSQL_ATTR_USE_BUFFERED_QUERY')) {
            @$pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);

        while ($stmt->nextRowset()) {
        }

        $stmt->closeCursor();

        if ($orig !== null) {
            $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, $orig);
        }

        return $rows;
    }

    
    //Función para registrar un Usuario
  public function registrarUsuario(string $nombre, string $mail, string $passwordPlano): void
    {
        $hash = Hash::make($passwordPlano);

        $this->callSp('CALL esc_altaUsuario(?, ?, ?)', [$nombre, $mail, $hash]);
    }

    // ==== Registrar admin ====
    public function registrarAdmin(string $nombre, string $mail, string $passwordPlano): void
    {
        $hash = Hash::make($passwordPlano);

        $this->callSp('CALL esc_altaAdmin(?, ?, ?)', [$nombre, $mail, $hash]);
    }




    /**
     * Activa un usuario y devuelve el mensaje del SP.
     */
    public function activarUsuario(string $mail): string
    {
        $rows = $this->callSp('CALL esc_activarUsuario(?)', [$mail]);

    if (!empty($rows) && isset($rows[0]->message)) {
        return $rows[0]->message;
    }

    return 'Usuario activado';
    }

    /**
     * Desactiva un usuario y devuelve el mensaje del SP.
     */
   public function desactivarUsuario(string $mail): string
{
    $rows = $this->callSp('CALL esc_desactivarUsuario(?)', [$mail]);

    if (!empty($rows) && isset($rows[0]->message)) {
        return $rows[0]->message;
    }

    return 'Usuario desactivado';
}


    /*
     |--------------------------------------------------------------------------
     | Operaciones Eloquent sobre Persona 
     |--------------------------------------------------------------------------
     */

    /**
     * Listado paginado de personas que tienen usuario asociado.
     */
    public function listarUsuarios(int $perPage = 5): LengthAwarePaginator
    {
        return Persona::conUsuario()
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Eliminar una persona.
     *
     * Podés pasar el modelo ya resuelto por route-model binding.
     */
    public function eliminarPersona(Persona $persona): void
    {
        $persona->delete();
    }
}
