<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RastreoController;
use App\Http\Controllers\Api\ZonaCalorController;

// Rutas públicas de la API
Route::get('/zonas-calor/datos', [ZonaCalorController::class, 'obtenerZonasIA']);

// Rutas protegidas (El conductor debe tener un token o sesión activa para enviar su GPS)
Route::middleware('auth:sanctum')->group(function () {
    
    // El celular del conductor enviará su latitud y longitud aquí cada 5 segundos
    Route::post('/rastreo/actualizar', [RastreoController::class, 'actualizarGPS']);
    
    // El pasajero consultará dónde viene el conductor
    Route::get('/rastreo/viaje/{id_viaje}', [RastreoController::class, 'obtenerUbicacionConductor']);
});
