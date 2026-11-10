<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Sleep;

/**
 * app:split-user-names con Groq simulado. Los usuarios reales sin nombre se marcan dentro de la
 * transacción del test (se revierte al terminar) para que el comando solo vea los del test.
 */
beforeEach(function () {
    config(['services.groq.key' => 'clave-de-prueba', 'services.groq.modelo' => 'modelo-de-prueba']);
    User::whereNull('nombre')->update(['nombre' => '(test)']);
});

function usuarioSinSeparar(string $realname): User
{
    return User::factory()->create(['realname' => $realname, 'nombre' => null, 'apellido' => null]);
}

function respuestaGroq(array $personas, int $status = 200)
{
    return Http::response(
        ['choices' => [['message' => ['content' => json_encode(['personas' => $personas])]]]],
        $status
    );
}

test('sin_aplicar_no_guarda_nada', function () {
    $usuario = usuarioSinSeparar('Fernandez Vega Magali Victoria');
    Http::fake(['api.groq.com/*' => respuestaGroq([['id' => $usuario->id, 'palabras_apellido' => 2]])]);

    $this->artisan('app:split-user-names')
        ->expectsOutputToContain('No se guardó nada')
        ->assertSuccessful();

    expect($usuario->fresh()->nombre)->toBeNull()
        ->and($usuario->fresh()->apellido)->toBeNull();
});

test('aplicar_corta_sobre_el_realname_original', function () {
    $usuario = usuarioSinSeparar('  De La Colina   Elisa Beatriz ');
    Http::fake(['api.groq.com/*' => respuestaGroq([['id' => $usuario->id, 'palabras_apellido' => 3]])]);

    $this->artisan('app:split-user-names --aplicar')->assertSuccessful();

    $usuario->refresh();
    expect($usuario->apellido)->toBe('De La Colina')
        ->and($usuario->nombre)->toBe('Elisa Beatriz')
        ->and($usuario->activities()->where('event', 'updated')->exists())->toBeTrue();
});

test('descarta_cortes_invalidos_e_ids_ajenos', function () {
    $valido = usuarioSinSeparar('Garcia German Edgardo');
    $invalido = usuarioSinSeparar('Rojas Raul');
    $ajeno = User::factory()->create(['nombre' => 'Original', 'apellido' => 'Original']);
    $unaPalabra = usuarioSinSeparar('Sanz');

    Http::fake(['api.groq.com/*' => respuestaGroq([
        ['id' => $valido->id, 'palabras_apellido' => 1],
        ['id' => $invalido->id, 'palabras_apellido' => 2], // dejaría el nombre vacío
        ['id' => $ajeno->id, 'palabras_apellido' => 1],    // no pertenece al lote
    ])]);

    $this->artisan('app:split-user-names --aplicar')
        ->expectsOutputToContain('1 sin resultado válido')
        ->expectsOutputToContain("{$unaPalabra->id} (Sanz)")
        ->assertSuccessful();

    expect($valido->fresh()->apellido)->toBe('Garcia')
        ->and($invalido->fresh()->nombre)->toBeNull()
        ->and($ajeno->fresh()->nombre)->toBe('Original')
        ->and($unaPalabra->fresh()->nombre)->toBeNull();
});

test('reintenta_cuando_groq_corta_por_limite', function () {
    Sleep::fake();
    $usuario = usuarioSinSeparar('Bayon Manuel Ignacio');
    Http::fake(['api.groq.com/*' => Http::sequence()
        ->push(['error' => ['message' => 'Rate limit reached']], 429, ['retry-after' => '7'])
        ->pushResponse(respuestaGroq([['id' => $usuario->id, 'palabras_apellido' => 1]])),
    ]);

    $this->artisan('app:split-user-names --aplicar')->assertSuccessful();

    Sleep::assertSlept(fn ($duracion) => $duracion->totalSeconds == 7);
    expect($usuario->fresh()->nombre)->toBe('Manuel Ignacio');
});
