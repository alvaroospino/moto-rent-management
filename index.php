<?php
// /index.php
// Página de bienvenida en la raíz del proyecto
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Moto Rent Management</title>
    <!-- Tailwind CDN (rápido para desarrollo). Mantengo el enlace local como fallback si existe -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="public/assets/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white shadow-2xl rounded-2xl p-8 text-center">
        <div class="mb-6">
            <i class="fas fa-motorcycle text-6xl text-indigo-600 mb-4"></i>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Moto Rent</h1>
            <p class="text-gray-600">Sistema de Gestión de Alquiler de Motos</p>
        </div>

        <div class="space-y-4">
            <p class="text-sm text-gray-500">Accede al sistema para gestionar tus operaciones</p>
            <div class="flex space-x-4">
                <a href="public/login" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
                </a>
                <a href="register.php" class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-user-plus mr-2"></i> Registrarse
                </a>
            </div>
        </div>

        <div class="mt-8 text-xs text-gray-400">
            <p>&copy; <?= date('Y') ?> Moto Rent Management. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
