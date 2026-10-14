<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RastreoVivo extends Model
{
    protected $table = 'rastreos_en_vivo';
    protected $primaryKey = 'id_rastreo';
    public $timestamps = false;

    protected $fillable = [
        'id_viaje',
        'id_usuario',
        'latitud',
        'longitud',
        'velocidad_actual',
        'registrado_en'
    ];

    public function viaje()
    {
        return $this->belongsTo(Viaje::class, 'id_viaje', 'id_viaje');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}