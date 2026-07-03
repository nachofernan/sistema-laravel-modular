# Módulo: Documentos

**Base de datos**: `documentos` (`DB_DATABASE_DOCUMENTOS`)  
**Rutas**: `routes/documentos.php`  
**Complejidad**: Baja

---

## Qué hace

Gestión de documentos institucionales de BAESA (reglamentos, procedimientos, circulares, etc.) que se ponen a disposición del personal. Permite:

- Subir documentos agrupados por categoría.
- Filtrar por sede (cada sede ve sus propios documentos + los generales).
- Registrar quién descargó cada documento y cuándo.
- Acceso público (sin login) para algunos documentos desde la home.
- Acceso autenticado desde el panel interno.

---

## Modelos

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `Documento` | `documentos` | Documento con archivo adjunto via Spatie MediaLibrary. |
| `Categoria` | `categorias` | Categoría del documento (ej: Reglamentos, Procedimientos). |
| `Descarga` | `descargas` | Registro de cada descarga (usuario, IP, fecha). |

---

## Documento: campos clave

```
id, nombre, descripcion, archivo, file_storage, extension, mimeType,
version, orden, visible, sede_id (null = visible para todas las sedes),
user_id (quién lo subió), categoria_id, archivo_uploaded_at,
created_at, updated_at
```

`categoria_id` (FK a `categorias`, `onDelete cascade`) existía en la base real pero nunca había quedado versionado en una migración — corregido con `2026_07_03_120000_add_categoria_id_to_documentos_table.php`.

El archivo físico se gestiona via Spatie MediaLibrary, colección `archivos`, disco `documentos`.

---

## Rutas públicas (sin login)

La home (`/`) expone una vista de documentos públicos, servida por `App\Http\Controllers\Home\HomeController` (no confundir con `Documentos\DocumentoController`, que es el CRUD del panel interno):
- `GET /cats/{categoria}` → `HomeController::documentoCategoria()` — documentos de una categoría (público)
- `GET /docs/{documento}` → `HomeController::documentoDownload()` — descarga directa, deprecada (construye el path a mano en vez de usar MediaLibrary)
- `GET /docs/{documento}/download` → `HomeController::documentoDownloadWithLog()` — descarga con registro de log, vía MediaLibrary

Esto permite compartir links directos a documentos sin que el receptor necesite cuenta en el sistema.

---

## Rutas internas (con login)

- `GET /home/documentos` — listado por categorías (panel interno)
- Upload y gestión de documentos requieren permiso `Documentos/ABM` o similar.

---

## Componentes Livewire

| Componente | Función |
|-----------|---------|
| `Documentos/Categorias` | Gestión de categorías |
| `Documentos/Documentos` | Listado y upload de documentos |

---

## Registro de descargas

La tabla `descargas` guarda: `documento_id`, `user_id`, `ip`, `created_at`. Permite auditar quién descargó qué y cuándo. El endpoint `download-with-log` registra la descarga automáticamente.

---

## Integración con Spatie MediaLibrary

El modelo `Documento` implementa `HasMedia` con la colección `archivos` apuntando al disco `documentos`. El archivo se almacena en `storage/app/documentos/` (o el path configurado en `config/filesystems.php`).

---

## Código eliminado

`App\Http\Controllers\Home\DocumentoController` — resource controller con todos los métodos vacíos salvo `categoria_show()`, que duplicaba exactamente lo que ya hace `HomeController::documentoCategoria()`. Ninguna ruta lo referenciaba (las rutas públicas reales van a `HomeController`, ver arriba). Eliminado.

---

## Puntos a mejorar

- No hay versionado de documentos (si se sube una nueva versión, no queda historial del archivo anterior).
- Las categorías son planas (sin jerarquía). Para BAESA puede ser suficiente, pero si crecen los tipos de documentos, podría ser limitante.
- La distinción "público / solo autenticado" se maneja por ruta, no por campo en el modelo — todos los documentos son accesibles si se conoce el ID. Habría que agregar un campo `publico` si se necesita control más fino.
- `HomeController::documentoDownload()` (ruta deprecada `GET /docs/{documento}`) arma el path del archivo a mano con `storage_path()` en vez de usar Spatie MediaLibrary como el resto del módulo — posible fuente de inconsistencias si cambia el disco/estructura de storage. No se tocó por estar marcada deprecada y podría estar en links ya compartidos.
