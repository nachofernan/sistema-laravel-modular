# Changelog

Registro de cambios a partir de Julio 2026, punto de inflexión donde el proyecto
empieza a trabajarse de forma estructurada con documentación, tests y revisión de
deuda técnica. Los cambios anteriores a esta fecha no están registrados aquí.

El formato es: `[fecha] — descripción`. Los cambios de código van con el archivo
o módulo afectado. Los cambios de infraestructura (tests, docs, config) van agrupados.

---

## 2026-07-16

### Usuarios — Organigrama (áreas tipificadas, responsables y cargos)
Las áreas eran un árbol plano (solo `nombre` + `area_id`). Se las dotó de semántica de organigrama sin romper la recursión existente.

- **Migración** `2026_07_16_090000_create_organigrama_structure` — catálogos `tipos_area` y `cargos`; `areas` suma `tipo_area_id`, `responsable_id`, `orden`, `activa`; `users` suma `cargo_id`. Se eliminó `users.puesto` (string libre nunca usado, no era `fillable`); su rol lo cubre `cargo_id`.
- **Modelos** — `TipoArea` y `Cargo` (catálogos). `Area`: relaciones `tipo()`/`responsable()`, `hijos()` ordenado por `orden`, y `descendantIds()` (evita ciclos padre↔descendiente). `User`: relación `cargo()` y accessor `nombreCompleto` (prioriza `realname`).
- **ABM de áreas** — `AreaController` con validación real (antes `$request->all()` pelado) y guarda anti-ciclos en `update`. Alta con tipo; edición con tipo/orden/activa. Catálogos `TipoAreaController` y `CargoController` (ABM inline, reutilizan permisos existentes).
- **Personal del área** — componente Livewire `Areas/Miembros`: lista/agrega (modal con buscador)/quita personal y define el responsable en vivo (solo miembros del área, validado server-side). El cargo se elige en el ABM de usuario.
- **Listado de áreas** — `ForeachTableTr` rehecho como árbol tipo explorador de archivos (indentación, íconos de carpeta, líneas guía `├`/`└`), con badges de tipo/estado y responsable. Reemplaza el prefijo `— —`.
- **Fix** — `ForeachSelect` (select de área padre): el `disabled` marcaba mal la opción del padre actual (salía deshabilitada y sin seleccionar). Rediseñado con `selected` + `excludeId`: preselecciona el padre y deshabilita el área editada y su subárbol.

### Tests y documentación
- `tests/Feature/Usuarios/` — nuevos `TipoAreaTest`, `CargoTest`, `AreaMiembrosTest` (componente Livewire); ampliados `AreaTest` (tipo, responsable, orden, `descendantIds`, activa) y `UsuarioTest` (cargo, `nombreCompleto`). Factories `TipoAreaFactory` y `CargoFactory`. Suite del módulo: 29 tests verde.
- `docs/modulos/01-USUARIOS.md` — sección de organigrama, tabla de modelos y componentes Livewire actualizadas, nota sobre la baja de `puesto`.

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

### Concursos — limpieza de drift de esquema en tests
- La migración `2025_07_27_000001_reestructurar_documentos_concursos.php` (jul 2025) reemplazó la tabla `documentos` por `concurso_documentos`/`oferta_documentos` y eliminó `documento_tipos.encriptado`, pero quedaron restos del modelo viejo sin limpiar.
- `app/Models/Concursos/Documento.php` — eliminado. Apuntaba a la tabla `documentos`, que ya no existe; reemplazado por `ConcursoDocumento`/`OfertaDocumento`. No tenía otro uso en el código.
- `database/factories/Concursos/DocumentoFactory.php` y `tests/Feature/Concursos/DocumentoTest.php` — eliminados junto con el modelo.
- `app/Models/Concursos/DocumentoTipo.php` — eliminada la relación `documentos()`, que referenciaba la clase `Documento` ya borrada (rota desde la migración de reestructuración).
- `database/factories/Concursos/DocumentoTipoFactory.php` — eliminado el campo `encriptado` (ya no existe en `documento_tipos`, se movió a `oferta_documentos`).
- `database/factories/Concursos/OfertaDocumentoFactory.php` — eliminado el campo `validado` y el state `validado()` (nunca existió esa columna en `oferta_documentos`, drift del factory).
- Resultado: suite de Concursos pasa de 10 tests rotos a solo los de `ConcursoControllerTest` relacionados con JWT (ver `docs/ROADMAP.md`, pendiente aparte).
- Confirmado que la suite de Proveedores (16 tests) ya estaba en verde — el ROADMAP no lo reflejaba.

### Automotores — tests feature (Fase 0)
- `database/factories/Automotores/{Vehiculo,Copres,Service}Factory.php` — nuevas, no existían.
- `tests/Feature/Automotores/{VehiculoTest,CopresTest,ServiceTest}.php` — nuevos. `VehiculoTest` cubre los distintos casos de `getNecesitaServiceAttribute()` (fuera de ventana, dentro de ventana sin service previo, service reciente, service lejano).

### Despacho — tests feature (Fase 0) y bugs en métodos sin uso
- `app/Models/Despacho/{Maquina,Registrador,Lectura}.php` — agregado `HasFactory` (faltaba, impedía crear factories).
- `database/factories/Despacho/{Maquina,Registrador,Lectura}Factory.php` — nuevas, no existían.
- `tests/Feature/Despacho/{MaquinaTest,RegistradorTest,LecturaTest}.php` — nuevos, cubren la relación many-to-many Maquina↔Registrador y el armado manual de lecturas por máquina.
- `app/Models/Despacho/Maquina.php` — eliminado `lecturas()` (`hasManyThrough`): asumía una FK directa `registradores.maquina_id` que no existe (la relación es many-to-many vía `maquina_registrador`), rompía en runtime. Sin uso en la app — `VisorDiario.php` ya arma las lecturas a mano.
- `app/Models/Despacho/Lectura.php` — eliminado `getMaquinaAttribute()`: llamaba a `$this->registrador->maquina` (relación singular inexistente; `Registrador` solo define `maquinas()` plural). Sin uso.
- `docs/modulos/10-DESPACHO.md` — documentados ambos bugs eliminados.

### Documentos — fix de test flaky
- `database/factories/Documentos/DocumentoFactory.php` — `visible` generaba `faker->boolean()` (50/50), haciendo flaky el test "un documento nuevo es visible por defecto". Cambiado a `true` fijo, acorde a lo que el test espera como comportamiento por defecto.

### Fase 2 (revisión superficial) — Documentos, Capacitaciones, Despacho
- `database/migrations/Documentos/2026_07_03_120000_add_categoria_id_to_documentos_table.php` — nueva. `documentos.categoria_id` existía en la base real (NOT NULL, FK a `categorias`, `onDelete cascade`) pero nunca había quedado versionado en ninguna migración; un entorno nuevo corriendo `migrate` desde cero rompía el módulo al crear cualquier documento. Migración marcada como ya aplicada en la tabla `migrations` de la conexión `documentos` (la columna ya existe, no hace falta re-ejecutarla).
- `app/Http/Controllers/Home/DocumentoController.php` — eliminado. Resource controller vacío salvo `categoria_show()`, que duplicaba exactamente lo que ya hace `HomeController::documentoCategoria()`. Ninguna ruta lo referenciaba; las rutas públicas reales de documentos van a `HomeController`.
- `app/Http/Controllers/Documentos/DocumentoController.php` — corregida doble asignación redundante (`$documento->file_storage = $documento->file_storage = ...`) en `store()`.
- `docs/modulos/02-DOCUMENTOS.md` — corregidos campos del modelo (`titulo` no existe, es `nombre`), documentado el drift de `categoria_id`, aclarado qué controller sirve las rutas públicas.
- `app/Http/Controllers/Capacitaciones/RespuestaController.php` — eliminado. Resource controller 100% vacío (los 7 métodos), sin ninguna ruta registrada en `routes/capacitaciones.php`.
- `docs/modulos/06-CAPACITACIONES.md` — quitada mención desactualizada de "no hay notificaciones al invitar" (ya se implementó, ver entrada del 2026-07-02).
- `docs/modulos/10-DESPACHO.md` — documentado en detalle el proceso de carga de archivos PRN: tanto `CargaPrn` como `CargaAutomatica` son cargas manuales disparadas desde el browser (no hay cron ni ruta de red). "Automática" se refiere a que el registrador se autodetecta del header del archivo, no a que el proceso sea periódico — corrige una suposición equivocada que traía la documentación original.

## 2026-07-07

### Usuarios — limpieza y bug documentado en el panel de emails
- `app/Http/Controllers/Usuarios/UserController.php` — eliminado constructor comentado (`__construct` viejo con `$this->middleware(...)`), reemplazado hace tiempo por el método `middleware()` estático que ya está activo.
- `routes/usuarios.php` — eliminada ruta `email-queue.index` duplicada (una copia estaba comentada justo arriba de la activa) y comentarios de relleno sin información real ("Rutas existentes...", comentario desprolijo sobre organización de rutas).
- `app/Http/Controllers/Usuarios/EmailQueueController.php` — eliminado `updateEnvFile()`, método privado que nunca se llamaba (sus dos únicos call-sites estaban comentados desde el principio).
- Investigado y documentado un bug real: los toggles "Habilitar/deshabilitar envíos" y "Filtro de dominio" del panel `/usuarios/email-queue` hacen `config([...])` en tiempo de ejecución, lo que solo afecta el proceso PHP de esa request AJAX puntual. Ni el worker de la cola (disparado manualmente desde el mismo panel, no hay scheduler corriendo `queue:work` en background) ni el siguiente request ven el cambio — vuelven a leer la config real desde `.env`. El botón no hace nada persistente. Documentado en `docs/modulos/01-USUARIOS.md` con propuesta de fix (no implementado: requiere reemplazar `config()` runtime por `Cache` o una tabla de configuración).
- `docs/modulos/01-USUARIOS.md` — documentados además los `destroy()`/`show()` vacíos sin implementar en `Area`/`Sede`/`Modulo`/`Permission`/`RoleController` (feature incompleta, no código muerto: nunca se dio de baja esos recursos desde la UI).
- `docs/ROADMAP.md` — nueva sección de tareas de Usuarios con lo de arriba.
