<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\Persona;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    
       /**
     * PRUEBA 2: Login exitoso con credenciales correctas
     */
    public function testCredencialesCorrectas()
    {
        $usuario = Persona::create([
            'Nombre' => 'Claudia',
            'Mail' => 'clau@gmail.com',
            'password' => Hash::make('Claudia123'),
            'Estado' => 'Activo',
            'SecretKey' => null,
            'remember_token' => null,
        ]);
        
        //Intentar login
        $response = $this->post('/login', [
            'Mail' => 'claum@gmail.com',
            'password' => 'password123',
        ]);
        
        //Verificar redirección a 2FA
        $response->assertRedirect('/2fa');
        
        //Verificar sesión 2FA pendiente
        $this->assertTrue(session()->has('2fa:pending:id'));
        $this->assertEquals($usuario->IDPersona, session('2fa:pending:id')); // ← IDPersona, no id
    }
        /**
     * PRUEBA 2: Login falla con password incorrecto
     */
    public function testCredencialesErroneas()
    {
        // 1. Crear usuario
        Persona::create([
            'Nombre' => 'Claudiamar',
            'Mail' => 'claudiamar@gmail.com',
            'password' => Hash::make('password123'),
            'Estado' => 'Activo',
            'SecretKey' => null,
        ]);
        
        // 2. Intentar login con password incorrecto
        $response = $this->post('/login', [
            'Mail' => 'claudiamar@gmail.com',
            'password' => 'password123',
        ]);
        
        // 3. Verificar error
        $response->assertSessionHasErrors(['Mail']);
    }
}