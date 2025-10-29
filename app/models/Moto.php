<?php
// /app/models/Moto.php (La clase específica)

require_once __DIR__ . '/BaseModel.php';

class Moto extends BaseModel {
    public function __construct() {
        // El id de la tabla es id_moto
        parent::__construct('motos', 'id_moto');
    }

    // Puedes añadir lógica específica de negocio aquí, ej:
    public function getRendimientoPorMoto($id_moto) {
        // Lógica que consultaría contratos, gastos y valor_adquisicion
        // Retorna un arreglo con [ingreso_total, gasto_total, utilidad]
        return [
            'ingreso_total' => 0.00,
            'gasto_total' => 0.00,
            'utilidad_neta' => 0.00
        ];
    }
}