# Upgrade Laravel 11 → 13

**Fecha de inicio:** 2026-09-03
**Rama de trabajo:** `upgrade/laravel-13` (una sola rama para todo el recorrido, merge a `main` al final)
**Plan completo:** ver `docs/DECISIONES.md` (2026-09-03) para el resumen de la decisión.

Este archivo se va actualizando a medida que se completa cada paso. Formato por entrada: qué se
hizo, qué se encontró, qué queda pendiente de tu lado (el usuario).

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

### Falta para cerrar el escalón 1

- [ ] **Smoke test manual (de tu lado):** login, un flujo de cada módulo grande, el endpoint JWT
  del Portal de Proveedores, un envío real de email por SMTP. Avisame el resultado.
- [ ] Decidir cuándo/cómo encarar `firebase/php-jwt` (weak encryption, severidad baja, pero toca
  el núcleo sagrado del JWT externo) — pendiente, no bloquea el cierre del escalón.
- [ ] Decidir si querés que arregle el test flaky de `BusquedaTest` (legajo) — pendiente, no
  bloquea el cierre del escalón.
- [ ] Entrada en `docs/CHANGELOG.md` una vez confirmado el smoke test.
- [ ] Recién ahí arranca el escalón 2 (Laravel 12 → 13 + Livewire 4).

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
