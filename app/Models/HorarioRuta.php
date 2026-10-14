<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HorarioRuta extends Model
{
    protected $table = 'horarios_rutas';
    protected $primaryKey = 'id_horario_ruta';
    public $timestamps = false;

    protected $fillable = [
        'id_ruta_conductor',
        'id_dia',
        'hora_salida'
    ];

    public function ruta()
    {
        return $this->belongsTo(RutaConductor::class, 'id_ruta_conductor', 'id_ruta_conductor');
    }

    public function dia()
    {
        return $this->belongsTo(DiaSemana::class, 'id_dia', 'id_dia');
    }
}