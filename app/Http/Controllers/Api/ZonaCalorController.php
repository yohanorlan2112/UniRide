<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SolicitudPasajero;
use Illuminate\Support\Facades\DB;

class ZonaCalorController extends Controller
{
    // Extrae los datos para generar el Heatmap
    public function obtenerZonasIA()
    {
        // Contamos cuántos pasajeros están pidiendo viaje desde cada ubicación hoy
        // Esto usa SQL puro a través de Laravel
        $demandaPorZona = DB::table('solicitudes_pasajeros')
            ->join('ubicaciones', 'solicitudes_pasajeros.id_ubicacion', '=', 'ubicaciones.id_ubicacion')
            ->select('ubicaciones.latitud', 'ubicaciones.longitud', 'ubicaciones.nombre_lugar', DB::raw('count(*) as nivel_demanda'))
            ->whereDate('solicitudes_pasajeros.estado', 'Buscando') // Solo los que buscan activamente
            ->groupBy('ubicaciones.id_ubicacion', 'ubicaciones.latitud', 'ubicaciones.longitud', 'ubicaciones.nombre_lugar')
            ->get();

        // Enviar al algoritmo de IA o al mapa de Google Maps para pintar los colores (rojo, amarillo, verde)
        return response()->json([
            'status' => 'success',
            'total_zonas_calientes' => $demandaPorZona->count(),
            'datos_ia' => $demandaPorZona
        ], 200);
    }
}