# Módulo: Usuarios

**Base de datos**: `usuarios` (`DB_DATABASE_USUARIOS`)  
**Rutas**: `routes/usuarios.php`  
**Complejidad**: Media

---

## Qué hace

Es el módulo central del sistema. Gestiona todo lo relacionado con identidad, acceso y organización:

- Alta, baja y modificación de usuarios internos.
- Asignación de roles y permisos (Spatie).
- Definición de áreas (jerárquicas) y sedes.
- Control de qué módulos están activos en el sistema.
- Registro de logs de actividad (logins, acciones).
- Seguridad de contraseñas (expiración, historial).
- Panel de administración de jobs de email en cola.

---

## Modelos

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `User` | `users` | Usuario principal. Tiene roles, área, sede, soft-delete. |
| `Area` | `areas` | Área organizacional. Jerárquica (una área puede tener padre). |
| `Sede` | `sedes` | Sede/ubicación física. |
| `Modulo` | `modulos` | Módulos habilitados en el sistema. Campo `estado` controla si se cargan las rutas. |
| `Role` | `roles` | Roles de Spatie. Naming: `Modulo/NombreRol`. |
| `Permission` | `permissions` | Permisos de Spatie. |
| `Log` | `logs` | Log de actividad del usuario (logins, acciones). |
| `PasswordSecurity` | `password_securities` | Fecha de vencimiento de contraseña, historial hash. |

---

## User: campos clave

```
id, name, realname, nombre, apellido, email, legajo, interno (teléfono interno),
visible (si aparece en listados), area_id, sede_id, password, last_login,
profile_photo_path, two_factor_secret, two_factor_recovery_codes,
deleted_at (soft delete)
```

**Nota**: `name` es el username de sistema, `realname` / `nombre` / `apellido` son el nombre real. Esta inconsistencia es un artefacto histórico; los campos `nombre` y `apellido` son los que se usan en la UI.

---

## Roles y permisos

Los roles siguen la convención `Modulo/Rol`. Ejemplos reales:
- `Plataforma/Admin` — superadmin, bypasea todas las restricciones
- `Proveedores/Acceso` — acceso básico al módulo de proveedores
- `Concursos/Admin` — administración de concursos
- `Inventario/ABM` — alta/baja/modificación en inventario

El método `User::getSistemasAcceso()` devuelve la lista de módulos a los que el usuario tiene acceso (extrae el prefijo de cada rol).

El método `User::isSuperAdmin()` verifica si tiene el rol `Plataforma/Admin`.

---

## Componentes Livewire

| Componente | Ubicación | Función |
|-----------|-----------|---------|
| `Users/Index` | `app/Livewire/Usuarios/Users/` | Listado y gestión de usuarios |
| `Areas/Index` | `app/Livewire/Usuarios/Areas/` | Gestión de áreas |
| `Modulos/Index` | `app/Livewire/Usuarios/Modulos/` | Gestión de módulos activos |

---

## Seguridad de contraseñas

El middleware `PasswordExpiryCheck` verifica en cada request si la contraseña del usuario expiró (según la fecha guardada en `password_securities`). Si expiró, redirige al formulario de cambio de contraseña antes de dejar pasar la request.

---

## Logs de actividad

La tabla `logs` registra eventos del usuario (login, logout, acciones relevantes). El modelo `Log` está en `App\Models\Usuarios\Log`. El accessor `User::getLastAccessAttribute()` devuelve el último login.

---

## Relaciones con otros módulos

`User` es el modelo más referenciado del sistema. Todos los demás módulos lo usan para:
- Relacionar tickets, documentos, inventario, capacitaciones, etc. con el usuario que los creó o tiene asignado.
- La relación se hace via `user_id` (FK en la tabla del módulo) pero no hay FK a nivel de base de datos porque son bases distintas.

---

## Puntos a mejorar

- La tabla `users` tiene campos redundantes (`name`, `realname`, `nombre`, `apellido`) que deberían consolidarse.
- No hay política formal de nomenclatura de roles; depende de que quien los crea siga la convención `Modulo/Rol` manualmente.
- El panel de gestión de jobs de email (ManagedJob) quedó en este módulo por conveniencia pero conceptualmente es transversal.
