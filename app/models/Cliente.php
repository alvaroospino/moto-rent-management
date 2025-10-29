<?php
// /app/models/Cliente.php

require_once __DIR__ . '/BaseModel.php';

class Cliente extends BaseModel {
    public function __construct() {
        // La tabla se llama 'clientes', su ID es 'id_cliente'
        parent::__construct('clientes', 'id_cliente');
    }

    // Método para encontrar un cliente por su identificación (Requisito de la DB)
    public function findByIdentificacion(string $identificacion) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE identificacion = :identificacion LIMIT 1");
        $stmt->bindParam(':identificacion', $identificacion);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Método para obtener una lista de clientes activos para selección en Contratos
    public function getActiveList() {
        $stmt = $this->db->query("SELECT id_cliente, nombre_completo, identificacion FROM {$this->table} ORDER BY nombre_completo ASC");
        return $stmt->fetchAll();
    }
}