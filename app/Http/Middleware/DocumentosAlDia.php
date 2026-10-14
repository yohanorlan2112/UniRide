<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DocumentosAlDia
{
    public function handle(Request $request, Closure $next): Response
    {
        $conductor = Auth::user()->perfilConductor;

        // Buscamos si tiene al menos un vehículo con la bandera documentos_al_dia en true
        $tieneVehiculoHabilitado = $conductor->vehiculos()->where('documentos_al_dia', true)->exists();

        if (!$tieneVehiculoHabilitado) {
            return redirect()->route('conductor.dashboard')
                ->with('error', 'No puedes publicar rutas. Debes registrar un vehículo y esperar la validación de tus documentos.');
        }

        return $next($request);
    }
}