<?php
// /app/core/App.php

class App {
    
    protected static $routes = [];

    // Método estático para registrar rutas GET
    public static function get($uri, $action) {
        self::$routes['GET'][$uri] = $action;
    }

    // Método estático para registrar rutas POST
    public static function post($uri, $action) {
        self::$routes['POST'][$uri] = $action;
    }

    public function run() {
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Determinar el base path (útil cuando el proyecto está en un subdirectorio)
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])); // p.ej. /moto-rent-management/public
        if ($scriptDir !== '/') {
            // Si la ruta solicitada comienza con el scriptDir, eliminarlo
            if (strpos($requestPath, $scriptDir) === 0) {
                $requestPath = substr($requestPath, strlen($scriptDir));
            }
        }

        $uri = trim($requestPath, '/');

        // El login y authenticate son accesibles sin sesión
        $isPublic = in_array($uri, ['', 'login', 'authenticate']);

        // Intentar coincidencia exacta primero (con y sin slash inicial)
        $action = self::$routes[$method]["/{$uri}"] ?? self::$routes[$method]["{$uri}"] ?? null;
        if ($uri === '') { // Manejar la ruta raíz /
            $action = self::$routes[$method]['/'] ?? $action;
        }

        $routeParams = [];
        // Si no hay coincidencia exacta, intentar rutas con patrones (regex)
        if (!$action && !empty(self::$routes[$method])) {
            $uriWithSlash = '/' . $uri;
            foreach (self::$routes[$method] as $routePattern => $routeAction) {
                // Normalizar el patrón (asegurar que no se duplique el slash)
                $pattern = '#^' . $routePattern . '$#';
                if (@preg_match($pattern, $uriWithSlash, $matches)) {
                    // Si hay coincidencia, tomar la acción y extraer parámetros
                    $action = $routeAction;
                    if (count($matches) > 1) {
                        $routeParams = array_slice($matches, 1);
                    }
                    break;
                }
            }
        }

        if (!$action) {
            // Manejo de Error 404 simple
            http_response_code(404);
            die("404 - Página no encontrada");
        }

        // Separa el Controlador y el Método
        list($controllerName, $methodName) = explode('@', $action);

        // Si no es una ruta pública, verificar autenticación (excepto logout)
        if (!$isPublic && $uri !== 'logout' && !Session::isLoggedIn()) {
            header('Location: /login');
            exit;
        }

        // Preparar el archivo del controlador e incluirlo si existe (por si falla el autoload)
        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
        }

        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            if (method_exists($controller, $methodName)) {
                // Llamar al método pasando parámetros extraídos de la URL si los hay
                if (!empty($routeParams)) {
                    call_user_func_array([$controller, $methodName], $routeParams);
                } else {
                    $controller->$methodName();
                }
            } else {
                die("Método {$methodName} no encontrado en el controlador {$controllerName}");
            }
        } else {
            die("Controlador {$controllerName} no encontrado");
        }
    }
}