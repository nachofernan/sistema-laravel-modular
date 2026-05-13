# Email Tracker: Trazabilidad completa de correos por modelo

**Fecha:** 2026-05-13  
**Área:** Sistema de correos / Módulo de concursos  

---

## El problema

Un correo de notificación de nuevo documento en un concurso no le llegó a un empleado. La búsqueda que siguió descubrió un error silencioso: `managed_jobs` mostraba 19 correos despachados para ese documento, pero en la tabla `jobs` solo había 2. No había forma de saber cuántos correos salieron realmente, a quién, ni cuáles estaban pendientes — sin revisar manualmente tablas internas.

El sistema de correos tenía infraestructura (email_logs + managed_jobs) pero sin vínculo directo entre ellas ni con el concurso que las originó. La relación era solo por texto en el campo `descripcion` ("Concurso #5 - notificacion_apertura"), frágil e inconsultable como FK.

---

## Diagnóstico previo

| Tabla | Qué tenía | Qué faltaba |
|-------|-----------|-------------|
| `email_logs` | destinatario, tipo, estado, error | vínculo al modelo (concurso, ticket, etc.) y al job que lo originó |
| `managed_jobs` | entity_type + entity_id (polimórfico manual) | columna `destinatario` directa (estaba enterrada en JSON metadata) |

El bug específico de `managed_jobs` mostrando 19 vs 2 reales ocurría porque no había forma de cruzar el resultado del envío (`email_logs`) con el job que lo disparó (`managed_jobs`).

También se detectó un bug silencioso: el código usaba el estado `'bloqueado'` en `EnviarCorreoAutomatizado` pero el enum de la migración solo tenía `exitoso`, `fallido`, `pendiente` — cualquier email bloqueado por filtro de dominio causaría un error de constraint en MySQL.

---

## Lo que se hizo

### 1. Migraciones

**`email_logs`** — se agregaron tres columnas:
- `emailable_type` + `emailable_id` — relación polimórfica al modelo origen (Concurso, Ticket, etc.)
- `managed_job_id` — FK a `managed_jobs` para cruzar el resultado con el job que lo generó
- Se corrigió el enum agregando `'bloqueado'`

**`managed_jobs`** — se agregó:
- `destinatario` — columna directa extraída del JSON metadata, con backfill automático de registros existentes

### 2. EnviarCorreoAutomatizado

El job ahora acepta `$emailableType`, `$emailableId` y `$managedJobId` en el constructor y los persiste en cada insert a `email_logs`.

### 3. EmailDispatcher

Cambio clave en `enviarMasivoConTracking`: el `ManagedJob` se crea **antes** de despachar el job (no después), para tener su ID disponible y pasárselo al job. Esto garantiza el vínculo en la dirección correcta.

`enviarMasivo` y `enviarPrioritario` reciben `$emailableType` y `$emailableId` opcionales para contextos sin tracking.

`cancelarJobsPorEntidad` ahora usa `$managedJob->destinatario` directamente en lugar de extraerlo del JSON.

### 4. EmailHelper

- `enviarMasivo` y `enviarPrioritario` propagan los nuevos parámetros opcionales
- `enviarTicketNuevo` vincula al modelo `Ticket`
- `reprogramarEmailsProrroga` vincula el aviso inmediato de prórroga al `Concurso` (era el gap más crítico — era el único email de concurso sin vínculo)
- Los métodos con `programarConTracking` ya estaban cubiertos por la cadena

### 5. Historial en el concurso (Livewire)

Nuevo componente `HistorialEmails` con modal accesible desde el header del concurso. Muestra dos secciones:

- **Pendientes**: jobs de `managed_jobs` con status=pending, con destinatario, tipo y fecha programada
- **Historial enviados**: registros de `email_logs` con destinatario, descripción, fecha y badge de estado (exitoso / fallido / bloqueado)

Las queries solo corren cuando se abre el modal (lazy load).

### 6. Corrección de comando existente

`ReprogramarMailsConcursos` tenía `$this->success()` que no existe en Laravel. Corregido a `$this->info()`. Este comando permite migrar todos los jobs pendientes existentes al nuevo sistema con un `php artisan mail:reprogramar-concursos`.

---

## Bugs encontrados durante el despliegue

### Bug 1 — managed_jobs nunca se marcaban como `executed`

`EnviarCorreoAutomatizado` enviaba el mail y escribía en `email_logs` pero nunca actualizaba el status del `managed_job` correspondiente. Todos los jobs quedaban en `pending` indefinidamente, incluso después de haberse enviado.

**Fix**: en `registrarEnvio()` se agregó un update a `managed_jobs` usando `managed_job_id`:
- `exitoso` / `bloqueado` → status `executed`
- cualquier otro → status `failed`

### Bug 2 — Historial no mostraba emails enviados

Los emails trackeados (via `enviarMasivoConTracking`) guardan `emailable_type = 'concurso'` (el string corto del entity_type). Los emails inmediatos sin tracking (via `enviarMasivo`) guardan `emailable_type = 'App\Models\Concursos\Concurso'` (el class name completo). La query del historial solo buscaba el class name y no encontraba los trackeados.

**Fix**: la query en `HistorialEmails` usa `whereIn('emailable_type', ['concurso', Concurso::class])` para capturar ambos formatos.

### Bug 3 — Queue worker con código viejo en memoria

Los workers de queue son procesos de larga duración. Al desplegar los cambios sin reiniciar el worker, los jobs nuevos se despachaban con los campos correctos pero el worker los ejecutaba con la clase vieja en memoria, resultando en `emailable_type = null` en `email_logs`.

**Fix**: `php artisan queue:restart` después de cada despliegue que toque jobs.

### Bug 4 — Ensalada de jobs duplicados (640 managed_jobs, 506 en cola)

La combinación de jobs viejos no cancelados correctamente + reprogramaciones encima generó duplicados masivos.

**Fix**: nuevo comando `mail:reset-concursos` que cancela todos los pending, vacía la cola de emails y reprograma desde cero todos los concursos activos con el código nuevo.

---

## Archivos modificados / creados

| Archivo | Tipo |
|---------|------|
| `database/migrations/2026_05_13_000001_add_polymorphic_to_email_logs_table.php` | nuevo |
| `database/migrations/2026_05_13_000002_add_destinatario_to_managed_jobs_table.php` | nuevo |
| `app/Jobs/Emails/EnviarCorreoAutomatizado.php` | modificado |
| `app/Services/EmailDispatcher.php` | modificado |
| `app/Helpers/EmailHelper.php` | modificado |
| `app/Console/Commands/ReprogramarMailsConcursos.php` | corregido |
| `app/Console/Commands/LimpiarYReprogramarConcursos.php` | nuevo |
| `app/Livewire/Concursos/Concurso/Show/HistorialEmails.php` | nuevo |
| `resources/views/livewire/concursos/concurso/show/historial-emails.blade.php` | nuevo |
| `resources/views/concursos/concursos/show.blade.php` | modificado |

---

## Compatibilidad con datos existentes

- Jobs en vuelo (serializados en tabla `jobs`) ejecutan sin error: los campos nuevos deserializan como `null` y el email sale igual
- `email_logs` existentes permanecen con `emailable_type = null` y no aparecen en el historial nuevo — son datos históricos previos al cambio
- `managed_jobs` existentes tuvieron `destinatario` backfilleado automáticamente por la migración

---

## Conclusión

El sistema de correos pasó de ser una caja negra a tener trazabilidad completa: cada email enviado sabe a qué modelo pertenece, qué job lo originó, y cuándo ocurrió. Los pendientes son visibles antes de que salgan. El gap entre "cuántos jobs se crearon" y "cuántos emails realmente salieron" ahora es detectable en segundos desde la pantalla del concurso, sin necesidad de revisar tablas internas ni esperar que alguien reporte que no le llegó.
