<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send all email
    | messages unless another mailer is explicitly specified when sending
    | the message. All additional mailers can be configured within the
    | "mailers" array. Examples of each type of mailer are provided.
    |
    */

    'default' =>  env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Laravel supports a variety of mail "transport" drivers that can be used
    | when delivering an email. You may specify which one you're using for
    | your mailers below. You may also add additional mailers if needed.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses", "ses-v2",
    |            "postmark", "resend", "log", "array",
    |            "failover", "roundrobin"
    |
    */

    'mailers' => [

        'microsoft-graph' => [
            'transport' => 'microsoft-graph',
            'client_id' => env('MAIL_MICROSOFT_GRAPH_CLIENT_ID'),
            'client_secret' => env('MAIL_MICROSOFT_GRAPH_CLIENT_SECRET'),
            'tenant_id' => env('MAIL_MICROSOFT_GRAPH_TENANT_ID'),
            'from' => [
                'address' => env('MAIL_MICROSOFT_GRAPH_FROM'),
                'name' => 'No responder',
            ],
            'save_to_sent_items' =>  env('MAIL_SAVE_TO_SENT_ITEMS', false),
        ],


        'smtp' => [
            'transport' => 'smtp',
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
            // 'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        'resend' => [
            'transport' => 'resend',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
        ],

        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all emails sent by your application to be sent from
    | the same address. Here you may specify a name and address that is
    | used globally for all emails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Envío Automatizado
    |--------------------------------------------------------------------------
    */
    'automated_sending_enabled' => env('MAIL_AUTOMATED_SENDING_ENABLED', true),
    
    /*
    |--------------------------------------------------------------------------
    | Intervalo entre Envíos (segundos)
    |--------------------------------------------------------------------------
    */
    'sending_interval' => env('MAIL_SENDING_INTERVAL', 3),
    
    /*
    |--------------------------------------------------------------------------
    | Límite de Correos por Minuto
    |--------------------------------------------------------------------------
    */
    'rate_limit_per_minute' => env('MAIL_RATE_LIMIT_PER_MINUTE', 20),
    
    /*
    |--------------------------------------------------------------------------
    | Configuración de Colas
    |--------------------------------------------------------------------------
    */
    'queue_configs' => [
        'default' => 'emails',
        'priority' => 'emails-priority',
        'retry_attempts' => 3,
        'retry_delay' => 60, // segundos
    ],

     /*
    |--------------------------------------------------------------------------
    | Configuración de Envío Automatizado
    |--------------------------------------------------------------------------
    */
    'automated_sending_enabled' => env('MAIL_AUTOMATED_SENDING_ENABLED', false),
    
    /*
    |--------------------------------------------------------------------------
    | Filtro de Dominios para Testing/Desarrollo
    |--------------------------------------------------------------------------
    */
    'domain_filter_enabled' => env('MAIL_DOMAIN_FILTER_ENABLED', false),
    
    'allowed_domains' => [
        // Dejarlo vacío para que NO permita ningún dominio automáticamente
        'buenosairesenergia.com.ar'
    ],
    
    'allowed_emails' => [
        'ifernandez@buenosairesenergia.com.ar' // Solo este email específico
    ],
    
    // Comportamiento cuando se bloquea un email
    'blocked_email_behavior' => env('MAIL_BLOCKED_EMAIL_BEHAVIOR', 'log'), // 'log', 'redirect', 'throw'
    
    // Email de redirección para testing
    'testing_redirect_email' => env('MAIL_TESTING_REDIRECT_EMAIL', 'ifernandez@buenosairesenergia.com.ar'),


];
