<?php

namespace App\Core;

use \PDO;

class Connection
{
    /**
     * @var PDO $pdo
     */
    private $pdo;

    /**
     * @var array $configs
     */
    private $configs;

    /**
     * Constructor
     *
     * @param array $configs
     */
    public function __construct(array $configs)
    {
        $this->configs = $configs;

        try {
            $this->connect();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Connect to the database
     */
    public function connect()
    {
        if ($this->pdo !== null) {
            return;
        }

        $db = $this->configs['database'] ?? false;
        $hostname = $this->configs['hostname'] ?? false;
        $port = $this->configs['port'] ?? false;
        $charset = $this->configs['charset'] ?? false;
        $username = $this->configs['username'] ?? false;
        $password = $this->configs['password'] ?? false;

        $this->pdo = new PDO(
            "mysql:dbname={$db};host={$hostname};port={$port};charset={$charset}",
            $username,
            $password
        );
    }

    /**
     * Disconnect the database
     */
    public function disconnect()
    {
        if ($this->pdo instanceof PDO) {
            return;
        }

        unset($this->pdo);
    }

    /**
     * Destruct
     */
    public function __destruct()
    {
        try {
            $this->disconnect();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
