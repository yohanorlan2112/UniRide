@extends('layouts.app')
@section('titulo', 'Radar - UniRide')
@section('content')
    <div class="mb-4 mt-2 flex items-center">
        <a href="{{ route('pasajero.dashboard') }}" class="text-uniride-azul_claro font-bold mr-3 text-xl">←</a>
        <h1 class="text-xl font-bold text-uniride-azul_oscuro">Radar de Conductores</h1>
    </div>

    @if (!$miUbicacion)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 rounded shadow-sm">
            <p class="text-sm text-yellow-700">Para ver la compatibilidad, primero debes <a
                    href="{{ route('pasajero.solicitud.crear') }}" class="font-bold underline">definir tu punto de
                    recogida</a> en el mapa.</p>
        </div>
    @else
        <p class="text-xs text-gray-500 mb-4 bg-white p-3 rounded-lg shadow-sm border border-gray-100">
            Buscando conductores cerca de tu punto de recogida marcado.
        </p>

        <div class="space-y-4">
            @forelse($resultados as $item)
            @php
                $ruta = $item['ruta'];
                $conductor = $ruta->conductor;
                $usuario = $conductor->usuario ?? null;
                // Toma el vehículo asignado a la ruta (o el primero del conductor como respaldo)
                $vehiculo = $ruta->vehiculo ?? $conductor->vehiculos->first();
                $cuposLibres = $ruta->cuposDisponibles();
            @endphp

            <div class="bg-white p-4 rounded-xl shadow border-l-4 {{ $item['compatibilidad'] > 75 ? 'border-uniride-verde' : 'border-yellow-400' }}">
                
                <div class="flex justify-between items-center mb-3">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-xl text-gray-500 font-bold mr-3 shadow-inner">
                            {{ $usuario ? substr($usuario->primer_nombre, 0, 1) : 'C' }}
                        </div>
                        
                        <div>
                            <h4 class="font-bold text-uniride-azul_oscuro text-sm">
                                {{ $usuario ? $usuario->primer_nombre . ' ' . $usuario->primer_apellido : 'Conductor Desconocido' }}
                            </h4>
                            <span class="inline-block bg-yellow-50 text-yellow-700 border border-yellow-200 text-[10px] px-2 py-0.5 rounded font-bold mb-1">
                                {{ $usuario ? $usuario->promedioCalificacion('conductor') : 'Nuevo 🌟' }}
                            </span>
                            
                            @if($vehiculo)
                                <p class="text-xs text-gray-500 font-medium">
                                    {{ $vehiculo->tipo_vehiculo == 'moto' ? '🛵 Moto' : '🚗 Carro' }} • <span class="uppercase font-bold text-gray-700">{{ $vehiculo->placa }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-center ml-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white shadow-inner text-xs
                            {{ $item['compatibilidad'] > 75 ? 'bg-uniride-verde' : ($item['compatibilidad'] > 40 ? 'bg-yellow-400' : 'bg-red-400') }}">
                            {{ $item['compatibilidad'] }}%
                        </div>
                        <span class="text-[9px] text-gray-400 mt-1">{{ $item['distancia'] }} km</span>
                    </div>
                </div>

                <div class="bg-gray-50 p-2 rounded mb-3 text-xs text-gray-600 flex justify-between items-center">
                    <span>🛣️ <strong>Destino:</strong> {{ $ruta->destino_final ?? 'Universidad' }}</span>
                    <span class="font-bold {{ $cuposLibres > 0 ? 'text-uniride-verde' : 'text-red-500' }}">
                        👥 {{ $cuposLibres }} cupo(s) libre(s)
                    </span>
                </div>

                @if($cuposLibres > 0)
                    <form action="{{ route('pasajero.viajes.solicitar') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_solicitud_pasajero" value="{{ $miUbicacion->id_solicitud_pasajero }}">
                        <input type="hidden" name="id_conductor" value="{{ $conductor->id_perfil_conductor }}">
                        <input type="hidden" name="id_ruta_conductor" value="{{ $ruta->id_ruta_conductor }}">
                        
                        <button type="submit" class="w-full text-xs font-bold text-white bg-uniride-azul_claro py-3 rounded-lg hover:opacity-90 shadow-sm transition-transform active:scale-95">
                            Solicitar Viaje
                        </button>
                    </form>
                @else
                    <div class="w-full text-center text-xs font-bold text-gray-400 bg-gray-100 py-2.5 rounded-lg">
                        🚫 Vehículo con cupo lleno
                    </div>
                @endif

            </div>
        @empty
            <div class="text-center p-6 bg-white rounded-xl shadow">
                <p class="text-gray-500 text-sm">No hay conductores circulando cerca en este momento.</p>
            </div>
        @endforelse
        </div>
    @endif
@endsection
