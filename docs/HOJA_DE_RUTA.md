# Hoja de Ruta — Refactorización y mejoras

**Actualizada**: Julio 2026  
**Criterio**: empezar por lo que da más valor con menos riesgo. Primero los módulos simples, luego los complejos. Nada de big bang.

---

## Fase 0 — Fundaciones (hacer antes de cualquier otra cosa)

Estas tareas son transversales y habilitan todo lo demás:

- [ ] **Tests básicos**: crear al menos un Feature test por módulo que cubra el flujo principal (index, create, store). Usar Pest. Empezar por módulos sin dependencias cross-base (AdminIP, Fichadas, Mesa de Entradas).
- [ ] **Seeders de desarrollo**: crear seeders con datos de prueba para poder levantar el sistema sin datos reales. Empezar por `usuarios` (roles, permisos, un superadmin, algunos usuarios con distintos roles).
- [ ] **Documentar variables de entorno**: agregar `.env.example` con descripciones de cada variable. Actualmente está pero sin explicaciones.

---

## Fase 1 — Módulos simples (bajo riesgo, valor inmediato)

### AdminIP
- [ ] Agregar validación de formato de IP a nivel de Request.
- [ ] Agregar índice único en campo `ip` a nivel de DB.

### ~~Fichadas~~ — DEPRECADO, ver sección "Módulos a eliminar"

### ~~Mesa de Entradas~~ — DEPRECADO, ver sección "Módulos a eliminar"

### Tickets
- [ ] Agregar notificación por email cuando se crea un ticket y cuando hay un mensaje nuevo.
- [ ] Mover las rutas del usuario de `web.php` a `routes/tickets.php` para consistencia.

---

## Fase 2 — Módulos de complejidad media

### Documentos
- [ ] Agregar campo `publico` al modelo para controlar acceso por campo, no por ruta.
- [ ] Agregar versionado básico (cuando se sube un nuevo archivo para el mismo documento, guardar el anterior).

### Inventario
- [ ] Consolidar la nomenclatura: renombrar tabla `valors` → `valores` en una migración.
- [ ] Agregar foto/imagen al elemento.
- [ ] Agregar exportación a Excel del listado de elementos.

### Capacitaciones
- [ ] Agregar notificaciones automáticas por email al invitar a una capacitación.
- [ ] Agregar vista de resultados/estadísticas de encuestas.
- [ ] Distinguir entre "invitado", "confirmó asistencia" y "asistió" (tres estados, no solo un booleano).

### Automotores
- [ ] Hacer configurable el intervalo de service por vehículo (actualmente hardcodeado a 10.000 km).
- [ ] Agregar exportación a Excel de COPRES.
- [ ] Aclarar y documentar el campo `kz` en COPRES.

### Despacho
- [ ] Documentar el proceso de carga automática de archivos PRN (ruta de red, schedule de Laravel).
- [ ] Agregar validación de lecturas fuera de rango.

---

## Fase 3 — Módulos complejos (mayor riesgo, mayor impacto)

### Usuarios
- [ ] Consolidar campos duplicados en `users`: definir si usar `name`/`realname` o `nombre`/`apellido` y migrar al patrón elegido.
- [ ] Crear política formal de nomenclatura de roles y documentarla.
- [ ] Separar la gestión de jobs de email del módulo Usuarios a un módulo o panel propio.

### Proveedores
- [ ] Extraer la lógica de validación de documentos a un `DocumentoValidacionService`.
- [ ] Revisar y documentar el método `falta_a_vencimiento()` — hay un `addYear()` que parece un workaround o bug.
- [ ] Renombrar tabla `proveedors` → `proveedores` con migración y actualización de todos los modelos que la referencian. **Alto impacto — hacer con cuidado y backup.**
- [ ] Agregar workflow de aprobación del proveedor (en lugar de cambio manual de estado).

### Concursos
- [ ] Crear un job/comando Artisan que actualice automáticamente el estado de concursos vencidos/cerrados a nivel de DB (actualmente es solo virtual en PHP).
- [ ] Documentar el proceso de gestión de claves de encriptación.
- [ ] Implementar como flujo guiado en UI la apertura de sobres (transición a `analisis` + desencriptación).
- [ ] Renombrar `ConcursoDocumento` y `Documento` (de oferta) para que la distinción sea más clara en el código.

---

---

## Módulos a eliminar

Estos módulos nunca se usaron en producción y no tienen futuro en el sistema. Se deben eliminar cuando haya una ventana de tiempo segura.

### Fichadas
Qué borrar:
- [ ] Archivo `routes/fichadas.php`
- [ ] Carpeta `app/Http/Controllers/Fichadas/`
- [ ] Carpeta `app/Models/Fichadas/`
- [ ] Carpeta `app/Livewire/Fichadas/` (si existe)
- [ ] Carpeta `resources/views/fichadas/`
- [ ] Registro en tabla `modulos` (base `usuarios`)
- [ ] Variable `DB_DATABASE_FICHADAS` en `.env` (y su conexión en `config/database.php`)
- [ ] Base de datos `fichadas` (coordinar con quien la creó; puede ser externa)

### Mesa de Entradas
Qué borrar:
- [ ] Archivo `routes/mesadeentradas.php`
- [ ] Carpeta `app/Http/Controllers/MesaDeEntradas/`
- [ ] Carpeta `app/Models/MesaDeEntradas/`
- [ ] Carpeta `app/Livewire/MesaDeEntradas/` (si existe)
- [ ] Carpeta `resources/views/mesadeentradas/`
- [ ] Carpeta `database/migrations/MesaDeEntradas/`
- [ ] Registro en tabla `modulos` (base `usuarios`)
- [ ] Variable `DB_DATABASE_MESADEENTRADAS` en `.env` (y su conexión en `config/database.php`)
- [ ] Base de datos `mesadeentradas`

**Precaución**: verificar que el módulo esté en estado `inactivo` en la tabla `modulos` antes de borrar el archivo de rutas, para que no explote si hay alguna referencia colgada.

---

## Notas de proceso

- Antes de cada tarea de Fase 3 que toque tablas: hacer un backup de la base correspondiente.
- Los cambios de naming de tablas (proveedors → proveedores) son de alto impacto: hay que actualizar modelos, queries hardcodeadas, joins con nombre de tabla explícito. Hacer en una rama separada y probar bien antes de mergear.
- Cualquier cambio en el módulo de Proveedores que afecte la API externa requiere coordinar con el portal de proveedores.
