# 🎯 Sidebar Dinámico - Guía de Uso

## 📋 Resumen

El nuevo sistema de sidebar permite:
- ✅ **Configuración centralizada** en `config/sidebar.php`
- ✅ **Fácil adición** de nuevos módulos
- ✅ **Indicadores visuales** de mantenimiento configurables
- ✅ **Gestión automática** de permisos y estados
- ✅ **Múltiples estilos** de indicadores

## 🚀 Activación

Para usar el nuevo sidebar, reemplaza la inclusión en tu layout:

```blade
{{-- Antes --}}
@include('layouts.partials.sidebar-navigation')

{{-- Después --}}
@include('layouts.partials.sidebar-navigation-new')
```

## 🔧 Agregar un Nuevo Módulo

### **Paso 1: Editar config/sidebar.php**

#### **Módulo con Submenú:**
```php
'mi_nuevo_modulo' => [
    'name' => 'Mi Módulo',
    'type' => 'dropdown',
    'permission_check' => ['MiModulo/Ver'],
    'order' => 100,
    'submenu' => [
        [
            'name' => 'Lista Principal',
            'route' => 'mi_modulo.index',
            'permission' => 'MiModulo/Ver',
            'active_when' => 'mi_modulo.*'
        ],
        [
            'name' => 'Configuración',
            'route' => 'mi_modulo.config',
            'permission' => 'MiModulo/Configurar',
            'active_when' => 'mi_modulo.config.*'
        ]
    ]
],
```

#### **Módulo de Enlace Directo:**
```php
'reportes' => [
    'name' => 'Reportes',
    'type' => 'link',
    'permission_check' => ['Reportes/Ver'],
    'route' => 'reportes.index',
    'active_when' => 'reportes.*',
    'order' => 110
],
```

### **Paso 2: Crear el Módulo en la Base de Datos**

```sql
INSERT INTO usuarios.modulos (nombre, descripcion, estado, created_at, updated_at) 
VALUES ('MiModulo', 'Descripción del módulo', 'activo', NOW(), NOW());
```

### **Paso 3: Configurar Permisos**

Asegúrate de que existan los permisos correspondientes en la tabla `permissions`.

## 🎨 Configuración de Indicadores

### **Estilos Disponibles:**

#### **1. Dot (Punto pulsante) - Por defecto**
```php
'maintenance_indicator' => [
    'style' => 'dot',
    'position' => 'right'
]
```
**Resultado:** `● Módulo` (punto rojo pulsante)

#### **2. Badge (Insignia)**
```php
'maintenance_indicator' => [
    'style' => 'badge',
    'position' => 'right'
]
```
**Resultado:** `Módulo !` (insignia roja con exclamación)

#### **3. Icon (Icono)**
```php
'maintenance_indicator' => [
    'style' => 'icon',
    'position' => 'right'
]
```
**Resultado:** `Módulo ⚠️` (icono de advertencia)

### **Cambio Rápido con Artisan:**

```bash
# Cambiar a punto pulsante
php artisan sidebar:maintenance-style dot

# Cambiar a insignia
php artisan sidebar:maintenance-style badge

# Cambiar a icono
php artisan sidebar:maintenance-style icon
```

## ⚙️ Opciones Avanzadas

### **Lógica Personalizada para Elementos Activos:**

```php
'active_when' => function() {
    return request()->routeIs('concursos.concursos.index') || 
           (request()->routeIs('concursos.concursos.show') && 
            request()->route('concurso')?->estado_id < 3);
}
```

### **Elementos de Solo Acción (No se marcan como activos):**

```php
[
    'name' => 'Exportar Excel',
    'route' => 'modulo.export',
    'permission' => 'Modulo/Exportar',
    'type' => 'action' // No se marca como activo
]
```

### **Exclusiones de Rutas:**

```php
[
    'name' => 'Elementos',
    'route' => 'modulo.index',
    'permission' => 'Modulo/Ver',
    'active_when' => 'modulo.*',
    'exclude_when' => 'modulo.eliminados' // No activo en esta ruta
]
```

### **Nombre Específico del Módulo en BD:**

```php
'adminip' => [
    'name' => 'Admin IP',
    'module_name' => 'AdminIP', // Nombre específico en la BD
    // ... resto de la configuración
]
```

## 🔍 Servicios Disponibles

### **SidebarService:**

```php
use App\Services\SidebarService;

$sidebar = app(SidebarService::class);

// Obtener módulos visibles
$modules = $sidebar->getVisibleModules();

// Verificar si está en mantenimiento
$isInMaintenance = $sidebar->isInMaintenance('usuarios', $config);

// Obtener indicador de mantenimiento
$indicator = $sidebar->getMaintenanceIndicator('proveedores', $config);

// Verificar si ruta está activa
$isActive = $sidebar->isRouteActive('usuarios.users.*');
```

## 📊 Estructura del Archivo config/sidebar.php

```php
return [
    'modules' => [
        'clave_modulo' => [
            'name' => 'Nombre Visible',
            'type' => 'dropdown|link',
            'permission_check' => ['Permiso1', 'Permiso2'],
            'order' => 10, // Orden de aparición
            'module_name' => 'NombreEnBD', // Opcional
            'route' => 'ruta.index', // Solo para type='link'
            'active_when' => 'patron.*|function',
            'submenu' => [ // Solo para type='dropdown'
                [
                    'name' => 'Subitem',
                    'route' => 'ruta.subitem',
                    'permission' => 'Permiso',
                    'active_when' => 'patron.*',
                    'exclude_when' => 'patron.excluido',
                    'type' => 'action' // Opcional
                ]
            ]
        ]
    ],
    
    'maintenance_indicator' => [
        'enabled' => true,
        'style' => 'dot|badge|icon',
        'position' => 'right|left'
    ]
];
```

## 🎯 Ventajas del Sistema

### **✅ Para Desarrolladores:**
- 📝 **Configuración centralizada** - Un solo archivo para gestionar todo
- 🔄 **Fácil mantenimiento** - Agregar módulos sin tocar Blade
- 🎨 **Indicadores flexibles** - Múltiples estilos visuales
- 🛡️ **Gestión automática** de permisos

### **✅ Para el Sistema:**
- ⚡ **Mejor rendimiento** - Cache de consultas de módulos
- 🧹 **Código más limpio** - Lógica separada de la vista
- 🔧 **Extensible** - Fácil agregar nuevas funcionalidades
- 📱 **Responsive** - Se adapta automáticamente

## 🚨 Migración desde el Sistema Anterior

### **Paso 1: Backup**
```bash
cp resources/views/layouts/partials/sidebar-navigation.blade.php resources/views/layouts/partials/sidebar-navigation-backup.blade.php
```

### **Paso 2: Activar Nueva Versión**
```blade
{{-- En tu layout principal --}}
@include('layouts.partials.sidebar-navigation-new')
```

### **Paso 3: Probar y Ajustar**
- Verificar que todos los módulos aparezcan
- Comprobar permisos
- Probar indicadores de mantenimiento

### **Paso 4: Limpiar (Opcional)**
Una vez confirmado que funciona, puedes eliminar:
- `sidebar-navigation.blade.php` (versión anterior)
- `sidebar-navigation-backup.blade.php`

## 💡 Tips y Mejores Prácticas

### **🎯 Nombrado Consistente:**
- Usa nombres en minúsculas para las claves
- Mantén consistencia con los nombres de rutas
- Documenta módulos especiales

### **🔢 Orden Lógico:**
- Usa incrementos de 10 para el orden (10, 20, 30...)
- Permite espacio para insertar módulos entre otros
- Agrupa módulos relacionados

### **🛡️ Permisos Granulares:**
- Define permisos específicos para cada acción
- Usa el patrón `Modulo/Accion/Operacion`
- Documenta los permisos requeridos

### **⚡ Rendimiento:**
- El servicio cachea las consultas de módulos
- Evita lógica compleja en las funciones `active_when`
- Usa `Route::has()` para verificar existencia de rutas

---

*Documento creado: [Fecha actual]*  
*Versión: 1.0*  
*Sistema: Sidebar Dinámico* 