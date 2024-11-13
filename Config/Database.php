<?php
namespace Config;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    private $host = 'localhost';
    private $dbname = 'harmoniikah';
    private $username = 'root';
    private $password = '';

    private function __construct()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit;
        }
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }
}
