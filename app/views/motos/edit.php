<?php
// /app/views/motos/edit.php
// $moto viene inyectado del controlador
?>

<div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Editar Moto</h2>
        <a href="<?= BASE_URL ?>motos" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-300">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>

    <?php if (isset($_GET['error']) && $_GET['error']): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>motos/update/<?= $moto['id_moto'] ?>" method="POST" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Placa</label>
                <input type="text" name="placa" value="<?= htmlspecialchars($moto['placa']) ?>" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Marca</label>
                <input type="text" name="marca" value="<?= htmlspecialchars($moto['marca']) ?>" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Modelo</label>
                <input type="text" name="modelo" value="<?= htmlspecialchars($moto['modelo']) ?>" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Valor de Adquisición ($)</label>
                <input type="number" name="valor_adquisicion" value="<?= htmlspecialchars($moto['valor_adquisicion']) ?>" step="0.01" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="estado" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="activo" <?= $moto['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="inactivo" <?= $moto['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    <option value="mantenimiento" <?= $moto['estado'] === 'mantenimiento' ? 'selected' : '' ?>>En Mantenimiento</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Adquisición</label>
                <input type="date" name="fecha_adquisicion" value="<?= htmlspecialchars($moto['fecha_adquisicion']) ?>" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="<?= BASE_URL ?>motos" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-300">
                Cancelar
            </a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition duration-300">
                <i class="fas fa-save mr-2"></i> Actualizar Moto
            </button>
        </div>
    </form>
</div>
