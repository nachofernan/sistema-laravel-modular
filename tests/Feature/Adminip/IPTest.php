<?php

use App\Models\Adminip\Categoria;
use App\Models\Adminip\IP;

test('puede crear una IP', function () {
    $ip = IP::factory()->create();

    expect($ip->id)->not->toBeNull()
        ->and($ip->ip)->toBeString()->not->toBeEmpty();
});

test('una IP puede pertenecer a una categoría', function () {
    $categoria = Categoria::factory()->create();
    $ip = IP::factory()->create(['categoria_id' => $categoria->id]);

    expect($ip->categoria_id)->toBe($categoria->id);
});
