<?php

namespace Tests\Feature\Adminip;

use App\Models\Adminip\IP;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IPTest extends TestCase
{
    /** @test */
    public function puede_crear_una_ip()
    {
        // Este test verifica que se puede crear una IP correctamente
        $ip = IP::factory()->create();
        $this->assertDatabaseHas('ips', [
            'id' => $ip->id,
        ], 'adminip');
    }
} 