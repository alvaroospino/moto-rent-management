<?php
// /app/controllers/GastoController.php

require_once __DIR__ . '/../models/Gasto.php';
require_once __DIR__ . '/../models/Moto.php';
require_once __DIR__ . '/../core/Session.php';

class GastoController {
    
    private $gastoModel;
    private $motoModel;

    public function __construct() {
        $this->gastoModel = new Gasto();
        $this->motoModel = new Moto();
    }

    // 1. Mostrar listado de gastos
    public function index() {
        Session::checkPermission(['administrador', 'contador']);

        $gastos = $this->gastoModel->getAllWithDetails();

        $data = [
            'title' => 'Gestión de Gastos Operacionales',
            'gastos' => $gastos,
        ];

        $contentView = __DIR__ . '/../views/gastos/index.php';
        $title = $data['title'];
        $gastos = $data['gastos'];
        require_once __DIR__ . '/../views/layouts/app.php';
    }

    // 2. Mostrar formulario para registrar gasto de la empresa
    public function create() {
        Session::checkPermission(['administrador', 'contador']);
        
        $motos = $this->motoModel->getAll();
        
        $data = [
            'title' => 'Registrar Gasto Operativo/Mantenimiento',
            'motos' => $motos,
        ];
        
        $contentView = __DIR__ . '/../views/gastos/create.php';
        $title = $data['title'];
        $motos = $data['motos'];
        require_once __DIR__ . '/../views/layouts/app.php';
    }

    // 3. Procesar el formulario de registro de gasto
    public function store() {
        Session::checkPermission(['administrador', 'contador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'gastos/create');
            exit;
        }

        $idMoto = empty($_POST['id_moto']) ? NULL : (int)$_POST['id_moto'];

        // Preparación de datos
        $data = [
            'id_usuario' => Session::get('user_id'),
            'id_moto' => $idMoto, // Puede ser NULL para gastos generales
            'fecha_gasto' => $_POST['fecha_gasto'],
            'monto' => floatval($_POST['monto']),
            'descripcion' => trim($_POST['descripcion']),
            'categoria' => $_POST['categoria'],
        ];

        try {
            $this->gastoModel->registrarGasto($data);
            header('Location: ' . BASE_URL . 'gastos?success=Gasto registrado correctamente.');
            exit;
        } catch (\Exception $e) {
            header('Location: ' . BASE_URL . 'gastos/create?error=Error al registrar el gasto: ' . $e->getMessage());
            exit;
        }
    }

    // 4. Mostrar detalles de un gasto específico
    public function show($id) {
        Session::checkPermission(['administrador', 'contador']);

        $gasto = $this->gastoModel->find($id);
        if (!$gasto) {
            header('Location: ' . BASE_URL . 'gastos?error=Gasto no encontrado.');
            exit;
        }

        $data = [
            'title' => 'Detalles del Gasto',
            'gasto' => $gasto,
        ];

        $contentView = __DIR__ . '/../views/gastos/show.php';
        $title = $data['title'];
        $gasto = $data['gasto'];
        require_once __DIR__ . '/../views/layouts/app.php';
    }

    // 5. Mostrar formulario para editar gasto
    public function edit($id) {
        Session::checkPermission(['administrador', 'contador']);

        $gasto = $this->gastoModel->find($id);
        if (!$gasto) {
            header('Location: ' . BASE_URL . 'gastos?error=Gasto no encontrado.');
            exit;
        }

        $motos = $this->motoModel->getAll();

        $data = [
            'title' => 'Editar Gasto Operativo',
            'gasto' => $gasto,
            'motos' => $motos,
        ];

        $contentView = __DIR__ . '/../views/gastos/edit.php';
        $title = $data['title'];
        $gasto = $data['gasto'];
        $motos = $data['motos'];
        require_once __DIR__ . '/../views/layouts/app.php';
    }

    // 6. Actualizar gasto
    public function update($id) {
        Session::checkPermission(['administrador', 'contador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'gastos/edit/' . $id);
            exit;
        }

        $idMoto = empty($_POST['id_moto']) ? NULL : (int)$_POST['id_moto'];

        $data = [
            'id_moto' => $idMoto,
            'fecha_gasto' => $_POST['fecha_gasto'],
            'monto' => floatval($_POST['monto']),
            'descripcion' => trim($_POST['descripcion']),
            'categoria' => $_POST['categoria'],
        ];

        try {
            $this->gastoModel->update($id, $data);
            header('Location: ' . BASE_URL . 'gastos?success=Gasto actualizado correctamente.');
            exit;
        } catch (\Exception $e) {
            header('Location: ' . BASE_URL . 'gastos/edit/' . $id . '?error=Error al actualizar el gasto: ' . $e->getMessage());
            exit;
        }
    }

    // 7. Eliminar gasto
    public function destroy($id) {
        Session::checkPermission(['administrador', 'contador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'gastos?error=Método no permitido.');
            exit;
        }

        try {
            $this->gastoModel->delete($id);
            header('Location: ' . BASE_URL . 'gastos?success=Gasto eliminado correctamente.');
            exit;
        } catch (\Exception $e) {
            header('Location: ' . BASE_URL . 'gastos?error=Error al eliminar el gasto: ' . $e->getMessage());
            exit;
        }
    }
}