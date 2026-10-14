<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PerfilConductor;
use App\Models\PerfilPasajero;
use App\Models\Rol;
use App\Models\RolUsuario;


class PerfilController extends Controller
{
    public function cambiarModo(Request $request)
    {
        $usuario = Auth::user();
        $modoDeseado = $request->input('modo'); // Recibe 'conductor' o 'pasajero'

        if ($modoDeseado == 'conductor') {
            // Si quiere ser conductor, verificamos si ya tiene el perfil
            if (!$usuario->perfilConductor) {
                // Le creamos el perfil vacío
                PerfilConductor::create([
                    'id_usuario' => $usuario->id_usuario,
                    'esta_verificado' => false
                ]);
                
                // Le asignamos el rol en la tabla intermedia
                $rol = Rol::where('nombre_rol', 'conductor')->first();
                if($rol) {
                    RolUsuario::create(['id_usuario' => $usuario->id_usuario, 'id_rol' => $rol->id_rol]);
                }
            }
            return redirect()->route('conductor.dashboard')->with('success', 'Has cambiado al modo Conductor 🚗');
            
        } else {
            // Si quiere ser pasajero
            if (!$usuario->perfilPasajero) {
                PerfilPasajero::create([
                    'id_usuario' => $usuario->id_usuario,
                    'contacto_emergencia_nombre' => 'Por definir',
                    'contacto_emergencia_telefono' => 'Por definir'
                ]);
                
                $rol = Rol::where('nombre_rol', 'pasajero')->first();
                if($rol) {
                    RolUsuario::create(['id_usuario' => $usuario->id_usuario, 'id_rol' => $rol->id_rol]);
                }
            }
            return redirect()->route('pasajero.dashboard')->with('success', 'Has cambiado al modo Pasajero 🎒');
        }
    }
    // Muestra el formulario con los datos actuales
    public function editarPerfil()
    {
        $usuario = Auth::user();
        return view('perfil.editar', compact('usuario'));
    }

    // Guarda los cambios en PostgreSQL
   public function actualizarPerfil(Request $request)
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $request->validate([
            'telefono' => 'nullable|string|max:15',
            'primer_nombre' => 'required|string|max:50',
            'segundo_nombre' => 'nullable|string|max:50',
            'primer_apellido' => 'required|string|max:50',
            'segundo_apellido' => 'nullable|string|max:50',
        ]);

        $usuario->update([
            'primer_nombre' => $request->primer_nombre,
            'segundo_nombre' => $request->segundo_nombre,
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido,
            'telefono' => $request->telefono,
        ]);

        // LÓGICA DE REDIRECCIÓN INTELIGENTE
        $modoDeseado = $request->input('modo');
        
        // 1. Si quiere ir a conductor y TIENE el perfil, lo dejamos.
        if ($modoDeseado == 'conductor' && $usuario->perfilConductor) {
            $rutaDestino = 'conductor.dashboard';
        } 
        // 2. Si quiere ir a pasajero y TIENE el perfil, lo dejamos.
        elseif ($modoDeseado == 'pasajero' && $usuario->perfilPasajero) {
            $rutaDestino = 'pasajero.dashboard';
        } 
        // 3. Plan de respaldo: Lo mandamos al perfil que sí exista.
        elseif ($usuario->perfilConductor) {
            $rutaDestino = 'conductor.dashboard';
        } else {
            $rutaDestino = 'pasajero.dashboard';
        }

        return redirect()->route($rutaDestino)->with('success', 'Tus datos personales han sido actualizados.');
    
    }
    // Añade esta función antes o después de editarPerfil()
    public function verPerfil()
    {
        $usuario = Auth::user();
        return view('perfil.ver', compact('usuario'));
    }
}