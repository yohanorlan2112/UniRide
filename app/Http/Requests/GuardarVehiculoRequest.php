<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarVehiculoRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo permite guardar si el usuario está logueado
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'placa' => 'required|string|max:15|unique:vehiculos,placa',
            'marca_modelo' => 'required|string|max:100',
            'tipo_vehiculo' => 'required|in:moto,carro',
            'capacidad' => 'required|integer|min:1|max:4',
        ];
    }

    public function messages(): array
    {
        // Mensajes personalizados en español
        return [
            'placa.required' => 'La placa es obligatoria.',
            'placa.unique' => 'Esta placa ya está registrada en el sistema.',
            'tipo_vehiculo.in' => 'El vehículo solo puede ser moto o carro.',
        ];
    }
}