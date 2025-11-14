<?php
// /app/views/auth/login.php
// Asume que Tailwind CSS está disponible en el proyecto.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Sistema - Moto Rent</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>assets/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL ?>assets/icons/icon-192x192.png">
    <!-- Tailwind CDN (rápido para desarrollo). Mantengo el enlace local como fallback si existe -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="<?= BASE_URL ?>assets/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-indigo-100 via-white to-purple-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo Section -->
            <div class="text-center">
                <div class="mx-auto w-24 h-24 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg mb-6">
                    <img src="<?= BASE_URL ?>assets/icons/icon-192x192.png" alt="Moto Rent Logo" class="w-16 h-16 rounded-full">
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Moto Rent</h1>
                <p class="text-gray-600">Sistema de Gestión de Alquiler de Motos</p>
            </div>

            <!-- Login Form -->
            <div>
                <form action="<?= BASE_URL ?>authenticate" method="POST" class="bg-white shadow-lg rounded-xl px-8 pt-6 pb-8">
                    <h2 class="text-2xl font-bold text-center text-indigo-700 mb-6">Acceso al Sistema</h2>

                    <?php if (isset($error) && $error): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            Correo Electrónico
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200" 
                               id="email" name="email" type="email" placeholder="usuario@empresa.com" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                            Contraseña
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-3 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200" 
                               id="password" name="password" type="password" placeholder="********" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 w-full shadow-md hover:shadow-lg transform hover:scale-105" 
                                type="submit">
                            Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>

            <div class="text-center">
                <p class="text-gray-500 text-sm">
                    &copy;<?= date('Y') ?> Moto Rent Management. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>
</body>
</html>