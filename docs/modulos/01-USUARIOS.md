# Módulo: Usuarios

**Base de datos**: `usuarios` (`DB_DATABASE_USUARIOS`)  
**Rutas**: `routes/usuarios.php`  
**Complejidad**: Media

---

## Qué hace

Es el módulo central del sistema. Gestiona todo lo relacionado con identidad, acceso y organización:

- Alta, baja y modificación de usuarios internos.
- Asignación de roles y permisos (Spatie).
- Organigrama: áreas jerárquicas tipificadas (gerencia, sector, etc.), con responsable y personal asignado; cargo de cada usuario.
- Definición de sedes.
- Control de qué módulos están activos en el sistema.
- Registro de logs de actividad (logins, acciones).
- Seguridad de contraseñas (expiración, historial).
- Panel de administración de jobs de email en cola.

---

## Modelos

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `User` | `users` | Usuario principal. Tiene roles, área, sede, cargo, soft-delete. |
| `Area` | `areas` | Área organizacional. Jerárquica (padre/hijos), tipificada, con responsable, orden y estado activo. |
| `TipoArea` | `tipos_area` | Catálogo de tipos de área (Gerencia, Subgerencia, Departamento, Sector…). |
| `Cargo` | `cargos` | Catálogo de cargos/puestos (Gerente, Asistente, Analista…). |
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
visible (si aparece en listados), area_id, sede_id, cargo_id, password, last_login,
profile_photo_path, two_factor_secret, two_factor_recovery_codes,
deleted_at (soft delete)
```

**Nota**: `name` es el username de sistema, `realname` / `nombre` / `apellido` son el nombre real. Esta inconsistencia es un artefacto histórico. El accessor `User::nombreCompleto` unifica el criterio de visualización: prioriza `realname`, cae a `apellido, nombre` y por último a `name`.

**Histórico**: existía un campo libre `puesto` (string) que nunca se llegó a usar (no era `fillable`, siempre vacío). Fue reemplazado por `cargo_id` (catálogo `Cargo`) y eliminado del esquema.

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

## Organigrama (áreas, tipos y cargos)

Las áreas modelan el organigrama de BAESA. La estructura es un árbol uniforme (`area_id` autoreferencial: `padre`/`hijos`), y cada nodo se **anota** con semántica en vez de tener estructuras distintas por nivel:

- **`tipo_area_id`** → `TipoArea`: qué es el nodo (Gerencia, Sector, etc.). Catálogo editable en `/usuarios/tipos-area`.
- **`responsable_id`** → `User`: quién encabeza el área. **Debe ser un miembro del área** (validado en el server y en la UI).
- **`orden`**: ordena las áreas hermanas en el árbol (`Area::hijos` ordena por este campo).
- **`activa`**: baja lógica sin perder el histórico.

El **cargo** de cada persona vive en `User::cargo_id` → `Cargo` (catálogo en `/usuarios/cargos`). Distingue a la gerenta de sus asistentes dentro de una misma área. **Importante**: cargo/responsable son datos organizacionales, distintos de los roles Spatie (que son permisos de sistema). No se mezclan.

`Area::descendantIds()` devuelve toda la descendencia de un área; se usa para impedir que un área tome como padre a sí misma o a un descendiente (evita ciclos que colgarían el render del árbol). Esa guarda está en `AreaController::update` y en el select de padre (`ForeachSelect`, que deshabilita el subárbol del área editada).

**Rutas y controllers**: `AreaController` (resource), `TipoAreaController` y `CargoController` (catálogos con ABM inline). Los catálogos reutilizan los permisos `Usuarios/Areas/*` y `Usuarios/Usuarios/*` respectivamente.

---

## Componentes Livewire

| Componente | Ubicación | Función |
|-----------|-----------|---------|
| `Users/Index` | `app/Livewire/Usuarios/Users/` | Listado y gestión de usuarios |
| `Areas/ForeachTableTr` | `app/Livewire/Usuarios/Areas/` | Render recursivo del árbol de áreas (estilo explorador de archivos, con líneas guía y tipo/responsable) |
| `Areas/ForeachSelect` | `app/Livewire/Usuarios/Areas/` | Select recursivo de área padre (deshabilita el subárbol del área editada para evitar ciclos) |
| `Areas/Miembros` | `app/Livewire/Usuarios/Areas/` | Panel en la edición de área: lista/agrega/quita personal y define el responsable (modal con buscador) |
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
- `destroy()` está vacío (sin implementar) en `AreaController`, `SedeController`, `ModuloController`, `PermissionController` y `RoleController`; `show()` también vacío en `RoleController`, `PermissionController`, `AreaController` y `SedeController`. Son resource controllers generados con el scaffold de Laravel donde nunca se implementó borrado/detalle para esos recursos — no hay forma de eliminar un área, sede, módulo, permiso o rol desde la UI. Distinto de `User`, que sí tiene borrado (soft-delete) pero implementado en el componente Livewire `EliminarModal`, no en `UserController::destroy()` (que también está vacío y sin uso real).

## Bug conocido: los toggles del panel de email no persisten

`EmailQueueController::toggleEnvios()` y `toggleFiltroDominio()` (rutas `POST /usuarios/email-queue/toggle-envios` y `POST .../filtro-dominio/toggle`) hacen `config(['mail.automated_sending_enabled' => $valor])` / `config(['mail.domain_filter_enabled' => $valor])`. Ese cambio solo vive en la memoria del proceso PHP que atiende esa request AJAX puntual — termina apenas se devuelve la respuesta JSON.

Ni el worker de la cola (`php artisan queue:work`, invocado manualmente vía el botón "Ejecutar cola" en `routes/usuarios.php`, que corre `Artisan::call('queue:work', ['--stop-when-empty' => true])` en un proceso nuevo) ni el siguiente request web ven ese cambio: ambos vuelven a leer `config('mail.automated_sending_enabled')` / `config('mail.domain_filter_enabled')` desde cero, tomando el valor real de `.env` (cacheado o no). No hay scheduler corriendo `queue:work` en background (`routes/console.php` solo define el comando `inspire` de ejemplo de Laravel) — el procesamiento de la cola de emails depende de que alguien entre al panel y apriete "Ejecutar cola".

**Consecuencia práctica**: el botón "Habilitar/Deshabilitar envíos automáticos" y el de "Filtro de dominio" en el panel de admin (`/usuarios/email-queue`) no hacen nada persistente. Un admin puede creer que apagó los envíos y en realidad el próximo email (o la próxima corrida manual de la cola) sale igual, porque el control real sigue siendo la variable en `.env`.

**Fix pendiente** (no implementado todavía, requiere decisión de diseño): reemplazar `config([...])` en tiempo de ejecución por algo que persista entre procesos — un valor en `Cache` (con el driver de cache configurado, que si es `file`/`database`/`redis` sí persiste entre procesos, a diferencia de `config()`) o una fila en una tabla de configuración, leído por `EnviarCorreoAutomatizado::handle()` y `EmailDomainValidatorService` en lugar de (o además de) `config()`. Ver también `docs/EMAIL_SYSTEM.md`.
