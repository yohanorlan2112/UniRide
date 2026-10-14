@extends('layouts.app')
@section('titulo', 'Calificar Viaje - UniRide')
@section('content')
<div class="bg-white p-6 rounded-xl shadow-md border-t-4 border-yellow-400 mt-4 text-center">
    <h1 class="text-xl font-bold text-uniride-azul_oscuro mb-1">¿Qué tal estuvo el viaje?</h1>
    <p class="text-xs text-gray-500 mb-6">Califica a tu {{ $rol_evaluado }}</p>

    <div class="w-20 h-20 mx-auto rounded-full bg-uniride-azul_oscuro flex items-center justify-center text-3xl text-white font-bold mb-2 shadow-inner">
        {{ substr($usuarioEvaluado->primer_nombre ?? 'U', 0, 1) }}
    </div>
    <h2 class="text-lg font-bold text-gray-800 mb-6">
        {{ $usuarioEvaluado->primer_nombre ?? '' }} {{ $usuarioEvaluado->primer_apellido ?? '' }}
    </h2>

    <form action="{{ route('calificaciones.guardar') }}" method="POST" class="space-y-5 text-left">
        @csrf
        <input type="hidden" name="id_viaje" value="{{ $viaje->id_viaje }}">
        <input type="hidden" name="id_evaluado" value="{{ $usuarioEvaluado->id_usuario }}">
        <input type="hidden" name="rol_evaluado" value="{{ $rol_evaluado }}">

        <!-- Selector de Estrellas -->
        <div class="text-center">
            <label class="block text-xs font-bold text-gray-500 uppercase mb-3">Selecciona las estrellas</label>
            <div class="flex justify-center space-x-2" id="contenedorEstrellas">
                @for($i = 1; $i <= 5; $i++)
                    <label class="cursor-pointer">
                        <input type="radio" name="puntuacion" value="{{ $i }}" class="sr-only" required onclick="pintarEstrellas({{ $i }})">
                        <span class="estrella text-4xl text-gray-300 transition transform hover:scale-110 inline-block">★</span>
                    </label>
                @endfor
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Comentario o reseña (Opcional)</label>
            <textarea name="comentario" rows="3" placeholder="Ej: Muy puntual y amable..." class="w-full border-2 border-gray-200 rounded-lg p-3 text-sm focus:outline-none focus:border-uniride-verde"></textarea>
        </div>

        <button type="submit" class="w-full bg-uniride-verde text-white font-titulo font-bold py-3 rounded-lg shadow-md hover:opacity-90">
            Enviar Calificación
        </button>
    </form>
</div>

<script>
    function pintarEstrellas(valor) {
        const estrellas = document.querySelectorAll('.estrella');
        estrellas.forEach((estrella, index) => {
            if (index < valor) {
                estrella.classList.remove('text-gray-300');
                estrella.classList.add('text-yellow-400');
            } else {
                estrella.classList.remove('text-yellow-400');
                estrella.classList.add('text-gray-300');
            }
        });
    }
</script>
@endsection