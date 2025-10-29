<?php
require 'app/core/Database.php';
require 'app/models/BaseModel.php';
require 'app/models/Moto.php';

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query('SELECT * FROM motos');
    $motos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo 'Motos en BD: ' . count($motos) . PHP_EOL;
    foreach($motos as $moto) {
        echo 'ID: ' . $moto['id_moto'] . ', Estado: ' . $moto['estado'] . ', Marca: ' . $moto['marca'] . ', Modelo: ' . $moto['modelo'] . PHP_EOL;
    }

    // Test the model
    echo PHP_EOL . 'Testing Moto model:' . PHP_EOL;
    $motoModel = new Moto();
    $motosDisponibles = $motoModel->where(['estado' => 'disponible'])->get();
    echo 'Motos disponibles: ' . count($motosDisponibles) . PHP_EOL;

    $motosActivas = $motoModel->where(['estado' => 'activo'])->get();
    echo 'Motos activas: ' . count($motosActivas) . PHP_EOL;

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
