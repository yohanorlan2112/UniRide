@extends('layouts.app')

@section('titulo', 'Ingresar - UniRide')

@section('content')
<div class="mt-8 bg-white p-6 rounded-xl shadow-md">
    <div class="text-center mb-6">
        <h1 class="text-3xl text-uniride-azul_oscuro font-bold mb-2">Bienvenido</h1>
        <p class="text-sm text-gray-500">Conecta tu ruta, comparte el camino.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        
        <div>
            <label class="block text-sm font-medium mb-1">Correo Institucional</label>
            <input type="email" name="correo" required class="w-full border-gray-300 rounded-lg p-3 bg-gray-50 focus:border-uniride-verde focus:ring-1 focus:ring-uniride-verde outline-none transition" placeholder="tu@universidad.edu.co">
            @error('correo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Contraseña</label>
            <input type="password" name="contrasena" required class="w-full border-gray-300 rounded-lg p-3 bg-gray-50 focus:border-uniride-verde focus:ring-1 focus:ring-uniride-verde outline-none transition" placeholder="••••••••">
        </div>

        <button type="submit" class="w-full bg-uniride-verde text-white font-titulo font-bold py-3 rounded-lg shadow-md hover:bg-opacity-90 transition mt-4">
            Ingresar
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm">¿No tienes cuenta? <a href="{{ route('registro') }}" class="text-uniride-azul_claro font-bold">Regístrate aquí</a></p>
    </div>
</div>
@endsection