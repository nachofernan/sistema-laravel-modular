# Módulo: AdminIP

**Base de datos**: `adminip` (`DB_DATABASE_ADMINIP`)  
**Rutas**: `routes/adminip.php`  
**Complejidad**: Baja

---

## Qué hace

Registro simple de direcciones IP de la red interna de BAESA, organizadas por categorías. Permite al área de Sistemas:

- Llevar un inventario de IPs asignadas (a equipos, servidores, impresoras, etc.).
- Categorizar las IPs por tipo de dispositivo o zona de red.
- Buscar rápidamente a qué corresponde una IP.

---

## Modelos

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `IP` | `ips` | Registro de una IP con nombre, MAC, descripción y usuario/contraseña asociados. |
| `Categoria` | `categorias` | Categoría de IP. Existe la columna y el FK en DB (ver abajo), pero no se usa en ningún lugar de la aplicación. |

---

## IP: campos clave

```
id, nombre, ip (dirección IP), bloque_a..d (octetos separados), mac, descripcion,
user, password, user_id, categoria_id, created_at, updated_at
```

`categoria_id` (FK nullable a `categorias.id`, `onDelete('set null')`) se agrega en la misma migración que crea `categorias` (`create_categorias_table.php`, no en `create_ips_table.php`). Existe a nivel de esquema pero **no está implementado en la aplicación**: ni `IP` ni `Categoria` declaran la relación Eloquent, ningún formulario Livewire (`Crear`/`Editar`) tiene el campo, y no hay filtro ni columna de categoría en el listado. Es esquema muerto, no código muerto.

---

## Estructura

El módulo más simple del sistema: un CRUD de IPs implementado enteramente en Livewire (`Crear`, `Editar`, `Check`, `TableSearch` en `app/Livewire/Adminip/Ips/Index/`). `IpController` es un resource controller donde solo `index()` hace algo (renderiza la vista que monta los componentes Livewire); el resto de los métodos del resource están vacíos porque el CRUD real pasa por Livewire, no por el controller.

La validación de formato de IP (regex) y unicidad ya está implementada a nivel de los componentes Livewire (`Crear::guardar()`, `Editar::guardar()`), no como `FormRequest` — consistente con el resto del módulo.

---

## Código eliminado

`CategoriaController` (resource controller con los 7 métodos vacíos) y la ruta `adminip.categorias.*` se eliminaron: no había ninguna vista `adminip/categorias/*`, la ruta no se usaba en ningún lado, y el único link de navegación a "Categorías" estaba comentado. El modelo `Categoria` y su columna/FK en DB se dejaron intactos — si en algún momento se quiere categorizar IPs de verdad, la columna ya está lista; solo falta la relación Eloquent y el campo en los formularios.

---

## Puntos a mejorar

- No tiene índice único en `ip` a nivel de DB (la unicidad solo se valida en la capa de aplicación).
- No tiene historial de cambios.
- No hay integración con escaneo de red o DHCP.
- `categoria_id` existe en DB sin usarse — decidir si se implementa la categorización real (relación Eloquent + campo en formularios) o se elimina la columna y la tabla `categorias`.
