<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Destinatarios de notificaciones fijas
    |--------------------------------------------------------------------------
    | Direcciones que reciben avisos automáticos del sistema, separadas por módulo.
    | Se configuran por .env (lista separada por comas) para no versionar emails reales.
    */

    'tickets' => [
        'nuevo' => array_filter(explode(',', env('NOTIF_TICKETS_NUEVO', ''))),
    ],

    'proveedores' => [
        'vencimiento_documentos' => array_filter(explode(',', env('NOTIF_PROVEEDORES_VENCIMIENTO', ''))),
        'nuevo_archivo_validacion' => array_filter(explode(',', env('NOTIF_PROVEEDORES_VALIDACION', ''))),
    ],

];
