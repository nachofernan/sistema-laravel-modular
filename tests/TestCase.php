<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    // Envuelve cada test en transacciones por conexión y las revierte al terminar.
    // Así los tests corren contra las bases de desarrollo sin dejar datos.
    protected $connectionsToTransact = [
        'usuarios',
        'tickets',
        'inventario',
        'documentos',
        'adminip',
        'capacitaciones',
        'proveedores',
        'concursos',
        'automotores',
        'despacho',
        'mesadeentradas',
    ];
}
