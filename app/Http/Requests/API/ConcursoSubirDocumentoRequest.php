<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ConcursoSubirDocumentoRequest extends FormRequest
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
            'documento_tipo_id' => 'nullable|exists:concursos.documento_tipos,id',
            'file' => 'required|file|max:10240', // Máximo 10MB
            'comentarios' => 'nullable|string|max:500', // Comentarios opcionales para documentos adicionales
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
            'documento_tipo_id.exists' => 'El tipo de documento seleccionado no existe.',
            'file.required' => 'El archivo es requerido.',
            'file.file' => 'El archivo debe ser válido.',
            'file.max' => 'El archivo no puede superar los 10MB.',
            'comentarios.max' => 'Los comentarios no pueden superar los 500 caracteres.',
        ];
    }
} 