<?php
// /app/models/PagoContrato.php

require_once __DIR__ . '/BaseModel.php';

class PagoContrato extends BaseModel {
    public function __construct() {
        parent::__construct('pagos_contrato', 'id_pago');
    }

    protected $fillable = [
        'id_contrato',
        'id_periodo',
        'id_usuario',
        'fecha_pago',
        'monto_pago',
        'concepto'
    ];

    /**
     * Reflejar pago en control diario
     */
    /**
     * Reflejar pago en control diario
     */
    public static function reflejarEnControlDiario($idContrato, $idPeriodo, $fecha, $montoPago, $idUsuario) {
        $db = Database::getInstance()->getConnection();

        // Primero intentar actualizar si existe
        $updateStmt = $db->prepare("
            UPDATE pagos_diarios_contrato
            SET monto_pagado = monto_pagado + :monto_pago,
                estado_dia = CASE WHEN monto_pagado + :monto_pago_case > 0 THEN 'pagado' ELSE 'pendiente' END,
                id_usuario_registra = :id_usuario,
                updated_at = :updated_at
            WHERE id_contrato = :id_contrato AND id_periodo = :id_periodo AND fecha = :fecha
        ");
        $timestamp = date('Y-m-d H:i:s');
        $result = $updateStmt->execute([
            ':monto_pago' => $montoPago,
            ':monto_pago_case' => $montoPago,
            ':id_usuario' => $idUsuario,
            ':updated_at' => $timestamp,
            ':id_contrato' => $idContrato,
            ':id_periodo' => $idPeriodo,
            ':fecha' => $fecha
        ]);

        // Si no se actualizó nada (no existe), insertar nuevo
        if ($result && $updateStmt->rowCount() == 0) {
            
            // Conversión explícita para PostgreSQL: 
            // el booleano de PHP 'true'/'false' se convierte a las cadenas 't'/'f'
            $esDomingo = (date('w', strtotime($fecha)) == 0);
            $esDomingo_pg = $esDomingo ? 't' : 'f'; 
            
            $estadoInicial = ($montoPago > 0) ? 'pagado' : 'pendiente';

            $insertStmt = $db->prepare("
                INSERT INTO pagos_diarios_contrato
                (id_contrato, id_periodo, fecha, es_domingo, estado_dia, monto_pagado, id_usuario_registra, created_at, updated_at)
                VALUES (:id_contrato, :id_periodo, :fecha, :es_domingo, :estado_dia, :monto_pagado, :id_usuario, :created_at, :updated_at)
            ");
            $insertStmt->execute([
                ':id_contrato' => $idContrato,
                ':id_periodo' => $idPeriodo,
                ':fecha' => $fecha,
                ':es_domingo' => $esDomingo_pg, // Usando el valor convertido
                ':estado_dia' => $estadoInicial,
                ':monto_pagado' => $montoPago,
                ':id_usuario' => $idUsuario,
                ':created_at' => $timestamp,
                ':updated_at' => $timestamp
            ]);
        }

        return true;
    }

    /**
     * Registrar un pago
     */
    public function registrarPago($data) {
        $periodoModel = new PeriodoContrato();
        $periodo = $periodoModel->find($data['id_periodo']);
        if (!$periodo) throw new Exception('Periodo no encontrado');
        if ($periodo['estado_periodo'] !== 'abierto') throw new Exception('El periodo ya está cerrado');

        // Crear pago
        $pagoId = $this->create([
            'id_contrato' => $data['id_contrato'],
            'id_periodo' => $data['id_periodo'],
            'id_usuario' => $data['id_usuario'],
            'fecha_pago' => $data['fecha_pago'],
            'monto_pago' => $data['monto_pago'],
            'concepto' => $data['concepto'] ?? ''
        ]);

        // Actualizar y reflejar
        $periodoModel->actualizarCuotaAcumulada($data['id_periodo'], $data['monto_pago']);
        self::reflejarEnControlDiario($data['id_contrato'], $data['id_periodo'], $data['fecha_pago'], $data['monto_pago'], $data['id_usuario']);

        return $pagoId;
    }

    /**
     * Obtener total pagado en un periodo específico
     */
    public static function getTotalPagadoEnPeriodo($idPeriodo) {
        $pagoModel = new self();
        $sql = "SELECT SUM(monto_pago) as total FROM {$pagoModel->table} WHERE id_periodo = :id_periodo";
        $stmt = $pagoModel->db->prepare($sql);
        $stmt->execute([':id_periodo' => $idPeriodo]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (float)$result['total'] : 0.00;
    }

    /**
     * Obtener total pagado en un contrato
     */
    public static function getTotalPagadoEnContrato($idContrato) {
        $pagoModel = new self();
        $sql = "SELECT SUM(monto_pago) as total FROM {$pagoModel->table} WHERE id_contrato = :id_contrato";
        $stmt = $pagoModel->db->prepare($sql);
        $stmt->execute([':id_contrato' => $idContrato]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (float)$result['total'] : 0.00;
    }

    /**
     * Obtener pagos por contrato con detalles
     */
    public static function getPagosPorContrato($idContrato) {
        $pagoModel = new self();
        $sql = "
            SELECT pc.*, u.nombre as usuario_nombre, p.numero_periodo
            FROM {$pagoModel->table} pc
            LEFT JOIN usuarios u ON pc.id_usuario = u.id_usuario
            LEFT JOIN periodos_contrato p ON pc.id_periodo = p.id_periodo
            WHERE pc.id_contrato = :id_contrato
            ORDER BY pc.fecha_pago DESC
        ";
        $stmt = $pagoModel->db->prepare($sql);
        $stmt->execute([':id_contrato' => $idContrato]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Actualizar un pago
     */
    public function updatePago($idPago, $data) {
        $pago = $this->find($idPago);
        if (!$pago) {
            throw new Exception('Pago no encontrado');
        }

        // Calcular diferencia de monto para ajustar cuota acumulada
        $diferenciaMonto = $data['monto_pago'] - $pago['monto_pago'];
        $fechaCambio = ($data['fecha_pago'] !== $pago['fecha_pago']);

        // Actualizar el pago
        $this->update($idPago, [
            'fecha_pago' => $data['fecha_pago'],
            'monto_pago' => $data['monto_pago'],
            'concepto' => $data['concepto'],
            'id_usuario' => $data['id_usuario']
        ]);

        // Si cambió el monto, ajustar la cuota acumulada del periodo
        if ($diferenciaMonto != 0) {
            $periodoModel = new PeriodoContrato();
            $periodoModel->actualizarCuotaAcumulada($pago['id_periodo'], $diferenciaMonto);
        }

        // Actualizar el control diario con lógica más precisa
        if ($fechaCambio && $diferenciaMonto != 0) {
            // Cambió fecha Y monto: restar anterior del día anterior, sumar nuevo al día nuevo
            self::reflejarEnControlDiario($pago['id_contrato'], $pago['id_periodo'], $pago['fecha_pago'], -$pago['monto_pago'], $data['id_usuario']);
            self::reflejarEnControlDiario($pago['id_contrato'], $pago['id_periodo'], $data['fecha_pago'], $data['monto_pago'], $data['id_usuario']);
        } elseif ($fechaCambio) {
            // Solo cambió fecha: mover el monto del día anterior al nuevo día
            self::reflejarEnControlDiario($pago['id_contrato'], $pago['id_periodo'], $pago['fecha_pago'], -$pago['monto_pago'], $data['id_usuario']);
            self::reflejarEnControlDiario($pago['id_contrato'], $pago['id_periodo'], $data['fecha_pago'], $pago['monto_pago'], $data['id_usuario']);
        } elseif ($diferenciaMonto != 0) {
            // Solo cambió monto: aplicar la diferencia al día actual
            self::reflejarEnControlDiario($pago['id_contrato'], $pago['id_periodo'], $pago['fecha_pago'], $diferenciaMonto, $data['id_usuario']);
        }
        // Si no cambió nada, no hacer nada en control diario

        return true;
    }

    /**
     * Eliminar un pago
     */
    public function deletePago($idPago) {
        $pago = $this->find($idPago);
        if (!$pago) {
            throw new Exception('Pago no encontrado');
        }

        // Ajustar la cuota acumulada antes de eliminar
        $periodoModel = new PeriodoContrato();
        $periodoModel->actualizarCuotaAcumulada($pago['id_periodo'], -$pago['monto_pago']);

        // Ajustar el control diario (restar el monto del día correspondiente)
        self::reflejarEnControlDiario($pago['id_contrato'], $pago['id_periodo'], $pago['fecha_pago'], -$pago['monto_pago'], Session::get('user_id'));

        // Eliminar el pago
        return $this->delete($idPago);
    }
}
