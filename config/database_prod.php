<?php

return array_merge(require __DIR__.'/database_default.php', [
    'host' => env('DB_HOST_PROD'),
    'username' => env('DB_USERNAME_PROD'),
    'password' => env('DB_PASSWORD_PROD'),
]);