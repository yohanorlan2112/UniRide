<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Viaje;
use App\Models\Calificacion;

class CalificacionController extends Controller
{
    // Muestra la pantalla de las estrellas
    public function crear($id_viaje, $rol_evaluado)
    {
        $viaje = Viaje::with(['conductor.usuario', 'solicitud.pasajeroPerfil.usuario'])->findOrFail($id_viaje);

        // Identificamos a quién vamos a calificar
        if ($rol_evaluado == 'conductor') {
            $usuarioEvaluado = $viaje->conductor->usuario;
        } else {
            $usuarioEvaluado = $viaje->solicitud->pasajeroPerfil->usuario;
        }

        return view('calificaciones.crear', compact('viaje', 'usuarioEvaluado', 'rol_evaluado'));
    }

    // Guarda las estrellas y el comentario en pgAdmin
    public function guardar(Request $request)
    {
        $request->validate([
            'id_viaje' => 'required|integer',
            'id_evaluado' => 'required|integer',
            'rol_evaluado' => 'required|in:conductor,pasajero',
            'puntuacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:255'
        ]);

        // Evitar que califique dos veces el mismo viaje
        $existe = Calificacion::where('id_viaje', $request->id_viaje)
                    ->where('id_evaluador', Auth::id())
                    ->exists();

        if (!$existe) {
            Calificacion::create([
                'id_viaje' => $request->id_viaje,
                'id_evaluador' => Auth::id(),
                'id_evaluado' => $request->id_evaluado,
                'rol_evaluado' => $request->rol_evaluado,
                'puntuacion' => $request->puntuacion,
                'comentario' => $request->comentario
            ]);
        }

        // Redirigir de vuelta a la lista de viajes correspondiente
        if ($request->rol_evaluado == 'conductor') {
            return redirect()->route('pasajero.viajes.index')->with('success', '¡Gracias por calificar a tu conductor!');
        } else {
            return redirect()->route('conductor.viajes.index')->with('success', '¡Gracias por calificar a tu pasajero!');
        }
    }

    // Muestra el apartado de todas las reseñas recibidas
    public function misResenas()
    {
        $usuario = Auth::user();
        $resenas = Calificacion::with('evaluador')
                    ->where('id_evaluado', $usuario->id_usuario)
                    ->orderBy('id_calificacion', 'desc')
                    ->get();

        return view('calificaciones.mis_resenas', compact('usuario', 'resenas'));
    }
}