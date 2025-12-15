<?php

namespace App\Http\Controllers\Auth;

use App\Services\Users\UserDataService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class RegisterAdminController 
{
    public function mostrarFormularioAdmin()
    {
        return view('registro.registro-admin');
    }

      public function RegistrarAdmin(Request $request, UserDataService $service): RedirectResponse
{
    $validated = $request->validate([
        'Nombre'   => ['required', 'string', 'max:255'],
        'Mail'     => ['required', 'email', 'unique:personas,Mail'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $service->registrarAdmin(
        nombre:        $validated['Nombre'],
        mail:          $validated['Mail'],
        passwordPlano: $validated['password']
    );

    return redirect()
        ->route('registro.admin')
        ->with('success', 'Administrador registrado. Inicia sesión para continuar.');
}

}
