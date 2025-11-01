<?php
// register.php - Archivo independiente para registrar usuarios
// Contiene todo el proceso de registro en un solo archivo para fácil eliminación

// Configuración de la base de datos usando el sistema centralizado
require_once __DIR__ . '/app/core/Database.php';

try {
    $pdo = Database::getInstance()->getConnection();
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Función para validar datos
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Procesar el formulario
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = validateInput($_POST['nombre'] ?? '');
    $email = validateInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $rol = validateInput($_POST['rol'] ?? 'operador');

    $errors = [];

    if (empty($nombre)) {
        $errors[] = "El nombre es obligatorio.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El email es obligatorio y debe ser válido.";
    }

    if (empty($password)) {
        $errors[] = "La contraseña es obligatoria.";
    } elseif (strlen($password) < 6) {
        $errors[] = "La contraseña debe tener al menos 6 caracteres.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Las contraseñas no coinciden.";
    }

    if (!in_array($rol, ['administrador', 'operador', 'contador'])) {
        $errors[] = "Rol inválido.";
    }

    // Verificar si el email ya existe
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "El email ya está registrado.";
        }
    }

    if (empty($errors)) {
        // Insertar usuario
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Detectar tipo de base de datos para usar la función de fecha correcta
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $nowFunction = ($driver === 'pgsql') ? 'CURRENT_TIMESTAMP' : 'NOW()';

        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password_hash, rol, creado_en) VALUES (?, ?, ?, ?, {$nowFunction})");
        try {
            $stmt->execute([$nombre, $email, $hashed_password, $rol]);
            $message = "Usuario registrado exitosamente.";
        } catch (PDOException $e) {
            $errors[] = "Error al registrar usuario: " . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        $message = implode('<br>', $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - Moto Rent</title>
    <link href="public/assets/css/tailwind.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md">
        <form action="register.php" method="POST" class="bg-white shadow-lg rounded-xl px-8 pt-6 pb-8 mb-4">
            <h2 class="text-3xl font-bold text-center text-indigo-700 mb-6">Registro de Usuario</h2>

            <?php if ($message): ?>
                <div class="mb-4 p-4 rounded <?php echo strpos($message, 'exitosamente') !== false ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">
                    Nombre Completo
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       id="nombre" name="nombre" type="text" placeholder="Juan Pérez" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Correo Electrónico
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       id="email" name="email" type="email" placeholder="usuario@empresa.com" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Contraseña
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       id="password" name="password" type="password" placeholder="********" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">
                    Confirmar Contraseña
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       id="confirm_password" name="confirm_password" type="password" placeholder="********" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="rol">
                    Rol
                </label>
                <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        id="rol" name="rol" required>
                    <option value="operador">Operador</option>
                    <option value="contador">Contador</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
                        type="submit">
                    Registrar Usuario
                </button>
            </div>

            <div class="text-center mt-4">
                <a href="index.php" class="text-indigo-600 hover:text-indigo-800">Volver al inicio</a>
            </div>
        </form>
        <p class="text-center text-gray-500 text-xs mt-4">
            &copy;<?= date('Y') ?> Moto Rent Management.
        </p>
    </div>
</body>
</html>
