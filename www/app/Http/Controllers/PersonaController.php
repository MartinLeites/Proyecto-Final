<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Services\Users\UserDataService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PersonaController
{
    /**
     * Listado de personas que tienen usuario asociado.
     */
    public function index(UserDataService $service): View
    {
        $personas = $service->listarUsuarios(5);

        return view('personas.index', compact('personas'));
    }

    
    /**
     * Activar cuenta
     */
    public function activarCuenta(Request $request, UserDataService $service): RedirectResponse
    {
        $data = $request->validate([
            'Mail' => ['required', 'email'],
        ]);

        try {
            $msg = $service->activarUsuario($data['Mail']);

            return back()->with('status', $msg);
        } catch (\Throwable $e) {
            return back()->with('error', 'Hubo un problema al activar el usuario.');
        }
    }

    /**
     * Desactivar cuenta 
     */
    public function desactivarCuenta(Request $request, UserDataService $service): RedirectResponse
    {
        $data = $request->validate([
            'Mail' => ['required', 'email'],
        ]);

        try {
            $msg = $service->desactivarUsuario($data['Mail']);

            return back()->with('status', $msg);
        } catch (\Throwable $e) {
            
            $msg = $e->getMessage();

            if (property_exists($e, 'errorInfo') && is_array($e->errorInfo)) {
                $msg = ($e->errorInfo[2] ?? $msg)
                    . (isset($e->errorInfo[1]) ? " (#{$e->errorInfo[1]})" : '');
            }

            return back()->with('error', $msg);
        }
    }

    /**
     * Eliminar una persona.
     */
    public function destroy(Persona $persona, UserDataService $service): RedirectResponse
    {
        $service->eliminarPersona($persona);

        return redirect()
            ->route('personas.index')
            ->with('success', 'Persona eliminada correctamente.');
    }
}
