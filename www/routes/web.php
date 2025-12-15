<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterUsuarioController;
use App\Http\Controllers\Auth\RegisterAdminController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\Auth\TwoFAController;

/*
|--------------------------------------------------------------------------
| Página principal (pública)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Busqueda
|--------------------------------------------------------------------------
*/
Route::post('/buscar', [SearchController::class, 'search'])->name('search.execute');
Route::get('/buscar',  [SearchController::class, 'search'])->name('search.results');
Route::get('/product/{storeName}/{productId}', [App\Http\Controllers\SearchController::class, 'showProductDetail'])->name('product.detail');

/*
|--------------------------------------------------------------------------
| Invitados (no logueados)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    // Login
    Route::get('login', [LoginController::class, 'mostrarLogin'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Registro de Usuario
    Route::get('registro/usuario', [RegisterUsuarioController::class, 'mostrarFormularioUsuario'])
        ->name('registro.usuario');

    Route::post('registro/usuario', [RegisterUsuarioController::class, 'RegistrarUsuario'])
        ->name('registro.usuario.store');
});

// 2FA 
Route::get('/2fa',  [TwoFAController::class, 'show'])->name('2fa.show');
Route::post('/2fa', [TwoFAController::class, 'verify'])->name('2fa.verify');

// Logout usuario (guard web)
Route::post('logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth:web');

/*
|--------------------------------------------------------------------------
| Rutas de Usuario autenticado  (guard: web)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:web')->group(function () {
    Route::resource('personas', PersonaController::class);

    Route::get('/perfil',        [PerfilController::class, 'mostrarPerfil'])->name('perfil.mostrar');
    Route::get('/perfil/editar', [PerfilController::class, 'editarPerfil'])->name('perfil.editar');
    Route::put('/perfil',        [PerfilController::class, 'actualizarPerfil'])->name('perfil.actualizar');
});

/*
|--------------------------------------------------------------------------
| Rutas de ADMIN (guard: admin)
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', fn() => redirect()->route('login'))->name('admin.login');

Route::post('/admin/logout', function () {
    if (Auth::guard('admin')->check()) {
        Auth::guard('admin')->logout();
    }
    return redirect()->route('login');
})->name('admin.logout');

Route::prefix('admin')->middleware(['auth:admin', 'admin'])->group(function () {

    Route::get('/dashboard', function () {
        return view('VistaAdmin.panelAdmin');
    })->name('VistaAdmin.panelAdmin');

    Route::get('/dashboard/Administracion-De-Usuarios', [PersonaController::class, 'index'])
        ->name('Administracion-De-Usuarios');

    Route::post('/personas/activar',    [PersonaController::class, 'activarCuenta'])->name('personas.activar');
    Route::post('/personas/desactivar', [PersonaController::class, 'desactivarCuenta'])->name('personas.desactivar');

    Route::get('registro/admin', [RegisterAdminController::class, 'mostrarFormularioAdmin'])
        ->name('registro.admin');
    Route::post('registro/admin', [RegisterAdminController::class, 'RegistrarAdmin'])
        ->name('registro.admin.store');
});
