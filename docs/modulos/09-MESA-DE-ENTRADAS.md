# Módulo: Mesa de Entradas

> **MÓDULO DEPRECADO — NO SE USA Y SE VA A ELIMINAR**  
> Nunca llegó a usarse en producción. Se mantiene en el código por el momento pero está en la lista de eliminación. No agregar funcionalidad nueva ni invertir tiempo en este módulo.

**Base de datos**: `mesadeentradas` (`DB_DATABASE_MESADEENTRADAS`)  
**Rutas**: `routes/mesadeentradas.php`  
**Complejidad**: Baja

---

## Qué hace (intención original)

Registro de documentación entrante a BAESA (mesa de entradas administrativa). Permite registrar y hacer seguimiento de los documentos que ingresan físicamente a la empresa: notas, expedientes, cartas, etc.

---

## Modelo

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `Entradas` | `entradas` | Registro de un documento/entrada recibida. |

El modelo es muy simple: `protected $guarded = []` (acepta todos los campos) y sin relaciones definidas.

---

## Migración (2024)

La tabla `entradas` se creó en junio de 2024. La migración `2026_03_12_120101_create_generacion_table.php` agrega la tabla `generacion` (posiblemente para generar numeración correlativa de entradas).

---

## Estado actual

Es uno de los módulos más nuevos y menos desarrollados. Tiene la estructura mínima. La funcionalidad exacta de campos depende de lo implementado en la migración; el modelo no tiene relaciones definidas aún.

---

## Puntos a mejorar

- El modelo no tiene relaciones (con `User` para saber quién registró, por ejemplo).
- No hay documentación de los campos de la tabla `entradas`.
- La tabla `generacion` sugiere un sistema de numeración correlativa que puede no estar completamente implementado.
- Sin Livewire: la UI probablemente usa controllers y vistas Blade tradicionales.
