# Módulo: Documentos

**Base de datos**: `documentos` (`DB_DATABASE_DOCUMENTOS`)  
**Rutas**: `routes/documentos.php` (panel) y `routes/web.php` (portal público)  
**Complejidad**: Baja

---

## Qué hace

Repositorio de publicación de los documentos institucionales de BAESA (reglamentos, políticas,
procedimientos, formularios, folletos). **No gestiona el ciclo de vida documental**: la elaboración,
la codificación y la aprobación son trabajo del área de Control de Gestión, que las administra por
fuera. Acá llega el documento terminado y se pone a disposición. Ver `docs/DECISIONES.md`
(2026-07-28).

- Documentos agrupados en categorías de dos niveles.
- Publicación sin login, declarada por atributo (`publica` / `publico`).
- Historial de versiones: reemplazar un archivo no borra el anterior.
- Registro de cada descarga (quién, IP, cuándo).

---

## Modelos

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `Documento` | `documentos` | Documento con archivo vía Spatie MediaLibrary. SoftDeletes. |
| `Categoria` | `categorias` | Categoría o subcategoría. Jerarquía de dos niveles. |
| `DocumentoVersion` | `documento_versiones` | Versión anterior del archivo de un documento. |
| `Descarga` | `descargas` | Registro de cada descarga (usuario nullable, IP, fecha). |

---

## Documento: campos clave

```
id, nombre, codigo, descripcion, observaciones, archivo, extension, mimeType,
version (int), orden, visible, publico, user_id, categoria_id,
archivo_uploaded_at, created_at, updated_at, deleted_at
```

- **`codigo`** — la codificación de Control de Gestión tal como viene (`L-07.2-003_v3`,
  `PG-07.2-012-v4`, `APG-07.2-012-01-v3`). Es un **string libre**: no se parsea ni se valida el
  formato, y puede estar vacío. El criterio de codificación es de esa área, no de la Plataforma.
- **`version`** — entero que **escribe la persona**, no el sistema: viene sugerido (el siguiente) en
  el modal de nueva versión y es editable en el alta y en la edición. Un documento puede llegar ya
  en la v4 de Control de Gestión. El sistema no parsea el `_vN` del `codigo` para deducirlo: que los
  dos números coincidan lo decide quien carga. Ver `docs/DECISIONES.md` (2026-08-04).
- **`visible`** — el documento está activo en el panel interno. Desmarcarlo es dar de baja sin borrar.
- **`publico`** — descargable sin iniciar sesión, **siempre que su rama de categorías también lo sea**.

---

## Visibilidad pública

La regla vive en el modelo, no en la vista:

- `Categoria::esPublica()` — la categoría es pública y, si es subcategoría, su padre también.
- `Documento::esPublico()` — el documento es público y su categoría es pública.

El menú público se arma con `Categoria::raicesPublicas()`, inyectado por un View Composer en
`AppServiceProvider` sobre las tres navegaciones (`components.navigation-links.guest`,
`layouts.partials.sidebar-navigation`, `layouts.partials.sidebar-navigation-new`).

`HomeController` verifica `esPublico()` / `esPublica()` antes de servir cualquier cosa: conocer el
ID de un documento no alcanza para descargarlo.

---

## Versionado

`Documento::reemplazarArchivo($archivo, $notas, $usuarioId)` es el único camino por el que entra un
archivo al módulo (lo usan el alta y la edición del panel):

1. El archivo vigente se **mueve** de la colección `archivos` a la colección `historial`.
2. Se crea una fila en `documento_versiones` con el número de versión que tenía, el `media_id`
   archivado, el nombre del archivo, las notas del cambio y quién lo hizo.
3. El nuevo archivo entra en `archivos` y el documento pasa al número de versión indicado (sin
   indicar, al siguiente).

La versión nueva tiene que ser **mayor que la vigente**, que es la que se acaba de archivar: repetir
un número rompería el índice único `(documento_id, version)` de `documento_versiones`. Sin subir
archivo, el número se puede corregir desde la edición mientras no baje de lo ya archivado.

`media_id` no tiene FK: la tabla `media` vive en otra base y el aislamiento entre bases no admite
FK cruzadas (axioma 1).

Desde el detalle del documento hay un modal **Nueva versión** que hace el reemplazo sin abrir la
edición completa: pide el archivo, la nota del cambio y el `codigo` (que suele traer su propio `_vN`
nuevo). Todo lo demás se sigue editando desde el formulario de edición.

Las versiones anteriores se descargan desde el detalle del documento y **no se registran en
`descargas`**: esa tabla mide el consumo del documento vigente.

---

## Rutas públicas (sin login)

- `GET /cats/{categoria}` → `HomeController::documentoCategoria()` — 404 si la categoría no es pública.
- `GET /docs/{documento}/download` → `HomeController::documentoDownload()` — sirve el archivo y
  registra la descarga. 404 si el documento no es público.
- `GET /docs/{documento}` — forma vieja del link, que puede estar circulando fuera del sistema.
  Apunta al mismo controlador y respeta la misma regla.

---

## Rutas internas (con login)

Bajo el rol `Documentos/Acceso`:

- `documentos.documentos.*` — resource completo. `destroy` es baja lógica (SoftDeletes) y usa el
  permiso de edición: el módulo no tiene un permiso de borrado propio.
- `documentos.documentos.download` — descarga del documento vigente, registra la descarga.
- `documentos.documentos.versiones.download` — descarga de una versión del historial.
- `documentos.categorias.*` — sólo `index`, `create`, `store`, `show`. Las categorías se editan
  desde el listado (componente Livewire) y **no se borran**: la FK en cascada arrastraría sus
  documentos.

Permisos: `Documentos/Documentos/{Ver,Crear,Editar}` y `Documentos/Categorias/{Ver,Crear,Editar}`.

---

## Componentes Livewire

| Componente | Función |
|-----------|---------|
| `Documentos/Categorias/Show/Edit` | Modal de edición de categoría (nombre, orden, `publica`). Chequea permiso en el componente, no sólo en el `@can` de la vista. |
| `Documentos/Documentos/Index/Search` | Listado del panel agrupado por subcategoría, con búsqueda por nombre, código y descripción (`Documento::scopeBuscar`). El término va en la URL (`?q=`); buscando, las categorías sin coincidencias no se muestran. |
| `Documentos/Documentos/Show/NuevaVersion` | Modal para reemplazar el archivo desde el detalle, sin pasar por la edición completa. Sólo toca archivo, `codigo` y la nota del cambio. Chequea permiso en el componente. |

---

## Estado de los datos (julio 2026)

76 documentos en 6 subcategorías bajo 3 categorías raíz; 72 públicos y 4 dados de baja que se
conservan a pedido. Producción acumula ~3.850 descargas, concentradas en los formularios de RRHH
("Solicitud de Licencia o Fracción" sola lleva más de 1.000). El último documento se cargó en
septiembre de 2025.

**Deuda conocida:** unos 30 de los 76 archivos se subieron sin conservar el nombre original y
quedaron con nombre aleatorio en MediaLibrary (`rg1gFXnpmqaN…pdf`). Su código de Control de Gestión
se perdió y hay que reconstruirlo a mano en la columna `codigo`.

---

## Puntos a mejorar

- No hay pantalla de estadísticas de descargas (los datos están, la vista no).
- Reordenar documentos y categorías se hace escribiendo el número de `orden` a mano.
