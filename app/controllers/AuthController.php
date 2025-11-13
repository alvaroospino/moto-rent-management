<?php
// /app/controllers/AuthController.php

require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../core/Session.php';

class AuthController {
    
    // Muestra la vista de login
    public function login() {
        // La vista de login debe estar en /app/views/auth/login.php
        $error = Session::get('login_error');
        Session::set('login_error', null); // Limpiar el error después de mostrarlo
        
        // Incluir la vista de login
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Procesa el formulario de login
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $usuarioModel = new Usuario();
        $user = $usuarioModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Login exitoso
            Session::init();
            Session::set('is_logged_in', true);
            Session::set('user_id', $user['id_usuario']);
            Session::set('user_name', $user['nombre']);
            Session::set('user_role', $user['rol']);

            // Redirigir al dashboard
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        } else {
            // Login fallido
            Session::set('login_error', 'Credenciales inválidas. Por favor, intente de nuevo.');
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    // Cierra la sesión
    public function logout() {
        Session::init();
        Session::destroy();
        header('Location: ' . BASE_URL . 'login');
        exit;
    }
}