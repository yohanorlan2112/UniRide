<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validar lo que envía el dispositivo móvil
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required'
        ]);

        // 2. Buscar al usuario en PostgreSQL
        $usuario = Usuario::where('correo', $request->correo)->first();

        // 3. Verificar si existe y la contraseña coincide
        if (!$usuario || !Hash::check($request->contrasena, $usuario->contrasena)) {
            return response()->json([
                'status' => 'error',
                'mensaje' => 'Credenciales incorrectas'
            ], 401); // 401 = No autorizado
        }

        // 4. Generar el Token de seguridad (Esta función existe gracias a HasApiTokens)
        $token = $usuario->createToken('token_acceso_movil')->plainTextToken;

        // 5. Devolver el token al dispositivo
        return response()->json([
            'status' => 'success',
            'mensaje' => 'Inicio de sesión exitoso',
            'token' => $token,
            'id_usuario' => $usuario->id_usuario
        ], 200);
    }
}