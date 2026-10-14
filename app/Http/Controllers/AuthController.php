<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use App\Models\RolUsuario;
use App\Models\PerfilConductor;
use App\Models\PerfilPasajero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // --- FUNCIONES PARA MOSTRAR LAS PANTALLAS (VISTAS) ---

    public function mostrarFormularioLogin()
    {
        if (Auth::check()) {
            return redirect()->route('inicio');
        }
        return view('auth.login');
    }

    public function mostrarFormularioRegistro()
    {
        if (Auth::check()) {
            return redirect()->route('inicio');
        }
        return view('auth.registro');
    }

    // --- FUNCIONES PARA PROCESAR LOS FORMULARIOS EN LA BASE DE DATOS ---

    public function registrar(Request $request)
    {
        // 1. Validar datos
       $request->validate([
        'primer_nombre' => 'required|string|max:50',
        'segundo_nombre' => 'nullable|string|max:50', // <-- Opcional
        'primer_apellido' => 'required|string|max:50',
        'segundo_apellido' => 'nullable|string|max:50', // <-- Opcional
        'documento_identidad' => 'required|string|max:20|unique:usuarios,documento_identidad',
        'codigo_estudiantil' => 'required|string|max:20|unique:usuarios,codigo_estudiantil',
        'correo' => 'required|email|unique:usuarios,correo',
        'contrasena' => 'required|min:8',
        'rol_seleccionado' => 'required|in:conductor,pasajero',
    ]);

    // 2. Crear el Usuario
    $usuario = Usuario::create([
        'primer_nombre' => $request->primer_nombre,
        'segundo_nombre' => $request->segundo_nombre,     // <-- Guardar
        'primer_apellido' => $request->primer_apellido,
        'segundo_apellido' => $request->segundo_apellido, // <-- Guardar
        'documento_identidad' => $request->documento_identidad,
        'codigo_estudiantil' => $request->codigo_estudiantil,
        'correo' => $request->correo,
        'contrasena' => Hash::make($request->contrasena),
        'estado_cuenta' => 'activa'
    ]);

        // 3. Buscar el ID del rol
        $rol = Rol::where('nombre_rol', $request->rol_seleccionado)->first();

        // 4. Asignar el rol
        RolUsuario::create([
            'id_usuario' => $usuario->id_usuario,
            'id_rol' => $rol->id_rol,
            'asignado_en' => now()
        ]);

        // 5. Crear el perfil específico
        if ($request->rol_seleccionado == 'conductor') {
            PerfilConductor::create([
                'id_usuario' => $usuario->id_usuario,
                'esta_verificado' => false
            ]);
        } else {
            PerfilPasajero::create([
                'id_usuario' => $usuario->id_usuario,
                'contacto_emergencia_nombre' => 'Por definir',
                'contacto_emergencia_telefono' => 'Por definir'
            ]);
        }

        // 6. Autenticar y redirigir
        Auth::login($usuario);
        
        $rutaDestino = $request->rol_seleccionado == 'conductor' ? 'conductor.dashboard' : 'pasajero.dashboard';
        return redirect()->route($rutaDestino)->with('success', '¡Bienvenido a UniRide!');
    }

    public function iniciarSesion(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required'
        ]);

        if (Auth::attempt(['correo' => $request->correo, 'password' => $request->contrasena])) {
            $request->session()->regenerate();
            
            // Redirigir según el rol del usuario logueado
            $usuario = Auth::user();
            if ($usuario->perfilConductor) {
                return redirect()->route('conductor.dashboard');
            } else {
                return redirect()->route('pasajero.dashboard');
            }
        }

        return back()->withErrors(['correo' => 'Las credenciales no coinciden con nuestros registros.']);
    }
    public function cerrarSesion(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}