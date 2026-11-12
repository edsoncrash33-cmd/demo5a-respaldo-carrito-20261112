<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aseguradora Rose')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <header class="site-header">
        <nav class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-6 py-4" aria-label="Navegación principal">
            <a href="{{ route('inicio') }}" class="brand">
                <span class="brand-mark" aria-hidden="true">♢</span>
                <span><strong>Aseguradora Rose</strong><small>Protegemos lo que más importa</small></span>
            </a>
            <div class="flex flex-wrap items-center gap-1 text-sm font-semibold">
                <a href="{{ route('inicio') }}" class="nav-link">Inicio</a>
                <a href="{{ route('catalogo') }}" class="nav-link">Catálogo</a>
                <a href="{{ route('paquetes') }}" class="nav-link">Paquetes</a>
                <a href="{{ route('nosotros') }}" class="nav-link">Nosotros</a>
                <a href="{{ route('contacto') }}" class="nav-link">Contacto</a>
                <a href="{{ route('carrito') }}" class="nav-link" aria-label="Abrir carrito de compras">🛒 Carrito ({{ count(session('carrito', [])) }})</a>
                <a href="{{ route('contacto') }}" class="admin-link">▣ Admin</a>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-6 py-8 text-sm sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} Aseguradora Rose.</p>
            <p>Tu tranquilidad, nuestra prioridad.</p>
        </div>
    </footer>
</body>
</html>
