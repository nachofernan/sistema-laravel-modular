<?php

namespace Database\Factories\Concursos;

use App\Models\Concursos\Historial;
use App\Models\Concursos\Concurso;
use App\Models\Concursos\Estado;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistorialFactory extends Factory
{
    protected $model = Historial::class;

    public function definition()
    {
        return [
            'concurso_id' => Concurso::factory(),
            'estado_id' => Estado::factory(),
            'user_id' => User::factory(),
        ];
    }
} 