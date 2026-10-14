<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RutaConductor extends Model
{
    protected $table = 'rutas_conductores';
    protected $primaryKey = 'id_ruta_conductor';
    public $timestamps = false;

    protected $fillable = [
        'id_conductor',
        'id_vehiculo', // <-- NUEVA COLUMNA
        'id_ubicacion_origen',
        'id_ubicacion_destino',
        'latitud_origen',
        'longitud_origen',
        'destino_final',
        'esta_activa'
    ];

    // Vehículo asignado a esta ruta
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    // Viajes solicitados en esta ruta
    public function viajes()
    {
        return $this->hasMany(Viaje::class, 'id_ruta_conductor', 'id_ruta_conductor');
    }

    // Calcula cuántos asientos libres quedan en esta ruta
    public function cuposDisponibles()
    {
        $capacidadTotal = $this->vehiculo->capacidad_pasajeros ?? 1;
        $ocupados = $this->viajes()->where('estado_viaje', 'Aceptado')->count();
        
        return max(0, $capacidadTotal - $ocupados);
    }

    // RELACIONES
    public function conductor()
    {
        return $this->belongsTo(PerfilConductor::class, 'id_conductor', 'id_perfil_conductor');
    }

    public function origen()
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion_origen', 'id_ubicacion');
    }

    public function destino()
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion_destino', 'id_ubicacion');
    }

    public function horarios()
    {
        return $this->hasMany(HorarioRuta::class, 'id_ruta_conductor', 'id_ruta_conductor');
    }
    // Muestra el Mapa de Calor con la concentración de pasajeros
    public function mapaCalor()
    {
        // Traemos todos los puntos de recogida que tengan coordenadas en el mapa
        $puntosPasajeros = \App\Models\SolicitudPasajero::with(['horarios.dia'])
            ->whereNotNull('latitud_origen')
            ->whereNotNull('longitud_origen')
            ->where('estado', '!=', 'Cancelado')
            ->get();

        return view('conductor.mapa_calor', compact('puntosPasajeros'));
    }
    
}