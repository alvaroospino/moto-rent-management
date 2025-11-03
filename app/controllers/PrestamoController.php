<?php
// /app/controllers/PrestamoController.php

require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../models/Prestamo.php';
require_once __DIR__ . '/../models/Contrato.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Moto.php';

class PrestamoController {

    /**
     * Mostrar lista de préstamos de un contrato
     */
    public function index($idContrato) {
        // Verificar permisos
        Session::checkPermission(['administrador', 'operador']);

        // Verificar que el contrato existe
        $contratoModel = new Contrato();
        $contrato = $contratoModel->find($idContrato);
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

        // Obtener préstamos del contrato
        $prestamos = Prestamo::getPrestamosPorContrato($idContrato);

        // Calcular totales
        $totalPrestamos = Prestamo::getTotalPrestamosActivos($idContrato);
        $totalSaldoPrestamos = Prestamo::getTotalSaldoPrestamos($idContrato);

        // Hacer variables disponibles para la vista
        extract(compact('contrato', 'cliente', 'moto', 'prestamos', 'totalPrestamos', 'totalSaldoPrestamos'));

        // Carga el layout principal, inyectando la vista específica
        $contentView = __DIR__ . '/../views/prestamos/index.php';
        $title = 'Préstamos del Contrato';

        require_once __DIR__ . '/../views/layouts/app.php';
    }

    /**
     * Mostrar formulario para crear préstamo
     */
    public function create($idContrato) {
        // Verificar permisos
        Session::checkPermission(['administrador', 'operador']);

        // Verificar que el contrato existe
        $contratoModel = new Contrato();
        $contrato = $contratoModel->find($idContrato);
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
        $contentView = __DIR__ . '/../views/prestamos/create.php';
        $title = 'Crear Nuevo Préstamo';

        require_once __DIR__ . '/../views/layouts/app.php';
    }

    /**
     * Guardar nuevo préstamo
     */
    public function store() {
        // Verificar permisos
        Session::checkPermission(['administrador', 'operador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'contratos');
            exit;
        }

        // Validar datos requeridos
        $required = ['id_contrato', 'monto_prestamo', 'fecha_prestamo'];
        foreach ($required as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                $_SESSION['error'] = "El campo $field es requerido.";
                header('Location: ' . BASE_URL . 'prestamos/create/' . $_POST['id_contrato']);
                exit;
            }
        }

        try {
            // Preparar datos
            $prestamoData = [
                'id_contrato' => (int)$_POST['id_contrato'],
                'id_usuario' => Session::get('user_id'),
                'monto_prestamo' => (float)$_POST['monto_prestamo'],
                'fecha_prestamo' => $_POST['fecha_prestamo'],
                'descripcion' => $_POST['descripcion'] ?? ''
            ];

            // Crear préstamo
            $prestamoModel = new Prestamo();
            $prestamoId = $prestamoModel->crearPrestamo($prestamoData);

            if ($prestamoId) {
                $_SESSION['success'] = 'Préstamo creado exitosamente.';
                header('Location: ' . BASE_URL . 'prestamos/' . $prestamoData['id_contrato']);
            } else {
                throw new Exception('Error al crear el préstamo.');
            }

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al crear el préstamo: ' . $e->getMessage();
            header('Location: ' . BASE_URL . 'prestamos/create/' . $_POST['id_contrato']);
        }
        exit;
    }

    /**
     * Mostrar detalles de un préstamo
     */
    public function show($idPrestamo) {
        // Verificar permisos
        Session::checkPermission(['administrador', 'operador']);

        $prestamoModel = new Prestamo();
        $prestamo = $prestamoModel->find($idPrestamo);
        if (!$prestamo) {
            $_SESSION['error'] = 'Préstamo no encontrado.';
            header('Location: ' . BASE_URL . 'contratos');
            exit;
        }

        // Obtener información del contrato
        $contratoModel = new Contrato();
        $contrato = $contratoModel->find($prestamo['id_contrato']);
        $clienteModel = new Cliente();
        $cliente = $clienteModel->find($contrato['id_cliente']);
        $motoModel = new Moto();
        $moto = $motoModel->find($contrato['id_moto']);

        // Hacer variables disponibles para la vista
        extract(compact('prestamo', 'contrato', 'cliente', 'moto'));

        // Carga el layout principal, inyectando la vista específica
        $contentView = __DIR__ . '/../views/prestamos/show.php';
        $title = 'Detalles del Préstamo';

        require_once __DIR__ . '/../views/layouts/app.php';
    }

    /**
     * Mostrar formulario para editar préstamo
     */
    public function edit($idPrestamo) {
        // Verificar permisos
        Session::checkPermission(['administrador', 'operador']);

        $prestamoModel = new Prestamo();
        $prestamo = $prestamoModel->find($idPrestamo);
        if (!$prestamo) {
            $_SESSION['error'] = 'Préstamo no encontrado.';
            header('Location: ' . BASE_URL . 'contratos');
            exit;
        }

        // Obtener información del contrato
        $contratoModel = new Contrato();
        $contrato = $contratoModel->find($prestamo['id_contrato']);
        $clienteModel = new Cliente();
        $cliente = $clienteModel->find($contrato['id_cliente']);
        $motoModel = new Moto();
        $moto = $motoModel->find($contrato['id_moto']);

        // Hacer variables disponibles para la vista
        extract(compact('prestamo', 'contrato', 'cliente', 'moto'));

        // Carga el layout principal, inyectando la vista específica
        $contentView = __DIR__ . '/../views/prestamos/edit.php';
        $title = 'Editar Préstamo';

        require_once __DIR__ . '/../views/layouts/app.php';
    }

    /**
     * Actualizar préstamo
     */
    public function update($idPrestamo) {
        // Verificar permisos
        Session::checkPermission(['administrador', 'operador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'contratos');
            exit;
        }

        $prestamoModel = new Prestamo();
        $prestamo = $prestamoModel->find($idPrestamo);
        if (!$prestamo) {
            $_SESSION['error'] = 'Préstamo no encontrado.';
            header('Location: ' . BASE_URL . 'contratos');
            exit;
        }

        try {
            // Preparar datos para actualización
            $updateData = [
                'monto_prestamo' => (float)$_POST['monto_prestamo'],
                'fecha_prestamo' => $_POST['fecha_prestamo'],
                'descripcion' => $_POST['descripcion'] ?? '',
                'actualizado_en' => date('Y-m-d H:i:s')
            ];

            // Si cambió el monto, ajustar el saldo restante proporcionalmente
            if ($updateData['monto_prestamo'] !== $prestamo['monto_prestamo']) {
                $diferencia = $updateData['monto_prestamo'] - $prestamo['monto_prestamo'];
                $updateData['saldo_restante'] = $prestamo['saldo_restante'] + $diferencia;
            }

            $prestamoModel->update($idPrestamo, $updateData);

            $_SESSION['success'] = 'Préstamo actualizado exitosamente.';
            header('Location: ' . BASE_URL . 'prestamos/' . $prestamo['id_contrato']);

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al actualizar el préstamo: ' . $e->getMessage();
            header('Location: ' . BASE_URL . 'prestamos/edit/' . $idPrestamo);
        }
        exit;
    }

    /**
     * Eliminar préstamo (solo si no tiene pagos aplicados)
     */
    public function delete($idPrestamo) {
        // Verificar permisos
        Session::checkPermission(['administrador']);

        $prestamoModel = new Prestamo();
        $prestamo = $prestamoModel->find($idPrestamo);
        if (!$prestamo) {
            $_SESSION['error'] = 'Préstamo no encontrado.';
            header('Location: ' . BASE_URL . 'contratos');
            exit;
        }

        // Verificar que no tenga pagos aplicados (saldo_restante = monto_prestamo)
        if ($prestamo['saldo_restante'] < $prestamo['monto_prestamo']) {
            $_SESSION['error'] = 'No se puede eliminar un préstamo que ya tiene pagos aplicados.';
            header('Location: ' . BASE_URL . 'prestamos/' . $prestamo['id_contrato']);
            exit;
        }

        try {
            $prestamoModel->delete($idPrestamo);
            $_SESSION['success'] = 'Préstamo eliminado exitosamente.';
            header('Location: ' . BASE_URL . 'prestamos/' . $prestamo['id_contrato']);

        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar el préstamo: ' . $e->getMessage();
            header('Location: ' . BASE_URL . 'prestamos/' . $prestamo['id_contrato']);
        }
        exit;
    }
}
