<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class PerfilController
{
    /**
     * Mostrar el perfil del usuario autenticado.
     */
    public function mostrarPerfil(Request $request)
    {
        $user = $request->user(); // guard web
        return view('perfil.mostrar', compact('user'));
    }

    /**
     * Formulario de edición del perfil.
     */
    public function editarPerfil(Request $request)
    {
        $user = $request->user(); // guard web
        return view('perfil.editar', compact('user'));
    }

    /**
     * Actualizar: Nombre y/o Mail y/o Contraseña (opcional, parcial).
     */
    public function actualizarPerfil(Request $request)
    {
        $user = $request->user();

        // Normalizar campos opcionales: "" -> null
        $norm = static function (?string $v) {
            if ($v === null) return null;
            $v = trim($v);
            return $v === '' ? null : $v;
        };

        $request->merge([
            'Nombre'                => $norm($request->input('Nombre')),
            'Mail'                  => $norm($request->input('Mail')),
            'password'              => $norm($request->input('password')),
            'password_confirmation' => $norm($request->input('password_confirmation')),
        ]);

        $validated = $request->validate([
            'Nombre'   => ['nullable', 'string', 'max:255'],
            'Mail'     => ['nullable', 'email', Rule::unique('personas', 'Mail')->ignore($user->getKey(), $user->getKeyName())],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Detectar si realmente se va a cambiar el mail
        $nuevoMail = $validated['Mail'] ?? null;
        $mailCambiado = $nuevoMail !== null && $nuevoMail !== $user->Mail;

        // Armar datos a actualizar
        $data = array_filter([
            'Nombre' => $validated['Nombre'] ?? null,
            'Mail'   => $nuevoMail,
        ], fn($v) => !is_null($v));

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        if (!empty($data)) {
            $user->forceFill($data)->save();
            // 👆 Aquí se ejecuta el TRIGGER que pone SecretKey en NULL si cambió el Mail
        }

        // Si se cambió el mail, cerrar sesión y enviar al login
        if ($mailCambiado) {
            // Cerrar sesión del usuario
            Auth::logout();

            // Limpiar datos de 2FA en sesión
            $request->session()->forget(['2fa:pending:id', '2fa:remember']);

            // Invalidar la sesión actual y regenerar token CSRF
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('success', 'Tu correo se actualizó correctamente. 
                    Por seguridad hemos cerrado tu sesión y se ha reiniciado la configuración de 2FA. 
                    Inicia sesión nuevamente para volver a configurar la verificación en dos pasos.');
        }

        // Si no cambió el mail, flujo normal
        return redirect()
            ->route('perfil.mostrar')
            ->with('success', '¡Perfil actualizado correctamente!');
    }
}
