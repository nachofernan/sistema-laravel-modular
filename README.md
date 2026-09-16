# Plataforma BAESA

> Sistema interno de gestión administrativa y operativa de **Buenos Aires Energía S.A.**, desarrollado y mantenido en solitario desde hace más de 4 años. Este repo se comparte como muestra de portfolio: código real de producción, no un proyecto de práctica.

<p align="left">
<img src="https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white" alt="PHP 8.2">
<img src="https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel&logoColor=white" alt="Laravel 11">
<img src="https://img.shields.io/badge/Livewire-3-4E56A6" alt="Livewire 3">
<img src="https://img.shields.io/badge/MySQL-multi--DB-4479A1?logo=mysql&logoColor=white" alt="MySQL multi-DB">
<img src="https://img.shields.io/badge/Estado-en%20producción-brightgreen" alt="En producción">
</p>

> ⚠️ **Esta rama (`upgrade/laravel-13`) contiene una migración completa a Laravel 13 / PHP 8.3+**, ya
> terminada y con la suite de tests en verde, pero **todavía no mergeada a `main`**: producción sigue
> en PHP 8.2 hasta que se actualice el servidor. El `main` de este repo refleja Laravel 11, la versión
> corriendo hoy en producción.

---

## Qué es esto

BAESA es una distribuidora de energía. Hace más de cuatro años esta plataforma arrancó como un
sistema simple de usuarios y documentos internos, y fue creciendo **a pedido**, módulo por módulo, a
medida que distintas áreas de la empresa necesitaban reemplazar planillas de Excel y procesos en
papel por algo centralizado: inventario de equipos IT, mesa de ayuda, gestión de proveedores,
concursos de precios, flota de vehículos, lecturas de máquinas de despacho de energía.

No es un producto SaaS ni una app pública — es la herramienta de trabajo diaria de varias decenas de
empleados de BAESA, con datos reales y procesos de la empresa corriendo sobre ella todos los días.
La sostiene y la extiende **una sola persona**, con foco en que funcione de forma confiable antes que
en la elegancia arquitectónica por sí misma — aunque, como se ve más abajo, la arquitectura tampoco
se dejó librada al azar.

Lo comparto en el portfolio porque es el proyecto donde más decisiones de diseño de fondo tuve que
tomar y sostener en el tiempo: aislamiento de datos entre áreas, permisos, integración con sistemas
externos, entre otras.

---

## Lo que vale la pena mirar

**Una base de datos por módulo, no un monolito de tablas compartidas.** Cada área funcional
(inventario, proveedores, concursos...) vive en su propia base MySQL, con su propia conexión
declarada explícitamente en cada modelo Eloquent. No hay JOINs ni foreign keys entre bases: la
relación entre datos de módulos distintos se resuelve en código. Es una restricción autoimpuesta
deliberada — prioriza que un módulo se pueda tocar, migrar o incluso desprender sin arrastrar al
resto del sistema, sobre la comodidad de un JOIN nativo.

**Módulos que se activan y desactivan en caliente.** Qué rutas carga el sistema depende de una tabla
en base de datos, no de código hardcodeado: un módulo `inactivo` simplemente no registra sus rutas ni
aparece en el menú. Agregar un módulo nuevo es una fila en una tabla más un archivo de rutas, no un
redeploy de configuración.

**Permisos por módulo con Spatie, chequeados también dentro de los componentes Livewire** —no solo en
el middleware HTTP, que no alcanza a proteger la interactividad de un componente una vez montado.

**Una API REST con JWT para un sistema externo.** El Portal de Proveedores es una aplicación de
terceros (los propios proveedores de BAESA) que consume endpoints autenticados por token para
declarar intención de participar en un concurso de precios y subir documentación. Es una superficie
que hay que tratar como contrato estable: cualquier cambio de forma en una respuesta puede romper una
integración que no controlo.

**Concursos de precios con sobre cerrado digital.** El módulo de mayor complejidad del sistema:
gestiona todo el ciclo de un concurso de compras — invitación a proveedores, declaración de
intención, carga de ofertas con documentación encriptada hasta el momento de apertura, prórrogas,
historial de estados, y la misma API externa para que el proveedor opere sin necesidad de una cuenta
interna.

**Envío de email en cola, con log de cada envío.** Integración con Microsoft Graph para el correo
corporativo, siempre a través de jobs encolados y con registro en base de datos de qué se mandó, a
quién y con qué resultado — nunca un `Mail::send()` síncrono en medio de un request.

---

## Stack

| Capa | Tecnología |
|---|---|
| Backend | Laravel 11 · PHP 8.2 |
| Frontend | Blade + Livewire 3 + Alpine.js, sin SPA |
| Estilos | TailwindCSS, compilado con Vite |
| Base de datos | MySQL — una base por módulo, conexión explícita por modelo |
| Auth | Laravel Jetstream + Fortify, 2FA opcional |
| Permisos | Spatie Laravel Permission, convención `Módulo/Rol` |
| Archivos | Spatie MediaLibrary, discos separados por módulo, con opción de encriptación |
| Email | `innoge/laravel-msgraph-mail` (Microsoft Graph) vía jobs en cola |
| API externa | JWT (`firebase/php-jwt`) para el Portal de Proveedores |
| PDF / Excel | `barryvdh/laravel-dompdf` · `maatwebsite/excel` |
| Tests | Pest, tests de integración contra MySQL real con `DatabaseTransactions` |

---

## Los módulos

| Módulo | Qué resuelve |
|---|---|
| **Usuarios** | Identidad, roles y permisos, organigrama, sedes, control de qué módulos están activos |
| **Documentos** | Repositorio de documentos institucionales, con historial de versiones y registro de descargas |
| **Inventario** | Equipamiento IT: asignación, historial de entregas/devoluciones, códigos automáticos por categoría |
| **AdminIP** | Registro e inventario de direcciones IP de la red interna |
| **Tickets** | Mesa de ayuda interna, con hilo de conversación por ticket |
| **Capacitaciones** | Gestión de capacitaciones internas, con encuestas de satisfacción |
| **Automotores** | Flota vehicular: cargas de combustible, services, alertas por kilometraje |
| **Despacho** | Lecturas de máquinas de generación, importadas desde archivos de los propios equipos |
| **Proveedores** | Registro completo de proveedores, con validación de documentación y portal externo propio |
| **Concursos** | Concursos de precios de punta a punta, con sobre digital encriptado y API para proveedores |

---

## Testing

La suite crece de forma incremental sobre lo que se toca, no de una vez: tests de integración con
Pest contra una base MySQL real (no mocks), usando transacciones para no recrear el esquema
multi-base en cada corrida. El núcleo transversal del sistema —aislamiento entre bases, permisos,
carga dinámica de módulos, el contrato de la API externa— **no se toca sin test**, sea cual sea el
tamaño del cambio.

---

## Sobre este repositorio

Este es el código real del sistema en producción, compartido como muestra técnica. No incluye
credenciales, datos ni el `.env` de la empresa, y no está pensado para levantarse "out of the box"
por fuera de la infraestructura de BAESA — es una referencia de arquitectura y de código, no una
plantilla de proyecto para clonar y correr.

---

**Ignacio Fernández** — desarrollo y mantenimiento en solitario desde 2021.
