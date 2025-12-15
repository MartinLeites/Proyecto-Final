@extends('layouts.layout')

@section('title', 'Comprobación de Usuario')

@push('styles')
    @vite('resources/css/2fa.css')
@endpush

@section('content')
    <div class="contenedor-2fa">
        @if (session('status'))
            <div class="alerta alerta--exito alerta--espaciado">{{ session('status') }}</div>
        @endif

        <h1 class="titulo-2fa">Verificación en dos pasos</h1>

        @if (!empty($showQr))
            <p class="texto-2fa texto-2fa--margen">
                Escanea este QR con tu app TOTP (Google Authenticator, etc.).
            </p>

            <div class="qr-2fa">
                {!! $qrSvg !!}
            </div>

            <p class="texto-2fa texto-2fa--pequeno">
                ¿No puedes escanear? Ingresa este código manualmente:
                <code class="codigo-manual-2fa">{{ $secret ?? '' }}</code>
            </p>
        @else
            <p class="texto-2fa texto-2fa--margen">
                Ingresa el código de tu app de autenticación.
            </p>
        @endif

        <form method="POST"
              action="{{ route('2fa.verify') }}"
              class="formulario-2fa"
              autocomplete="off">
            @csrf

            <label class="campo-2fa">
                <span class="etiqueta-2fa">Código de 6 dígitos</span>
                <input
                    name="code"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    autocomplete="one-time-code"
                    class="input-codigo-2fa"
                    placeholder="123456"
                    required
                    autofocus
                >
            </label>

            @error('code')
                <div class="mensaje-error-2fa">{{ $message }}</div>
            @enderror

            <button type="submit" class="boton-primario-2fa">
                Verificar
            </button>
        </form>

        <div class="pie-2fa">
            <a href="{{ route('login') }}" class="enlace-secundario-2fa">
                Cambiar de cuenta
            </a>
        </div>
    </div>
@endsection
