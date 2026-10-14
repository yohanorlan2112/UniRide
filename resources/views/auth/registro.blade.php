@extends('layouts.app')

@section('titulo', 'Registro - UniRide')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md mt-4 mb-8">
    <h1 class="text-2xl text-uniride-azul_oscuro font-bold mb-4">Crea tu cuenta</h1>

    <form method="POST" action="{{ route('registro') }}" class="space-y-4">
        @csrf

        <!-- Nombres -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Primer Nombre *</label>
                <input type="text" name="primer_nombre" required class="w-full border-b-2 border-gray-300 rounded-lg p-2 bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Segundo Nombre</label>
                <input type="text" name="segundo_nombre" class="w-full border-b-2 border-gray-300 rounded-lg p-2 bg-gray-50">
            </div>
        </div>

        <!-- Apellidos -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Primer Apellido *</label>
                <input type="text" name="primer_apellido" required class="w-full border-b-2 border-gray-300 rounded-lg p-2 bg-gray-50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Segundo Apellido</label>
                <input type="text" name="segundo_apellido" class="w-full border-b-2 border-gray-300 rounded-lg p-2 bg-gray-50">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Documento de Identidad</label>
            <input type="text" name="documento_identidad" required class="w-full border-gray-300 rounded-lg p-2 bg-gray-50">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Código Estudiantil</label>
            <input type="text" name="codigo_estudiantil" required class="w-full border-gray-300 rounded-lg p-2 bg-gray-50">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Correo Institucional</label>
            <input type="email" name="correo" required class="w-full border-gray-300 rounded-lg p-2 bg-gray-50">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Contraseña</label>
            <input type="password" name="contrasena" required minlength="8" class="w-full border-gray-300 rounded-lg p-2 bg-gray-50">
        </div>

        <h2 class="text-lg text-uniride-azul_oscuro font-bold mt-6 mb-2">¿Cómo te moverás?</h2>
        
        <!-- Selección de Rol estilo Tarjetas -->
        <div class="grid grid-cols-2 gap-3 mb-4">
            <label class="cursor-pointer">
                <input type="radio" name="rol_seleccionado" value="conductor" class="peer sr-only" required>
                <div class="p-4 border-2 rounded-xl text-center transition-all peer-checked:border-uniride-verde peer-checked:bg-green-50 hover:bg-gray-50">
                    <div class="text-2xl mb-1">🚗</div>
                    <div class="font-bold text-sm text-uniride-azul_oscuro">Conductor</div>
                    <div class="text-xs text-gray-500 mt-1">Tengo vehículo</div>
                </div>
            </label>

            <label class="cursor-pointer">
                <input type="radio" name="rol_seleccionado" value="pasajero" class="peer sr-only">
                <div class="p-4 border-2 rounded-xl text-center transition-all peer-checked:border-uniride-verde peer-checked:bg-green-50 hover:bg-gray-50">
                    <div class="text-2xl mb-1">🎒</div>
                    <div class="font-bold text-sm text-uniride-azul_oscuro">Pasajero</div>
                    <div class="text-xs text-gray-500 mt-1">Busco transporte</div>
                </div>
            </label>
        </div>

        <button type="submit" class="w-full bg-uniride-azul_oscuro text-white font-titulo font-bold py-3 rounded-lg shadow-md hover:bg-opacity-90 transition mt-2">
            Comenzar mi viaje
        </button>
    </form>
</div>
@endsection