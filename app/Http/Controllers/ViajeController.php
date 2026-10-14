<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Viaje;

class ViajeController extends Controller
{
    public function index()
    {
        $pasajero = Auth::user()->perfilPasajero;
        
        $viajes = Viaje::with(['solicitud', 'conductor.usuario', 'conductor.vehiculos', 'ruta.vehiculo', 'calificaciones'])
            ->whereHas('solicitud', function($query) use ($pasajero) {
                $query->where('id_pasajero', $pasajero->id_perfil_pasajero);
            })->orderBy('id_viaje', 'desc')->get();

        return view('pasajero.mis_viajes', compact('viajes'));
    }

    public function solicitarViaje(Request $request)
    {
        $request->validate([
            'id_solicitud_pasajero' => 'required|integer',
            'id_conductor' => 'required|integer',
            'id_ruta_conductor' => 'nullable|integer'
        ]);

        Viaje::create([
            'id_solicitud_pasajero' => $request->id_solicitud_pasajero,
            'id_conductor' => $request->id_conductor,
            'id_ruta_conductor' => $request->id_ruta_conductor,
            'estado_viaje' => 'Pendiente'
        ]);

        return redirect()->route('pasajero.viajes.index')->with('success', '¡Viaje solicitado! Esperando respuesta del conductor.');
    }

    public function cancelarViaje($id_viaje)
    {
        $viaje = Viaje::findOrFail($id_viaje);
        
        if ($viaje->estado_viaje == 'Pendiente' || $viaje->estado_viaje == 'Aceptado') {
            $viaje->update(['estado_viaje' => 'Cancelado']);
            return back()->with('success', 'El viaje ha sido cancelado exitosamente.');
        }

        return back()->with('error', 'Este viaje ya no se puede cancelar.');
    }

    // --- FUNCIONES DEL CONDUCTOR CON CONTROL DE CUPOS ---

    public function viajesConductor()
    {
        $conductor = Auth::user()->perfilConductor;
        
        $viajes = Viaje::with(['solicitud.pasajeroPerfil.usuario', 'ruta.vehiculo', 'calificaciones'])
            ->where('id_conductor', $conductor->id_perfil_conductor)
            ->orderBy('id_viaje', 'desc')->get();

        return view('conductor.mis_viajes', compact('viajes'));
    }

    public function aceptarViaje($id_viaje)
    {
        $viaje = Viaje::with(['ruta.vehiculo', 'conductor.vehiculos'])->findOrFail($id_viaje);

        // 1. Determinar el vehículo y su capacidad máxima
        $vehiculo = $viaje->ruta->vehiculo ?? $viaje->conductor->vehiculos->first();
        $capacidadMaxima = $vehiculo ? ($vehiculo->capacidad_pasajeros ?? 1) : 1;

        // 2. Contar cuántos pasajeros ya están 'Aceptados' en esta ruta (o con este conductor)
        if ($viaje->id_ruta_conductor) {
            $pasajerosAceptados = Viaje::where('id_ruta_conductor', $viaje->id_ruta_conductor)
                                    ->where('estado_viaje', 'Aceptado')
                                    ->count();
        } else {
            $pasajerosAceptados = Viaje::where('id_conductor', $viaje->id_conductor)
                                    ->where('estado_viaje', 'Aceptado')
                                    ->count();
        }

        // 3. Validar si aún caben más pasajeros en el vehículo
        if ($pasajerosAceptados >= $capacidadMaxima) {
            return back()->with('error', "¡Cupo lleno! Tu vehículo ({$vehiculo->placa}) solo tiene capacidad para {$capacidadMaxima} pasajero(s).");
        }

        $viaje->update(['estado_viaje' => 'Aceptado']);
        $restantes = $capacidadMaxima - ($pasajerosAceptados + 1);

        return back()->with('success', "¡Pasajero aceptado! Te quedan {$restantes} cupo(s) disponible(s) en el vehículo.");
    }

    public function rechazarViaje($id_viaje)
    {
        $viaje = Viaje::findOrFail($id_viaje);
        $viaje->update(['estado_viaje' => 'Rechazado']);
        return back()->with('success', 'Solicitud rechazada.');
    }

    public function terminarViaje($id_viaje)
    {
        $viaje = Viaje::findOrFail($id_viaje);
        $viaje->update(['estado_viaje' => 'Realizado']);
        return back()->with('success', '¡Viaje finalizado! El cupo del vehículo se ha liberado.');
    }
}