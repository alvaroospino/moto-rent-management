<?php
// /app/views/gastos/show.php
?>

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Detalles del Gasto</h2>
        <a href="<?= BASE_URL ?>gastos" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Información General</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-600">ID del Gasto</label>
                    <p class="text-sm text-gray-900">#<?= htmlspecialchars($gasto['id_gasto']) ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Fecha del Gasto</label>
                    <p class="text-sm text-gray-900"><?= htmlspecialchars(date('d/m/Y', strtotime($gasto['fecha_gasto']))) ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Categoría</label>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        <?= htmlspecialchars(ucfirst($gasto['categoria'])) ?>
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Monto</label>
                    <p class="text-lg font-bold text-green-600">$<?= number_format($gasto['monto'], 2) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Información Adicional</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Moto Asociada</label>
                    <p class="text-sm text-gray-900">
                        <?php if ($gasto['id_moto']): ?>
                            <?= htmlspecialchars($gasto['moto_placa'] ?? 'N/A') ?> - <?= htmlspecialchars($gasto['moto_marca'] ?? '') ?>
                        <?php else: ?>
                            <span class="text-gray-500">Gasto General (No asociado a moto específica)</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Registrado por</label>
                    <p class="text-sm text-gray-900"><?= htmlspecialchars($gasto['usuario_nombre']) ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Fecha de Registro</label>
                    <p class="text-sm text-gray-900">
                        <?php if (isset($gasto['created_at'])): ?>
                            <?= htmlspecialchars(date('d/m/Y H:i', strtotime($gasto['created_at']))) ?>
                        <?php else: ?>
                            No disponible
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Descripción Detallada</h3>
            <p class="text-sm text-gray-900 leading-relaxed">
                <?= nl2br(htmlspecialchars($gasto['descripcion'])) ?>
            </p>
        </div>
    </div>

    <div class="mt-8 flex justify-end space-x-4">
        <a href="<?= BASE_URL ?>gastos/edit/<?= $gasto['id_gasto'] ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
            <i class="fas fa-edit mr-2"></i> Editar Gasto
        </a>
        <form action="<?= BASE_URL ?>gastos/destroy/<?= $gasto['id_gasto'] ?>" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este gasto? Esta acción no se puede deshacer.')">
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-trash mr-2"></i> Eliminar Gasto
            </button>
        </form>
    </div>
</div>
