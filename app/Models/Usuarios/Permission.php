<?php

namespace App\Models\Usuarios;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $connection = 'usuarios';
}