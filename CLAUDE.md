# CLAUDE.md — Guía de trabajo para este proyecto

Sistema interno de gestión administrativa y operativa de **Buenos Aires Energía S.A. (BAESA)**.
Arrancó hace ~4 años como plataforma de usuarios y documentos, y fue creciendo orgánicamente con
módulos a pedido de la empresa: inventario, proveedores, concursos de precios, etc. La base de código
refleja esa evolución: hay partes bien estructuradas y partes más artesanales. El objetivo **no** es
reescribir, sino mantener la plataforma funcionando y refactorizar progresivamente a medida que se
agregan features nuevas.

Esto **no** es un SaaS ni una app pública: es una herramienta interna para el personal de BAESA. El
criterio de calidad es que funcione correctamente, sea mantenible por una persona sola con ayuda de
IA, y no rompa lo que ya anda.

El **qué** del sistema (módulos, arquitectura, modelos, rutas) vive en `docs/ARQUITECTURA.md` y
`docs/modulos/`. Este documento es **cómo se trabaja** el proyecto, no qué hace. Si hay contradicción
entre este archivo y una decisión registrada en `docs/DECISIONES.md`, manda la decisión más reciente.

---

## Principio cero: nada está escrito en piedra

Ninguna decisión de este proyecto —y **menos que ninguna las técnicas**— es un contrato. El stack, la
estructura de un módulo, Livewire vs. otra cosa: todo es hipótesis de trabajo revisable cuando aparece
un motivo real. Lo que **sí** es estable son los axiomas de arquitectura de más abajo, y aun esos se
cambian con una charla explícita, no de contrabando. Cuando algo se decide o se revierte, se anota en
`docs/DECISIONES.md`. No se reescribe el pasado: una decisión que cae se marca con una entrada nueva
que la reemplaza.

---

## Los modos de trabajo

Este proyecto se conversa antes de codearse. Hay tres roles, encarnados como agentes en
`.claude/agents/`, y hay que saber en cuál se está:

- **Mentor** (`baesa-mentor` — Opus, solo lectura). La voz de asesor del proyecto: se discute
  arquitectura multi-módulo, modelo de datos, permisos por módulo, integraciones (API externa de
  proveedores, email por MS Graph), deuda técnica, roadmap y decisiones de largo plazo. No escribe
  código ni "aprovecha" la charla para dejar un archivo hecho. **La sesión principal trabaja por
  defecto con este stance**: antes de mandar a ejecutar algo grande, se piensa y se recomienda. El
  agente `baesa-mentor` es para clavarse en una decisión pesada y devolver un análisis de un tiro. Si
  de la charla sale una decisión, se anota en `docs/DECISIONES.md` y ahí termina.
- **Senior** (`baesa-senior` — Opus, todas las tools). Implementa el trabajo profundo que toca
  estructura o reglas: migraciones, modelos con su `$connection`, permisos y roles, lógica de negocio,
  relaciones Eloquent nuevas, jobs de cola, endpoints de la API externa, refactors con efecto en
  cascada. En pasos chicos explicados antes. Corre el test local relevante durante el trabajo y la
  suite en el checkpoint, pasa Pint sobre lo tocado, y commitea al cerrar una etapa con sentido propio.
- **Junior** (`baesa-junior` — Sonnet, solo edición). Ejecuta ediciones directas y acotadas que no
  tocan estructura: copy en vistas, ajustes de Blade/Tailwind/Alpine, typos, agregar un campo a
  `$fillable` cuando la columna ya existe, renombrar una variable local, mover un partial. Cumple lo
  pedido, sin dudar mucho y con poco preámbulo. No corre tests, no commitea, no testea visualmente. Si
  la tarea resulta ser estructural (migración, permiso, regla de negocio, ruta, relación nueva,
  conexión de DB) o toca el núcleo sagrado, **frena y la devuelve para el senior**.

Si el rol no está claro, se pregunta cuál corresponde antes de hacer nada. Ante la duda, mentor: una
pregunta de más cuesta menos que un archivo escrito de más.

> Nota: un subagente corre en contexto aislado y **no puede preguntarte en vivo** — el ida y vuelta
> ocurre en el hilo principal, que es quien delega y releva las dudas que el subagente devuelve en su
> reporte.

### Cómo se pregunta

- Si algo no se entiende o hay ambigüedad, se pregunta **con opciones concretas** (A / B / C), no con
  un "¿cómo querés que lo haga?" abierto.
- Si el concepto es lo bastante grande como para que la respuesta correcta dependa de cosas que
  todavía no están decididas, no se ofrecen opciones: se pide charlarlo.
- Nunca se resuelve una ambigüedad de **diseño** eligiendo por cuenta propia y avisando después. Una
  ambigüedad de **implementación** (nombre de una variable, orden de dos métodos), sí: se deriva y se
  sigue. Preguntar de más frena tanto como asumir de más.

---

## Cómo se trabaja

- Antes de tocar archivos, se explica qué se va a crear/modificar y por qué. Se espera confirmación
  antes de avanzar con un paso de alcance nuevo (no cada línea, sí cada salto de alcance).
- Cada paso es un cambio lógico chico (una migración, un modelo, un componente Livewire), no varios
  cambios de golpe sin avisar.
- Si un cambio obliga a tocar otra capa (esquema→modelo, regla de negocio→test, permiso→controlador,
  modelo→vista), se marca explícitamente como **efecto en cascada** antes de hacerlo. Nunca silencioso.
- **Tocar el aislamiento entre bases, los permisos/módulos dinámicos o el contrato de la API externa =
  efecto en cascada garantizado.** Ver "Axiomas de arquitectura". No se toca sin avisarlo como lo que
  es y sin un test que lo respalde.
- Si en el camino aparece algo necesario que no estaba pedido (un bug real, un fix que hace falta para
  que lo pedido funcione), se hace y se explica después, con el motivo — no se pide permiso para cada
  hallazgo chico, pero tampoco se cuela sin decir nada.
- Git: commit al cerrar una **etapa con sentido propio** (ej: migración + modelo + su test), no cada
  capa suelta. Una tanda de cambios chicos (copys, estilos, tweaks de UI) se commitea junta, como un
  solo bloque. No se deja una etapa terminada sin commitear, ni se commitea a mitad de un cambio que no
  compila o no pasa tests. Antes de tocar archivos con cambios sin commitear, se revisa `git status` /
  `git diff`. Mensajes en español, estilo del historial del repo.

---

## Axiomas de arquitectura

Son las reglas que no se negocian sin una conversación explícita. Todo lo demás es táctica. En BAESA
el "núcleo sagrado" no es un dominio único (como en un producto de un solo negocio), sino la
**infraestructura transversal que sostiene a todos los módulos**: romperla afecta cosas que no tienen
nada que ver con lo que estabas tocando. Ese núcleo es lo que estos axiomas protegen, y **todo cambio
sobre él lleva test y aviso de cascada**.

1. **Cada módulo vive en su propia base de datos; el aislamiento es sagrado.** Cada modelo declara su
   `protected $connection` apuntando a la conexión de su módulo (`DB_DATABASE_<MODULO>` en `.env`, ver
   `config/database.php`). **Nunca** joins ni foreign keys cross-base a nivel DB: la relación entre
   datos de módulos distintos se resuelve en Eloquent, no en SQL. Un modelo sin su `$connection`
   explícito es un bug esperando pasar. La base `usuarios` es la central (auth, roles, módulos, áreas).

2. **La autorización va por permisos de módulo, y el módulo `Usuarios` la centraliza.** Roles y
   permisos con Spatie, patrón `Modulo/Rol` (`Proveedores/Acceso`, `Concursos/Admin`). **Siempre** se
   chequea permiso (`$this->authorize()` / `can()` / middleware / `@can`) antes de mostrar o mutar algo
   sensible — también en componentes Livewire, porque el controlador HTTP no protege el componente.
   Nada de inventar un esquema de permisos paralelo por fuera de Spatie.

3. **Los módulos son dinámicos: se cargan desde la tabla `modulos` (base `usuarios`).** Un módulo
   `inactivo` no carga sus rutas. Agregar/renombrar/desactivar un módulo se hace por esa tabla y su
   seeder, no hardcodeando rutas o menús. El sidebar y la navegación se derivan de ahí.

4. **La API externa (Portal de Proveedores) es un contrato con un sistema de terceros.** Endpoints JWT
   (`firebase/php-jwt`) en `routes/api.php`. Cambiar la forma de una respuesta, un status o el esquema
   de auth **rompe una app externa que no controlamos**. Cualquier cambio ahí es núcleo sagrado: se
   avisa, se versiona con cuidado y lleva test. La documentación de esos endpoints vive en `docs/API_*`.

5. **El email masivo va siempre por cola y queda registrado.** Envíos vía `innoge/laravel-msgraph-mail`
   (Microsoft Graph), en jobs encolados, con log en la tabla `email_logs`. Nada de mandar mails masivos
   sincrónicos en el request ni saltearse el log.

6. **Los archivos se gestionan con Spatie MediaLibrary, con discos separados por módulo.** Discos en
   `config/filesystems.php`. Algunos documentos (Concursos) soportan encriptación. No se guardan
   adjuntos a mano por fuera de MediaLibrary.

7. **Sin SPA.** Todo server-rendered vía Blade/Livewire 3. El JS custom es Alpine.js puntual. No se
   introduce Vue/React ni un framework front nuevo sin pasar por el Principio cero.

8. **El dominio se nombra en castellano.** Modelos, tablas, variables, rutas y vistas siguen el
   glosario del negocio en español. Nada de mezclar inglés y castellano en el dominio.

---

## Stack y decisiones tomadas

- **Backend**: Laravel 11, PHP 8.2.
- **Frontend**: Livewire 3 + Blade + Alpine.js + TailwindCSS, compilado con Vite.
- **Auth**: Laravel Jetstream + Fortify. Roles/permisos con Spatie Laravel Permission. 2FA disponible.
- **Multi-DB**: una base MySQL por módulo, conexión por modelo (`$connection`). Ver axioma 1.
- **PDF**: `barryvdh/laravel-dompdf` para reportes.
- **Excel**: `maatwebsite/excel` + PhpSpreadsheet para exports.
- **API externa**: endpoints JWT (`firebase/php-jwt`) para el Portal de Proveedores. Ver `routes/api.php`.
- **Email**: `innoge/laravel-msgraph-mail` (MS Graph), jobs en cola, logs en `email_logs`.

---

## Entorno y comandos

- **SO / shell**: Windows + XAMPP, shell **PowerShell**. La DB es la **MySQL de XAMPP** — tiene que
  estar levantada para correr la app y los tests.
- **Tests**: `php artisan test` (todo) o `php artisan test --filter=<Nombre>` para acotar. En
  `phpunit.xml` la conexión de DB está comenteada, así que los tests **corren contra la MySQL real de
  XAMPP**. El `TestCase` usa **`DatabaseTransactions`** (no `RefreshDatabase`), porque el esquema es
  multi-base y las transacciones respetan las conexiones sin recrear todo. Si fallan por no conectar,
  es que MySQL no está corriendo — no es un bug del test.
- **Formateo**: `./vendor/bin/pint` (Laravel Pint). Correr sobre lo tocado antes de commitear.
- **Assets**: `npm run dev` (watch) / `npm run build` (producción) para Vite/Tailwind.

---

## Testing

No hay una suite histórica completa: el testing se está construyendo de forma incremental (ver
`docs/TESTS.md` y `docs/ROADMAP.md`). El criterio se dosifica por **zona** (qué se tocó) y por
**checkpoint** (cuándo se corre qué). No se gastan diez unidades de esfuerzo en programar y cien en
re-testear cosas ya probadas.

**Núcleo sagrado (los axiomas: aislamiento multi-DB, permisos/módulos dinámicos, contrato de la API
externa, email en cola):**

- **Todo cambio en este núcleo lleva test antes de considerarse terminado.** Camino feliz + casos de
  permisos (los 403 esperados) + el caso feo (un endpoint de la API externa que devuelve la forma
  equivocada, un modelo que quedó sin `$connection`, un módulo inactivo cuyas rutas no deberían cargar).
  Nada de "test pendiente para después" acá.
- Tests de integración reales contra la MySQL de XAMPP con `DatabaseTransactions`, no mocks de DB.
- Nombres en español descriptivo: `un_usuario_sin_permiso_no_puede_ver_concursos`.

**Periferia (CRUD de un módulo, Blade, Tailwind, textos, tweaks de UI):**

- Módulos nuevos o refactorizados deberían tener al menos un **Feature test básico** (Pest) del flujo
  principal (index / create / store). Si genuinamente no se puede en el momento, se dice explícitamente.
- Lógica cerrada y trivial que si se rompe se rompe sola (un color, un copy, una validación tonta) no
  lleva test propio: es esfuerzo al pedo.

**Alcance de la corrida (aplica a las dos zonas):**

- Durante el trabajo se corre **solo el/los test locales relevantes** (`--filter=<Nombre>`), no la
  suite entera.
- La **suite completa** es un evento de *checkpoint*: al cerrar un bloque grande, antes de commitear
  algo del núcleo sagrado, o cuando se tocó algo transversal (un modelo base, una conexión, config).
- Se reporta el **resumen** (`110 passed`, o los que fallan con su detalle), no el volcado verde línea
  por línea. Si fallan por no conectar a MySQL, se dice — no se maquilla ni se oculta.

**Testeo visual**: no lo hace Claude. Nada de `curl`, Playwright ni levantar navegador para "ver" una
pantalla, salvo pedido explícito. La revisión visual la hace el usuario; a lo sumo manda un screenshot.

---

## Principios de código

- **YAGNI explícito**: sin capas de abstracción, patrones o infraestructura hasta que una funcionalidad
  concreta los necesite de verdad. Nada de repositorios/servicios "por las dudas".
- Convenciones estándar de Laravel/Livewire. Sin inventar estructura de carpetas ni patrones propios.
- Sin código defensivo para casos que no pueden pasar. Validación en los bordes del sistema (input de
  usuario, Form Requests, respuesta de una API externa), no en cada método interno que ya confía en sus
  invariantes.
- **Tres líneas repetidas son mejores que una abstracción prematura.**
- `$fillable` explícito, nunca `$guarded = []`. `$casts` para fechas, booleanos y enums.
- Eager loading de lo que la vista use; nunca lazy-load dentro de un `@foreach` en la vista.
- Español en nombres, métodos, mensajes de validación y comentarios. camelCase (métodos/variables),
  PascalCase (clases), snake_case (tablas/columnas).

---

## Documentación

- Comentarios inline solo cuando el *por qué* no es obvio. Métodos nuevos o sustancialmente cambiados
  llevan docblock breve si el nombre no alcanza para explicar qué disparan / quién los llama / de qué
  dependen.
- `docs/ARQUITECTURA.md` — el **qué** global del sistema (módulos, capas, decisiones estructurales).
- `docs/modulos/` — un archivo por módulo (12 hoy). Actualizar cuando cambia algo estructural del módulo.
- `docs/DECISIONES.md` — bitácora **append-only** de decisiones de diseño/arquitectura, con el motivo y
  lo que se descartó. Cuando una charla cierra algo, se anota acá. No se reescribe el pasado: si una
  decisión se revierte, se agrega una entrada nueva que la revierte.
- `docs/updates/YYYY-MM-DD_titulo.md` — detalle de cada cambio significativo. Uno por sesión relevante.
- `docs/CHANGELOG.md` — índice cronológico de los cambios (a partir de Julio 2026), un renglón por
  entrada. Actualizar cada vez que se cierra un cambio significativo.
- `docs/ROADMAP.md` — trabajo pendiente y hecho desde Julio 2026 (no es historial: eso es el changelog).
  `docs/archivo/HOJA_DE_RUTA.md` es el roadmap **legacy** (previo a Julio 2026), se conserva como referencia.
- `docs/TESTS.md` — estructura y estado de los tests.

---

## Lo que no hacer

- No hacer joins ni foreign keys cross-base a nivel DB. La relación entre módulos se resuelve en Eloquent.
- No crear un modelo sin su `protected $connection` explícito.
- No inventar un esquema de permisos por fuera de Spatie ni saltearse el chequeo en componentes Livewire.
- No hardcodear rutas/menús de un módulo: se derivan de la tabla `modulos`.
- No cambiar la forma de una respuesta de la API externa sin tratarlo como núcleo sagrado (aviso + test).
- No mandar email masivo sincrónico ni por fuera de la cola/`email_logs`.
- No guardar adjuntos por fuera de Spatie MediaLibrary.
- No introducir Vue/React ni un framework front nuevo sin pasar por el Principio cero.
- No crear abstracciones (repositorios/servicios) ni campos "por si acaso" sin necesidad real.
- No usar `$guarded = []`. No dejar `dd()`, `dump()`, `var_dump()` ni `console.log` de debug en commits.
- No hacer lazy-loading en vistas. No mezclar inglés con castellano en el dominio.
