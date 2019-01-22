<?php
return [
    'paths' => [
        'migrations' => './db/migrations'
    ],
    'environments' =>
        [
            'default_database' => 'development',
            'development' => [
                'adapter' => 'pgsql',
                'host' => getenv('DB_HOST'),
                'name' => getenv('DB_NAME'),
                'user' => getenv('DB_USER'),
                'pass' => getenv('DB_PASS'),
                'port' => getenv('DB_PORT')
            ]
        ]
];