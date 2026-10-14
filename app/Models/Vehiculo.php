<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';
    protected $primaryKey = 'id_vehiculo';
    public $timestamps = false;

    protected $fillable = [
        'id_conductor',
        'placa',
        'marca_modelo',
        'tipo_vehiculo',
        'capacidad_pasajeros', // <-- NUEVA COLUMNA
        'documentos_al_dia'
    ];

    public function conductor()
    {
        return $this->belongsTo(PerfilConductor::class, 'id_conductor', 'id_perfil_conductor');
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoVehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }
}