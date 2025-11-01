<?php
// /app/models/Usuario.php

require_once __DIR__ . '/../core/Database.php';

class Usuario {
    private $db;
    private $table = 'usuarios';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Método para PostgreSQL
    public function findByEmail(string $email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email AND activo = :activo LIMIT 1";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        // PostgreSQL usa booleanos
        $stmt->bindValue(':activo', true, PDO::PARAM_BOOL);

        $stmt->execute();
        return $stmt->fetch();
    }

    // 🧩 Crear nuevo usuario
    public function create(string $nombre, string $email, string $password, string $rol = 'operador') {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (nombre, email, password_hash, rol)
            VALUES (:nombre, :email, :password_hash, :rol)
        ");

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $passwordHash);
        $stmt->bindParam(':rol', $rol);

        return $stmt->execute();
    }
}
