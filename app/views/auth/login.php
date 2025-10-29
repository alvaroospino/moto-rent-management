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
    <!-- Tailwind CDN (rápido para desarrollo). Mantengo el enlace local como fallback si existe -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="/assets/css/tailwind.css" rel="stylesheet"> 
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md">
        <form action="<?= BASE_URL ?>authenticate" method="POST" class="bg-white shadow-lg rounded-xl px-8 pt-6 pb-8 mb-4">
            <h2 class="text-3xl font-bold text-center text-indigo-700 mb-6">Moto Rent Gestión</h2>
            
            <?php if (isset($error) && $error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Correo Electrónico
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                       id="email" name="email" type="email" placeholder="usuario@empresa.com" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Contraseña
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                       id="password" name="password" type="password" placeholder="********" required>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 w-full" 
                        type="submit">
                    Iniciar Sesión
                </button>
            </div>
        </form>
        <p class="text-center text-gray-500 text-xs mt-4">
            &copy;<?= date('Y') ?> Moto Rent Management.
        </p>
    </div>
</body>
</html>