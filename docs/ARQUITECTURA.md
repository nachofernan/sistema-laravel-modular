# Arquitectura General — Plataforma BAESA

**Última actualización:** Julio 2026

---

## Qué es el sistema

Plataforma interna de gestión administrativa y operativa de **Buenos Aires Energía S.A. (BAESA)**. Es un monolito Laravel con arquitectura modular: cada área funcional (usuarios, inventario, proveedores, etc.) vive en su propio namespace, base de datos y conjunto de rutas. Los módulos comparten autenticación, sistema de permisos y UI, pero sus datos están aislados.

---

## Stack tecnológico

| Capa | Tecnología |
|------|-----------|
| Framework | Laravel 11 / PHP 8.2 |
| Frontend | Blade + Livewire 3 + Alpine.js |
| CSS | TailwindCSS (compilado con Vite) |
| Base de datos | MySQL (múltiples bases, una por módulo) |
| Auth | Laravel Jetstream + Fortify |
| Permisos | Spatie Laravel Permission |
| Archivos | Spatie MediaLibrary |
| PDF | barryvdh/laravel-dompdf |
| Excel | maatwebsite/excel + PhpSpreadsheet |
| Email | innoge/laravel-msgraph-mail (Microsoft Graph) |
| API externa | firebase/php-jwt (Portal de Proveedores) |

---

## Múltiples bases de datos

La característica más importante de la arquitectura: **cada módulo tiene su propia base de datos MySQL**. La conexión se define en cada modelo con `protected $connection = 'nombre_modulo'`.

Las conexiones están configuradas en `config/database.php` y sus credenciales en `.env`:

| Conexión | Variable `.env` | Módulo |
|----------|----------------|--------|
| `usuarios` | `DB_DATABASE_USUARIOS` | Usuarios, Auth, Permisos |
| `tickets` | `DB_DATABASE_TICKETS` | Tickets de soporte |
| `inventario` | `DB_DATABASE_INVENTARIO` | Inventario IT |
| `documentos` | `DB_DATABASE_DOCUMENTOS` | Documentos institucionales |
| `adminip` | `DB_DATABASE_ADMINIP` | Gestión de IPs |
| `capacitaciones` | `DB_DATABASE_CAPACITACIONES` | Capacitaciones |
| `proveedores` | `DB_DATABASE_PROVEEDORES` | Registro de proveedores |
| `concursos` | `DB_DATABASE_CONCURSOS` | Concursos de precios |
| `automotores` | `DB_DATABASE_AUTOMOTORES` | Flota vehicular |
| `despacho` | `DB_DATABASE_DESPACHO` | Lecturas de máquinas |
| `mesadeentradas` | `DB_DATABASE_MESADEENTRADAS` | Mesa de entradas |
| `fichadas` | `DB_DATABASE_FICHADAS` | Fichadas (solo lectura) |
| (externos) | `DB_DATABASE_PROVEEDORES_EXTERNOS` | Portal externo de proveedores |

**Implicación importante**: las relaciones entre modelos de distintas bases de datos NO pueden usar JOINs SQL nativos. Se resuelven en PHP cargando los modelos por separado o usando `belongsTo` con la clave foránea manual.

---

## Estructura de directorios

```
app/
  Http/
    Controllers/        # Controladores por módulo (carpeta por módulo)
  Livewire/             # Componentes Livewire por módulo
  Models/               # Modelos por módulo (carpeta por módulo)
  Services/             # Servicios transversales (email, encriptación, etc.)
  Jobs/                 # Jobs de colas (emails, procesamiento async)
  Mail/                 # Mailable classes
  Actions/              # Acciones Fortify/Jetstream
  Rules/                # Reglas de validación custom

routes/
  web.php               # Rutas base + carga dinámica de módulos
  api.php               # API REST (Portal de Proveedores externo)
  <modulo>.php          # Rutas de cada módulo (uno por archivo)

resources/views/
  <modulo>/             # Vistas Blade por módulo
  layouts/              # Layouts compartidos
  components/           # Componentes Blade reutilizables
  livewire/             # Vistas de componentes Livewire

database/migrations/
  <Modulo>/             # Migraciones separadas por módulo

docs/
  modulos/              # Documentación de cada módulo
  updates/              # Registro de cambios y actualizaciones
```

---

## Sistema de autenticación y permisos

### Auth
- **Jetstream + Fortify**: login, registro, recuperación de contraseña, 2FA opcional.
- **Middleware `PasswordExpiryCheck`**: fuerza cambio de contraseña si expiró (tabla `password_securities`).
- **SoftDeletes en User**: los usuarios eliminados se desactivan, no se borran.

### Roles y permisos (Spatie)
- **Convención de naming**: `Modulo/Rol`. Ejemplos: `Proveedores/Acceso`, `Concursos/Admin`, `Inventario/ABM`.
- Cada ruta de módulo tiene middleware `role:Modulo/Rol` que controla el acceso.
- Un usuario puede tener múltiples roles de múltiples módulos.
- El rol `Plataforma/Admin` es superadmin y bypasea restricciones.
- Los módulos se habilitan/deshabilitan desde la tabla `modulos` (campo `estado`). Si un módulo está `inactivo`, sus rutas no se cargan en absoluto.

---

## Carga dinámica de módulos

En `routes/web.php`, los módulos se cargan iterando sobre la tabla `modulos`:

```php
foreach (Modulo::where('estado', '!=', 'inactivo')->get() as $modulo) {
    $moduloLower = strtolower($modulo->nombre);
    if (File::exists(base_path("routes/{$moduloLower}.php"))) {
        require base_path("routes/{$moduloLower}.php");
    }
}
```

Esto significa que para agregar un módulo nuevo hay que: crear el registro en la tabla `modulos` y el archivo `routes/nombremodulo.php`.

---

## Sistema de emails

- **Driver**: Microsoft Graph (innoge/laravel-msgraph-mail).
- **Cola**: los envíos van a una queue de Laravel para no bloquear la request.
- **Log de emails**: tabla `email_logs` (conexión default) registra cada envío con estado y destinatario.
- **EmailDispatcher**: servicio en `app/Services/EmailDispatcher.php` centraliza el envío y logging.
- **Jobs**: `app/Jobs/Emails/` contiene los jobs por módulo.

---

## API REST (Portal de Proveedores externo)

Existe un portal externo donde los proveedores pueden ver sus concursos, declarar intención de participar y subir documentos. La autenticación es via JWT:

- Token se genera con CUIT + email del proveedor (válido 10 minutos).
- Middleware `verify.jwt` valida el token en cada request.
- Rutas en `routes/api.php`.
- Ver `docs/ESPECIFICACIONES_TECNICAS_CONCURSOS.md` para detalle completo.

---

## Módulos del sistema

| # | Módulo | Complejidad | Doc |
|---|--------|-------------|-----|
| 1 | Usuarios | Media | [ver](modulos/01-USUARIOS.md) |
| 2 | Documentos | Baja | [ver](modulos/02-DOCUMENTOS.md) |
| 3 | Inventario | Media | [ver](modulos/03-INVENTARIO.md) |
| 4 | AdminIP | Baja | [ver](modulos/04-ADMINIP.md) |
| 5 | Tickets | Baja | [ver](modulos/05-TICKETS.md) |
| 6 | Capacitaciones | Media | [ver](modulos/06-CAPACITACIONES.md) |
| 7 | ~~Fichadas~~ | ~~Baja~~ | [ver](modulos/07-FICHADAS.md) — **DEPRECADO, a eliminar** |
| 8 | Automotores | Baja | [ver](modulos/08-AUTOMOTORES.md) |
| 9 | ~~Mesa de Entradas~~ | ~~Baja~~ | [ver](modulos/09-MESA-DE-ENTRADAS.md) — **DEPRECADO, a eliminar** |
| 10 | Despacho | Media | [ver](modulos/10-DESPACHO.md) |
| 11 | Proveedores | Alta | [ver](modulos/11-PROVEEDORES.md) |
| 12 | Concursos | Alta | [ver](modulos/12-CONCURSOS.md) |

---

## Deuda técnica conocida

- **Sin tests**: prácticamente no hay tests automatizados. Las migraciones y cambios se prueban manualmente.
- **Nomenclatura inconsistente**: algunos modelos y tablas mezclan español/inglés, singular/plural (ej: `proveedors`, `invitacions`, `direccions`). Es un artefacto del crecimiento orgánico.
- **Relaciones cross-DB sin tipos**: las relaciones entre bases de datos no tienen foreign keys a nivel DB; la integridad referencial es solo en código.
- **Controladores gordos**: algunos controladores (especialmente Proveedores y Concursos) tienen lógica de negocio que debería estar en servicios o modelos.
- **Sin factory/seeder en módulos**: solo existe el seeder base de Laravel. No hay datos de prueba automatizados.
- **Documentación parcial**: la carpeta `docs/` tiene documentación de la API pero no de los módulos internos. Eso es lo que este directorio `docs/modulos/` empieza a resolver.
