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
    <!-- Global Variables -->
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
    </script>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <style>
        /* Normalización de contenedores internos dentro del área de contenido para evitar espacios laterales en desktop */
        .page-container .container {
            max-width: 100% !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        .page-container .mx-auto {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        .page-container .max-w-7xl,
        .page-container .max-w-6xl,
        .page-container .max-w-5xl,
        .page-container .max-w-4xl,
        .page-container .max-w-3xl,
        .page-container .max-w-2xl {
            max-width: 100% !important;
        }
        /* Evitar que estilos particulares afecten al layout general */
        .page-container .absolute.container { position: static !important; }

       
    </style>
    <div class="flex h-screen">
        
        <aside id="sidebar" class="fixed md:static z-30 inset-y-0 left-0 w-64 md:w-64 transform -translate-x-full md:translate-x-0 bg-gray-800 text-white flex flex-col transition-all duration-200 ease-in-out overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-gray-700 sidebar-header">
                <span class="text-2xl font-semibold text-indigo-400 sidebar-title">MotoRent</span>
                <button id="closeSidebar" class="md:hidden text-gray-300 hover:text-white" aria-label="Cerrar menú">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="flex-grow p-4 overflow-y-auto sidebar-nav">
                <ul class="space-y-1">
                    <li>
                        <a href="<?= BASE_URL ?>dashboard" class="flex items-center p-2 rounded-lg transition hover:bg-gray-700">
                            <i class="fas fa-tachometer-alt w-6 text-center mr-2"></i> <span class="sidebar-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="pt-2">
                        <span class="text-2xs tracking-wider font-semibold uppercase text-gray-500 block px-2 mb-1 sidebar-section">Operación</span>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>motos" class="flex items-center p-2 rounded-lg transition hover:bg-gray-700">
                            <i class="fas fa-motorcycle w-6 text-center mr-2"></i> <span class="sidebar-label">Activos (Motos)</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>clientes" class="flex items-center p-2 rounded-lg transition hover:bg-gray-700">
                            <i class="fas fa-users w-6 text-center mr-2"></i> <span class="sidebar-label">Clientes</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>contratos" class="flex items-center p-2 rounded-lg transition hover:bg-gray-700">
                            <i class="fas fa-file-contract w-6 text-center mr-2"></i> <span class="sidebar-label">Contratos</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>gastos" class="flex items-center p-2 rounded-lg transition hover:bg-gray-700">
                            <i class="fas fa-wallet w-6 text-center mr-2"></i> <span class="sidebar-label">Gastos</span>
                        </a>
                    </li>
                    <?php if ($userRole === 'administrador'): ?>
                    <li class="pt-2">
                        <span class="text-2xs tracking-wider font-semibold uppercase text-gray-500 block px-2 mb-1 sidebar-section">Administración</span>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>usuarios" class="flex items-center p-2 rounded-lg transition hover:bg-gray-700">
                            <i class="fas fa-user-shield w-6 text-center mr-2"></i> <span class="sidebar-label">Usuarios</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>reportes" class="flex items-center p-2 rounded-lg transition hover:bg-gray-700">
                            <i class="fas fa-chart-line w-6 text-center mr-2"></i> <span class="sidebar-label">Reportes</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </aside>
        
        <div id="mainWrapper" class="main-content flex-1 flex flex-col overflow-hidden transition-all duration-200 ease-in-out">
            <header class="flex items-center justify-between gap-2 p-4 bg-white shadow-md sticky top-0 z-20">
                <div class="flex items-center gap-3">
                    <button id="openSidebar" class="md:hidden text-gray-700 hover:text-gray-900" aria-label="Abrir menú">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    <button id="toggleDesktopSidebar" class="hidden md:inline-flex items-center justify-center w-9 h-9 rounded text-gray-600 hover:text-gray-900 hover:bg-gray-100" title="Colapsar/Expandir menú">
                        <i class="fas fa-angle-double-left text-xl" id="toggleDesktopIcon"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800"><?= $title ?? 'Dashboard' ?></h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Hola, <?= htmlspecialchars($userName) ?> (<?= htmlspecialchars($userRole) ?>)</span>
                    <a href="<?= BASE_URL ?>logout" class="text-red-500 hover:text-red-700 transition">
                        <i class="fas fa-sign-out-alt mr-1"></i> Salir
                    </a>
                </div>
            </header>
            
            <main class="flex-1 overflow-x-hidden overflow-y-auto">
                <div class="page-container px-3 md:px-4 lg:px-6 py-4 md:py-6">
                    <?php 
                    if (isset($contentView)) {
                        require_once $contentView;
                    } else {
                        echo '<div class="text-center text-gray-500 mt-20">Contenido no definido.</div>';
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Manejo de apertura/cierre del sidebar en móvil
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const toggleDesktopSidebar = document.getElementById('toggleDesktopSidebar');
        const toggleDesktopIcon = document.getElementById('toggleDesktopIcon');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
        }
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
        }

        openBtn && openBtn.addEventListener('click', openSidebar);
        closeBtn && closeBtn.addEventListener('click', closeSidebar);

        // Cerrar al hacer click fuera en móvil
        document.addEventListener('click', (e) => {
            const isMdUp = window.matchMedia('(min-width: 768px)').matches;
            if (isMdUp) return;
            if (!sidebar.contains(e.target) && !openBtn.contains(e.target)) {
                closeSidebar();
            }
        });

        // Estado colapsado en escritorio
        const COLLAPSED_KEY = 'sidebarCollapsed';
        function applyDesktopCollapsed(collapsed) {
            // Estados en body para controlar padding del contenido
            document.body.classList.remove('layout-desktop-expanded', 'layout-desktop-collapsed');
            document.body.classList.add(collapsed ? 'layout-desktop-collapsed' : 'layout-desktop-expanded');

            // Control de ancho del sidebar e interiores (solo en md+)
            sidebar.classList.remove('md:w-64', 'md:w-16');
            if (collapsed) {
                sidebar.classList.add('md:w-16');
                document.querySelectorAll('.sidebar-label').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.sidebar-section').forEach(el => el.classList.add('hidden'));
                document.querySelector('.sidebar-nav')?.classList.add('px-2');
                document.querySelector('.sidebar-nav')?.classList.remove('p-4');
                document.querySelector('.sidebar-header')?.classList.add('px-2');
                document.querySelector('.sidebar-header')?.classList.remove('p-4');
                toggleDesktopIcon.classList.remove('fa-angle-double-left');
                toggleDesktopIcon.classList.add('fa-angle-double-right');
            } else {
                sidebar.classList.add('md:w-64');
                document.querySelectorAll('.sidebar-label').forEach(el => el.classList.remove('hidden'));
                document.querySelectorAll('.sidebar-section').forEach(el => el.classList.remove('hidden'));
                document.querySelector('.sidebar-nav')?.classList.remove('px-2');
                document.querySelector('.sidebar-nav')?.classList.add('p-4');
                document.querySelector('.sidebar-header')?.classList.remove('px-2');
                document.querySelector('.sidebar-header')?.classList.add('p-4');
                toggleDesktopIcon.classList.remove('fa-angle-double-right');
                toggleDesktopIcon.classList.add('fa-angle-double-left');
            }
        }

        function getStoredCollapsed() {
            try { return localStorage.getItem(COLLAPSED_KEY) === '1'; } catch (_) { return false; }
        }
        function storeCollapsed(v) {
            try { localStorage.setItem(COLLAPSED_KEY, v ? '1' : '0'); } catch (_) {}
        }

        // Inicializar según preferencia almacenada
        const initialCollapsed = getStoredCollapsed();
        applyDesktopCollapsed(initialCollapsed);

        // Alternar en click (solo escritorio)
        toggleDesktopSidebar && toggleDesktopSidebar.addEventListener('click', () => {
            const isMdUp = window.matchMedia('(min-width: 768px)').matches;
            if (!isMdUp) return;
            const nowCollapsed = !(sidebar.classList.contains('md:w-16'));
            applyDesktopCollapsed(nowCollapsed);
            storeCollapsed(nowCollapsed);
        });

        // Asegurar estado correcto al cambiar tamaño
        window.addEventListener('resize', () => {
            const isMdUp = window.matchMedia('(min-width: 768px)').matches;
            if (!isMdUp) {
                // En móvil, sidebar siempre oculto off-canvas
                closeSidebar();
            } else {
                // Reaplicar estado en escritorio y limpiar cualquier translate residual
                sidebar.classList.remove('-translate-x-full');
                applyDesktopCollapsed(getStoredCollapsed());
            }
        });
    </script>
</body>
</html>