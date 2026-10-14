@extends('layouts.app')
@section('titulo', 'Mis Rutas - UniRide')
@section('content')
    <div class="mb-4 mt-2 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('conductor.dashboard') }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
            <h1 class="text-xl font-bold text-uniride-azul_oscuro">Mis Rutas</h1>
        </div>
        <a href="{{ route('conductor.rutas.crear') }}"
            class="text-xs bg-uniride-verde text-white px-3 py-1 rounded-full font-bold">+ Nueva</a>
    </div>

    <div class="space-y-4">
        @forelse($rutas as $ruta)
            <div
                class="bg-white p-4 rounded-xl shadow border-l-4 {{ $ruta->esta_activa ? 'border-uniride-verde' : 'border-gray-300' }}">

                <div class="flex items-center mb-3">
                    <div class="w-2 h-2 rounded-full bg-uniride-azul_oscuro mr-2"></div>
                    <p class="text-sm font-bold text-gray-700 truncate">
                        @if ($ruta->latitud_origen)
                            Punto de salida (Mapa) 📍
                        @else
                            {{ $ruta->origen->nombre_lugar ?? 'Origen no definido' }}
                        @endif
                    </p>
                </div>

                <div class="flex items-center mb-4">
                    <div class="w-2 h-2 rounded-full bg-uniride-verde mr-2"></div>
                    <p class="text-sm font-bold text-gray-700 truncate">
                        {{ $ruta->destino_final ?? ($ruta->destino->nombre_lugar ?? 'Destino no definido') }}
                    </p>
                </div>
                @if ($ruta->vehiculo)
                    <p class="text-xs font-bold text-uniride-azul_claro mb-3">
                        {{ $ruta->vehiculo->tipo_vehiculo == 'moto' ? '🛵' : '🚗' }} Vehículo: {{ $ruta->vehiculo->placa }}
                        • Cupos libres: {{ $ruta->cuposDisponibles() }} de {{ $ruta->vehiculo->capacidad_pasajeros ?? 1 }}
                    </p>
                @endif

                <div class="bg-gray-50 p-2 rounded-lg text-xs text-gray-600 mb-2">
                    <span class="font-bold block mb-1">Horarios:</span>
                    @foreach ($ruta->horarios as $h)
                        <span class="inline-block bg-white border border-gray-200 px-2 py-1 rounded mr-1 mb-1">
                            {{ substr($h->dia->nombre ?? 'Día', 0, 3) }} -
                            {{ \Carbon\Carbon::parse($h->hora_salida)->format('h:i A') }}
                        </span>
                    @endforeach
                </div>

                <div class="flex justify-between items-center mt-3 border-t border-gray-100 pt-3">
                    <span class="{{ $ruta->esta_activa ? 'text-green-600' : 'text-gray-400' }} text-xs font-bold">
                        {{ $ruta->esta_activa ? '🟢 En circulación' : '⏸️ Pausada' }}
                    </span>

                    <div class="flex space-x-2">
                        <a href="{{ route('conductor.rutas.editar', $ruta->id_ruta_conductor) }}"
                            class="text-xs font-bold text-gray-600 bg-gray-100 px-3 py-1.5 rounded-lg hover:bg-gray-200 transition">
                            ✏️ Editar
                        </a>
                        <form action="{{ route('conductor.rutas.eliminar', $ruta->id_ruta_conductor) }}" method="POST"
                            class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta ruta?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-xs font-bold text-red-500 bg-red-50 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">
                                🗑️ Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center p-6 bg-white rounded-xl shadow mt-8">
                <p class="text-gray-500 text-sm mb-4">Aún no tienes rutas trazadas.</p>
                <a href="{{ route('conductor.rutas.crear') }}"
                    class="bg-uniride-verde text-white font-bold py-2 px-6 rounded-lg shadow">Trazar Primera Ruta</a>
            </div>
        @endforelse
    </div>
@endsection
