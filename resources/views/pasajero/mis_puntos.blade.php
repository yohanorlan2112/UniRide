@extends('layouts.app')
@section('titulo', 'Mis Puntos - UniRide')
@section('content')
<div class="mb-4 mt-2 flex items-center justify-between">
    <div class="flex items-center">
        <a href="{{ route('pasajero.dashboard') }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
        <h1 class="text-xl font-bold text-uniride-azul_oscuro">Puntos de Recogida</h1>
    </div>
    <a href="{{ route('pasajero.puntos.crear') }}" class="text-xs bg-uniride-verde text-white px-3 py-1 rounded-full font-bold">+ Añadir</a>
</div>

<div class="space-y-4 mt-6">
    @forelse($solicitudes as $punto)
        <div class="bg-white p-4 rounded-xl shadow border border-gray-100">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h4 class="font-bold text-uniride-azul_oscuro">
                        {{ $punto->latitud_origen ? 'Ubicación en el mapa 📍' : 'Punto guardado' }}
                    </h4>
                    <p class="text-xs text-gray-500 font-medium">Hacia: {{ $punto->destino_final ?? 'Universidad' }}</p>
                </div>
                <span class="text-sm font-bold text-uniride-verde">${{ number_format($punto->precio_ofrecido, 0, ',', '.') }}</span>
            </div>

            <div class="flex space-x-2 mb-4">
                <a href="{{ route('pasajero.puntos.editar', $punto->id_solicitud_pasajero) }}" class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded hover:bg-gray-200">✏️ Editar</a>
                <form action="{{ route('pasajero.puntos.eliminar', $punto->id_solicitud_pasajero) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este punto?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs font-bold text-red-500 bg-red-50 px-3 py-1 rounded hover:bg-red-100">🗑️ Eliminar</button>
                </form>
            </div>

            <!-- Botón de acción principal -->
            @php
                // Verificamos si este punto ya tiene un viaje en curso
                $viajeActivo = $punto->viajes->whereIn('estado_viaje', ['Pendiente', 'Aceptado'])->first();
            @endphp

            @if($viajeActivo)
                <!-- Si ya hay viaje, ocultamos la búsqueda y mostramos el botón de cancelar -->
                <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200 text-center">
                    <p class="text-xs text-yellow-700 font-bold mb-2">Ya tienes un viaje {{ strtolower($viajeActivo->estado_viaje) }} desde aquí.</p>
                    <form action="{{ route('pasajero.viajes.cancelar', $viajeActivo->id_viaje) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas cancelar tu viaje activo?');">
                        @csrf
                        <button type="submit" class="w-full text-xs font-bold text-red-500 bg-white border border-red-200 py-2 rounded-lg hover:bg-red-50 shadow-sm">
                            Cancelar Viaje Actual
                        </button>
                    </form>
                </div>
            @else
                <!-- Si no hay viaje, se muestra el botón normal -->
                <a href="{{ route('pasajero.buscar_conductores', $punto->id_solicitud_pasajero) }}" class="block text-center w-full text-sm font-bold text-white bg-uniride-azul_oscuro py-2 rounded-lg shadow-sm hover:opacity-90">
                    🔍 Buscar conductores
                </a>
            @endif
            
        </div>
    @empty
        <div class="text-center p-6 bg-white rounded-xl shadow">
            <p class="text-gray-500 text-sm mb-3">No tienes puntos de recogida guardados.</p>
        </div>
    @endforelse
</div>
@endsection