# Resumen General de la API

Este documento describe la estructura, funcionamiento y buenas prácticas de la API implementada en la plataforma.

---

## 1. Estructura General

- **Rutas:** Definidas en `routes/api.php`.
- **Controladores principales:**
  - `AuthController`
  - `FileController`
  - `ConcursoController`
  - `ProveedorController`
- **Protección:** Uso de middleware `verify.jwt` para proteger la mayoría de los endpoints.

---

## 2. Endpoints Principales

### Públicos (sin autenticación JWT)
- `POST /generate-token` — Genera un token JWT para un proveedor válido.
- `POST /validate-provider` — Valida la existencia de un proveedor por CUIT.

### Protegidos (requieren JWT)
- **Gestión de archivos:**
  - `POST /upload` — Subida de archivos de concursos.
  - `POST /uploadDocumentacionGeneral` — Subida de documentación general de proveedores.
  - `POST /uploadDocumentacionApoderado` — Subida de documentación de apoderados.
  - `GET /download` — Descarga de archivos.
  - `GET /delete` — Eliminación de archivos.
- **Datos de proveedores y concursos:**
  - `GET /provider-data/{cuit}` — Datos completos del proveedor.
  - `GET /provider-invitations/{providerId}` — Invitaciones activas y finalizadas.
  - `GET /concurso-details/{concursoId}/{cuit}` — Detalles completos de un concurso para un proveedor.
  - `GET /proveedor-dashboard/{cuit}` — Dashboard completo del proveedor.
  - `GET /documento-tipos` — Tipos de documentos disponibles.
  - `GET /rubros` — Rubros y subrubros.
  - `POST /proveedor-subrubro` — Gestión de subrubros del proveedor.
  - `POST /proveedor-rubro-completo` — Gestión masiva de subrubros por rubro.

---

## 3. Buenas Prácticas Observadas

- Uso de middleware para proteger recursos sensibles.
- Validación de datos de entrada en los controladores.
- Manejo de errores y respuestas JSON claras y consistentes.
- Separación de responsabilidades entre controladores.
- Uso eficiente de Eloquent y relaciones.
- Integración de servicios externos (Spatie Media Library, JWT).

---

## 4. Sugerencias de Mejora

- Documentar los endpoints con Swagger o Postman para facilitar el consumo externo.
- Unificar el manejo de errores en un helper para mayor consistencia.
- Mantener y ampliar los tests automatizados.
- Revisar la seguridad de los archivos subidos y la gestión de tokens JWT (rotación, revocación, expiración).

---

## 5. Conclusión

La API está bien estructurada, es modular y sigue buenas prácticas de Laravel. Es fácilmente extensible y segura para los casos de uso actuales.

Para dudas puntuales o ampliaciones, consultar los controladores en `app/Http/Controllers/API/` o este mismo documento. 