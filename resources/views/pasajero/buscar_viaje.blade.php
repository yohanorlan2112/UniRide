@extends('layouts.app')
@section('titulo', 'Nuevo Punto de Recogida - UniRide')

@section('content')
<div class="mb-4 mt-2 flex items-center">
    <a href="{{ route('pasajero.puntos.index') }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
    <h1 class="text-xl font-bold text-uniride-azul_oscuro">Añadir Punto de Recogida</h1>
</div>

<div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-uniride-verde mb-8">
    <form method="POST" action="{{ route('pasajero.puntos.guardar') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold text-uniride-azul_oscuro mb-2 uppercase">📍 Mueve el pin a tu ubicación exacta</label>
            <div id="mapa" class="w-full h-64 rounded-lg shadow-inner border-2 border-gray-200 z-10 relative"></div>
            
            <input type="hidden" name="latitud_origen" id="latitud_origen" required>
            <input type="hidden" name="longitud_origen" id="longitud_origen" required>
        </div>

        <div>
            <label class="block text-xs font-bold text-uniride-verde mb-1 uppercase">Destino Final</label>
            <select name="destino_final" required class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde text-gray-700 font-medium">
                <option value="" disabled selected>Selecciona a qué sede vas...</option>
                <option value="Universidad Principal">Universidad (Sede Principal)</option>
                <option value="La Primavera">Sede La Primavera</option>
            </select>
        </div>

        <hr class="border-gray-100">

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">¿Qué día?</label>
                <select name="id_dia" required class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde">
                    @foreach($dias as $dia)
                        <option value="{{ $dia->id_dia }}">{{ $dia->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">¿A qué hora?</label>
                <input type="time" name="hora_recogida" required class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-uniride-azul_oscuro mb-1 uppercase">Aporte para gasolina</label>
            <div class="relative">
                <span class="absolute left-0 top-2 text-gray-500 font-bold">$</span>
                <input type="number" name="precio_ofrecido" required min="1000" step="500" placeholder="Ej: 2000" class="w-full border-b-2 border-gray-200 py-2 pl-5 focus:outline-none focus:border-uniride-verde font-bold text-lg text-uniride-verde">
            </div>
        </div>

        <button type="submit" class="w-full bg-uniride-verde text-white font-titulo font-bold py-3 rounded-lg shadow-md hover:opacity-90 mt-6">
            Guardar Punto de Recogida
        </button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var latitudOcana = 8.2377;
        var longitudOcana = -73.3560;
        
        var mapa = L.map('mapa').setView([latitudOcana, longitudOcana], 15);

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