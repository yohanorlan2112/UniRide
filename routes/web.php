<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\RutaConductorController;
use App\Http\Controllers\PerfilController;

// Rutas públicas (No requieren login)
// Rutas públicas
// Ruta principal inteligente
Route::get('/', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        $usuario = \Illuminate\Support\Facades\Auth::user();
        return $usuario->perfilConductor 
            ? redirect()->route('conductor.dashboard') 
            : redirect()->route('pasajero.dashboard');
    }
    return redirect()->route('login');
})->name('inicio');

Route::get('/login', [AuthController::class, 'mostrarFormularioLogin'])->name('login');
Route::post('/login', [AuthController::class, 'iniciarSesion']);
Route::get('/registro', [AuthController::class, 'mostrarFormularioRegistro'])->name('registro');
Route::post('/registro', [AuthController::class, 'registrar']);
Route::post('/logout', [AuthController::class, 'cerrarSesion'])->name('logout');

// Rutas protegidas (Requieren estar logueado)
Route::middleware(['auth'])->group(function () {
    
    // ZONA SOLO PARA CONDUCTORES (Usa el middleware 'es.conductor' que creaste)
    Route::middleware(['es.conductor'])->prefix('conductor')->name('conductor.')->group(function () {
        Route::get('/dashboard', function () {
            return view('conductor.dashboard');
        })->name('dashboard');
        // Vehículos
        Route::get('/vehiculos', [VehiculoController::class, 'index'])->name('vehiculos.index');
        Route::get('/vehiculos/nuevo', [VehiculoController::class, 'create'])->name('vehiculos.crear');
        Route::post('/vehiculos', [VehiculoController::class, 'store'])->name('vehiculos.guardar');
        
        // Rutas de viaje
        Route::get('/rutas', [RutaConductorController::class, 'index'])->name('rutas.index');
        Route::get('/rutas/nueva', [RutaConductorController::class, 'create'])->name('rutas.crear');
        Route::post('/rutas', [RutaConductorController::class, 'store'])->name('rutas.guardar');
        // Gestor de Solicitudes de Viaje (Conductor)
        Route::get('/mis-viajes', [\App\Http\Controllers\ViajeController::class, 'viajesConductor'])->name('viajes.index');
        Route::post('/mis-viajes/{id_viaje}/aceptar', [\App\Http\Controllers\ViajeController::class, 'aceptarViaje'])->name('viajes.aceptar');
        Route::post('/mis-viajes/{id_viaje}/rechazar', [\App\Http\Controllers\ViajeController::class, 'rechazarViaje'])->name('viajes.rechazar');
        Route::post('/mis-viajes/{id_viaje}/terminar', [\App\Http\Controllers\ViajeController::class, 'terminarViaje'])->name('viajes.terminar');
        // Mapa de Calor (Demanda de Pasajeros)
        Route::get('/zonas-calor', [\App\Http\Controllers\RutaConductorController::class, 'mapaCalor'])->name('mapa_calor');
        // --- VEHÍCULOS (Ver, Crear, Editar, Eliminar) ---
        Route::get('/vehiculos', [\App\Http\Controllers\VehiculoController::class, 'index'])->name('vehiculos.index');
        Route::get('/vehiculos/nuevo', [\App\Http\Controllers\VehiculoController::class, 'create'])->name('vehiculos.crear');
        Route::post('/vehiculos', [\App\Http\Controllers\VehiculoController::class, 'store'])->name('vehiculos.guardar');
        Route::get('/vehiculos/{id}/editar', [\App\Http\Controllers\VehiculoController::class, 'edit'])->name('vehiculos.editar');
        Route::put('/vehiculos/{id}', [\App\Http\Controllers\VehiculoController::class, 'update'])->name('vehiculos.actualizar');
        Route::delete('/vehiculos/{id}', [\App\Http\Controllers\VehiculoController::class, 'destroy'])->name('vehiculos.eliminar');
        
        // --- RUTAS DE VIAJE (Ver, Crear, Editar, Eliminar) ---
        Route::get('/rutas', [\App\Http\Controllers\RutaConductorController::class, 'index'])->name('rutas.index');
        Route::get('/rutas/nueva', [\App\Http\Controllers\RutaConductorController::class, 'create'])->name('rutas.crear');
        Route::post('/rutas', [\App\Http\Controllers\RutaConductorController::class, 'store'])->name('rutas.guardar');
        Route::get('/rutas/{id}/editar', [\App\Http\Controllers\RutaConductorController::class, 'edit'])->name('rutas.editar');
        Route::put('/rutas/{id}', [\App\Http\Controllers\RutaConductorController::class, 'update'])->name('rutas.actualizar');
        Route::delete('/rutas/{id}', [\App\Http\Controllers\RutaConductorController::class, 'destroy'])->name('rutas.eliminar');
    });

      // ZONA SOLO PARA PASAJEROS
      Route::middleware(['es.pasajero'])->prefix('pasajero')->name('pasajero.')->group(function () {
        
        // 1. El Menú Principal (Solo 2 opciones)
        Route::get('/dashboard', function() {
            return view('pasajero.dashboard');
        })->name('dashboard');

        // 2. Puntos de Recogida
        Route::post('/viaje/solicitar', [\App\Http\Controllers\ViajeController::class, 'solicitarViaje'])->name('viajes.solicitar');
        Route::get('/mis-puntos', [\App\Http\Controllers\SolicitudController::class, 'index'])->name('puntos.index');
        Route::get('/mis-puntos/nuevo', [\App\Http\Controllers\SolicitudController::class, 'create'])->name('puntos.crear');
        Route::get('/mis-puntos/{id}/editar', [\App\Http\Controllers\SolicitudController::class, 'edit'])->name('puntos.editar');
        Route::put('/mis-puntos/{id}', [\App\Http\Controllers\SolicitudController::class, 'update'])->name('puntos.actualizar');
        Route::post('/mis-puntos', [\App\Http\Controllers\SolicitudController::class, 'store'])->name('puntos.guardar');
        Route::delete('/mis-puntos/{id}', [\App\Http\Controllers\SolicitudController::class, 'destroy'])->name('puntos.eliminar');
        // Reemplaza la ruta de cancelar actual por esta:
        Route::post('/mis-viajes/{id_viaje}/cancelar', [\App\Http\Controllers\ViajeController::class, 'cancelarViaje'])->name('viajes.cancelar');

        // 3. Mis Viajes (Historial y Estado)
        Route::get('/mis-viajes', [\App\Http\Controllers\ViajeController::class, 'index'])->name('viajes.index');
        
        // 4. Buscar Conductores desde un Punto
        Route::get('/buscar-conductores/{id_solicitud}', [\App\Http\Controllers\MatchController::class, 'explorarConductores'])->name('buscar_conductores');
    });
    // Ruta para cambiar entre pasajero y conductor
    Route::post('/cambiar-modo', [PerfilController::class, 'cambiarModo'])->name('cambiar_modo');

    // --- NUEVAS RUTAS DE PERFIL ---
    Route::get('/perfil/ver', [PerfilController::class, 'verPerfil'])->name('perfil.ver'); 
    Route::get('/mi-perfil', [PerfilController::class, 'editarPerfil'])->name('perfil.editar');
    Route::put('/mi-perfil', [PerfilController::class, 'actualizarPerfil'])->name('perfil.actualizar');
    // --- RUTAS DE CALIFICACIONES Y RESEÑAS ---
    Route::get('/calificar/{id_viaje}/{rol_evaluado}', [\App\Http\Controllers\CalificacionController::class, 'crear'])->name('calificaciones.crear');
    Route::post('/calificar', [\App\Http\Controllers\CalificacionController::class, 'guardar'])->name('calificaciones.guardar');
    Route::get('/mis-resenas', [\App\Http\Controllers\CalificacionController::class, 'misResenas'])->name('calificaciones.index');
    
});