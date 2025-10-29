<?php
// /app/models/Pago.php

require_once __DIR__ . '/BaseModel.php';

class Pago extends BaseModel {
    public function __construct() {
        parent::__construct('movimientos_contrato', 'id_movimiento');
    }

    /**
     * Registrar un pago en la tabla movimientos_contrato
     */
    public function registrarPago($data) {
        $sql = "INSERT INTO movimientos_contrato (
                    id_contrato,
                    id_usuario,
                    fecha_movimiento,
                    tipo,
                    monto,
                    concepto,
                    es_abono_capital,
                    es_abono_interes,
                    impacta_saldo_total
                ) VALUES (
                    :id_contrato,
                    :id_usuario,
                    :fecha_movimiento,
                    :tipo,
                    :monto,
                    :concepto,
                    :es_abono_capital,
                    :es_abono_interes,
                    :impacta_saldo_total
                )";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id_contrato' => $data['id_contrato'],
            'id_usuario' => $data['id_usuario'],
            'fecha_movimiento' => $data['fecha_movimiento'],
            'tipo' => $data['tipo'],
            'monto' => $data['monto'],
            'concepto' => $data['concepto'],
            'es_abono_capital' => $data['es_abono_capital'] ? 1 : 0,
            'es_abono_interes' => $data['es_abono_interes'] ? 1 : 0,
            'impacta_saldo_total' => $data['impacta_saldo_total'] ? 1 : 0
        ]);
    }

    /**
     * Obtener pagos de un contrato específico
     */
    public function getPagosPorContrato($idContrato) {
        $sql = "SELECT * FROM movimientos_contrato
                WHERE id_contrato = :id_contrato
                AND tipo = 'pago_ingreso'
                ORDER BY fecha_movimiento DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_contrato' => $idContrato]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener pagos del día actual
     */
    public function getPagosDelDia() {
        $sql = "SELECT SUM(monto) as total FROM movimientos_contrato
                WHERE tipo = 'pago_ingreso'
                AND DATE(fecha_movimiento) = CURDATE()";

        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Obtener pagos del mes actual
     */
    public function getPagosDelMes() {
        $sql = "SELECT SUM(monto) as total FROM movimientos_contrato
                WHERE tipo = 'pago_ingreso'
                AND MONTH(fecha_movimiento) = MONTH(CURDATE())
                AND YEAR(fecha_movimiento) = YEAR(CURDATE())";

        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Obtener resumen de pagos por contrato en un período
     */
    public function getResumenPagos($fechaInicio = null, $fechaFin = null) {
        $whereClause = "WHERE tipo = 'pago_ingreso'";

        if ($fechaInicio && $fechaFin) {
            $whereClause .= " AND fecha_movimiento BETWEEN :fecha_inicio AND :fecha_fin";
        }

        $sql = "SELECT
                    c.id_contrato,
                    cl.nombre_completo as cliente,
                    m.marca,
                    m.modelo,
                    m.placa,
                    COUNT(mc.id_movimiento) as total_pagos,
                    SUM(mc.monto) as total_pagado,
                    MAX(mc.fecha_movimiento) as ultimo_pago
                FROM contratos c
                JOIN clientes cl ON c.id_cliente = cl.id_cliente
                JOIN motos m ON c.id_moto = m.id_moto
                LEFT JOIN movimientos_contrato mc ON c.id_contrato = mc.id_contrato
                $whereClause
                GROUP BY c.id_contrato, cl.nombre_completo, m.marca, m.modelo, m.placa
                ORDER BY ultimo_pago DESC";

        $stmt = $this->db->prepare($sql);

        if ($fechaInicio && $fechaFin) {
            $stmt->execute([
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin
            ]);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
