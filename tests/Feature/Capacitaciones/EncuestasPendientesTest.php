<?php

use App\Models\Capacitaciones\Encuesta;
use App\Models\Capacitaciones\Invitacion;
use App\Models\Capacitaciones\Pregunta;
use App\Models\Capacitaciones\Respuesta;

test('una invitación presente con encuesta activa sin responder tiene encuestas pendientes', function () {
    $invitacion = Invitacion::factory()->create(['presente' => true]);
    $encuesta = Encuesta::factory()->create([
        'capacitacion_id' => $invitacion->capacitacion_id,
        'estado' => 1,
    ]);
    Pregunta::factory()->create(['encuesta_id' => $encuesta->id]);

    expect($invitacion->tieneEncuestasPendientes())->toBeTrue();
});

test('una encuesta ya respondida no cuenta como pendiente', function () {
    $invitacion = Invitacion::factory()->create(['presente' => true]);
    $encuesta = Encuesta::factory()->create([
        'capacitacion_id' => $invitacion->capacitacion_id,
        'estado' => 1,
    ]);
    $pregunta = Pregunta::factory()->create(['encuesta_id' => $encuesta->id]);
    Respuesta::factory()->create([
        'pregunta_id' => $pregunta->id,
        'user_id' => $invitacion->user_id,
    ]);

    expect($invitacion->tieneEncuestasPendientes())->toBeFalse();
});

test('una invitación sin asistencia no cuenta como pendiente aunque haya encuesta sin responder', function () {
    $invitacion = Invitacion::factory()->create(['presente' => false]);
    $encuesta = Encuesta::factory()->create([
        'capacitacion_id' => $invitacion->capacitacion_id,
        'estado' => 1,
    ]);
    Pregunta::factory()->create(['encuesta_id' => $encuesta->id]);

    expect($invitacion->tieneEncuestasPendientes())->toBeFalse();
});

test('una encuesta inactiva no cuenta como pendiente', function () {
    $invitacion = Invitacion::factory()->create(['presente' => true]);
    $encuesta = Encuesta::factory()->create([
        'capacitacion_id' => $invitacion->capacitacion_id,
        'estado' => 0,
    ]);
    Pregunta::factory()->create(['encuesta_id' => $encuesta->id]);

    expect($invitacion->tieneEncuestasPendientes())->toBeFalse();
});
