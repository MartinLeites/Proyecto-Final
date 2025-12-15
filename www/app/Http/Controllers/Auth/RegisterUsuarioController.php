<?php

namespace App\Http\Controllers\Auth;

use App\Services\Users\UserDataService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterUsuarioController
{
    public function mostrarFormularioUsuario(): View
    {
        return view('registro.registro-usuario');
    }

    public function registrarUsuario(Request $request, UserDataService $service): RedirectResponse
    {
        $validated = $request->validate([
            'Nombre'   => ['required', 'string', 'max:255'],
            'Mail'     => ['required', 'email', 'unique:personas,Mail'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Si el SP falla, lanzará una excepción y Laravel mostrará un error 500
        // (o lo podrás capturar con try/catch si querés algo más fino)
        $service->registrarUsuario(
            nombre:        $validated['Nombre'],
            mail:          $validated['Mail'],
            passwordPlano: $validated['password']
        );

        return redirect()
            ->route('login')
            ->with('success', 'Usuario registrado. Inicia sesión para continuar.');
    }
}
