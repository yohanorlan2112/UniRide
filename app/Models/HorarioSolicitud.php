<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HorarioSolicitud extends Model
{
    protected $table = 'horarios_solicitudes';
    protected $primaryKey = 'id_horario_solicitud';
    public $timestamps = false;

    protected $fillable = [
        'id_solicitud_pasajero',
        'id_dia',
        'hora_recogida'
    ];

    public function solicitud()
    {
        return $this->belongsTo(SolicitudPasajero::class, 'id_solicitud_pasajero', 'id_solicitud_pasajero');
    }

    public function dia()
    {
        return $this->belongsTo(DiaSemana::class, 'id_dia', 'id_dia');
    }
}