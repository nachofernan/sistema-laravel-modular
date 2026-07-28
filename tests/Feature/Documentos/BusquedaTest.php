<?php

use App\Livewire\Documentos\Documentos\Index\Search;
use App\Models\Documentos\Categoria;
use App\Models\Documentos\Documento;
use App\Models\User;
use Livewire\Livewire;

/**
 * El listado del panel agrupa por subcategoría y hasta esta versión no filtraba
 * nada: el buscador tiene que mirar nombre, código y descripción, y esconder las
 * categorías que no aportan resultados.
 *
 * Los tests corren contra la base de dev, que ya tiene documentos reales: por eso
 * los nombres y códigos llevan el sufijo ZZQ y no se cuentan resultados totales.
 */
function subcategoriaConDocumentos(array $documentos, string $nombre = 'Subcategoria ZZQ'): Categoria
{
    $categoria = Categoria::factory()
        ->hijaDe(Categoria::factory()->create(['nombre' => "Padre de {$nombre}"]))
        ->create(['nombre' => $nombre]);

    foreach ($documentos as $atributos) {
        Documento::factory()->create($atributos + ['categoria_id' => $categoria->id]);
    }

    return $categoria;
}

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('sin termino de busqueda se listan todos los documentos', function () {
    subcategoriaConDocumentos([
        ['nombre' => 'Politica de regalos ZZQ'],
        ['nombre' => 'Manual de compras ZZQ'],
    ]);

    Livewire::test(Search::class)
        ->assertSee('Politica de regalos ZZQ')
        ->assertSee('Manual de compras ZZQ');
});

test('busca por nombre', function () {
    subcategoriaConDocumentos([
        ['nombre' => 'Politica de regalos ZZQ'],
        ['nombre' => 'Manual de compras ZZQ'],
    ]);

    Livewire::test(Search::class)
        ->set('buscar', 'regalos ZZQ')
        ->assertSee('Politica de regalos ZZQ')
        ->assertDontSee('Manual de compras ZZQ');
});

test('busca por codigo de control de gestion', function () {
    subcategoriaConDocumentos([
        ['nombre' => 'Politica de regalos ZZQ', 'codigo' => 'ZZQ-07.2-003_v3'],
        ['nombre' => 'Manual de compras ZZQ', 'codigo' => 'ZZQ-09.0-001_v1'],
    ]);

    Livewire::test(Search::class)
        ->set('buscar', 'ZZQ-07.2')
        ->assertSee('Politica de regalos ZZQ')
        ->assertDontSee('Manual de compras ZZQ');
});

test('busca por descripcion', function () {
    subcategoriaConDocumentos([
        ['nombre' => 'Politica de regalos ZZQ', 'descripcion' => 'Obsequios ZZQ de proveedores'],
        ['nombre' => 'Manual de compras ZZQ', 'descripcion' => 'Circuito de adquisiciones'],
    ]);

    Livewire::test(Search::class)
        ->set('buscar', 'Obsequios ZZQ')
        ->assertSee('Politica de regalos ZZQ')
        ->assertDontSee('Manual de compras ZZQ');
});

test('una categoria sin coincidencias no aparece en los resultados', function () {
    $conResultado = subcategoriaConDocumentos([['nombre' => 'Politica de regalos ZZQ']], 'Normativa ZZQ');
    $sinResultado = subcategoriaConDocumentos([['nombre' => 'Manual de compras ZZQ']], 'Abastecimiento ZZQ');

    Livewire::test(Search::class)
        ->set('buscar', 'regalos ZZQ')
        ->assertSee($conResultado->nombre)
        ->assertDontSee($sinResultado->nombre);
});

test('una busqueda sin resultados avisa en vez de mostrar el listado vacio', function () {
    subcategoriaConDocumentos([['nombre' => 'Politica de regalos ZZQ']]);

    Livewire::test(Search::class)
        ->set('buscar', 'ZZQ no existe este documento')
        ->assertViewHas('resultados', 0)
        ->assertSee('Sin resultados');
});
