<?php
// /app/controllers/NotificacionController.php

require_once __DIR__ . '/../models/Notificacion.php';

class NotificacionController {
    private $notificacionModel;

    public function __construct() {
        $this->notificacionModel = new Notificacion();
    }

    public function getUnreadCount() {
        $userId = Session::get('user_id');
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Usuario no autenticado']);
            return;
        }

        $count = $this->notificacionModel->getUnreadCount($userId);
        echo json_encode(['count' => $count]);
    }

    public function getNotifications() {
        $userId = Session::get('user_id');
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Usuario no autenticado']);
            return;
        }

        $limit = $_GET['limit'] ?? 10;
        $notifications = $this->notificacionModel->getByUser($userId, $limit);

        echo json_encode(['notifications' => $notifications]);
    }

    public function markAsRead() {
        $userId = Session::get('user_id');
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Usuario no autenticado']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $notificationId = $input['id'] ?? null;

        if (!$notificationId) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de notificación requerido']);
            return;
        }

        $success = $this->notificacionModel->markAsRead($notificationId, $userId);
        echo json_encode(['success' => $success]);
    }

    public function markAllAsRead() {
        $userId = Session::get('user_id');
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Usuario no autenticado']);
            return;
        }

        $success = $this->notificacionModel->markAllAsRead($userId);
        echo json_encode(['success' => $success]);
    }

    public function create() {
        $userId = Session::get('user_id');
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Usuario no autenticado']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        $data = [
            'id_usuario' => $userId,
            'titulo' => $input['titulo'] ?? '',
            'mensaje' => $input['mensaje'] ?? '',
            'tipo' => $input['tipo'] ?? 'info',
            'leida' => 0
        ];

        $id = $this->notificacionModel->create($data);
        echo json_encode(['success' => true, 'id' => $id]);
    }

    // Método para crear notificaciones del sistema (pagos pendientes, contratos próximos a vencer, etc.)
    public function createSystemNotification($userId, $titulo, $mensaje, $tipo = 'info') {
        $data = [
            'id_usuario' => $userId,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'tipo' => $tipo,
            'leida' => 0
        ];

        return $this->notificacionModel->create($data);
    }
}
