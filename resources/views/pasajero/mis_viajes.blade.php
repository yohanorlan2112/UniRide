@extends('layouts.app')
@section('titulo', 'Mis Viajes - UniRide')
@section('content')
<div class="mb-4 mt-2 flex items-center">
    <a href="{{ route('pasajero.dashboard') }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
    <h1 class="text-xl font-bold text-uniride-azul_oscuro">Mis Viajes</h1>
</div>

<div class="space-y-4 mt-6">
    @forelse($viajes as $viaje)
        @php
            // Definir colores dinámicos según el estado del viaje
            $borde = 'border-gray-300'; $bgEstado = 'bg-gray-100'; $txtEstado = 'text-gray-600';
            $opacidad = ''; // Solo se desvanecen los cancelados/rechazados
            
            if($viaje->estado_viaje == 'Aceptado') { 
                $borde = 'border-uniride-verde'; $bgEstado = 'bg-green-100'; $txtEstado = 'text-green-700'; 
            } elseif($viaje->estado_viaje == 'Pendiente') { 
                $borde = 'border-yellow-400'; $bgEstado = 'bg-yellow-100'; $txtEstado = 'text-yellow-700'; 
            } elseif($viaje->estado_viaje == 'Cancelado' || $viaje->estado_viaje == 'Rechazado') { 
                $borde = 'border-red-400'; $bgEstado = 'bg-red-100'; $txtEstado = 'text-red-700'; 
                $opacidad = 'opacity-75';
            } elseif($viaje->estado_viaje == 'Realizado') { 
                $borde = 'border-uniride-azul_claro'; $bgEstado = 'bg-blue-100'; $txtEstado = 'text-blue-700'; 
            }
        @endphp

        <div class="bg-white p-4 rounded-xl shadow border-l-4 {{ $borde }} {{ $opacidad }} relative">
            
            <!-- Etiqueta de Estado Visual -->
            <div class="absolute top-4 right-4 {{ $bgEstado }} {{ $txtEstado }} text-[10px] font-bold px-2 py-1 rounded uppercase">
                {{ $viaje->estado_viaje }}
            </div>
            
            <p class="text-xs text-gray-500 mb-3 mt-1">Hacia: <span class="font-bold">{{ $viaje->solicitud->destino_final ?? 'Universidad' }}</span></p>
            
            <!-- Datos reales del Conductor -->
            <div class="bg-gray-50 p-3 rounded-lg flex items-center mb-3">
                @php
                    $usuarioConductor = $viaje->conductor->usuario ?? null;
                    $vehiculo = $viaje->conductor->vehiculos->first() ?? null;
                @endphp
                
                <div class="w-8 h-8 rounded-full bg-uniride-azul_oscuro flex items-center justify-center text-white font-bold mr-3 shadow-inner">
                    {{ $usuarioConductor ? substr($usuarioConductor->primer_nombre, 0, 1) : 'C' }}
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-700">
                        {{ $usuarioConductor ? $usuarioConductor->primer_nombre . ' ' . $usuarioConductor->primer_apellido : 'Conductor Asignado' }}
                    </p>
                    @if($vehiculo)
                        <p class="text-[10px] text-gray-500 uppercase">{{ $vehiculo->tipo_vehiculo }} • <span class="font-bold text-gray-700">{{ $vehiculo->placa }}</span></p>
                    @else
                        <p class="text-[10px] text-gray-500">Vehículo por definir</p>
                    @endif
                </div>
            </div>
            
            <!-- ACCIÓN 1: Botón de Cancelar (Solo para viajes que aún no empiezan) -->
            @if($viaje->estado_viaje == 'Pendiente' || $viaje->estado_viaje == 'Aceptado')
                <form action="{{ route('pasajero.viajes.cancelar', $viaje->id_viaje) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas cancelar este viaje?');">
                    @csrf
                    <button type="submit" class="w-full text-xs font-bold text-red-500 bg-white border border-red-200 py-2 rounded-lg hover:bg-red-50 shadow-sm transition">Cancelar Viaje</button>
                </form>
            @endif

           <!-- ACCIÓN 2: Botón de Calificar al Conductor -->
            @if($viaje->estado_viaje == 'Realizado')
                @php
                    $miCalificacion = $viaje->calificaciones->where('id_evaluador', Auth::id())->first();
                @endphp
                <div class="mt-2 pt-3 border-t border-gray-100">
                    @if($miCalificacion)
                        <p class="text-center text-xs font-bold text-yellow-600 bg-yellow-50 py-2 rounded-lg">
                            Calificaste a este conductor con {{ $miCalificacion->puntuacion }} ⭐
                        </p>
                    @else
                        <a href="{{ route('calificaciones.crear', ['id_viaje' => $viaje->id_viaje, 'rol_evaluado' => 'conductor']) }}" class="w-full text-xs font-bold text-white bg-yellow-400 py-3 rounded-lg shadow-md hover:bg-yellow-500 transition flex items-center justify-center">
                            <span class="text-lg mr-2">⭐</span> Calificar Conductor
                        </a>
                    @endif
                </div>
            @endif

        </div>
    @empty
        <div class="text-center p-6 bg-white rounded-xl shadow">
            <p class="text-gray-500 text-sm">No tienes viajes registrados en el historial.</p>
        </div>
    @endforelse
</div>
@endsection