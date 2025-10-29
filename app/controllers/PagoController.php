<?php
// /app/controllers/PagoController.php

require_once __DIR__ . '/../models/Contrato.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Moto.php';
require_once __DIR__ . '/../models/Pago.php';
require_once __DIR__ . '/../core/Session.php';

class PagoController {
    
    private $contratoModel;

    public function __construct() {
        $this->contratoModel = new Contrato();
    }

    /**
     * Mostrar formulario para registrar un pago
     */
    public function create($idContrato) {
        Session::checkPermission(['administrador', 'operador', 'contador']);

        $contrato = $this->contratoModel->find($idContrato);
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

        // Hacer variables disponibles para la vista
        extract(compact('contrato', 'cliente', 'moto'));

        // Carga el layout principal, inyectando la vista específica
        $contentView = __DIR__ . '/../views/pagos/create.php';
        $title = 'Registrar Pago';

        require_once __DIR__ . '/../views/layouts/app.php';
    }

    /**
     * Lógica para registrar un pago simple (Abono al contrato mensual acumulado).
     */
    public function registrarPago() {
        Session::checkPermission(['administrador', 'operador', 'contador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }

        $idContrato = (int)$_POST['id_contrato'];
        $montoPago = floatval($_POST['monto_pago']);
        $concepto = trim($_POST['concepto'] ?? 'Pago del cliente.');
        $idUsuario = Session::get('user_id');

        if ($montoPago <= 0) {
            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=El monto del pago debe ser positivo.");
            exit;
        }

        $contrato = $this->contratoModel->find($idContrato);
        if (!$contrato) {
            header("Location: " . BASE_URL . "contratos?error=Contrato no encontrado.");
            exit;
        }

        // Obtener periodo actual abierto
        $periodoActual = PeriodoContrato::getPeriodoActual($idContrato);
        if (!$periodoActual) {
            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=No hay periodo abierto para este contrato.");
            exit;
        }

        // Registrar el pago en el periodo actual
        $db = Database::getInstance()->getConnection();
        try {
            $db->beginTransaction();

            $pagoModel = new PagoContrato();
            $pagoId = $pagoModel->registrarPago([
                'id_contrato' => $idContrato,
                'id_periodo' => $periodoActual['id_periodo'],
                'id_usuario' => $idUsuario,
                'fecha_pago' => date('Y-m-d'),
                'monto_pago' => $montoPago,
                'concepto' => $concepto
            ]);

            // Nota: El saldo restante se actualiza solo al cerrar el período, no con cada pago individual

            $db->commit();

            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?success=Pago de $" . number_format($montoPago, 2) . " registrado.");
            exit;
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=" . urlencode('Error al registrar pago: ' . $e->getMessage()));
            exit;
        }
    }

    /**
     * Cerrar periodo y aplicar abonos
     */
    public function cerrarPeriodo($idContrato, $idPeriodo) {
        Session::checkPermission(['administrador', 'operador']);

        try {
            $abonos = $this->contratoModel->cerrarPeriodo($idContrato, $idPeriodo);

            // Verificar si hubo error en el cierre del periodo
            if (isset($abonos['error'])) {
                header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=" . urlencode($abonos['error']));
                exit;
            }

            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?success=Periodo cerrado y abonos aplicados.");
        } catch (Exception $e) {
            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=" . urlencode('Error al cerrar periodo: ' . $e->getMessage()));
        }
        exit;
    }

    /**
     * Lógica para registrar un préstamo adicional (Incrementa saldo restante).
     */
    public function registrarPrestamo() {
        Session::checkPermission(['administrador', 'operador']); // Solo operadores/admins pueden prestar

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }

        $idContrato = (int)$_POST['id_contrato'];
        $montoPrestamo = floatval($_POST['monto_prestamo']);
        $concepto = trim($_POST['concepto_prestamo']);
        $idUsuario = Session::get('user_id');

        if ($montoPrestamo <= 0) {
            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=El monto del préstamo debe ser positivo.");
            exit;
        }

        $contrato = $this->contratoModel->find($idContrato);
        if (!$contrato) {
            header("Location: " . BASE_URL . "contratos?error=Contrato no encontrado.");
            exit;
        }

        // Registrar el préstamo como un pago adicional en el periodo actual
        $periodoActual = PeriodoContrato::getPeriodoActual($idContrato);
        if (!$periodoActual) {
            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=No hay periodo abierto para este contrato.");
            exit;
        }

        $db = Database::getInstance()->getConnection();
        try {
            $db->beginTransaction();

            // Registrar el préstamo como pago adicional
            $pagoModel = new PagoContrato();
            $pagoModel->registrarPago([
                'id_contrato' => $idContrato,
                'id_periodo' => $periodoActual['id_periodo'],
                'id_usuario' => $idUsuario,
                'fecha_pago' => date('Y-m-d'),
                'monto_pago' => $montoPrestamo,
                'concepto' => "Préstamo adicional: " . $concepto
            ]);

            // Actualizar saldo restante (aumenta el monto adeudado)
            $nuevoSaldo = $contrato['saldo_restante'] + $montoPrestamo;
            $this->contratoModel->update($idContrato, ['saldo_restante' => $nuevoSaldo]);

            $db->commit();

            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?success=Préstamo de $" . number_format($montoPrestamo, 2) . " registrado.");
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=" . urlencode('Error al registrar préstamo: ' . $e->getMessage()));
        }
        exit;
    }
}