# 🔧 Guía de Modo Mantenimiento - Plataforma de Desarrollo

## 📋 Resumen del Sistema

La plataforma cuenta con **dos sistemas de mantenimiento**:

1. **Sistema Personalizado** (recomendado): Permite acceso a usuarios autorizados
2. **Sistema Nativo Laravel**: Bloqueo total del sistema

## 🎯 Sistema Recomendado: Modo Mantenimiento Personalizado

### ✅ Ventajas
- ✨ Acceso para administradores durante el mantenimiento
- 🛡️ Control granular mediante permisos
- 🎨 Vista personalizada para usuarios
- 📱 Mantiene APIs funcionando para usuarios autorizados

### 🔑 Usuarios con Acceso Durante Mantenimiento
Solo usuarios con el permiso `Usuarios/Modulos/Editar` pueden acceder al sistema.

---

## 🚀 Procedimiento de Activación

### **PASO 1: Verificar Usuarios Autorizados**

Antes de activar el mantenimiento, verifica qué usuarios tienen acceso:

```sql
-- Conectarse a la base de datos 'usuarios'
SELECT u.name, u.email, u.realname 
FROM users u
JOIN model_has_permissions mhp ON u.id = mhp.model_id
JOIN permissions p ON mhp.permission_id = p.id
WHERE p.name = 'Usuarios/Modulos/Editar'
AND u.deleted_at IS NULL;
```

### **PASO 2: Activar Modo Mantenimiento**

#### **Opción A: Modificar archivo .env (Recomendado)**
```bash
# Editar el archivo .env
SYSTEM_MAINTENANCE=true
```

#### **Opción B: Configurar variable de entorno del servidor**
```bash
# En el servidor web
export SYSTEM_MAINTENANCE=true
```

### **PASO 3: Verificar Activación**

1. **Abrir el sitio en una ventana de incógnito**
2. **Verificar que muestra**: "Servicio en mantenimiento"
3. **Iniciar sesión con usuario autorizado**
4. **Confirmar acceso completo al sistema**

---

## ⚙️ Durante el Mantenimiento

### **✅ Lo que SÍ funciona:**
- ✨ Acceso completo para usuarios con permiso `Usuarios/Modulos/Editar`
- 📊 Todas las funcionalidades del sistema
- 🔗 APIs para usuarios autorizados
- 📧 Envío de emails
- 🎯 Tareas programadas (cron jobs)

### **❌ Lo que NO funciona:**
- 🚫 Acceso para usuarios sin permisos
- 🚫 Registro de nuevos usuarios
- 🚫 APIs públicas

### **🛠️ Tareas Típicas Durante Mantenimiento:**
- 📊 Actualizaciones de base de datos
- 🔄 Despliegue de nuevo código
- 🧹 Limpieza de archivos temporales
- 🔧 Configuración del servidor
- 📈 Optimización de rendimiento

---

## 🔄 Procedimiento de Desactivación

### **PASO 1: Completar todas las tareas de mantenimiento**

### **PASO 2: Desactivar el modo mantenimiento**

#### **Opción A: Modificar archivo .env**
```bash
# Cambiar en el archivo .env
SYSTEM_MAINTENANCE=false
# O comentar la línea
# SYSTEM_MAINTENANCE=true
```

#### **Opción B: Eliminar variable de entorno**
```bash
# En el servidor
unset SYSTEM_MAINTENANCE
```

### **PASO 3: Verificar Desactivación**

1. **Abrir el sitio en ventana de incógnito**
2. **Verificar acceso normal al login**
3. **Probar login con usuario estándar**
4. **Confirmar funcionamiento completo**

---

## 🆘 Sistema de Respaldo: Modo Mantenimiento Laravel

### **Cuándo usar:**
- 🚨 Emergencias críticas
- 🔧 Mantenimiento de infraestructura
- 📊 Migraciones complejas de BD

### **Activación:**
```bash
# Activar (bloqueo total)
php artisan down --secret="token-secreto-aqui"

# Activar con mensaje personalizado
php artisan down --message="Actualización del sistema en progreso"

# Activar con tiempo estimado
php artisan down --retry=3600  # Reintentar en 1 hora
```

### **Acceso de emergencia:**
```
https://tudominio.com/token-secreto-aqui
```

### **Desactivación:**
```bash
php artisan up
```

---

## 🔍 Monitoreo y Verificación

### **Archivos Clave a Monitorear:**
- `storage/framework/maintenance.php` (Laravel nativo)
- `storage/logs/laravel.log` (errores durante mantenimiento)

### **Comandos de Verificación:**
```bash
# Verificar estado Laravel
php artisan down --help

# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Verificar permisos de usuarios
php artisan tinker
>>> User::permission('Usuarios/Modulos/Editar')->get(['name', 'email']);
```

---

## ⚠️ Consideraciones Importantes

### **🛡️ Seguridad:**
- ✅ Siempre verificar usuarios autorizados antes de activar
- ✅ Usar conexiones seguras (HTTPS) durante mantenimiento
- ✅ Monitorear logs para intentos de acceso

### **⏰ Timing:**
- 🌙 Activar en horarios de menor tráfico
- 📅 Notificar a usuarios con anticipación
- ⏱️ Establecer ventanas de tiempo específicas

### **📢 Comunicación:**
- 📧 Email a usuarios sobre la ventana de mantenimiento
- 📱 Mensajes en redes sociales si aplica
- 🎯 Vista de mantenimiento con información clara

---

## 🐛 Troubleshooting

### **❌ "No puedo acceder con usuario autorizado"**
1. Verificar que `SYSTEM_MAINTENANCE=true` en .env
2. Confirmar permisos del usuario en BD
3. Limpiar caché: `php artisan config:cache`
4. Verificar sesiones del usuario

### **❌ "El modo no se activa"**
1. Verificar sintaxis en .env (sin espacios)
2. Confirmar que el middleware está registrado
3. Revisar `bootstrap/app.php` línea 28

### **❌ "Vista de mantenimiento no se muestra"**
1. Verificar archivo `resources/views/errors/mantenimiento.blade.php`
2. Comprobar permisos de archivos
3. Limpiar caché de vistas: `php artisan view:clear`

---

## 📞 Contactos de Emergencia

**En caso de problemas durante el mantenimiento:**
- 🔧 Administrador del Sistema: [Tu contacto]
- 💻 Soporte Técnico: [Tu contacto]
- 🆘 Emergencias: [Tu contacto]

---

## 📝 Checklist de Mantenimiento

### **✅ Pre-Mantenimiento:**
- [ ] Verificar usuarios autorizados
- [ ] Notificar a usuarios finales
- [ ] Respaldar base de datos
- [ ] Verificar espacio en disco
- [ ] Preparar plan de rollback

### **✅ Durante Mantenimiento:**
- [ ] Activar modo mantenimiento
- [ ] Verificar acceso administrativo
- [ ] Ejecutar tareas programadas
- [ ] Monitorear logs de errores
- [ ] Documentar cambios realizados

### **✅ Post-Mantenimiento:**
- [ ] Desactivar modo mantenimiento
- [ ] Verificar funcionamiento completo
- [ ] Probar funcionalidades críticas
- [ ] Notificar finalización a usuarios
- [ ] Documentar lecciones aprendidas

---

*Documento creado el: [Fecha actual]*  
*Última actualización: [Fecha actual]*  
*Versión: 1.0* 