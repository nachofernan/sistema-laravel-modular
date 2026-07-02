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
id, titulo, descripcion, categoria_id, user_id (quién lo subió),
sede_id (null = visible para todas las sedes), archivo_uploaded_at,
created_at, updated_at
```

El archivo físico se gestiona via Spatie MediaLibrary, colección `archivos`, disco `documentos`.

---

## Rutas públicas (sin login)

La home (`/`) expone una vista de documentos públicos. Los endpoints:
- `GET /cats/{categoria}` — documentos de una categoría (público)
- `GET /docs/{documento}/download` — descarga con registro de log

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

## Puntos a mejorar

- No hay versionado de documentos (si se sube una nueva versión, no queda historial del archivo anterior).
- Las categorías son planas (sin jerarquía). Para BAESA puede ser suficiente, pero si crecen los tipos de documentos, podría ser limitante.
- La distinción "público / solo autenticado" se maneja por ruta, no por campo en el modelo — todos los documentos son accesibles si se conoce el ID. Habría que agregar un campo `publico` si se necesita control más fino.
