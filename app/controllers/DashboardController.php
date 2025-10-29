<?php
// /app/controllers/DashboardController.php

require_once __DIR__ . '/../core/Session.php';
// Requerir modelos necesarios para obtener KPIs
// require_once __DIR__ . '/../models/Moto.php'; 
// require_once __DIR__ . '/../models/Contrato.php'; 

class DashboardController {
    
    public function index() {
        // Verificar permisos: todos los usuarios logueados pueden ver el dashboard
        Session::checkPermission(['administrador', 'operador', 'contador']);

        // --- Lógica de Negocio para KPIs ---
        // Cargar modelos para obtener datos reales de la base de datos
        require_once __DIR__ . '/../models/Moto.php';
        require_once __DIR__ . '/../models/Contrato.php';

        $motoModel = new Moto();
        $contratoModel = new Contrato();

        $stats = [
            'motos_activas' => $motoModel->count(['estado' => 'activo']),
            'ingreso_dia' => $contratoModel->getIngresoHoy(),
            'total_por_cobrar' => $contratoModel->getSaldoTotal(),
            'contratos_mora' => $contratoModel->count(['estado' => 'mora']),
        ];

        $data = [
            'title' => 'Dashboard Principal',
            'stats' => $stats,
        ];

        // Carga el layout principal, inyectando la vista específica
        $contentView = __DIR__ . '/../views/dashboard/index.php';
        $title = $data['title'];
        $stats = $data['stats'];

        require_once __DIR__ . '/../views/layouts/app.php';
    }
}