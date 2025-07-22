# Documentación de Tests Automatizados

Este documento describe la estructura, ejecución y recomendaciones para los tests automatizados de la plataforma.

## Estructura de los tests

Los tests están organizados por módulo, siguiendo la estructura de carpetas del proyecto. Por cada módulo principal existe:

- Una carpeta de tests en `tests/Feature/<Módulo>/`.
- Una carpeta de factories en `database/factories/<Módulo>/`.

Cada modelo relevante de cada módulo tiene:
- Un archivo de test básico (`<Modelo>Test.php`) con pruebas de creación y relaciones explícitas.
- Una factory asociada que respeta los campos y relaciones definidos en las migraciones.

### Ejemplo de estructura

```
- tests/
  - Feature/
    - Usuarios/
    - Inventario/
    - Proveedores/
    - Concursos/
    - ...
- database/
  - factories/
    - Usuarios/
    - Inventario/
    - Proveedores/
    - Concursos/
    - ...
```

## ¿Qué cubren los tests?
- Creación de registros en cada modelo principal.
- Verificación de relaciones explícitas (belongsTo, hasMany, etc.)
- Los nombres y mensajes de los tests están en español para facilitar la presentación de informes.
- No se blanquea la base de datos completa: los tests crean y eliminan solo los datos que utilizan.

## Ejecución de los tests

Puedes ejecutar los tests de un módulo específico con:

```
php artisan test tests/Feature/<Módulo>
```

Por ejemplo, para ejecutar solo los tests de Proveedores:
```
php artisan test tests/Feature/Proveedores
```

Para correr todos los tests de la plataforma:
```
php artisan test
```

## Recomendaciones
- Si agregas un nuevo modelo, crea su factory y su test básico siguiendo el mismo patrón.
- Si cambias la estructura de una tabla (migración), revisa y actualiza la factory correspondiente.
- Los tests están pensados para ser la base de una cobertura más compleja: puedes expandirlos agregando validaciones de negocio, flujos de usuario, etc.
- Si un test falla por un campo faltante, revisa primero la factory y la migración.

## Buenas prácticas
- Mantén los tests y factories sincronizados con la base de datos.
- Usa nombres y mensajes en español para claridad en los informes.
- Ejecuta los tests antes de cada despliegue o cambio importante.

---

**Última actualización:** Julio 2025 