HTML
@extends('layouts.app')
@section('titulo', 'Mi Perfil - UniRide')

@section('content')
@php
    $modoActual = request('modo', 'pasajero'); // Si no hay nada, asume pasajero
    $rutaRegreso = $modoActual == 'conductor' ? route('conductor.dashboard') : route('pasajero.dashboard');
@endphp

<div class="mb-6 mt-4 flex items-center">
    <!-- Flecha dinámica -->
    <a href="{{ $rutaRegreso }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
    <h1 class="text-2xl font-bold text-uniride-azul_oscuro">Datos Personales</h1>
</div>

<div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-uniride-verde">
    
    <!-- (Mantén aquí el bloque del círculo y las etiquetas visuales de los roles que ya tenías) -->

    <form action="{{ route('perfil.actualizar') }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        
        <!-- Este campo invisible le dice al controlador a qué dashboard devolvernos -->
        <input type="hidden" name="modo" value="{{ $modoActual }}">


        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Nombres</label>
            <input type="text" name="primer_nombre" value="{{ $usuario->primer_nombre }}" required class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Apellidos</label>
            <input type="text" name="primer_apellido" value="{{ $usuario->primer_apellido }}" required class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Teléfono de Contacto</label>
            <input type="text" name="telefono" value="{{ $usuario->telefono }}" placeholder="Ej: 3001234567" class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde">
        </div>

        <!-- Campos de solo lectura (seguridad) -->
        <div>
            <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Correo Institucional</label>
            <input type="email" value="{{ $usuario->correo }}" readonly class="w-full border-b-2 border-gray-100 py-2 bg-gray-50 text-gray-400 cursor-not-allowed">
        </div>
        
        <div>
            <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Documento / Código</label>
            <input type="text" value="{{ $usuario->documento_identidad }}" readonly class="w-full border-b-2 border-gray-100 py-2 bg-gray-50 text-gray-400 cursor-not-allowed">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Código U</label>
            <input type="text" value="{{ $usuario->codigo_estudiantil }}" readonly class="w-full border-b-2 border-gray-100 py-2 bg-gray-50 text-gray-400 cursor-not-allowed font-medium">
        </div>
        <div class="flex space-x-2 mb-3">
            @if($usuario->perfilConductor)
                <span class="bg-uniride-azul_claro text-white text-xs px-3 py-1 rounded-full font-bold shadow-sm">🚗 Conductor</span>
            @endif
            @if($usuario->perfilPasajero)
                <span class="bg-uniride-verde text-white text-xs px-3 py-1 rounded-full font-bold shadow-sm">🎒 Pasajero</span>
            @endif
        </div>

        <button type="submit" class="w-full bg-uniride-azul_oscuro text-white font-titulo font-bold py-3 rounded-lg shadow-md hover:bg-opacity-90 mt-6">
            Guardar Cambios
        </button>
    </form>
</div>
@endsection