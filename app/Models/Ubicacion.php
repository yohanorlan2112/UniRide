<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table = 'ubicaciones';
    protected $primaryKey = 'id_ubicacion';
    public $timestamps = false; 

    protected $fillable = [
        'google_place_id',
        'nombre_lugar',
        'direccion_formateada',
        'latitud',
        'longitud'
    ];

    // Una ubicación puede ser el origen de muchas rutas
    public function rutasComoOrigen()
    {
        return $this->hasMany(RutaConductor::class, 'id_ubicacion_origen', 'id_ubicacion');
    }

    // Una ubicación puede ser el destino de muchas rutas
    public function rutasComoDestino()
    {
        return $this->hasMany(RutaConductor::class, 'id_ubicacion_destino', 'id_ubicacion');
    }
}