<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservaViaje extends Model
{
    protected $table = 'reservas_viajes';
    protected $primaryKey = 'id_reserva';
    public $timestamps = false;

    protected $fillable = [
        'id_viaje',
        'id_solicitud_pasajero',
        'precio_acordado',
        'estado' // Ej: 'Confirmado', 'Rechazado', 'Finalizado'
    ];

    public function viaje()
    {
        return $this->belongsTo(Viaje::class, 'id_viaje', 'id_viaje');
    }

    public function solicitud()
    {
        return $this->belongsTo(SolicitudPasajero::class, 'id_solicitud_pasajero', 'id_solicitud_pasajero');
    }

    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'id_reserva', 'id_reserva');
    }
}