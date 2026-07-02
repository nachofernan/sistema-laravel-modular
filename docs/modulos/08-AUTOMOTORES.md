# Módulo: Automotores

**Base de datos**: `automotores` (`DB_DATABASE_AUTOMOTORES`)  
**Rutas**: `routes/automotores.php`  
**Complejidad**: Baja

---

## Qué hace

Gestión de la flota vehicular de BAESA:

- Registro de vehículos (marca, modelo, patente, kilometraje actual).
- Carga de COPRES (Comprobantes de Carga de Combustible): quién cargó, cuántos litros, precio, importe, km al momento de la carga, fechas de salida y reentrada.
- Registro de services (mantenimientos periódicos).
- Alertas automáticas cuando un vehículo se aproxima al kilometraje de service.

---

## Modelos

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `Vehiculo` | `vehiculos` | Vehículo de la flota. |
| `Copres` | `copres` | Comprobante de carga de combustible. |
| `Service` | `services` | Registro de service/mantenimiento. |

---

## Vehiculo: campos clave

```
id, marca, modelo, patente, kilometraje (actual), created_at, updated_at
```

El accessor `getNombreCompletoAttribute()` devuelve `"marca modelo"`.

El accessor `getNecesitaServiceAttribute()` implementa la lógica de alerta:
- Se activa cuando el resto de `kilometraje % 10000` está entre 9000 y 3000 (ventana del service).
- Dentro de esa ventana, verifica si el último service fue hace más de 6000 km.
- Si es así, retorna `true` (necesita service).

---

## Copres: campos clave

```
id, fecha, numero_ticket_factura, cuit (del proveedor de combustible),
vehiculo_id, litros, precio_x_litro, importe_final,
es_original (bool: si es el comprobante original o una copia),
km_vehiculo, kz (campo adicional de control), salida (date), reentrada (date),
user_id_creator, created_at, updated_at
```

---

## Service: campos clave

```
id, vehiculo_id, fecha_service, kilometros (km al momento del service),
descripcion, created_at, updated_at
```

---

## Componentes Livewire

| Componente | Función |
|-----------|---------|
| `Automotores/Vehiculos/Index` | Listado de vehículos + alerta de service |
| `Automotores/Copres/Index` | Listado de COPRES |
| `Automotores/Services/Index` | Historial de services |

---

## Puntos a mejorar

- La lógica de alerta de service (`getNecesitaServiceAttribute`) está hardcodeada a 10.000 km de intervalo. Debería ser configurable por vehículo.
- No hay exportación a Excel de los COPRES (útil para contabilidad).
- No hay gestión de seguros, VTV u otras habilitaciones vehiculares.
- El campo `kz` en COPRES no tiene documentación de qué significa; hay que consultarlo con quien lo usó.
