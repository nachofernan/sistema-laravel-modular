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
