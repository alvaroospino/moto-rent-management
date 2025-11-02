<?php
// /app/controllers/MovimientoPersonalController.php

require_once __DIR__ . '/../models/MovimientoPersonal.php';
require_once __DIR__ . '/../core/Session.php';

class MovimientoPersonalController {

    private $movimientoModel;

    public function __construct() {
        $this->movimientoModel = new MovimientoPersonal();
    }

    // 1. Mostrar listado de movimientos personales
    public function index() {
        Session::checkPermission(['administrador', 'operador', 'contador']);

        $idUsuario = Session::get('user_id');
        $movimientos = $this->movimientoModel->getAllWithDetails($idUsuario);
        $totales = $this->movimientoModel->getTotales($idUsuario);

        $data = [
            'title' => 'Control de Gastos Personales',
            'movimientos' => $movimientos,
            'totales' => $totales,
        ];

        $contentView = __DIR__ . '/../views/movimientos_personales/index.php';
        $title = $data['title'];
        $movimientos = $data['movimientos'];
        $totales = $data['totales'];
        require_once __DIR__ . '/../views/layouts/app.php';
    }

    // 2. Mostrar formulario para registrar movimiento personal
    public function create() {
        Session::checkPermission(['administrador', 'operador', 'contador']);

        $data = [
            'title' => 'Registrar Movimiento Personal',
        ];

        $contentView = __DIR__ . '/../views/movimientos_personales/create.php';
        $title = $data['title'];
        require_once __DIR__ . '/../views/layouts/app.php';
    }

    // 3. Procesar el formulario de registro de movimiento
    public function store() {
        Session::checkPermission(['administrador', 'operador', 'contador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'movimientos-personales/create');
            exit;
        }

        // Preparación de datos
        $data = [
            'id_usuario' => Session::get('user_id'),
            'tipo' => $_POST['tipo'],
            'fecha_movimiento' => $_POST['fecha_movimiento'],
            'monto' => floatval($_POST['monto']),
            'descripcion' => trim($_POST['descripcion']),
        ];

        try {
            $this->movimientoModel->registrarMovimiento($data);
            header('Location: ' . BASE_URL . 'movimientos-personales?success=Movimiento registrado correctamente.');
            exit;
        } catch (\Exception $e) {
            header('Location: ' . BASE_URL . 'movimientos-personales/create?error=Error al registrar el movimiento: ' . $e->getMessage());
            exit;
        }
    }

    // 4. Mostrar detalles de un movimiento específico
    public function show($id) {
        Session::checkPermission(['administrador', 'operador', 'contador']);

        $movimiento = $this->movimientoModel->find($id);
        if (!$movimiento) {
            header('Location: ' . BASE_URL . 'movimientos-personales?error=Movimiento no encontrado.');
            exit;
        }

        // Verificar que el movimiento pertenece al usuario actual
        if ($movimiento['id_usuario'] != Session::get('user_id')) {
            header('Location: ' . BASE_URL . 'movimientos-personales?error=No tienes permisos para ver este movimiento.');
            exit;
        }

        $data = [
            'title' => 'Detalles del Movimiento Personal',
            'movimiento' => $movimiento,
        ];

        $contentView = __DIR__ . '/../views/movimientos_personales/show.php';
        $title = $data['title'];
        $movimiento = $data['movimiento'];
        require_once __DIR__ . '/../views/layouts/app.php';
    }

    // 5. Mostrar formulario para editar movimiento
    public function edit($id) {
        Session::checkPermission(['administrador', 'operador', 'contador']);

        $movimiento = $this->movimientoModel->find($id);
        if (!$movimiento) {
            header('Location: ' . BASE_URL . 'movimientos-personales?error=Movimiento no encontrado.');
            exit;
        }

        // Verificar que el movimiento pertenece al usuario actual
        if ($movimiento['id_usuario'] != Session::get('user_id')) {
            header('Location: ' . BASE_URL . 'movimientos-personales?error=No tienes permisos para editar este movimiento.');
            exit;
        }

        $data = [
            'title' => 'Editar Movimiento Personal',
            'movimiento' => $movimiento,
        ];

        $contentView = __DIR__ . '/../views/movimientos_personales/edit.php';
        $title = $data['title'];
        $movimiento = $data['movimiento'];
        require_once __DIR__ . '/../views/layouts/app.php';
    }

    // 6. Actualizar movimiento
    public function update($id) {
        Session::checkPermission(['administrador', 'operador', 'contador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'movimientos-personales/edit/' . $id);
            exit;
        }

        // Verificar que el movimiento existe y pertenece al usuario
        $movimiento = $this->movimientoModel->find($id);
        if (!$movimiento || $movimiento['id_usuario'] != Session::get('user_id')) {
            header('Location: ' . BASE_URL . 'movimientos-personales?error=Movimiento no encontrado o sin permisos.');
            exit;
        }

        $data = [
            'tipo' => $_POST['tipo'],
            'fecha_movimiento' => $_POST['fecha_movimiento'],
            'monto' => floatval($_POST['monto']),
            'descripcion' => trim($_POST['descripcion']),
        ];

        try {
            $this->movimientoModel->update($id, $data);
            header('Location: ' . BASE_URL . 'movimientos-personales?success=Movimiento actualizado correctamente.');
            exit;
        } catch (\Exception $e) {
            header('Location: ' . BASE_URL . 'movimientos-personales/edit/' . $id . '?error=Error al actualizar el movimiento: ' . $e->getMessage());
            exit;
        }
    }

    // 7. Eliminar movimiento
    public function destroy($id) {
        Session::checkPermission(['administrador', 'operador', 'contador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'movimientos-personales?error=Método no permitido.');
            exit;
        }

        // Verificar que el movimiento existe y pertenece al usuario
        $movimiento = $this->movimientoModel->find($id);
        if (!$movimiento || $movimiento['id_usuario'] != Session::get('user_id')) {
            header('Location: ' . BASE_URL . 'movimientos-personales?error=Movimiento no encontrado o sin permisos.');
            exit;
        }

        try {
            $this->movimientoModel->delete($id);
            header('Location: ' . BASE_URL . 'movimientos-personales?success=Movimiento eliminado correctamente.');
            exit;
        } catch (\Exception $e) {
            header('Location: ' . BASE_URL . 'movimientos-personales?error=Error al eliminar el movimiento: ' . $e->getMessage());
            exit;
        }
    }
}
