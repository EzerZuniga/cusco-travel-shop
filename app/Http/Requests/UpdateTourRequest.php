<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tourId = $this->route('tour')->id ?? null;

        return [
            'titulo' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('tours', 'slug')->ignore($tourId),
            ],
            'descripcion' => 'required|string|min:10',
            'precio' => 'required|numeric|min:0',
            'duracion' => 'nullable|string|max:100',
            'imagen' => 'nullable|string|max:500',
            'activo' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'El título es obligatorio',
            'slug.unique' => 'Este slug ya está en uso',
            'descripcion.required' => 'La descripción es obligatoria',
            'precio.required' => 'El precio es obligatorio',
        ];
    }
}
