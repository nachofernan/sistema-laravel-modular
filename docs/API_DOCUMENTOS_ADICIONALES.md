# API - Documentos Adicionales de Concursos

## 📋 Descripción General

Los documentos adicionales son archivos que pueden ser subidos por el proveedor sin estar asociados a un tipo específico de documento. Estos documentos complementan la oferta del proveedor y pueden incluir información adicional, especificaciones técnicas, catálogos, etc.

## 🔄 Endpoints Disponibles

### 1. Subir Documento (Requerido o Adicional)

**Endpoint:** `POST /api/concursos/{concurso_id}/documentos`

**Descripción:** Permite subir tanto documentos requeridos (con tipo específico) como documentos adicionales (sin tipo).

#### Parámetros de URL
- `concurso_id` (integer, required): ID del concurso

#### Body (multipart/form-data)
- `file` (file, required): Archivo a subir (máximo 10MB)
- `documento_tipo_id` (integer, optional): ID del tipo de documento
  - Si se proporciona: documento requerido
  - Si se omite o es null: documento adicional
- `comentarios` (string, optional, max 500 chars): Comentarios sobre el documento (solo para adicionales)

#### Ejemplos de Uso

**Documento Requerido:**
```bash
curl -X POST "https://api.ejemplo.com/api/concursos/123/documentos" \
  -H "Authorization: Bearer {token}" \
  -F "file=@documento.pdf" \
  -F "documento_tipo_id=5"
```

**Documento Adicional:**
```bash
curl -X POST "https://api.ejemplo.com/api/concursos/123/documentos" \
  -H "Authorization: Bearer {token}" \
  -F "file=@catalogo.pdf" \
  -F "comentarios=Catálogo de productos 2024"
```

#### Respuesta Exitosa
```json
{
  "success": true,
  "data": {
    "id": 456,
    "invitacion_id": 789,
    "documento_tipo_id": null,
    "archivo": "catalogo.pdf",
    "mimeType": "application/pdf",
    "extension": "pdf",
    "user_id_created": null,
    "comentarios": "Catálogo de productos 2024",
    "created_at": "2024-01-15T10:30:00Z",
    "es_adicional": true,
    "fue_subido_por_proveedor": true,
    "media_id": 12345
  },
  "message": "Documento adicional subido correctamente."
}
```

### 2. Obtener Documentos Adicionales

**Endpoint:** `GET /api/concursos/{concurso_id}/documentos-adicionales`

**Descripción:** Obtiene todos los documentos adicionales separados por origen (proveedor vs empresa).

#### Parámetros de URL
- `concurso_id` (integer, required): ID del concurso

#### Headers
- `Authorization: Bearer {token}` (required)

#### Respuesta Exitosa
```json
{
  "success": true,
  "data": {
    "documentos_proveedor": [
      {
        "id": 456,
        "invitacion_id": 789,
        "documento_tipo_id": null,
        "archivo": "catalogo.pdf",
        "mimeType": "application/pdf",
        "extension": "pdf",
        "user_id_created": null,
        "comentarios": "Catálogo de productos 2024",
        "created_at": "2024-01-15T10:30:00Z",
        "es_adicional": true,
        "fue_subido_por_proveedor": true,
        "media_id": 12345
      }
    ],
    "documentos_empresa": [
      {
        "id": 457,
        "invitacion_id": 789,
        "documento_tipo_id": null,
        "archivo": "especificaciones.pdf",
        "mimeType": "application/pdf",
        "extension": "pdf",
        "user_id_created": 10,
        "comentarios": "Especificaciones técnicas adicionales",
        "created_at": "2024-01-16T14:20:00Z",
        "es_adicional": true,
        "fue_subido_por_proveedor": false,
        "creador": {
          "id": 10,
          "name": "Juan Pérez"
        },
        "media_id": 12346
      }
    ],
    "total_proveedor": 1,
    "total_empresa": 1
  },
  "message": "Documentos adicionales obtenidos correctamente."
}
```

### 3. Eliminar Documento

**Endpoint:** `DELETE /api/concursos/{concurso_id}/documentos/{documento_id}`

**Descripción:** Elimina un documento (requerido o adicional) subido por el proveedor.

#### Parámetros de URL
- `concurso_id` (integer, required): ID del concurso
- `documento_id` (integer, required): ID del documento a eliminar

#### Headers
- `Authorization: Bearer {token}` (required)

#### Respuesta Exitosa
```json
{
  "success": true,
  "message": "Documento eliminado correctamente."
}
```

#### Respuesta de Error (Documento de Empresa)
```json
{
  "success": false,
  "message": "No se puede eliminar un documento ingresado por la empresa."
}
```

### 4. Obtener Todos los Documentos

**Endpoint:** `GET /api/concursos/{concurso_id}/documentos`

**Descripción:** Obtiene todos los documentos de la invitación, separando requeridos y adicionales.

#### Respuesta
```json
{
  "success": true,
  "data": {
    "documentos_requeridos": [...],
    "documentos_adicionales": [...],
    "total_requeridos": 5,
    "total_adicionales": 2
  },
  "message": "Documentos de la invitación obtenidos correctamente."
}
```

### 5. Descargar Documento

**Endpoint:** `GET /api/concursos/{concurso_id}/documentos/{media_id}/descargar`

**Descripción:** Descarga un archivo específico (funciona para documentos requeridos y adicionales).

#### Parámetros de URL
- `concurso_id` (integer, required): ID del concurso
- `media_id` (integer, required): ID del media (no del documento)

## 📊 Estructura de Datos

### DocumentoAdicionalResource
```json
{
  "id": 456,
  "invitacion_id": 789,
  "documento_tipo_id": null,
  "documento_proveedor_id": null,
  "archivo": "catalogo.pdf",
  "mimeType": "application/pdf",
  "extension": "pdf",
  "file_storage": "catalogo.pdf",
  "user_id_created": null,
  "comentarios": "Catálogo de productos 2024",
  "created_at": "2024-01-15T10:30:00Z",
  "updated_at": "2024-01-15T10:30:00Z",
  "es_adicional": true,
  "fue_subido_por_proveedor": true,
  "fue_ingresado_por_empresa": false,
  "creador": {
    "id": 10,
    "name": "Juan Pérez"
  },
  "media_id": 12345
}
```

## 🔐 Reglas de Negocio

### Subida de Documentos
- **Tamaño máximo:** 10MB por archivo
- **Formatos permitidos:** Todos los formatos de archivo
- **Documentos adicionales:** Solo pueden ser subidos por proveedores
- **Documentos de empresa:** Se cargan internamente por el sistema

### Estados del Concurso y Subida de Documentos
- **Estado 1 (Borrador):** No se permiten subidas
- **Estado 2 (Activo):** Se permiten subidas solo antes de la fecha de cierre
- **Estado 3 (Análisis):** Solo se permiten documentos adicionales si `permite_carga = true`
- **Estado 4+ (Finalizado, etc.):** No se permiten subidas

### Eliminación de Documentos
- **Solo proveedores:** Pueden eliminar documentos que ellos subieron
- **Documentos de empresa:** No pueden ser eliminados por proveedores
- **Fecha de cierre:** No se pueden eliminar después del cierre del concurso

### Acceso a Documentos
- **Proveedores:** Solo ven documentos de su propia invitación
- **Documentos de empresa:** Visibles para el proveedor pero no eliminables

## 🚨 Códigos de Error

| Código | Mensaje | Descripción |
|--------|---------|-------------|
| 400 | `El archivo debe ser válido.` | Archivo corrupto o inválido |
| 400 | `El archivo no puede superar los 10MB.` | Archivo demasiado grande |
| 400 | `Los comentarios no pueden superar los 500 caracteres.` | Comentarios muy largos |
| 403 | `No se pueden subir documentos. El concurso ya ha cerrado.` | Concurso en estado activo pero cerrado |
| 403 | `No se pueden subir documentos en esta etapa del concurso.` | Concurso en análisis sin permite_carga |
| 403 | `No se pueden subir documentos requeridos en esta etapa del concurso. Solo se permiten documentos adicionales.` | Intento de subir documento requerido en análisis |
| 403 | `No se pueden subir documentos en el estado actual del concurso.` | Estado no permitido para subidas |
| 403 | `No se puede eliminar un documento ingresado por la empresa.` | Intento de eliminar documento de empresa |
| 403 | `No se puede eliminar el documento. El concurso ya ha cerrado.` | Concurso cerrado |
| 404 | `Documento no encontrado en la oferta.` | Documento inexistente |
| 404 | `Archivo no encontrado.` | Media inexistente |
| 500 | `Error al subir el documento: {error}` | Error interno del servidor |

## 📱 Ejemplos de Implementación

### JavaScript/Fetch
```javascript
// Subir documento adicional
async function subirDocumentoAdicional(concursoId, archivo, comentarios) {
  const formData = new FormData();
  formData.append('file', archivo);
  if (comentarios) {
    formData.append('comentarios', comentarios);
  }

  const response = await fetch(`/api/concursos/${concursoId}/documentos`, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`
    },
    body: formData
  });

  return await response.json();
}

// Obtener documentos adicionales
async function obtenerDocumentosAdicionales(concursoId) {
  const response = await fetch(`/api/concursos/${concursoId}/documentos-adicionales`, {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });

  return await response.json();
}

// Eliminar documento
async function eliminarDocumento(concursoId, documentoId) {
  const response = await fetch(`/api/concursos/${concursoId}/documentos/${documentoId}`, {
    method: 'DELETE',
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });

  return await response.json();
}
```

### Python/Requests
```python
import requests

def subir_documento_adicional(concurso_id, archivo_path, comentarios=None):
    url = f"https://api.ejemplo.com/api/concursos/{concurso_id}/documentos"
    
    with open(archivo_path, 'rb') as archivo:
        files = {'file': archivo}
        data = {}
        if comentarios:
            data['comentarios'] = comentarios
            
        response = requests.post(
            url,
            files=files,
            data=data,
            headers={'Authorization': f'Bearer {token}'}
        )
    
    return response.json()

def obtener_documentos_adicionales(concurso_id):
    url = f"https://api.ejemplo.com/api/concursos/{concurso_id}/documentos-adicionales"
    
    response = requests.get(
        url,
        headers={'Authorization': f'Bearer {token}'}
    )
    
    return response.json()
```

## 🔄 Flujo de Trabajo Típico

1. **Proveedor accede al concurso**
2. **Sube documentos requeridos** (con `documento_tipo_id`)
3. **Sube documentos adicionales** (sin `documento_tipo_id`)
4. **Empresa carga documentos internos** (sistema interno)
5. **Proveedor visualiza todos los documentos**
6. **Proveedor puede eliminar solo sus documentos**
7. **Proveedor puede descargar todos los documentos**

## 📝 Notas Importantes

- Los documentos adicionales son opcionales y complementarios
- Los comentarios solo se aplican a documentos adicionales
- El `media_id` es diferente al `documento_id` (usar `media_id` para descargas)
- Los documentos de empresa son visibles pero no modificables por proveedores
- Todos los endpoints requieren autenticación JWT válida

## ⚠️ Validaciones de Estado del Concurso

### Estados Permitidos para Subida de Documentos:

| Estado | Nombre | Documentos Requeridos | Documentos Adicionales | Condiciones |
|--------|--------|----------------------|------------------------|-------------|
| 1 | Borrador | ❌ No | ❌ No | - |
| 2 | Activo | ✅ Sí | ✅ Sí | Solo antes de la fecha de cierre |
| 3 | Análisis | ❌ No | ✅ Sí | Solo si `permite_carga = true` |
| 4+ | Finalizado/Otros | ❌ No | ❌ No | - |

### Ejemplos de Validaciones:

**Estado Activo (2) - Antes del cierre:**
```json
{
  "success": true,
  "message": "Documento subido correctamente."
}
```

**Estado Activo (2) - Después del cierre:**
```json
{
  "success": false,
  "message": "No se pueden subir documentos. El concurso ya ha cerrado."
}
```

**Estado Análisis (3) - Sin permite_carga:**
```json
{
  "success": false,
  "message": "No se pueden subir documentos en esta etapa del concurso."
}
```

**Estado Análisis (3) - Con permite_carga pero documento requerido:**
```json
{
  "success": false,
  "message": "No se pueden subir documentos requeridos en esta etapa del concurso. Solo se permiten documentos adicionales."
}
```

**Estado Análisis (3) - Con permite_carga y documento adicional:**
```json
{
  "success": true,
  "message": "Documento adicional subido correctamente."
}
```

**Estado Borrador (1):**
```json
{
  "success": false,
  "message": "No se pueden subir documentos en el estado actual del concurso."
}
``` 