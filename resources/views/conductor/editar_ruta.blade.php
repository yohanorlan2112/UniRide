@extends('layouts.app')
@section('titulo', 'Editar Ruta - UniRide')
@section('content')
<div class="mb-4 mt-2 flex items-center">
    <a href="{{ route('conductor.rutas.index') }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
    <h1 class="text-xl font-bold text-uniride-azul_oscuro">Editar Ruta</h1>
</div>

<div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-uniride-azul_claro mb-8">
    <form method="POST" action="{{ route('conductor.rutas.actualizar', $ruta->id_ruta_conductor) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Mapa interactivo con la ubicación guardada -->
        <div>
            <label class="block text-xs font-bold text-uniride-azul_oscuro mb-2 uppercase">🚗 Punto de partida en el mapa</label>
            <div id="mapaEditarRuta" class="w-full h-64 rounded-lg shadow-inner border-2 border-gray-200 z-10 relative"></div>
            
            <input type="hidden" name="latitud_origen" id="latitud_origen" value="{{ $ruta->latitud_origen }}" required>
            <input type="hidden" name="longitud_origen" id="longitud_origen" value="{{ $ruta->longitud_origen }}" required>
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
                <option value="Universidad Principal" {{ $ruta->destino_final == 'Universidad Principal' ? 'selected' : '' }}>Universidad (Sede Principal)</option>
                <option value="La Primavera" {{ $ruta->destino_final == 'La Primavera' ? 'selected' : '' }}>Sede La Primavera</option>
            </select>
        </div>

        <hr class="border-gray-100">

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-2 uppercase">¿Qué días haces esta ruta?</label>
            <div class="grid grid-cols-3 gap-2">
                @foreach($dias as $dia)
                <label class="cursor-pointer">
                    <input type="checkbox" name="dias[]" value="{{ $dia->id_dia }}" class="peer sr-only" 
                        {{ in_array($dia->id_dia, $diasSeleccionados) ? 'checked' : '' }}>
                    <div class="p-2 border rounded text-center text-xs font-bold transition-all peer-checked:bg-uniride-azul_oscuro peer-checked:text-white peer-checked:border-uniride-azul_oscuro hover:bg-gray-50">
                        {{ substr($dia->nombre, 0, 3) }}
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Hora de Salida</label>
                <input type="time" name="hora_salida" value="{{ $horaActual }}" required class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Estado de Ruta</label>
                <select name="esta_activa" class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde font-bold text-sm">
                    <option value="1" {{ $ruta->esta_activa ? 'selected' : '' }}>🟢 En circulación</option>
                    <option value="0" {{ !$ruta->esta_activa ? 'selected' : '' }}>⏸️ Pausada</option>
                </select>
            </div>
        </div>

        <button type="submit" class="w-full bg-uniride-azul_oscuro text-white font-titulo font-bold py-3 rounded-lg shadow-md hover:opacity-90 mt-6">
            Guardar Cambios
        </button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var lat = parseFloat(document.getElementById('latitud_origen').value) || 8.2377;
        var lng = parseFloat(document.getElementById('longitud_origen').value) || -73.3560;
        
        var mapa = L.map('mapaEditarRuta').setView([lat, lng], 15);

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