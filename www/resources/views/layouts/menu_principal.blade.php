<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ShockingSales')</title>

    @auth('admin')
    @vite('resources/css/adminStyle.css')
    @else
    @vite('resources/css/plantilla.css')
    @endauth

    @vite('resources/js/user-menu.js')
    @stack('styles')
</head>

<body>
    @php
    $isAdmin = Auth::guard('admin')->check();
    $isUser = Auth::guard('web')->check() && !$isAdmin;
    @endphp

    <header id="site-header">
        <div id="header-bar" class="container">
            <a href="{{ url('/') }}" id="brand" aria-label="Inicio">
                <img src="{{ asset('imagenes/logo1.png') }}" alt="ShockingSales" id="logo">
            </a>

            <nav id="primary-nav" aria-label="Principal">
                <ul id="menu">
                    <li><a href="{{ url('/') }}">Home</a></li>

                    {{-- Invitado: solo Entrar / Registrarse --}}
                    @guest('web')
                    @guest('admin')
                    <li><a href="{{ route('login') }}">Entrar</a></li>
                    <li><a href="{{ route('registro.usuario') }}">Registrarse</a></li>
                    @endguest
                    @endguest

                    {{-- ADMIN: accesos del panel en el menú lateral --}}
                    @if ($isAdmin)
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
                    @endif
                </ul>
            </nav>

            {{-- MENÚ DE CUENTA (solo si hay usuario o admin logueado) --}}
            @if ($isAdmin || $isUser)
            <div class="user-menu" id="userMenu">
                <button type="button" id="userTrigger" class="user-select" aria-haspopup="menu"
                    aria-expanded="false">

                    {{-- Avatar con inicial --}}
                    <span class="avatar">
                        @if ($isAdmin)
                        {{ strtoupper(substr(Auth::guard('admin')->user()->Nombre ?? 'A', 0, 1)) }}
                        @else
                        {{ strtoupper(substr(Auth::guard('web')->user()->Nombre ?? 'U', 0, 1)) }}
                        @endif
                    </span>

                    <span class="label">
                        {{ $isAdmin ? 'Admin' : 'Cuenta' }}
                    </span>
                    <span class="caret" aria-hidden="true"></span>
                </button>

                <div class="user-dropdown" id="userDropdown" role="menu">
                    <a href="{{ route('perfil.mostrar') }}" class="menu-item" role="menuitem">
                        Perfil
                    </a>

                    {{-- Wishlist solo para usuario normal, nunca para admin --}}
                    @if ($isUser)
                    <a href="#" class="menu-item" role="menuitem">
                        Wishlist
                    </a>
                    @endif

                    <form action="{{ $isAdmin ? route('logout') : route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="menu-item" role="menuitem">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </header>

    {{-- HERO con buscador XL y mensaje claro --}}
    <section id="hero">
        <div class="container">
            @guest('admin')
            <h1 id="hero-title">Compará precios en segundos</h1>
            {{-- Buscador principal  --}}
            <form id="site-search" action="{{ route('search.results') }}" method="GET" role="search">
                <input type="search" name="query" id="search-input" placeholder="Buscar producto, modelo o SKU…"
                    value="{{ request('query') }}" aria-label="Buscar" required minlength="2">
                <button type="submit" class="button" id="search-button">Buscar</button>
            </form>
            @endguest
        </div>
    </section>

    <main id="site-main">
        @yield('content')
    </main>

    <footer id="site-footer">
        <div class="container">
            <div id="footer-links">
                <a href="{{ url('/terminos') }}">Términos</a>
                <a href="{{ url('/privacidad') }}">Privacidad</a>
                <a href="{{ url('/contacto') }}">Contacto</a>
            </div>
            <div id="copyright">© {{ date('Y') }} ShockingSales</div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>