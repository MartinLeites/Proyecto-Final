<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\Persona;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController
{

    //Funcion para la vista del formulario login
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    // Funcion que maneja el login básico y prepara la sesión para el 2FA
    public function login(Request $request)
    {
        //Validación de entrada 
        $request->validate([
            'Mail'     => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        //Busca usuario por email que recibe del formulario
        $persona = Persona::where('Mail', $request->Mail)->first();

        //Valida las credenciales con Auth::validate, comprueba email y password sin iniciar sesión
        if (
            !$persona ||
            !Auth::validate(['Mail' => $request->Mail, 'password' => $request->password])
        ) {
            throw ValidationException::withMessages([
                'Mail' => 'El correo no la contraseña no son correctos.',
            ]);
        }


        //Verificar si el estado de la cuenta es "Activo" para seguir, si no está activa da un mensaje
        if (strtolower(trim((string) $persona->Estado)) !== 'activo') {
            return redirect()
                ->route('login')
                ->withErrors(['Mail' => 'Tu cuenta está desactivada. Contacta al administrador.'])
                ->withInput($request->only('Mail', 'remember'));
        }

        //Y prepara los  datos de sesión para 2FA, con la ID de persona y la preferencia "remember" para el siguiente paso del flujo 2FA
        $request->session()->put('2fa:pending:id', $persona->IDPersona);
        $request->session()->put('2fa:remember', (bool) $request->boolean('remember'));

        // se redirige al paso de 2FA 
        return redirect()->route('2fa.show');
    }


    // Funcion para cerrar la sesión del usuario y limpia datos relacionados con 2FA.

    public function logout(Request $request)
    {
        // Si el usuario está autenticado como admin y cierra la esa sesión.
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        // Cierra con el logout del guard por defecto que es la sesión principal 
        Auth::logout();


        // Elimina claves temporales que indicaban un login pendiente de 2FA
        $request->session()->forget(['2fa:pending:id', '2fa:remember']);

        // Invalidate borra la sesión actual
        $request->session()->invalidate();

        // Regenerar token CSRF para mayor seguridad.
        $request->session()->regenerateToken();

        //Devuelve a el home
        return redirect('/');
    }

    // Devuelve el nombre del campo que utiliza la lógica de Auth para identificar al usuario.
    public function username()
    {
        return 'Mail';
    }
}
