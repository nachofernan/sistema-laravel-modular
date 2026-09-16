# Decisiones de diseño y arquitectura

Bitácora **append-only** de las decisiones de diseño y arquitectura de la plataforma BAESA. Cada
entrada registra **qué se decidió, por qué, y qué se descartó**. Es la memoria del proyecto: cuando una
charla cierra algo, se anota acá.

**Reglas de la bitácora:**

- **No se reescribe el pasado.** Si una decisión se revierte o cambia, no se edita la entrada vieja: se
  agrega una **entrada nueva** que la reemplaza y referencia a la anterior por su fecha/título.
- Orden cronológico, la más reciente arriba de su bloque de fecha.
- Si hay contradicción entre el `CLAUDE.md` y una decisión registrada acá, **manda la decisión más
  reciente** (y conviene actualizar el `CLAUDE.md` para reflejarla).
- Esto es distinto del `CHANGELOG.md` (qué cambió en el código) y del `ROADMAP.md` (qué falta hacer).
  Acá va el **por qué** de las decisiones, no el detalle de la implementación.

Formato de cada entrada:

```
## YYYY-MM-DD — Título corto de la decisión

**Decisión:** qué se resolvió.
**Motivo:** por qué.
**Se descartó:** qué alternativas se consideraron y por qué no.
**Reemplaza a:** (opcional) fecha/título de la decisión previa que esta deja sin efecto.
```

---

## 2026-09-16 — Se destraba el merge de `upgrade/laravel-13` a `main`

**Decisión:** El usuario confirma que el servidor de producción ya corre PHP 8.3+. Con la
precondición cumplida, se ejecuta el merge de `upgrade/laravel-13` a `main` (verificación final de
suite completa en 175 passed / 3 failed, mismos fallos preexistentes de siempre, merge `--no-ff`
sin conflictos, rama borrada). `main` queda en Laravel 13.30.1.

**Motivo:** Resuelve la única condición pendiente que dejó pausado el merge desde la decisión del
2026-09-04, de abajo.

**Se descartó:** nada nuevo, es la continuación directa de lo ya decidido — no hubo alternativas a
evaluar una vez cumplida la precondición.

**Reemplaza a:** 2026-09-04 — Se pausa el merge de `upgrade/laravel-13` a `main` por el PHP de
producción.

---

## 2026-09-04 — Se pausa el merge de `upgrade/laravel-13` a `main` por el PHP de producción

**Decisión:** El código y los tests del upgrade completo (Laravel 11 → 12 → 13) quedan terminados,
verificados (suite completa 175 passed / 3 failed preexistentes, smoke test manual OK incluyendo
envío real de email) y commiteados en la rama `upgrade/laravel-13`. El **merge a `main` se pausa a
propósito** hasta que se actualice el PHP del servidor de producción — puede quedar así sin
problema salvo que aparezca una urgencia real que obligue a tocar `main`.

**Motivo:** Producción corre **PHP 8.2**. Laravel 13 requiere PHP 8.3+ (confirmado puntualmente
porque `spatie/laravel-permission` 8.3.0 declara `"php": "^8.3"` en su `composer.json`, y es una
dependencia dura del framework en esta versión). Mergear ahora dejaría `main` con un framework que
no arranca en el servidor tal como está configurado hoy. Se estima retomar la semana del
2026-09-07, cuando se actualice el PHP de producción.

**Corrección sobre el relevamiento inicial** (ver la decisión del 2026-09-03, más abajo): en ese
momento se había anotado que "el PHP de este XAMPP ya es 8.3.33, cubre el mínimo de Laravel 13" —
eso era correcto para el **entorno local de desarrollo**, pero nunca se chequeó la versión de PHP
del **servidor de producción**, que resultó ser distinta (8.2). Queda como aprendizaje para
próximos upgrades de framework: confirmar la versión de PHP en todos los entornos relevantes, no
solo en el de desarrollo, antes de dar por cerrado el relevamiento previo.

**Se descartó:** mergear igual y postergar el bump de PHP de producción como tarea aparte —
implicaría dejar `main` roto en producción hasta que se resuelva el PHP, contra la regla del
proyecto de no dejar `main` en un estado que no funcione.

---

## 2026-09-03 — Se encara el upgrade de Laravel 11 a 13, pasando por 12, en una sola rama

**Decisión:** Se sube el framework de Laravel v11.45.1 a Laravel 13, en dos escalones (11→12,
12→13) pero sin quedarse a vivir en el 12 intermedio. Todo el trabajo se hace en una única rama
(`upgrade/laravel-13`), con un commit por paso lógico, y se mergea a `main` una sola vez al final,
cuando la suite completa pase y el smoke test manual esté OK en Laravel 13. Se marca el commit de
arranque con el tag liviano `pre-upgrade-laravel11` para tener un punto de referencia fácil.

Relevado antes de arrancar: `bootstrap/app.php` ya está en el formato moderno de Laravel 11 (sin
`Kernel.php`), el PHP de este XAMPP ya es 8.3.33 (cubre el mínimo de Laravel 13), y dos paquetes
tienen incompatibilidad confirmada con Laravel 12 tal como están hoy: `maatwebsite/excel` (3.1 →
4.0.2) e `innoge/laravel-msgraph-mail` (1.4 → 2.x). Se confirmó además que `innoge/laravel-msgraph-mail`
**no está en uso activo** hoy (`.env` tiene `MAIL_MAILER=smtp` contra `smtp.office365.com`; las
líneas de `microsoft-graph` están comentadas), así que su actualización es de bajo riesgo funcional
aunque sigue haciendo falta para que `composer update` del framework no se trabe. Livewire 3 no
soporta Laravel 13: hace falta subir a Livewire 4 dentro del escalón 12→13.

**Motivo:** Es un cambio que toca la infraestructura transversal completa (todos los axiomas a la
vez), no un módulo — se decide y ejecuta en el hilo principal. El análisis previo completo está en
`docs/updates/2026-09-03_upgrade-laravel-13.md` (se va actualizando a medida que avanza cada paso).

**Se descartó:** un salto directo 11→13 (acumula demasiado riesgo sin poder aislar qué rompió qué).
También se descartó mergear a `main` después de cada escalón (12 estable primero, 13 después): se
prefirió una sola rama larga para simplificar el trabajo con git, asumiendo que si aparece una
urgencia real de producción mientras tanto se resuelve aparte en `main` y se trae a la rama.

---

## 2026-08-19 — El gestor del concurso se corrige para que reciba sus notificaciones

**Decisión:** En `Concurso::getCorreosInteresados()`, el bloque que arma el grupo `'contactos_concurso'`
usaba `$this->usuario_id` (columna inexistente — la columna real de `concursos` es `user_id`) y
`$this->usuario->correo` (el modelo `User` no tiene `correo`, es `email`). Se corrigen ambos a
`user_id`/`email`.

**Motivo:** Surgió al armar la notificación de documentación adicional cargada en análisis (ver
`docs/updates/2026-08-19_documentacion-adicional-analisis-concursos.md`), que depende de que ese grupo
incluya al gestor. Con el bug, el `if($this->usuario_id)` siempre era falso: el gestor **nunca** se
agregó a ese grupo, en ningún envío. Como el mismo método sirve a prórroga, apertura, cierre y
anulación (`ProrrogaController`, `AccionesConcurso`), el fix cambia también el comportamiento de esos
envíos existentes: a partir de ahora el gestor sí los recibe, que es lo que el código siempre pretendió
hacer. Aprobado explícitamente por el usuario antes de tocar el método compartido, por el efecto en
cascada sobre notificaciones ya en producción.

**Se descartó:** resolver el caso nuevo agregando el email del gestor a mano solo en el controller de
la notificación nueva, dejando el bug intacto en el método compartido — hubiera dejado sin arreglo el
mismo problema en prórroga/apertura/cierre/anulación.

---

## 2026-08-04 — Los agentes son fases del trabajo, no rangos

**Decisión:** Se reemplazan los tres agentes por rango (`baesa-mentor` Opus, `baesa-senior` Opus,
`baesa-junior` Sonnet) por tres agentes por **fase**: `explorador` (Haiku, solo lectura — rastrea y
devuelve la conclusión con rutas exactas), `ejecutor` (Sonnet, edita periferia con la decisión ya
tomada) y `testeador` (Haiku, corre tests y devuelve el veredicto destilado). Se eliminan el mentor y
el senior. El criterio que ordena: **se delega el trabajo mecánico o de fan-out, no el juicio**; todo
lo que toca el núcleo sagrado se planea y se implementa en el hilo principal, con el usuario presente.
Se actualiza `CLAUDE.md` §"Los modos de trabajo" en consecuencia.

**Motivo:** Baja de consumo de tokens por tarea, verificada en otro proyecto (Consultorio) donde la
estructura ya venía funcionando. Mentor/senior/junior son el mismo trabajo a tres potencias:
`baesa-senior` era Opus arrancando **en frío**, y su propio prompt le mandaba leer `CLAUDE.md` +
`ARQUITECTURA.md` + el `docs/modulos/` correspondiente + `DECISIONES.md` (~650 líneas) antes de tocar
un archivo — contexto que el hilo principal ya tenía cargado, pagado dos veces a precio de Opus. Y
`baesa-mentor` era redundante por definición: el propio `CLAUDE.md` establecía que la sesión principal
trabaja con ese stance por defecto, con más contexto que el subagente y con capacidad de repreguntar.
Lo que sí paga es Haiku destilando volumen sin ejercer juicio: un barrido de archivos que vuelve como
tres rutas, una corrida de PHPUnit que vuelve como `110 passed`.

**Se descartó:** (a) Copiar el modelo tal cual de Consultorio — allá el núcleo es un dominio único
concentrado en `app/Services/` y `app/Actions/`, así que el ejecutor detecta el nervio **por ruta de
archivo**; en BAESA el núcleo es infraestructura transversal esparcida en 12 módulos, y el límite hubo
que definirlo **por tipo de cambio**. Se conservó para el `ejecutor` la lista de límites de
`baesa-junior`, que ya era esa traducción. (b) Conservar el `senior` para fan-out estructural ya
decidido: ese caso lo cubre el `ejecutor` con el paso cerrado, y dejarlo disponible reinstala la
tentación de usarlo para lo que no es fan-out. (c) Conservar el `mentor`, por lo dicho en el motivo.
(d) Adoptar dos cosas más que venían en el mismo paquete de Consultorio y no se tomaron: pasar
`DECISIONES.md` de append-only a "referencia viva" reescribible por tema (con 4 años de historia, el
*por qué cayó* una decisión vale más que la prolijidad) y bajar el piso de testing a "el default es no
testear" (allá el riesgo es un saldo que el usuario ve al día siguiente; acá es un módulo que rompe
otro en silencio — el ahorro real ya lo da la regla de alcance `--filter`/checkpoint, que no se toca).
Sí se tomó del paquete el docblock que **nombra el test que lo cubre**, para el núcleo sagrado.

**Reemplaza a:** 2026-07-23 — Adopción de la estructura de trabajo por roles y bitácora de decisiones
(solo en la parte de los agentes; el resto de esa entrada —principio cero, axiomas, testing dosificado,
esta bitácora— sigue vigente).

---

## 2026-08-04 — El número de versión lo pone la persona, no el sistema

**Decisión:** `documentos.version` deja de autoincrementarse. El número se escribe en el alta, en
la edición y en el modal de nueva versión, donde viene **sugerido** (el siguiente) pero es editable.
La única regla es que no puede pisar una versión ya archivada: al subir un archivo tiene que ser
mayor que la vigente, que es la que pasa al historial.

**Motivo:** los documentos no nacen en la Plataforma. Uno puede llegar a la Plataforma ya en la v4
de Control de Gestión, y con la numeración automática quedaba registrado como v1 —o como v2 al
reemplazarlo— sin forma de corregirlo. El número que importa es el del documento, no el de cuántas
veces se lo subió acá.

**Se descartó:** (a) derivar la versión del `_vN` del `codigo` parseándolo — vuelve a atar la
Plataforma al criterio de codificación de otra área, que es justo lo que se decidió no hacer el
2026-07-28; además hay documentos sin código. (b) Dejar el autoincremento y agregar aparte un
campo de "versión del documento": dos numeraciones para la misma cosa.

**Reemplaza a:** la parte de *2026-07-28 — El módulo Documentos publica, no gestiona el ciclo de
vida documental* que definía `version` como "entero propio que cuenta reemplazos de archivo". Sigue
en pie que **no se parsea el `codigo`**: que los dos números coincidan es decisión de quien carga,
no una sincronización del sistema.

---

## 2026-07-28 — El módulo Documentos publica, no gestiona el ciclo de vida documental

**Decisión:** Documentos se modela como **repositorio de publicación**: recibe el documento
terminado y lo pone a disposición. La codificación de Control de Gestión (`L-07.2-003_v3`,
`PG-07.2-012-v4`) se guarda en una columna `codigo` que es **string libre**, sin parsear, sin
catálogo de tipos documentales ni de procesos, y sin validación de formato. El `version` del
sistema es un entero propio que cuenta reemplazos de archivo y **no se sincroniza** con el `_vN`
del código.

**Motivo:** El criterio de codificación, la numeración y la aprobación son trabajo del área de
Control de Gestión, que los administra en Redmine. En la Plataforma se subían "sólo los públicos
y finales". Modelar acá el ciclo de elaboración sería duplicar un proceso que no controlamos y
que cambiaría por decisiones ajenas al sistema.

**Se descartó:** (a) tablas de tipos documentales (`Z`/`M`/`L`/`PG`/`PE`/`F`/anexos) y de procesos
(`07.2`, `09.0`, `17.1`) derivadas de los nombres de archivo — el patrón existe y es consistente,
pero convertirlo en esquema ata la Plataforma a un criterio de otra área. (b) Correlativo
autogenerado: lo asigna una persona. (c) Flujo de aprobación (elaboró/revisó/aprobó, vigencia
formal) — sobreingeniería para lo que el módulo hace; en su lugar, un campo `observaciones` libre.
(d) Un campo `estado`: `visible` y `publico` ya deciden todo lo que la aplicación decide, y un
estado que sólo se muestra es texto con reglas de más.

**Pendiente, no decidido:** absorber el contenido de Redmine es una idea conversada, sin acuerdo
con la gerencia. Este modelo no la presupone ni la bloquea.

---

## 2026-07-28 — La visibilidad pública es un atributo, no una consecuencia

**Decisión:** Que algo se vea sin iniciar sesión se declara en el modelo: `categorias.publica` y
`documentos.publico`. Un documento es público sólo si él y toda su rama de categorías lo son, y el
controlador lo verifica antes de servir el archivo.

**Motivo:** La visibilidad se derivaba de la estructura y de la ruta: el menú público listaba toda
categoría raíz que existiera (con la query adentro del Blade, repetida en tres navegaciones), y la
descarga pública entregaba cualquier documento a quien conociera el ID —incluidos los marcados como
no visibles— sin registrar la descarga. No había forma de tener una categoría interna.

**Se descartó:** resolverlo con un filtro en la vista. La vista decide qué mostrar; qué se puede
entregar es una regla del dominio y tiene que estar donde no se pueda esquivar.

---

## 2026-07-23 — Reorganización de la carpeta `docs/`

**Decisión:** Se reordena `docs/` en subcarpetas por propósito: la raíz queda con lo canónico y vigente
(`ARQUITECTURA`, `CHANGELOG`, `ROADMAP`, `DECISIONES`, `TESTS`, más `modulos/` y `updates/`); `guias/`
para manuales operativos vigentes (email, mantenimiento, sidebar, TitoBot); `api/` para la doc de la
API externa, con un `README.md` índice que marca las fuentes canónicas; y `archivo/` para material
superado o histórico, que se conserva pero no se mantiene. Se agrega un `docs/README.md` como mapa de
entrada. Se corrigieron los links internos afectados por los movimientos.

**Motivo:** La carpeta tenía 21 archivos sueltos mezclando lo vigente con lo viejo, con solapamientos
(`SISTEMA_GENERAL` superado por `ARQUITECTURA`) y la doc de la API fragmentada en 9 archivos. Costaba
saber qué era fuente de verdad y qué era histórico.

**Se descartó:** (a) reescribir los 9 docs de la API en uno solo — es un contrato con una app externa y
reescribir a mano tiene riesgo de introducir imprecisiones; se prefirió ordenar + indexar sin tocar el
contenido de los endpoints. (b) Borrar el material superado — se archivó en `archivo/` en vez de
eliminarlo, para no perder el registro.

---

## 2026-07-23 — Adopción de la estructura de trabajo por roles y bitácora de decisiones

**Decisión:** Se reestructura el `CLAUDE.md` alrededor de una guía de **cómo se trabaja** (no de qué
hace el sistema): principio cero (nada está en piedra), tres modos de trabajo encarnados como agentes
en `.claude/agents/` (`baesa-mentor` asesor de solo lectura, `baesa-senior` para trabajo estructural,
`baesa-junior` para ediciones mecánicas), axiomas de arquitectura que definen el "núcleo sagrado", y
testing dosificado por zona y checkpoint. Se crea este archivo (`docs/DECISIONES.md`) como bitácora
append-only.

**Motivo:** La estructura viene de otro proyecto (auditoría de riesgos) donde resultó práctica y
cómoda. Se adaptó a la realidad de BAESA: en vez de un dominio único con núcleo sagrado propio, el
núcleo sagrado es la **infraestructura transversal** que sostiene a todos los módulos (aislamiento
multi-DB, permisos `Modulo/Rol` + módulos dinámicos, contrato de la API JWT externa, email en cola).
Ajustes de realidad: Livewire 3 (no 4) y tests con `DatabaseTransactions` multi-base (no
`RefreshDatabase`).

**Se descartó:** Copiar tal cual el `CLAUDE.md` del proyecto de origen (sus axiomas de dominio —
cálculo de riesgo, ciclo de estados, autorización por área — no aplican a BAESA). También se descartó
no crear los agentes y dejar solo los stances descritos en el `CLAUDE.md`: se prefirió tener los tres
agentes reales para poder delegar.
