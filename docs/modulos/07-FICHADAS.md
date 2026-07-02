# Módulo: Fichadas

> **MÓDULO DEPRECADO — NO SE USA Y SE VA A ELIMINAR**  
> Nunca llegó a usarse en producción. Se mantiene en el código por el momento pero está en la lista de eliminación. No agregar funcionalidad nueva ni invertir tiempo en este módulo.

**Base de datos**: `fichadas` (`DB_DATABASE_FICHADAS`)  
**Rutas**: `routes/fichadas.php`  
**Complejidad**: Baja

---

## Qué hace (intención original)

Visualización del registro de fichadas (marcaciones de ingreso/egreso) del personal de BAESA. Este módulo es de **solo lectura**: los datos los genera un sistema externo (reloj de fichadas / sistema de RRHH) y este módulo simplemente los muestra.

---

## Modelo

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `Fichada` | `fichadas` | Registro de una fichada (marcación). |

El modelo tiene `protected $guarded = false` porque es solo lectura y no necesita protección de mass-assignment.

---

## Fichada: campos principales (inferidos del uso)

```
idEmpleado (= legajo del usuario), fecha, hora, tipo (ingreso/egreso o similar)
```

La relación con `User` se hace via `legajo`:
```php
public function usuario()
{
    return $this->belongsTo(User::class, 'idEmpleado', 'legajo');
}
```

Y en `User`:
```php
public function fichadas()
{
    return $this->hasMany(Fichada::class, 'idEmpleado', 'legajo');
}
```

---

## Integración

La tabla `fichadas` en la base de datos es poblada externamente. El sistema de BAESA solo lee de ella. No hay migración en el proyecto (la tabla la crea el sistema externo).

---

## Acceso

Requiere rol `Fichadas/Acceso`. La vista muestra las fichadas filtradas por empleado y/o rango de fechas.

---

## Puntos a mejorar

- La estructura real de la tabla depende del sistema externo. Si cambia, hay que actualizar el modelo.
- No hay sincronización automática ni importación manual dentro del sistema; la base se comparte directamente.
- No hay alertas de ausencias o irregularidades.
