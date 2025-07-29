# API - Documentos Validados por Proveedor

## Endpoint

```
GET /api/proveedores/{cuit}/documentos-validados
```

## Descripción

Este endpoint retorna solo los últimos documentos validados por tipo de documento para un proveedor específico. Es decir, un documento por cada tipo de documento que tenga al menos un documento validado.

## Autenticación

Requiere autenticación JWT. Incluir el token en el header:
```
Authorization: Bearer {token}
```

## Parámetros

- `cuit` (string, requerido): CUIT del proveedor

## Respuesta

### Éxito (200)

```json
{
    "success": true,
    "data": [
        {
            "id": 123,
            "tipo_documento": {
                "id": 1,
                "nombre": "Inscripción AFIP",
                "codigo": "AFIP"
            },
            "vencimiento": "2024-12-31"
        },
        {
            "id": 124,
            "tipo_documento": {
                "id": 2,
                "nombre": "Constancia de Inscripción",
                "codigo": "CONST"
            }
        }
    ],
    "message": "Documentos obtenidos correctamente."
}
```

### Error (404)

```json
{
    "success": false,
    "message": "Proveedor no encontrado."
}
```

## Notas

- Solo se retornan documentos que estén validados (`validacion.validado = true`)
- Solo se retorna un documento por tipo (el más reciente)
- El campo `vencimiento` solo se incluye si el tipo de documento requiere vencimiento y el documento tiene una fecha de vencimiento
- Si un tipo de documento no tiene documentos validados, no aparecerá en la respuesta

## Ejemplo de uso

```bash
curl -X GET \
  "https://api.ejemplo.com/api/proveedores/20123456789/documentos-validados" \
  -H "Authorization: Bearer {tu_token_jwt}"
``` 