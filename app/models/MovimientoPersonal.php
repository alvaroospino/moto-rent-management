<?php
// /app/models/MovimientoPersonal.php

require_once __DIR__ . '/BaseModel.php';

class MovimientoPersonal extends BaseModel {
    public function __construct() {
        // La tabla se llama 'movimientos_personales', su ID es 'id_movimiento'
        parent::__construct('movimientos_personales', 'id_movimiento');
    }

    /**
     * Registra un movimiento personal (ingreso o gasto).
     * @param array $data Incluye id_usuario, tipo, fecha_movimiento, monto, descripcion.
     * @return int ID del movimiento registrado.
     */
    public function registrarMovimiento(array $data) {
        // Validación básica
        if ($data['monto'] <= 0) {
            throw new Exception("El monto del movimiento debe ser positivo.");
        }
        if (!in_array($data['tipo'], ['ingreso', 'gasto'])) {
            throw new Exception("El tipo debe ser 'ingreso' o 'gasto'.");
        }

        // Uso del método create de BaseModel para insertar
        return $this->create($data);
    }

    /**
     * Obtiene todos los movimientos personales del usuario con detalles adicionales.
     * @param int $idUsuario
     * @return array Lista de movimientos con joins.
     */
    public function getAllWithDetails(int $idUsuario): array {
        $sql = "
            SELECT
                mp.id_movimiento,
                mp.tipo,
                mp.fecha_movimiento,
                mp.monto,
                mp.descripcion,
                mp.creado_en,
                u.nombre AS usuario_nombre
            FROM {$this->table} mp
            LEFT JOIN usuarios u ON mp.id_usuario = u.id_usuario
            WHERE mp.id_usuario = :id_usuario
            ORDER BY mp.fecha_movimiento DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene los totales de ingresos, gastos y saldo para un usuario.
     * @param int $idUsuario
     * @return array ['total_ingresos', 'total_gastos', 'saldo']
     */
    public function getTotales(int $idUsuario): array {
        $sql = "
            SELECT
                COALESCE(SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE 0 END), 0) as total_ingresos,
                COALESCE(SUM(CASE WHEN tipo = 'gasto' THEN monto ELSE 0 END), 0) as total_gastos,
                COALESCE(SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE -monto END), 0) as saldo
            FROM {$this->table}
            WHERE id_usuario = :id_usuario
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'total_ingresos' => (float)$result['total_ingresos'],
            'total_gastos' => (float)$result['total_gastos'],
            'saldo' => (float)$result['saldo']
        ];
    }
}
