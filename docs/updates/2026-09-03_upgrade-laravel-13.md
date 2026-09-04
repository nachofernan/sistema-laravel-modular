# Upgrade Laravel 11 → 13

**Fecha de inicio:** 2026-09-03
**Rama de trabajo:** `upgrade/laravel-13` (una sola rama para todo el recorrido, merge a `main` al final)
**Plan completo:** ver `docs/DECISIONES.md` (2026-09-03) para el resumen de la decisión.

Este archivo se va actualizando a medida que se completa cada paso. Formato por entrada: qué se
hizo, qué se encontró, qué queda pendiente de tu lado (el usuario). La sección siguiente,
**"Para la próxima sesión"**, es autocontenida — sirve como prompt de arranque sin necesidad de
leer el resto del archivo. El resto de abajo es el detalle cronológico, por si hace falta
profundidad.

---

## Para la próxima sesión — empezar acá

### Qué es esto

Proyecto BAESA (Laravel, multi-DB, ver `CLAUDE.md` en la raíz del repo para las reglas de trabajo
del proyecto — leerlo si no está ya en contexto). Se hizo un upgrade de framework de Laravel 11 a
Laravel 13, pasando por Laravel 12 en el medio. Es un cambio que toca la infraestructura
transversal completa (núcleo sagrado ampliado, ver `CLAUDE.md`), así que se trabajó en el hilo
principal con el usuario presente, no delegado a ciegas.

**Cómo se está versionando:** todo el recorrido vive en una sola rama, `upgrade/laravel-13`,
creada desde `main` (con el tag `pre-upgrade-laravel11` marcando el punto de partida). Se mergea a
`main` una sola vez al final — **no** se mergea nada intermedio. Si en algún momento hay una
urgencia real en `main`, se resuelve ahí aparte y se trae a esta rama con un merge.

### Estado actual (al cerrar la sesión del 2026-09-04)

- **Código y tests: LISTOS.** La rama `upgrade/laravel-13` está en Laravel 13.30.1, con la suite
  completa en verde (175 passed, 3 failed preexistentes) y el smoke test manual completo
  confirmado OK por el usuario (login/sesión, un componente Livewire de cada módulo grande, el
  endpoint JWT del Portal de Proveedores, y el envío real de email — este último quedó pendiente
  de todo el recorrido y ya se probó, sin problemas).
- **Escalón 1 (Laravel 11 → 12): CERRADO.** Detalle completo en la sección "Escalón 1.1" más abajo.
- **Escalón 2 (Laravel 12 → 13): CERRADO en código.** Detalle completo en la sección "Escalón 2"
  más abajo.
- **⚠️ EL MERGE A `main` ESTÁ PAUSADO A PROPÓSITO — no es un olvido.** Motivo: **producción corre
  PHP 8.2**, y Laravel 13 (puntualmente confirmado con `spatie/laravel-permission` 8.3.0, que
  declara `"php": "^8.3"`) necesita PHP 8.3+. Mergear ahora dejaría `main` con un framework que no
  levanta en el servidor de producción tal como está hoy. **Esto es una corrección sobre el
  relevamiento original**: en la sección "Hallazgos del análisis previo" de más abajo se había
  anotado que el PHP ya cumplía el mínimo de Laravel 13 — eso era cierto solo para el **PHP local
  de XAMPP** (8.3.33), nunca se había chequeado el PHP del **servidor de producción** en ese
  momento. Queda como aprendizaje: para upgrades de framework, chequear la versión de PHP también
  en producción, no solo en el entorno de desarrollo.
  Decisión registrada en `docs/DECISIONES.md` (2026-09-04). El usuario avisa que puede quedar
  parado sin problema — "salvo algún cambio urgente y raro" — hasta que se actualice el PHP de
  producción, estimado para la semana del 2026-09-07.

### Lo único que falta: Escalón 2.5 — Merge final (bloqueado por PHP de producción)

**No arrancar este paso sin confirmar primero que el PHP de producción ya está en 8.3+.** Si en
esta sesión no se sabe el estado, preguntarle al usuario antes de tocar nada — no asumir.

Una vez confirmado que producción tiene PHP 8.3+:
1. `git status` limpio, parado en `upgrade/laravel-13`.
2. Correr la suite completa (vía `testeador`) una vez más sobre el estado final de la rama, para
   confirmar que sigue en 175 passed / 3 failed antes de mergear (puede haber pasado tiempo desde
   el último commit).
3. Merge `upgrade/laravel-13` → `main` con `--no-ff`.
4. Borrar la rama ya mergeada (`upgrade/laravel-13`).
5. Actualizar `docs/ROADMAP.md` (sacar la tarea de "actualizar PHP de producción" si ya se resolvió
   y este es justamente el motivo por el que se estaba esperando).
6. Confirmar con el usuario si hace falta un smoke test adicional ya en producción después del
   deploy — no asumir que el smoke test de este entorno (XAMPP local) cubre todo lo del servidor.

### Dos cosas sueltas, sin bloquear nada, a resolver cuando surja el momento

- **`firebase/php-jwt`** 6.11.1 tiene una vulnerabilidad de severidad baja ("weak encryption",
  fixed en 7.0.0+). No se tocó en el escalón 1 porque es el paquete que usa
  `app/Http/Middleware/VerifyJWT.php` para el contrato JWT con el Portal de Proveedores — axioma 4,
  núcleo sagrado. Si se decide encararlo, necesita test explícito antes de darlo por hecho (no es
  parte obligatoria de llegar a Laravel 13, es un tema aparte).
- **Test flaky** en `tests/Feature/Documentos/BusquedaTest.php` (`busca por nombre`): puede fallar
  por `UniqueConstraintViolationException` en `users.legajo_unique`, porque `UserFactory` usa
  `Faker::unique()` que no garantiza unicidad contra los datos reales ya en `plataforma_dev` (los
  tests corren con `DatabaseTransactions` contra la base real). Preexistente, no causado por el
  upgrade. Se decide si se arregla aparte.

---

## Paso 0 — Preparación

**Estado:** en curso

- `git status` en `main`: limpio, sin cambios pendientes. ✅
- Suite completa como línea de base: **175 passed, 3 failed, 0 skipped**. ✅ (ver detalle abajo)
- Decisión registrada en `docs/DECISIONES.md` (2026-09-03).
- Tag `pre-upgrade-laravel11` creado sobre el commit de `main`, y rama `upgrade/laravel-13`
  creada y activa. ✅

**Paso 0 completo.** Arranca el escalón 1 (Laravel 11 → 12).

---

## Escalón 1.1 — `maatwebsite/excel` (en curso)

**Uso real en el código** (relevado antes de tocar nada): solo 3 exports, sin ningún import.
- `app/Exports/Automotores/CopresExport.php` — `FromCollection`, `WithHeadings`, `WithMapping`. Simple.
- `app/Exports/Inventario/ElementosExport.php` — igual de simple.
- `app/Exports/Proveedores/ProveedorExport.php` — el único con algo para mirar: extiende
  `\PhpOffice\PhpSpreadsheet\Cell\StringValueBinder` directo, y tiene un `use FromQuery` + método
  `query()` **muerto** (ya no se usa, la clase implementa `FromArray`, no `FromQuery` — quedó del
  cambio anterior). No es parte de este upgrade, pero lo anoto como candidato a limpieza aparte.
- Sin uso de Laravel Scout, sin `config/excel.php` publicado (usa el default del paquete).

### Hallazgo que cambia el plan: excel v4 y el bump de Laravel están acoplados

`maatwebsite/excel` 3.1 (la actual) **ya es incompatible con Laravel 12**, y la nueva 4.0.2
**requiere Laravel 12 o 13** (`illuminate/support ^12.0 || ^13.0`). No se puede actualizar el
paquete de Excel antes que el framework, ni el framework antes que el paquete: **quedan atados a
la misma corrida de `composer update`**, aunque en el plan original los había separado como pasos
independientes. Ajusto el orden: van juntos en un solo commit (documentado igual como "efecto en
cascada": paquete forzado por el bump de framework), no dos commits separados como decía el plan
original.

### Bloqueante encontrado — necesito algo de tu lado antes de seguir

Al correr `composer update` apareció que la extensión **`exif` de PHP no está habilitada** en el
PHP de este XAMPP (`C:\xampp\php\php.ini`), y `spatie/laravel-medialibrary` (ya instalado, versión
11.13.0) la requiere. Hasta ahora no daba problema porque nada forzaba a Composer a re-resolver ese
paquete; el bump de framework sí lo va a tocar.

**Lo que necesito que hagas vos:**
1. Abrir `C:\xampp\php\php.ini`.
2. Buscar la línea `;extension=exif` (línea 947) y sacarle el `;` de adelante, dejando
   `extension=exif`.
3. Reiniciar Apache desde el panel de XAMPP (para que tome el cambio).

No lo edito yo directo porque es un archivo de configuración global de tu XAMPP, fuera del repo, y
puede afectar a otras cosas que corran ahí — mejor que lo hagas vos sabiendo qué cambia. Avisame
cuando esté listo y sigo con el `composer update`.

**Estado:** resuelto — extensión habilitada por el usuario, seguimos.

### El `composer update` real terminó siendo más grande de lo previsto

Al ir resolviendo, aparecieron más paquetes atados al mismo bump que los dos ya previstos. Orden
real de lo que pasó (todo en un solo `composer update`, ya que estaban interdependientes):

- **`barryvdh/laravel-dompdf` 2.2.0 tampoco soportaba Laravel 12** (tope real: Laravel 11). Se
  subió a **3.1.2** (trae `dompdf/dompdf` 2.0.8 → 3.1.6). Revisé el único uso real en el proyecto
  (`app/Livewire/Proveedores/Anexosolped/Create.php` → `Pdf::loadView(...)`, vista con
  `public_path('img/membrete.png')`): usa una imagen local, no remota, así que el cambio de
  Dompdf 3 que desactiva `enable_remote` por default **no afecta** a este caso. Bajo riesgo.
- **Composer bloqueó por política de seguridad** cualquier versión de `laravel/framework` con
  advisories conocidos (varias del rango 11.x y 12.x por debajo de 12.61.1). Se fijó el piso en
  `^12.61` para no toparse con eso — terminó instalando **v12.69.1** (la última de la serie).
- **`pestphp/pest-plugin-laravel` 2.4.0 no soportaba Laravel 12**. Acá se te preguntó y elegiste
  ir directo a v4 (PHPUnit 12) en vez de la v3 intermedia, para no tocar el paquete de testing dos
  veces en todo el recorrido. Quedó: `pestphp/pest` **v4.7.8**, `pestphp/pest-plugin-laravel`
  **v4.1.0**, `phpunit/phpunit` **v12.5.33**.
- **Corrección sobre lo que había anotado antes**: `innoge/laravel-msgraph-mail` en su versión
  actual (1.4.0) **sí soporta Laravel 12** (su constraint real es
  `illuminate/contracts: ^9.38|^10.0|^11.0|^12.0` — mi relevamiento previo estaba mal en este
  punto puntual). No hizo falta tocarlo en este escalón. Sigue sin soportar Laravel 13, así que
  toca bumpearlo recién en el escalón 2.

### Resultado final del bump de framework

`laravel/framework` **v11.45.1 → v12.69.1**. `composer update` corrió limpio.

### Hallazgo aparte: vulnerabilidad crítica ya activa (no relacionada al upgrade en sí)

El audit de seguridad de Composer encontró que **Livewire 3.6.3 (la versión que estaba en
producción hasta este momento) tiene una RCE crítica** (`PKSA-3r5d-mb8f-1qw9` y relacionadas,
fixed en 3.6.4+), más dos vulnerabilidades (alta y media) en `spatie/laravel-medialibrary`
11.13.0, fixed en 11.23.0. Ninguna de las dos tiene que ver con el upgrade de Laravel — ya estaban
presentes antes. Como el fix es un bump chico dentro de la misma versión mayor (no toca Livewire 4,
eso sigue siendo tarea del escalón 2), lo apliqué directo como parche de seguridad:
- `livewire/livewire` 3.6.3 → **3.8.7**
- `spatie/laravel-medialibrary` 11.13.0 → **11.23.7**
- de paso, `symfony/yaml` (transitiva) y `psy/psysh` (herramienta de dev de Tinker) también
  tenían CVEs de severidad baja, ya resueltos al actualizar.

Queda **1 advisory pendiente, de severidad baja**: `firebase/php-jwt` 6.11.1 tiene "weak
encryption" (fixed en 7.0.0+). No lo toqué porque es el paquete que usa `VerifyJWT.php` para el
contrato JWT con el Portal de Proveedores — axioma 4, núcleo sagrado. Un bump de mayor ahí necesita
test explícito antes de darlo por hecho, así que lo dejo para una charla y un paso aparte, no
colgado de este escalón. Severidad baja, no es urgente.

### Verificación post-bump: bajó el número de tests ejecutados (esperado)

Corrida completa después del bump: **111 passed + 1 failed = 112 tests ejecutados**, contra la
línea de base de **175 passed + 3 failed = 178**. La baja de ~66 tests es exactamente la
consecuencia esperada: PHPUnit 12 dejó de reconocer `/** @test */` (confirmado por búsqueda
externa antes de tocar nada), así que esos 66 métodos en 34 archivos dejaron de ejecutarse (no
fallan, simplemente no corren). Es el motivo por el que elegiste migrarlos ahora. Migración
delegada al `ejecutor` (mecánica, decisión ya tomada) — resultado abajo.

Los 3 fallos preexistentes de `oferta_documentos` no aparecieron en esta corrida porque viven en
`ConcursoControllerTest`, que usa `/** @test */` — van a reaparecer (con el mismo motivo de
siempre, tabla faltante) una vez migrados los tests.

**Hallazgo nuevo, no relacionado al upgrade:** `Tests\Feature\Documentos\BusquedaTest > busca por
nombre` falló con `UniqueConstraintViolationException` en `users.legajo_unique` (valor duplicado
`91455`). Causa: `UserFactory` genera el legajo con
`$this->faker->unique()->numberBetween(1000, 999999)` — el `unique()` de Faker solo garantiza no
repetirse *dentro de la misma corrida*, no contra los legajos reales que ya existen en la base
`plataforma_dev` (los tests corren con `DatabaseTransactions` contra la base real, no una limpia).
Es un test flaky preexistente que ya podía fallar antes por la misma razón, con probabilidad baja
— no lo causó este upgrade, simplemente tocó esta vez. **No lo arreglé** (no es parte de este
trabajo); lo dejo anotado para que decidas si querés que lo arregle aparte (la solución típica
sería validar unicidad contra la tabla real, no solo contra Faker).

**Migración de tests completa:** 66/66 ocurrencias migradas en 34 archivos (delegado al ejecutor,
sin casos raros). Suite completa verificada de nuevo: **175 passed, 3 failed = 178**, idéntico a
la línea de base original. El flaky de `BusquedaTest` (legajo duplicado) no se repitió esta vez —
sigue anotado como pendiente aparte, no arreglado.

**Escalón 1 (Laravel 11 → 12): código y tests completos.** Commits:
- `deps: sube Laravel 11 a 12, con los paquetes que quedaron atados al bump`
- `tests: migra los 66 métodos /** @test */ a #[Test]`

### Smoke test manual — OK

Confirmado por el usuario (2026-09-03): login, todos los módulos, descarga de Excel y de PDF
funcionando perfecto. **No se probó el envío real de email** — se decide a propósito dejarlo para
el final de todo el recorrido (11→12→13), ya que este escalón no tocó nada del mailer y el riesgo
de que algo se rompa ahí por este cambio puntual es muy bajo.

**Escalón 1 (Laravel 11 → 12): CERRADO.** Entrada agregada en `docs/CHANGELOG.md` (2026-09-03).

Quedan dos decisiones sueltas, anotadas pero sin bloquear el resto:
- `firebase/php-jwt` (weak encryption, severidad baja, núcleo sagrado del JWT externo).
- Test flaky de `BusquedaTest` (colisión de legajo).

---

## Escalón 2 — Laravel 12 → 13

**Estado:** CERRADO en código y tests. Merge a `main` pausado (ver sección de arriba).

### Línea de base de tests (2026-09-03, sobre `main`, antes de tocar nada)

**175 passed, 3 failed.** Los 3 fallos son **preexistentes** y no tienen que ver con este upgrade —
los tres son del mismo archivo (`Tests\Feature\Concursos\ConcursoControllerTest`) y fallan por la
misma causa: falta la tabla `oferta_documentos` en la base `plataforma_dev` (`SQLSTATE[42S02]: Base
table or view not found`). Es un problema de estructura de esa base de datos local, no de código.

Tests que fallan hoy (antes de tocar nada):
- `puede eliminar documento de oferta antes...`
- `puede dar de baja oferta completa`
- `dar de baja solo elimina documentos de p...`

**Importante para el resto del upgrade:** si estos 3 siguen fallando exactamente igual después de
cada escalón, es la misma causa de siempre (no algo que rompió el upgrade). Si aparece un fallo
nuevo o distinto, ahí sí hay que pararse a mirarlo. Esto es aparte del upgrade — si en algún
momento querés que la arregle (crear la tabla que falta en `plataforma_dev`), avisame, pero no es
parte de este trabajo.

### Hallazgos del análisis previo (resumen, detalle completo en la charla / DECISIONES.md)

| Paquete/pieza | Estado hoy | Acción necesaria |
|---|---|---|
| `laravel/framework` | v11.45.1 | subir a ^12 y después a ^13 |
| `maatwebsite/excel` + `phpoffice/phpspreadsheet` | 3.1.56 + 1.29.0 (fijo) | **incompatible con Laravel 12** — subir a 4.0.2, soltar el pin |
| `innoge/laravel-msgraph-mail` | 1.4.0 | no declara soporte 12/13 — subir a 2.x. **No está en uso activo** (mailer real es SMTP) |
| `livewire/livewire` | 3.6.3 | **no soporta Laravel 13** — subir a ^4.0 en el escalón 12→13 |
| PHP | 8.3.33 (XAMPP local) | ya cumple el mínimo de Laravel 13 (8.3+), no hay que tocarlo |
| `bootstrap/app.php` | formato moderno (Laravel 11+) | sin acción, ya migrado |

### 2.1 — `innoge/laravel-msgraph-mail` 1.4.0 → 2.0.0

Confirmado que 1.4.0 no soporta `illuminate/contracts ^13.0`. Revisado el release notes de 2.0.0
en GitHub: cambios en token caching interno, `save_to_sent_items` ahora se lee correctamente de la
config del mailer (antes se ignoraba silenciosamente en mailers con key custom — mejora, no
rotura), nombres de adjuntos usan la extensión real. Ninguno de estos cambios afecta la config
actual (`client_id`, `client_secret`, `tenant_id`, `from`, `save_to_sent_items` en
`config/mail.php`), y el mailer sigue sin estar activo en producción. Suite completa sin cambios
(175/3). Commit: `deps: sube innoge/laravel-msgraph-mail 1.4.0 a 2.0.0 (paso 2.1, Laravel 13)`.

### 2.2 — Livewire 3.8.7 → 4.4.3

Grep dirigido antes de tocar nada: **sin uso real** de `wire:model.blur`/`.change` en ningún lado
del proyecto, y la única ocurrencia de `$this->emit(...)` legacy está **comentada** (código muerto,
`app/Livewire/Concursos/Concurso/Rubros.php:42`). Sin impacto de esos dos puntos.

Sí apareció el punto que el plan pedía verificar: `routes/web.php` usa
`Livewire::setUpdateRoute()` y `Livewire::setScriptRoute()` con un prefijo custom
(`LIVEWIRE_URL_PREFIX`, por cómo corre la app bajo un subdirectorio). En Livewire 4 ambos closures
reciben un segundo parámetro `$path` (el path con hash derivado de `APP_KEY`). Se confirmó con el
usuario que **producción sigue sirviendo la app bajo un subpath** (`/var/www/html/plataforma/` con
el `public` copiado y el `index.php` apuntando a `../../plataforma_laravel/`, plan de pasar a
symlink en el servidor nuevo) — el prefijo custom no es un resabio, sigue siendo necesario. Se
ajustó la firma de los dos closures agregando `$path` (sin usarlo), preservando el comportamiento
exacto de hoy.

Suite completa sin cambios (175/3). Smoke test manual del usuario, en dos entornos (XAMPP directo
con subpath y `artisan serve`): login, apertura de modal Livewire, envío y visualización de datos,
`wire:model.live` en buscadores, sin errores de consola — confirmado OK antes de seguir con el
framework. Commit: `deps: sube livewire/livewire 3.8.7 a 4.4.3 (paso 2.2, Laravel 13)`.

### 2.3 — Framework a Laravel 13.30.1

Leída la guía oficial completa (`https://laravel.com/docs/13.x/upgrade`) y contrastada contra el
código real con greps dirigidos antes de tocar nada. De los ítems de la guía, ninguno aplicaba al
proyecto: sin `upsert()` con `uniqueBy` vacío, sin objetos guardados en cache (`Cache::put` no se
usa en `app/`), sin `Route::domain()`, sin `->extend()` de drivers custom, sin vistas de paginación
Bootstrap legacy, sin `morphToMany` con pivot custom, `VerifyCsrfToken`/`ValidateCsrfToken` solo
aparece en el stub default de `config/sanctum.php` (sin uso propio en middleware o tests). El caso
de `session.serialization`: la clave no está en `config/session.php`, y el fallback interno del
framework cuando falta (`SessionManager::class`) sigue siendo `'php'` — el cambio de default a
`'json'` es solo para el skeleton de apps nuevas, no afecta una app existente que no declara la
clave. Sin acción necesaria, sesiones no se invalidan.

`composer update` del framework arrastró una cascada más larga que en el escalón 1, resuelta en una
sola corrida (mismo patrón: paquetes que no resolvían contra `illuminate ^13`, se sumaron todos
juntos a un único `composer require` porque Composer no puede resolverlos de a uno cuando hay
conflictos cruzados):

- **`spatie/laravel-permission` 6.20.0 → 8.3.0** (dos versiones mayores; paquete de permisos,
  axioma 2). Revisado el `UPGRADING.md` de v6→v7 y v7→v8 antes de tocarlo: sin cambios en
  `hasRole()`, `hasPermissionTo()`, `assignRole()`, `givePermissionTo()`, `can()`, Gate, ni en el
  middleware `role`/`permission` — los breaking changes son renombres de clases internas de
  eventos (`PermissionAttached` → `PermissionAttachedEvent`, etc.) y comandos Artisan, más cambios
  de firma en contratos custom (`Contracts\Role`, `Contracts\Wildcard`). Confirmado con grep que el
  proyecto no referencia nada de eso ni implementa esos contratos. `config/permission.php` usa
  `teams: false` (default), sin necesidad de tocar el esquema de tablas.
- `laravel/sanctum` 4.1.1 → 4.3.3: el caché local de Composer estaba desactualizado y no mostraba
  que 4.3.3 ya soporta `illuminate ^13.0` (`composer clear-cache` lo resolvió). Confirmado con grep
  que Sanctum está instalado (trait `HasApiTokens` en `User`, scaffolding de Jetstream) pero
  **ninguna ruta usa `auth:sanctum`** — dormido, sin riesgo funcional real.
- `laravel/jetstream` 5.3.7 → 5.5.3, `laravel/fortify` 1.27.0 → 1.39.0: bump menor (sin salto de
  mayor), trae `laravel/passkeys` como dependencia transitiva nueva de Fortify (feature de
  passkeys) — confirmado que no hay config ni código propio que la active, queda dormida.
- `laravel/tinker` 2.10.1 → 3.0.2, `laravel/sail` 1.43.1 → 1.67.0 (dev, sin `docker-compose.yml` en
  el proyecto — confirmado que no se usa).

`composer audit`: **1 advisory pendiente, sin cambios** — `firebase/php-jwt` (weak encryption,
severidad baja, ya documentado como pendiente aparte desde el escalón 1, no relacionado a este
upgrade).

Verificado `php artisan migrate:status`: sin migraciones pendientes reales (un chequeo inicial con
`--database=usuarios` mostró todo como "Pending" — falso positivo, ese flag apunta la consulta a la
tabla de migraciones de esa conexión específica en vez de la default, que es donde vive el
historial real).

Suite completa sin cambios (175/3, sin fallos nuevos de permisos, auth, sesión ni Livewire).
Commit: `deps: sube Laravel 12 a 13, con los paquetes que quedaron atados al bump`.

### 2.4 — Verificación del escalón 2

Suite completa: **175 passed, 3 failed** (los 3 preexistentes de siempre, sin cambios). Smoke test
manual completo confirmado OK por el usuario: login/sesión, componentes Livewire de los módulos
grandes, endpoint JWT del Portal de Proveedores, y **envío real de email** — este último quedó
pendiente de todo el recorrido 11→12→13 y se probó recién acá, sin problemas.

**Escalón 2 (Laravel 12 → 13): CERRADO en código y tests.**

---
