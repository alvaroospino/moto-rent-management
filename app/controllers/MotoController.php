<?php
// /app/controllers/MotoController.php

require_once __DIR__ . '/../models/Moto.php';
require_once __DIR__ . '/../core/Session.php';

class MotoController {
    
    private $motoModel;

    public function __construct() {
        $this->motoModel = new Moto();
    }

    // 1. Mostrar listado de motos
    public function index() {
        Session::checkPermission(['administrador', 'operador']);
        
        $motos = $this->motoModel->getAll();
        
        $data = [
            'title' => 'Gestión de Activos (Motos)',
            'motos' => $motos,
        ];
        
        $contentView = __DIR__ . '/../views/motos/index.php';
        $title = $data['title'];
        $motos = $data['motos'];
        require_once __DIR__ . '/../views/layouts/app.php';
    }

    // 2. Mostrar formulario para crear moto
    public function create() {
        Session::checkPermission(['administrador']); // Solo Admins pueden añadir activos
        
        $title = 'Registrar Nueva Moto';
        $contentView = __DIR__ . '/../views/motos/create.php';
        require_once __DIR__ . '/../views/layouts/app.php';
    }

    // 3. Procesar el formulario de creación
    public function store() {
        Session::checkPermission(['administrador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'motos/create');
            exit;
        }

        // Validación y saneamiento básico
        $data = [
            'placa' => trim($_POST['placa']),
            'marca' => trim($_POST['marca']),
            'modelo' => trim($_POST['modelo']),
            'valor_adquisicion' => floatval($_POST['valor_adquisicion']), // Inversión
            'estado' => 'activo',
            'fecha_adquisicion' => $_POST['fecha_adquisicion'],
        ];

        try {
            $this->motoModel->create($data);
            // Redirigir con mensaje de éxito (usando flash messages en Session)
            header('Location: ' . BASE_URL . 'motos?success=Moto registrada correctamente.');
            exit;
        } catch (\Exception $e) {
            // Manejo de errores (ej. placa duplicada)
            header('Location: ' . BASE_URL . 'motos/create?error=Error al registrar la moto.');
            exit;
        }
    }

    // 4. Mostrar formulario para editar moto
    public function edit($id) {
        Session::checkPermission(['administrador']);

        $moto = $this->motoModel->findById($id);
        if (!$moto) {
            header('Location: ' . BASE_URL . 'motos?error=Moto no encontrada.');
            exit;
        }

        $title = 'Editar Moto';
        $contentView = __DIR__ . '/../views/motos/edit.php';
        require_once __DIR__ . '/../views/layouts/app.php';
    }

    // 5. Procesar el formulario de actualización
    public function update($id) {
        Session::checkPermission(['administrador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'motos/edit/' . $id);
            exit;
        }

        // Validación y saneamiento básico
        $data = [
            'placa' => trim($_POST['placa']),
            'marca' => trim($_POST['marca']),
            'modelo' => trim($_POST['modelo']),
            'valor_adquisicion' => floatval($_POST['valor_adquisicion']),
            'estado' => $_POST['estado'],
            'fecha_adquisicion' => $_POST['fecha_adquisicion'],
        ];

        try {
            $this->motoModel->update($id, $data);
            header('Location: ' . BASE_URL . 'motos?success=Moto actualizada correctamente.');
            exit;
        } catch (\Exception $e) {
            header('Location: ' . BASE_URL . 'motos/edit/' . $id . '?error=Error al actualizar la moto.');
            exit;
        }
    }

    // 6. Eliminar moto
    public function delete($id) {
        Session::checkPermission(['administrador']);

        try {
            $this->motoModel->delete($id);
            header('Location: ' . BASE_URL . 'motos?success=Moto eliminada correctamente.');
            exit;
        } catch (\Exception $e) {
            header('Location: ' . BASE_URL . 'motos?error=Error al eliminar la moto.');
            exit;
        }
    }
}