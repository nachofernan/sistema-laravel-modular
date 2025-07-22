<?php

namespace Database\Factories\Usuarios;

use App\Models\Usuarios\ManagedJob;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ManagedJobFactory extends Factory
{
    protected $model = ManagedJob::class;

    public function definition()
    {
        return [
            'job_uuid' => Str::uuid(),
            'status' => 'pending',
            'job_type' => 'email',
            'entity_type' => 'App\\Models\\User',
            'tags' => ['email', 'sms'],
            'entity_id' => 1,
        ];
    }
} 