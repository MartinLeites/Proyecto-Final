<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ShockingSales')</title>

    @vite('resources/css/adminStyle.css')
    @vite('resources/js/user-menu.js')
    @stack('styles')
</head>

<body>
    @php
        $admin = Auth::guard('admin')->user();
    @endphp

    <header id="site-header">
        <div id="header-bar">
            <a href="{{ url('/') }}" id="brand" aria-label="Inicio">
                <img src="{{ asset('imagenes/logo1.png') }}" alt="ShockingSales" id="logo">
            </a>

            {{-- Menú de cuenta (Admin) --}}
            @if ($admin)
                <div class="user-menu" id="userMenu">
                    <button type="button" id="userTrigger" class="user-select" aria-haspopup="menu"
                        aria-expanded="false">
                        <span class="avatar">{{ strtoupper(substr($admin->Nombre ?? 'A', 0, 1)) }}</span>
                        <span class="label">Admin</span>
                        <span class="caret" aria-hidden="true"></span>
                    </button>

                    <div class="user-dropdown" id="userDropdown" role="menu">
                        <a href="{{ route('perfil.mostrar') }}" class="menu-item" role="menuitem">Perfil</a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="menu-item" role="menuitem">Salir</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </header>

    <nav id="primary-nav" aria-label="Principal">
        <ul id="menu">
            <li>
                <a href="{{ url('/') }}" aria-current="{{ request()->is('/') ? 'page' : '' }}" id="home">
                    Home
                </a>
            </li>
            <li>
                <a href="{{ route('VistaAdmin.panelAdmin') }}" id="panel">
                    Panel de Configuración
                </a>
            </li>
            <li>
                <a href="{{ route('registro.admin') }}" id="registroAdmin">
                    Nuevo Administrador
                </a>
            </li>
        </ul>
    </nav>

    <main id="site-main">
        <section id="content" class="container">
            @yield('content')
        </section>
    </main>

    <footer id="site-footer">
        © {{ date('Y') }} ShockingSales
    </footer>

    @stack('scripts')
</body>

</html>
