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

El servicio `app/Services/ProcesadorPrn::procesar()` parsea el contenido de un archivo `.PRN`: detecta y saltea el header (línea que empieza con `time`), interpreta cada línea como fecha/hora + columnas de datos, toma la columna indicada por `registrador->columna_datos`, la multiplica por `factor_conversion` y hace upsert en `lecturas` (por `registrador_id` + `fecha` + `bloque_horario` + `hora_hasta`, así reprocesar el mismo archivo actualiza en vez de duplicar). Cada bloque horario dura 15 minutos; la hora "24:00" de fin de día se mapea al bloque 23 (23:45–00:00).

Hay dos formas de cargar archivos, ambas **manuales desde el navegador** (no hay cron ni carpeta de red involucrados):

- **`Despacho/CargaPrn`** (`livewire/despacho/carga-prn.blade.php`): el usuario elige el registrador de un `<select>` y sube un único archivo (`WithFileUploads`). Usa `procesar()` con el registrador ya conocido.
- **`Despacho/CargaAutomatica`** (`livewire/despacho/carga-automatica.blade.php`): se llama "automática" porque el registrador **no se selecciona a mano** — se autodetecta leyendo el código en el header del archivo (`ProcesadorPrn::detectarCodigoRegistrador()`, segunda columna de la línea `time,...`) y se busca por `Registrador::where('codigo', ...)`. El usuario selecciona varios archivos con `<input type="file" multiple>`; un script Alpine.js (`cargaAuto`) los lee como base64 en el browser (`FileReader.readAsDataURL`) y los manda uno por uno a `procesarArchivo()` vía Livewire, mostrando una tabla de progreso por archivo. Todo el procesamiento ocurre en request/response normales de Livewire, disparados por el usuario — no hay ninguna tarea programada en `routes/console.php` para este módulo ni lectura de ruta de red/carpeta compartida.

---

## Componentes Livewire

| Componente | Función |
|-----------|---------|
| `Despacho/MaquinasIndex` | Listado y gestión de máquinas |
| `Despacho/RegistradoresIndex` | Listado y gestión de registradores |
| `Despacho/VisorDiario` | Visualización de lecturas del día |
| `Despacho/CargaPrn` | Carga manual de un archivo PRN, registrador elegido a mano |
| `Despacho/CargaAutomatica` | Carga de varios archivos PRN a la vez, registrador autodetectado del header de cada archivo |

---

## Puntos a mejorar

- El módulo es específico del negocio energético y requiere conocimiento del dominio para extenderlo. Sin documentación de los equipos físicos, es difícil de mantener.
- No hay validación de integridad de las lecturas (detectar valores fuera de rango).
- No hay exportación a Excel de las lecturas.

## Código eliminado (bugs en métodos sin uso)

- `Maquina::lecturas()` usaba `hasManyThrough(Lectura::class, Registrador::class)`, que asume una FK directa `registradores.maquina_id`. Pero Maquina↔Registrador es **many-to-many** (tabla pivot `maquina_registrador`), no one-to-many — `hasManyThrough` no es válido para ese caso y la query rompía en runtime. No se usaba en ningún lado: `VisorDiario.php` arma las lecturas a mano con `Lectura::whereIn('registrador_id', $registradores->pluck('id'))`. Eliminado.
- `Lectura::getMaquinaAttribute()` llamaba a `$this->registrador->maquina` (singular), pero `Registrador` solo define `maquinas()` (plural). Tampoco tenía sentido conceptual: un registrador puede pertenecer a varias máquinas, no hay "la" máquina de una lectura. Sin uso. Eliminado.
