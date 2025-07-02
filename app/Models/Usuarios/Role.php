<?php

namespace App\Models\Usuarios;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $connection = 'usuarios';
}
