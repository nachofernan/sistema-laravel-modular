<?php

namespace App\Models\Shared\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ConcursoProveedor extends Pivot
{
    //
    protected $connection = 'concursos';
    protected $table = 'concurso_proveedor';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection('concursos');
    }
}
