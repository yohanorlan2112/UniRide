@extends('layouts.app')
@section('titulo', 'Panel Pasajero - UniRide')

@section('content')
<div class="mb-8 mt-4 text-center">
    <div class="w-20 h-20 mx-auto rounded-full bg-uniride-verde flex items-center justify-center text-4xl text-white font-bold mb-3 shadow-lg">
        {{ substr(Auth::user()->primer_nombre, 0, 1) }}
    </div>
    <h1 class="text-2xl font-bold text-uniride-azul_oscuro">Hola, {{ Auth::user()->primer_nombre }} 🎒</h1>
    <p class="text-sm text-gray-500">¿Qué necesitas hacer hoy?</p>
</div>

<div class="space-y-4">
    <!-- Opción 1: Puntos de Recogida -->
    <a href="{{ route('pasajero.puntos.index') }}" class="block bg-white p-6 rounded-xl shadow-md border-l-4 border-uniride-verde hover:bg-gray-50 transition">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-2xl mr-4">📍</div>
            <div>
                <h2 class="text-lg font-bold text-uniride-azul_oscuro">Mis Puntos de Recogida</h2>
                <p class="text-xs text-gray-500">Añade, edita o elimina tus paradas y busca quién te lleve desde ahí.</p>
            </div>
        </div>
    </a>

    <!-- Opción 2: Mis Viajes -->
    <a href="{{ route('pasajero.viajes.index') }}" class="block bg-white p-6 rounded-xl shadow-md border-l-4 border-uniride-azul_claro hover:bg-gray-50 transition">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-2xl mr-4">🚙</div>
            <div>
                <h2 class="text-lg font-bold text-uniride-azul_oscuro">Mis Viajes</h2>
                <p class="text-xs text-gray-500">Revisa el estado de tus viajes (Pendiente, Aceptado, Cancelado).</p>
            </div>
        </div>
    </a>
</div>
@endsection