# Documentación — Plataforma BAESA

Mapa de la documentación del proyecto. Punto de entrada: si buscás algo, empezá acá.

## Cómo se trabaja el proyecto

- [`../CLAUDE.md`](../CLAUDE.md) — guía de cómo se trabaja: modos de trabajo (mentor/senior/junior),
  axiomas de arquitectura, testing, convenciones. **Es lo primero que hay que leer.**
- [`DECISIONES.md`](DECISIONES.md) — bitácora append-only de decisiones de diseño (qué / por qué / qué
  se descartó).

## Qué es y cómo está hecho el sistema

- [`ARQUITECTURA.md`](ARQUITECTURA.md) — arquitectura general: stack, multi-DB, auth/permisos, carga
  dinámica de módulos, deuda técnica conocida. **Doc canónico de arquitectura.**
- [`modulos/`](modulos/) — un archivo por módulo (12). El detalle de cada área funcional.
- [`api/`](api/) — documentación de la API externa (Portal de Proveedores). Ver su
  [`api/README.md`](api/README.md).

## Estado y gestión del trabajo

- [`ROADMAP.md`](ROADMAP.md) — trabajo pendiente y hecho (desde Julio 2026).
- [`CHANGELOG.md`](CHANGELOG.md) — índice cronológico de cambios significativos.
- [`updates/`](updates/) — detalle de cada cambio relevante, un archivo por sesión.
- [`TESTS.md`](TESTS.md) — estructura y estado de los tests automatizados.

## Guías operativas y de referencia

- [`guias/`](guias/) — manuales de sistemas y features vigentes (email, modo mantenimiento, sidebar,
  TitoBot/Gemini).

## Histórico

- [`archivo/`](archivo/) — documentación superada, análisis puntuales y guías que ya no reflejan el
  sistema actual. Se conserva por referencia, **no se mantiene**. Ver su [`archivo/README.md`](archivo/README.md).

---

*Estructura reorganizada el 2026-07-23. Ver la entrada correspondiente en `DECISIONES.md`.*
