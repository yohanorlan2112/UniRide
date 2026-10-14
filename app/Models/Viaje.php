<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Viaje extends Model
{
    protected $table = 'viajes';
    protected $primaryKey = 'id_viaje';
    public $timestamps = false; // Como lo hacemos a palito, desactivamos timestamps automáticos

    protected $fillable = [
        'id_solicitud_pasajero',
        'id_conductor',
        'id_ruta_conductor', // <-- NUEVA COLUMNA
        'estado_viaje'
    ];

    public function ruta()
    {
        return $this->belongsTo(RutaConductor::class, 'id_ruta_conductor', 'id_ruta_conductor');
    }

    // Conexión con el punto del pasajero
    public function solicitud()
    {
        return $this->belongsTo(SolicitudPasajero::class, 'id_solicitud_pasajero', 'id_solicitud_pasajero');
    }

    // Conexión con el perfil del conductor
    public function conductor()
    {
        return $this->belongsTo(PerfilConductor::class, 'id_conductor', 'id_perfil_conductor');
    }
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'id_viaje', 'id_viaje');
    }
}