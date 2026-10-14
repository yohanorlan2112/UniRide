<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehiculo;

class VehiculoController extends Controller
{
    public function index()
    {
        $conductor = Auth::user()->perfilConductor;
        $vehiculos = $conductor->vehiculos;
        
        return view('conductor.mis_vehiculos', compact('vehiculos'));
    }

    public function create()
    {
        return view('conductor.crear_vehiculo');
    }

    public function store(Request $request)
    {
        $maxCupos = $request->tipo_vehiculo == 'moto' ? 1 : 3;

        $request->validate([
            'placa' => 'required|string|max:10|unique:vehiculos,placa',
            'marca_modelo' => 'required|string|max:50',
            'tipo_vehiculo' => 'required|in:carro,moto',
            'capacidad_pasajeros' => "required|integer|min:1|max:$maxCupos",
        ]);

        $conductor = Auth::user()->perfilConductor;

        Vehiculo::create([
            'id_conductor' => $conductor->id_perfil_conductor,
            'placa' => strtoupper($request->placa),
            'marca_modelo' => $request->marca_modelo,
            'tipo_vehiculo' => $request->tipo_vehiculo,
            'capacidad_pasajeros' => $request->tipo_vehiculo == 'moto' ? 1 : $request->capacidad_pasajeros,
            'documentos_al_dia' => false
        ]);

        return redirect()->route('conductor.vehiculos.index')->with('success', 'Vehículo registrado con éxito.');
    }

    public function edit($id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        return view('conductor.editar_vehiculo', compact('vehiculo'));
    }

    public function update(Request $request, $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $maxCupos = $request->tipo_vehiculo == 'moto' ? 1 : 3;

        $request->validate([
            'placa' => 'required|string|max:10',
            'marca_modelo' => 'required|string|max:50',
            'tipo_vehiculo' => 'required|in:carro,moto',
            'capacidad_pasajeros' => "required|integer|min:1|max:$maxCupos",
        ]);

        $vehiculo->update([
            'placa' => strtoupper($request->placa),
            'marca_modelo' => $request->marca_modelo,
            'tipo_vehiculo' => $request->tipo_vehiculo,
            'capacidad_pasajeros' => $request->tipo_vehiculo == 'moto' ? 1 : $request->capacidad_pasajeros,
        ]);

        return redirect()->route('conductor.vehiculos.index')->with('success', 'Vehículo actualizado correctamente.');
    }

    public function destroy($id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->delete();

        return redirect()->route('conductor.vehiculos.index')->with('success', 'Vehículo eliminado de tu garaje.');
    }
}