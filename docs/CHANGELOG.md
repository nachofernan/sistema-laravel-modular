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

### Concursos — limpieza, documentación y panel de apertura
- `app/Models/Concursos/Concurso.php` — eliminados bloques comentados (`proveedores()`, dos versiones viejas de `sedes()`). Eliminado `use ConcursoProveedor` sin uso y línea comentada en `editable()`.
- `app/Http/Controllers/Concursos/ProrrogaController.php` — eliminado `use Mail` sin uso.
- `app/Livewire/Concursos/Concurso/Show/AccionesConcurso.php` — eliminados `//Mail::to()` comentado y bloque `/* if subDays */` comentado.
- `app/Services/ConcursoEncryptionService.php` — docblock completo explicando qué encripta, cuándo, algoritmo, dónde vive la clave, advertencia de backup, y diferencia con `FileEncryptionService`.
- `docs/modulos/12-CONCURSOS.md` — sección de encriptación reescrita con flujo completo de apertura, advertencia de backup de clave, y clarificación de que los estados vencido/cerrado son virtuales por diseño.
- `resources/views/livewire/concursos/concurso/show/acciones-concurso.blade.php` — panel de resumen debajo del botón "Abrir Ofertas": invitados totales, con oferta (intencion=3), sin respuesta, no participan, documentos a desencriptar y documentos a eliminar.

### Proveedores — limpieza y documentación
- `app/Http/Controllers/Proveedores/ValidacionController.php` — eliminado repair loop de `index()`. La corrección de datos se movió al comando `proveedores:reparar-validaciones`.
- `app/Console/Commands/RepararValidacionesProveedores.php` — nuevo comando Artisan para crear registros `Validacion` faltantes. Ejecutar una vez; si devuelve cero, el loop del controller nunca estaba haciendo nada.
- `app/Http/Controllers/Proveedores/DocumentoController.php` — eliminado `Mail::to()` comentado. Emails desactivados (`jprojeda`, `mmartin`) reformateados a una línea por dirección con comentario explícito. Eliminados `use Mail` y `use Storage` sin uso.
- `app/Models/Proveedores/Proveedor.php` — eliminados bloques comentados (versión anterior de `falta_a_vencimiento()`, relación `documentos()` y `concursos()` obsoletas). `falta_a_vencimiento()` documentado: centinelas de retorno (-1/15/1000) y motivo del `addYear()` (overflow en servidor 32-bit con fechas > ~2038). Corregido `subDays(30)` → `copy()->subDays(30)` para evitar mutación del objeto Carbon.

### Inventario — limpieza y mejoras
- `app/Http/Controllers/Inventario/ElementoController.php` — eliminado `Elemento::all()` muerto en `index()` (la tabla la renderiza Livewire). Agregada validación en `store()`.
- `app/Exports/Inventario/ElementosExport.php` — nuevo export Excel del listado de elementos (código, categoría, estado, usuario, sede, área).
- `routes/inventario.php` — nueva ruta `GET inventario/elementos/exportar`.
- `resources/views/inventario/elementos/index.blade.php` — botón "Exportar Excel" en el header.

### Automotores — limpieza, constantes y export
- `app/Models/Automotores/Vehiculo.php` — números mágicos de `getNecesitaServiceAttribute()` extraídos a constantes (`KM_INTERVALO_SERVICE`, `KM_VENTANA_ALERTA_ANTES`, `KM_VENTANA_ALERTA_DESPUES`, `KM_MAXIMO_DESDE_ULTIMO_SERVICE`).
- `app/Models/Automotores/Copres.php` — comentario en campo `kz` explicando que es el identificador de factura del sistema SAP.
- `app/Http/Controllers/Automotores/CopresController.php` — eliminado query muerto en `index()` (la tabla la renderiza Livewire). Agregado método `exportar()`.
- `app/Exports/Automotores/CopresExport.php` — nuevo export Excel de COPRES (fecha, vehículo, litros, precios, KM, KZ, ticket, CUIT).
- `routes/automotores.php` — nueva ruta `GET automotores/copres/exportar`.
- `resources/views/automotores/copres/index.blade.php` — botón "Exportar Excel" en el header.

### Capacitaciones — email al invitar
- `app/Mail/Capacitaciones/InvitacionCapacitacion.php` — nuevo mailable para notificar al usuario invitado.
- `resources/views/emails/capacitaciones/invitacion.blade.php` — vista del mail con nombre de capacitación, fechas, modalidad y link.
- `app/Livewire/Capacitaciones/Capacitacions/Show/Invitaciones.php` — `agregar()` ahora envía email al usuario invitado vía `EmailHelper::enviarNotificacion()`.

### AdminIP — aclaración en rutas
- `routes/web.php` — agregado comentario explicando que las rutas del grupo `home` son las rutas de usuario de Tickets y Capacitaciones, distintas de las rutas de encargado/admin en sus propios archivos de rutas.

## 2026-07-03

### AdminIP — limpieza y documentación
- `app/Http/Controllers/Adminip/CategoriaController.php` — eliminado. Resource controller con los 7 métodos vacíos, sin vista asociada (`resources/views/adminip/categorias/` no existe) y sin uso real.
- `routes/adminip.php` — eliminada la ruta `Route::resource('categorias', ...)` y el import correspondiente.
- `resources/views/components/navigation-links/adminip.blade.php` — eliminado el link a "Categorías" que estaba comentado.
- `docs/modulos/04-ADMINIP.md` — documentado que `categoria_id` existe en `ips` (FK a `categorias`, agregada en `create_categorias_table.php`) pero no está implementado a nivel de aplicación: sin relación Eloquent, sin campo en los formularios Livewire. Aclarado que la validación de formato/unicidad de IP ya vive en los componentes Livewire (`Crear`/`Editar`), no como `FormRequest`.
- `docs/ROADMAP.md` — agregada sección AdminIP (faltaba); ítem de validación de IP de la hoja de ruta legacy marcado como cumplido.
