<?php

namespace Database\Factories\Tickets;

use App\Models\Tickets\Documento;
use App\Models\Tickets\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentoFactory extends Factory
{
    protected $model = Documento::class;

    public function definition()
    {
        return [
            'ticket_id' => Ticket::factory(),
            'archivo' => $this->faker->filePath(),
            'file_storage' => $this->faker->filePath(),
            'extension' => $this->faker->fileExtension(),
            'mimeType' => $this->faker->mimeType(),
            'user_id_created' => User::factory(),
        ];
    }
} 