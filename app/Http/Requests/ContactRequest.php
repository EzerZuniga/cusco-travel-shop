<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:30',
            'mensaje' => 'required|string|min:10|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser válido',
            'mensaje.required' => 'El mensaje es obligatorio',
            'mensaje.min' => 'El mensaje debe tener al menos 10 caracteres',
            'mensaje.max' => 'El mensaje no puede exceder 2000 caracteres',
        ];
    }
}
