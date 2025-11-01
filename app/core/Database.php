<?php
// /app/core/Database.php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $config = require __DIR__ . '/../../config/database.php';

        // Detectar si usar PostgreSQL o MySQL basado en variables de entorno
        $dbType = getenv('DB_TYPE') ?: 'mysql'; // Por defecto MySQL para local

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            if ($dbType === 'postgresql' || $dbType === 'pgsql') {
                // PostgreSQL (Render)
                $host = getenv('DB_HOST') ?: $config['host'];
                $port = getenv('DB_PORT') ?: 5432;
                $dbname = getenv('DB_NAME') ?: $config['db_name'];
                $user = getenv('DB_USERNAME') ?: $config['username'];
                $pass = getenv('DB_PASSWORD') ?: $config['password'];
                $sslmode = getenv('DB_SSLMODE') ?: 'require';

                $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode={$sslmode}";
                $this->conn = new PDO($dsn, $user, $pass, $options);
            } else {
                // MySQL (local)
                $dsn = "mysql:host={$config['host']};dbname={$config['db_name']};charset={$config['charset']}";
                $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
            }
        } catch (\PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    // Método estático para obtener la única instancia (Singleton)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Método para obtener la conexión PDO
    public function getConnection() {
        return $this->conn;
    }
}
