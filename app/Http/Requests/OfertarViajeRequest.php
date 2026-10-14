<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class OfertarViajeRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'id_ubicacion' => 'required|exists:ubicaciones,id_ubicacion',
            'precio_ofrecido' => 'required|numeric|min:1000',
            'id_dia' => 'required|exists:dias_semana,id_dia',
            'hora_recogida' => 'required|date_format:H:i'
        ];
    }
}