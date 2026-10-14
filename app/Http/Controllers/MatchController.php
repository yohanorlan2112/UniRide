<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SolicitudPasajero;
use App\Models\RutaConductor;

class MatchController extends Controller
{
    // Recibimos el ID de la solicitud desde la URL
    public function explorarConductores($id_solicitud)
    {
        $pasajero = Auth::user()->perfilPasajero;
        
        // Buscamos exactamente el punto que el estudiante seleccionó usando su ID
        $miUbicacion = SolicitudPasajero::where('id_pasajero', $pasajero->id_perfil_pasajero)
                        ->where('id_solicitud_pasajero', $id_solicitud)
                        ->first();

        // Traemos todas las rutas activas de los conductores
        // Con esto cargamos los datos del usuario y el vehículo atados a la ruta
$rutas = RutaConductor::with(['conductor.usuario', 'vehiculo', 'viajes'])->where('esta_activa', true)->get();
        $resultados = [];

        if ($miUbicacion && $miUbicacion->latitud_origen) {
            foreach ($rutas as $ruta) {
                if ($ruta->latitud_origen) {
                    // Calculamos la distancia real
                    $distanciaKm = $this->calcularDistancia(
                        $miUbicacion->latitud_origen, $miUbicacion->longitud_origen,
                        $ruta->latitud_origen, $ruta->longitud_origen
                    );

                    // Calculamos compatibilidad: 0km = 100%. Si está a más de 5km, es 0%.
                    $compatibilidad = max(0, 100 - ($distanciaKm * 20));

                    $resultados[] = [
                        'ruta' => $ruta,
                        'distancia' => round($distanciaKm, 1),
                        'compatibilidad' => round($compatibilidad)
                    ];
                }
            }

            // Ordenamos para que los que pasan más cerca (100%) salgan de primeros
            usort($resultados, function($a, $b) {
                return $b['compatibilidad'] <=> $a['compatibilidad'];
            });
        }

        return view('pasajero.radar', compact('resultados', 'miUbicacion'));
    }

    // Fórmula Matemática de Haversine
    private function calcularDistancia($lat1, $lon1, $lat2, $lon2) {
        $radioTierra = 6371; // Kilómetros
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $radioTierra * $c; // Devuelve la distancia en km
    }
}