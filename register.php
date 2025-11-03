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
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            animation: fadeIn 1s ease-in-out;
        }
        .form-container {
            animation: slideUp 0.8s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .input-group {
            position: relative;
        }
        .input-group i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }
        .input-group input, .input-group select {
            padding-left: 40px;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md form-container">
        <form action="register.php" method="POST" class="glass-effect shadow-2xl rounded-2xl px-8 pt-6 pb-8 mb-4">
            <h2 class="text-3xl font-bold text-center text-white mb-6 drop-shadow-lg">
                <i class="fas fa-user-plus mr-2"></i>Registro de Usuario
            </h2>

            <?php if ($message): ?>
                <div class="mb-4 p-4 rounded-lg <?php echo strpos($message, 'exitosamente') !== false ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'; ?> animate-pulse">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="mb-4 input-group">
                <label class="block text-white text-sm font-bold mb-2" for="nombre">
                    <i class="fas fa-user mr-1"></i>Nombre Completo
                </label>
                <i class="fas fa-user"></i>
                <input class="shadow appearance-none border border-white/20 rounded-lg w-full py-3 px-3 pl-10 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 bg-white/90 transition-all duration-300"
                       id="nombre" name="nombre" type="text" placeholder="Juan Pérez" required>
            </div>

            <div class="mb-4 input-group">
                <label class="block text-white text-sm font-bold mb-2" for="email">
                    <i class="fas fa-envelope mr-1"></i>Correo Electrónico
                </label>
                <i class="fas fa-envelope"></i>
                <input class="shadow appearance-none border border-white/20 rounded-lg w-full py-3 px-3 pl-10 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 bg-white/90 transition-all duration-300"
                       id="email" name="email" type="email" placeholder="usuario@empresa.com" required>
            </div>

            <div class="mb-4 input-group">
                <label class="block text-white text-sm font-bold mb-2" for="password">
                    <i class="fas fa-lock mr-1"></i>Contraseña
                </label>
                <i class="fas fa-lock"></i>
                <input class="shadow appearance-none border border-white/20 rounded-lg w-full py-3 px-3 pl-10 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 bg-white/90 transition-all duration-300"
                       id="password" name="password" type="password" placeholder="********" required>
            </div>

            <div class="mb-4 input-group">
                <label class="block text-white text-sm font-bold mb-2" for="confirm_password">
                    <i class="fas fa-lock mr-1"></i>Confirmar Contraseña
                </label>
                <i class="fas fa-lock"></i>
                <input class="shadow appearance-none border border-white/20 rounded-lg w-full py-3 px-3 pl-10 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 bg-white/90 transition-all duration-300"
                       id="confirm_password" name="confirm_password" type="password" placeholder="********" required>
            </div>

            <div class="mb-6 input-group">
                <label class="block text-white text-sm font-bold mb-2" for="rol">
                    <i class="fas fa-user-tag mr-1"></i>Rol
                </label>
                <i class="fas fa-user-tag"></i>
                <select class="shadow appearance-none border border-white/20 rounded-lg w-full py-3 px-3 pl-10 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 bg-white/90 transition-all duration-300"
                        id="rol" name="rol" required>
                    <option value="operador">Operador</option>
                    <option value="contador">Contador</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>

            <div class="flex items-center justify-between">
                <button class="btn-gradient text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline w-full text-lg"
                        type="submit">
                    <i class="fas fa-paper-plane mr-2"></i>Registrar Usuario
                </button>
            </div>

            <div class="text-center mt-4">
                <a href="index.php" class="text-white hover:text-gray-200 transition-colors duration-300 underline">
                    <i class="fas fa-arrow-left mr-1"></i>Volver al inicio
                </a>
            </div>
        </form>
        <p class="text-center text-white/70 text-xs mt-4 drop-shadow">
            &copy;<?= date('Y') ?> Moto Rent Management.
        </p>
    </div>
</body>
</html>
