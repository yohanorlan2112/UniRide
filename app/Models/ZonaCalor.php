<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ZonaCalor extends Model
{
    protected $table = 'zonas_calor';
    protected $primaryKey = 'id_zona_calor';
    public $timestamps = false;

    protected $fillable = [
        'id_ubicacion',
        'puntaje_demanda',
        'calculado_en'
    ];

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion', 'id_ubicacion');
    }
}