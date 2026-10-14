<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DocumentoVehiculo extends Model
{
    protected $table = 'documentos_vehiculos';
    protected $primaryKey = 'id_documento_vehiculo';
    public $timestamps = false;

    protected $fillable = [
        'id_vehiculo',
        'tipo_documento',
        'archivo_url',
        'fecha_expedicion',
        'fecha_vencimiento',
        'estado_validacion',
        'observaciones'
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }
}