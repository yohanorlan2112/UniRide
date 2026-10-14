@extends('layouts.app')
@section('titulo', 'Zonas de Calor - UniRide')

@section('content')
<!-- Plugin de Mapa de Calor para Leaflet -->
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

<div class="mb-4 mt-2 flex items-center justify-between">
    <div class="flex items-center">
        <a href="{{ route('conductor.dashboard') }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
        <div>
            <h1 class="text-xl font-bold text-uniride-azul_oscuro">Zonas de Calor 🔥</h1>
            <p class="text-xs text-gray-500">Concentración de pasajeros buscando ruta</p>
        </div>
    </div>
    <span class="bg-red-100 text-red-600 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
        {{ $puntosPasajeros->count() }} puntos activos
    </span>
</div>

<!-- Filtros rápidos -->
<div class="bg-white p-3 rounded-xl shadow-sm mb-4 flex items-center justify-between border border-gray-100">
    <div class="flex items-center space-x-2">
        <span class="text-xs font-bold text-gray-500 uppercase">Filtrar destino:</span>
        <select id="filtroDestino" onchange="actualizarMapa()" class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 font-bold text-uniride-azul_oscuro focus:outline-none focus:border-uniride-verde">
            <option value="todos">Todas las sedes</option>
            <option value="Universidad Principal">Sede Principal</option>
            <option value="La Primavera">Sede La Primavera</option>
        </select>
    </div>

    <button type="button" onclick="toggleMarcadores()" id="btnMarcadores" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-3 py-1.5 rounded-lg transition">
        👁️ Ver Puntos
    </button>
</div>

<!-- Contenedor del Mapa Térmico -->
<div class="bg-white p-4 rounded-xl shadow-md border-t-4 border-red-500 mb-6">
    <div id="mapaCalor" class="w-full h-80 rounded-lg shadow-inner border-2 border-gray-200 z-10 relative"></div>

    <!-- Leyenda de colores -->
    <div class="flex justify-between items-center mt-3 px-2 text-[11px] text-gray-500 font-medium">
        <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-blue-400 inline-block mr-1"></span> Baja demanda</span>
        <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-lime-400 inline-block mr-1"></span> Media</span>
        <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-red-500 inline-block mr-1"></span> Alta demanda (Más ingresos)</span>
    </div>

    <div class="mt-4 pt-3 border-t border-gray-100">
        <p class="text-xs text-gray-500 mb-3 text-center">
            💡 <strong>Tip:</strong> Toca sobre una zona roja en el mapa para fijar tu punto de partida allí y ganar más cupos.
        </p>
        <a id="btnCrearRutaAqui" href="{{ route('conductor.rutas.crear') }}" class="block text-center w-full bg-uniride-verde text-white font-titulo font-bold py-3 rounded-lg shadow-md hover:opacity-90 transition">
            ➕ Trazar Ruta en Zona Caliente
        </a>
    </div>
</div>

<script>
    // Pasamos los puntos de PostgreSQL a JavaScript de forma segura
    const puntosBD = @json($puntosPasajeros);
    let mapa, capaCalor, grupoMarcadores;
    let mostrarPines = false;
    let marcadorSeleccionado = null;

    document.addEventListener("DOMContentLoaded", function() {
        // Centrado inicial en Ocaña
        const latOcana = 8.2377;
        const lngOcana = -73.3560;

        mapa = L.map('mapaCalor').setView([latOcana, lngOcana], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(mapa);

        grupoMarcadores = L.layerGroup();
        actualizarMapa();

        // Permitir al conductor tocar cualquier zona caliente para iniciar su ruta ahí
        mapa.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            if (marcadorSeleccionado) {
                marcadorSeleccionado.setLatLng(e.latlng);
            } else {
                marcadorSeleccionado = L.marker([lat, lng]).addTo(mapa);
            }

            marcadorSeleccionado.bindPopup("<b>🚗 Iniciar tu ruta aquí</b><br>Haz clic en el botón verde abajo.").openPopup();

            // Actualizamos el botón para que envíe estas coordenadas al formulario de crear ruta
            const urlBase = "{{ route('conductor.rutas.crear') }}";
            const btn = document.getElementById('btnCrearRutaAqui');
            btn.href = `${urlBase}?lat=${lat}&lng=${lng}`;
            btn.innerHTML = "🚀 Crear Ruta desde el Punto Seleccionado";
        });
    });

    function actualizarMapa() {
        const filtro = document.getElementById('filtroDestino').value;
        const coordenadasCalor = [];
        grupoMarcadores.clearLayers();

        puntosBD.forEach(punto => {
            if (filtro === 'todos' || punto.destino_final === filtro) {
                const lat = parseFloat(punto.latitud_origen);
                const lng = parseFloat(punto.longitud_origen);

                if (!isNaN(lat) && !isNaN(lng)) {
                    // [latitud, longitud, intensidad (0.8 para que resalte bien el calor)]
                    coordenadasCalor.push([lat, lng, 0.9]);

                    // También creamos un círculo informativo por si activa "Ver Puntos"
                    const precio = new Intl.NumberFormat('es-CO').format(punto.precio_ofrecido);
                    const marker = L.circleMarker([lat, lng], {
                        radius: 7,
                        fillColor: "#21A179",
                        color: "#FFFFFF",
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.9
                    }).bindPopup(`
                        <div class="text-xs">
                            <p class="font-bold text-blue-900 mb-1">🎒 Pasajero esperando ruta</p>
                            <p><strong>Destino:</strong> ${punto.destino_final ?? 'Universidad'}</p>
                            <p><strong>Aporte:</strong> <span class="text-green-600 font-bold">$${precio}</span></p>
                        </div>
                    `);

                    grupoMarcadores.addLayer(marker);
                }
            }
        });

        // Si ya existía una capa de calor, la quitamos antes de redibujar
        if (capaCalor) {
            mapa.removeLayer(capaCalor);
        }

        // Dibujamos las manchas térmicas
        capaCalor = L.heatLayer(coordenadasCalor, {
            radius: 32,
            blur: 20,
            maxZoom: 16,
            max: 1.0,
            gradient: {
                0.3: 'blue',
                0.5: 'lime',
                0.7: 'yellow',
                1.0: 'red'
            }
        }).addTo(mapa);
    }

    function toggleMarcadores() {
        mostrarPines = !mostrarPines;
        const btn = document.getElementById('btnMarcadores');
        if (mostrarPines) {
            grupoMarcadores.addTo(mapa);
            btn.classList.replace('bg-gray-100', 'bg-uniride-azul_claro');
            btn.classList.replace('text-gray-700', 'text-white');
            btn.innerHTML = "🙈 Ocultar Puntos";
        } else {
            mapa.removeLayer(grupoMarcadores);
            btn.classList.replace('bg-uniride-azul_claro', 'bg-gray-100');
            btn.classList.replace('text-white', 'text-gray-700');
            btn.innerHTML = "👁️ Ver Puntos";
        }
    }
</script>
@endsection