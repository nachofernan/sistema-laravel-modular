<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Configuración del Sidebar
    |--------------------------------------------------------------------------
    |
    | Este archivo define la estructura del sidebar de navegación.
    | Permite agregar, modificar o quitar módulos de forma centralizada.
    |
    */

    'modules' => [
        
        // Módulos con submenú
        'usuarios' => [
            'name' => 'Usuarios',
            'type' => 'dropdown',
            'permission_check' => ['Usuarios/Usuarios/Ver', 'Usuarios/Areas/Ver', 'Usuarios/Sedes/Ver', 'Usuarios/Modulos/Ver'],
            'icon' => 'users', // Opcional para futuras mejoras
            'order' => 10,
            'submenu' => [
                [
                    'name' => 'Usuarios',
                    'route' => 'usuarios.users.index',
                    'permission' => 'Usuarios/Usuarios/Ver',
                    'active_when' => 'usuarios.users.*',
                    'exclude_when' => 'usuarios.users.trashed'
                ],
                [
                    'name' => 'Borrados',
                    'route' => 'usuarios.users.trashed',
                    'permission' => 'Usuarios/Usuarios/Eliminar',
                    'active_when' => 'usuarios.users.trashed'
                ],
                [
                    'name' => 'Areas',
                    'route' => 'usuarios.areas.index',
                    'permission' => 'Usuarios/Areas/Ver',
                    'active_when' => 'usuarios.areas.*'
                ],
                [
                    'name' => 'Sedes',
                    'route' => 'usuarios.sedes.index',
                    'permission' => 'Usuarios/Sedes/Ver',
                    'active_when' => 'usuarios.sedes.*'
                ],
                [
                    'name' => 'Módulos',
                    'route' => 'usuarios.modulos.index',
                    'permission' => 'Usuarios/Modulos/Ver',
                    'active_when' => 'usuarios.modulos.*'
                ],
                [
                    'name' => 'Cola de Correos',
                    'route' => 'usuarios.email-queue.index',
                    'permission' => 'Usuarios/Modulos/Ver',
                    'active_when' => 'usuarios.email-queue.*'
                ]
            ]
        ],

        'proveedores' => [
            'name' => 'Proveedores',
            'type' => 'dropdown',
            'permission_check' => ['Proveedores/Proveedores/Ver', 'Proveedores/DocumentoTipos/Ver', 'Proveedores/Rubros/Ver'],
            'order' => 20,
            'submenu' => [
                [
                    'name' => 'Proveedores',
                    'route' => 'proveedores.proveedors.index',
                    'permission' => 'Proveedores/Proveedores/Ver',
                    'active_when' => 'proveedores.proveedors.*',
                    'exclude_when' => 'proveedores.proveedors.eliminados'
                ],
                [
                    'name' => 'Exportar Excel',
                    'route' => 'proveedores.proveedors.export',
                    'permission' => 'Proveedores/Proveedores/Ver',
                    'type' => 'action' // No se marca como activo
                ],
                [
                    'name' => 'Eliminados',
                    'route' => 'proveedores.proveedors.eliminados',
                    'permission' => 'Proveedores/Proveedores/EditarEstado',
                    'active_when' => 'proveedores.proveedors.eliminados'
                ],
                [
                    'name' => 'Validaciones',
                    'route' => 'proveedores.validacions.index',
                    'permission' => 'Proveedores/Proveedores/EditarEstado',
                    'active_when' => 'proveedores.validacions.*'
                ],
                [
                    'name' => 'Tipos de Documentos',
                    'route' => 'proveedores.documento_tipos.index',
                    'permission' => 'Proveedores/DocumentoTipos/Ver',
                    'active_when' => 'proveedores.documento_tipos.*'
                ],
                [
                    'name' => 'Rubros y Subrubros',
                    'route' => 'proveedores.rubros.index',
                    'permission' => 'Proveedores/Rubros/Ver',
                    'active_when' => 'proveedores.rubros.*'
                ]
            ]
        ],

        'concursos' => [
            'name' => 'Concursos',
            'type' => 'dropdown',
            'permission_check' => ['Concursos/Concursos/Ver', 'Concursos/DocumentoTipos/Ver'],
            'order' => 30,
            'submenu' => [
                [
                    'name' => 'Concursos Activos',
                    'route' => 'concursos.concursos.index',
                    'permission' => 'Concursos/Concursos/Ver',
                    'active_when' => function() {
                        return request()->routeIs('concursos.concursos.index') || 
                               (request()->routeIs('concursos.concursos.show') && request()->route('concurso')?->estado_id < 3) ||
                               request()->routeIs('concursos.concursos.create');
                    }
                ],
                [
                    'name' => 'Calendario',
                    'route' => 'concursos.calendario',
                    'permission' => 'Concursos/Concursos/Ver',
                    'active_when' => 'concursos.calendario*'
                ],
                [
                    'name' => 'Concursos Terminados',
                    'route' => 'concursos.concursos.terminados',
                    'permission' => 'Concursos/Concursos/Ver',
                    'active_when' => function() {
                        return request()->routeIs('concursos.concursos.terminados') ||
                               (request()->routeIs('concursos.concursos.show') && request()->route('concurso')?->estado_id >= 3);
                    }
                ],
                [
                    'name' => 'Tipos de Documentos',
                    'route' => 'concursos.documento_tipos.index',
                    'permission' => 'Concursos/DocumentoTipos/Ver',
                    'active_when' => 'concursos.documento_tipos.*'
                ]
            ]
        ],

        'inventario' => [
            'name' => 'Inventario',
            'type' => 'dropdown',
            'permission_check' => ['Inventario/Elementos/Ver', 'Inventario/Categorias/Ver', 'Inventario/Usuarios/Ver', 'Inventario/Perifericos/Ver'],
            'order' => 40,
            'submenu' => [
                [
                    'name' => 'Inventario',
                    'route' => 'inventario.elementos.index',
                    'permission' => 'Inventario/Elementos/Ver',
                    'active_when' => 'inventario.elementos.*'
                ],
                [
                    'name' => 'Categorias',
                    'route' => 'inventario.categorias.index',
                    'permission' => 'Inventario/Categorias/Ver',
                    'active_when' => 'inventario.categorias.*'
                ],
                [
                    'name' => 'Usuarios',
                    'route' => 'inventario.users.index',
                    'permission' => 'Inventario/Usuarios/Ver',
                    'active_when' => 'inventario.users.*'
                ],
                [
                    'name' => 'Periféricos',
                    'route' => 'inventario.perifericos.index',
                    'permission' => 'Inventario/Perifericos/Ver',
                    'active_when' => 'inventario.perifericos.*'
                ]
            ]
        ],

        'documentos' => [
            'name' => 'Documentos',
            'type' => 'dropdown',
            'permission_check' => ['Documentos/Documentos/Ver', 'Documentos/Categorias/Ver'],
            'order' => 50,
            'submenu' => [
                [
                    'name' => 'Documentos',
                    'route' => 'documentos.documentos.index',
                    'permission' => 'Documentos/Documentos/Ver',
                    'active_when' => 'documentos.documentos.*'
                ],
                [
                    'name' => 'Categorias',
                    'route' => 'documentos.categorias.index',
                    'permission' => 'Documentos/Categorias/Ver',
                    'active_when' => 'documentos.categorias.*'
                ]
            ]
        ],

        // Módulos de enlace directo
        'tickets' => [
            'name' => 'Tickets',
            'type' => 'link',
            'permission_check' => ['Tickets/Tickets/Ver'],
            'route' => 'tickets.tickets.index',
            'active_when' => 'tickets.tickets.*',
            'order' => 60
        ],

        'capacitaciones' => [
            'name' => 'Capacitaciones',
            'type' => 'link',
            'permission_check' => ['Capacitaciones/Capacitaciones/Ver'],
            'route' => 'capacitaciones.capacitacions.index',
            'active_when' => 'capacitaciones.capacitacions.*',
            'order' => 70
        ],

        'fichadas' => [
            'name' => 'Fichadas',
            'type' => 'link',
            'permission_check' => ['Fichadas/Fichadas/Ver'],
            'route' => 'fichadas.fichadas.index',
            'active_when' => 'fichadas.fichadas.*',
            'order' => 80
        ],

        'adminip' => [
            'name' => 'Admin IP',
            'type' => 'link',
            'permission_check' => ['AdminIP/IPS/Ver'],
            'route' => 'adminip.ips.index',
            'active_when' => 'adminip.ips.*',
            'order' => 90,
            'module_name' => 'AdminIP' // Nombre específico en BD si difiere
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Indicadores de Estado
    |--------------------------------------------------------------------------
    */
    
    'maintenance_indicator' => [
        'enabled' => true,
        'style' => 'icon', // 'dot', 'badge', 'icon'
        'position' => 'right' // 'right', 'left'
    ]
]; 