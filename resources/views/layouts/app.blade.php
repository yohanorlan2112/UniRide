<!DOCTYPE html>
<html lang="es">
<!-- Librerías de Mapas (Leaflet) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('titulo', 'UniRide - Conecta tu ruta, comparte el camino')</title>

    <!-- Fuentes Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        uniride: {
                            azul_oscuro: '#12355B',
                            verde: '#21A179',
                            gris_oscuro: '#263238',
                            azul_claro: '#4FA3D1',
                            blanco: '#FFFFFF'
                        }
                    },
                    fontFamily: {
                        titulo: ['Montserrat', 'sans-serif'],
                        cuerpo: ['Roboto', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 text-uniride-gris_oscuro antialiased">

    <!-- Menú de Navegación Móvil -->
    <!-- Menú de Navegación Móvil -->
    <nav class="bg-uniride-azul_oscuro shadow-md relative z-50">
        <div class="max-w-md mx-auto px-4 py-4 flex justify-between items-center">
            @php
                $urlLogo = route('login');
                if(Auth::check()) {
                    if(request()->is('conductor*') || request('modo') == 'conductor') {
                        $urlLogo = route('conductor.dashboard');
                    } elseif(request()->is('pasajero*') || request('modo') == 'pasajero') {
                        $urlLogo = route('pasajero.dashboard');
                    } else {
                        $urlLogo = Auth::user()->perfilConductor ? route('conductor.dashboard') : route('pasajero.dashboard');
                    }
                }
            @endphp

            <a href="{{ $urlLogo }}" class="text-uniride-blanco font-titulo font-bold text-2xl tracking-wide">
                UNIRIDE
            </a>

            @auth
                <div class="flex items-center space-x-3">

                    <!-- Botón de Cambio de Modo -->
                    @if (request()->is('conductor*'))
                        <form action="{{ route('cambiar_modo') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="modo" value="pasajero">
                            <button type="submit"
                                class="text-xs bg-uniride-verde text-white px-3 py-1 rounded-full font-bold shadow-sm hover:opacity-90">Modo
                                Pasajero 🎒</button>
                        </form>
                    @else
                        <form action="{{ route('cambiar_modo') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="modo" value="conductor">
                            <button type="submit"
                                class="text-xs bg-uniride-azul_claro text-white px-3 py-1 rounded-full font-bold shadow-sm hover:opacity-90">Modo
                                Conductor 🚗</button>
                        </form>
                    @endif

                    <!-- Menú de Usuario (Avatar) -->
                    <div class="relative">
                        <!-- El círculo del avatar -->
                        <button onclick="document.getElementById('menuUsuario').classList.toggle('hidden')"
                            class="w-9 h-9 rounded-full bg-uniride-verde border-2 border-white flex items-center justify-center text-white font-bold shadow-md focus:outline-none overflow-hidden">
                            <!-- Aquí a futuro pondrás la etiqueta <img> de la API. Por ahora usamos la inicial -->
                            {{ substr(Auth::user()->primer_nombre, 0, 1) }}
                        </button>
                        <!-- El desplegable -->
                        <div id="menuUsuario"
                            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 border border-gray-100">
                            <div class="px-4 py-2 border-b border-gray-100 mb-1">
                                <p class="text-sm font-bold text-uniride-azul_oscuro truncate">
                                    {{ Auth::user()->primer_nombre }} {{ Auth::user()->primer_apellido }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->correo }}</p>
                            </div>

                            @php
                                // Lógica corregida: Si ya trae un modo en la URL, lo respeta. Si no, mira en qué pantalla está.
                                if (request()->has('modo')) {
                                    $modoActual = request('modo');
                                } elseif (request()->is('conductor*')) {
                                    $modoActual = 'conductor';
                                } else {
                                    $modoActual = 'pasajero';
                                }
                            @endphp

                            <a href="{{ route('perfil.ver', ['modo' => $modoActual]) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-uniride-azul_claro font-medium">👁️
                                Ver Perfil</a>

                            <a href="{{ route('perfil.editar', ['modo' => $modoActual]) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-uniride-verde font-medium">✏️
                                Editar Datos</a>
                            <a href="{{ route('calificaciones.index', ['modo' => $modoActual]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-yellow-500 font-medium">⭐ Mis Reseñas</a>

                            <form action="{{ route('logout') }}" method="POST"
                                class="block w-full border-t border-gray-50 mt-1 pt-1">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium">🚪
                                    Cerrar Sesión</button>
                            </form>
                        </div>

                    </div>

                </div>
            @endauth
        </div>
    </nav>

    <!-- Contenedor Principal -->
    <main class="max-w-md mx-auto p-4 min-h-screen">
        <!-- Alertas -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-uniride-verde text-green-700 p-4 mb-4 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- AQUÍ SE INYECTAN LAS PANTALLAS (El error suele ocurrir si borraste esta línea) -->
        @yield('content')
    </main>

</body>

</html>
