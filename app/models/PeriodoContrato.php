<?php
// /app/models/PeriodoContrato.php

require_once __DIR__ . '/BaseModel.php';

class PeriodoContrato extends BaseModel {
    public function __construct() {
        parent::__construct('periodos_contrato', 'id_periodo');
    }

    /**
     * Método obsoleto - Los días ahora se generan dinámicamente al registrar pagos o marcar como no pago.
     * Mantengo la firma por compatibilidad, pero no hace nada.
     */
    public static function poblarDiasHabilesPeriodo($idContrato, $idPeriodo, $fechaInicio, $fechaFin) {
        // No hacer nada - lógica simplificada
        return true;
    }

    protected $fillable = [
        'id_contrato',
        'numero_periodo',
        'fecha_inicio_periodo',
        'fecha_fin_periodo',
        'cuota_acumulada',
        'estado_periodo',
        'cerrado_en',
        'pago_anticipado'
    ];

    /**
     * Calcular días hábiles en un mes (excluyendo domingos)
     */
    public static function calcularDiasHabilesMes($mes = null, $anio = null) {
        $mes = $mes ?? date('m');
        $anio = $anio ?? date('Y');

        $primerDia = strtotime("$anio-$mes-01");
        $ultimoDia = strtotime(date('Y-m-t', $primerDia));

        $diasHabiles = 0;
        for ($dia = $primerDia; $dia <= $ultimoDia; $dia = strtotime('+1 day', $dia)) {
            // Excluir domingos (0 = domingo)
            if (date('w', $dia) != 0) {
                $diasHabiles++;
            }
        }

        return $diasHabiles;
    }

    /**
     * Calcular fecha fin de periodo mensual simple (1 mes calendario)
     */
    public static function calcularFechaFinPeriodo($fechaInicio) {
        return date('Y-m-d', strtotime($fechaInicio . ' +1 month -1 day'));
    }

    /**
     * Crear periodos para un contrato
     */
    public static function crearPeriodosParaContrato($idContrato, $fechaInicio, $plazoMeses) {
        $periodos = [];
        $fechaInicioPeriodo = $fechaInicio;

        for ($i = 1; $i <= $plazoMeses; $i++) {
            $fechaFinPeriodo = self::calcularFechaFinPeriodo($fechaInicioPeriodo);

            $periodos[] = [
                'id_contrato' => $idContrato,
                'numero_periodo' => $i,
                'fecha_inicio_periodo' => $fechaInicioPeriodo,
                'fecha_fin_periodo' => $fechaFinPeriodo,
                'cuota_acumulada' => 0.00,
                'estado_periodo' => 'abierto'
            ];

            // Siguiente periodo comienza al día siguiente del fin del anterior
            $fechaInicioPeriodo = date('Y-m-d', strtotime($fechaFinPeriodo . ' +1 day'));
        }

        $periodoModel = new self();
        foreach ($periodos as $periodo) {
            $periodoModel->create($periodo);
        }

        return $periodos;
    }

    /**
     * Obtener periodo actual abierto para un contrato
     */
    public static function getPeriodoActual($idContrato) {
        $periodo = new self();
        return $periodo->where(['id_contrato' => $idContrato, 'estado_periodo' => 'abierto'])
                    ->orderBy('numero_periodo', 'ASC')
                    ->first();
    }

    /**
     * Obtener periodos de un contrato
     */
    public static function getPeriodosPorContrato($idContrato) {
        $periodo = new self();
        return $periodo->where(['id_contrato' => $idContrato])
                    ->orderBy('numero_periodo', 'ASC')
                    ->get();
    }

    /**
     * Obtener días del periodo desde pagos_diarios_contrato
     */
    public static function getDiasPeriodo($idContrato, $idPeriodo) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM pagos_diarios_contrato WHERE id_contrato = :c AND id_periodo = :p ORDER BY fecha ASC");
        $stmt->execute([':c' => $idContrato, ':p' => $idPeriodo]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Marcar estado de un día (pagado/pendiente/no_pago) y opcional observación
     */
    public static function marcarEstadoDia($idContrato, $idPeriodo, $fecha, $estado, $observacion = null) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE pagos_diarios_contrato SET estado_dia = :e, observacion = :o WHERE id_contrato = :c AND id_periodo = :p AND fecha = :f");
        return $stmt->execute([':e' => $estado, ':o' => $observacion, ':c' => $idContrato, ':p' => $idPeriodo, ':f' => $fecha]);
    }

    /**
     * Actualizar cuota acumulada en un periodo
     */
    public function actualizarCuotaAcumulada($idPeriodo, $montoAdicional) {
        $periodo = $this->find($idPeriodo);
        if (!$periodo) {
            throw new Exception('Periodo no encontrado');
        }

        $nuevaCuota = $periodo['cuota_acumulada'] + $montoAdicional;
        $this->update($idPeriodo, ['cuota_acumulada' => $nuevaCuota]);

        return $nuevaCuota;
    }
}
