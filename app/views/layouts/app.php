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
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Preloader Script -->
    <script src="<?= BASE_URL ?>assets/js/preloader.js"></script>
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

        /* Ocultar scrollbar en la navegación del sidebar */
        .sidebar-nav::-webkit-scrollbar {
            display: none;
        }
        .sidebar-nav {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }


    </style>
    <div class="flex h-screen">
        
        <aside id="sidebar" class="fixed md:static z-30 inset-y-0 left-0 w-64 md:w-64 transform -translate-x-full md:translate-x-0 bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 text-white flex flex-col transition-all duration-300 ease-in-out overflow-hidden shadow-2xl">
            <div class="flex items-center justify-between p-6 border-b border-gray-700/50 sidebar-header bg-gradient-to-r from-indigo-600/20 to-purple-600/20 backdrop-blur-sm">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-motorcycle text-white text-lg"></i>
                    </div>
                    <span class="text-2xl font-bold text-white sidebar-title bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">MotoRent</span>
                </div>
                <button id="closeSidebar" class="md:hidden w-8 h-8 flex items-center justify-center text-gray-300 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200" aria-label="Cerrar menú">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <nav class="flex-grow p-4 overflow-y-auto sidebar-nav bg-gradient-to-b from-transparent to-gray-900/50">
                <ul class="space-y-2">
                    <li>
                        <a href="<?= BASE_URL ?>dashboard" class="sidebar-link flex items-center p-3 rounded-xl transition-all duration-200 hover:bg-gradient-to-r hover:from-indigo-600/20 hover:to-purple-600/20 hover:shadow-lg hover:scale-105 group">
                            <div class="sidebar-icon w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg transition-all duration-200">
                                <i class="fas fa-tachometer-alt text-white text-sm"></i>
                            </div>
                            <span class="sidebar-label font-medium text-gray-200 group-hover:text-white transition-colors duration-200">Dashboard</span>
                        </a>
                    </li>
                    <li class="pt-4">
                        <span class="sidebar-section text-xs tracking-wider font-bold uppercase text-gray-400 block px-3 mb-2 border-l-2 border-indigo-500 pl-2">Operación</span>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>motos" class="sidebar-link flex items-center p-3 rounded-xl transition-all duration-200 hover:bg-gradient-to-r hover:from-indigo-600/20 hover:to-purple-600/20 hover:shadow-lg hover:scale-105 group">
                            <div class="sidebar-icon w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg transition-all duration-200">
                                <i class="fas fa-motorcycle text-white text-sm"></i>
                            </div>
                            <span class="sidebar-label font-medium text-gray-200 group-hover:text-white transition-colors duration-200">Activos (Motos)</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>clientes" class="sidebar-link flex items-center p-3 rounded-xl transition-all duration-200 hover:bg-gradient-to-r hover:from-indigo-600/20 hover:to-purple-600/20 hover:shadow-lg hover:scale-105 group">
                            <div class="sidebar-icon w-8 h-8 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg transition-all duration-200">
                                <i class="fas fa-users text-white text-sm"></i>
                            </div>
                            <span class="sidebar-label font-medium text-gray-200 group-hover:text-white transition-colors duration-200">Clientes</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>contratos" class="sidebar-link flex items-center p-3 rounded-xl transition-all duration-200 hover:bg-gradient-to-r hover:from-indigo-600/20 hover:to-purple-600/20 hover:shadow-lg hover:scale-105 group">
                            <div class="sidebar-icon w-8 h-8 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg transition-all duration-200">
                                <i class="fas fa-file-contract text-white text-sm"></i>
                            </div>
                            <span class="sidebar-label font-medium text-gray-200 group-hover:text-white transition-colors duration-200">Contratos</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>gastos" class="sidebar-link flex items-center p-3 rounded-xl transition-all duration-200 hover:bg-gradient-to-r hover:from-indigo-600/20 hover:to-purple-600/20 hover:shadow-lg hover:scale-105 group">
                            <div class="sidebar-icon w-8 h-8 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-lg flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg transition-all duration-200">
                                <i class="fas fa-wallet text-white text-sm"></i>
                            </div>
                            <span class="sidebar-label font-medium text-gray-200 group-hover:text-white transition-colors duration-200">Gastos</span>
                        </a>
                    </li>
                    <?php if ($userRole === 'administrador'): ?>
                    <li class="pt-4">
                        <span class="sidebar-section text-xs tracking-wider font-bold uppercase text-gray-400 block px-3 mb-2 border-l-2 border-purple-500 pl-2">Administración</span>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>usuarios" class="sidebar-link flex items-center p-3 rounded-xl transition-all duration-200 hover:bg-gradient-to-r hover:from-indigo-600/20 hover:to-purple-600/20 hover:shadow-lg hover:scale-105 group">
                            <div class="sidebar-icon w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg transition-all duration-200">
                                <i class="fas fa-user-shield text-white text-sm"></i>
                            </div>
                            <span class="sidebar-label font-medium text-gray-200 group-hover:text-white transition-colors duration-200">Usuarios</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>reportes" class="sidebar-link flex items-center p-3 rounded-xl transition-all duration-200 hover:bg-gradient-to-r hover:from-indigo-600/20 hover:to-purple-600/20 hover:shadow-lg hover:scale-105 group">
                            <div class="sidebar-icon w-8 h-8 bg-gradient-to-br from-rose-500 to-pink-600 rounded-lg flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg transition-all duration-200">
                                <i class="fas fa-chart-line text-white text-sm"></i>
                            </div>
                            <span class="sidebar-label font-medium text-gray-200 group-hover:text-white transition-colors duration-200">Reportes</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </aside>
        
        <div id="mainWrapper" class="main-content flex-1 flex flex-col overflow-hidden transition-all duration-200 ease-in-out">
            <header class="flex items-center justify-between gap-4 p-4 bg-gradient-to-r from-white to-gray-50 shadow-lg sticky top-0 z-20 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <button id="openSidebar" class="md:hidden bg-gradient-to-br from-white to-gray-50 p-2 rounded-lg shadow-md border border-gray-200 hover:border-indigo-300 hover:shadow-lg hover:scale-105 transition-all duration-200 group" aria-label="Abrir menú">
                        <div class="w-5 h-5 flex flex-col justify-center items-center">
                            <span class="block w-4 h-0.5 bg-gray-600 mb-1 transition-all duration-200 group-hover:bg-indigo-600"></span>
                            <span class="block w-4 h-0.5 bg-gray-600 mb-1 transition-all duration-200 group-hover:bg-indigo-600"></span>
                            <span class="block w-4 h-0.5 bg-gray-600 transition-all duration-200 group-hover:bg-indigo-600"></span>
                        </div>
                    </button>
                    <button id="toggleDesktopSidebar" class="hidden md:inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-white to-gray-50 shadow-lg border-2 border-gray-200 hover:border-indigo-300 hover:shadow-2xl hover:scale-105 transition-all duration-300 group" title="Colapsar/Expandir menú">
                        <i class="fas fa-angle-double-left text-xl text-gray-600 group-hover:text-indigo-600 transition-colors duration-300" id="toggleDesktopIcon"></i>
                    </button>
                    <h1 class="text-xl font-bold text-gray-800 hidden sm:block"><?= $title ?? 'Dashboard' ?></h1>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Estado de conexión -->
                    <div class="hidden md:flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse" title="Conectado"></div>
                        <span class="text-xs text-gray-600">En línea</span>
                    </div>

                    <!-- Notificaciones -->
                    <button class="relative p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200" title="Notificaciones">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                    </button>

                    <!-- Saludo mejorado -->
                    <div class="flex items-center space-x-2 text-gray-700">
                        <i class="fas fa-hand-wave text-yellow-500 text-lg"></i>
                        <span class="font-medium">Hola, <?= htmlspecialchars($userName) ?></span>
                    </div>

                    <!-- Avatar y menú desplegable -->
                    <div class="relative">
                        <button id="userMenuBtn" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition-all duration-200 group">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm shadow-md">
                                <?= strtoupper(substr($userName, 0, 1)) ?>
                            </div>
                            <i class="fas fa-chevron-down text-gray-500 group-hover:text-gray-700 transition-colors duration-200"></i>
                        </button>

                        <!-- Menú desplegable -->
                        <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50 hidden">
                            <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-200">
                                <i class="fas fa-user mr-3 text-indigo-500"></i>
                                Perfil
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-200">
                                <i class="fas fa-cog mr-3 text-gray-500"></i>
                                Configuración
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-200">
                                <i class="fas fa-question-circle mr-3 text-blue-500"></i>
                                Ayuda
                            </a>
                            <hr class="my-2 border-gray-200">
                            <a href="<?= BASE_URL ?>logout" class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-200">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                Cerrar sesión
                            </a>
                        </div>
                    </div>
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
                document.querySelectorAll('.sidebar-link').forEach(el => el.classList.add('justify-center'));
                document.querySelectorAll('.sidebar-icon').forEach(el => el.classList.remove('mr-3'));
                document.querySelector('.sidebar-nav')?.classList.add('px-2');
                document.querySelector('.sidebar-nav')?.classList.remove('p-4');
                document.querySelector('.sidebar-header')?.classList.add('px-2');
                document.querySelector('.sidebar-header')?.classList.remove('p-6');
                toggleDesktopIcon.classList.remove('fa-angle-double-left');
                toggleDesktopIcon.classList.add('fa-angle-double-right');
            } else {
                sidebar.classList.add('md:w-64');
                document.querySelectorAll('.sidebar-label').forEach(el => el.classList.remove('hidden'));
                document.querySelectorAll('.sidebar-section').forEach(el => el.classList.remove('hidden'));
                document.querySelectorAll('.sidebar-link').forEach(el => el.classList.remove('justify-center'));
                document.querySelectorAll('.sidebar-icon').forEach(el => el.classList.add('mr-3'));
                document.querySelector('.sidebar-nav')?.classList.remove('px-2');
                document.querySelector('.sidebar-nav')?.classList.add('p-4');
                document.querySelector('.sidebar-header')?.classList.remove('px-2');
                document.querySelector('.sidebar-header')?.classList.add('p-6');
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

        // Inicializar según preferencia almacenada (solo en desktop)
        const isMdUp = window.matchMedia('(min-width: 768px)').matches;
        if (isMdUp) {
            const initialCollapsed = getStoredCollapsed();
            applyDesktopCollapsed(initialCollapsed);
        } else {
            // En móvil, asegurar que esté expandido
            applyDesktopCollapsed(false);
        }

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

        // Manejo del menú desplegable del usuario
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userMenu = document.getElementById('userMenu');

        userMenuBtn && userMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
        });

        // Cerrar menú al hacer click fuera
        document.addEventListener('click', (e) => {
            if (!userMenuBtn.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>