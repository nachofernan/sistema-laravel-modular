# Módulo: Tickets

**Base de datos**: `tickets` (`DB_DATABASE_TICKETS`)  
**Rutas**: `routes/tickets.php` + rutas en `routes/web.php` (home)  
**Complejidad**: Baja

---

## Qué hace

Sistema de soporte interno (help desk) para que el personal de BAESA pueda reportar problemas o hacer solicitudes al área de Sistemas:

- Cualquier usuario autenticado puede abrir un ticket.
- El ticket tiene categoría, estado y mensaje inicial.
- Se puede agregar mensajes de seguimiento (hilo de conversación).
- Un encargado (usuario de Sistemas) se asigna al ticket.
- Los mensajes tienen flag de "leído" para indicar mensajes nuevos.
- Se puede adjuntar un documento al ticket.
- Al finalizar se cierra el ticket con fecha de finalización.

---

## Modelos

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `Ticket` | `tickets` | Ticket de soporte. |
| `Mensaje` | `mensajes` | Mensaje dentro del hilo del ticket. |
| `Estado` | `estados` | Estado del ticket (Abierto, En proceso, Cerrado, etc.). |
| `Categoria` | `categorias` | Tipo de problema (Hardware, Software, Red, etc.). |
| `Documento` | `documentos` | Documento adjunto opcional al ticket. |

---

## Ticket: campos clave

```
id, titulo, descripcion, estado_id, categoria_id,
user_id (quien lo abrió), user_encargado_id (quien lo atiende),
finalizado (timestamp de cierre, null = abierto),
created_at, updated_at
```

---

## Flujo típico

1. Usuario crea ticket con título, descripción y categoría.
2. Encargado ve el ticket en su panel, lo toma y cambia el estado a "En proceso".
3. Se intercambian mensajes en el hilo hasta resolver el problema.
4. Encargado cierra el ticket (se pone `finalizado = now()`).

---

## Mensajes

```
id, ticket_id, user_id, mensaje, leido (bool), created_at
```

El método `Ticket::mensajesNuevos()` cuenta los mensajes no leídos del usuario dueño del ticket. Esto permite mostrar un badge de "nuevos mensajes" en el listado de tickets.

---

## Acceso

- **Usuarios normales**: ven y gestionan sus propios tickets desde `/home/tickets`.
- **Encargados (Sistemas)**: ven todos los tickets desde el panel del módulo con filtros por estado.

Las rutas del usuario normal están en `web.php` bajo el grupo `home`. Las del encargado están en `routes/tickets.php` protegidas por rol.

---

## Componente Livewire

| Componente | Función |
|-----------|---------|
| `Tickets/Tickets/Index` | Listado de tickets para el encargado |

---

## Puntos a mejorar

- No hay notificaciones automáticas (email) cuando se crea o actualiza un ticket.
- El campo `leido` solo controla mensajes en una dirección (del encargado al usuario); no hay tracking inverso.
- No hay SLA ni tiempos de respuesta.
- El módulo de tickets para el usuario está acoplado a la home (`web.php`) en lugar de estar completamente en su propio archivo de rutas.
