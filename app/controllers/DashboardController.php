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
        require_once __DIR__ . '/../models/Cliente.php';
        require_once __DIR__ . '/../models/Gasto.php';

        $motoModel = new Moto();
        $contratoModel = new Contrato();
        $clienteModel = new Cliente();
        $gastoModel = new Gasto();

        $stats = [
            'motos_activas' => $motoModel->count(['estado' => 'activo']),
            'motos_disponibles' => $motoModel->count(['estado' => 'disponible']),
            'ingreso_dia' => $contratoModel->getIngresoHoy(),
            'ingreso_mes' => $contratoModel->getIngresoMes(),
            'total_por_cobrar' => $contratoModel->getSaldoTotal(),
            'contratos_mora' => $contratoModel->count(['estado' => 'mora']),
            'total_contratos_activos' => $contratoModel->count(['estado' => 'activo']),
            'total_clientes' => $clienteModel->count(),
            'gastos_mes' => $gastoModel->getTotalGastosMes(),
            'utilidad_neta_mes' => $contratoModel->getUtilidadNetaMes(),
        ];

        // Obtener datos para gráficos
        $chartData = [
            'ingresos_ultimos_6_meses' => $contratoModel->getIngresosUltimos6Meses(),
            'gastos_ultimos_6_meses' => $gastoModel->getGastosUltimos6Meses(),
            'pagos_recientes' => $contratoModel->getPagosRecientes(10),
            'rentabilidad_mensual' => $contratoModel->getRentabilidadMensual(),
        ];

        // Obtener contratos activos para pagos rápidos
        $contratos_activos = $contratoModel->getContratosActivos();

        $data = [
            'title' => 'Dashboard Principal',
            'stats' => $stats,
            'contratos_activos' => $contratos_activos,
            'chartData' => $chartData,
        ];

        // Carga el layout principal, inyectando la vista específica
        $contentView = __DIR__ . '/../views/dashboard/index.php';
        $title = $data['title'];
        $stats = $data['stats'];
        $contratos_activos = $data['contratos_activos'];
        $chartData = $data['chartData'];

        require_once __DIR__ . '/../views/layouts/app.php';
    }

    public function pagoRapido() {
        // Verificar permisos
        Session::checkPermission(['administrador', 'operador', 'contador']);

        // Validar datos del POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /dashboard');
            exit;
        }

        $id_contrato = $_POST['id_contrato'] ?? null;
        $monto_pago = $_POST['monto_pago'] ?? null;
        $fecha_pago = $_POST['fecha_pago'] ?? date('Y-m-d');
        $concepto = $_POST['concepto'] ?? '';

        if (!$id_contrato || !$monto_pago || $monto_pago <= 0) {
            $_SESSION['error'] = 'Datos de pago inválidos';
            header('Location: /dashboard');
            exit;
        }

        try {
            // Obtener periodo actual del contrato
            $periodoActual = Contrato::getPeriodoActual($id_contrato);
            if (!$periodoActual) {
                throw new Exception('No se encontró un periodo abierto para este contrato');
            }

            // Registrar el pago
            require_once __DIR__ . '/../models/PagoContrato.php';
            $pagoModel = new PagoContrato();
            $pagoModel->registrarPago([
                'id_contrato' => $id_contrato,
                'id_periodo' => $periodoActual['id_periodo'],
                'id_usuario' => Session::get('user_id'),
                'fecha_pago' => $fecha_pago,
                'monto_pago' => $monto_pago,
                'concepto' => $concepto
            ]);

            $_SESSION['success'] = 'Pago registrado exitosamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al registrar el pago: ' . $e->getMessage();
        }

        header('Location: /dashboard');
        exit;
    }
}