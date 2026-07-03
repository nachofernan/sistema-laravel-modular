# Módulo: Despacho

**Base de datos**: `despacho` (`DB_DATABASE_DESPACHO`)  
**Rutas**: `routes/despacho.php`  
**Complejidad**: Media

---

## Qué hace

Sistema de lectura y registro de mediciones de máquinas de despacho (probablemente turbinas, compresoras u equipos de energía). Permite:

- Definir máquinas y registradores (medidores/sensores) asociados.
- Importar lecturas desde archivos `.PRN` generados por los equipos.
- Visualizar lecturas diarias en un visor.
- Carga automática y manual de archivos de datos.

---

## Modelos

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `Maquina` | `maquinas` | Máquina de despacho (turbina, compresor, etc.). |
| `Registrador` | `registradores` | Registrador/sensor asociado a una o varias máquinas. |
| `Lectura` | `lecturas` | Valor registrado por un registrador en un momento dado. |

---

## Maquina: campos clave

```
id, codigo, nombre, descripcion, activa (bool), created_at, updated_at
```

---

## Registrador: campos clave

```
id, codigo, nombre, tipo (principal | respaldo | control | auxiliar),
tipo_dato (pulsos | potencia), columna_datos (int),
factor_conversion (decimal:6), activo (bool), notas,
created_at, updated_at
```

El `factor_conversion` convierte el valor crudo del archivo PRN a la unidad física correspondiente.

---

## Relación Maquina ↔ Registrador

Es **many-to-many**: una máquina puede tener varios registradores (principal + respaldo + control), y un registrador puede estar en varias máquinas. La tabla pivot es `maquina_registrador`.

---

## Procesamiento de archivos PRN

El servicio `app/Services/ProcesadorPrn.php` parsea los archivos `.PRN` que generan los equipos. Cada archivo tiene columnas de datos que se mapean a registradores según `columna_datos`.

---

## Componentes Livewire

| Componente | Función |
|-----------|---------|
| `Despacho/MaquinasIndex` | Listado y gestión de máquinas |
| `Despacho/RegistradoresIndex` | Listado y gestión de registradores |
| `Despacho/VisorDiario` | Visualización de lecturas del día |
| `Despacho/CargaPrn` | Carga manual de archivo PRN |
| `Despacho/CargaAutomatica` | Configuración de carga automática |

---

## Puntos a mejorar

- El módulo es específico del negocio energético y requiere conocimiento del dominio para extenderlo. Sin documentación de los equipos físicos, es difícil de mantener.
- No hay validación de integridad de las lecturas (detectar valores fuera de rango).
- No hay exportación a Excel de las lecturas.
- La "carga automática" sugiere un proceso periódico que puede depender de una ruta de red o carpeta compartida; esto no está documentado.

## Código eliminado (bugs en métodos sin uso)

- `Maquina::lecturas()` usaba `hasManyThrough(Lectura::class, Registrador::class)`, que asume una FK directa `registradores.maquina_id`. Pero Maquina↔Registrador es **many-to-many** (tabla pivot `maquina_registrador`), no one-to-many — `hasManyThrough` no es válido para ese caso y la query rompía en runtime. No se usaba en ningún lado: `VisorDiario.php` arma las lecturas a mano con `Lectura::whereIn('registrador_id', $registradores->pluck('id'))`. Eliminado.
- `Lectura::getMaquinaAttribute()` llamaba a `$this->registrador->maquina` (singular), pero `Registrador` solo define `maquinas()` (plural). Tampoco tenía sentido conceptual: un registrador puede pertenecer a varias máquinas, no hay "la" máquina de una lectura. Sin uso. Eliminado.
