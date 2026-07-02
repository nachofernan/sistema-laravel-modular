# Módulo: Proveedores

**Base de datos**: `proveedores` (`DB_DATABASE_PROVEEDORES`)  
**Rutas**: `routes/proveedores.php`  
**Complejidad**: Alta

---

## Qué hace

Registro y gestión completa de proveedores de BAESA. Es uno de los módulos más completos:

- Alta, baja y modificación de proveedores (razón social, CUIT, correo, etc.).
- Clasificación por rubros y subrubros.
- Gestión de contactos por proveedor.
- Gestión de apoderados con documentación respaldatoria.
- Datos bancarios del proveedor.
- Direcciones del proveedor.
- **Documentación**: cada proveedor sube documentos de tipos predefinidos (constancia AFIP, certificado de no retención, etc.) que son validados por el área.
- **Validación de documentos**: un operador puede aprobar o rechazar cada documento subido.
- **Alertas de vencimiento**: los documentos con fecha de vencimiento generan alertas cuando están próximos a vencer.
- **Portal externo**: existe una API REST (JWT) para que los propios proveedores gestionen sus documentos sin necesidad de cuenta interna.
- Export a Excel y PDF del registro de proveedor.
- Estado del proveedor (activo, suspendido, inhabilitado, etc.).
- Flag de litigio.

---

## Modelos

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `Proveedor` | `proveedors` | Proveedor principal. |
| `Estado` | `estados` | Estado del proveedor. |
| `Contacto` | `contactos` | Persona de contacto del proveedor. |
| `Apoderado` | `apoderados` | Apoderado legal del proveedor. |
| `Bancario` | `bancarios` | Datos bancarios (CBU, banco, cuenta). |
| `Direccion` | `direccions` | Dirección del proveedor. |
| `Documento` | `documentos` | Documento subido por/para el proveedor. |
| `DocumentoTipo` | `documento_tipos` | Tipo de documento requerido (con código, nombre, si vence, si es obligatorio). |
| `Validacion` | `validacions` | Validación de un documento (aprobado/rechazado + motivo). |
| `Rubro` | `rubros` | Rubro/categoría de negocio. |
| `Subrubro` | `subrubros` | Subrubro dentro de un rubro. |
| `ProveedorExterno` | `proveedor_externo` | Credenciales del proveedor para el portal externo. |

---

## Proveedor: campos clave

```
id, razonsocial, cuit, correo, telefono, estado_id,
litigio (bool), user_id_created, user_id_updated,
created_at, updated_at
```

---

## Sistema de documentos del proveedor

Es el núcleo más complejo del módulo. Los documentos son **polimórficos** (pueden pertenecer a un `Proveedor` o a un `Apoderado`):

```
Documento
  documentable_type: 'App\Models\Proveedores\Proveedor' | 'App\Models\Proveedores\Apoderado'
  documentable_id: id del proveedor o apoderado
  documento_tipo_id: tipo de documento
  vencimiento: fecha de vencimiento (null si no vence)
```

Cada documento tiene una `Validacion` asociada (1:1) con `validado: bool` y opcionalmente un motivo de rechazo.

### Métodos clave de Proveedor para documentos:
- `documentos($soloValidados)` — todos los documentos (o solo los validados) del proveedor.
- `ultimo_documento($tipo, $soloValidados)` — el documento más reciente de un tipo.
- `traer_documento_valido($tipo, $fecha_limite)` — documento validado, no vencido, cargado antes de una fecha (usado en Concursos para validar documentación al momento de la invitación).
- `ultimosDocumentosValidados()` — un documento validado por cada tipo (el más reciente).
- `falta_a_vencimiento()` — días al próximo vencimiento. Retorna `-1` si ya venció, `15` si vence en menos de 30 días.

---

## Tipos de documentos

La tabla `documento_tipos` define qué documentos puede/debe tener un proveedor:
```
id, codigo, nombre, descripcion, vencimiento (bool), obligatorio (bool)
```

Algunos tipos tienen `tipo_documento_proveedor_id` para vincularse con los tipos de documento del módulo de Concursos (para cuando los concursos requieren documentación del proveedor).

---

## Rubros y subrubros

Los proveedores se clasifican por rubro/subrubro de actividad. La relación es many-to-many entre `Proveedor` y `Subrubro` (tabla pivot `proveedor_subrubro`).

```
Rubro (ej: "Construcción")
  └── Subrubro (ej: "Electricidad", "Plomería")
        └── Proveedores (muchos proveedores por subrubro)
```

---

## Portal externo (ProveedorExterno)

El modelo `ProveedorExterno` vincula un proveedor con credenciales para acceder al portal externo (autenticación por CUIT/username). Ver documentación de la API en `docs/ESPECIFICACIONES_TECNICAS_CONCURSOS.md` y `docs/API_OVERVIEW.md`.

---

## Componentes Livewire

| Componente | Función |
|-----------|---------|
| `Proveedores/Proveedors/Index` | Listado con filtros y búsqueda |
| `Proveedores/Proveedors/Show` | Vista detalle del proveedor |
| `Proveedores/Rubros/Index` | Gestión de rubros |
| `Proveedores/DocumentoTipos/Index` | Tipos de documento requeridos |
| `Proveedores/Validacions/Index` | Cola de validación de documentos |
| `Proveedores/Externos/Index` | Gestión de cuentas del portal externo |
| `Proveedores/Anexosolped` | Vista especial para proceso de SOLPED |

---

## Controladores

`app/Http/Controllers/Proveedores/`:
- `ProveedorController` — CRUD + export + PDF
- `ContactoController`, `ApoderadoController`, `DireccionController`, `BancarioController`
- `DocumentoController`, `DocumentoTipoController`, `ValidacionController`
- `RubroController`, `SubrubroController`

---

## Relación con Concursos

Proveedores y Concursos están fuertemente relacionados:
- Un concurso invita a proveedores → modelo `Invitacion` en la base `concursos`.
- La validez de los documentos del proveedor se evalúa al momento de la invitación.
- El método `traer_documento_valido($tipo, $fecha_limite)` es clave para este flujo.

---

## Reestructuración de documentos (2025)

La migración `2025_01_22_*` fue una reestructuración importante de la tabla de documentos del módulo: backup → delete → recreate → migrate data → clean backup. Fue un cambio significativo que agrega la estructura polimórfica.

---

## Puntos a mejorar

- El método `falta_a_vencimiento()` en `Proveedor` tiene lógica confusa con `addYear()` que puede ser un bug o workaround — merece revisión.
- Los controladores son grandes; la lógica de validación de documentos podría extraerse a un servicio.
- `proveedors` como nombre de tabla es un artefacto del pluralizador inglés de Laravel aplicado a un modelo en español. Debería ser `proveedores` pero cambiarlo requiere migración.
- No hay workflow de aprobación del proveedor en sí (solo de sus documentos); el estado del proveedor se cambia manualmente.
