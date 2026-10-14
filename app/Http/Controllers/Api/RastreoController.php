<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RastreoVivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RastreoController extends Controller
{
    // Función que el celular del conductor llama cada X segundos
    public function actualizarGPS(Request $request)
    {
        $request->validate([
            'id_viaje' => 'required|integer',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'velocidad_actual' => 'nullable|numeric'
        ]);

        $usuario = Auth::user();

        // Guardar el punto en el mapa "a palito" usando tu modelo
        $rastreo = RastreoVivo::create([
            'id_viaje' => $request->id_viaje,
            'id_usuario' => $usuario->id_usuario,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'velocidad_actual' => $request->velocidad_actual ?? 0,
            'registrado_en' => now()
        ]);

        // Retornar respuesta exitosa en JSON (No retorna una vista)
        return response()->json([
            'status' => 'success',
            'mensaje' => 'Coordenadas actualizadas',
            'data' => $rastreo
        ], 200);
    }

    // Función para que el mapa del pasajero sepa dónde dibujar el carrito
    public function obtenerUbicacionConductor($id_viaje)
    {
        // Traemos la última coordenada registrada de ese viaje
        $ultimaUbicacion = RastreoVivo::where('id_viaje', $id_viaje)
                                      ->orderBy('registrado_en', 'desc')
                                      ->first();

        if (!$ultimaUbicacion) {
            return response()->json(['status' => 'error', 'mensaje' => 'Sin datos de GPS'], 404);
        }

        return response()->json([
            'status' => 'success',
            'latitud' => $ultimaUbicacion->latitud,
            'longitud' => $ultimaUbicacion->longitud
        ], 200);
    }
}