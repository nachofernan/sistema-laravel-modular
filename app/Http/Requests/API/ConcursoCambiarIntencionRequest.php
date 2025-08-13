<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ConcursoCambiarIntencionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'intencion' => 'required|integer|in:0,1,2,3',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'intencion.required' => 'La intención es requerida.',
            'intencion.integer' => 'La intención debe ser un número entero.',
            'intencion.in' => 'La intención debe ser: 0 (Pregunta), 1 (Participa), 2 (No participa), 3 (Ofertó).',
        ];
    }
} 