@extends('layouts.app')
@section('titulo', 'Solicitudes de Pasajeros - UniRide')
@section('content')
    <div class="mb-4 mt-2 flex items-center">
        <a href="{{ route('conductor.dashboard') }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
        <h1 class="text-xl font-bold text-uniride-azul_oscuro">Solicitudes Recibidas</h1>
    </div>

    <div class="space-y-4 mt-6">
        @forelse($viajes as $viaje)
            @php
                $usuarioPasajero = $viaje->solicitud->pasajeroPerfil->usuario ?? null;
                $borde = 'border-gray-300';

                if ($viaje->estado_viaje == 'Pendiente') {
                    $borde = 'border-yellow-400';
                } elseif ($viaje->estado_viaje == 'Aceptado') {
                    $borde = 'border-uniride-azul_claro';
                } elseif ($viaje->estado_viaje == 'Realizado') {
                    $borde = 'border-uniride-verde';
                }
            @endphp

            <div
                class="bg-white p-4 rounded-xl shadow border-t-4 {{ $borde }} relative {{ in_array($viaje->estado_viaje, ['Rechazado', 'Cancelado']) ? 'opacity-50' : '' }}">

                <div
                    class="absolute top-4 right-4 text-[10px] font-bold px-2 py-1 rounded uppercase bg-gray-100 text-gray-600">
                    {{ $viaje->estado_viaje }}
                </div>

                <!-- Datos del Pasajero + Su Calificación Promedio -->
                <div class="flex items-center mb-4 mt-2">
                    <div
                        class="w-12 h-12 rounded-full bg-uniride-verde flex items-center justify-center text-white text-xl font-bold mr-3 shadow-inner">
                        {{ $usuarioPasajero ? substr($usuarioPasajero->primer_nombre, 0, 1) : 'P' }}
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">
                            {{ $usuarioPasajero ? $usuarioPasajero->primer_nombre . ' ' . $usuarioPasajero->primer_apellido : 'Pasajero Desconocido' }}
                        </h4>

                        <!-- Promedio de estrellas del Pasajero -->
                        <span
                            class="inline-block bg-yellow-50 text-yellow-700 border border-yellow-200 text-[10px] px-2 py-0.5 rounded font-bold mt-0.5">
                            Reputación:
                            {{ $usuarioPasajero ? $usuarioPasajero->promedioCalificacion('pasajero') : 'Nuevo 🌟' }}
                        </span>

                        <p class="text-xs text-gray-500 font-medium mt-1">Aporte: <span
                                class="text-uniride-verde font-bold">${{ number_format($viaje->solicitud->precio_ofrecido ?? 0, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 p-3 rounded-lg text-xs text-gray-600 mb-4">
                    <p class="mb-1">📍 <strong>Recoger en:</strong>
                        {{ $viaje->solicitud->latitud_origen ? 'Ubicación en mapa' : 'Punto guardado' }}</p>
                    <p>🏁 <strong>Destino:</strong> {{ $viaje->solicitud->destino_final ?? 'Universidad' }}</p>
                    @if ($viaje->ruta && $viaje->ruta->vehiculo)
                        <p class="mt-1 text-uniride-azul_oscuro">
                            🚘 <strong>Vehículo:</strong> {{ strtoupper($viaje->ruta->vehiculo->placa) }}
                            (Cupos libres: {{ $viaje->ruta->cuposDisponibles() }} de
                            {{ $viaje->ruta->vehiculo->capacidad_pasajeros ?? 1 }})
                        </p>
                    @endif
                </div>

                <!-- Botones de Acción -->
                <div class="flex space-x-2 mt-2">
                    @if ($viaje->estado_viaje == 'Pendiente')
                        <form action="{{ route('conductor.viajes.aceptar', $viaje->id_viaje) }}" method="POST"
                            class="flex-1">
                            @csrf <button
                                class="w-full bg-uniride-azul_claro text-white font-bold py-2 rounded-lg hover:opacity-90 shadow-sm text-sm">Aceptar</button>
                        </form>
                        <form action="{{ route('conductor.viajes.rechazar', $viaje->id_viaje) }}" method="POST"
                            class="flex-1">
                            @csrf <button
                                class="w-full bg-red-50 text-red-500 border border-red-200 font-bold py-2 rounded-lg hover:bg-red-100 shadow-sm text-sm">Rechazar</button>
                        </form>
                    @elseif($viaje->estado_viaje == 'Aceptado')
                        <form action="{{ route('conductor.viajes.terminar', $viaje->id_viaje) }}" method="POST"
                            class="w-full">
                            @csrf <button
                                class="w-full bg-uniride-verde text-white font-bold py-3 rounded-lg hover:opacity-90 shadow-md text-sm">✅
                                Terminar Viaje</button>
                        </form>
                    @elseif($viaje->estado_viaje == 'Realizado')
                        @php
                            $miCalificacion = $viaje->calificaciones->where('id_evaluador', Auth::id())->first();
                        @endphp
                        <div class="w-full pt-2 border-t border-gray-100">
                            @if ($miCalificacion)
                                <p class="text-center text-xs font-bold text-yellow-600 bg-yellow-50 py-2 rounded-lg">
                                    Calificaste a este pasajero con {{ $miCalificacion->puntuacion }} ⭐
                                </p>
                            @else
                                <a href="{{ route('calificaciones.crear', ['id_viaje' => $viaje->id_viaje, 'rol_evaluado' => 'pasajero']) }}"
                                    class="w-full text-xs font-bold text-white bg-yellow-400 py-3 rounded-lg shadow-md hover:bg-yellow-500 transition flex items-center justify-center">
                                    <span class="text-lg mr-2">⭐</span> Calificar Pasajero
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

            </div>
        @empty
            <div class="text-center p-6 bg-white rounded-xl shadow">
                <p class="text-gray-500 text-sm">No has recibido solicitudes de viaje todavía.</p>
            </div>
        @endforelse
    </div>
@endsection
