<?php

use App\Models\Tickets\Ticket;
use App\Models\Tickets\Estado;
use App\Models\Tickets\Categoria;
use App\Models\User;

test('puede crear un ticket', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->id)->not->toBeNull();
});

test('un ticket tiene estado, categoría y usuario', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->estado)->not->toBeNull()
        ->and($ticket->categoria)->not->toBeNull()
        ->and($ticket->user)->not->toBeNull();
});

test('un ticket recién creado no está finalizado', function () {
    $ticket = Ticket::factory()->create(['finalizado' => null]);

    expect($ticket->finalizado)->toBeNull();
});
