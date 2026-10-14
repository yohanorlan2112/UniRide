<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SolicitudPasajero;
use App\Models\HorarioSolicitud;
use App\Models\Ubicacion;
use App\Models\DiaSemana;
use App\Models\Viaje;
use App\Models\Calificacion;

class SolicitudController extends Controller
{
    // 1. Listar puntos de recogida del pasajero
    public function index()
    {
        $pasajero = Auth::user()->perfilPasajero;
        
        $solicitudes = SolicitudPasajero::with(['viajes'])
            ->where('id_pasajero', $pasajero->id_perfil_pasajero)
            ->orderBy('id_solicitud_pasajero', 'desc')
            ->get();

        return view('pasajero.mis_puntos', compact('solicitudes'));
    }

    // 2. Mostrar el mapa para registrar un nuevo punto
    public function create()
    {
        $ubicaciones = Ubicacion::all();
        $dias = DiaSemana::all();
        
        return view('pasajero.buscar_viaje', compact('ubicaciones', 'dias'));
    }

    // 3. Guardar el nuevo punto en PostgreSQL
    public function store(Request $request)
    {
        $request->validate([
            'latitud_origen' => 'required',
            'longitud_origen' => 'required',
            'destino_final' => 'required|string',
            'precio_ofrecido' => 'required|numeric|min:1000',
            'id_dia' => 'required|integer',
            'hora_recogida' => 'required'
        ]);

        $pasajero = Auth::user()->perfilPasajero;

        $solicitud = SolicitudPasajero::create([
            'id_pasajero' => $pasajero->id_perfil_pasajero,
            'latitud_origen' => $request->latitud_origen,
            'longitud_origen' => $request->longitud_origen,
            'destino_final' => $request->destino_final,
            'precio_ofrecido' => $request->precio_ofrecido,
            'estado' => 'Buscando'
        ]);

        HorarioSolicitud::create([
            'id_solicitud_pasajero' => $solicitud->id_solicitud_pasajero,
            'id_dia' => $request->id_dia,
            'hora_recogida' => $request->hora_recogida
        ]);

        return redirect()->route('pasajero.puntos.index')->with('success', 'Punto de recogida guardado correctamente.');
    }

    // 4. Mostrar formulario para editar un punto
    public function edit($id)
    {
        $punto = SolicitudPasajero::findOrFail($id);
        return view('pasajero.editar_punto', compact('punto'));
    }

    // 5. Actualizar el punto en PostgreSQL
    public function update(Request $request, $id)
    {
        $punto = SolicitudPasajero::findOrFail($id);
        
        $punto->update([
            'latitud_origen' => $request->latitud_origen,
            'longitud_origen' => $request->longitud_origen,
            'destino_final' => $request->destino_final,
            'precio_ofrecido' => $request->precio_ofrecido
        ]);

        return redirect()->route('pasajero.puntos.index')->with('success', 'Punto actualizado correctamente.');
    }

    // 6. Eliminar un punto de recogida
    public function destroy($id)
    {
        $punto = SolicitudPasajero::findOrFail($id);

        HorarioSolicitud::where('id_solicitud_pasajero', $id)->delete();

        $viajesIds = Viaje::where('id_solicitud_pasajero', $id)->pluck('id_viaje');
        if ($viajesIds->count() > 0) {
            Calificacion::whereIn('id_viaje', $viajesIds)->delete();
            Viaje::where('id_solicitud_pasajero', $id)->delete();
        }

        $punto->delete();

        return redirect()->route('pasajero.puntos.index')->with('success', 'Punto de recogida eliminado correctamente.');
    }
}