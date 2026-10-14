@extends('layouts.app')
@section('titulo', 'Registrar Vehículo - UniRide')

@section('content')
<div class="mb-4 mt-2 flex items-center">
    <a href="{{ route('conductor.vehiculos.index') }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
    <h1 class="text-xl font-bold text-uniride-azul_oscuro">Registrar Nuevo Vehículo</h1>
</div>

<div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-uniride-verde">
    <form method="POST" action="{{ route('conductor.vehiculos.guardar') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Tipo de Vehículo</label>
            <select name="tipo_vehiculo" id="tipo_vehiculo" onchange="ajustarCapacidad()" required class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde font-medium text-gray-700">
                <option value="" disabled selected>Selecciona un tipo...</option>
                <option value="carro">🚗 Carro (Máx. 3 pasajeros)</option>
                <option value="moto">🛵 Moto (Máx. 1 pasajero)</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Capacidad de Pasajeros (Cupos)</label>
            <select name="capacidad_pasajeros" id="capacidad_pasajeros" required class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde font-bold text-uniride-azul_oscuro">
                <option value="1">1 Pasajero</option>
                <option value="2" class="opcion-carro">2 Pasajeros</option>
                <option value="3" class="opcion-carro" selected>3 Pasajeros</option>
            </select>
            <p id="notaCapacidad" class="text-[11px] text-gray-400 mt-1">Selecciona cuántos cupos libres ofreces.</p>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Placa</label>
            <input type="text" name="placa" required placeholder="Ej: ABC123" class="w-full border-b-2 border-gray-200 py-2 uppercase font-bold text-uniride-azul_oscuro focus:outline-none focus:border-uniride-verde">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Marca y Línea / Modelo</label>
            <input type="text" name="marca_modelo" required placeholder="Ej: Chevrolet Spark / Yamaha FZ" class="w-full border-b-2 border-gray-200 py-2 focus:outline-none focus:border-uniride-verde">
        </div>

        <button type="submit" class="w-full bg-uniride-verde text-white font-titulo font-bold py-3 rounded-lg shadow-md hover:opacity-90 mt-6">
            Guardar Vehículo
        </button>
    </form>
</div>

<script>
    function ajustarCapacidad() {
        const tipo = document.getElementById('tipo_vehiculo').value;
        const selectCapacidad = document.getElementById('capacidad_pasajeros');
        const opcionesCarro = document.querySelectorAll('.opcion-carro');
        const nota = document.getElementById('notaCapacidad');

        if (tipo === 'moto') {
            selectCapacidad.value = '1';
            opcionesCarro.forEach(op => op.style.display = 'none');
            nota.textContent = '🛵 En moto solo se permite máximo 1 pasajero.';
        } else {
            opcionesCarro.forEach(op => op.style.display = 'block');
            selectCapacidad.value = '3';
            nota.textContent = '🚗 En carro puedes llevar entre 1 y 3 pasajeros.';
        }
    }
</script>
@endsection