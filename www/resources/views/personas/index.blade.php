@extends('layouts.admin')

@section('title', 'Listado de Personas')

@push('styles')
    @vite('resources/css/personas.css')
@endpush

@section('content')    
<h1>Listado de personas</h1>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Mail</th>
            <th>Estado</th>
            <th style="width: 260px;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($personas as $persona)
            <tr>
                <td>{{ $persona->Nombre }}</td>
                <td>{{ $persona->Mail }}</td>
                <td>
                    @if ($persona->Estado === 'Activo')
                        <span class="badge text-bg-success">Activo</span>
                    @else
                        <span class="badge text-bg-secondary">Inactivo</span>
                    @endif
                </td>
                <td>
                    @if ($persona->Estado === 'Activo')
                        <form action="{{ route('personas.desactivar') }}" method="POST" style="display:inline">
                            @csrf
                            <input type="hidden" name="Mail" value="{{ $persona->Mail }}">
                            <button type="submit" class="btn btn-outline-warning btn-sm"
                                onclick="return confirm('¿Desactivar esta cuenta?')">
                                Desactivar
                            </button>
                        </form>
                    @else
                        <form action="{{ route('personas.activar') }}" method="POST" style="display:inline">
                            @csrf
                            <input type="hidden" name="Mail" value="{{ $persona->Mail }}">
                            <button type="submit" class="btn btn-outline-success btn-sm">
                                Activar
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('personas.destroy', $persona) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Eliminar esta persona?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No hay personas disponibles.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $personas->links() }}
@endsection
