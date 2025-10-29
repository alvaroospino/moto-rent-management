<?php
// /app/controllers/ContratoController.php

require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/Contrato.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Moto.php';

class ContratoController {

   public function index() {
        // Verificar permisos
        Session::checkPermission(['administrador', 'operador']);

        // Obtener contratos activos
        $contratos = Contrato::getContratosActivos();

        // Para cada contrato, obtener información del cliente y moto, y calcular saldo por pagar
        foreach ($contratos as &$contrato) {
            $clienteModel = new Cliente();
            $cliente = $clienteModel->find($contrato['id_cliente'] ?? null);
            $motoModel = new Moto();
            $moto = $motoModel->find($contrato['id_moto'] ?? null);

            $contrato['cliente_nombre'] = $cliente ? $cliente['nombre_completo'] : 'N/A';
            $contrato['moto_marca'] = $moto ? $moto['marca'] : 'N/A';
            $contrato['moto_modelo'] = $moto ? $moto['modelo'] : 'N/A';

            // saldo_restante en DB representa el capital amortizado acumulado.
            // Para la UI, mostrar el saldo por pagar real: valor_vehiculo - saldo_restante acumulado.
            $valorVehiculo = isset($contrato['valor_vehiculo']) ? (float)$contrato['valor_vehiculo'] : 0.0;
            $capitalAmortizado = isset($contrato['saldo_restante']) ? (float)$contrato['saldo_restante'] : 0.0;
            $saldoPorPagar = max(0, $valorVehiculo - $capitalAmortizado);
            $contrato['saldo_por_pagar'] = $saldoPorPagar;
        }

        // ¡EL FIX! Elimina la referencia para evitar fugas de datos al próximo controlador/vista.
        unset($contrato); //

        // Carga el layout principal, inyectando la vista específica
        $contentView = __DIR__ . '/../views/contratos/index.php';
        $title = 'Gestión de Contratos';

        require_once __DIR__ . '/../views/layouts/app.php';
    }

    public function create() {
        // Verificar permisos
        Session::checkPermission(['administrador', 'operador']);

        // Obtener listas para los selects
        $clienteModel = new Cliente();
        $clientes = $clienteModel->all();
        $motoModel = new Moto();
        $motos = $motoModel->where(['estado' => 'activo'])->get();

        // Carga el layout principal, inyectando la vista específica
        $contentView = __DIR__ . '/../views/contratos/create.php';
        $title = 'Crear Nuevo Contrato';

        require_once __DIR__ . '/../views/layouts/app.php';
    }

    public function store() {
        // Verificar permisos
        Session::checkPermission(['administrador', 'operador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'contratos/create');
            exit;
        }

        // Validar datos requeridos
        $required = ['id_cliente', 'id_moto', 'fecha_inicio', 'cuota_mensual', 'plazo_meses', 'abono_capital_mensual', 'ganancia_mensual'];
        foreach ($required as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $_SESSION['error'] = "El campo $field es requerido.";
                header('Location: ' . BASE_URL . 'contratos/create');
                exit;
            }
        }

        try {
            // Preparar datos simplificados
            $contratoData = [
                'id_cliente' => (int)$_POST['id_cliente'],
                'id_moto' => (int)$_POST['id_moto'],
                'fecha_inicio' => $_POST['fecha_inicio'],
                'valor_vehiculo' => (float)$_POST['valor_vehiculo'],
                'cuota_mensual' => (float)$_POST['cuota_mensual'],
                'plazo_meses' => (int)$_POST['plazo_meses'],
                'abono_capital_mensual' => (float)$_POST['abono_capital_mensual'],
                'ganancia_mensual' => (float)$_POST['ganancia_mensual']
            ];

            // Crear contrato con cálculos simples
            $contratoId = Contrato::createContrato($contratoData);

            if ($contratoId) {
                // Cambiar estado de la moto a 'alquilada'
                $moto = new Moto();
                $moto->update($contratoData['id_moto'], ['estado' => 'alquilada']);

                $_SESSION['success'] = 'Contrato creado exitosamente.';
                header('Location: ' . BASE_URL . 'contratos');
            } else {
                throw new Exception('Error al crear el contrato.');
            }

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear el contrato: ' . $e->getMessage();
            header('Location: ' . BASE_URL . 'contratos/create');
        }
        exit;
    }

    public function detail($id) {
        // Verificar permisos
        Session::checkPermission(['administrador', 'operador']);

        $contratoModel = new Contrato();
        $contrato = $contratoModel->find($id);
        if (!$contrato) {
            $_SESSION['error'] = 'Contrato no encontrado.';
            header('Location: ' . BASE_URL . 'contratos');
            exit;
        }

        // Obtener información relacionada
        $clienteModel = new Cliente();
        $cliente = $clienteModel->find($contrato['id_cliente']);
        $motoModel = new Moto();
        $moto = $motoModel->find($contrato['id_moto']);

        // Obtener periodos y pagos acumulados
        $allPeriodos = PeriodoContrato::getPeriodosPorContrato($id);
        $totalPagado = PagoContrato::getTotalPagadoEnContrato($id);

        // Filtrar para mostrar solo el periodo actual y el siguiente
        $periodos = [];
        $today = date('Y-m-d');
        $currentPeriod = null;
        $nextPeriod = null;

        foreach ($allPeriodos as $periodo) {
            if ($today >= $periodo['fecha_inicio_periodo'] && $today <= $periodo['fecha_fin_periodo']) {
                $currentPeriod = $periodo;
            } elseif ($today < $periodo['fecha_inicio_periodo']) {
                if (!$nextPeriod) {
                    $nextPeriod = $periodo;
                }
            }
        }

        if ($currentPeriod) {
            $periodos[] = $currentPeriod;
        }
        if ($nextPeriod) {
            $periodos[] = $nextPeriod;
        }

        // Obtener historial de pagos
        $pagoModel = new PagoContrato();
        $pagos = $pagoModel->getPagosPorContrato($id);

        // Calcular información adicional simplificada
        $saldoRestante = $contrato['saldo_restante'];
        $pagosRealizadosMes = Contrato::calcularPagosMesActual($id);

        // Hacer variables disponibles para la vista
        extract(compact('contrato', 'cliente', 'moto', 'periodos', 'totalPagado', 'pagos', 'saldoRestante', 'pagosRealizadosMes'));

        // Carga el layout principal, inyectando la vista específica
        $contentView = __DIR__ . '/../views/contratos/detail.php';
        $title = 'Detalle del Contrato';

        require_once __DIR__ . '/../views/layouts/app.php';
    }

    /**
     * API endpoint para calcular cuota mensual simple
     */
    public function calcularCuotas() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Método no permitido']);
            exit;
        }

        $valorVehiculo = (float)($_POST['valor_vehiculo'] ?? 0);
        $plazoMeses = (int)($_POST['plazo_meses'] ?? 0);

        if ($valorVehiculo <= 0 || $plazoMeses <= 0) {
            echo json_encode(['error' => 'Datos inválidos']);
            exit;
        }

        try {
            $cuotaMensual = $valorVehiculo / $plazoMeses;

            echo json_encode([
                'success' => true,
                'cuota_mensual' => $cuotaMensual,
                'total_a_pagar' => $valorVehiculo
            ]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    public function cerrarPeriodo($idContrato, $idPeriodo) {

        header('Content-Type: application/json');

        // Verificar autenticación y permisos sin redireccionar
        if (!Session::isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Sesión expirada. Por favor, inicie sesión nuevamente.']);
            exit;
        }

        if (!in_array(Session::getUserRole(), ['administrador', 'operador'])) {
            echo json_encode(['success' => false, 'message' => 'No tiene permisos para realizar esta acción.']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        try {
            $contratoModel = new Contrato();
            $resultado = $contratoModel->cerrarPeriodo($idContrato, $idPeriodo);

            if ($resultado && !isset($resultado['error'])) {
                echo json_encode(['success' => true, 'message' => 'Período cerrado exitosamente']);
            } else {
                $message = isset($resultado['error']) ? $resultado['error'] : 'No se pudo cerrar el período';
                echo json_encode(['success' => false, 'message' => $message]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}
