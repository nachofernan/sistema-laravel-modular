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
| 🔲 | Tests: cobertura básica de modelos — Proveedores, Concursos |
| 🔲 | Seeders de desarrollo: usuarios con roles, datos mínimos por módulo |
| ✅ | Documentación: CLAUDE.md, ARQUITECTURA.md, docs/modulos/ (12 módulos) |

---

## AdminIP

| Estado | Tarea |
|--------|-------|
| ⏸ | Migración: índice único en campo `ip` (verificar duplicados antes de aplicar) |

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
| 🔲 | Exportación a Excel del listado de elementos |

---

## Capacitaciones

| Estado | Tarea |
|--------|-------|
| 🔲 | Notificaciones por email al invitar a una capacitación |
| 🔲 | Vista de resultados/estadísticas de encuestas |

---

## Automotores

| Estado | Tarea |
|--------|-------|
| 🔲 | Intervalo de service configurable por vehículo (actualmente hardcodeado a 10.000 km) |
| 🔲 | Exportación a Excel de COPRES |
| 🔲 | Documentar/aclarar el campo `kz` en COPRES |

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
| 🔲 | Extraer lógica de validación de documentos a un servicio |
| 🔲 | Revisar método `falta_a_vencimiento()` — el `addYear()` parece un workaround sin documentar |

---

## Concursos

| Estado | Tarea |
|--------|-------|
| 🔲 | Job/comando Artisan para actualizar estados vencidos/cerrados automáticamente en DB |
| 🔲 | Documentar gestión de claves de encriptación |
| 🔲 | Flujo guiado en UI para apertura de sobres (transición a análisis + desencriptación) |

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
