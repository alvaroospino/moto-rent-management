<?php 
// /app/views/gastos/create.php
// $motos viene inyectado del controlador

$fechaHoy = date('Y-m-d');
$categorias = ['mantenimiento', 'operativo', 'general', 'impuestos'];
?>
<div class="bg-white p-6 rounded-lg shadow-xl">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Registro de Gasto de la Empresa</h2>
    <p class="mb-4 text-sm text-gray-600 border-l-4 border-yellow-400 pl-3">
        **Importante:** Este formulario registra gastos que reducen la utilidad del negocio. Para gastos que debe pagar el cliente, use la opción de "Préstamo/Cargo" en el detalle del Contrato.
    </p>

    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>gastos/store" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                <label for="monto" class="block text-sm font-medium text-gray-700">Monto del Gasto ($) (*)</label>
                <input type="number" step="0.01" name="monto" id="monto" required min="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 p-2" placeholder="Ej: 150.00">
            </div>
            
            <div>
                <label for="fecha_gasto" class="block text-sm font-medium text-gray-700">Fecha del Gasto (*)</label>
                <input type="date" name="fecha_gasto" id="fecha_gasto" value="<?= $fechaHoy ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
            </div>
            
            <div>
                <label for="categoria" class="block text-sm font-medium text-gray-700">Categoría (*)</label>
                <select name="categoria" id="categoria" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                    <option value="">Seleccione Categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat ?>"><?= ucfirst($cat) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label for="id_moto" class="block text-sm font-medium text-gray-700">Moto Asociada (Opcional)</label>
                <select name="id_moto" id="id_moto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                    <option value="">Gasto General (No asociado a una moto)</option>
                    <?php foreach ($motos as $moto): ?>
                        <option value="<?= $moto['id_moto'] ?>">
                            <?= htmlspecialchars($moto['placa']) ?> - <?= htmlspecialchars($moto['marca']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción Detallada (*)</label>
                <textarea name="descripcion" id="descripcion" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" placeholder="Ej: Cambio de aceite y filtro para la moto placa XYZ123."></textarea>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end">
            <a href="<?= BASE_URL ?>gastos" class="mr-4 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="fas fa-minus-circle mr-2"></i> Registrar Gasto
            </button>
        </div>
    </form>
</div>