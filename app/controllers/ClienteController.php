<?php
// /app/controllers/ClienteController.php

require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../core/Session.php';

class ClienteController {
    
    private $clienteModel;

    public function __construct() {
        $this->clienteModel = new Cliente();
    }

    // 1. Mostrar listado de clientes
    public function index() {
        Session::checkPermission(['administrador', 'operador', 'contador']);
        
        $clientes = $this->clienteModel->getAll();
        
        $data = [
            'title' => 'Gestión de Clientes',
            'clientes' => $clientes,
        ];
        
        $contentView = __DIR__ . '/../views/clientes/index.php';
        $title = $data['title'];
        $clientes = $data['clientes'];
        
        require_once __DIR__ . '/../views/layouts/app.php';
    }

    // 2. Mostrar formulario para crear cliente
    public function create() {
        Session::checkPermission(['administrador', 'operador']);
        
        $title = 'Registrar Nuevo Cliente';
        $contentView = __DIR__ . '/../views/clientes/create.php';
        require_once __DIR__ . '/../views/layouts/app.php';
    }

    // 3. Procesar el formulario de creación
    public function store() {
        Session::checkPermission(['administrador', 'operador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'clientes/create');
            exit;
        }

        // Validación y saneamiento
        $data = [
            'nombre_completo' => trim($_POST['nombre_completo']),
            'identificacion' => trim($_POST['identificacion']),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
        ];

        // Se podría agregar validación para verificar identificación única.

        try {
            $this->clienteModel->create($data);
            header('Location: ' . BASE_URL . 'clientes?success=Cliente registrado correctamente.');
            exit;
        } catch (\Exception $e) {
            // Manejo de error de DB (ej. identificación duplicada)
            header('Location: ' . BASE_URL . 'clientes/create?error=Error al registrar el cliente. (Identificación duplicada?)');
            exit;
        }
    }

    // 4. Mostrar formulario para editar cliente
    public function edit($id) {
        Session::checkPermission(['administrador', 'operador']);

        $cliente = $this->clienteModel->find($id);
        if (!$cliente) {
            header('Location: ' . BASE_URL . 'clientes?error=Cliente no encontrado.');
            exit;
        }

        $title = 'Editar Cliente';
        $contentView = __DIR__ . '/../views/clientes/edit.php';
        require_once __DIR__ . '/../views/layouts/app.php';
    }

    // 5. Procesar el formulario de edición
    public function update($id) {
        Session::checkPermission(['administrador', 'operador']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'clientes/edit/' . $id);
            exit;
        }

        // Validación y saneamiento
        $data = [
            'nombre_completo' => trim($_POST['nombre_completo']),
            'identificacion' => trim($_POST['identificacion']),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
        ];

        try {
            $this->clienteModel->update($id, $data);
            header('Location: ' . BASE_URL . 'clientes?success=Cliente actualizado correctamente.');
            exit;
        } catch (\Exception $e) {
            header('Location: ' . BASE_URL . 'clientes/edit/' . $id . '?error=Error al actualizar el cliente.');
            exit;
        }
    }

    // 6. Eliminar cliente
    public function delete($id) {
        Session::checkPermission(['administrador']);

        try {
            $this->clienteModel->delete($id);
            header('Location: ' . BASE_URL . 'clientes?success=Cliente eliminado correctamente.');
            exit;
        } catch (\Exception $e) {
            header('Location: ' . BASE_URL . 'clientes?error=Error al eliminar el cliente.');
            exit;
        }
    }
}
