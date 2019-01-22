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
        $this->connection = \Doctrine\DBAL\DriverManager::getConnection([
            'dbname' => getenv('DB_NAME'),
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PASS'),
            'host' => getenv('DB_HOST'),
            'driver' => 'pdo_pgsql',
        ], $config);
        return $this->connection;
    }
}