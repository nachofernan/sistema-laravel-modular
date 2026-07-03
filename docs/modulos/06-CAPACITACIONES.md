# Módulo: Capacitaciones

**Base de datos**: `capacitaciones` (`DB_DATABASE_CAPACITACIONES`)  
**Rutas**: `routes/capacitaciones.php`  
**Complejidad**: Media

---

## Qué hace

Gestión de capacitaciones internas de BAESA:

- Crear y administrar capacitaciones con fechas de inicio y fin.
- Invitar a usuarios a una capacitación.
- Adjuntar documentación a cada capacitación (material, presentaciones).
- Crear encuestas de satisfacción o evaluación post-capacitación.
- Registrar las respuestas de los participantes a las encuestas.

---

## Modelos

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `Capacitacion` | `capacitacions` | Capacitación con nombre, descripción y fechas. |
| `Invitacion` | `invitacions` | Invitación de un usuario a una capacitación. |
| `Documento` | `documentos` | Material adjunto a la capacitación. |
| `Encuesta` | `encuestas` | Encuesta asociada a una capacitación. |
| `Pregunta` | `preguntas` | Pregunta de la encuesta. |
| `Opcion` | `opcions` | Opciones de respuesta para preguntas de selección. |
| `Respuesta` | `respuestas` | Respuesta de un usuario a una pregunta. |

---

## Capacitacion: campos clave

```
id, nombre, descripcion, fecha_inicio (date), fecha_final (date),
created_at, updated_at
```

---

## Invitacion: campos clave

```
id, capacitacion_id, user_id, asistio (bool), created_at, updated_at
```

La invitación vincula un usuario a una capacitación y registra si finalmente asistió.

---

## Encuestas

Cada capacitación puede tener una o varias encuestas. Cada encuesta tiene preguntas, y cada pregunta puede tener opciones de respuesta predefinidas (múltiple choice) o ser abierta (texto libre).

```
Encuesta
  └── Pregunta (tipo: texto | opciones)
        └── Opcion (si es múltiple choice)
        └── Respuesta (por usuario)
```

---

## Documentos adjuntos

Los documentos de capacitaciones se gestionan con un modelo `Documento` propio del módulo (distinto al de Documentos institucionales). Permiten adjuntar archivos de material (PDFs, PPTs) que los invitados pueden descargar.

Ruta de descarga: `GET /home/capacitacions/documentos/{documento}` — accesible para cualquier usuario autenticado (no requiere rol especial).

---

## Componentes Livewire

| Componente | Función |
|-----------|---------|
| `Capacitaciones/Capacitacions/Index` | Listado de capacitaciones |
| `Capacitaciones/Encuesta/...` | Gestión de encuestas |

---

## Relación con Usuarios

`Invitacion` referencia `user_id` de la base `usuarios`. El modelo `User` tiene `hasMany(Invitacion::class)` para acceder a las capacitaciones del usuario.

---

## Actualización reciente (2025)

La migración `2025_08_26_102509_update_capacitaciones_and_invitaciones_structure.php` modificó la estructura de capacitaciones e invitaciones. Ver el archivo de migración para el detalle de los cambios.

---

## Puntos a mejorar

- No hay diferencia entre "invitado" y "confirmó asistencia" (solo `asistio` booleano post-hecho).
- Las encuestas son funcionales pero básicas: no hay tipos de pregunta avanzados (escala Likert, ranking).
- No hay vista de resultados/estadísticas de encuestas.
