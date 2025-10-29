<?php
// /app/models/Gasto.php

require_once __DIR__ . '/BaseModel.php';

class Gasto extends BaseModel {
    public function __construct() {
        // La tabla se llama 'gastos_empresa', su ID es 'id_gasto'
        parent::__construct('gastos_empresa', 'id_gasto');
    }

    /**
     * Registra un gasto operativo o de mantenimiento.
     * @param array $data Incluye id_usuario, id_moto (puede ser NULL), fecha_gasto, monto, descripcion, categoria.
     * @return int ID del gasto registrado.
     */
    public function registrarGasto(array $data) {
        // Validación básica
        if ($data['monto'] <= 0) {
            throw new Exception("El monto del gasto debe ser positivo.");
        }
        
        // Uso del método create de BaseModel para insertar
        return $this->create($data); 
    }
    
    /**
     * Obtiene todos los gastos con detalles adicionales (moto y usuario).
     * @return array Lista de gastos con joins.
     */
    public function getAllWithDetails(): array {
        $sql = "
            SELECT
                g.id_gasto,
                g.fecha_gasto,
                g.monto,
                g.descripcion,
                g.categoria,
                g.id_moto,
                g.id_usuario,
                g.creado_en,
                m.placa AS moto_placa,
                m.marca AS moto_marca,
                u.nombre AS usuario_nombre
            FROM {$this->table} g
            LEFT JOIN motos m ON g.id_moto = m.id_moto
            LEFT JOIN usuarios u ON g.id_usuario = u.id_usuario
            ORDER BY g.fecha_gasto DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene el total de gastos para una moto específica en un período (para reportes).
     * @param int $idMoto
     * @param string $fechaInicio
     * @param string $fechaFin
     * @return float
     */
    public function getTotalGastosPorMoto(int $idMoto, string $fechaInicio, string $fechaFin): float {
        $sql = "
            SELECT SUM(monto)
            FROM {$this->table}
            WHERE id_moto = :id_moto
            AND fecha_gasto BETWEEN :fecha_inicio AND :fecha_fin
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_moto', $idMoto, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_inicio', $fechaInicio);
        $stmt->bindParam(':fecha_fin', $fechaFin);
        $stmt->execute();
        return (float)($stmt->fetchColumn() ?? 0.00);
    }
}