<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EsAdmin
{
    public function handle($request, Closure $next)
    {
        // Debe existir sesión del guard admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::guard('admin')->user();

        // Verificá tu flag existente, sin tocar DB
        if (!$user || empty($user->admin)) {
            Auth::guard('admin')->logout();
            abort(403, 'No tenés permisos de administrador.');
        }

        return $next($request);
    }
}
