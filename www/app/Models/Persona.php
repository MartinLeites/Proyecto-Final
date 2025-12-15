<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Persona extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'personas';
    protected $primaryKey = 'IDPersona';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Estado',
        'Nombre',
        'Mail',
        'password',
        'SecretKey'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'SecretKey',
    ];

    protected function casts(): array
    {
        return [
            'password'  => 'hashed',
            'SecretKey' => 'encrypted',
        ];
    }

    /*
     |--------------------------------------------------------------------------
     | Relaciones
     |--------------------------------------------------------------------------
     */

    public function admin()
    {
        return $this->hasOne(Administrador::class, 'IDPersona', 'IDPersona');
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'IDPersona', 'IDPersona');
    }

    /*
     |--------------------------------------------------------------------------
     | Scopes los para los Services
     |--------------------------------------------------------------------------
     */

    /**
     * Para buscar los usuarios Usuarios.
     */
    public function scopeConUsuario($query)
    {
        return $query->has('usuario')->with('usuario');
    }

    /**
     * Personas con Estado = Activo
     */
    public function scopeActivos($query)
    {
        return $query->where('Estado', 'Activo');
    }
}
