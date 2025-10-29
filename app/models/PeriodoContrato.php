<?php
// /app/models/PeriodoContrato.php

require_once __DIR__ . '/BaseModel.php';

class PeriodoContrato extends BaseModel {
    public function __construct() {
        parent::__construct('periodos_contrato', 'id_periodo');
    }

    protected $fillable = [
        'id_contrato',
        'numero_periodo',
        'fecha_inicio_periodo',
        'fecha_fin_periodo',
        'cuota_acumulada',
        'estado_periodo',
        'cerrado_en'
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

    /**
     * Cerrar periodo simplificado
     */
    public function cerrarPeriodo($idPeriodo, $cuotaMensual) {
        $periodo = $this->find($idPeriodo);
        if (!$periodo) {
            throw new Exception('Periodo no encontrado');
        }

        // Obtener el contrato para acceder al abono capital mensual
        $contratoModel = new Contrato();
        $contrato = $contratoModel->find($periodo['id_contrato']);
        if (!$contrato) {
            throw new Exception('Contrato no encontrado');
        }

        $cuotaAcumulada = $periodo['cuota_acumulada'];
        $fechaActual = date('Y-m-d');
        $fechaInicioPeriodo = $periodo['fecha_inicio_periodo'];
        $fechaFinPeriodo = $periodo['fecha_fin_periodo'];

        // Validar que estamos dentro del periodo correspondiente
        if ($fechaActual < $fechaInicioPeriodo || $fechaActual > $fechaFinPeriodo) {
            // No se puede cerrar el periodo fuera de las fechas establecidas
            return [
                'abono_capital' => 0,
                'error' => 'No se puede cerrar el periodo fuera de las fechas establecidas'
            ];
        }

        // Si la cuota acumulada es mayor o igual a la cuota mensual Y estamos en el periodo correcto, cerrar el periodo
        if ($cuotaAcumulada >= $cuotaMensual) {
            $this->update($idPeriodo, [
                'estado_periodo' => 'cerrado',
                'cerrado_en' => date('Y-m-d H:i:s')
            ]);

            return [
                'abono_capital' => $contrato['abono_capital_mensual'] ?? $cuotaMensual
            ];
        }

        // Si no se ha completado la cuota completa, no cerrar el periodo ni aplicar abono capital
        return [
            'abono_capital' => 0,
            'error' => 'La cuota acumulada no alcanza el mínimo requerido para este periodo'
        ];
    }
}
