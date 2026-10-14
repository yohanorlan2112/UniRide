<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class RegistroUsuarioRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'primer_nombre' => 'required|string|max:50',
            'primer_apellido' => 'required|string|max:50',
            'documento_identidad' => 'required|string|max:20|unique:usuarios,documento_identidad',
            'correo' => 'required|email|max:150|unique:usuarios,correo',
            'contrasena' => 'required|string|min:8',
            'rol_seleccionado' => 'required|in:conductor,pasajero',
        ];
    }
}