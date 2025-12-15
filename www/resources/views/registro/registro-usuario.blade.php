@extends('layouts.layout')

@section('title', 'Registro Usuario')

@push('styles')
    @vite('resources/css/registroUsuario.css')
@endpush


@section('content')
    <div class="auth-container">
        <div class="auth-card">

            <div class="card-header">
                <h1>Crear cuenta de usuario</h1>
                <p>Completa los datos para continuar</p>
            </div>

            <div class="card-body">
              
                @if (session('success'))
                    <div class="message success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="message error">{{ session('error') }}</div>
                @endif

                 
                @if ($errors->any())
                    <div class="message error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('registro.usuario.store') }}" class="auth-form">
                    @csrf

                    <div class="field">
                        <label for="Nombre">Nombre</label>
                        <input id="Nombre" name="Nombre" type="text" value="{{ old('Nombre') }}" required>
                        @error('Nombre')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="Mail">Correo electrónico</label>
                        <input id="Mail" name="Mail" type="email" value="{{ old('Mail') }}" autocomplete="email"
                            required>
                        @error('Mail')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">Contraseña</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required>
                        <div class="help-text">Mínimo 8 caracteres</div>
                        @error('password')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Confirmar contraseña</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            autocomplete="new-password" required>
                    </div>

                    <button type="submit" class="btn-primary">Registrar Usuario</button>
                </form>
            </div>

        </div>
    </div>
@endsection
