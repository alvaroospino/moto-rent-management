<?php
// /app/controllers/ContratoController.php

require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/Contrato.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Moto.php';
require_once __DIR__ . '/../models/PeriodoContrato.php';
require_once __DIR__ . '/../models/PagoContrato.php';
require_once __DIR__ . '/../models/Prestamo.php';

class ContratoController {

    /**
     * Obtiene el tipo de motor de la base de datos
     */
    private function getDatabaseType() {
        $db = Database::getInstance()->getConnection();
        return $db->getAttribute(PDO::ATTR_DRIVER_NAME);
    }

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

        // Estadísticas para el dashboard de contratos
        $stats = [
            'total_contratos_activos' => count($contratos),
            'valor_total_contratos' => array_sum(array_column($contratos, 'valor_vehiculo')),
            'saldo_total_por_cobrar' => array_sum(array_column($contratos, 'saldo_por_pagar')),
            'ingreso_mensual_estimado' => array_sum(array_column($contratos, 'cuota_mensual')),
            'contratos_proximos_vencer' => count(Contrato::getContratosProximosVencer()),
            'pagos_mes_actual' => Contrato::calcularPagosMesActualTotal()
        ];

        // Datos para gráficos
        $chartData = [
            'estado_contratos' => $this->getEstadoContratosData(),
            'pagos_mensuales' => Contrato::getIngresosUltimos6Meses(),
            'contratos_por_mes' => $this->getContratosPorMesData()
        ];

        // Alertas importantes
        $alertas = [
            'contratos_venciendo' => Contrato::getContratosProximosVencer(),
            'contratos_sin_pagos' => $this->getContratosSinPagosRecientes()
        ];

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

        // Filtrar para mostrar todos los periodos abiertos (disponibles para cerrar)
        $periodos = array_filter($allPeriodos, function($periodo) {
            return $periodo['estado_periodo'] === 'abierto';
        });

        // Preparar datos del historial completo para el modal
        $historialPeriodos = [];
        $pagoModel = new PagoContrato();
        foreach ($allPeriodos as $periodo) {
            $diasPeriodo = PeriodoContrato::getDiasPeriodo($id, $periodo['id_periodo']);
            $habiles = 0; $pagados = 0; $pendientes = 0; $nopago = 0; $totalDia = 0;
            foreach ($diasPeriodo as $d) {
                if ((int)$d['es_domingo'] === 1) continue;
                $habiles++;
                $totalDia += (float)$d['monto_pagado'];
                switch ($d['estado_dia']) {
                    case 'pagado': $pagados++; break;
                    case 'no_pago': $nopago++; break;
                    default: $pendientes++; break;
                }
            }

            // Obtener pagos realizados en este periodo específico
            $pagosPeriodo = $pagoModel->where(['id_periodo' => $periodo['id_periodo']])
                                      ->orderBy('fecha_pago', 'ASC')
                                      ->get();

            $historialPeriodos[] = [
                'periodo' => $periodo,
                'dias' => $diasPeriodo,
                'pagos' => $pagosPeriodo,
                'metricas' => [
                    'habiles' => $habiles,
                    'pagados' => $pagados,
                    'pendientes' => $pendientes,
                    'nopago' => $nopago,
                    'total_pagado' => $totalDia
                ]
            ];
        }

        // Obtener historial de pagos
        $pagoModel = new PagoContrato();
        $pagos = $pagoModel->getPagosPorContrato($id);

        // Calcular información adicional simplificada
        $saldoRestante = $contrato['saldo_restante'];
        $pagosRealizadosMes = Contrato::calcularPagosMesActual($id);

        // Hacer variables disponibles para la vista
        extract(compact('contrato', 'cliente', 'moto', 'periodos', 'totalPagado', 'pagos', 'saldoRestante', 'pagosRealizadosMes', 'historialPeriodos'));

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

    /**
     * Obtener datos para gráfico de estado de contratos
     */
    private function getEstadoContratosData() {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT estado, COUNT(*) as cantidad FROM contratos GROUP BY estado";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener datos de contratos por mes
     */
    private function getContratosPorMesData() {
        $dbType = $this->getDatabaseType();
        if ($dbType === 'pgsql') {
            $sql = "
                SELECT
                    TO_CHAR(fecha_inicio, 'YYYY-MM') as mes,
                    COUNT(*) as cantidad
                FROM contratos
                WHERE fecha_inicio >= (CURRENT_DATE - INTERVAL '6 months')
                GROUP BY TO_CHAR(fecha_inicio, 'YYYY-MM')
                ORDER BY mes ASC
            ";
        } else {
            $sql = "
                SELECT
                    DATE_FORMAT(fecha_inicio, '%Y-%m') as mes,
                    COUNT(*) as cantidad
                FROM contratos
                WHERE fecha_inicio >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(fecha_inicio, '%Y-%m')
                ORDER BY mes ASC
            ";
        }
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener contratos sin pagos recientes
     */
    private function getContratosSinPagosRecientes() {
        $dbType = $this->getDatabaseType();
        if ($dbType === 'pgsql') {
            $sql = "
                SELECT c.id_contrato, c.fecha_inicio, c.estado, c.valor_vehiculo, c.cuota_mensual, c.saldo_restante,
                       cl.nombre_completo, m.placa, m.marca,
                       CURRENT_DATE - COALESCE(MAX(pc.fecha_pago), c.fecha_inicio) as dias_sin_pago
                FROM contratos c
                JOIN clientes cl ON c.id_cliente = cl.id_cliente
                JOIN motos m ON c.id_moto = m.id_moto
                LEFT JOIN pagos_contrato pc ON c.id_contrato = pc.id_contrato
                WHERE c.estado = 'activo'
                GROUP BY c.id_contrato, c.fecha_inicio, c.estado, c.valor_vehiculo, c.cuota_mensual, c.saldo_restante, cl.nombre_completo, m.placa, m.marca
                HAVING CURRENT_DATE - COALESCE(MAX(pc.fecha_pago), c.fecha_inicio) > 30
                ORDER BY dias_sin_pago DESC
                LIMIT 5
            ";
        } else {
            $sql = "
                SELECT c.*, cl.nombre_completo, m.placa, m.marca,
                       DATEDIFF(CURDATE(), COALESCE(MAX(pc.fecha_pago), c.fecha_inicio)) as dias_sin_pago
                FROM contratos c
                JOIN clientes cl ON c.id_cliente = cl.id_cliente
                JOIN motos m ON c.id_moto = m.id_moto
                LEFT JOIN pagos_contrato pc ON c.id_contrato = pc.id_contrato
                WHERE c.estado = 'activo'
                GROUP BY c.id_contrato
                HAVING dias_sin_pago > 30
                ORDER BY dias_sin_pago DESC
                LIMIT 5
            ";
        }
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function details($id) {
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

        // Calcular utilidad del contrato
        $utilidad = Contrato::getUtilidadContrato($id);

        // Calcular total a pagar (cuota mensual * plazo meses)
        $total_a_pagar = $contrato['cuota_mensual'] * $contrato['plazo_meses'];

        // Obtener total de préstamos activos del contrato
        $total_prestamos = Prestamo::getTotalPrestamosActivos($id);

        // El total a pagar real incluye el valor del contrato + préstamos
        $total_a_pagar_real = $total_a_pagar + $total_prestamos;

        // Calcular total pagado hasta ahora
        $total_pagado = PagoContrato::getTotalPagadoEnContrato($id);

        // Calcular saldo restante de la deuda total
        $saldo_restante_deuda_total = $total_a_pagar_real - $total_pagado;

        // Hacer variables disponibles para la vista
        extract(compact('contrato', 'cliente', 'moto', 'utilidad', 'total_a_pagar', 'total_prestamos', 'total_pagado', 'saldo_restante_deuda_total'));

        // Carga el layout principal, inyectando la vista específica
        $contentView = __DIR__ . '/../views/contratos/details.php';
        $title = 'Detalles Completos del Contrato';

        require_once __DIR__ . '/../views/layouts/app.php';
    }

    public function edit($id) {
        // Verificar permisos
        Session::checkPermission(['administrador', 'operador']);

        $contratoModel = new Contrato();
        $contrato = $contratoModel->find($id);
        if (!$contrato) {
            $_SESSION['error'] = 'Contrato no encontrado.';
            header('Location: ' . BASE_URL . 'contratos');
            exit;
        }

        // Solo permitir editar contratos activos
        if ($contrato['estado'] !== 'activo') {
            $_SESSION['error'] = 'Solo se pueden editar contratos activos.';
            header('Location: ' . BASE_URL . 'contratos/details/' . $id);
            exit;
        }

        // Obtener información relacionada
        $clienteModel = new Cliente();
        $cliente = $clienteModel->find($contrato['id_cliente']);
        $motoModel = new Moto();
        $moto = $motoModel->find($contrato['id_moto']);

        // Hacer variables disponibles para la vista
        extract(compact('contrato', 'cliente', 'moto'));

        // Carga el layout principal, inyectando la vista específica
        $contentView = __DIR__ . '/../views/contratos/edit.php';
        $title = 'Editar Contrato';

        require_once __DIR__ . '/../views/layouts/app.php';
    }

    public function update($id) {
        // Verificar permisos
        Session::checkPermission(['administrador', 'operador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'contratos');
            exit;
        }

        $contratoModel = new Contrato();
        $contrato = $contratoModel->find($id);
        if (!$contrato) {
            $_SESSION['error'] = 'Contrato no encontrado.';
            header('Location: ' . BASE_URL . 'contratos');
            exit;
        }

        // Solo permitir editar contratos activos
        if ($contrato['estado'] !== 'activo') {
            $_SESSION['error'] = 'Solo se pueden editar contratos activos.';
            header('Location: ' . BASE_URL . 'contratos/details/' . $id);
            exit;
        }

        // Validar datos requeridos
        $required = ['valor_vehiculo', 'cuota_mensual', 'abono_capital_mensual', 'ganancia_mensual'];
        foreach ($required as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $_SESSION['error'] = "El campo $field es requerido.";
                header('Location: ' . BASE_URL . 'contratos/edit/' . $id);
                exit;
            }
        }

        try {
            // Preparar datos para actualización
            $updateData = [
                'valor_vehiculo' => (float)$_POST['valor_vehiculo'],
                'cuota_mensual' => (float)$_POST['cuota_mensual'],
                'abono_capital_mensual' => (float)$_POST['abono_capital_mensual'],
                'ganancia_mensual' => (float)$_POST['ganancia_mensual']
            ];

            // Agregar observaciones si se proporcionaron
            if (isset($_POST['observaciones'])) {
                $updateData['observaciones'] = trim($_POST['observaciones']);
            }

            // Actualizar contrato
            $contratoModel->update($id, $updateData);

            $_SESSION['success'] = 'Contrato actualizado exitosamente.';
            header('Location: ' . BASE_URL . 'contratos/details/' . $id);

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar el contrato: ' . $e->getMessage();
            header('Location: ' . BASE_URL . 'contratos/edit/' . $id);
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
