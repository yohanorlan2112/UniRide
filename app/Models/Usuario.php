<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // 1. IMPORTANTE: Importar Sanctum aquí

class Usuario extends Authenticatable
{
    // 2. IMPORTANTE: Agregar HasApiTokens dentro de la clase
    use HasApiTokens, Notifiable; 

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'documento_identidad',
        'codigo_estudiantil',
        'telefono',
        'correo',
        'contrasena',
        'estado_cuenta'
    ];

    protected $hidden = [
        'contrasena',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function perfilConductor()
    {
        return $this->hasOne(PerfilConductor::class, 'id_usuario', 'id_usuario');
    }

    public function perfilPasajero()
    {
        return $this->hasOne(PerfilPasajero::class, 'id_usuario', 'id_usuario');
    }
    public function calificacionesRecibidas()
    {
        return $this->hasMany(Calificacion::class, 'id_evaluado', 'id_usuario');
    }

    public function promedioCalificacion($rol = null)
    {
        $query = $this->calificacionesRecibidas();
        if ($rol) {
            $query->where('rol_evaluado', $rol);
        }
        $promedio = $query->avg('puntuacion');
        
        return $promedio ? number_format($promedio, 1) . ' ⭐' : 'Nuevo 🌟';
    }
}