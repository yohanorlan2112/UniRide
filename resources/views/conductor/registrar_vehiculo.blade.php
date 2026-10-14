@extends('layouts.app')

@section('titulo', 'Registrar Vehículo - UniRide')

@section('content')
<div class="mb-4 mt-2 flex items-center">
    <a href="{{ route('conductor.dashboard') }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
    <h1 class="text-xl font-bold text-uniride-azul_oscuro">Registrar Vehículo</h1>
</div>

<div class="bg-white p-6 rounded-xl shadow-md mb-8">
    <p class="text-sm text-gray-500 mb-6">Ingresa los datos del vehículo con el que vas a realizar tus rutas.</p>

    <form method="POST" action="{{ route('conductor.vehiculos.guardar') }}" class="space-y-5">
        @csrf

        <!-- Selección visual del tipo de vehículo -->
        <div>
            <label class="block text-xs font-bold text-uniride-azul_oscuro mb-2 uppercase">Tipo de Vehículo</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="cursor-pointer">
                    <input type="radio" name="tipo_vehiculo" value="moto" class="peer sr-only" required>
                    <div class="p-3 border-2 rounded-lg text-center transition-all peer-checked:border-uniride-verde peer-checked:bg-green-50 hover:bg-gray-50">
                        <div class="text-2xl mb-1">🛵</div>
                        <div class="font-bold text-sm text-uniride-gris_oscuro">Motocicleta</div>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="tipo_vehiculo" value="carro" class="peer sr-only">
                    <div class="p-3 border-2 rounded-lg text-center transition-all peer-checked:border-uniride-verde peer-checked:bg-green-50 hover:bg-gray-50">
                        <div class="text-2xl mb-1">🚗</div>
                        <div class="font-bold text-sm text-uniride-gris_oscuro">Automóvil</div>
                    </div>
                </label>
            </div>
            @error('tipo_vehiculo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Placa</label>
                <input type="text" name="placa" required placeholder="ABC-123" class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde uppercase transition">
                @error('placa') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Capacidad</label>
                <input type="number" name="capacidad" required min="1" max="4" placeholder="Ej: 1" class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde transition">
                <span class="text-xs text-gray-400">Cupos libres</span>
                @error('capacidad') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Marca y Modelo</label>
            <input type="text" name="marca_modelo" required placeholder="Ej: Yamaha FZ 2.0" class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde transition">
            @error('marca_modelo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="bg-blue-50 p-3 rounded-lg border border-blue-100 mt-2">
            <p class="text-xs text-uniride-azul_oscuro"><span class="font-bold">Nota:</span> Después de registrar el vehículo, deberás subir la foto del SOAT y Tecnomecánica para habilitarlo.</p>
        </div>

        <button type="submit" class="w-full bg-uniride-azul_oscuro text-white font-titulo font-bold py-3 rounded-lg shadow-md hover:bg-opacity-90 transition mt-4">
            Guardar Vehículo
        </button>
    </form>
</div>
@endsection