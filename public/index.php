<?php
// /public/index.php

// Cargar clases esenciales
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Session.php';
require_once __DIR__ . '/../app/core/App.php'; 

// Inicializar la sesión de forma segura
Session::init();

// Definir la URL base del proyecto (para rutas absolutas)
define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . '/');

    // Autocarga de clases para no tener que usar 'require_once' en todos lados
    // Nota: usar el nombre de carpeta correcto 'controllers' (plural)
    spl_autoload_register(function ($class) {
        // Definir directorios base para Modelos y Controladores
        $dirs = ['controllers', 'models', 'core'];

        foreach ($dirs as $dir) {
            $file = __DIR__ . "/../app/{$dir}/{$class}.php";
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    });

// 1. Inicializar el Router (Clase App)
$app = new App(); 

// 2. Definición de rutas (Aquí mapeamos URLs a Controladores/Métodos)
// Esto es un ejemplo de un enrutador simple. En un framework se usaría un sistema más avanzado.
$app->get('/', 'IndexController@index'); // Ruta principal - Página de bienvenida
$app->get('/dashboard', 'DashboardController@index');

// Rutas de Autenticación
$app->get('/login', 'AuthController@login');
$app->post('/authenticate', 'AuthController@authenticate');
$app->get('/logout', 'AuthController@logout');

// Rutas de Activos (Módulo 2)
$app->get('/motos', 'MotoController@index');
$app->get('/motos/create', 'MotoController@create');
$app->post('/motos/store', 'MotoController@store');
$app->get('/motos/edit/(\d+)', 'MotoController@edit');
$app->post('/motos/update/(\d+)', 'MotoController@update');
$app->get('/motos/delete/(\d+)', 'MotoController@delete');

// Rutas de Clientes (NUEVAS)
$app->get('/clientes', 'ClienteController@index');
$app->get('/clientes/create', 'ClienteController@create');
$app->post('/clientes/store', 'ClienteController@store');
$app->get('/clientes/edit/(\d+)', 'ClienteController@edit');
$app->post('/clientes/update/(\d+)', 'ClienteController@update');
$app->get('/clientes/delete/(\d+)', 'ClienteController@delete');

// Rutas de Contratos (Módulo 3)
$app->get('/contratos', 'ContratoController@index');
$app->get('/contratos/create', 'ContratoController@create');
$app->post('/contratos/store', 'ContratoController@store');
$app->get('/contratos/detail/(\d+)', 'ContratoController@detail');

// Rutas de Pagos/Préstamos (Módulo 4 )
$app->get('/pagos/contrato/(\d+)', 'PagoController@create');
$app->post('/pagos/store', 'PagoController@registrarPago');
$app->post('/prestamos/store', 'PagoController@registrarPrestamo');

// Rutas de Gastos Operacionales (Módulo 5 - NUEVAS)
$app->get('/gastos', 'GastoController@index');
$app->get('/gastos/create', 'GastoController@create');
$app->post('/gastos/store', 'GastoController@store');
$app->get('/gastos/show/(\d+)', 'GastoController@show');
$app->get('/gastos/edit/(\d+)', 'GastoController@edit');
$app->post('/gastos/update/(\d+)', 'GastoController@update');
$app->post('/gastos/destroy/(\d+)', 'GastoController@destroy');

// Rutas de Reportes (Módulo 6 - NUEVAS)
$app->get('/reportes', 'ReporteController@index');
// $app->get('/reportes/export', 'ReporteController@export'); // Ruta para exportación

// 3. Despachar la solicitud
$app->run();