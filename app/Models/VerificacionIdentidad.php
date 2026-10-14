<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class VerificacionIdentidad extends Model
{
    protected $table = 'verificaciones_identidad';
    protected $primaryKey = 'id_verificacion';
    public $timestamps = false;

    protected $fillable = [
        'id_conductor',
        'foto_licencia_frente_url',
        'foto_licencia_reverso_url',
        'foto_rostro_selfie_url',
        'porcentaje_similitud',
        'estado_verificacion',
        'fecha_solicitud'
    ];

    public function conductor()
    {
        return $this->belongsTo(PerfilConductor::class, 'id_conductor', 'id_perfil_conductor');
    }
}