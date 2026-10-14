<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EsPasajero
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $usuario = Auth::user();
        
        // Verifica si existe el registro en perfiles_pasajeros
        if (!$usuario->perfilPasajero) {
            abort(403, 'Acceso denegado. Esta zona es exclusiva para pasajeros.');
        }

        return $next($request);
    }
}