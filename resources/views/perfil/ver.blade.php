@extends('layouts.app')
@section('titulo', 'Mi Perfil - UniRide')

@section('content')
@php
    $modoActual = request('modo', 'pasajero'); // Si no hay nada, asume pasajero
    $rutaRegreso = $modoActual == 'conductor' ? route('conductor.dashboard') : route('pasajero.dashboard');
@endphp

<div class="mb-6 mt-4 flex items-center justify-between">
    <div class="flex items-center">
        <!-- Flecha dinámica -->
        <a href="{{ $rutaRegreso }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
        <h1 class="text-2xl font-bold text-uniride-azul_oscuro">Mi Perfil</h1>
    </div>
    <a href="{{ route('perfil.editar', ['modo' => $modoActual]) }}" class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-bold hover:bg-gray-200">Editar</a>
</div>

<div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-uniride-verde">
    
    <div class="flex flex-col items-center mb-6">
        <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-3xl text-gray-400 font-bold mb-3 shadow-inner">
            {{ substr($usuario->primer_nombre, 0, 1) }}
        </div>
        
        <!-- Roles activos -->
        <div class="flex space-x-2 mb-1">
            @if($usuario->perfilConductor)
                <span class="bg-uniride-azul_claro text-white text-xs px-3 py-1 rounded-full font-bold shadow-sm">🚗 Conductor</span>
            @endif
            @if($usuario->perfilPasajero)
                <span class="bg-uniride-verde text-white text-xs px-3 py-1 rounded-full font-bold shadow-sm">🎒 Pasajero</span>
            @endif
        </div>
    </div>

    <!-- Lista de datos (Solo lectura) -->
    <div class="space-y-5 bg-gray-50 p-4 rounded-lg border border-gray-100">
        
        <div>
            <span class="block text-xs font-bold text-gray-400 mb-1 uppercase">Nombre Completo</span>
            <p class="text-gray-800 font-medium">{{ $usuario->primer_nombre }} {{ $usuario->segundo_nombre}} {{ $usuario->primer_apellido }} {{$usuario->segundo_apellido}}</p>
        </div>
        
        <div>
            <span class="block text-xs font-bold text-gray-400 mb-1 uppercase">Teléfono de Contacto</span>
            <p class="text-gray-800 font-medium">{{ $usuario->telefono ?? 'No registrado' }}</p>
        </div>

        <div>
            <span class="block text-xs font-bold text-gray-400 mb-1 uppercase">Correo Institucional</span>
            <p class="text-gray-800 font-medium">{{ $usuario->correo }}</p>
        </div>
        
        <div class="grid grid-cols-2 gap-4 pt-2 border-t border-gray-200 mt-2">
            <div>
                <span class="block text-xs font-bold text-gray-400 mb-1 uppercase">Documento</span>
                <p class="text-gray-800 font-medium">{{ $usuario->documento_identidad }}</p>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 mb-1 uppercase">Código U</span>
                <p class="text-gray-800 font-medium">{{ $usuario->codigo_estudiantil }}</p>
            </div>
        </div>

    </div>
</div>
@endsection