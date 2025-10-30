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
     * Reflejar pago en control diario - Inserta o actualiza el día específico del pago
     */
    public static function reflejarEnControlDiario($idContrato, $idPeriodo, $fecha, $montoPago, $idUsuario) {
        $db = Database::getInstance()->getConnection();

        // Primero obtener el monto actual del día
        $stmt = $db->prepare("SELECT monto_pagado FROM pagos_diarios_contrato WHERE id_contrato = :id_contrato AND id_periodo = :id_periodo AND fecha = :fecha");
        $stmt->execute([':id_contrato' => $idContrato, ':id_periodo' => $idPeriodo, ':fecha' => $fecha]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);

        $currentMonto = $current ? (float)$current['monto_pagado'] : 0.00;
        $newMonto = $currentMonto + $montoPago;

        // Determinar nuevo estado basado en el monto
        $newEstado = ($newMonto > 0) ? 'pagado' : 'pendiente';

        // Intentar actualizar primero
        $updateStmt = $db->prepare("
            UPDATE pagos_diarios_contrato
            SET monto_pagado = :new_monto,
                estado_dia = :new_estado,
                id_usuario_registra = :id_usuario,
                updated_at = NOW()
            WHERE id_contrato = :id_contrato AND id_periodo = :id_periodo AND fecha = :fecha
        ");

        $result = $updateStmt->execute([
            ':new_monto' => $newMonto,
            ':new_estado' => $newEstado,
            ':id_usuario' => $idUsuario,
            ':id_contrato' => $idContrato,
            ':id_periodo' => $idPeriodo,
            ':fecha' => $fecha
        ]);

        // Si no se actualizó ninguna fila, insertar nuevo registro
        if ($result && $updateStmt->rowCount() == 0) {
            $insertStmt = $db->prepare("
                INSERT INTO pagos_diarios_contrato
                (id_contrato, id_periodo, fecha, es_domingo, estado_dia, monto_pagado, id_usuario_registra, created_at, updated_at)
                VALUES (:id_contrato, :id_periodo, :fecha, :es_domingo, :estado_dia, :monto_pagado, :id_usuario, NOW(), NOW())
            ");
            $esDomingo = (date('w', strtotime($fecha)) == 0) ? 1 : 0;
            $insertStmt->execute([
                ':id_contrato' => $idContrato,
                ':id_periodo' => $idPeriodo,
                ':fecha' => $fecha,
                ':es_domingo' => $esDomingo,
                ':estado_dia' => $newEstado,
                ':monto_pagado' => $newMonto,
                ':id_usuario' => $idUsuario
            ]);
        }

        return $result;
    }

    /**
     * Registrar un pago en un periodo
     */
    public function registrarPago($data) {
        // Validar que el periodo existe y está abierto
        $periodoModel = new PeriodoContrato();
        $periodo = $periodoModel->find($data['id_periodo']);
        if (!$periodo) {
            throw new Exception('Periodo no encontrado');
        }
        if ($periodo['estado_periodo'] !== 'abierto') {
            throw new Exception('El periodo ya está cerrado');
        }

        // Crear el pago
        $pagoId = $this->create([
            'id_contrato' => $data['id_contrato'],
            'id_periodo' => $data['id_periodo'],
            'id_usuario' => $data['id_usuario'],
            'fecha_pago' => $data['fecha_pago'],
            'monto_pago' => $data['monto_pago'],
            'concepto' => $data['concepto'] ?? ''
        ]);

        // Actualizar cuota acumulada en el periodo
        $periodoModel->actualizarCuotaAcumulada($data['id_periodo'], $data['monto_pago']);

        // Reflejar SOLO el día del pago en control diario (no distribuir)
        self::reflejarEnControlDiario($data['id_contrato'], $data['id_periodo'], $data['fecha_pago'], $data['monto_pago'], $data['id_usuario']);

        return $pagoId;
    }

    /**
     * Obtener pagos de un contrato
     */
    public function getPagosPorContrato($idContrato) {
        $sql = "SELECT pc.*, p.numero_periodo, p.fecha_inicio_periodo, p.fecha_fin_periodo, p.estado_periodo
                FROM pagos_contrato pc
                JOIN periodos_contrato p ON pc.id_periodo = p.id_periodo
                WHERE pc.id_contrato = :id_contrato
                ORDER BY pc.fecha_pago DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_contrato' => $idContrato]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener pagos de un periodo específico
     */
    public function getPagosPorPeriodo($idPeriodo) {
        return $this->where(['id_periodo' => $idPeriodo])
                   ->orderBy('fecha_pago', 'DESC')
                   ->get();
    }

    /**
     * Obtener pagos en un rango de fechas
     */
    public function getPagosPorFecha($fechaInicio, $fechaFin, $idContrato = null) {
        $where = "fecha_pago BETWEEN :fecha_inicio AND :fecha_fin";
        $params = ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin];

        if ($idContrato) {
            $where .= " AND id_contrato = :id_contrato";
            $params['id_contrato'] = $idContrato;
        }

        return $this->whereRaw($where, $params)
                   ->orderBy('fecha_pago', 'DESC')
                   ->get();
    }

    /**
     * Calcular total pagado en un periodo
     */
    public static function getTotalPagadoEnPeriodo($idPeriodo) {
        $pago = new self();
        $sql = "SELECT SUM(monto_pago) as total FROM pagos_contrato WHERE id_periodo = :id_periodo";
        $stmt = $pago->db->prepare($sql);
        $stmt->execute(['id_periodo' => $idPeriodo]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Calcular total pagado en un contrato
     */
    public static function getTotalPagadoEnContrato($idContrato) {
        $pago = new self();
        $sql = "SELECT SUM(monto_pago) as total FROM pagos_contrato WHERE id_contrato = :id_contrato";
        $stmt = $pago->db->prepare($sql);
        $stmt->execute(['id_contrato' => $idContrato]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Actualizar un pago
     */
    public function updatePago($idPago, $data) {
        $pago = $this->find($idPago);
        if (!$pago) {
            throw new Exception('Pago no encontrado');
        }

        // Calcular diferencia de monto para ajustar control diario
        $diferenciaMonto = $data['monto_pago'] - $pago['monto_pago'];

        // Actualizar el pago
        $this->update($idPago, $data);

        // Si cambió el monto, ajustar el control diario
        if ($diferenciaMonto != 0) {
            // Restar el monto anterior del día
            self::reflejarEnControlDiario($pago['id_contrato'], $pago['id_periodo'], $pago['fecha_pago'], -$pago['monto_pago'], $data['id_usuario'] ?? $pago['id_usuario']);
            // Agregar el nuevo monto al día
            self::reflejarEnControlDiario($pago['id_contrato'], $pago['id_periodo'], $data['fecha_pago'] ?? $pago['fecha_pago'], $data['monto_pago'], $data['id_usuario'] ?? $pago['id_usuario']);
        } elseif (($data['fecha_pago'] ?? $pago['fecha_pago']) != $pago['fecha_pago']) {
            // Si cambió la fecha, mover el monto al nuevo día
            self::reflejarEnControlDiario($pago['id_contrato'], $pago['id_periodo'], $pago['fecha_pago'], -$pago['monto_pago'], $data['id_usuario'] ?? $pago['id_usuario']);
            self::reflejarEnControlDiario($pago['id_contrato'], $pago['id_periodo'], $data['fecha_pago'], $data['monto_pago'], $data['id_usuario'] ?? $pago['id_usuario']);
        }

        // Actualizar cuota acumulada si cambió el monto
        if ($diferenciaMonto != 0) {
            $periodoModel = new PeriodoContrato();
            $periodoModel->actualizarCuotaAcumulada($pago['id_periodo'], $diferenciaMonto);
        }

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

        // Revertir el reflejo en control diario
        self::reflejarEnControlDiario($pago['id_contrato'], $pago['id_periodo'], $pago['fecha_pago'], -$pago['monto_pago'], $pago['id_usuario']);

        // Actualizar cuota acumulada
        $periodoModel = new PeriodoContrato();
        $periodoModel->actualizarCuotaAcumulada($pago['id_periodo'], -$pago['monto_pago']);

        // Eliminar el pago
        return $this->delete($idPago);
    }
}
