<?php
// /app/models/Notificacion.php

class Notificacion extends BaseModel {
    public function __construct() {
        parent::__construct('notificaciones', 'id_notificacion');
    }

    public function getByUser($userId, $limit = 10) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE id_usuario = :user_id
            ORDER BY fecha_creacion DESC
            LIMIT :limit
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUnreadCount($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total FROM {$this->table}
            WHERE id_usuario = :user_id AND leida = 0
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return (int) $result['total'];
    }

    public function markAsRead($notificationId, $userId) {
        $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET leida = 1, fecha_leida = NOW()
            WHERE id_notificacion = :id AND id_usuario = :user_id
        ");
        $stmt->bindParam(':id', $notificationId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function markAllAsRead($userId) {
        $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET leida = 1, fecha_leida = NOW()
            WHERE id_usuario = :user_id AND leida = 0
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function create(array $data) {
        $data['fecha_creacion'] = date('Y-m-d H:i:s');
        return parent::create($data);
    }

    public function getRecent($userId, $days = 7) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE id_usuario = :user_id
            AND fecha_creacion >= DATE_SUB(NOW(), INTERVAL :days DAY)
            ORDER BY fecha_creacion DESC
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
