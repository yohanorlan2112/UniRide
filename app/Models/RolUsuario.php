<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RolUsuario extends Model
{
    protected $table = 'roles_usuarios';
    protected $primaryKey = 'id_rol_usuario';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_rol',
        'asignado_en'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }
}