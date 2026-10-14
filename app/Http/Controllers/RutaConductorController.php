<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RutaConductor;
use App\Models\HorarioRuta;
use App\Models\Ubicacion;
use App\Models\DiaSemana;
use App\Models\SolicitudPasajero;

class RutaConductorController extends Controller
{
    public function index()
    {
        $conductor = Auth::user()->perfilConductor;
        
        $rutas = RutaConductor::with(['vehiculo', 'origen', 'destino', 'horarios.dia'])
                    ->where('id_conductor', $conductor->id_perfil_conductor)
                    ->get();
                    
        return view('conductor.mis_rutas', compact('rutas'));
    }

    public function create()
    {
        $conductor = Auth::user()->perfilConductor;
        $vehiculos = $conductor->vehiculos;

        // Si no tiene vehículos registrados, lo mandamos a registrar uno primero
        if ($vehiculos->isEmpty()) {
            return redirect()->route('conductor.vehiculos.crear')
                ->with('error', 'Primero debes registrar un vehículo para poder trazar una ruta.');
        }

        $ubicaciones = Ubicacion::all();
        $dias = DiaSemana::all();
        
        return view('conductor.crear_ruta', compact('ubicaciones', 'dias', 'vehiculos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_vehiculo' => 'required|integer',
            'latitud_origen' => 'required',
            'longitud_origen' => 'required',
            'destino_final' => 'required|string',
            'dias' => 'required|array',
            'hora_salida' => 'required'
        ]);

        $conductor = Auth::user()->perfilConductor;

        $ruta = RutaConductor::create([
            'id_conductor' => $conductor->id_perfil_conductor,
            'id_vehiculo' => $request->id_vehiculo,
            'latitud_origen' => $request->latitud_origen,
            'longitud_origen' => $request->longitud_origen,
            'destino_final' => $request->destino_final,
            'esta_activa' => true
        ]);

        foreach ($request->dias as $id_dia) {
            HorarioRuta::create([
                'id_ruta_conductor' => $ruta->id_ruta_conductor,
                'id_dia' => $id_dia,
                'hora_salida' => $request->hora_salida
            ]);
        }

        return redirect()->route('conductor.rutas.index')->with('success', 'Ruta trazada exitosamente.');
    }

    public function edit($id)
    {
        $conductor = Auth::user()->perfilConductor;
        $vehiculos = $conductor->vehiculos;
        $ruta = RutaConductor::with('horarios')->findOrFail($id);
        $dias = DiaSemana::all();
        
        $diasSeleccionados = $ruta->horarios->pluck('id_dia')->toArray();
        $horaActual = $ruta->horarios->first() ? \Carbon\Carbon::parse($ruta->horarios->first()->hora_salida)->format('H:i') : '06:00';

        return view('conductor.editar_ruta', compact('ruta', 'dias', 'diasSeleccionados', 'horaActual', 'vehiculos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_vehiculo' => 'required|integer',
            'latitud_origen' => 'required',
            'longitud_origen' => 'required',
            'destino_final' => 'required|string',
            'dias' => 'required|array',
            'hora_salida' => 'required',
            'esta_activa' => 'required|boolean'
        ]);

        $ruta = RutaConductor::findOrFail($id);

        $ruta->update([
            'id_vehiculo' => $request->id_vehiculo,
            'latitud_origen' => $request->latitud_origen,
            'longitud_origen' => $request->longitud_origen,
            'destino_final' => $request->destino_final,
            'esta_activa' => $request->esta_activa
        ]);

        HorarioRuta::where('id_ruta_conductor', $ruta->id_ruta_conductor)->delete();

        foreach ($request->dias as $id_dia) {
            HorarioRuta::create([
                'id_ruta_conductor' => $ruta->id_ruta_conductor,
                'id_dia' => $id_dia,
                'hora_salida' => $request->hora_salida
            ]);
        }

        return redirect()->route('conductor.rutas.index')->with('success', 'Ruta actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $ruta = RutaConductor::findOrFail($id);
        HorarioRuta::where('id_ruta_conductor', $id)->delete();
        $ruta->delete();

        return redirect()->route('conductor.rutas.index')->with('success', 'Ruta eliminada correctamente.');
    }

    public function mapaCalor()
    {
        $puntosPasajeros = SolicitudPasajero::with(['horarios.dia'])
            ->whereNotNull('latitud_origen')
            ->whereNotNull('longitud_origen')
            ->where('estado', '!=', 'Cancelado')
            ->get();

        return view('conductor.mapa_calor', compact('puntosPasajeros'));
    }
}