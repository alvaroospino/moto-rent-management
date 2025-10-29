<?php
// /app/views/layouts/app.php
// Este archivo carga la estructura base y la vista específica.

require_once __DIR__ . '/../../core/Session.php';

if (!Session::isLoggedIn()) {
    header('Location: /login');
    exit;
}

// Variables de la sesión para mostrar en la interfaz
$userName = Session::get('user_name');
$userRole = Session::get('user_role');

// Definir BASE_URL si no está definido (para compatibilidad)
if (!defined('BASE_URL')) {
    define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . '/');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Alquiler - Moto Rent</title>
    <!-- Tailwind CDN (rápido para desarrollo). Mantengo el enlace local como fallback si existe -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="/assets/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.tailwindcss.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.tailwindcss.min.js"></script>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="flex h-screen">
        
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="p-6 text-2xl font-semibold text-indigo-400 border-b border-gray-700">
                MotoRent
            </div>
            <nav class="flex-grow p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="<?= BASE_URL ?>dashboard" class="flex items-center p-2 rounded-lg transition duration-150 hover:bg-gray-700">
                            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                        </a>
                    </li>
                    <li class="pt-2">
                        <span class="text-xs font-semibold uppercase text-gray-500 block px-2 mb-1">Operación</span>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>motos" class="flex items-center p-2 rounded-lg transition duration-150 hover:bg-gray-700">
                            <i class="fas fa-motorcycle mr-3"></i> Activos (Motos)
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>clientes" class="flex items-center p-2 rounded-lg transition duration-150 hover:bg-gray-700">
                            <i class="fas fa-users mr-3"></i> Clientes
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>contratos" class="flex items-center p-2 rounded-lg transition duration-150 hover:bg-gray-700">
                            <i class="fas fa-file-contract mr-3"></i> Contratos
                        </a>
                    </li>
                     <li>
                        <a href="<?= BASE_URL ?>gastos" class="flex items-center p-2 rounded-lg transition duration-150 hover:bg-gray-700">
                            <i class="fas fa-wallet mr-3"></i> Gastos
                        </a>
                    </li>
                    <?php if ($userRole === 'administrador'): ?>
                    <li class="pt-2">
                        <span class="text-xs font-semibold uppercase text-gray-500 block px-2 mb-1">Administración</span>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>usuarios" class="flex items-center p-2 rounded-lg transition duration-150 hover:bg-gray-700">
                            <i class="fas fa-user-shield mr-3"></i> Usuarios
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>reportes" class="flex items-center p-2 rounded-lg transition duration-150 hover:bg-gray-700">
                            <i class="fas fa-chart-line mr-3"></i> Reportes
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </aside>
        
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between p-4 bg-white shadow-md">
                <h1 class="text-xl font-semibold text-gray-800"><?= $title ?? 'Dashboard' ?></h1>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Hola, **<?= htmlspecialchars($userName) ?>** (<?= htmlspecialchars($userRole) ?>)</span>
                    <a href="<?= BASE_URL ?>logout" class="text-red-500 hover:text-red-700 transition duration-150">
                        <i class="fas fa-sign-out-alt mr-1"></i> Salir
                    </a>
                </div>
            </header>
            
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                <?php 
                // $contentView debe ser seteada por el controlador antes de cargar el layout
                if (isset($contentView)) {
                    require_once $contentView;
                } else {
                    echo '<div class="text-center text-gray-500 mt-20">Contenido no definido.</div>';
                }
                ?>
            </main>
        </div>
    </div>
</body>
</html>