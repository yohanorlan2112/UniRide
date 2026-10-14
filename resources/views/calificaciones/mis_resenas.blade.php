@extends('layouts.app')
@section('titulo', 'Mis Reseñas - UniRide')
@section('content')
@php
    $modoActual = request('modo', 'pasajero');
    $rutaRegreso = $modoActual == 'conductor' ? route('conductor.dashboard') : route('pasajero.dashboard');
@endphp

<div class="mb-4 mt-2 flex items-center">
    <a href="{{ $rutaRegreso }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
    <h1 class="text-xl font-bold text-uniride-azul_oscuro">Mis Reseñas Recibidas</h1>
</div>

<!-- Resumen de Promedios -->
<div class="grid grid-cols-2 gap-3 mb-6">
    <div class="bg-white p-4 rounded-xl shadow text-center border-t-4 border-uniride-azul_claro">
        <span class="text-xs text-gray-500 font-bold uppercase block mb-1">Como Conductor 🚗</span>
        <span class="text-xl font-bold text-uniride-azul_oscuro">{{ $usuario->promedioCalificacion('conductor') }}</span>
    </div>
    <div class="bg-white p-4 rounded-xl shadow text-center border-t-4 border-uniride-verde">
        <span class="text-xs text-gray-500 font-bold uppercase block mb-1">Como Pasajero 🎒</span>
        <span class="text-xl font-bold text-uniride-azul_oscuro">{{ $usuario->promedioCalificacion('pasajero') }}</span>
    </div>
</div>

<!-- Lista de Comentarios -->
<div class="space-y-4">
    @forelse($resenas as $resena)
        <div class="bg-white p-4 rounded-xl shadow border-l-4 {{ $resena->rol_evaluado == 'conductor' ? 'border-uniride-azul_claro' : 'border-uniride-verde' }}">
            <div class="flex justify-between items-center mb-2">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-600 text-xs mr-2">
                        {{ substr($resena->evaluador->primer_nombre ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800">
                            {{ $resena->evaluador->primer_nombre ?? 'Usuario' }} {{ $resena->evaluador->primer_apellido ?? '' }}
                        </p>
                        <span class="text-[10px] text-gray-400">Te calificó como {{ ucfirst($resena->rol_evaluado) }}</span>
                    </div>
                </div>
                <div class="text-yellow-400 text-sm font-bold">
                    @for($i = 1; $i <= 5; $i++)
                        <span>{{ $i <= $resena->puntuacion ? '★' : '☆' }}</span>
                    @endfor
                </div>
            </div>
            
            @if($resena->comentario)
                <p class="text-xs text-gray-600 bg-gray-50 p-2 rounded italic mt-2">"{{ $resena->comentario }}"</p>
            @else
                <p class="text-[11px] text-gray-400 mt-1">Sin comentario adicional.</p>
            @endif
        </div>
    @empty
        <div class="text-center p-6 bg-white rounded-xl shadow">
            <p class="text-gray-500 text-sm">Aún no has recibido calificaciones en tus viajes.</p>
        </div>
    @endforelse
</div>
@endsection