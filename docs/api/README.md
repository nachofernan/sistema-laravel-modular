# API externa — Portal de Proveedores

Documentación de la API REST que consume el **Portal de Proveedores externo** (app de terceros): los
proveedores ven sus concursos, declaran intención de participar y suben/descargan documentación.
Autenticación por **JWT** (`firebase/php-jwt`, token con CUIT + email, válido ~10 min). Rutas en
`routes/api.php`, middleware `verify.jwt`.

> ⚠️ Esta API es un **contrato con un sistema externo que no controlamos**. Cambiar la forma de una
> respuesta, un status o el esquema de auth puede romper el portal. Es núcleo sagrado: se avisa, se
> versiona con cuidado y lleva test. Ver los axiomas en [`../../CLAUDE.md`](../../CLAUDE.md).

## Por dónde empezar

| Doc | Qué es | Cuándo leerlo |
|-----|--------|---------------|
| [`API_OVERVIEW.md`](API_OVERVIEW.md) | Resumen general: estructura, convenciones, buenas prácticas | Primera lectura, vista de pájaro |
| [`API_PROVEEDORES_CONCURSOS.md`](API_PROVEEDORES_CONCURSOS.md) | Auth y endpoints núcleo de proveedores/concursos | Entender el flujo básico |
| **[`API_CONCURSOS_COMPLETA.md`](API_CONCURSOS_COMPLETA.md)** | **Referencia completa de endpoints de concursos** | **Fuente canónica de endpoints** |
| **[`ESPECIFICACIONES_TECNICAS_CONCURSOS.md`](ESPECIFICACIONES_TECNICAS_CONCURSOS.md)** | **Especificaciones técnicas detalladas** (modelo de datos, contratos, para reimplementar) | **Fuente canónica técnica** |
| [`API_DOCUMENTOS_ADICIONALES.md`](API_DOCUMENTOS_ADICIONALES.md) | Endpoints de documentos adicionales de una oferta | Feature específico |
| [`API_DOCUMENTOS_VALIDADOS.md`](API_DOCUMENTOS_VALIDADOS.md) | Endpoint de documentos validados por proveedor | Feature específico |

**En negrita las dos fuentes canónicas.** Ante duda o contradicción entre docs, manda
`API_CONCURSOS_COMPLETA.md` (endpoints) + `ESPECIFICACIONES_TECNICAS_CONCURSOS.md` (contratos técnicos).

## Material histórico

Los resúmenes de implementación y ejemplos puntuales que documentaban cambios ya integrados se movieron
a [`../archivo/`](../archivo/) (`RESUMEN_IMPLEMENTACION_CONCURSOS.md`, `RESUMEN_ENDPOINTS_NUEVOS.md`,
`EJEMPLO_USO_DOCUMENTOS_VALIDADOS.md`). No son referencia vigente.
