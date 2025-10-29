<?php
// /app/controller/IndexController.php

class IndexController {

    public function index() {
        // Esta es la página principal pública, no requiere login
        // Carga la vista de bienvenida
        require_once __DIR__ . '/../views/index.php';
    }
}
