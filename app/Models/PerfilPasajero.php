<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PerfilPasajero extends Model
{
    protected $table = 'perfiles_pasajeros';
    protected $primaryKey = 'id_perfil_pasajero';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'contacto_emergencia_nombre',
        'contacto_emergencia_telefono'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function solicitudes()
    {
        return $this->hasMany(SolicitudPasajero::class, 'id_pasajero', 'id_perfil_pasajero');
    }
}