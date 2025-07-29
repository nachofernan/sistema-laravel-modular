<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ProveedorSyncSubrubrosRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'subrubro_ids' => 'required|array',
            'subrubro_ids.*' => 'exists:proveedores.subrubros,id',
        ];
    }
} 