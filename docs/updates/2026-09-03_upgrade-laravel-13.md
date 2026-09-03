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
del proyecto — leerlo si no está ya en contexto). Estamos en medio de un upgrade de framework de
Laravel 11 a Laravel 13, decidido con el usuario, pasando por Laravel 12 en el medio. Es un cambio
que toca la infraestructura transversal completa (núcleo sagrado ampliado, ver `CLAUDE.md`), así
que se trabaja en el hilo principal con el usuario presente, no delegado a ciegas.

**Cómo se está versionando:** todo el recorrido vive en una sola rama, `upgrade/laravel-13`,
creada desde `main` (con el tag `pre-upgrade-laravel11` marcando el punto de partida). Se mergea a
`main` una sola vez al final, cuando todo esté en Laravel 13 y verificado — **no** se mergea nada
intermedio. Si en algún momento hay una urgencia real en `main`, se resuelve ahí aparte y se trae
a esta rama con un merge.

### Estado actual (al cerrar la sesión del 2026-09-03)

- Rama activa: `upgrade/laravel-13`, con 5 commits sobre `main` (ver `git log upgrade/laravel-13
  ^main` para el detalle). **Nada mergeado a `main` todavía.**
- **Escalón 1 (Laravel 11 → 12): CERRADO y verificado.** `laravel/framework` está en v12.69.1.
  Detalle completo más abajo en este mismo archivo, en la sección "Escalón 1.1".
- Suite de tests: **175 passed, 3 failed** (los 3 fallos son preexistentes, no relacionados —
  tabla `oferta_documentos` faltante en `plataforma_dev`, ver detalle abajo). Esta es la línea de
  base a la que hay que volver después de cada escalón.
- Smoke test manual del escalón 1: **confirmado OK por el usuario** — login, todos los módulos,
  exports a Excel y a PDF. **No se probó el envío real de email**, a propósito — se decidió
  dejarlo para el final de todo el recorrido, ya que ningún escalón tocó el mailer todavía.

### Lo que falta: Escalón 2 (Laravel 12 → 13), todavía NO iniciado

Pasos en orden (adaptar si al ejecutar aparece algo nuevo, como pasó en el escalón 1 — avisar y
ajustar, no forzar el plan original a como dé lugar):

**2.0 — Retomar:**
1. Confirmar `git status` limpio y que se está parado en la rama `upgrade/laravel-13` (no en
   `main`).
2. Correr la suite completa (vía el agente `testeador`) para confirmar que se sigue en 175
   passed / 3 failed antes de tocar nada nuevo.

**2.1 — `innoge/laravel-msgraph-mail` (independiente, se puede hacer primero y aparte):**
- Actualmente en 1.4.0. No soporta Laravel 13 (su constraint es
  `illuminate/contracts: ^9.38|^10.0|^11.0|^12.0`, sin `^13.0`). Hay que subirlo a la serie 2.x.
- Recordatorio importante: **este mailer no está activo en producción** (`.env` tiene
  `MAIL_MAILER=smtp` contra `smtp.office365.com`; las líneas de `microsoft-graph` están
  comentadas) — confirmado en la sesión anterior. Bajo riesgo funcional, pero sigue siendo
  dependencia dura en `composer.json` así que hay que subirla igual para que `composer update`
  del framework no se trabe. Revisar el changelog de la v2 antes de darla por trivial.

**2.2 — Livewire 3 → 4 (antes de tocar el framework — es el cambio de mayor superficie):**
- `livewire/livewire` está en 3.8.7 (ya parcheado por seguridad en el escalón 1). Laravel 13
  requiere Livewire 4 (confirmado: v4+ para Laravel 13, la propia librería lo dice explícito).
- Puntos concretos a revisar, confirmados en el análisis previo:
  - **Prefijo de URL cambia** de `/livewire/` a `/livewire-{hash}/` (hash derivado de `APP_KEY`).
    El proyecto tiene `LIVEWIRE_URL_PREFIX` custom en `.env` — confirmar que sigue funcionando o
    si rompe algo aguas arriba.
  - `wire:model.blur` / `.change` cambian de semántica en v4 (ahora controlan sincronización de
    estado del cliente, no solo timing de red) — hacer un grep dirigido antes de asumir que no se
    usan (no se había detectado uso en el relevamiento inicial, pero eso fue ANTES de escribir
    este paso, conviene reconfirmar sobre el código real en ese momento).
  - `$this->emit(...)` viejo (pre-Livewire-3) rompería si sobrevivió algo así — grep dirigido
    también.
  - Es mayormente retrocompatible según la propia documentación de Livewire — no debería requerir
    reescribir los 108 componentes, pero hay que verificarlo con la suite + smoke visual.
- Suite completa después del bump. Commit separado, antes de tocar `laravel/framework`.

**2.3 — Framework a Laravel 13:**
- `composer require laravel/framework:^13.0` (chequear igual que en el escalón 1 si Composer
  bloquea versiones bajas por advisories de seguridad — en el escalón 1 hubo que fijar el piso en
  `^12.61` por eso mismo; puede repetirse el patrón acá, revisar el mensaje de error de Composer
  si aparece y fijar el piso correspondiente).
- Seguir la guía oficial en `https://laravel.com/docs/13.x/upgrade` punto por punto (fetchearla,
  no asumir de memoria — en el escalón 1 sirvió mucho leerla literal en vez de confiar en resúmenes
  de terceros, que tenían al menos un dato inventado sobre `TrustHosts` que la guía oficial no
  respalda).
- Confirmar versión mínima de `laravel/jetstream` / `laravel/fortify` que declare soporte 13 (en
  el escalón 1 no hizo falta tocarlas, puede que acá tampoco, pero confirmar).
- Ir chequeando de paso, igual que en el escalón 1, si algún otro paquete queda atado al bump
  (dompdf, spatie/*, etc. ya deberían estar bien porque se subieron a versiones con rango amplio
  en el escalón 1, pero no asumir sin confirmar con el propio `composer update`).

**2.4 — Verificación del escalón 2:**
- Suite completa (debe volver a 175 passed / 3 failed).
- Smoke test manual del usuario: sesión/login (ojo que `SESSION_CONNECTION=usuarios` es custom,
  no la default), un componente Livewire de cada módulo grande, el endpoint JWT del Portal de
  Proveedores, **y esta vez sí probar el envío real de email** (ya que quedó pendiente de todo el
  recorrido).
- Commits de cierre + `docs/CHANGELOG.md` + `docs/ARQUITECTURA.md` si cambió algo estructural
  visible (ej. el prefijo de Livewire).

**2.5 — Cierre de todo el upgrade:**
- Suite completa una vez más sobre el estado final de la rama.
- Merge `upgrade/laravel-13` → `main` con `--no-ff`.
- Borrar la rama ya mergeada.
- Actualizar `docs/ROADMAP.md`.

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

## Escalón 2 — Laravel 12 → 13 (arranca acá)

**Estado:** no iniciado.

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

### Pendiente de tu lado

- Nada todavía en este paso — es todo preparación de mi lado. En cuanto arranque el escalón 1 vas
  a tener que validar manualmente (login, un flujo por módulo, envío real de email, endpoint JWT).

---
