<?php 
// /app/views/clientes/create.php
?>
<div class="bg-white p-6 rounded-lg shadow-xl">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Registro de Nuevo Cliente</h2>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>clientes/store" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                <label for="nombre_completo" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                <input type="text" name="nombre_completo" id="nombre_completo" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
            </div>
            
            <div>
                <label for="identificacion" class="block text-sm font-medium text-gray-700">Identificación (Cédula/ID)</label>
                <input type="text" name="identificacion" id="identificacion" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
            </div>
            
            <div>
                <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
            </div>
            
            <div class="col-span-1 md:col-span-2">
                <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                <textarea name="direccion" id="direccion" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2"></textarea>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end">
            <a href="<?= BASE_URL ?>clientes" class="mr-4 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-user-plus mr-2"></i> Registrar Cliente
            </button>
        </div>
    </form>
</div>