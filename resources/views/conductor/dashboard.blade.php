@extends('layouts.app')

@section('titulo', 'Panel Conductor - UniRide')

@section('content')
    <div class="mb-6 mt-4">
        <h1 class="text-2xl font-bold text-uniride-azul_oscuro">Hola, {{ Auth::user()->primer_nombre }} 🚗</h1>
        <p class="text-sm text-gray-500">¿Hacia dónde vamos hoy?</p>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <a href="{{ route('conductor.vehiculos.index') }}"
            class="bg-white p-4 rounded-xl shadow flex flex-col items-center justify-center text-center border-l-4 border-uniride-verde hover:bg-gray-50">
            <span class="text-2xl mb-2">📄</span>
            <span class="text-sm font-bold text-uniride-gris_oscuro">Mis Vehículos</span>
        </a>

        <a href="{{ route('conductor.rutas.index') }}"
            class="bg-white p-4 rounded-xl shadow flex flex-col items-center justify-center text-center border-l-4 border-uniride-azul_claro hover:bg-gray-50">
            <span class="text-2xl mb-2">🗺️</span>
            <span class="text-sm font-bold text-uniride-gris_oscuro">Mis Rutas</span>
        </a>
        <a href="{{ route('conductor.viajes.index') }}"
            class="bg-white p-4 rounded-xl shadow flex flex-col items-center justify-center text-center border-l-4 border-uniride-verde hover:bg-gray-50">
            <span class="text-2xl mb-2">🗺️</span>
            <span class="text-sm font-bold text-uniride-gris_oscuro">Mis viajes </span>
        </a>
        <!-- Botón destacado de Zonas de Calor -->
        <a href="{{ route('conductor.mapa_calor') }}"
            class="bg-white p-4 rounded-xl shadow flex flex-col items-center justify-center text-center border-l-4 border-uniride-azul_claro hover:bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="text-3xl mr-3">🔥</span>
                    <div>
                        <h3 class="text-sm font-bold text-uniride-azul_oscuro">Mapa de Calor</h3>
                        
                    </div>
                </div>
                <span class="text-red-500 font-bold text-lg">→</span>
            </div>
        </a>
    </div>

    <div class="bg-uniride-azul_oscuro text-white p-6 rounded-xl shadow-md">
        <h2 class="text-lg font-bold mb-2">Generar Ingresos</h2>
        <p class="text-sm font-cuerpo mb-4 opacity-90">Publica tu ruta hacia la universidad y recoge compañeros en el
            camino.</p>
        <!-- Botón arreglado -->
        <a href="{{ route('conductor.rutas.crear') }}"
            class="block text-center w-full bg-uniride-verde text-white font-bold py-2 rounded-lg shadow hover:bg-opacity-90">Publicar
            Nueva Ruta</a>
    </div>
@endsection
