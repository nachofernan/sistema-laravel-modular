<?php

namespace Database\Factories\Usuarios;

use App\Models\Usuarios\PasswordSecurity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PasswordSecurityFactory extends Factory
{
    protected $model = PasswordSecurity::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'password_expiry_days' => 180,
        ];
    }
} 