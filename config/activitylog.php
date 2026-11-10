<?php

/*
 * Historial de cambios (auditoría) con spatie/laravel-activitylog.
 * La tabla vive en la base general `plataforma`, como media y email_logs: es
 * infraestructura transversal y el sujeto puede ser de cualquier módulo
 * (ver docs/DECISIONES.md).
 */

return [

    'enabled' => env('ACTIVITY_LOGGER_ENABLED', true),

    /*
     * Solo aplica si se corre `activitylog:clean`, que no está agendado:
     * el historial se conserva completo.
     */
    'delete_records_older_than_days' => 365,

    'default_log_name' => 'default',

    'default_auth_driver' => null,

    /*
     * Los usuarios usan SoftDeletes: el historial de un usuario eliminado
     * tiene que poder seguir resolviendo su sujeto.
     */
    'subject_returns_soft_deleted_models' => true,

    'activity_model' => \Spatie\Activitylog\Models\Activity::class,

    'table_name' => 'activity_log',

    /*
     * Conexión fija, no por .env: el aislamiento multi-DB exige que cada
     * tabla tenga su conexión explícita.
     */
    'database_connection' => 'mysql',
];
