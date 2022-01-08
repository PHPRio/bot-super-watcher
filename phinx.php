<?php
preg_match('/\/\/(?P<user>[^:]*):(?P<pass>[^@]*)@(?P<host>[^:]*):(?P<port>[^\/]*)\/(?P<name>.*)/', getenv('DATABASE_URL'), $matches);

return [
    'paths' => [
        'migrations' => './db/migrations'
    ],
    'environments' =>
        [
            'default_database' => 'development',
            'development' => [
                'adapter' => 'pgsql',
                'host' => $matches['host'],
                'name' => $matches['name'],
                'user' => $matches['user'],
                'pass' => $matches['pass'],
                'port' => $matches['port']
            ]
        ]
];