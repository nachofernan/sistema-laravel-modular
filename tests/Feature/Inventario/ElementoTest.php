<?php

use App\Livewire\Inventario\Elementos\Index\Search;
use App\Models\Inventario\Categoria;
use App\Models\Inventario\Elemento;
use App\Models\User;
use App\Models\Usuarios\Permission;
use App\Models\Usuarios\Role;
use Livewire\Livewire;

test('puede crear un elemento de inventario', function () {
    $elemento = Elemento::factory()->create();

    expect($elemento->id)->not->toBeNull()
        ->and($elemento->codigo)->toBeString()->not->toBeEmpty();
});

test('un elemento pertenece a categoría y estado', function () {
    $elemento = Elemento::factory()->create();

    expect($elemento->categoria)->not->toBeNull()
        ->and($elemento->estado)->not->toBeNull();
});

test('createCodigo genera un código con el prefijo de la categoría', function () {
    $categoria = Categoria::factory()->create(['prefijo' => 'NB']);

    $codigo = Elemento::createCodigo($categoria->id);

    expect($codigo)->toStartWith('NB');
});

test('la_ruta_de_exportar_descarga_el_excel', function () {
    $usuario = User::factory()->create();
    $usuario->assignRole(Role::firstOrCreate(['name' => 'Inventario/Acceso', 'guard_name' => 'web']));
    $usuario->givePermissionTo(Permission::firstOrCreate(['name' => 'Inventario/Elementos/Ver', 'guard_name' => 'web']));

    $this->actingAs($usuario)
        ->get(route('inventario.elementos.exportar'))
        ->assertOk()
        ->assertDownload('inventario-elementos-'.now()->format('Y-m-d').'.xlsx');
});

test('el_listado_filtra_elementos_por_sede', function () {
    $enSede = Elemento::factory()->create();
    $enOtraSede = Elemento::factory()->create();

    Livewire::actingAs(User::factory()->create())
        ->test(Search::class)
        ->set('estado_search', [$enSede->estado_id, $enOtraSede->estado_id])
        ->set('sede', $enSede->sede_id)
        ->assertSee($enSede->codigo)
        ->assertDontSee($enOtraSede->codigo);
});
