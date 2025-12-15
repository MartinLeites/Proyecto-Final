@extends('layouts.admin')

@section('title', 'Panel Administrador')

@push('styles')
    @vite('resources/css/panel.css')
@endpush


@section('content')


<h2>Configuraciones del Sistema </h2>
<h3>  <a href="{{ route('personas.index') }}">Usuarios de ShockingSales</a></h3>






@endsection
