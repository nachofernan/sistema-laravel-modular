# Plataforma BAESA — Reglas del proyecto

## Qué es esto

Sistema interno de gestión administrativa y operativa de **Buenos Aires Energía S.A. (BAESA)**. Arrancó hace ~4 años como plataforma de usuarios y documentos, y fue creciendo orgánicamente con nuevos módulos a pedido de la empresa: inventario, proveedores, concursos de precios, etc. La base de código refleja esa evolución: hay partes bien estructuradas y partes más artesanales. El objetivo actual no es reescribir, sino mantener la plataforma funcionando y refactorizar progresivamente a medida que se agregan features nuevas.

Esto **no** es un producto SaaS ni una app pública. Es una herramienta interna para el personal de BAESA. El criterio de calidad es: funciona correctamente, es mantenible por una persona sola con ayuda de IA, y no rompe lo que ya anda.

Documentación de módulos y arquitectura: `docs/ARQUITECTURA.md` y `docs/modulos/`.

---

## Stack y decisiones tomadas

- **Backend**: Laravel 11, PHP 8.2
- **Frontend**: Livewire 3 + Blade + Alpine.js + TailwindCSS, compilado con Vite
- **Auth**: Laravel Jetstream + Fortify. Roles y permisos con Spatie Laravel Permission. 2FA disponible.
- **Múltiples bases de datos**: cada módulo tiene su propia base de datos MySQL. La conexión se configura en cada modelo (`protected $connection`). Las conexiones disponibles están en `.env` con el patrón `DB_DATABASE_<MODULO>`. Ver `config/database.php`.
- **Permisos por módulo**: los roles siguen el patrón `Modulo/Rol` (ej: `Proveedores/Acceso`, `Concursos/Admin`). El módulo `Usuarios` centraliza la administración.
- **Módulos dinámicos**: los módulos activos se cargan desde la tabla `modulos` en la base `usuarios`. Si un módulo está `inactivo`, sus rutas no se cargan.
- **Archivos**: Spatie MediaLibrary para gestión de archivos adjuntos. Discos separados por módulo en `config/filesystems.php`. Algunos documentos (Concursos) soportan encriptación.
- **PDF**: barryvdh/laravel-dompdf para generación de reportes.
- **Excel**: maatwebsite/excel + PhpSpreadsheet para exports.
- **API externa**: endpoints JWT (firebase/php-jwt) para el Portal de Proveedores externo. Ver `routes/api.php`.
- **Email**: innoge/laravel-msgraph-mail (Microsoft Graph). Jobs en cola para envíos masivos. Sistema de logs de email en tabla `email_logs`.
- **Sin SPA**: no hay Vue/React. Todo server-rendered via Blade/Livewire. El JS custom es Alpine.js puntual.

---

## Cómo trabajamos

- Antes de tocar archivos, se explica qué se va a crear/modificar y por qué. Se espera confirmación antes de avanzar con un paso de alcance nuevo.
- Cada paso es un cambio lógico chico, no varios cambios de golpe sin avisar.
- Si un cambio obliga a tocar otra capa del sistema (modelo, migración, vista, ruta), se marca explícitamente como efecto en cascada antes de hacerlo.
- Si en el camino aparece algo necesario que no estaba pedido (un bug real, un fix que hace falta para que lo pedido funcione), se hace y se explica después — no se pide permiso para cada hallazgo chico, pero tampoco se cuela sin decir nada.
- Control de versiones semi-constante: al cerrar una etapa con sentido propio se commitea.
- Cuando hay ambigüedad real sobre qué hacer (no derivable del código ni del pedido), se pregunta. Cuando la respuesta es derivable, se deriva y se sigue.

## Principios de código

- YAGNI explícito: sin capas de abstracción, patrones o infraestructura hasta que una funcionalidad concreta los necesite de verdad.
- Convenciones estándar de Laravel/Livewire. Sin inventar estructura propia.
- Sin código defensivo para casos que no pueden pasar. Validación en los bordes del sistema.
- Tres líneas repetidas son mejores que una abstracción prematura.

## Testing

- No hay suite de tests activa. Los módulos nuevos o refactorizados deberían tener al menos un test feature básico (Pest). Si no es posible en el momento, se dice explícitamente.
- El framework disponible es Pest (con plugin Laravel).

## Documentación

- Comentarios inline solo cuando el *por qué* no es obvio.
- Funciones/métodos nuevos o modificados sustancialmente llevan docblock si su propósito no es evidente por el nombre.
- Documentación de módulos en `docs/modulos/`. Actualizar cuando cambia algo estructural.

---

## Próximos pasos

Ver `docs/ARQUITECTURA.md` para el estado actual del sistema y `docs/HOJA_DE_RUTA.md` para el plan de refactorización por etapas.
