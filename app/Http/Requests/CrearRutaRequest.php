<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class CrearRutaRequest extends FormRequest
{
    
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'id_ubicacion_origen' => 'required|exists:ubicaciones,id_ubicacion',
            'id_ubicacion_destino' => 'required|exists:ubicaciones,id_ubicacion',
            'dias' => 'required|array|min:1',
            'dias.*' => 'exists:dias_semana,id_dia',
            'hora_salida' => 'required|date_format:H:i'
        ];
    }
}