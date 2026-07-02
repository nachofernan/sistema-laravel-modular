# Módulo: Concursos

**Base de datos**: `concursos` (`DB_DATABASE_CONCURSOS`)  
**Rutas**: `routes/concursos.php`  
**Complejidad**: Alta

---

## Qué hace

Gestión completa del proceso de concurso de precios de BAESA (proceso de compras/contrataciones):

- Crear y administrar concursos con estados, fechas, rubros y sedes.
- Invitar proveedores del registro a participar.
- Los proveedores pueden declarar su intención de participar (o no).
- Los proveedores suben documentación requerida por el concurso.
- Posibilidad de encriptar los documentos de oferta (apertura en sobre cerrado digital).
- Gestión de prórrogas (extensión de la fecha de cierre).
- Contactos del concurso (personas responsables en BAESA).
- Historial de cambios de estado.
- Generación de PDF de resumen del concurso.
- Calendario de concursos.
- Envío masivo de emails a proveedores invitados.
- **API REST** (JWT) para que los proveedores interactúen desde el portal externo.

---

## Estados de un concurso

Los estados en BD son estáticos, pero el modelo implementa una lógica de estado virtual:

| estado_id | Estado base | Estado real (computed) |
|-----------|-------------|----------------------|
| 1 | Precarga | `precarga` (en preparación) / `vencido` (si fecha_cierre pasó) |
| 2 | Activo | `activo` (abierto para participar) / `cerrado` (si fecha_cierre pasó) |
| 3 | Análisis | `analisis` (en evaluación de ofertas) |
| 4 | Terminado | `terminado` |
| 5 | Anulado | `anulado` |

El accessor `getEstadoActualAttribute()` calcula el estado real. El método `editable()` determina si un concurso puede modificarse.

---

## Modelos

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `Concurso` | `concursos` | Concurso de precios. |
| `Estado` | `estados` | Estado base del concurso. |
| `Invitacion` | `invitacions` | Invitación de un proveedor a un concurso. |
| `ConcursoDocumento` | `documentos` | Documento del concurso (documentación base publicada por BAESA). |
| `Documento` | (tabla oferta) | Documento subido por un proveedor invitado como parte de su oferta. |
| `DocumentoTipo` | `documento_tipos` | Tipo de documento requerido por el concurso. |
| `Prorroga` | `prorrogas` | Prórroga (extensión de la fecha de cierre). |
| `ConcursoSede` | `concurso_sede` | Pivot: concurso habilitado para una sede. |
| `Contacto` | `contactos` | Contacto interno del concurso (responsable de BAESA). |
| `Historial` | `historials` | Registro de cambios de estado del concurso. |

---

## Concurso: campos clave

```
id, nombre, numero (asignado al pasar a activo), descripcion,
fecha_inicio, fecha_cierre, numero_legajo, legajo,
estado_id, subrubro_id, user_id (gestor),
permite_carga (bool), created_at, updated_at
```

---

## Invitacion: campos clave

```
id, concurso_id, proveedor_id, intencion (0=sin respuesta, 1=participa, 2=no participa, 3=ofertó),
fecha_envio, observaciones, created_at, updated_at
```

---

## Tipos de documentos en un concurso

Hay dos tipos de documentos en un concurso:

1. **Documentos del concurso** (`ConcursoDocumento`): archivos publicados por BAESA que todos los invitados pueden ver y descargar (pliego, especificaciones, etc.).
2. **Documentos de oferta** (via `Invitacion → Documento`): archivos que cada proveedor sube como parte de su oferta. Pueden estar encriptados.

La tabla `concurso_documento_tipo` vincula qué tipos de documentos se requieren para un concurso específico (con flag `de_concurso` para distinguir si es un doc del concurso o de la oferta del proveedor).

---

## Encriptación de documentos (sobre cerrado digital)

Los documentos de oferta (`oferta_documentos`, campo `encriptado = true`) se almacenan cifrados en disco. Los proveedores suben su oferta encriptada durante el período activo; solo se pueden abrir cuando el concurso pasa a estado de análisis, operación que hace el comité de apertura.

**Algoritmo:** AES-256-CBC. El IV (16 bytes) se escribe al inicio de cada archivo.

**Clave:** variable de entorno `CONCURSO_ENCRYPTION_KEY` (valor base64 de una clave de 32 bytes). Se configura en `.env` y se accede vía `config('app.concurso_encryption_key')`.

**⚠️ Backup obligatorio:** si la clave se pierde o cambia, los documentos de ofertas no abiertas son **irrecuperables para siempre**. La clave debe estar respaldada en al menos dos lugares seguros independientes del servidor.

**Flujo de apertura (manual, por comité):**
1. El concurso cierra (fecha_cierre pasa).
2. El comité abre el panel de acciones y ve el resumen: N ofertas presentadas, N documentos a desencriptar, N documentos a eliminar.
3. Hace clic en "Abrir Ofertas" → `AccionesConcurso::actualizarEstado(3)`.
4. El sistema llama a `ConcursoEncryptionService::bulkDecryptOfertas()`: desencripta documentos de intencion=3, elimina documentos de intencion≠3.
5. El concurso queda en estado "análisis" y los documentos son accesibles.

Esta operación es **irreversible**.

**Servicios involucrados:**
- `app/Services/ConcursoEncryptionService.php` — encriptación/desencriptación de ofertas (AES-256-CBC, clave de .env).
- `app/Services/FileEncryptionService.php` — encriptación general de archivos (AES-256-GCM, clave autogenerada en `storage/app/encryption_key`). Sistema independiente, no intercambiable con el anterior.
- `app/Services/ConcursoFileService.php` — lógica de negocio para archivos de concursos.

---

## Componentes Livewire

| Componente | Función |
|-----------|---------|
| `Concursos/Concurso/Index` | Listado de concursos con filtros |
| `Concursos/Concurso/Create` | Creación de concurso |
| `Concursos/Concurso/Show/` | Vista detalle (con subcomponentes) |
| `Concursos/Concurso/InvitarProveedor` | Modal de invitación de proveedores |
| `Concursos/Concurso/Rubros` | Gestión de rubros del concurso |

---

## Relación con Proveedores

- `Concurso::subrubro()` → `Subrubro` (base `proveedores`): el concurso se clasifica en un subrubro del registro de proveedores.
- `Invitacion::proveedor()` → `Proveedor` (base `proveedores`): el proveedor invitado vive en la base de proveedores.
- Los correos de invitación se construyen con `Concurso::getCorreosInteresados()` que agrega proveedor, contactos del proveedor y contactos del concurso.

---

## API REST para el portal externo

Ver `docs/ESPECIFICACIONES_TECNICAS_CONCURSOS.md` para detalle completo. Resumen:

- Auth: JWT con CUIT + email del proveedor (token 10 min).
- `GET /api/concursos` — concursos del proveedor.
- `GET /api/concursos/{id}` — detalle de un concurso.
- `PATCH /api/concursos/{id}/invitacion` — cambiar intención de participar.
- `POST /api/concursos/{id}/documentos` — subir documento de oferta.
- `GET /api/concursos/{id}/documentos` — ver documentos subidos.
- `GET /api/concursos/{id}/documentos/{docId}/descargar` — descargar documento.

---

## EmailDispatcher y envíos masivos

El sistema puede enviar emails masivos a todos los invitados de un concurso. El flujo:
1. Desde la UI, se selecciona qué grupos reciben el email (proveedores, contactos de proveedores, contactos del concurso).
2. `Concurso::getCorreosInteresados()` construye la lista de destinatarios.
3. Se disparan jobs en cola para enviar sin bloquear la UI.
4. Los envíos quedan registrados en `email_logs`.

---

## Reestructuración de documentos (2025)

La migración `2025_07_27_000001_reestructurar_documentos_concursos.php` fue un cambio importante en la estructura de la tabla de documentos del concurso. Ver el archivo para detalle.

---

## Puntos a mejorar

- La distinción entre `ConcursoDocumento` y `Documento` (de oferta) es conceptualmente clara pero su implementación en código puede ser confusa; hay que revisar los nombres.
- El campo `permite_carga` en el concurso y `obligatorio` en `documento_tipos` están íntimamente relacionados pero la lógica de qué hacer cuando no se puede cargar y hay tipos obligatorios no está documentada.
- Los estados `vencido` y `cerrado` son virtuales (calculados en PHP) por diseño — la transición a "análisis" es siempre manual (decisión del comité), no automática.
