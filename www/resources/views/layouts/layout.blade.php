<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'ShockingSales')</title>

    
    @vite('resources/css/layout.css')

    @stack('styles')

    @stack('script')

</head>
<body>

    <header>
        <div class="header-inner">
            <a href="{{ url('/') }}" class="brand" aria-label="Inicio">
                <img src="{{ asset('imagenes/logo1.png') }}" alt="ShockingSales" class="logo-img">
            </a>
        </div>
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
