# Análisis de Impacto: Migración del Campo `cuit` de Entero a Texto

**Fecha:** Mayo 2025  
**Autor:** Equipo de Desarrollo  
**Estado:** Pendiente de aprobación  

---

## Contexto y Motivación

El campo `cuit` en el módulo de proveedores fue diseñado originalmente para almacenar exclusivamente CUITs argentinos, los cuales son cadenas de 11 dígitos sin letras. Por esa razón se definió como tipo entero (`BIGINT`) en la base de datos.

Con la incorporación de **proveedores extranjeros** al sistema, los identificadores tributarios de otros países (RUT chileno, NIF español, EIN estadounidense, etc.) incluyen letras y guiones, haciendo imposible su almacenamiento en un campo numérico.

Este documento analiza el impacto estructural de cambiar el tipo del campo `cuit` de `BIGINT` a `VARCHAR` en todos los módulos del sistema.

---

## Alcance del Campo Afectado

El campo `cuit` de la tabla `proveedors` (base de datos `proveedores`) es el registro maestro del sistema. No es un campo aislado: cumple **tres roles simultáneos** que lo hacen crítico:

1. **Identificador de negocio** — es el dato con el que el personal interno identifica a un proveedor
2. **Clave de búsqueda** — todos los listados, filtros y buscadores del sistema lo usan
3. **Credencial de autenticación** — es el username con el que el proveedor se conecta al portal externo vía API

---

## Módulos y Archivos con Impacto Directo

### 1. Base de Datos — Impacto ALTO

| Elemento | Detalle |
|---|---|
| Tabla | `proveedores.proveedors` |
| Tipo actual | `BIGINT` (entero de 64 bits) |
| Tipo propuesto | `VARCHAR(30)` |
| Registros existentes | Todos los CUITs actuales (numéricos) serán convertidos automáticamente a texto sin pérdida de información |
| Riesgo | La operación de ALTER TABLE reconstruye la tabla completa; en producción con muchos registros puede generar un tiempo de inactividad breve |

> **Nota importante:** El módulo de Automotores (`copres`) ya define su campo `cuit` como `string` desde el inicio. La inconsistencia de tipos entre módulos confirma que el cambio era necesario y fue anticipado parcialmente.

---

### 2. Relación con el Portal Externo — Impacto CRÍTICO

Este es el punto de mayor riesgo del cambio.

El sistema mantiene **dos bases de datos separadas**:
- `proveedores` — contiene los datos maestros del proveedor, incluyendo `cuit` (hoy `BIGINT`)
- `proveedores_externos` — contiene las credenciales de acceso al portal externo; la columna `username` almacena el CUIT del proveedor (siempre fue `VARCHAR`)

```
proveedores.proveedors.cuit  (BIGINT)
        ↕ join cross-database
proveedores_externos.users.username  (VARCHAR)
```

Actualmente el motor de base de datos realiza una **conversión automática de tipos** para resolver este join, lo que funciona pero es técnicamente incorrecto. Al convertir `cuit` a `VARCHAR`, ambas columnas quedan del mismo tipo y el join pasa a ser limpio y correcto.

**El riesgo:** si en la base de datos `proveedores_externos` algún `username` fue almacenado con un formato diferente al CUIT numérico puro (con guiones, espacios u otros caracteres), la vinculación dejará de funcionar y el proveedor no podrá iniciar sesión. Esto **debe auditarse antes de ejecutar cualquier cambio**.

---

### 3. API de Autenticación — Impacto ALTO

La API que usa el portal externo de proveedores recibe el CUIT como credencial de login y lo incluye dentro del token de seguridad (JWT).

**Flujo actual:**

```
Cliente externo  →  POST /api/generate-token { cuit, email }
                →  Sistema valida proveedor por cuit + email
                →  Genera JWT con { cuit, email, ... } en el payload
                →  Cliente usa ese token en todos los endpoints
```

**Impacto del cambio:**
- Si algún cliente externo (aplicación móvil, portal de proveedores, integración de terceros) envía el CUIT como número entero en lugar de texto en sus requests, puede generar comportamiento inesperado
- El claim `cuit` dentro del JWT pasará de ser un número a ser un texto; si algún cliente parsea ese campo esperando un número, se romperá

**Acción requerida:** Coordinar con los equipos responsables de los clientes externos antes de desplegar el cambio.

---

### 4. Validaciones del Sistema Interno — Impacto ALTO

Todas las validaciones actuales del formulario de alta y edición de proveedores asumen que el CUIT es un número:

```
Reglas actuales:  requerido + numérico + mínimo 7 dígitos + máximo 15 dígitos
```

Estas reglas deben ser redefinidas para aceptar tanto CUITs argentinos como identificadores extranjeros. Esto **requiere una decisión de negocio** sobre los formatos aceptables antes de implementar el cambio técnico.

**Preguntas a resolver antes de implementar:**
- ¿Se valida el formato del CUIT argentino de forma diferente al identificador extranjero?
- ¿Cuál es la longitud máxima aceptada para identificadores extranjeros?
- ¿Se agrega un campo `tipo_identificador` para distinguir el origen del número?

---

### 5. Módulo de Concursos — Impacto BAJO

El módulo de concursos usa el CUIT del proveedor únicamente para:
- Mostrarlo en pantalla al invitar proveedores a un concurso
- Incluirlo en los arrays de destinatarios para envío de correos

Ambos usos son de **solo lectura y visualización**. El cambio de tipo es transparente en este módulo.

---

### 6. Buscadores y Listados — Impacto BAJO

Todos los buscadores del sistema (listado de proveedores, anexo SOLPED, invitación a concursos) usan el operador `LIKE` para filtrar por CUIT. Este operador funciona correctamente tanto con columnas numéricas como de texto, por lo que no hay cambio funcional. El rendimiento de búsqueda sobre `VARCHAR` con índice es equivalente al actual.

---

### 7. Export a Excel — Impacto BAJO

El export de proveedores incluye la columna CUIT. Con el tipo actual (`BIGINT`), la celda en Excel es de tipo numérico. Con `VARCHAR`, la celda pasará a ser de tipo texto. Si existen reportes o macros que operen matemáticamente sobre esa columna en Excel, deberán ajustarse.

---

## Resumen Ejecutivo de Impacto

| Área | Impacto | Requiere acción |
|---|---|---|
| Migración de base de datos | Alto | Sí — nueva migración |
| Relación con portal externo | Crítico | Sí — auditoría de datos previa obligatoria |
| API de autenticación (JWT) | Alto | Sí — coordinar con clientes externos |
| Validaciones formulario web | Alto | Sí — redefinir política de validación |
| Módulo de Concursos | Bajo | No |
| Buscadores y listados | Bajo | No (funciona igual) |
| Export Excel | Bajo | Verificar reportes downstream |
| Vistas y PDFs | Ninguno | No |
| Módulo Automotores | Ninguno | No (ya usa string) |

---

## Estimación de Esfuerzo

| Tarea | Esfuerzo estimado |
|---|---|
| Auditoría de consistencia de datos cross-database | 2–4 hs |
| Definición de política de validación (reunión de negocio) | 1–2 hs |
| Escritura de migración de base de datos | 1 hs |
| Actualización de validaciones en controladores | 2 hs |
| Coordinación con clientes externos de la API | Variable |
| Pruebas de integración end-to-end en staging | 4–6 hs |
| **Total estimado (sin coordinación externa)** | **~10–15 hs** |

---

## Condiciones Previas para Implementar

El cambio **no debe implementarse** hasta que se cumplan estas condiciones:

1. **Auditoría de datos aprobada:** Verificar que todos los registros en `proveedores_externos.users.username` coincidan exactamente con su correspondiente `proveedors.cuit`, sin diferencias de formato.

2. **Política de validación definida:** El área de negocio debe definir qué formato se acepta para los identificadores tributarios extranjeros.

3. **Clientes externos notificados:** Los consumidores de la API (portal externo, integraciones) deben ser informados del cambio y confirmar compatibilidad.

4. **Entorno de staging actualizado:** El cambio debe probarse en staging con datos reales replicados antes de ejecutarse en producción.

---

## Conclusión

El cambio es **técnicamente necesario y viable**, pero su impacto trasciende una modificación de código puntual: afecta la base de datos, el flujo de autenticación externo y los contratos de la API. La ejecución correcta requiere coordinación entre el equipo de desarrollo, el área de negocio y los responsables de los sistemas externos que consumen la API.

El riesgo más alto es la consistencia de la relación entre las dos bases de datos (`proveedores` y `proveedores_externos`). Una migración sin auditoría previa puede dejar proveedores existentes sin acceso al portal externo.

Se recomienda **planificar el cambio en un sprint dedicado** con ventana de mantenimiento programada para la ejecución en producción.
