# Administración de Usuarios Externos (Portal Proveedores)

**Fecha:** 2026-05-22  
**Área:** Módulo de Proveedores / Usuarios Externos  

---

## El problema

Los proveedores registrados en el sistema pueden acceder a un portal externo (Laravel separado). Al registrarse allí se crea un usuario usando el CUIT como username. El reclamo más frecuente era que los proveedores olvidaban su contraseña y no había ninguna pantalla en el panel de administración para blanquearla manualmente: había que acceder directamente a la base de datos.

---

## Lo que se hizo

### Nueva pantalla: Usuarios Externos

**`app/Livewire/Proveedores/Externos/Index.php`**

Componente Livewire que lista los usuarios de la base de datos del portal externo (`proveedores_externos`). Incluye:
- Búsqueda en tiempo real por CUIT/username o correo electrónico
- Filtro "Solo sin proveedor vinculado" (filtra en PHP para evitar subqueries cross-database, ya que `ProveedorExterno` y `Proveedor` están en conexiones distintas)
- Paginación manual con `LengthAwarePaginator` (25 por página)
- Lógica de reset de contraseña en dos pasos: ingreso de contraseña provisoria → confirmación → hash y guardado con `must_change_password = true`

**`resources/views/livewire/proveedores/externos/index.blade.php`**

Vista del componente. Tabla con columnas: CUIT/username, correo, fecha de registro, último acceso, estado de cambio de contraseña (Pendiente/OK), proveedor interno vinculado (con link al perfil). Modal de blanqueo con doble confirmación. ESC y click en fondo oscuro cierran el modal.

**`resources/views/proveedores/externos/index.blade.php`**

Página principal con `x-app-layout` y `x-page-header`.

**`routes/proveedores.php`**

Nueva ruta `GET proveedores/externos` → `proveedores.externos.index`, bajo el middleware `role:Proveedores/Acceso`.

**`config/sidebar.php`**

Entrada en el submenú de Proveedores con permiso `Proveedores/Externos/Ver`.

---

## Archivos modificados / creados

| Archivo | Tipo |
|---------|------|
| `app/Livewire/Proveedores/Externos/Index.php` | nuevo |
| `resources/views/livewire/proveedores/externos/index.blade.php` | nuevo |
| `resources/views/proveedores/externos/index.blade.php` | nuevo |
| `routes/proveedores.php` | modificado |
| `config/sidebar.php` | modificado |

---

## Notas de implementación

- El modelo `ProveedorExterno` usa la conexión `proveedores_externos` y apunta a la tabla `users` del portal externo. La relación `proveedorInterno()` usa `username` ↔ `cuit`.
- El eager loading cross-database (`with('proveedorInterno')`) funciona porque cada modelo resuelve su propia conexión; no se genera un JOIN entre bases.
- El filtro "sin vinculación" no usa `whereDoesntHave` para evitar una subquery cross-DB que requeriría permisos especiales entre esquemas. Se filtra sobre la colección en memoria.
- Al blanquear la contraseña se setea `must_change_password = true`, forzando al proveedor a cambiarla en el próximo login (comportamiento nativo del portal externo).

---

## Pendiente / no incluido

- No se creó el permiso `Proveedores/Externos/Ver` en la base de datos; debe asignarse manualmente desde el módulo de Usuarios.
- No hay acción para desbloquear usuarios con `locked_until` activo (bloqueados por intentos fallidos). Puede sumarse en una segunda etapa.
