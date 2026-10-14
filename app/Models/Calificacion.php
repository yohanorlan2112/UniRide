<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    protected $table = 'calificaciones';
    protected $primaryKey = 'id_calificacion';
    public $timestamps = false;

    protected $fillable = [
        'id_viaje',
        'id_evaluador',
        'id_evaluado',
        'rol_evaluado',
        'puntuacion',
        'comentario'
    ];

    public function evaluador()
    {
        return $this->belongsTo(Usuario::class, 'id_evaluador', 'id_usuario');
    }

    public function evaluado()
    {
        return $this->belongsTo(Usuario::class, 'id_evaluado', 'id_usuario');
    }
}