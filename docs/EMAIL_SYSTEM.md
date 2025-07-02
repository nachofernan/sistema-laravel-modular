# Sistema de Emails - Buenos Aires Energía

## Resumen Ejecutivo

Sistema modular de envío de emails automáticos con control de filtros por dominio, rate limiting y gestión de colas. Diseñado para operar en diferentes ambientes (desarrollo, testing, producción) con controles de seguridad para evitar envíos accidentales.

## Arquitectura del Sistema

### Componentes Principales

1. **Job Principal**: `App\Jobs\Emails\EnviarCorreoAutomatizado`
2. **Service de Validación**: `App\Services\Email\EmailDomainValidatorService`
3. **Controller de Gestión**: `App\Http\Controllers\Usuarios\EmailQueueController`
4. **Dispatcher**: `App\Services\EmailDispatcher`
5. **Helper**: `App\Helpers\EmailHelper`

### Flujo de Trabajo

```
[Módulo] → [EmailHelper] → [EmailDispatcher] → [EnviarCorreoAutomatizado Job] → [Validación Dominio] → [SMTP]
```

## Configuración (.env)

### Control General del Sistema

```env
# Control maestro de todos los envíos
MAIL_AUTOMATED_SENDING_ENABLED=true

# Rate limiting
MAIL_SENDING_INTERVAL=3                    # Segundos entre emails
MAIL_RATE_LIMIT_PER_MINUTE=20             # Máximo emails por minuto
```

### Filtros de Dominio (Seguridad)

```env
# Activar/desactivar filtros
MAIL_DOMAIN_FILTER_ENABLED=true

# Comportamiento con emails no permitidos
MAIL_BLOCKED_EMAIL_BEHAVIOR=redirect      # redirect | redirect_all | log | throw

# Email de destino para testing
MAIL_TESTING_REDIRECT_EMAIL=ifernandez@buenosairesenergia.com.ar
```

## Modos de Operación

### 1. Desarrollo/Testing Individual
```env
MAIL_DOMAIN_FILTER_ENABLED=true
MAIL_BLOCKED_EMAIL_BEHAVIOR=redirect_all
```
**Resultado**: TODOS los emails van a `ifernandez@buenosairesenergia.com.ar`

### 2. Testing Interno/Empresa
```env
MAIL_DOMAIN_FILTER_ENABLED=true
MAIL_BLOCKED_EMAIL_BEHAVIOR=redirect
```
**Resultado**: Solo emails `@buenosairesenergia.com.ar` se envían normalmente, el resto se redirige

### 3. Producción
```env
MAIL_DOMAIN_FILTER_ENABLED=false
```
**Resultado**: Se envían emails a cualquier dominio

### 4. Mantenimiento
```env
MAIL_AUTOMATED_SENDING_ENABLED=false
```
**Resultado**: NO se envía ningún email, independientemente de otros filtros

## Configuración en `config/mail.php`

```php
// Configuración de filtros de dominio
'domain_filter_enabled' => env('MAIL_DOMAIN_FILTER_ENABLED', false),

'allowed_domains' => [
    'buenosairesenergia.com.ar'
],

'allowed_emails' => [
    'ifernandez@buenosairesenergia.com.ar'
],

'blocked_email_behavior' => env('MAIL_BLOCKED_EMAIL_BEHAVIOR', 'log'),
'testing_redirect_email' => env('MAIL_TESTING_REDIRECT_EMAIL', 'ifernandez@buenosairesenergia.com.ar'),

// Rate limiting
'automated_sending_enabled' => env('MAIL_AUTOMATED_SENDING_ENABLED', true),
'sending_interval' => env('MAIL_SENDING_INTERVAL', 3),
'rate_limit_per_minute' => env('MAIL_RATE_LIMIT_PER_MINUTE', 20),
```

## Uso en Módulos

### Envío Inmediato
```php
use App\Services\EmailDispatcher;
use App\Mail\MiMailable;

// Envío inmediato
EmailDispatcher::enviarInmediato(
    'destinatario@ejemplo.com',
    new MiMailable($datos),
    'tipo_email',
    'Descripción del envío'
);

// Envío prioritario (cola rápida)
EmailDispatcher::enviarPrioritario(
    'destinatario@ejemplo.com',
    new MiMailable($datos),
    'urgente',
    'Email urgente'
);
```

### Envío Programado
```php
// Programar para fecha específica
$fechaEnvio = Carbon::now()->addHours(2);

EmailDispatcher::programar(
    'destinatario@ejemplo.com',
    new MiMailable($datos),
    'recordatorio',
    'Recordatorio programado',
    $fechaEnvio
);
```

### Envío Masivo
```php
$destinatarios = ['email1@test.com', 'email2@test.com'];

EmailDispatcher::enviarMasivo(
    $destinatarios,
    new MiMailable($datos),
    'newsletter',
    'Newsletter mensual'
);
```

## Comportamientos de Filtrado

### `redirect_all`
- **Qué hace**: Redirige TODOS los emails al email de testing
- **Cuándo usar**: Desarrollo individual, testing de funcionalidades
- **Logs**: Registra el destinatario original y final

### `redirect`
- **Qué hace**: Permite emails a dominios autorizados, redirige el resto
- **Cuándo usar**: Testing interno con equipo de la empresa
- **Logs**: Solo registra emails redirigidos

### `log`
- **Qué hace**: Permite emails autorizados, bloquea y registra el resto
- **Cuándo usar**: Ambiente de staging controlado
- **Logs**: Registra emails bloqueados sin enviar

### `throw`
- **Qué hace**: Lanza excepción cuando encuentra email no autorizado
- **Cuándo usar**: Debugging, desarrollo con validación estricta

## Base de Datos

### Tabla `email_logs`
```sql
CREATE TABLE email_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    destinatario VARCHAR(255) NOT NULL,
    destinatario_original VARCHAR(255) NULL,  -- Para casos de redirección
    tipo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estado ENUM('exitoso', 'fallido', 'bloqueado') NOT NULL,
    error TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX(estado, created_at),
    INDEX(destinatario_original)
);
```

### Estados de Email
- **exitoso**: Email enviado correctamente
- **fallido**: Error en el envío (SMTP, red, etc.)
- **bloqueado**: Bloqueado por filtro de dominio

## Panel de Administración

### Ruta
```
/usuarios/email-queue
```

### Funcionalidades
- **Estadísticas en tiempo real**: Emails pendientes, enviados, fallidos
- **Control maestro**: Activar/desactivar todos los envíos
- **Gestión de cola**: Ver, eliminar, reajustar jobs
- **Logs detallados**: Historial de envíos con filtros
- **Limpieza automática**: Eliminar logs antiguos

### Endpoints AJAX
```php
POST /usuarios/email-queue/toggle-envios     // Activar/desactivar sistema
POST /usuarios/email-queue/reajustar-cola    // Reordenar tiempos de cola
DELETE /usuarios/email-queue/eliminar-job    // Eliminar job específico
DELETE /usuarios/email-queue/limpiar-logs    // Limpiar logs antiguos
GET /usuarios/email-queue/estadisticas       // Estadísticas en tiempo real
```

## Colas de Laravel

### Configuración Requerida
```bash
# Asegurar que las colas estén configuradas
php artisan queue:table
php artisan migrate

# Worker para procesar emails
php artisan queue:work --queue=emails-priority,emails
```

### Colas Utilizadas
- **emails-priority**: Emails urgentes (prioridad alta)
- **emails**: Emails normales y programados

## Rate Limiting

### Funcionamiento
1. **Intervalo entre envíos**: Pausa configurada entre emails individuales
2. **Límite por minuto**: Máximo emails en ventana de 60 segundos
3. **Recuperación automática**: Espera hasta el próximo minuto si alcanza límite

### Configuración por Proveedor
```env
# Gmail/Outlook (conservador)
MAIL_RATE_LIMIT_PER_MINUTE=10
MAIL_SENDING_INTERVAL=5

# SendGrid/Mailgun (más permisivo)  
MAIL_RATE_LIMIT_PER_MINUTE=100
MAIL_SENDING_INTERVAL=1
```

## Troubleshooting

### Jobs que no procesan
```bash
# Verificar worker activo
php artisan queue:work --queue=emails-priority,emails

# Ver jobs fallidos
php artisan queue:failed

# Limpiar cola si es necesario
php artisan queue:flush
```

### Emails no se envían
1. Verificar `MAIL_AUTOMATED_SENDING_ENABLED=true`
2. Verificar configuración SMTP
3. Revisar logs en panel de administración
4. Verificar filtros de dominio

### Jobs corruptos en cola
1. Usar panel de administración para eliminar jobs problemáticos
2. Ejecutar `php artisan queue:flush` si es masivo
3. Los jobs corruptos se marcan visualmente en el panel

## Seguridad

### Medidas Implementadas
- **Filtro de dominios**: Previene envíos accidentales a externos
- **Logging completo**: Auditoría de todos los envíos
- **Control maestro**: Apagar todo el sistema con una variable
- **Rate limiting**: Protección contra spam y límites SMTP

### Recomendaciones
- **Desarrollo**: Usar siempre `MAIL_DOMAIN_FILTER_ENABLED=true`
- **Testing**: Configurar `redirect_all` para pruebas individuales
- **Producción**: Deshabilitar filtros solo cuando esté verificado
- **Monitoreo**: Revisar logs regularmente

## Integración con Módulos

### Módulo de Concursos
```php
// Ejemplo de uso en concursos
EmailHelper::notificarAperturaConcurso($concurso, $proveedores);
EmailHelper::programarEmailsAutomaticosConcurso($concurso, $proveedores);
```

### Módulo de Usuarios
```php
// Ejemplo de notificaciones de usuario
EmailHelper::notificarNuevoUsuario($usuario);
EmailHelper::recordatorioPassword($usuario);
```

## Mantenimiento

### Tareas Regulares
- **Limpiar logs**: Ejecutar limpieza mensual de logs antiguos
- **Monitorear cola**: Verificar que no se acumulen jobs
- **Revisar filtros**: Ajustar configuración según necesidades

### Actualizaciones
- **Nuevos módulos**: Seguir patrón de `EmailHelper::metodo()`
- **Nuevos filtros**: Extender `EmailDomainValidatorService`
- **Nuevos comportamientos**: Agregar casos en job principal

---

## Contacto de Mantenimiento

**Desarrollador**: Ignacio José Fernandez  
**Módulo**: Sistema de Emails  
**Última actualización**: Junio 2025  

**Nota**: Este sistema está diseñado para ser modular y extensible. Cualquier modificación debe respetar la arquitectura existente y mantener la compatibilidad con módulos existentes.