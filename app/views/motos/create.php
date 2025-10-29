<?php 
// /app/views/motos/create.php
?>
<div class="bg-white p-6 rounded-lg shadow-xl">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Registro de Activo (Moto)</h2>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>motos/store" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                <label for="placa" class="block text-sm font-medium text-gray-700">Placa</label>
                <input type="text" name="placa" id="placa" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
            </div>
            
            <div>
                <label for="marca" class="block text-sm font-medium text-gray-700">Marca</label>
                <input type="text" name="marca" id="marca" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
            </div>
            
            <div>
                <label for="modelo" class="block text-sm font-medium text-gray-700">Modelo</label>
                <input type="text" name="modelo" id="modelo" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
            </div>
            
            <div>
                <label for="valor_adquisicion" class="block text-sm font-medium text-gray-700">Valor de Adquisición ($)</label>
                <input type="number" step="0.01" name="valor_adquisicion" id="valor_adquisicion" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" placeholder="Ej: 5000.00">
                <p class="mt-1 text-xs text-gray-500">Valor inicial para calcular la rentabilidad.</p>
            </div>
            
            <div>
                <label for="fecha_adquisicion" class="block text-sm font-medium text-gray-700">Fecha de Adquisición</label>
                <input type="date" name="fecha_adquisicion" id="fecha_adquisicion" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
            </div>
        </div>
        
        <div class="mt-8 flex justify-end">
            <a href="<?= BASE_URL ?>motos" class="mr-4 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i> Registrar Moto
            </button>
        </div>
    </form>
</div>