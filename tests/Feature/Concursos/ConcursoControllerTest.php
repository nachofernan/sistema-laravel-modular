<?php

namespace Tests\Feature\Concursos;

use Tests\TestCase;
use App\Models\Concursos\Concurso;
use App\Models\Concursos\Invitacion;
use App\Models\Concursos\OfertaDocumento;
use App\Models\Proveedores\Proveedor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ConcursoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $proveedor;
    protected $concurso;
    protected $invitacion;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Configurar storage fake
        Storage::fake('concursos');
        
        // Crear proveedor
        $this->proveedor = Proveedor::factory()->create();
        
        // Crear concurso con fecha de cierre futura
        $this->concurso = Concurso::factory()->create([
            'fecha_cierre' => Carbon::now()->addDays(7),
            'estado_id' => 2 // Activo
        ]);
        
        // Crear invitación
        $this->invitacion = Invitacion::factory()->create([
            'concurso_id' => $this->concurso->id,
            'proveedor_id' => $this->proveedor->id,
            'intencion' => 2 // Participando
        ]);
        
        // Generar token JWT
        $response = $this->postJson('/api/generate-token', [
            'cuit' => $this->proveedor->cuit
        ]);
        
        $this->token = $response->json('data.token');
    }

    /** @test */
    public function puede_eliminar_documento_de_oferta_antes_del_cierre()
    {
        // Crear documento de oferta
        $documento = OfertaDocumento::factory()->create([
            'invitacion_id' => $this->invitacion->id,
            'user_id_created' => null // Subido por proveedor
        ]);
        
        // Agregar archivo al documento
        $file = UploadedFile::fake()->create('documento.pdf', 100);
        $documento->addMedia($file)->toMediaCollection('archivos', 'concursos');
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/concursos/{$this->concurso->id}/documentos/{$documento->id}");
        
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Documento eliminado correctamente.'
                ]);
        
        // Verificar que el documento fue eliminado
        $this->assertDatabaseMissing('oferta_documentos', ['id' => $documento->id]);
    }

    /** @test */
    public function no_puede_eliminar_documento_despues_del_cierre()
    {
        // Modificar fecha de cierre a pasado
        $this->concurso->update(['fecha_cierre' => Carbon::now()->subDay()]);
        
        // Crear documento de oferta
        $documento = OfertaDocumento::factory()->create([
            'invitacion_id' => $this->invitacion->id,
            'user_id_created' => null
        ]);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/concursos/{$this->concurso->id}/documentos/{$documento->id}");
        
        $response->assertStatus(403)
                ->assertJson([
                    'success' => false,
                    'message' => 'No se puede eliminar el documento. El concurso ya ha cerrado.'
                ]);
    }

    /** @test */
    public function no_puede_eliminar_documento_de_empresa()
    {
        // Crear documento ingresado por empresa
        $documento = OfertaDocumento::factory()->create([
            'invitacion_id' => $this->invitacion->id,
            'user_id_created' => User::factory()->create()->id
        ]);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/concursos/{$this->concurso->id}/documentos/{$documento->id}");
        
        $response->assertStatus(403)
                ->assertJson([
                    'success' => false,
                    'message' => 'No se puede eliminar un documento ingresado por la empresa.'
                ]);
    }

    /** @test */
    public function no_puede_eliminar_documento_inexistente()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/concursos/{$this->concurso->id}/documentos/999");
        
        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'Documento no encontrado en la oferta.'
                ]);
    }

    /** @test */
    public function puede_dar_de_baja_oferta_completa()
    {
        // Crear varios documentos de oferta
        $documentos = OfertaDocumento::factory()->count(3)->create([
            'invitacion_id' => $this->invitacion->id,
            'user_id_created' => null // Subidos por proveedor
        ]);
        
        // Agregar archivos a los documentos
        foreach ($documentos as $documento) {
            $file = UploadedFile::fake()->create('documento.pdf', 100);
            $documento->addMedia($file)->toMediaCollection('archivos', 'concursos');
        }
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/concursos/{$this->concurso->id}/oferta");
        
        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Oferta dada de baja correctamente. Se eliminaron 3 documentos.',
                    'data' => [
                        'documentos_eliminados' => 3,
                        'intencion_actualizada' => 1
                    ]
                ]);
        
        // Verificar que los documentos fueron eliminados
        foreach ($documentos as $documento) {
            $this->assertDatabaseMissing('oferta_documentos', ['id' => $documento->id]);
        }
        
        // Verificar que la intención cambió a 1
        $this->invitacion->refresh();
        $this->assertEquals(1, $this->invitacion->intencion);
    }

    /** @test */
    public function no_puede_dar_de_baja_oferta_despues_del_cierre()
    {
        // Modificar fecha de cierre a pasado
        $this->concurso->update(['fecha_cierre' => Carbon::now()->subDay()]);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/concursos/{$this->concurso->id}/oferta");
        
        $response->assertStatus(403)
                ->assertJson([
                    'success' => false,
                    'message' => 'No se puede dar de baja la oferta. El concurso ya ha cerrado.'
                ]);
    }

    /** @test */
    public function dar_de_baja_solo_elimina_documentos_de_proveedor()
    {
        // Crear documento de proveedor
        $documentoProveedor = OfertaDocumento::factory()->create([
            'invitacion_id' => $this->invitacion->id,
            'user_id_created' => null
        ]);
        
        // Crear documento de empresa
        $documentoEmpresa = OfertaDocumento::factory()->create([
            'invitacion_id' => $this->invitacion->id,
            'user_id_created' => User::factory()->create()->id
        ]);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson("/api/concursos/{$this->concurso->id}/oferta");
        
        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'documentos_eliminados' => 1
                    ]
                ]);
        
        // Verificar que solo se eliminó el documento del proveedor
        $this->assertDatabaseMissing('oferta_documentos', ['id' => $documentoProveedor->id]);
        $this->assertDatabaseHas('oferta_documentos', ['id' => $documentoEmpresa->id]);
    }
} 