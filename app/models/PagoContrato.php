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
}
