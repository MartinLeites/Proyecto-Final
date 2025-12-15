<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Mi Aplicación')</title>

  @yield('styles')
</head>
<body>
  <div class="layout">
    <header>
      <div class="header-content">
        <a class="brand" href="{{ route('home') }}">
          <img src="{{ asset('imagenes/Logo1.JPG') }}" alt="Logo">
        </a>
          <h1>Registro </h1>

        <nav>
          <ul>
            @auth
              <li>
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button class="button" type="submit">Cerrar sesión</button>
                </form>
              </li>
            @endauth
          </ul>
        </nav>
      </div>
    </header>

    <main>
      <div class="container">
        @yield('content')
      </div>
    </main>

    <footer>
      <p>&copy; {{ date('Y') }} Mi Aplicación. Todos los derechos reservados.</p>
    </footer>
  </div>

  @stack('scripts')
</body>
</html>