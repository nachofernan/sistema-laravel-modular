<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConcursoCambiarIntencionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'intencion'     => 'required|integer|in:0,1,2,3',
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'intencion.required'      => 'La intención es requerida.',
            'intencion.integer'       => 'La intención debe ser un número entero.',
            'intencion.in'            => 'La intención debe ser: 0 (Pregunta), 1 (Participa), 2 (No participa), 3 (Ofertó).',
            'observaciones.string' => 'El motivo de rechazo debe ser un texto.',
            'observaciones.max'    => 'El motivo de rechazo no puede superar los 1000 caracteres.',
        ];
    }
} 