<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for database operations. This is
    | the connection which will be utilized unless another connection
    | is explicitly specified when you execute a query / statement.
    |
    */

    'default' => env('DB_CONNECTION', 'sqlite'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Below are all of the database connections defined for your application.
    | An example configuration is provided for each database system which
    | is supported by Laravel. You're free to add / remove connections.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'usuarios' => array_merge(require __DIR__.'/database_default.php', [
            'database' => env('DB_DATABASE_USUARIOS', 'forge'),
            'username' => env('DB_USERNAME_USUARIOS', 'forge'),
            'password' => env('DB_PASSWORD_USUARIOS', ''),
        ]),

        'usuarios_prod' => array_merge(require __DIR__.'/database_prod.php', [
            'database' => 'usuarios',
        ]),

        'tickets' => array_merge(require __DIR__.'/database_default.php', [
            'database' => env('DB_DATABASE_TICKETS', 'forge'),
            'username' => env('DB_USERNAME_TICKETS', 'forge'),
            'password' => env('DB_PASSWORD_TICKETS', ''),
        ]),

        'tickets_prod' => array_merge(require __DIR__.'/database_prod.php', [
            'database' => 'tickets',
        ]),

        'inventario' => array_merge(require __DIR__.'/database_default.php', [
            'database' => env('DB_DATABASE_INVENTARIO', 'forge'),
            'username' => env('DB_USERNAME_INVENTARIO', 'forge'),
            'password' => env('DB_PASSWORD_INVENTARIO', ''),
        ]),

        'inventario_prod' => array_merge(require __DIR__.'/database_prod.php', [
            'database' => 'inventario',
        ]),

        'documentos' => array_merge(require __DIR__.'/database_default.php', [
            'database' => env('DB_DATABASE_DOCUMENTOS', 'forge'),
            'username' => env('DB_USERNAME_DOCUMENTOS', 'forge'),
            'password' => env('DB_PASSWORD_DOCUMENTOS', ''),
        ]),

        'documentos_prod' => array_merge(require __DIR__.'/database_prod.php', [
            'database' => 'documentos',
        ]),

        'adminip' => array_merge(require __DIR__.'/database_default.php', [
            'database' => env('DB_DATABASE_ADMINIP', 'forge'),
            'username' => env('DB_USERNAME_ADMINIP', 'forge'),
            'password' => env('DB_PASSWORD_ADMINIP', ''),
        ]),

        'adminip_prod' => array_merge(require __DIR__.'/database_prod.php', [
            'database' => 'adminip',
        ]),

        'capacitaciones' => array_merge(require __DIR__.'/database_default.php', [
            'database' => env('DB_DATABASE_CAPACITACIONES', 'forge'),
            'username' => env('DB_USERNAME_CAPACITACIONES', 'forge'),
            'password' => env('DB_PASSWORD_CAPACITACIONES', ''),
        ]),

        'capacitaciones_prod' => array_merge(require __DIR__.'/database_prod.php', [
            'database' => 'capacitaciones',
        ]),

        'proveedores' => array_merge(require __DIR__.'/database_default.php', [
            'database' => env('DB_DATABASE_PROVEEDORES', 'forge'),
            'username' => env('DB_USERNAME_PROVEEDORES', 'forge'),
            'password' => env('DB_PASSWORD_PROVEEDORES', ''),
        ]),

        'proveedores_prod' => array_merge(require __DIR__.'/database_prod.php', [
            'database' => 'proveedores2',
        ]),

        'proveedores_externos' => array_merge(require __DIR__.'/database_default.php', [
            'database' => env('DB_DATABASE_PROVEEDORES_EXTERNOS', 'forge'),
            'username' => env('DB_USERNAME_PROVEEDORES_EXTERNOS', 'forge'),
            'password' => env('DB_PASSWORD_PROVEEDORES_EXTERNOS', ''),
        ]),

        'proveedores_externos_prod' => array_merge(require __DIR__.'/database_prod.php', [
            'database' => 'proveedores_externos',
        ]),

        'concursos' => array_merge(require __DIR__.'/database_default.php', [
            'database' => env('DB_DATABASE_CONCURSOS', 'forge'),
            'username' => env('DB_USERNAME_CONCURSOS', 'forge'),
            'password' => env('DB_PASSWORD_CONCURSOS', ''),
        ]),

        'concursos_prod' => array_merge(require __DIR__.'/database_prod.php', [
            'database' => 'concursos',
        ]),

        'automotores' => array_merge(require __DIR__.'/database_default.php', [
            'database' => env('DB_DATABASE_AUTOMOTORES', 'forge'),
            'username' => env('DB_USERNAME_AUTOMOTORES', 'forge'),
            'password' => env('DB_PASSWORD_AUTOMOTORES', ''),
        ]),

        'automotores_prod' => array_merge(require __DIR__.'/database_prod.php', [
            'database' => 'automotores',
        ]),

        'mesadeentradas' => array_merge(require __DIR__.'/database_default.php', [
            'database' => env('DB_DATABASE_MESADEENTRADAS', 'forge'),
            'username' => env('DB_USERNAME_MESADEENTRADAS', 'forge'),
            'password' => env('DB_PASSWORD_MESADEENTRADAS', ''),
        ]),

        'mesadeentradas_prod' => array_merge(require __DIR__.'/database_prod.php', [
            'database' => 'mesadeentradas',
        ]),

        'fichadas' => array_merge(require __DIR__.'/database_default.php', [
            'database' => env('DB_DATABASE_FICHADAS', 'forge'),
            'username' => env('DB_USERNAME_FICHADAS', 'forge'),
            'password' => env('DB_PASSWORD_FICHADAS', ''),
        ]),

        /* 'fichadas' => array_merge(require __DIR__.'/database_prod.php', [
            'database' => 'fichadas',
        ]), */

        /* 'fichadas' => [
            'driver' => 'mysql',
            'url' => env('DB_URL'),
            'host' => '172.17.8.63',
            'port' => '3306',
            'database' =>'fichadas',
            'username' => 'fichadas',
            'password' => 'fichadas123',
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ], */

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'mariadb' => [
            'driver' => 'mariadb',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run on the database.
    |
    */

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as Memcached. You may define your connection settings here.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
