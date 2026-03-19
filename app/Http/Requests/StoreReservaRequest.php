<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservaRequest extends FormRequest
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
            'fecha' => 'required|date|after_or_equal:today',
            'personas' => 'required|integer|min:1|max:50',
            'estado' => 'nullable|in:pendiente,confirmada,cancelada',
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
            'fecha.after_or_equal' => 'La fecha no puede ser anterior a hoy',
            'personas.required' => 'El número de personas es obligatorio',
            'personas.min' => 'Debe haber al menos 1 persona',
            'personas.max' => 'No puede haber más de 50 personas',
        ];
    }
}
