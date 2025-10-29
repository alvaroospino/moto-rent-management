<?php
// /app/views/gastos/edit.php
// $gasto viene inyectado del controlador
// $motos viene inyectado del controlador

$fechaHoy = date('Y-m-d');
$categorias = ['mantenimiento', 'operativo', 'general', 'impuestos'];
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Editar Gasto Operativo</h2>
        <a href="<?= BASE_URL ?>gastos/show/<?= $gasto['id_gasto'] ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>

    <p class="mb-4 text-sm text-gray-600 border-l-4 border-yellow-400 pl-3">
        **Importante:** Modifica los datos del gasto. Asegúrate de que la información sea correcta.
    </p>

    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>gastos/update/<?= $gasto['id_gasto'] ?>" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label for="monto" class="block text-sm font-medium text-gray-700">Monto del Gasto ($) (*)</label>
                <input type="number" step="0.01" name="monto" id="monto" required min="0.01"
                       value="<?= htmlspecialchars($gasto['monto']) ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 p-2" placeholder="Ej: 150.00">
            </div>

            <div>
                <label for="fecha_gasto" class="block text-sm font-medium text-gray-700">Fecha del Gasto (*)</label>
                <input type="date" name="fecha_gasto" id="fecha_gasto" required
                       value="<?= htmlspecialchars($gasto['fecha_gasto']) ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
            </div>

            <div>
                <label for="categoria" class="block text-sm font-medium text-gray-700">Categoría (*)</label>
                <select name="categoria" id="categoria" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                    <option value="">Seleccione Categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat ?>" <?= $gasto['categoria'] === $cat ? 'selected' : '' ?>>
                            <?= ucfirst($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="id_moto" class="block text-sm font-medium text-gray-700">Moto Asociada (Opcional)</label>
                <select name="id_moto" id="id_moto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                    <option value="">Gasto General (No asociado a una moto)</option>
                    <?php foreach ($motos as $moto): ?>
                        <option value="<?= $moto['id_moto'] ?>" <?= $gasto['id_moto'] == $moto['id_moto'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($moto['placa']) ?> - <?= htmlspecialchars($moto['marca']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="md:col-span-2">
                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción Detallada (*)</label>
                <textarea name="descripcion" id="descripcion" rows="3" required
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2"
                          placeholder="Ej: Cambio de aceite y filtro para la moto placa XYZ123."><?= htmlspecialchars($gasto['descripcion']) ?></textarea>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="<?= BASE_URL ?>gastos/show/<?= $gasto['id_gasto'] ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                Cancelar
            </a>
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i> Actualizar Gasto
            </button>
        </div>
    </form>
</div>
