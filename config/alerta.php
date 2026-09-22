<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Banner de alerta global
    |--------------------------------------------------------------------------
    |
    | Franja de aviso que se muestra arriba de todo el sistema (incluido el
    | login) mientras esté activa. Pensada para avisos puntuales como
    | mantenimientos programados. No reemplaza al modo mantenimiento en sí
    | (SYSTEM_MAINTENANCE): solo es un mensaje visible, el sistema sigue
    | funcionando con normalidad.
    |
    */

    'activo' => (bool) env('ALERTA_GLOBAL_ACTIVA', false),

    'mensaje' => env('ALERTA_GLOBAL_MENSAJE'),

];
