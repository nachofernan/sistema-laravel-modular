# Changelog

Registro de cambios a partir de Julio 2026, punto de inflexión donde el proyecto
empieza a trabajarse de forma estructurada con documentación, tests y revisión de
deuda técnica. Los cambios anteriores a esta fecha no están registrados aquí.

El formato es: `[fecha] — descripción`. Los cambios de código van con el archivo
o módulo afectado. Los cambios de infraestructura (tests, docs, config) van agrupados.

---

## 2026-07-02

### Documentación inicial del proyecto
- Creado `CLAUDE.md` con contexto real del proyecto (empresa, stack, decisiones tomadas).
- Creado `docs/ARQUITECTURA.md` — documento madre con estructura general, multi-DB, auth/permisos, módulos.
- Creados `docs/modulos/01` al `12` — descripción de cada módulo, modelos, flujos, deuda técnica.
- Creado `docs/HOJA_DE_RUTA.md` — plan de refactorización por fases (reemplazado por `ROADMAP.md`).
- Módulos Fichadas y Mesa de Entradas marcados como deprecados (nunca se usaron en producción).

### Tests — Fase 0
- `tests/TestCase.php` — reemplazado `RefreshDatabase` por `DatabaseTransactions` con todas las conexiones del sistema. Los tests corren contra las bases de desarrollo sin dejar datos.
- `tests/Pest.php` — limpiado; solo vincula `TestCase` base sin traits globales.
- Tests convertidos a sintaxis Pest funcional: AdminIP, Usuarios, Tickets, Inventario, Documentos, Capacitaciones.
- `database/factories/Capacitaciones/CapacitacionFactory.php` — campo `fecha` → `fecha_inicio` + `fecha_final` (alineado con migración de 2025).
- `database/factories/Capacitaciones/InvitacionFactory.php` — agregado campo `tipo` (`presencial|virtual`).
- **Resultado: 62 tests, 111 assertions, verde.**

### Tickets — limpieza de código muerto
- `app/Http/Controllers/Home/TicketController.php` — eliminados bloques comentados de `Mail::to()` y `EnviarTicketNuevoEmail::dispatch()` anteriores a la migración a `EmailHelper`.
- `app/Http/Controllers/Home/MensajeController.php` — eliminada línea comentada `Mail::to()`.
- `app/Http/Controllers/Tickets/MensajeController.php` — eliminada línea comentada `Mail::to()`.

### AdminIP — aclaración en rutas
- `routes/web.php` — agregado comentario explicando que las rutas del grupo `home` son las rutas de usuario de Tickets y Capacitaciones, distintas de las rutas de encargado/admin en sus propios archivos de rutas.
