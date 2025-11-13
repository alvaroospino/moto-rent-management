<?php
// /app/core/Session.php

class Session {

    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            // Configuraciones de seguridad de la sesión
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_strict_mode', 1);
            if (isset($_SERVER['HTTPS'])) {
                ini_set('session.cookie_secure', 1);
            }
            session_start();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public static function destroy() {
        $_SESSION = array();
        session_destroy();
    }

    // Lógica clave para la autenticación y roles
    public static function isLoggedIn() {
        return (bool)self::get('is_logged_in');
    }

    public static function getUserRole() {
        return self::get('user_role');
    }

    public static function checkPermission(array $allowedRoles) {
        if (!self::isLoggedIn()) {
            // Redirigir a login si no está autenticado
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        if (!in_array(self::getUserRole(), $allowedRoles)) {
            // Redirigir a una página de error o dashboard si no tiene permiso
            header('Location: ' . BASE_URL . 'dashboard?error=access_denied');
            exit;
        }
    }
}