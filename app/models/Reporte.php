<?php
// /app/models/Reporte.php

require_once __DIR__ . '/BaseModel.php';

class Reporte extends BaseModel {
    
    // Este modelo no necesita una tabla principal
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Calcula la rentabilidad real por moto en un período dado.
     * @param string $fechaInicio
     * @param string $fechaFin
     * @return array
     */
    public function getUtilidadRealPorMoto(string $fechaInicio, string $fechaFin): array {

        // Paso 1: Obtener Ingreso Real por Alquiler desde pagos_contrato
        $sqlIngresos = "
            SELECT
                c.id_moto,
                SUM(pc.monto_pago) as total_ingreso_alquiler
            FROM contratos c
            JOIN pagos_contrato pc ON c.id_contrato = pc.id_contrato
            WHERE pc.fecha_pago BETWEEN :f_inicio AND :f_fin
            GROUP BY c.id_moto
        ";
        $stmtIngresos = $this->db->prepare($sqlIngresos);
        $stmtIngresos->execute([':f_inicio' => $fechaInicio, ':f_fin' => $fechaFin]);
        $ingresos = $stmtIngresos->fetchAll(PDO::FETCH_KEY_PAIR); // [id_moto => total]

        // Paso 2: Obtener Gastos Operacionales (Asociados directamente a la moto)
        $sqlGastos = "
            SELECT
                id_moto,
                SUM(monto) as total_gasto_moto
            FROM gastos_empresa
            WHERE id_moto IS NOT NULL
              AND fecha_gasto BETWEEN :f_inicio AND :f_fin
            GROUP BY id_moto
        ";
        $stmtGastos = $this->db->prepare($sqlGastos);
        $stmtGastos->execute([':f_inicio' => $fechaInicio, ':f_fin' => $fechaFin]);
        $gastos = $stmtGastos->fetchAll(PDO::FETCH_KEY_PAIR); // [id_moto => total]

        // Paso 3: Obtener Inversión Inicial y datos de la moto
        $sqlMotos = "SELECT id_moto, placa, marca, valor_adquisicion FROM motos";
        $motos = $this->db->query($sqlMotos)->fetchAll();

        $reporte = [];

        foreach ($motos as $moto) {
            $id = $moto['id_moto'];
            $ingreso = (float)($ingresos[$id] ?? 0.00);
            $gasto = (float)($gastos[$id] ?? 0.00);
            $inversion_diaria = $moto['valor_adquisicion']; // Para este reporte, se asume la inversión total.

            // Utilidad Real = Ingreso por Alquiler - Gastos Operativos (del período) - (Amortización de Inversión)
            // Para simplicidad en este alcance, restaremos solo Gastos Operativos y mostraremos la Inversión aparte.

            $utilidad_bruta = $ingreso - $gasto;

            $reporte[] = [
                'id_moto' => $id,
                'placa' => $moto['placa'],
                'inversion_inicial' => (float)$inversion_diaria,
                'ingreso_alquiler' => $ingreso,
                'gasto_operativo' => $gasto,
                'utilidad_neta' => $utilidad_bruta,
                'roi_parcial' => $ingreso > 0 ? ($ingreso / $inversion_diaria) * 100 : 0,
            ];
        }

        return $reporte;
    }
}