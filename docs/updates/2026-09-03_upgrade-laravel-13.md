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
