# 2026-11-10 — Historial de cambios de usuarios y log de accesos

## Qué se pidió
Guardar el historial de cambios de los usuarios: quién lo creó, quién lo editó, qué campo cambió
de qué valor a qué valor y qué roles se agregaron o quitaron. Hasta ahora solo se registraban
logins y cambios de contraseña, y con huecos.

## Qué se hizo

1. **activitylog** — `spatie/laravel-activitylog` 4.12 (v5 pide PHP 8.4; el entorno corre 8.3).
   `config/activitylog.php` con conexión fija `mysql` (base general `plataforma`). Migración
   `database/migrations/2026_11_10_100000_create_activity_log_table.php` (une los tres stubs del
   paquete). En el primer armado quedó en `usuarios`; se movió a `plataforma` en la misma sesión,
   antes de producción (ver `docs/DECISIONES.md`).
2. **User** — trait `LogsActivity` con `getActivitylogOptions()`: solo campos que cambiaron, sin
   contraseña ni secretos. Descripciones en castellano (`creado`, `editado`, `eliminado`, `restaurado`).
3. **Roles** — `User::sincronizarRoles()` reemplaza a `syncRoles()` en `UserController` (store,
   update, sync_roles) y registra agregados/quitados.
4. **Accesos** — listener `App\Listeners\RegistrarAccesoUsuario` en lugar del middleware
   `LogUserActivity` (borrado). Eventos nuevos en `logs`: `logout`, `login_fallido`,
   `password_reset_admin`. Se registra también el cambio de contraseña propio, el reset por mail y
   el cambio desde el perfil de Jetstream.
5. **Seguridad** — `ResetPasswordModal` no chequeaba permiso: ahora exige `Usuarios/Usuarios/Editar`.
6. **Log** — corregido el import de `User` en `Log::usuario()` (apuntaba a una clase inexistente) y
   `$guarded = []` → `$fillable`.
7. **Vista** — componente `Usuarios\Users\Show\Historial` al pie de la ficha del usuario, paginado,
   con área/sede/cargo resueltos por nombre. Sin permiso propio: lo gobierna `Usuarios/Usuarios/Ver`.

## Tests
- `HistorialUsuarioTest` (8): alta con causante, edición old/new, contraseña fuera del historial,
  roles agregados/quitados, sin registro si no cambian los roles, conexión `usuarios`, vista y 403.
- `LogTest` (+6): login, logout, login fallido, reset por mail, reset por admin y 403 del reset.

## Otros
- `composer.json`: `php ^8.2` → `^8.3` (mínimo de Laravel 13; producción corre 8.3.36).
- `tests/TestCase.php`: se suma `mysql` a `$connectionsToTransact`.

## Para producción
- `composer install --no-dev` y `php artisan migrate --path=database/migrations/2026_11_10_100000_create_activity_log_table.php` (base `plataforma`).
- Los usuarios ya existentes no tienen historial previo: arranca a registrar desde el deploy.

## Fuera de alcance
- (Resuelto en la misma sesión) `SplitUserNames` ahora guarda vía `$user->update()` y queda en el historial.
- Cambios de permisos de un rol (`syncPermissions` en Módulos) no se registran todavía.
- `users.last_login` sigue sin escribirse.
