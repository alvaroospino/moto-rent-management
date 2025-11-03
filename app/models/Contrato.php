<?php
// /app/models/Contrato.php

require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/PeriodoContrato.php';
require_once __DIR__ . '/PagoContrato.php';
require_once __DIR__ . '/Gasto.php'; // Agregado: Requerido por getUtilidadNetaMes y getRentabilidadMensual

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
        $periodos = PeriodoContrato::crearPeriodosParaContrato($contratoId, $data['fecha_inicio'], $data['plazo_meses']);

        // Poblar días del primer periodo generado (y opcionalmente todos)
        $periodoModel = new PeriodoContrato();
        $created = $periodoModel->where(['id_contrato' => $contratoId])->orderBy('numero_periodo','ASC')->get();
        foreach ($created as $p) {
            PeriodoContrato::poblarDiasHabilesPeriodo($contratoId, $p['id_periodo'], $p['fecha_inicio_periodo'], $p['fecha_fin_periodo']);
        }

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
        $baseModel = new self(); // Usamos la instancia para acceder a los métodos de compatibilidad

        // --- CONSULTA AJUSTADA PARA COMPATIBILIDAD ---
        $sqlMonth = $baseModel->getSqlMonth('fecha_pago');
        $sqlYear = $baseModel->getSqlYear('fecha_pago');
        $sqlCurDate = $baseModel->getSqlCurrentDate();
        $sqlCurDateMonth = $baseModel->getSqlMonth($sqlCurDate);
        $sqlCurDateYear = $baseModel->getSqlYear($sqlCurDate);

        $sql = "SELECT SUM(monto_pago) as total FROM pagos_contrato
                WHERE id_contrato = :id_contrato
                AND {$sqlMonth} = {$sqlCurDateMonth}
                AND {$sqlYear} = {$sqlCurDateYear}";
        // --- FIN AJUSTE ---

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
        $baseModel = new self(); // Usamos la instancia para acceder a los métodos de compatibilidad
        $sqlCurDate = $baseModel->getSqlCurrentDate();

        // PostgreSQL usa sintaxis específica
        $sql = "SELECT SUM(monto_pago) as total FROM pagos_contrato WHERE fecha_pago::date = {$sqlCurDate}";

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

    /**
     * Obtener contratos próximos a vencer (próximos 7 días)
     */
    public static function getContratosProximosVencer() {
        $contrato = new self();

        // PostgreSQL usa la diferencia de fechas con operador -
        $dateDiffSql = "(p.fecha_fin_periodo::date - CURRENT_DATE)";

        $sql = "
            SELECT c.*, cl.nombre_completo, m.placa, m.marca,
                   {$dateDiffSql} as dias_restantes
            FROM contratos c
            JOIN clientes cl ON c.id_cliente = cl.id_cliente
            JOIN motos m ON c.id_moto = m.id_moto
            JOIN periodos_contrato p ON c.id_contrato = p.id_contrato
            WHERE c.estado = 'activo'
            AND p.estado_periodo = 'abierto'
            AND {$dateDiffSql} BETWEEN 0 AND 7
            ORDER BY p.fecha_fin_periodo ASC
        ";

        $stmt = $contrato->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener utilidad neta del mes actual (ingresos - gastos)
     */
    public static function getUtilidadNetaMes() {
        // Ingresos del mes
        $ingresos = self::getIngresoMes();

        // Gastos del mes
        // Asegúrate de que Gasto.php esté requerido al inicio del archivo
        $gastoModel = new Gasto();
        $gastos = $gastoModel->getTotalGastosMes();

        return $ingresos - $gastos;
    }

    /**
     * Obtener ingresos del mes actual
     */
    public static function getIngresoMes() {
        $pagoModel = new PagoContrato();
        $baseModel = new self(); // Usamos la instancia para acceder a los métodos de compatibilidad

        // --- CONSULTA AJUSTADA PARA COMPATIBILIDAD ---
        $sqlMonth = $baseModel->getSqlMonth('fecha_pago');
        $sqlYear = $baseModel->getSqlYear('fecha_pago');
        $sqlCurDate = $baseModel->getSqlCurrentDate();
        $sqlCurDateMonth = $baseModel->getSqlMonth($sqlCurDate);
        $sqlCurDateYear = $baseModel->getSqlYear($sqlCurDate);

        $sql = "SELECT SUM(monto_pago) as total FROM pagos_contrato
                WHERE {$sqlMonth} = {$sqlCurDateMonth}
                AND {$sqlYear} = {$sqlCurDateYear}";
        // --- FIN AJUSTE ---

        $stmt = $pagoModel->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total'] ?? 0 : 0;
    }

    /**
     * Obtener ingresos de los últimos 6 meses
     */
    public static function getIngresosUltimos6Meses() {
        $contrato = new self();

        // --- CONSULTA AJUSTADA PARA COMPATIBILIDAD ---
        $sqlDateFormat = $contrato->getSqlDateFormat('fecha_pago');
        $sqlDateSub = $contrato->getSqlDateSubMonths($contrato->getSqlCurrentDate(), 6);

        $sql = "
            SELECT
                {$sqlDateFormat} as mes,
                SUM(monto_pago) as total_ingresos
            FROM pagos_contrato
            WHERE fecha_pago >= {$sqlDateSub}
            GROUP BY {$sqlDateFormat}
            ORDER BY mes ASC
        ";
        // --- FIN AJUSTE ---

        $stmt = $contrato->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener pagos recientes
     */
    public static function getPagosRecientes($limit = 10) {
        $contrato = new self();

        // PostgreSQL TO_CHAR para formato 'DD/MM/YYYY'
        $dateFormatDisplay = "TO_CHAR(pc.fecha_pago, 'DD/MM/YYYY')";

        $sql = "
            SELECT
                pc.*,
                c.nombre_completo,
                m.placa,
                {$dateFormatDisplay} as fecha_formateada
            FROM pagos_contrato pc
            JOIN contratos co ON pc.id_contrato = co.id_contrato
            JOIN clientes c ON co.id_cliente = c.id_cliente
            JOIN motos m ON co.id_moto = m.id_moto
            ORDER BY pc.fecha_pago DESC
            LIMIT :limit
        ";
        $stmt = $contrato->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Calcular total de pagos del mes actual para todos los contratos
     */
    public static function calcularPagosMesActualTotal() {
        $pagoModel = new PagoContrato();
        $baseModel = new self(); // Usamos la instancia para acceder a los métodos de compatibilidad

        // --- CONSULTA AJUSTADA PARA COMPATIBILIDAD ---
        $sqlMonth = $baseModel->getSqlMonth('fecha_pago');
        $sqlYear = $baseModel->getSqlYear('fecha_pago');
        $sqlCurDate = $baseModel->getSqlCurrentDate();
        $sqlCurDateMonth = $baseModel->getSqlMonth($sqlCurDate);
        $sqlCurDateYear = $baseModel->getSqlYear($sqlCurDate);
        
        $sql = "SELECT SUM(monto_pago) as total FROM pagos_contrato
                WHERE {$sqlMonth} = {$sqlCurDateMonth}
                AND {$sqlYear} = {$sqlCurDateYear}";
        // --- FIN AJUSTE ---
        
        $stmt = $pagoModel->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total'] ?? 0 : 0;
    }

    /**
     * Obtener rentabilidad mensual (ingresos vs gastos)
     */
    public static function getRentabilidadMensual() {
        $contrato = new self();
        $gastoModel = new Gasto();

        // Generar los últimos 6 meses (Lógica PHP es compatible)
        $meses = [];
        for ($i = 5; $i >= 0; $i--) {
            $meses[] = date('Y-m', strtotime("-$i months"));
        }

        // --- CONSULTA AJUSTADA PARA COMPATIBILIDAD ---
        $sqlDateFormat = $contrato->getSqlDateFormat('fecha_pago');
        $sqlDateSub = $contrato->getSqlDateSubMonths($contrato->getSqlCurrentDate(), 6);

        $sql = "
            SELECT
                {$sqlDateFormat} as mes,
                SUM(monto_pago) as ingresos
            FROM pagos_contrato
            WHERE fecha_pago >= {$sqlDateSub}
            GROUP BY {$sqlDateFormat}
            ORDER BY mes ASC
        ";
        // --- FIN AJUSTE ---

        $stmt = $contrato->db->query($sql);
        $ingresos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $gastos = $gastoModel->getGastosUltimos6Meses();

        $rentabilidad = [];
        foreach ($meses as $mes) {
            $ingreso_mes = 0;
            foreach ($ingresos as $ingreso) {
                if ($ingreso['mes'] === $mes) {
                    $ingreso_mes = $ingreso['ingresos'];
                    break;
                }
            }
            $gasto_mes = 0;
            foreach ($gastos as $gasto) {
                if ($gasto['mes'] === $mes) {
                    $gasto_mes = $gasto['total_gastos'];
                    break;
                }
            }
            $rentabilidad[] = [
                'mes' => $mes,
                'ingresos' => $ingreso_mes,
                'gastos' => $gasto_mes,
                'utilidad' => $ingreso_mes - $gasto_mes
            ];
        }

        return $rentabilidad;
    }

    /**
     * Calcular utilidad neta de un contrato específico (ingresos totales - gastos totales de la moto)
     * @param int $idContrato
     * @return array
     */
    public static function getUtilidadContrato($idContrato) {
        $contratoModel = new self();
        $contrato = $contratoModel->find($idContrato);
        if (!$contrato) {
            return ['error' => 'Contrato no encontrado'];
        }

        // Ingresos totales del contrato
        $ingresosTotales = PagoContrato::getTotalPagadoEnContrato($idContrato);

        // Gastos totales de la moto asociada al contrato
        $gastoModel = new Gasto();
        $gastosTotales = $gastoModel->getTotalGastosPorMotoTotal($contrato['id_moto']);

        // Utilidad neta
        $utilidadNeta = $ingresosTotales - $gastosTotales;

        return [
            'ingresos_totales' => $ingresosTotales,
            'gastos_totales' => $gastosTotales,
            'utilidad_neta' => $utilidadNeta,
            'rentabilidad' => $ingresosTotales > 0 ? (($utilidadNeta / $ingresosTotales) * 100) : 0
        ];
    }
}