# Módulo: Inventario

**Base de datos**: `inventario` (`DB_DATABASE_INVENTARIO`)  
**Rutas**: `routes/inventario.php`  
**Complejidad**: Media

---

## Qué hace

Administración del equipamiento IT de BAESA (computadoras, monitores, teclados, periféricos, etc.):

- Registro de elementos con categoría, características técnicas y valores.
- Seguimiento de a quién está asignado cada elemento.
- Historial de entregas y devoluciones.
- Registro de modificaciones (cambios de componentes, actualizaciones).
- Gestión de periféricos asociados a elementos principales.
- Categorías con prefijo para generar códigos automáticos (ej: `NB00001` para notebooks).

---

## Modelos

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `Elemento` | `elementos` | Elemento de inventario (PC, monitor, etc.). |
| `Categoria` | `categorias` | Categoría con icono y prefijo (ej: `NB` para notebooks). |
| `Caracteristica` | `caracteristicas` | Característica técnica de una categoría (ej: "RAM", "Procesador"). |
| `Valor` | `valors` | Valor de una característica para un elemento específico. |
| `Estado` | `estados` | Estado del elemento (Activo, Baja, Reparación, etc.). |
| `Entrega` | `entregas` | Asignación de un elemento a un usuario con fecha. |
| `Modificacion` | `modificacions` | Registro de cambios en el elemento (upgrade de RAM, etc.). |
| `Opcion` | `opcions` | Opciones predefinidas para características de tipo "select". |
| `Periferico` | `perifericos` | Periférico asociado a un elemento (mouse, teclado, auriculares). |

---

## Elemento: campos clave

```
id, codigo (generado automáticamente), nombre, descripcion, numero_serie,
categoria_id, estado_id, user_id (usuario actual asignado), sede_id, area_id,
created_at, updated_at
```

El código se genera con `Elemento::createCodigo($categoria_id)`: toma el prefijo de la categoría y lo concatena con un número padded de 5 dígitos (ej: `NB00023`).

---

## Asignación (Entregas)

La asignación de un elemento a un usuario se gestiona via la tabla `entregas`:

```
id, elemento_id, user_id, fecha_entrega, fecha_devolucion (null = activa)
```

- Un elemento tiene una sola entrega activa a la vez (la que tiene `fecha_devolucion` null).
- `Elemento::entregaActual()` — entrega activa del usuario asignado actualmente.
- `Elemento::entregaAbierta()` — cualquier entrega sin fecha de devolución.
- Cuando se reasigna, se cierra la entrega anterior (se pone `fecha_devolucion`).

---

## Sistema de características

Las categorías tienen características definidas (ej: la categoría "Notebook" puede tener "Procesador", "RAM", "Disco"). Cada elemento tiene valores para esas características en la tabla `valors`.

```
Categoria → tiene → Caracteristicas
Elemento → tiene → Valors (cada Valor apunta a una Caracteristica y al Elemento)
```

Las características pueden ser de tipo texto libre u opciones predefinidas (`opcions`).

---

## Componentes Livewire

| Componente | Función |
|-----------|---------|
| `Inventario/Elementos/Index` | Listado de elementos con filtros |
| `Inventario/Elementos/Show` | Vista detalle + historial |
| `Inventario/Areas/Index` | Áreas (vincula con módulo Usuarios) |
| `Inventario/Categorias/Index` | Gestión de categorías |
| `Inventario/Perifericos/Index` | Periféricos |
| `Inventario/Users/Index` | Vista de elementos por usuario |

---

## Controladores

`app/Http/Controllers/Inventario/` tiene un controlador por entidad:
`ElementoController`, `EntregaController`, `CategoriaController`, `CaracteristicaController`, `EstadoController`, `ModificacionController`, `PerifericoController`, `ValorController`, `UserController`.

---

## Relaciones cross-base de datos

- `Elemento::user()` → `User` (base `usuarios`): no hay FK real, es una relación por `user_id`.
- `Elemento::sede()` → `Sede` (base `usuarios`): igual.
- `Elemento::area()` → `Area` (base `usuarios`): igual.

---

## Puntos a mejorar

- `Valor` tiene nombre de tabla `valors` (inconsistente con el modelo en español). Legacy del inicio del proyecto.
- No hay foto/imagen de elemento. Para equipamiento físico sería útil.
- No hay integración con QR/código de barras para escaneo físico.
- El sistema de características es flexible pero complejo de mantener desde la UI; agregar/quitar características a una categoría existente no recalcula los elementos sin valor.
