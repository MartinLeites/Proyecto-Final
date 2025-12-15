@extends('layouts.layout')

@push('styles')
    @vite('resources/css/perfil.css')
@endpush

@section('content')
    <div class="perfil-contenedor">
        @if (session('success'))
            <div class="perfil-alerta-exito">
                {{ session('success') }}
            </div>
        @endif

        <h1 class="perfil-titulo">Mi perfil</h1>

        <div class="perfil-contenido">
            <div class="perfil-datos">
                <p><strong>Nombre:</strong> {{ $user->Nombre }}</p>
                <p><strong>Email:</strong> {{ $user->Mail }}</p>
            </div>
        </div>

        <div class="perfil-acciones">
            <a href="{{ route('perfil.editar') }}" class="perfil-boton-perfil">
                Editar perfil
            </a>
            <a href="{{ url()->previous() }}" class="perfil-boton-secundario">
                Volver
            </a>
        </div>
    </div>
@endsection
