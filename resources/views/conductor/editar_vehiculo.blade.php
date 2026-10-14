@extends('layouts.app')
@section('titulo', 'Editar Vehículo - UniRide')
@section('content')
<div class="mb-4 mt-2 flex items-center">
    <a href="{{ route('conductor.vehiculos.index') }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
    <h1 class="text-xl font-bold text-uniride-azul_oscuro">Editar Vehículo</h1>
</div>

<div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-uniride-azul_claro">
    <form method="POST" action="{{ route('conductor.vehiculos.actualizar', $vehiculo->id_vehiculo) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Tipo de Vehículo</label>
            <select name="tipo_vehiculo" id="tipo_vehiculo" onchange="ajustarCapacidad()" required class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde font-medium text-gray-700">
                <option value="carro" {{ $vehiculo->tipo_vehiculo == 'carro' ? 'selected' : '' }}>🚗 Carro (Máx. 3 pasajeros)</option>
                <option value="moto" {{ $vehiculo->tipo_vehiculo == 'moto' ? 'selected' : '' }}>🛵 Moto (Máx. 1 pasajero)</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Capacidad de Pasajeros (Cupos)</label>
            <select name="capacidad_pasajeros" id="capacidad_pasajeros" required class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde font-bold text-uniride-azul_oscuro">
                <option value="1" {{ ($vehiculo->capacidad_pasajeros ?? 1) == 1 ? 'selected' : '' }}>1 Pasajero</option>
                <option value="2" class="opcion-carro" {{ ($vehiculo->capacidad_pasajeros ?? 1) == 2 ? 'selected' : '' }}>2 Pasajeros</option>
                <option value="3" class="opcion-carro" {{ ($vehiculo->capacidad_pasajeros ?? 1) == 3 ? 'selected' : '' }}>3 Pasajeros</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Placa</label>
            <input type="text" name="placa" value="{{ $vehiculo->placa }}" required class="w-full border-b-2 border-gray-200 py-2 uppercase font-bold text-uniride-azul_oscuro focus:outline-none focus:border-uniride-verde">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Marca y Línea / Modelo</label>
            <input type="text" name="marca_modelo" value="{{ $vehiculo->marca_modelo }}" required class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde">
        </div>

        <button type="submit" class="w-full bg-uniride-azul_oscuro text-white font-titulo font-bold py-3 rounded-lg shadow-md hover:opacity-90 mt-6">
            Guardar Cambios
        </button>
    </form>
</div>

<script>
    function ajustarCapacidad() {
        const tipo = document.getElementById('tipo_vehiculo').value;
        const selectCapacidad = document.getElementById('capacidad_pasajeros');
        const opcionesCarro = document.querySelectorAll('.opcion-carro');

        if (tipo === 'moto') {
            selectCapacidad.value = '1';
            opcionesCarro.forEach(op => op.style.display = 'none');
        } else {
            opcionesCarro.forEach(op => op.style.display = 'block');
        }
    }
    document.addEventListener("DOMContentLoaded", ajustarCapacidad);
</script>
@endsection