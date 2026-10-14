<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilConductor extends Model
{
    protected $table = 'perfiles_conductores';
    protected $primaryKey = 'id_perfil_conductor';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'numero_licencia',
        'esta_verificado'
    ];

    // RELACIONES
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'id_conductor', 'id_perfil_conductor');
    }
}