<?php
// /app/models/Usuario.php

require_once __DIR__ . '/../core/Database.php';

class Usuario /* extends BaseModel */ { 
    private $db;
    private $table = 'usuarios';

    public function __construct() {
        // En una implementación real se heredaría de BaseModel, usando su conexión.
        $this->db = Database::getInstance()->getConnection();
    }

    // Método crucial para la autenticación
    public function findByEmail(string $email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email AND activo = 1 LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Ejemplo: Crear un usuario (útil para la instalación o registro)
    public function create(string $nombre, string $email, string $password, string $rol = 'operador') {
        // Nota: Asegúrate de hashear la contraseña antes de guardar.
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (nombre, email, password_hash, rol) VALUES (:nombre, :email, :password_hash, :rol)");
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $passwordHash);
        $stmt->bindParam(':rol', $rol);

        return $stmt->execute();
    }
}