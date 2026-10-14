@extends('layouts.app')
@section('titulo', 'Mis Vehículos - UniRide')
@section('content')
<div class="mb-4 mt-2 flex items-center justify-between">
    <div class="flex items-center">
        <a href="{{ route('conductor.dashboard') }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
        <h1 class="text-xl font-bold text-uniride-azul_oscuro">Mi Garaje</h1>
    </div>
    <a href="{{ route('conductor.vehiculos.crear') }}" class="text-xs bg-uniride-azul_claro text-white px-3 py-1 rounded-full font-bold">+ Nuevo</a>
</div>

<div class="space-y-4">
    @forelse($vehiculos as $v)
        <div class="bg-white p-4 rounded-xl shadow border-l-4 {{ $v->documentos_al_dia ? 'border-uniride-verde' : 'border-red-400' }}">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h2 class="font-bold text-uniride-azul_oscuro uppercase text-lg">{{ $v->placa }}</h2>
                    <p class="text-sm text-gray-600">{{ $v->marca_modelo }} ({{ ucfirst($v->tipo_vehiculo) }})</p>
                    <p class="text-xs font-bold text-uniride-azul_claro mt-1">👥 Capacidad: {{ $v->capacidad_pasajeros ?? 1 }} pasajero(s)</p>
                </div>
                <span class="text-2xl">{{ $v->tipo_vehiculo == 'moto' ? '🛵' : '🚗' }}</span>
            </div>
            
            <div class="mb-3">
                @if($v->documentos_al_dia)
                    <span class="inline-block bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-bold">Aprobado y Activo</span>
                @else
                    <span class="inline-block bg-red-100 text-red-700 text-xs px-2 py-1 rounded font-bold">Faltan Documentos</span>
                @endif
            </div>

            <!-- Botones de Editar y Eliminar -->
            <div class="flex justify-end space-x-2 border-t border-gray-100 pt-3 mt-2">
                <a href="{{ route('conductor.vehiculos.editar', $v->id_vehiculo) }}" class="text-xs font-bold text-gray-600 bg-gray-100 px-3 py-1.5 rounded-lg hover:bg-gray-200 transition">
                    ✏️ Editar
                </a>
                <form action="{{ route('conductor.vehiculos.eliminar', $v->id_vehiculo) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar este vehículo?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs font-bold text-red-500 bg-red-50 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">
                        🗑️ Eliminar
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="text-center bg-white p-6 rounded-xl shadow mt-8">
            <p class="text-gray-500 text-sm mb-4">No tienes vehículos registrados.</p>
            <a href="{{ route('conductor.vehiculos.crear') }}" class="bg-uniride-verde text-white font-bold py-2 px-6 rounded-lg shadow">Registrar Uno</a>
        </div>
    @endforelse
</div>
@endsection