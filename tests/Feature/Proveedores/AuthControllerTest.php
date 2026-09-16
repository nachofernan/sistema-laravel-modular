<?php

namespace Tests\Feature\Proveedores;

use App\Models\Proveedores\Proveedor;
use Firebase\JWT\JWT;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    #[Test]
    public function un_proveedor_valido_obtiene_token_y_accede_a_endpoint_protegido()
    {
        $proveedor = Proveedor::factory()->create();

        $response = $this->postJson('/api/generate-token', [
            'cuit' => $proveedor->cuit,
            'email' => $proveedor->correo,
        ]);

        $response->assertStatus(200)->assertJsonStructure(['token']);

        $token = $response->json('token');

        $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/proveedores/tipos-documentos')
            ->assertStatus(200);
    }

    #[Test]
    public function generar_token_con_proveedor_inexistente_devuelve_404()
    {
        $response = $this->postJson('/api/generate-token', [
            'cuit' => '99999999999',
            'email' => 'no-existe@baesa.com.ar',
        ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function endpoint_protegido_sin_token_devuelve_401()
    {
        $this->getJson('/api/proveedores/tipos-documentos')
            ->assertStatus(401);
    }

    #[Test]
    public function endpoint_protegido_con_token_firmado_con_otro_secreto_devuelve_401()
    {
        $proveedor = Proveedor::factory()->create();

        $tokenAjeno = JWT::encode([
            'sub' => $proveedor->id,
            'cuit' => $proveedor->cuit,
            'iat' => time(),
            'exp' => time() + 600,
        ], 'secreto-que-no-es-el-configurado', 'HS256');

        $this->withHeaders(['Authorization' => 'Bearer ' . $tokenAjeno])
            ->getJson('/api/proveedores/tipos-documentos')
            ->assertStatus(401);
    }

    #[Test]
    public function endpoint_protegido_con_token_expirado_devuelve_401()
    {
        $proveedor = Proveedor::factory()->create();

        $tokenExpirado = JWT::encode([
            'sub' => $proveedor->id,
            'cuit' => $proveedor->cuit,
            'iat' => time() - 1200,
            'exp' => time() - 600,
        ], config('services.jwt.secret'), 'HS256');

        $this->withHeaders(['Authorization' => 'Bearer ' . $tokenExpirado])
            ->getJson('/api/proveedores/tipos-documentos')
            ->assertStatus(401);
    }
}
