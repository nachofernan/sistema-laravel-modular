<?php

namespace Database\Factories\Usuarios;

use App\Models\Usuarios\Log;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogFactory extends Factory
{
    protected $model = Log::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'ip_address' => $this->faker->ipv4,
            'event' => 'login',
        ];
    }
} 