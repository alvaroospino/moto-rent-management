<?php
// /app/views/gastos/show.php
?>

<!-- Header Section -->
<div class="bg-gradient-to-r from-yellow-500 via-amber-500 to-orange-500 rounded-xl shadow-lg p-6 mb-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold flex items-center">
                <i class="fas fa-receipt mr-3 text-yellow-100"></i>
                Detalles del Gasto
            </h1>
            <p class="text-yellow-100 mt-2">Información completa del gasto #<?= htmlspecialchars($gasto['id_gasto']) ?></p>
        </div>
        <a href="<?= BASE_URL ?>gastos" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center backdrop-blur-sm">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver a Gastos
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left Column - Main Info -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Amount Card -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-green-800 mb-2 flex items-center">
                        <i class="fas fa-dollar-sign mr-2 text-green-600"></i>
                        Monto del Gasto
                    </h3>
                    <p class="text-3xl font-bold text-green-700">$<?= number_format($gasto['monto'], 2) ?></p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <i class="fas fa-money-bill-wave text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Description Card -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-align-left mr-2 text-indigo-600"></i>
                Descripción Detallada
            </h3>
            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-lg p-4">
                <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                    <?= nl2br(htmlspecialchars($gasto['descripcion'])) ?>
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-cogs mr-2 text-gray-600"></i>
                Acciones
            </h3>
            <div class="flex flex-wrap gap-4">
                <a href="<?= BASE_URL ?>gastos/edit/<?= $gasto['id_gasto'] ?>"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-600 hover:to-amber-600 text-white rounded-xl font-semibold transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-edit mr-2"></i>
                    Editar Gasto
                </a>
                <form action="<?= BASE_URL ?>gastos/destroy/<?= $gasto['id_gasto'] ?>" method="POST" class="inline"
                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar este gasto? Esta acción no se puede deshacer.')">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white rounded-xl font-semibold transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-trash mr-2"></i>
                        Eliminar Gasto
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column - Details -->
    <div class="space-y-6">

        <!-- General Information -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                Información General
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <span class="text-sm font-medium text-blue-700">ID del Gasto</span>
                    <span class="text-sm font-bold text-blue-800">#<?= htmlspecialchars($gasto['id_gasto']) ?></span>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <span class="text-sm font-medium text-green-700">Fecha del Gasto</span>
                    <span class="text-sm font-bold text-green-800 flex items-center">
                        <i class="fas fa-calendar-day mr-1"></i>
                        <?= htmlspecialchars(date('d/m/Y', strtotime($gasto['fecha_gasto']))) ?>
                    </span>
                </div>
                <div class="p-3 bg-purple-50 rounded-lg">
                    <span class="text-sm font-medium text-purple-700 block mb-2">Categoría</span>
                    <?php
                    $categoriaClass = 'bg-gray-100 text-gray-800';
                    switch($gasto['categoria']) {
                        case 'mantenimiento': $categoriaClass = 'bg-blue-100 text-blue-800'; break;
                        case 'operativo': $categoriaClass = 'bg-green-100 text-green-800'; break;
                        case 'general': $categoriaClass = 'bg-purple-100 text-purple-800'; break;
                        case 'impuestos': $categoriaClass = 'bg-red-100 text-red-800'; break;
                    }
                    ?>
                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full <?= $categoriaClass ?>">
                        <i class="fas fa-tag mr-1"></i>
                        <?= htmlspecialchars(ucfirst($gasto['categoria'])) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-plus-circle mr-2 text-indigo-600"></i>
                Información Adicional
            </h3>
            <div class="space-y-4">
                <div class="p-3 bg-indigo-50 rounded-lg">
                    <span class="text-sm font-medium text-indigo-700 block mb-2">Moto Asociada</span>
                    <span class="text-sm text-indigo-800">
                        <?php if ($gasto['id_moto']): ?>
                            <div class="flex items-center">
                                <i class="fas fa-motorcycle mr-2 text-indigo-600"></i>
                                <?= htmlspecialchars($gasto['moto_placa'] ?? 'N/A') ?> - <?= htmlspecialchars($gasto['moto_marca'] ?? '') ?>
                            </div>
                        <?php else: ?>
                            <span class="italic text-indigo-600">Gasto General (No asociado a moto específica)</span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Registrado por</span>
                    <span class="text-sm font-bold text-gray-800 flex items-center">
                        <i class="fas fa-user mr-1"></i>
                        <?= htmlspecialchars($gasto['usuario_nombre']) ?>
                    </span>
                </div>
                <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                    <span class="text-sm font-medium text-orange-700">Fecha de Registro</span>
                    <span class="text-sm font-bold text-orange-800 flex items-center">
                        <i class="fas fa-clock mr-1"></i>
                        <?php if (isset($gasto['created_at'])): ?>
                            <?= htmlspecialchars(date('d/m/Y H:i', strtotime($gasto['created_at']))) ?>
                        <?php else: ?>
                            No disponible
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-gradient-to-br from-yellow-50 to-amber-50 border border-yellow-200 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-yellow-800 mb-4 flex items-center">
                <i class="fas fa-chart-bar mr-2 text-yellow-600"></i>
                Estadísticas Rápidas
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-yellow-700">Tipo de Gasto</span>
                    <span class="text-sm font-semibold text-yellow-800">
                        <?php if ($gasto['id_moto']): ?>Específico<?php else: ?>General<?php endif; ?>
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-yellow-700">Días desde registro</span>
                    <span class="text-sm font-semibold text-yellow-800">
                        <?php
                        $fechaRegistro = isset($gasto['created_at']) ? strtotime($gasto['created_at']) : time();
                        $dias = floor((time() - $fechaRegistro) / (60*60*24));
                        echo $dias . ' día' . ($dias != 1 ? 's' : '');
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
