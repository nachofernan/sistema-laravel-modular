# Plataforma de Desarrollo - Documentación General

## Resumen General

La plataforma es un sistema modular desarrollado en Laravel, orientado a la gestión administrativa y operativa de Buenos Aires Energía S.A. (BAESA). Su arquitectura permite la integración de múltiples módulos funcionales, cada uno con su propia lógica, modelos y controladores, pero compartiendo autenticación, permisos y una estructura común.

---

## Estructura General del Proyecto

- **app/**: Código principal de la aplicación (controladores, modelos, servicios, jobs, helpers, etc.).
- **routes/**: Definición de rutas web y API, separadas por módulo.
- **resources/views/**: Vistas Blade organizadas por módulo y funcionalidad.
- **database/**: Migraciones, seeders y factories.
- **public/**: Archivos públicos (imágenes, assets, entrypoints).
- **config/**: Archivos de configuración de Laravel y módulos.
- **docs/**: Documentación interna del sistema.

---

## Módulos Principales

Cada módulo representa un área funcional independiente, con sus propios controladores, modelos, rutas y vistas. Los módulos activos principales son:

### 1. Usuarios
- **Funcionalidad:** Gestión de usuarios, roles, permisos, áreas, sedes y módulos habilitados. Incluye administración de seguridad de contraseñas y panel de gestión de colas de emails.
- **Rutas:** `routes/usuarios.php`

### 2. Tickets
- **Funcionalidad:** Gestión de tickets de soporte, mensajes asociados y seguimiento de estados. Permite adjuntar documentos a los tickets.
- **Rutas:** `routes/tickets.php`

### 3. Inventario
- **Funcionalidad:** Administración de elementos de inventario, categorías, usuarios asignados y periféricos. Permite registrar entregas, devoluciones y modificaciones.
- **Rutas:** `routes/inventario.php`

### 4. Documentos
- **Funcionalidad:** Gestión de documentos institucionales, categorización, descarga y control de versiones. Incluye registro de descargas y permisos por usuario/sede.
- **Rutas:** `routes/documentos.php`

### 5. Proveedores
- **Funcionalidad:** Administración de proveedores, contactos, apoderados, documentos, validaciones y rubros. Permite exportar información y generar reportes en PDF.
- **Rutas:** `routes/proveedores.php`

### 6. Capacitaciones
- **Funcionalidad:** Gestión de capacitaciones, encuestas, preguntas, opciones y documentos asociados. Permite registrar la participación y resultados de los usuarios.
- **Rutas:** `routes/capacitaciones.php`

### 7. Concursos
- **Funcionalidad:** Administración de concursos públicos, documentos, tipos de documentos, prórrogas y calendario de eventos. Permite la descarga de documentación y generación de resúmenes en PDF.
- **Rutas:** `routes/concursos.php`

### 8. Mesa de Entradas
- **Funcionalidad:** Registro y gestión de entradas administrativas, seguimiento y control de documentación recibida.
- **Rutas:** `routes/mesadeentradas.php`

### 9. AdminIP
- **Funcionalidad:** Gestión de direcciones IP y categorías asociadas para administración de red interna.
- **Rutas:** `routes/adminip.php`

---

## Arquitectura y Seguridad

- **Autenticación:** Basada en Laravel Fortify y Jetstream, con soporte para roles y permisos (Spatie Laravel Permission).
- **Permisos:** Cada módulo y funcionalidad está protegido por middleware de permisos y roles.
- **APIs:** Existen endpoints RESTful para integración con portales externos (por ejemplo, Portal de Proveedores).
- **Colas y Jobs:** Uso de colas para procesamiento de emails y tareas asíncronas.
- **Encriptación de Archivos:** Algunos módulos (Concursos, Proveedores) soportan almacenamiento y descarga de archivos encriptados.

---

## Integración y Extensibilidad

- El sistema permite agregar nuevos módulos fácilmente, siguiendo la convención de rutas, controladores y modelos.
- Los módulos pueden compartir usuarios, roles y permisos, facilitando la administración centralizada.

---

## Recomendaciones de Uso y Mantenimiento

- Mantener actualizada la documentación de cada módulo en la carpeta `docs/`.
- Revisar periódicamente los permisos y roles asignados.
- Monitorear las colas de jobs y el sistema de emails.
- Realizar backups regulares de la base de datos y archivos adjuntos.

---

## Contacto y Soporte

Para soporte técnico o consultas sobre la plataforma, contactar al área de Sistemas de BAESA.

---

**Última actualización:** Junio 2024 