<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EsConductor
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificar si hay alguien logueado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Verificar si ese usuario logueado tiene un perfil de conductor en la base de datos
        $usuario = Auth::user();
        
        if (!$usuario->perfilConductor) {
            // Si es un pasajero intentando entrar a zona de conductores, lo botamos:
            abort(403, 'Acceso denegado. Esta zona es solo para conductores de UniRide.');
        }

        // 3. Si todo está bien, lo deja pasar a la vista
        return $next($request);
    }
}