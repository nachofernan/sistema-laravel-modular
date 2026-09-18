# Análisis de Impacto: Migración del Campo `cuit` de Entero a Texto

**Fecha:** Mayo 2026 (actualizado Septiembre 2026)  
**Autor:** Equipo de Desarrollo  
**Estado:** Implementado en código (2026-09-18, ver `docs/CHANGELOG.md`) — pendiente activación en
producción  

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

**El riesgo:** si en la base de datos `proveedores_externos` algún `username` fue almacenado con un formato diferente al CUIT numérico puro (con guiones, espacios u otros caracteres), la vinculación dejará de funcionar y el proveedor no podrá iniciar sesión.

> **Actualización (Sep. 2026):** Auditoría realizada. El `username` de `proveedores_externos` es
> literalmente el `cuit` guardado en `proveedores.proveedors` — **no hay inconsistencias de formato**.
> Además, se confirmó que el único consumidor de la API es el propio sistema de proveedores externos,
> y solo toma el `cuit` en dos momentos puntuales: **registro y login**. No hay otros integradores ni
> otros puntos de la API que dependan de su forma actual. El riesgo de este punto queda saldado.

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

Estas reglas deben ser redefinidas para aceptar tanto CUITs argentinos como identificadores extranjeros.

> **Actualización (Sep. 2026) — Política de validación definida:**
>
> - **Longitud:** entre 6 y 20 caracteres.
> - **Sanitización:** se eliminan guiones, barras, espacios y puntos antes de guardar — el valor
>   persistido es siempre alfanumérico puro (`[A-Z0-9]`), sin separadores de ningún tipo.
> - **Mayúsculas:** las letras se normalizan a mayúsculas.
> - **UX:** un JS en el input pasa a mayúsculas mientras el usuario escribe (feedback inmediato).
> - **Defensa en profundidad:** independientemente del JS, el backend aplica `strtoupper()` (o
>   equivalente) antes de persistir — el JS es cosmético, la garantía real la da el servidor.
> - No se agrega un campo `tipo_identificador` separado: el dato queda unificado en `cuit` como texto
>   libre normalizado.
>
> Esto se implementa como una regla de validación custom (o Form Request) en
> `ProveedorController` — reemplaza las reglas actuales `numeric|min:1000000|max:999999999999999`
> por algo del estilo `required|string|min:6|max:20|regex:/^[A-Z0-9]+$/` aplicado **después** de la
> sanitización (trim de guiones/barras/espacios/puntos + `strtoupper`), no antes.

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
| Relación con portal externo | Crítico | **Resuelto** — auditoría sin inconsistencias (Sep. 2026) |
| API de autenticación (JWT) | Alto | **Resuelto** — único consumidor es el propio portal, solo en registro/login (Sep. 2026) |
| Validaciones formulario web | Alto | **Resuelto** — política definida: 6-20 caracteres, sanitizado alfanumérico, mayúsculas (Sep. 2026) |
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

~~El cambio no debe implementarse hasta que se cumplan estas condiciones~~ — **actualizado Sep. 2026,
todas resueltas:**

1. ~~Auditoría de datos aprobada~~ **✔ Hecho.** El `username` de `proveedores_externos` es literalmente
   el `cuit` de `proveedores.proveedors`, sin diferencias de formato.

2. ~~Política de validación definida~~ **✔ Hecho.** Ver sección "Validaciones del Sistema Interno"
   arriba: 6-20 caracteres, sanitizado a alfanumérico puro, mayúsculas, uppercase en JS + backend.

3. ~~Clientes externos notificados~~ **✔ No aplica.** El único consumidor de la API es el propio
   sistema de proveedores externos, y solo usa `cuit` en registro y login — no hay otros
   integradores a coordinar.

4. **Pendiente:** probar en staging con datos reales antes de ejecutar en producción (sigue vigente,
   es checklist de despliegue estándar, no un bloqueante de diseño).

---

## Conclusión

El cambio es **técnicamente necesario y viable**. El análisis original identificaba tres riesgos
(consistencia cross-DB, contrato de API externa, política de validación) que **ya fueron
despejados** en la revisión de Septiembre 2026: no hay inconsistencias de datos, la API no tiene
otros consumidores además del propio portal, y la política de validación quedó definida.

Lo que queda es la ejecución en sí — como el cambio toca dos axiomas de arquitectura (aislamiento
multi-DB por la relación con `proveedores_externos`, y el contrato de la API externa), se trata como
**núcleo sagrado**: migración + cambio de validación + actualización del JWT si corresponde, cada uno
con su test, y avisando el efecto en cascada antes de tocar cada capa (ver CLAUDE.md).
