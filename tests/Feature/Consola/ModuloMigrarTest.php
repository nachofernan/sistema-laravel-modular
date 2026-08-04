<?php

/**
 * `modulo:migrar` toca el aislamiento entre bases: corre migraciones contra la
 * conexión de un módulo. Lo que estos tests protegen no es que migre, sino que
 * **no** migre donde no corresponde — nunca pasa `--force`.
 */
// La suite corre en el entorno `testing`, que el comando rechaza igual que a
// producción: para probar lo que hace en local hay que decirle que está en local.
beforeEach(function () {
    $this->app['env'] = 'local';
});

test('no corre si el entorno no es local', function () {
    $this->app['env'] = 'production';

    $this->artisan('modulo:migrar documentos')
        ->expectsOutputToContain('sólo con APP_ENV=local')
        ->assertFailed();
});

test('no corre contra la conexion de produccion del modulo', function () {
    $this->artisan('modulo:migrar documentos_prod')
        ->expectsOutputToContain('es la de producción')
        ->assertFailed();
});

test('avisa si el modulo no tiene carpeta de migraciones', function () {
    $this->artisan('modulo:migrar pepito')
        ->expectsOutputToContain('No existe la carpeta de migraciones')
        ->assertFailed();
});

test('avisa si el --desde no corresponde a ninguna migracion', function () {
    $this->artisan('modulo:migrar documentos --desde=1999_01_01_000000')
        ->expectsOutputToContain('Ninguna migración de Documentos empieza con')
        ->assertFailed();
});

test('resuelve la carpeta del modulo y la base de su conexion', function () {
    $this->artisan('modulo:migrar documentos')
        ->expectsOutputToContain('Documentos')
        ->expectsOutputToContain(config('database.connections.documentos.database'))
        ->assertSuccessful();
});
