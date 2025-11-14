<?php
// /app/controllers/PagoController.php

require_once __DIR__ . '/../models/Contrato.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Moto.php';
require_once __DIR__ . '/../models/Pago.php';
require_once __DIR__ . '/../models/PagoContrato.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/ImageHelper.php';

class PagoController {
    
    private $contratoModel;

    /**
     * Marcar un día como no_pago (genera el día si no existe)
     */
    public function marcarNoPagoDia() {
        Session::checkPermission(['administrador', 'operador', 'contador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }

        $idContrato = (int)($_POST['id_contrato'] ?? 0);
        $idPeriodo = (int)($_POST['id_periodo'] ?? 0);
        $fecha = trim($_POST['fecha'] ?? '');
        $observacion = trim($_POST['observacion'] ?? '');

        if (!$idContrato || !$idPeriodo || !$fecha) {
            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=Datos incompletos para marcar no pago.");
            exit;
        }

        try {
            // Generar el día si no existe (con monto 0 y estado no_pago)
            $db = Database::getInstance()->getConnection();

            // Intentar actualizar primero
            $updateStmt = $db->prepare("
                UPDATE pagos_diarios_contrato
                SET estado_dia = 'no_pago',
                    observacion = :observacion,
                    updated_at = NOW()
                WHERE id_contrato = :id_contrato AND id_periodo = :id_periodo AND fecha = :fecha
            ");

            $result = $updateStmt->execute([
                ':observacion' => $observacion,
                ':id_contrato' => $idContrato,
                ':id_periodo' => $idPeriodo,
                ':fecha' => $fecha
            ]);

            // Si no se actualizó ninguna fila, insertar nuevo registro
            if ($result && $updateStmt->rowCount() == 0) {
                $insertStmt = $db->prepare("
                    INSERT INTO pagos_diarios_contrato
                    (id_contrato, id_periodo, fecha, es_domingo, estado_dia, monto_pagado, observacion, created_at, updated_at)
                    VALUES (:id_contrato, :id_periodo, :fecha, :es_domingo, 'no_pago', 0.00, :observacion, NOW(), NOW())
                ");
                $esDomingo = (date('w', strtotime($fecha)) == 0) ? 1 : 0;
                $insertStmt->execute([
                    ':id_contrato' => $idContrato,
                    ':id_periodo' => $idPeriodo,
                    ':fecha' => $fecha,
                    ':es_domingo' => $esDomingo,
                    ':observacion' => $observacion
                ]);
            }

            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?success=Día ${fecha} marcado como no pago.");
            exit;
        } catch (Exception $e) {
            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=" . urlencode('Error al marcar no pago: ' . $e->getMessage()));
            exit;
        }
    }

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
        $fechaPago = !empty($_POST['fecha_pago']) ? $_POST['fecha_pago'] : date('Y-m-d');

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

        // Procesar comprobante si se subió
        $comprobantePath = null;
        if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] === UPLOAD_ERR_OK) {
            try {
                $file = $_FILES['comprobante'];

                // Validar tipo de archivo
                if (!ImageHelper::isValidImageType($file['name'])) {
                    header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=Tipo de archivo no válido. Solo se permiten imágenes JPG, PNG y GIF.");
                    exit;
                }

                // Validar tamaño del archivo (máximo 5MB)
                if (!ImageHelper::isValidFileSize($file['size'])) {
                    header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=El archivo es demasiado grande. Máximo 5MB permitido.");
                    exit;
                }

                // Generar nombre único para el archivo
                $filename = ImageHelper::generateUniqueFilename($file['name']);
                $uploadDir = __DIR__ . '/../../public/uploads/comprobantes/';
                $destinationPath = $uploadDir . $filename;

                // Redimensionar y guardar imagen
                ImageHelper::resizeImage($file['tmp_name'], $destinationPath);

                // Guardar ruta relativa para la base de datos
                $comprobantePath = 'uploads/comprobantes/' . $filename;

            } catch (Exception $e) {
                header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=" . urlencode('Error al procesar la imagen: ' . $e->getMessage()));
                exit;
            }
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
                'fecha_pago' => $fechaPago,
                'monto_pago' => $montoPago,
                'concepto' => $concepto,
                'comprobante' => $comprobantePath
            ]);

            // Nota: El saldo restante se actualiza solo al cerrar el período, no con cada pago individual

            $db->commit();

            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?success=Pago de $" . number_format($montoPago, 2) . " registrado.");
            exit;
        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            // Eliminar archivo si se subió pero falló el registro
            if ($comprobantePath && file_exists(__DIR__ . '/../../public/' . $comprobantePath)) {
                unlink(__DIR__ . '/../../public/' . $comprobantePath);
            }
            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=" . urlencode('Error al registrar pago: ' . $e->getMessage()));
            exit;
        }
    }

    /**
     * Cerrar periodo y aplicar abonos (solo si la cuota está completa)
     */
    public function cerrarPeriodo($idContrato, $idPeriodo) {
        Session::checkPermission(['administrador', 'operador']);

        try {
            // Verificar que el período tenga la cuota completa antes de cerrar
            $periodoModel = new PeriodoContrato();
            $periodo = $periodoModel->find($idPeriodo);

            if (!$periodo) {
                header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=Período no encontrado.");
                exit;
            }

            // Obtener la cuota mensual del contrato
            $contrato = $this->contratoModel->find($idContrato);
            $cuotaMensual = $contrato['cuota_mensual'];

            // Verificar si la cuota acumulada alcanza la cuota mensual
            if ($periodo['cuota_acumulada'] < $cuotaMensual) {
                header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=No se puede cerrar el período. La cuota acumulada ($" . number_format($periodo['cuota_acumulada'], 2) . ") es menor a la cuota mensual ($" . number_format($cuotaMensual, 2) . ").");
                exit;
            }

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

    /**
     * Mostrar formulario para editar un pago
     */
    public function edit($idPago) {
        Session::checkPermission(['administrador', 'operador', 'contador']);

        $pagoModel = new PagoContrato();
        $pago = $pagoModel->find($idPago);
        if (!$pago) {
            $_SESSION['error'] = 'Pago no encontrado.';
            header('Location: ' . BASE_URL . 'contratos');
            exit;
        }

        // Obtener información relacionada
        $contrato = $this->contratoModel->find($pago['id_contrato']);
        $clienteModel = new Cliente();
        $cliente = $clienteModel->find($contrato['id_cliente']);
        $motoModel = new Moto();
        $moto = $motoModel->find($contrato['id_moto']);

        // Hacer variables disponibles para la vista
        extract(compact('pago', 'contrato', 'cliente', 'moto'));

        // Carga el layout principal, inyectando la vista específica
        $contentView = __DIR__ . '/../views/pagos/edit.php';
        $title = 'Editar Pago';

        require_once __DIR__ . '/../views/layouts/app.php';
    }

    /**
     * Actualizar un pago
     */
    public function update($idPago) {
        Session::checkPermission(['administrador', 'operador', 'contador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }

        $montoPago = floatval($_POST['monto_pago']);
        $concepto = trim($_POST['concepto'] ?? 'Pago del cliente.');
        $fechaPago = !empty($_POST['fecha_pago']) ? $_POST['fecha_pago'] : date('Y-m-d');
        $idUsuario = Session::get('user_id');

        if ($montoPago <= 0) {
            header("Location: " . BASE_URL . "pagos/edit/{$idPago}?error=El monto del pago debe ser positivo.");
            exit;
        }

        $pagoModel = new PagoContrato();
        $pago = $pagoModel->find($idPago);
        if (!$pago) {
            header("Location: " . BASE_URL . "contratos?error=Pago no encontrado.");
            exit;
        }

        // Procesar comprobante si se subió uno nuevo
        $comprobantePath = $pago['comprobante']; // Mantener el actual por defecto
        if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] === UPLOAD_ERR_OK) {
            try {
                $file = $_FILES['comprobante'];

                // Validar tipo de archivo
                if (!ImageHelper::isValidImageType($file['name'])) {
                    header("Location: " . BASE_URL . "pagos/edit/{$idPago}?error=Tipo de archivo no válido. Solo se permiten imágenes JPG, PNG y GIF.");
                    exit;
                }

                // Validar tamaño del archivo (máximo 5MB)
                if (!ImageHelper::isValidFileSize($file['size'])) {
                    header("Location: " . BASE_URL . "pagos/edit/{$idPago}?error=El archivo es demasiado grande. Máximo 5MB permitido.");
                    exit;
                }

                // Generar nombre único para el archivo
                $filename = ImageHelper::generateUniqueFilename($file['name']);
                $uploadDir = __DIR__ . '/../../public/uploads/comprobantes/';
                $destinationPath = $uploadDir . $filename;

                // Redimensionar y guardar imagen
                ImageHelper::resizeImage($file['tmp_name'], $destinationPath);

                // Guardar ruta relativa para la base de datos
                $comprobantePath = 'uploads/comprobantes/' . $filename;

                // Eliminar el comprobante anterior si existía
                if (!empty($pago['comprobante']) && file_exists(__DIR__ . '/../../public/' . $pago['comprobante'])) {
                    unlink(__DIR__ . '/../../public/' . $pago['comprobante']);
                }

            } catch (Exception $e) {
                header("Location: " . BASE_URL . "pagos/edit/{$idPago}?error=" . urlencode('Error al procesar la imagen: ' . $e->getMessage()));
                exit;
            }
        }

        try {
            $pagoModel->updatePago($idPago, [
                'fecha_pago' => $fechaPago,
                'monto_pago' => $montoPago,
                'concepto' => $concepto,
                'id_usuario' => $idUsuario,
                'comprobante' => $comprobantePath
            ]);

            header("Location: " . BASE_URL . "contratos/detail/{$pago['id_contrato']}?success=Pago actualizado correctamente.");
            exit;
        } catch (Exception $e) {
            // Eliminar archivo si se subió pero falló la actualización
            if ($comprobantePath !== $pago['comprobante'] && file_exists(__DIR__ . '/../../public/' . $comprobantePath)) {
                unlink(__DIR__ . '/../../public/' . $comprobantePath);
            }
            header("Location: " . BASE_URL . "pagos/edit/{$idPago}?error=" . urlencode('Error al actualizar pago: ' . $e->getMessage()));
            exit;
        }
    }

    /**
     * Eliminar un pago
     */
    public function delete($idPago) {
        Session::checkPermission(['administrador', 'operador']);

        $pagoModel = new PagoContrato();
        $pago = $pagoModel->find($idPago);
        if (!$pago) {
            header("Location: " . BASE_URL . "contratos?error=Pago no encontrado.");
            exit;
        }

        $idContrato = $pago['id_contrato'];

        try {
            $pagoModel->deletePago($idPago);
            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?success=Pago eliminado correctamente.");
            exit;
        } catch (Exception $e) {
            header("Location: " . BASE_URL . "contratos/detail/{$idContrato}?error=" . urlencode('Error al eliminar pago: ' . $e->getMessage()));
            exit;
        }
    }
}
