@extends('layouts.app')
@section('titulo', 'Trazar Ruta - UniRide')

@section('content')
<div class="mb-4 mt-2 flex items-center">
    <a href="{{ route('conductor.rutas.index') }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
    <h1 class="text-xl font-bold text-uniride-azul_oscuro">Trazar Nueva Ruta</h1>
</div>

<div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-uniride-verde mb-8">
    <form method="POST" action="{{ route('conductor.rutas.guardar') }}" class="space-y-5">
        @csrf

        <!-- Mapa interactivo -->
        <div>
            <label class="block text-xs font-bold text-uniride-azul_oscuro mb-2 uppercase">🚗 Mueve el pin a tu punto de partida</label>
            <div id="mapaConductor" class="w-full h-64 rounded-lg shadow-inner border-2 border-gray-200 z-10 relative"></div>
            
            <input type="hidden" name="latitud_origen" id="latitud_origen" required>
            <input type="hidden" name="longitud_origen" id="longitud_origen" required>
        </div>
        <div>
            <label class="block text-xs font-bold text-uniride-azul_oscuro mb-1 uppercase">🚘 ¿En qué vehículo harás esta ruta?</label>
            <select name="id_vehiculo" required class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde font-medium text-gray-700">
                <option value="" disabled {{ !isset($ruta) ? 'selected' : '' }}>Selecciona tu vehículo...</option>
                @foreach($vehiculos as $v)
                    <option value="{{ $v->id_vehiculo }}" {{ (isset($ruta) && $ruta->id_vehiculo == $v->id_vehiculo) ? 'selected' : '' }}>
                        {{ $v->tipo_vehiculo == 'moto' ? '🛵' : '🚗' }} {{ strtoupper($v->placa) }} - {{ $v->marca_modelo }} ({{ $v->capacidad_pasajeros ?? 1 }} cupo/s)
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-uniride-verde mb-1 uppercase">Destino Final</label>
            <select name="destino_final" required class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde font-medium text-gray-700">
                <option value="" disabled selected>Selecciona hacia dónde vas...</option>
                <option value="Universidad Principal">Universidad (Sede Principal)</option>
                <option value="La Primavera">Sede La Primavera</option>
            </select>
        </div>

        <hr class="border-gray-100">

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-2 uppercase">¿Qué días haces esta ruta?</label>
            <div class="grid grid-cols-3 gap-2">
                @foreach($dias as $dia)
                <label class="cursor-pointer">
                    <input type="checkbox" name="dias[]" value="{{ $dia->id_dia }}" class="peer sr-only">
                    <div class="p-2 border rounded text-center text-xs font-bold transition-all peer-checked:bg-uniride-azul_oscuro peer-checked:text-white peer-checked:border-uniride-azul_oscuro hover:bg-gray-50">
                        {{ substr($dia->nombre, 0, 3) }}
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Hora de Salida</label>
            <input type="time" name="hora_salida" required class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde">
        </div>

        <button type="submit" class="w-full bg-uniride-verde text-white font-titulo font-bold py-3 rounded-lg shadow-md hover:opacity-90 mt-6">
            Activar Ruta
        </button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        var latitudOcana = parseFloat(urlParams.get('lat')) || 8.2377;
        var longitudOcana = parseFloat(urlParams.get('lng')) || -73.3560;
        
        var mapa = L.map('mapaConductor').setView([latitudOcana, longitudOcana], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(mapa);

        var marcador = L.marker([latitudOcana, longitudOcana], {
            draggable: true
        }).addTo(mapa);

        document.getElementById('latitud_origen').value = latitudOcana;
        document.getElementById('longitud_origen').value = longitudOcana;

        marcador.on('dragend', function(event) {
            var posicion = marcador.getLatLng();
            document.getElementById('latitud_origen').value = posicion.lat;
            document.getElementById('longitud_origen').value = posicion.lng;
        });
    });
</script>
@endsection