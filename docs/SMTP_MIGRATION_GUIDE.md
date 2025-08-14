# 🚀 Guía de Migración: Microsoft Graph → SMTP Office 365

## 📋 Resumen

Esta guía te ayudará a migrar tu sistema de emails de Microsoft Graph a SMTP de Office 365 de forma segura y sin interrupciones.

## 🔧 Configuración Requerida

### Variables .env para Office 365 SMTP

```env
# Configuración SMTP Office 365
MAIL_MAILER=smtp
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=tu-email@buenosairesenergia.com.ar
MAIL_PASSWORD=tu-app-password-o-contraseña
MAIL_FROM_ADDRESS=tu-email@buenosairesenergia.com.ar
MAIL_FROM_NAME="Buenos Aires Energía"

# Configuración adicional (opcional)
MAIL_EHLO_DOMAIN=buenosairesenergia.com.ar
```

### Configuración de Contraseña

**Opción 1: Contraseña Normal (Recomendado para tu caso)**
```env
MAIL_PASSWORD=tu-contraseña-normal
```

**Opción 2: App Password (Solo si tienes 2FA habilitado)**
1. Ve a [Microsoft Account Security](https://account.microsoft.com/security)
2. Activa la **autenticación de 2 factores**
3. Genera una **App Password** específica para Laravel
4. Usa esa App Password en `MAIL_PASSWORD`

## 🧪 Comandos de Testing

### 1. Verificar Configuración

```bash
php artisan email:check-config
```

**Qué hace:**
- Muestra toda la configuración actual
- Verifica variables de entorno
- Compara configuraciones SMTP vs Microsoft Graph

### 2. Probar Envío de Email

```bash
# Envío básico
php artisan email:test-smtp tu-email@ejemplo.com

# Con opciones personalizadas
php artisan email:test-smtp tu-email@ejemplo.com --subject="Test Personalizado" --from="otro-email@ejemplo.com"
```

**Qué hace:**
- Envía un email de prueba con diseño profesional
- Muestra información detallada del envío
- Proporciona debugging en caso de errores

## 🔄 Proceso de Migración

### Fase 1: Preparación
1. **Configurar variables .env** con credenciales SMTP
2. **Ejecutar verificación**: `php artisan email:check-config`
3. **Probar conexión**: `php artisan email:test-smtp tu-email@ejemplo.com`

### Fase 2: Testing
1. **Cambiar temporalmente** `MAIL_MAILER=smtp` en .env
2. **Probar módulos** uno por uno:
   - Tickets
   - Concursos
   - Capacitaciones
   - Proveedores
3. **Verificar logs** en `storage/logs/laravel.log`

### Fase 3: Migración
1. **Confirmar** que todo funciona correctamente
2. **Mantener** `MAIL_MAILER=smtp` permanentemente
3. **Monitorear** el primer día de producción

## 📊 Monitoreo y Debugging

### Logs Importantes

```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Buscar errores de email
grep -i "mail\|smtp\|email" storage/logs/laravel.log
```

### Comandos Útiles

```bash
# Limpiar cache de configuración
php artisan config:clear

# Ver configuración actual
php artisan config:show mail

# Probar conexión SMTP (sin enviar)
php artisan email:check-config
```

## ⚠️ Troubleshooting Común

### Error: "Authentication failed"

**Causas:**
- Contraseña incorrecta
- Usuario bloqueado
- 2FA habilitado (en ese caso necesitarías App Password)

**Solución:**
```env
# Verificar que la contraseña sea correcta
MAIL_PASSWORD=tu-contraseña-normal

# Si tienes 2FA habilitado, usar App Password
MAIL_PASSWORD=tu-app-password-de-16-caracteres
```

### Error: "Connection timeout"

**Causas:**
- Firewall bloqueando puerto 587
- Host incorrecto
- Problemas de red

**Solución:**
```env
# Verificar configuración
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
```

### Error: "Rate limit exceeded"

**Causas:**
- Demasiados emails por minuto
- Límite diario alcanzado

**Solución:**
- Tu sistema ya maneja rate limiting
- Verificar configuración en `config/mail.php`

## 🔒 Seguridad

### Mejores Prácticas

1. **Usar contraseña normal** (como prefieres) o App Password si tienes 2FA
2. **Limitar permisos** de la cuenta de email
3. **Monitorear logs** regularmente
4. **Usar HTTPS** en producción
5. **Validar destinatarios** (ya implementado)

### Configuración de Seguridad

```env
# Habilitar filtros de dominio en desarrollo
MAIL_DOMAIN_FILTER_ENABLED=true
MAIL_BLOCKED_EMAIL_BEHAVIOR=redirect

# Deshabilitar en producción
MAIL_DOMAIN_FILTER_ENABLED=false
```

## 📈 Ventajas del Nuevo Sistema

### ✅ Beneficios

- **Más simple**: No requiere configuración de Azure AD
- **Más confiable**: SMTP es estándar y estable
- **Mejor debugging**: Logs más claros
- **Menos dependencias**: No necesita librerías especiales
- **Mismo sistema**: Tu arquitectura actual se mantiene

### 🔄 Compatibilidad

- **Rate limiting**: ✅ Funciona igual
- **Filtros de dominio**: ✅ Funciona igual
- **Colas prioritarias**: ✅ Funciona igual
- **Jobs programados**: ✅ Funciona igual
- **Tracking de emails**: ✅ Funciona igual

## 🎯 Próximos Pasos

1. **Configurar** variables .env con SMTP
2. **Probar** con los comandos de testing
3. **Validar** todos los módulos
4. **Migrar** cambiando `MAIL_MAILER`
5. **Monitorear** logs y funcionamiento

## 📞 Soporte

Si encuentras problemas:

1. **Revisar logs**: `storage/logs/laravel.log`
2. **Ejecutar tests**: `php artisan email:test-smtp`
3. **Verificar configuración**: `php artisan email:check-config`
4. **Consultar documentación**: `docs/EMAIL_SYSTEM.md`

---

**¡La migración es simple y segura! Tu sistema actual funciona exactamente igual, solo cambia el transport de emails.** 