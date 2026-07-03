<?php

namespace Database\Factories\Despacho;

use App\Models\Despacho\Lectura;
use App\Models\Despacho\Registrador;
use Illuminate\Database\Eloquent\Factories\Factory;

class LecturaFactory extends Factory
{
    protected $model = Lectura::class;

    public function definition(): array
    {
        $bloque = $this->faker->numberBetween(0, 23);
        $valorCrudo = $this->faker->randomFloat(4, 0, 1000);

        return [
            'registrador_id' => Registrador::factory(),
            'fecha' => $this->faker->date(),
            'bloque_horario' => $bloque,
            'hora_desde' => sprintf('%02d:00:00', $bloque),
            'hora_hasta' => sprintf('%02d:00:00', ($bloque + 1) % 24),
            'valor_crudo' => $valorCrudo,
            'valor_convertido' => $valorCrudo,
        ];
    }
}
