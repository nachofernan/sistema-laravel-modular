<?php

namespace Tests\Feature\Usuarios;

use App\Models\Usuarios\ManagedJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagedJobTest extends TestCase
{
    // NOTA: No usamos RefreshDatabase para no borrar la base completa

    /** @test */
    public function puede_crear_un_job()
    {
        // Este test verifica que se puede crear un job administrado
        /* $job = ManagedJob::create([
            'status' => 'pending',
            'job_type' => 'email',
            'entity_type' => 'App\\Models\\User',
            'entity_id' => 1,
        ]); */
        $job = ManagedJob::factory()->create();
        $this->assertDatabaseHas('managed_jobs', [
            'id' => $job->id,
        ]);
    }
} 