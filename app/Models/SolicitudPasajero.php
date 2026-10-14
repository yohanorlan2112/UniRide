<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SolicitudPasajero extends Model
{
    protected $table = 'solicitudes_pasajeros';
    protected $primaryKey = 'id_solicitud_pasajero';
    public $timestamps = false;

  protected $fillable = [
        'id_pasajero',
        'id_ubicacion', 
        'latitud_origen', 
        'longitud_origen', 
        'destino_final',   
        'precio_ofrecido',
        'estado'
    ];

    public function pasajero()
    {
        return $this->belongsTo(PerfilPasajero::class, 'id_pasajero', 'id_perfil_pasajero');
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion', 'id_ubicacion');
    }

    public function horarios()
    {
        return $this->hasMany(HorarioSolicitud::class, 'id_solicitud_pasajero', 'id_solicitud_pasajero');
    }
    // Relación: Un punto de recogida puede tener varios intentos de viaje
    public function viajes()
    {
        return $this->hasMany(\App\Models\Viaje::class, 'id_solicitud_pasajero', 'id_solicitud_pasajero');
    }
    // Conecta la solicitud con el perfil del pasajero para sacar su nombre y foto
    public function pasajeroPerfil()
    {
        return $this->belongsTo(\App\Models\PerfilPasajero::class, 'id_pasajero', 'id_perfil_pasajero');
    }
}