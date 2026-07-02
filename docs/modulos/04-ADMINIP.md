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
| `IP` | `ips` | Registro de una IP con descripción y categoría. |
| `Categoria` | `categorias` | Categoría de IP (ej: Servidores, Impresoras, Terminales). |

---

## IP: campos clave

```
id, ip (dirección IP), descripcion, categoria_id, created_at, updated_at
```

---

## Estructura

Es el módulo más simple del sistema: dos tablas, dos modelos, un par de vistas. No tiene Livewire ni lógica de negocio compleja; es básicamente un CRUD con filtro por categoría.

---

## Puntos a mejorar

- No valida que la IP sea única o tenga formato válido a nivel de DB.
- No tiene historial de cambios.
- No hay integración con escaneo de red o DHCP.
- Si crece la gestión de red, este módulo debería expandirse para incluir MAC address, VLAN, estado de conectividad, etc.
