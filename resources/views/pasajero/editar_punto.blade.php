@extends('layouts.app')
@section('titulo', 'Editar Punto - UniRide')

@section('content')
<div class="mb-4 mt-2 flex items-center">
    <a href="{{ route('pasajero.puntos.index') }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
    <h1 class="text-xl font-bold text-uniride-azul_oscuro">Editar Punto</h1>
</div>

<div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-uniride-azul_claro mb-8">
    <form method="POST" action="{{ route('pasajero.puntos.actualizar', $punto->id_solicitud_pasajero) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Mapa interactivo -->
        <div>
            <label class="block text-xs font-bold text-uniride-azul_oscuro mb-2 uppercase">📍 Actualiza tu ubicación</label>
            <div id="mapaEditar" class="w-full h-64 rounded-lg shadow-inner border-2 border-gray-200 z-10 relative"></div>
            
            <input type="hidden" name="latitud_origen" id="latitud_origen" value="{{ $punto->latitud_origen }}" required>
            <input type="hidden" name="longitud_origen" id="longitud_origen" value="{{ $punto->longitud_origen }}" required>
        </div>

        <div>
            <label class="block text-xs font-bold text-uniride-verde mb-1 uppercase">Destino Final</label>
            <select name="destino_final" required class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde font-medium text-gray-700">
                <option value="Universidad Principal" {{ $punto->destino_final == 'Universidad Principal' ? 'selected' : '' }}>Universidad (Sede Principal)</option>
                <option value="La Primavera" {{ $punto->destino_final == 'La Primavera' ? 'selected' : '' }}>Sede La Primavera</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-uniride-azul_oscuro mb-1 uppercase">Aporte para gasolina</label>
            <div class="relative">
                <span class="absolute left-0 top-2 text-gray-500 font-bold">$</span>
                <input type="number" name="precio_ofrecido" value="{{ $punto->precio_ofrecido }}" required min="1000" step="500" class="w-full border-b-2 border-gray-200 py-2 pl-5 focus:outline-none focus:border-uniride-verde font-bold text-lg text-uniride-verde">
            </div>
        </div>

        <button type="submit" class="w-full bg-uniride-azul_oscuro text-white font-titulo font-bold py-3 rounded-lg shadow-md hover:bg-opacity-90 mt-6">
            Guardar Cambios
        </button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Leemos las coordenadas guardadas de la base de datos
        var lat = parseFloat(document.getElementById('latitud_origen').value) || 8.2377;
        var lng = parseFloat(document.getElementById('longitud_origen').value) || -73.3560;
        
        var mapa = L.map('mapaEditar').setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(mapa);

        var marcador = L.marker([lat, lng], { draggable: true }).addTo(mapa);

        marcador.on('dragend', function(event) {
            var posicion = marcador.getLatLng();
            document.getElementById('latitud_origen').value = posicion.lat;
            document.getElementById('longitud_origen').value = posicion.lng;
        });
    });
</script>
@endsection