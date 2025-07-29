<?php

namespace Database\Factories\Documentos;

use App\Models\Documentos\Descarga;
use App\Models\Documentos\Documento;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DescargaFactory extends Factory
{
    protected $model = Descarga::class;

    public function definition()
    {
        return [
            'documento_id' => Documento::factory(),
            'user_id' => User::factory(),
        ];
    }
} 