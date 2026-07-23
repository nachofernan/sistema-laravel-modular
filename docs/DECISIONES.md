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
