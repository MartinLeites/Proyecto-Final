@extends('layouts.layout')

@push('styles')
    @vite('resources/css/editar.css')
@endpush

@push('scripts')
    @vite('resources/js/mensaje.js')
@endpush


@section('content')
    <div class="editar-perfil-contenedor">
        <h1 class="editar-perfil-titulo">Editar perfil</h1>

        @if ($errors->any())
            <div class="editar-perfil-alerta" role="alert">
                <ul class="editar-perfil-alerta-lista">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('perfil.actualizar') }}"
              class="editar-perfil-formulario"
              novalidate
              autocomplete="off">
            @csrf
            @method('PUT')

            <div class="editar-perfil-grupo">
                <label for="Nombre" class="editar-perfil-etiqueta">
                    Nombre <span class="editar-perfil-pista">(opcional)</span>
                </label>
                <input id="Nombre"
                       name="Nombre"
                       type="text"
                       class="editar-perfil-input"
                       value="{{ old('Nombre') }}"
                       placeholder="Escribe solo si quieres cambiarlo"
                       autocomplete="off"
                       autocapitalize="none"
                       spellcheck="false">
            </div>

            <div class="editar-perfil-grupo">
                <label for="Mail" class="editar-perfil-etiqueta">
                    Email <span class="editar-perfil-pista">(opcional)</span>
                </label>
                <input id="Mail"
                       name="Mail"
                       type="email"
                       class="editar-perfil-input"
                       value="{{ old('Mail') }}"
                       placeholder="Escribe solo si quieres cambiarlo"
                       autocomplete="off"
                       autocapitalize="none"
                       spellcheck="false">
            </div>

            <hr class="editar-perfil-separador">

            <div class="editar-perfil-grupo">
                <label for="password" class="editar-perfil-etiqueta">
                    Nueva contraseña <span class="editar-perfil-pista">(opcional)</span>
                </label>
                <input id="password"
                       name="password"
                       type="password"
                       class="editar-perfil-input"
                       placeholder="Mínimo 8 caracteres"
                       autocomplete="new-password">
            </div>

            <div class="editar-perfil-grupo">
                <label for="password_confirmation" class="editar-perfil-etiqueta">
                    Confirmar nueva contraseña
                </label>
                <input id="password_confirmation"
                       name="password_confirmation"
                       type="password"
                       class="editar-perfil-input"
                       autocomplete="new-password">
            </div>

            <div class="editar-perfil-acciones">
                <button type="submit" class="editar-perfil-boton-primario">
                    Guardar cambios
                </button>
                <a href="{{ route('perfil.mostrar') }}" class="editar-perfil-boton-secundario">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection