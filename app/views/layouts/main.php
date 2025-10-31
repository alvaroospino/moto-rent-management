<?php
// /app/views/layouts/main.php
// Layout para páginas públicas (login, registro, etc.)

// Definir BASE_URL si no está definido
if (!defined('BASE_URL')) {
    define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . '/');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moto Rent - Gestión de Alquiler de Motos</title>
    <!-- PWA Manifest -->
    <link rel="manifest" href="<?= BASE_URL ?>manifest.json">
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="/assets/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Global Variables -->
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
    </script>
</head>
<body class="bg-gradient-to-br from-indigo-100 via-white to-purple-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <?php
            if (isset($contentView)) {
                require_once $contentView;
            } else {
                echo '<div class="text-center text-gray-500">Contenido no definido.</div>';
            }
            ?>
        </div>
    </div>

    <script>
        // PWA Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?= BASE_URL ?>sw.js')
                    .then((registration) => {
                        console.log('Service Worker registrado con éxito:', registration);
                    })
                    .catch((error) => {
                        console.log('Error al registrar Service Worker:', error);
                    });
            });
        }
    </script>
</body>
</html>
