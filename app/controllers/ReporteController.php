<?php
// /app/controllers/ReporteController.php

require_once __DIR__ . '/../models/Reporte.php';
require_once __DIR__ . '/../core/Session.php';

class ReporteController {
    
    private $reporteModel;

    public function __construct() {
        $this->reporteModel = new Reporte();
    }

    public function index() {
        Session::checkPermission(['administrador', 'contador']);
        
        $fechaInicio = $_GET['f_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['f_fin'] ?? date('Y-m-d');
        
        $reporteMotos = $this->reporteModel->getUtilidadRealPorMoto($fechaInicio, $fechaFin);

        $data = [
            'title' => 'Reportes de Rentabilidad y Utilidad Real',
            'reporteMotos' => $reporteMotos,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
        ];
        
        $contentView = __DIR__ . '/../views/reportes/index.php';
        $title = $data['title'];
        $reporteMotos = $data['reporteMotos'];
        $f_inicio = $data['fechaInicio'];
        $f_fin = $data['fechaFin'];
        require_once __DIR__ . '/../views/layouts/app.php';
    }
    
    // Implementar exportación a CSV/PDF aquí...
    // public function export() { ... }
}