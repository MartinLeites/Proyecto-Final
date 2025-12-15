<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'ShockingSales')</title>


    @vite('resources/css/barra.css')

    @stack('styles')

    @vite('resources/js/user-menu.js')
    @stack('script')

</head>

<body>

<header>

    {{-- LOGO / HOME --}}
    <a href="{{ url('/') }}" id="brand" aria-label="Inicio">
        <img src="{{ asset('imagenes/logo1.png') }}" alt="ShockingSales" id="logo">
    </a>

    {{-- BARRA DE BÚSQUEDA PRINCIPAL --}}
    <form id="site-search" action="{{ route('search.results') }}" method="GET" role="search">
        <input type="search" name="query" id="search-input" placeholder="Buscar en ShockingSales..."
               value="{{ request('query') }}" required minlength="2">
        <button type="submit" class="button" id="search-button">
            🔍
        </button>
    </form>

    {{-- MENÚ PRINCIPAL --}}
    <nav id="primary-nav" aria-label="Principal">
        <ul id="menu">
            <li><a href="{{ url('/') }}">Home</a></li>

            {{-- Invitado --}}
            @guest('web')
                <li><a href="{{ route('login') }}">Entrar</a></li>
                <li><a href="{{ route('registro.usuario') }}">Registrarse</a></li>
            @endguest

            {{-- Usuario logueado --}}
            @auth('web')

                {{-- MENÚ DESPLEGABLE DE USUARIO (USA user-menu.js) --}}
                <li class="user-menu-container">

                    <div class="user-menu" id="userMenu">
                        <button type="button" id="userTrigger" class="user-select" aria-haspopup="menu" aria-expanded="false">
                            
                            {{-- Avatar con inicial --}}
                            <span class="avatar">
                                {{ strtoupper(substr(Auth::guard('web')->user()->Nombre ?? 'U', 0, 1)) }}
                            </span>

                            <span class="label">Cuenta</span>
                            <span class="caret" aria-hidden="true"></span>
                        </button>

                        <div class="user-dropdown" id="userDropdown" role="menu">
                            <a href="{{ route('perfil.mostrar') }}" class="menu-item" role="menuitem">Perfil</a>

                            <a href="#" class="menu-item" role="menuitem">Wishlist</a>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="menu-item" role="menuitem">
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>

                </li>

            @endauth

        </ul>
    </nav>

</header>


    <main>
        <section class="container">
            @yield('content')
        </section>
    </main>

    <footer>
        © {{ date('Y') }} ShockingSales
    </footer>


    @stack('scripts')
</body>

</html>
