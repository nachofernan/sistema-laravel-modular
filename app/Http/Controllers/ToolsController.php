<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Usuarios\Area;
use App\Models\Usuarios\Sede;
use Illuminate\Support\Facades\DB;

class ToolsController extends Controller
{
    /**
     * Herramientas disponibles para la IA
     */
    public static function getAvailableTools()
    {
        return [
            [
                'name' => 'buscar_usuario_por_nombre',
                'description' => 'Busca usuarios por nombre completo o parcial, soportando tanto formato {apellido nombre} como {nombre apellido}',
                'parameters' => [
                    'nombre' => 'string - nombre completo o parcial del usuario (puede ser nombre, apellido o ambos)'
                ]
            ],
            [
                'name' => 'buscar_usuario_por_legajo',
                'description' => 'Busca un usuario específico por su número de legajo',
                'parameters' => [
                    'legajo' => 'string|int - número de legajo del empleado'
                ]
            ],
            [
                'name' => 'buscar_usuarios_por_area',
                'description' => 'Busca todos los usuarios de un área específica',
                'parameters' => [
                    'area' => 'string - nombre del área (ej: sistemas, ventas, auditoria)'
                ]
            ],
            [
                'name' => 'buscar_usuarios_por_sede',
                'description' => 'Busca todos los usuarios de una sede específica',
                'parameters' => [
                    'sede' => 'string - nombre de la sede'
                ]
            ],
            [
                'name' => 'buscar_interno_por_nombre',
                'description' => 'Busca el número interno telefónico de un empleado por su nombre',
                'parameters' => [
                    'nombre' => 'string - nombre completo o parcial del empleado'
                ]
            ],
            [
                'name' => 'listar_areas',
                'description' => 'Lista todas las áreas disponibles en la empresa',
                'parameters' => []
            ],
            [
                'name' => 'listar_sedes',
                'description' => 'Lista todas las sedes disponibles en la empresa',
                'parameters' => []
            ],
            [
                'name' => 'obtener_estructura_area',
                'description' => 'Obtiene la estructura jerárquica de un área (padre e hijos)',
                'parameters' => [
                    'area' => 'string - nombre del área'
                ]
            ],
            [
                'name' => 'buscar_usuarios_con_roles', // Revisar sólo para administradores
                'description' => 'Busca usuarios que tienen roles específicos en el sistema',
                'parameters' => [
                    'rol' => 'string - nombre del rol (opcional)',
                    'sistema' => 'string - sistema específico (opcional)'
                ]
            ],
            [
                'name' => 'estadisticas_usuarios',
                'description' => 'Obtiene estadísticas generales de usuarios (total por área, sede, etc.)',
                'parameters' => []
            ]
        ];
    }

    /**
     * Ejecuta una herramienta específica
     */
    public function executeTool(Request $request)
    {
        $toolName = $request->input('tool');
        $parameters = $request->input('parameters', []);
        try {
            switch ($toolName) {
                case 'buscar_usuario_por_nombre':
                    return $this->buscarUsuarioPorNombre($parameters);
                
                case 'buscar_usuario_por_legajo':
                    return $this->buscarUsuarioPorLegajo($parameters);
                
                case 'buscar_usuarios_por_area':
                    return $this->buscarUsuariosPorArea($parameters);
                
                case 'buscar_usuarios_por_sede':
                    return $this->buscarUsuariosPorSede($parameters);
                
                case 'buscar_interno_por_nombre':
                    return $this->buscarInternoPorNombre($parameters);
                
                case 'listar_areas':
                    return $this->listarAreas();
                
                case 'listar_sedes':
                    return $this->listarSedes();
                
                case 'obtener_estructura_area':
                    return $this->obtenerEstructuraArea($parameters);
                
                case 'buscar_usuarios_con_roles':
                    return $this->buscarUsuariosConRoles($parameters);
                
                case 'estadisticas_usuarios':
                    return $this->estadisticasUsuarios();
                
                default:
                    return response()->json(['error' => 'Herramienta no encontrada'], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Busca usuarios por nombre
     */
    public function buscarUsuarioPorNombre($params)
    {
        $nombre = trim($params['nombre'] ?? '');
        
        if (empty($nombre)) {
            return response()->json(['error' => 'Nombre requerido'], 400);
        }

        $users = User::where(function ($query) use ($nombre) {
            // Búsqueda simple por coincidencia exacta o parcial
            $query->where('name', 'like', "%{$nombre}%")
                  ->orWhere('realname', 'like', "%{$nombre}%");

            // Dividir el input en palabras para manejar nombre y apellido
            $palabras = explode(' ', $nombre);
            if (count($palabras) >= 2) {
                $nombre1 = $palabras[0];
                $nombre2 = implode(' ', array_slice($palabras, 1));

                // Buscar en orden {nombre apellido} o {apellido nombre}
                $query->orWhere(function ($subQuery) use ($nombre1, $nombre2) {
                    $subQuery->where('realname', 'like', "%{$nombre1}%{$nombre2}%")
                             ->orWhere('realname', 'like', "%{$nombre2}%{$nombre1}%");
                });
            }
        })
        ->with(['area', 'sede'])
        ->whereNull('deleted_at')
        ->get()
        ->map(function($user) {
            return [
                'id' => $user->id,
                'nombre' => $user->name,
                'nombre_real' => $user->realname,
                'email' => $user->email,
                'legajo' => $user->legajo,
                'interno' => $user->interno ?? 'Sin interno asignado',
                'area' => $user->area->nombre ?? 'Sin área asignada',
                'sede' => $user->sede->nombre ?? 'Sin sede asignada',
                //'visible' => $user->visible ? 'Sí' : 'No'
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $users,
            'total' => $users->count(),
            'message' => $users->count() > 0 ? 'Usuarios encontrados' : 'No se encontraron usuarios con ese nombre'
        ]);
    }

    /**
     * Busca usuario por legajo
     */
    public function buscarUsuarioPorLegajo($params)
    {
        $legajo = $params['legajo'] ?? '';
        
        if (empty($legajo)) {
            return response()->json(['error' => 'Legajo requerido'], 400);
        }

        $user = User::where('legajo', $legajo)
                    ->with(['area', 'sede'])
                    ->whereNull('deleted_at')
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró usuario con ese legajo'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'nombre' => $user->name,
                'nombre_real' => $user->realname,
                'email' => $user->email,
                'legajo' => $user->legajo,
                'interno' => $user->interno ?? 'Sin interno asignado',
                'area' => $user->area->nombre ?? 'Sin área asignada',
                'sede' => $user->sede->nombre ?? 'Sin sede asignada',
                //'visible' => $user->visible ? 'Sí' : 'No',
                //'roles' => method_exists($user, 'getRoleNames') ? $user->getRoleNames()->toArray() : []
            ]
        ]);
    }

    /**
     * Busca usuarios por área
     */
    public function buscarUsuariosPorArea($params)
    {
        $area = $params['area'] ?? '';
        
        if (empty($area)) {
            return response()->json(['error' => 'Área requerida'], 400);
        }

        $users = User::whereHas('area', function($query) use ($area) {
                        $query->where('nombre', 'like', "%{$area}%");
                     })
                     ->with(['area', 'sede'])
                     ->whereNull('deleted_at')
                     ->get()
                     ->map(function($user) {
                         return [
                             'nombre' => $user->realname,
                             'email' => $user->email,
                             'legajo' => $user->legajo,
                             'interno' => $user->interno ?? 'Sin interno',
                             'area' => $user->area->nombre,
                             'sede' => $user->sede->nombre ?? 'Sin sede'
                         ];
                     });

        return response()->json([
            'success' => true,
            'data' => $users,
            'total' => $users->count(),
            'message' => $users->count() > 0 ? "Se encontraron {$users->count()} usuarios en el área" : 'No se encontraron usuarios en esa área'
        ]);
    }

    /**
     * Busca usuarios por sede
     */
    public function buscarUsuariosPorSede($params)
    {
        $sede = $params['sede'] ?? '';
        
        if (empty($sede)) {
            return response()->json(['error' => 'Sede requerida'], 400);
        }

        $users = User::whereHas('sede', function($query) use ($sede) {
                        $query->where('nombre', 'like', "%{$sede}%");
                     })
                     ->with(['area', 'sede'])
                     ->whereNull('deleted_at')
                     ->get()
                     ->map(function($user) {
                         return [
                             'nombre' => $user->name,
                             'email' => $user->email,
                             'legajo' => $user->legajo,
                             'interno' => $user->interno ?? 'Sin interno',
                             'area' => $user->area->nombre ?? 'Sin área',
                             'sede' => $user->sede->nombre
                         ];
                     });

        return response()->json([
            'success' => true,
            'data' => $users,
            'total' => $users->count(),
            'message' => $users->count() > 0 ? "Se encontraron {$users->count()} usuarios en la sede" : 'No se encontraron usuarios en esa sede'
        ]);
    }

    /**
     * Busca interno por nombre
     */
    public function buscarInternoPorNombre($params)
    {
        $nombre = trim($params['nombre'] ?? '');
        
        if (empty($nombre)) {
            return response()->json(['error' => 'Nombre requerido'], 400);
        }

        $users = User::where(function($query) use ($nombre) {
            // Búsqueda simple por coincidencia exacta o parcial
            $query->where('name', 'like', "%{$nombre}%")
                  ->orWhere('realname', 'like', "%{$nombre}%");

            // Dividir el input en palabras para manejar nombre y apellido
            $palabras = explode(' ', $nombre);
            if (count($palabras) >= 2) {
                $nombre1 = $palabras[0];
                $nombre2 = implode(' ', array_slice($palabras, 1));

                // Buscar en orden {nombre apellido} o {apellido nombre}
                $query->orWhere(function ($subQuery) use ($nombre1, $nombre2) {
                    $subQuery->where('realname', 'like', "%{$nombre1}%{$nombre2}%")
                             ->orWhere('realname', 'like', "%{$nombre2}%{$nombre1}%");
                });
            }
        })
        ->whereNotNull('interno')
        ->whereNull('deleted_at')
        ->with('area')
        ->get()
        ->map(function($user) {
            return [
                'nombre' => $user->name,
                'interno' => $user->interno,
                'email' => $user->email,
                'area' => $user->area->nombre ?? 'Sin área'
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $users,
            'total' => $users->count(),
            'message' => $users->count() > 0 ? 'Internos encontrados' : 'No se encontraron internos para ese nombre'
        ]);
    }

    /**
     * Lista todas las áreas
     */
    public function listarAreas()
    {
        $areas = Area::select('id', 'nombre', 'area_id')
                     ->with('padre:id,nombre')
                     ->orderBy('nombre')
                     ->get()
                     ->map(function($area) {
                         return [
                             'id' => $area->id,
                             'nombre' => $area->nombre,
                             'area_padre' => $area->padre->nombre ?? 'Área principal',
                             'usuarios_count' => $area->users()->count()
                         ];
                     });

        return response()->json([
            'success' => true,
            'data' => $areas,
            'total' => $areas->count()
        ]);
    }

    /**
     * Lista todas las sedes
     */
    public function listarSedes()
    {
        $sedes = Sede::select('id', 'nombre')
                     ->orderBy('nombre')
                     ->get()
                     ->map(function($sede) {
                         return [
                             'id' => $sede->id,
                             'nombre' => $sede->nombre,
                             'usuarios_count' => $sede->users()->count()
                         ];
                     });

        return response()->json([
            'success' => true,
            'data' => $sedes,
            'total' => $sedes->count()
        ]);
    }

    /**
     * Obtiene estructura jerárquica de un área
     */
    public function obtenerEstructuraArea($params)
    {
        $area = $params['area'] ?? '';
        
        if (empty($area)) {
            return response()->json(['error' => 'Área requerida'], 400);
        }

        $areaModel = Area::where('nombre', 'like', "%{$area}%")
                         ->with(['padre', 'hijos'])
                         ->first();

        if (!$areaModel) {
            return response()->json(['error' => 'Área no encontrada'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'area' => [
                    'id' => $areaModel->id,
                    'nombre' => $areaModel->nombre
                ],
                'area_padre' => $areaModel->padre ? [
                    'id' => $areaModel->padre->id,
                    'nombre' => $areaModel->padre->nombre
                ] : null,
                'areas_hijas' => $areaModel->hijos->map(function($hijo) {
                    return [
                        'id' => $hijo->id,
                        'nombre' => $hijo->nombre,
                        'usuarios_count' => $hijo->users()->count()
                    ];
                }),
                'usuarios_count' => $areaModel->users()->count()
            ]
        ]);
    }

    /**
     * Busca usuarios con roles específicos
     */
    public function buscarUsuariosConRoles($params)
    {
        $rol = $params['rol'] ?? null;
        $sistema = $params['sistema'] ?? null;

        $query = User::with(['area', 'sede'])
                     ->whereNull('deleted_at');

        if ($rol) {
            $query->whereHas('roles', function($q) use ($rol) {
                $q->where('name', 'like', "%{$rol}%");
            });
        }

        $users = $query->get()->map(function($user) use ($sistema) {
            $roles = method_exists($user, 'getRoleNames') ? $user->getRoleNames()->toArray() : [];
            
            if ($sistema) {
                $roles = array_filter($roles, function($role) use ($sistema) {
                    return strpos($role, $sistema) !== false;
                });
            }

            if (empty($roles) && ($sistema)) {
                return null;
            }

            return [
                'nombre' => $user->name,
                'email' => $user->email,
                'legajo' => $user->legajo,
                'area' => $user->area->nombre ?? 'Sin área',
                'sede' => $user->sede->nombre ?? 'Sin sede',
                'roles' => $roles
            ];
        })->filter();

        return response()->json([
            'success' => true,
            'data' => $users->values(),
            'total' => $users->count()
        ]);
    }

    /**
     * Obtiene estadísticas generales
     */
    public function estadisticasUsuarios()
    {
        $totalUsuarios = User::whereNull('deleted_at')->count();
        $usuariosConInterno = User::whereNotNull('interno')->whereNull('deleted_at')->count();
        
        $usuariosPorArea = Area::with('users')
                               ->get()
                               ->map(function($area) {
                                   return [
                                       'area' => $area->nombre,
                                       'usuarios' => $area->users()->whereNull('deleted_at')->count()
                                   ];
                               })
                               ->where('usuarios', '>', 0)
                               ->values();

        $usuariosPorSede = Sede::with('users')
                               ->get()
                               ->map(function($sede) {
                                   return [
                                       'sede' => $sede->nombre,
                                       'usuarios' => $sede->users()->whereNull('deleted_at')->count()
                                   ];
                               })
                               ->where('usuarios', '>', 0)
                               ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'resumen' => [
                    'total_usuarios' => $totalUsuarios,
                    'usuarios_con_interno' => $usuariosConInterno,
                    'usuarios_sin_interno' => $totalUsuarios - $usuariosConInterno,
                    'total_areas' => Area::count(),
                    'total_sedes' => Sede::count()
                ],
                'usuarios_por_area' => $usuariosPorArea,
                'usuarios_por_sede' => $usuariosPorSede
            ]
        ]);
    }
}