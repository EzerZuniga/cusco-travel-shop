<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'usuario_id' => 'required|exists:usuarios,id',
            'tour_id' => 'required|exists:tours,id',
            'fecha' => 'required|date',
            'personas' => 'required|integer|min:1|max:50',
            'estado' => 'required|in:pendiente,confirmada,cancelada',
        ];
    }

    public function messages(): array
    {
        return [
            'usuario_id.required' => 'El usuario es obligatorio',
            'usuario_id.exists' => 'El usuario seleccionado no existe',
            'tour_id.required' => 'El tour es obligatorio',
            'tour_id.exists' => 'El tour seleccionado no existe',
            'fecha.required' => 'La fecha es obligatoria',
            'fecha.date' => 'La fecha debe ser una fecha válida',
            'personas.required' => 'El número de personas es obligatorio',
            'personas.min' => 'Debe haber al menos 1 persona',
            'personas.max' => 'No puede haber más de 50 personas',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado debe ser: pendiente, confirmada o cancelada',
        ];
    }
}
