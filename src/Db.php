<?php
namespace Admin;

class Db
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    private $connection;
    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection()
    {
        if ($this->connection) {
            return $this->connection;
        }
        $config = new \Doctrine\DBAL\Configuration();
        preg_match('\/\/(?P<user>[^:]*):(?P<pass>[^@]*)@(?P<host>[^:]*):(?P<port>[^\/]*)\/(?P<name>.*)', getenv('DATABASE_URL'), $matches);
        $this->connection = \Doctrine\DBAL\DriverManager::getConnection([
            'dbname' => $matches['name'],
            'user' => $matches['user'],
            'password' => $matches['password'],
            'host' => $matches['host'],
            'driver' => 'pdo_pgsql',
        ], $config);
        return $this->connection;
    }
}