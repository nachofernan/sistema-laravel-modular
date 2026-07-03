# Roadmap

Tareas pendientes y realizadas desde Julio 2026. Las tareas anteriores a esta fecha
están en `docs/HOJA_DE_RUTA.md` (legacy).

Leyenda: ✅ hecho · 🔲 pendiente · ⏸ postergado/a futuro · ❌ descartado

---

## Fundaciones

| Estado | Tarea |
|--------|-------|
| ✅ | Tests: infraestructura base (DatabaseTransactions multi-DB, Pest funcional) |
| ✅ | Tests: cobertura básica de modelos — AdminIP, Usuarios, Tickets, Inventario, Documentos, Capacitaciones |
| 🔲 | Tests: cobertura básica de modelos — Automotores, Despacho |
| ✅ | Tests: cobertura básica de modelos — Proveedores (ya existían, todos en verde) |
| ✅ | Tests: cobertura básica de modelos — Concursos (ya existían; limpieza de drift de esquema, ver sección Concursos) |
| 🔲 | Seeders de desarrollo: usuarios con roles, datos mínimos por módulo |
| ✅ | Documentación: CLAUDE.md, ARQUITECTURA.md, docs/modulos/ (12 módulos) |

---

## AdminIP

| Estado | Tarea |
|--------|-------|
| ✅ | Validación de formato de IP — ya existía en los componentes Livewire (`Crear`/`Editar`), no como `FormRequest`; tarea de la hoja de ruta legacy dada por cumplida |
| ✅ | Limpieza: eliminado `CategoriaController` (resource vacío), ruta `adminip.categorias.*` y link de nav comentado — sin ninguna vista ni uso real |
| ⏸ | Migración: índice único en campo `ip` (verificar duplicados antes de aplicar) |
| ⏸ | `categoria_id` existe en `ips` (FK a `categorias`) pero no está implementado en la app (sin relación Eloquent, sin campo en formularios): implementar la categorización real o eliminar la columna y la tabla `categorias` |

---

## Tickets

| Estado | Tarea |
|--------|-------|
| ✅ | Limpieza: eliminar código muerto comentado en los tres controllers (`Mail::to`, `dispatch` legacy) |
| ✅ | Rutas: comentario en `web.php` aclarando la separación usuario/encargado |

---

## Documentos

| Estado | Tarea |
|--------|-------|
| 🔲 | Campo `publico` en modelo/tabla para control de acceso por campo (actualmente es por ruta) |
| 🔲 | Versionado básico de archivos |

---

## Inventario

| Estado | Tarea |
|--------|-------|
| ⏸ | Migración: renombrar tabla `valors` → `valores` |
| ✅ | Limpieza: eliminar query muerto `Elemento::all()` en `index()` |
| ✅ | Validación en `ElementoController::store()` |
| ✅ | Exportación a Excel del listado de elementos |

---

## Capacitaciones

| Estado | Tarea |
|--------|-------|
| ✅ | Notificaciones por email al invitar a una capacitación |
| 🔲 | Vista de resultados/estadísticas de encuestas |

---

## Automotores

| Estado | Tarea |
|--------|-------|
| ✅ | Extraer constantes en `getNecesitaServiceAttribute()` (KM_INTERVALO_SERVICE y ventanas) |
| ✅ | Documentar campo `kz` en `Copres` (identificador de factura SAP) |
| ✅ | Exportación a Excel de COPRES |
| ✅ | Limpieza: eliminar query muerto en `CopresController::index()` |

---

## Despacho

| Estado | Tarea |
|--------|-------|
| 🔲 | Documentar el proceso de carga automática de archivos PRN |

---

## Proveedores

| Estado | Tarea |
|--------|-------|
| ⏸ | Migración: renombrar tabla `proveedors` → `proveedores` (alto impacto, requiere backup) |
| ✅ | Repair loop en `ValidacionController::index()` → movido a comando `proveedores:reparar-validaciones` |
| ✅ | Limpieza de código muerto en `DocumentoController` y `Proveedor` model |
| ✅ | Documentar `falta_a_vencimiento()` (addYear = workaround 32-bit + fechas lejanas) |
| ❌ | Extraer lógica de validación a un servicio (descartado: lógica ya bien distribuida, sin duplicación) |

---

## Concursos

| Estado | Tarea |
|--------|-------|
| ❌ | Job/comando para auto-actualizar estados en DB (descartado: transición manual por diseño, decisión del comité) |
| ✅ | Documentar gestión de claves de encriptación (ConcursoEncryptionService + docs/modulos/12-CONCURSOS.md) |
| ✅ | Panel de resumen previo a apertura de sobres (invitados, ofertas, docs a desencriptar/eliminar) |
| ✅ | Limpieza de código muerto en Concurso model, ProrrogaController y AccionesConcurso |
| ✅ | Limpieza de drift de esquema post-migración `reestructurar_documentos_concursos` (jul 2025): eliminado modelo `Documento`/factory/test viejos (tabla `documentos` ya no existe), corregidos `DocumentoTipoFactory` y `OfertaDocumentoFactory` (campos `encriptado`/`validado` que no existen en las tablas actuales) |
| 🔲 | `ConcursoControllerTest` (API JWT) falla con 401 en vez del status esperado en 6 de 8 tests: usa `RefreshDatabase` en vez de `DatabaseTransactions` (inconsistente con el resto de la suite) y el token de `/api/generate-token` no autentica bien en el entorno de test. Requiere investigación aparte |

---

## Usuarios

| Estado | Tarea |
|--------|-------|
| ⏸ | Migración: consolidar campos duplicados `name`/`realname`/`nombre`/`apellido` en `users` |

---

## A eliminar (módulos deprecados)

| Estado | Tarea |
|--------|-------|
| 🔲 | Eliminar módulo Fichadas (rutas, controllers, modelos, vistas, registro en `modulos`) |
| 🔲 | Eliminar módulo Mesa de Entradas (ídem) |
