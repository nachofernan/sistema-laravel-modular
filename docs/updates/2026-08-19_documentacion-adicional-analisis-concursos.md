# Notificación interna: documentación adicional cargada en análisis

**Fecha:** 2026-08-19
**Área:** Módulo de concursos / sistema de correos

---

## El pedido

Cuando un concurso está en estado **análisis** (`estado_id = 3`) y tiene `permite_carga = true`, el
Portal de Proveedores permite que el proveedor siga subiendo documentos **adicionales** a una oferta ya
presentada. El endpoint (`ConcursoController::subirDocumento`) guardaba el archivo y respondía `200`,
pero no avisaba a nadie de BAESA: el personal del concurso solo se enteraba si entraba a revisar
manualmente.

Se pidió una notificación para ese evento, dirigida **solo al personal interno del concurso**
(contactos técnicos y administrativos + el usuario gestor), nunca al proveedor.

---

## Lo que se hizo

### Mailable y vista nuevos
- `app/Mail/Concursos/DocumentacionAdicionalAnalisis.php` — mismo esqueleto que `NuevaProrroga`/`NuevoDocumento` (usa `ConcursoMailableTrait`).
- `resources/views/emails/concursos/documentacion-adicional-analisis.blade.php` — mismo maquetado de tabla HTML que las otras notificaciones de concurso.

### Hook en el endpoint
`ConcursoController::subirDocumento()`, después de `DB::commit()`: si `$concurso->estado_id == 3` (la
misma rama ya validada más arriba en el método — análisis + `permite_carga` + solo documento
adicional), arma destinatarios con `$concurso->getCorreosInteresados(['contactos_concurso'])` y los
envía con `EmailHelper::enviarMasivo()`. Reutiliza la cola + `email_logs` existentes, sin plomería
nueva. En estado activo (carga normal de oferta) no se dispara nada.

### Bug encontrado y corregido: el gestor nunca recibía nada
Al armar los destinatarios con `getCorreosInteresados(['contactos_concurso'])` aparecieron dos bugs en
`Concurso.php` que hacían que el gestor **nunca** se agregara a ese grupo, en ningún envío existente
(prórroga, apertura, cierre, anulación incluidos):
- `if($this->usuario_id)` — la columna real es `user_id`, `usuario_id` no existe → siempre `null`/falso.
- `'email' => ... $this->usuario->correo` — el modelo `User` no tiene `correo`, es `email`.

Corregido con aprobación explícita del usuario (cambia comportamiento de emails ya en producción, no
solo del nuevo). Detalle y motivo completo en `docs/DECISIONES.md` (2026-08-19).

### Fallback en el trait compartido
`ConcursoMailableTrait::getConcursoId()` solo resolvía el concurso vía `instanceof Concurso` o
`->concurso_id` directo. `OfertaDocumento` no tiene `concurso_id` propio (llega vía `invitacion`), así
que se agregó un tercer fallback: `entidad->invitacion->concurso_id`. Sin esto, el link "Ir al
Concurso" del mail hubiera quedado roto (`/concursos/concursos/0`).

### Tests
`tests/Feature/Concursos/ConcursoControllerTest.php` — dos casos nuevos:
- `subir_documento_adicional_en_analisis_notifica_al_personal_interno` — sube en estado análisis, espera 3 jobs encolados (gestor + 2 contactos).
- `subir_documento_en_estado_activo_no_notifica_al_personal_interno` — sube en estado activo, espera cero jobs.

**No se pudo confirmar en verde**: los 7 tests preexistentes del archivo ya fallaban con 401 antes de
este cambio (confirmado con `git stash` + corrida sobre el código sin tocar) — problema de entorno con
la validación JWT del token de test, ya trackeado en `docs/ROADMAP.md` → Concursos. Los 2 tests nuevos
heredan el mismo bloqueo. Verificado en su lugar con `php -l` en todos los archivos tocados (sin
errores de sintaxis).

---

## Archivos modificados / creados

| Archivo | Tipo |
|---------|------|
| `app/Mail/Concursos/DocumentacionAdicionalAnalisis.php` | nuevo |
| `resources/views/emails/concursos/documentacion-adicional-analisis.blade.php` | nuevo |
| `app/Http/Controllers/API/ConcursoController.php` | modificado (hook post-commit en `subirDocumento`) |
| `app/Models/Concursos/Concurso.php` | modificado (fix bug gestor en `getCorreosInteresados`) |
| `app/Mail/Concursos/Traits/ConcursoMailableTrait.php` | modificado (fallback en `getConcursoId`) |
| `tests/Feature/Concursos/ConcursoControllerTest.php` | modificado (2 tests nuevos) |
| `docs/modulos/12-CONCURSOS.md` | modificado |
| `docs/DECISIONES.md` | modificado |
| `docs/ROADMAP.md` | modificado (conteo actualizado del bug JWT ya trackeado) |
