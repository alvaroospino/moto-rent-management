<?php
// /app/models/Contrato.php

require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/PeriodoContrato.php';
require_once __DIR__ . '/PagoContrato.php';

class Contrato extends BaseModel {
    public function __construct() {
        parent::__construct('contratos', 'id_contrato');
    }

    protected $fillable = [
        'id_cliente',
        'id_moto',
        'fecha_inicio',
        'valor_vehiculo',
        'plazo_meses',
        'cuota_mensual',
        'saldo_restante',
        'estado',
        'abono_capital_mensual',
        'ganancia_mensual'
    ];

    /**
     * Crear un nuevo contrato simplificado
     */
    public static function createContrato($data) {
        // Validar datos requeridos
        if (!isset($data['cuota_mensual'], $data['plazo_meses'])) {
            throw new Exception('Datos incompletos para crear contrato');
        }

        // Preparar datos para inserción
        $contratoData = [
            'id_cliente' => $data['id_cliente'],
            'id_moto' => $data['id_moto'],
            'fecha_inicio' => $data['fecha_inicio'],
            'valor_vehiculo' => $data['valor_vehiculo'],
            'plazo_meses' => $data['plazo_meses'],
            'cuota_mensual' => $data['cuota_mensual'],
            'saldo_restante' => 0, // Capital amortizado (se incrementa con abonos de capital)
            'estado' => 'activo',
            'abono_capital_mensual' => $data['abono_capital_mensual'],
            'ganancia_mensual' => $data['ganancia_mensual']
        ];

        $contrato = new self();
        $contratoId = $contrato->create($contratoData);

        // Crear periodos mensuales simples para el contrato
        PeriodoContrato::crearPeriodosParaContrato($contratoId, $data['fecha_inicio'], $data['plazo_meses']);

        return $contratoId;
    }

    /**
     * Obtener contratos activos
     */
    public static function getContratosActivos() {
        $contrato = new self();
        return $contrato->where(['estado' => 'activo'])->get();
    }

    /**
     * Obtener contratos por cliente
     */
    public static function getContratosPorCliente($clienteId) {
        $contrato = new self();
        return $contrato->where(['id_cliente' => $clienteId])->get();
    }

    /**
     * Obtener detalle completo de un contrato con periodos
     */
    public function getDetalleContrato($idContrato) {
        $contrato = $this->find($idContrato);
        if (!$contrato) {
            return null;
        }

        // Obtener periodos
        $periodos = PeriodoContrato::getPeriodosPorContrato($idContrato);

        // Obtener total pagado
        $totalPagado = PagoContrato::getTotalPagadoEnContrato($idContrato);

        return [
            'contrato' => $contrato,
            'periodos' => $periodos,
            'total_pagado' => $totalPagado
        ];
    }

    /**
     * Actualizar saldo después de cerrar un periodo
     */
    public function actualizarSaldo($idContrato, $abonoCapital) {
        $contrato = $this->find($idContrato);
        if (!$contrato) {
            throw new Exception('Contrato no encontrado');
        }

        $nuevoSaldo = $contrato['saldo_restante'] + $abonoCapital; // Suma el abono capital

        $this->update($idContrato, ['saldo_restante' => $nuevoSaldo]);

        // Si el saldo llega al valor del vehículo, marcar como finalizado
        if ($nuevoSaldo >= $contrato['valor_vehiculo']) {
            $this->update($idContrato, ['estado' => 'finalizado']);
        }

        return $nuevoSaldo;
    }

    /**
     * Obtener periodo actual para un contrato
     */
    public static function getPeriodoActual($idContrato) {
        return PeriodoContrato::getPeriodoActual($idContrato);
    }

    /**
     * Cerrar periodo simplificado
     */
    public function cerrarPeriodo($idContrato, $idPeriodo) {
        $contrato = $this->find($idContrato);
        if (!$contrato) {
            throw new Exception('Contrato no encontrado');
        }

        $periodoModel = new PeriodoContrato();
        $periodo = $periodoModel->find($idPeriodo);
        if (!$periodo) {
            throw new Exception('Periodo no encontrado');
        }

        // Verificar que el periodo esté abierto
        if ($periodo['estado_periodo'] !== 'abierto') {
            return ['error' => 'El periodo ya está cerrado'];
        }

        // Calcular total pagado en el periodo
        $totalPagado = PagoContrato::getTotalPagadoEnPeriodo($idPeriodo);

        // Si el total pagado es menor que la cuota mensual, no cerrar
        if ($totalPagado < $contrato['cuota_mensual']) {
            return ['error' => 'La cuota acumulada no alcanza el mínimo requerido para este periodo. Pagado: $' . number_format($totalPagado, 0, ',', '.') . ' de $' . number_format($contrato['cuota_mensual'], 0, ',', '.')];
        }

        // Cerrar el periodo
        $fechaActual = date('Y-m-d');
        $fechaFinPeriodo = $periodo['fecha_fin_periodo'];
        $esPagoAnticipado = ($fechaActual < $fechaFinPeriodo);

        $periodoModel->update($idPeriodo, [
            'estado_periodo' => 'cerrado',
            'cerrado_en' => date('Y-m-d H:i:s'),
            'pago_anticipado' => $esPagoAnticipado ? 1 : 0
        ]);

        // Aplicar abono capital al saldo restante
        $abonoCapital = $contrato['abono_capital_mensual'];
        $this->actualizarSaldo($idContrato, $abonoCapital);

        return [
            'abono_capital' => $abonoCapital,
            'pago_anticipado' => $esPagoAnticipado,
            'total_pagado' => $totalPagado
        ];
    }

    /**
     * Calcular cuota mensual usando fórmula de amortización
     */
    public static function calcularCuotaMensual($valorVehiculo, $tasaInteresAnual, $plazoMeses) {
        $tasaMensual = $tasaInteresAnual / 100 / 12;
        $cuota = $valorVehiculo * ($tasaMensual * pow(1 + $tasaMensual, $plazoMeses)) / (pow(1 + $tasaMensual, $plazoMeses) - 1);
        return round($cuota, 2);
    }

    /**
     * Calcular cuotas diarias (capital e intereses)
     */
    public static function calcularCuotasDiarias($cuotaMensual, $valorVehiculo, $tasaInteresAnual, $plazoMeses) {
        $tasaDiaria = $tasaInteresAnual / 100 / 365;
        $capitalDiario = $valorVehiculo / ($plazoMeses * 30); // Aproximadamente 30 días por mes
        $interesDiario = $valorVehiculo * $tasaDiaria;

        return [
            'capital_diario' => round($capitalDiario, 2),
            'interes_diario' => round($interesDiario, 2)
        ];
    }

    /**
     * Calcular pagos realizados en el mes actual para un contrato
     */
    public static function calcularPagosMesActual($idContrato) {
        $pagoModel = new PagoContrato();
        $sql = "SELECT SUM(monto_pago) as total FROM pagos_contrato
                WHERE id_contrato = :id_contrato
                AND MONTH(fecha_pago) = MONTH(CURDATE())
                AND YEAR(fecha_pago) = YEAR(CURDATE())";
        $stmt = $pagoModel->db->prepare($sql);
        $stmt->execute(['id_contrato' => $idContrato]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total'] ?? 0 : 0;
    }

    /**
     * Obtener ingreso del día actual
     */
    public static function getIngresoHoy() {
        $pagoModel = new PagoContrato();
        $sql = "SELECT SUM(monto_pago) as total FROM pagos_contrato WHERE DATE(fecha_pago) = CURDATE()";
        $stmt = $pagoModel->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total'] ?? 0 : 0;
    }

    /**
     * Obtener saldo total por cobrar
     */
    public static function getSaldoTotal() {
        $contrato = new self();
        $sql = "SELECT SUM(saldo_restante) as total FROM contratos WHERE estado = 'activo'";
        $stmt = $contrato->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total'] ?? 0 : 0;
    }
}
