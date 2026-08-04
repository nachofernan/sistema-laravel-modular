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

Este proyecto se conversa antes de codearse. **El hilo principal —donde vos y el usuario piensan,
deciden y tocan la infraestructura transversal— es el centro.** Ahí se discute el diseño, se cierra
una decisión y se implementa todo lo que toca el núcleo sagrado: la integridad del núcleo se protege
con *presencia* (el usuario en la conversación), no con potencia. **El núcleo sagrado no se delega.**

Los agentes de `.claude/agents/` **no son rangos** (un junior, un senior, un jefe): son **fases del
trabajo**. Un subagente no le sirve al usuario, le sirve al hilo principal: es una función acotada
que corre en su propia ventana de contexto y devuelve un resultado destilado, para no ensuciar la
conversación con material crudo. Se delega el trabajo *mecánico o de fan-out*, no el juicio.

- **Explorador** (`explorador` — Haiku, solo lectura). El sabueso: rastrea dónde vive una lógica, cómo
  se conectan dos módulos, qué permiso gobierna una acción, si ya existe un componente para X, y
  **devuelve la conclusión con rutas exactas, no el volcado de archivos.** Se usa cuando contestar algo
  implica barrer muchos archivos o varios módulos y solo importa el resultado. Con 12 módulos y las
  relaciones cross-módulo resueltas en Eloquent, ese barrido es caro y frecuente. Buscar bien es
  mecánico; por eso es Haiku, no porque sea tonto.
- **Ejecutor** (`ejecutor` — Sonnet, edita). Ejecuta **decisiones ya tomadas** en la **periferia**:
  copy en vistas, Blade/Tailwind/Alpine, typos, un campo a `$fillable` cuando la columna ya existe,
  renombrar una variable local, mover un partial. Cumple con poco preámbulo y sin reabrir lo decidido.
  No corre tests ni commitea. Si el pedido resulta estructural (migración, permiso, `$connection`,
  ruta, relación nueva, API externa, cola/email) o toca el núcleo sagrado, **corta y lo devuelve al
  hilo principal.**
- **Testeador** (`testeador` — Haiku, solo corre y reporta). Corre `php artisan test` y devuelve un
  veredicto destilado —`110 passed`, o los que fallan con su detalle— sin cargar el volcado verde en
  la conversación. Distingue un fallo real de la MySQL de XAMPP apagada. **No arregla:** un test que
  falla nunca se maquilla para que pase; el arreglo se decide en el hilo principal.

Regla que ordena todo: **planear y ejecutar el núcleo sagrado pasan por el hilo principal, con el
usuario presente.** Los subagentes están para lo que *no* es esa decisión — encontrar, ejecutar
periferia, verificar. Si dudás de si algo es núcleo o periferia, es núcleo: preguntá antes de delegar.

> Nota: un subagente corre en contexto aislado y **no puede preguntarte en vivo** — el ida y vuelta
> ocurre en el hilo principal, que es quien delega y releva las dudas que el subagente devuelve en su
> reporte. Por eso no se delega lo que va a necesitar una charla a mitad de camino.

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
  por línea. Si fallan por no conectar a MySQL, se dice — no se maquilla ni se oculta. **El costo no es
  correr los tests: es cargar su output en la conversación.** Por eso la corrida —sobre todo la suite
  completa— va por el `testeador`, que devuelve el veredicto sin el volcado.

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
- Cuando un método del **núcleo sagrado** tiene lógica real (una regla, un cálculo, un chequeo de
  permiso no trivial), su docblock **nombra el test que lo cubre** — por nombre del test, no por
  `archivo:línea`. Así se puede tirar ese test con `--filter` sin barrer la suite buscando cuál era.
  No va en getters ni en un cambio de `true`/`false`. Si el test se renombra, se actualiza el
  docblock: es una línea.
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
