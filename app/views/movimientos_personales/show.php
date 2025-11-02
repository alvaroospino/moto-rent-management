<?php
// /app/views/movimientos_personales/show.php
?>

<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 rounded-xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-eye text-3xl mr-4 text-green-100"></i>
                <div>
                    <h1 class="text-3xl font-bold">Detalles del Movimiento</h1>
                    <p class="text-green-100 mt-1">Información completa del movimiento #<?= htmlspecialchars($movimiento['id_movimiento']) ?></p>
                </div>
            </div>
            <a href="<?= BASE_URL ?>movimientos-personales/edit/<?= $movimiento['id_movimiento'] ?>"
               class="bg-white text-green-600 hover:bg-green-50 px-4 py-2 rounded-lg font-semibold transition duration-200 flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Editar
            </a>
        </div>
    </div>

    <!-- Details Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Movement Type Badge -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Información del Movimiento</h2>
                <?php if ($movimiento['tipo'] === 'ingreso'): ?>
                    <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-arrow-up mr-2"></i>
                        Ingreso
                    </span>
                <?php else: ?>
                    <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                        <i class="fas fa-arrow-down mr-2"></i>
                        Gasto
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Details Content -->
        <div class="p-6 space-y-6">
            <!-- ID and Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-hashtag text-gray-500 mr-2"></i>
                        <span class="text-sm font-medium text-gray-700">ID del Movimiento</span>
                    </div>
                    <p class="text-lg font-semibold text-gray-900">#<?= htmlspecialchars($movimiento['id_movimiento']) ?></p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-calendar text-gray-500 mr-2"></i>
                        <span class="text-sm font-medium text-gray-700">Fecha del Movimiento</span>
                    </div>
                    <p class="text-lg font-semibold text-gray-900">
                        <?= htmlspecialchars(date('d/m/Y', strtotime($movimiento['fecha_movimiento']))) ?>
                    </p>
                </div>
            </div>

            <!-- Amount -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center mb-2">
                            <i class="fas fa-dollar-sign text-green-600 mr-2"></i>
                            <span class="text-sm font-medium text-green-700">Monto</span>
                        </div>
                        <p class="text-3xl font-bold <?= $movimiento['tipo'] === 'ingreso' ? 'text-green-600' : 'text-red-600' ?>">
                            $<?= number_format($movimiento['monto'], 2) ?>
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-green-600 font-medium mb-1">Tipo</div>
                        <div class="text-lg font-semibold text-gray-900">
                            <?= ucfirst($movimiento['tipo']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <i class="fas fa-align-left text-gray-500 mr-2"></i>
                    <span class="text-sm font-medium text-gray-700">Descripción</span>
                </div>
                <p class="text-gray-900 leading-relaxed">
                    <?= nl2br(htmlspecialchars($movimiento['descripcion'])) ?>
                </p>
            </div>

            <!-- User and Creation Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-user text-gray-500 mr-2"></i>
                        <span class="text-sm font-medium text-gray-700">Registrado por</span>
                    </div>
                    <p class="text-lg font-semibold text-gray-900">
                        <?= htmlspecialchars($movimiento['usuario_nombre']) ?>
                    </p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-clock text-gray-500 mr-2"></i>
                        <span class="text-sm font-medium text-gray-700">Fecha de Registro</span>
                    </div>
                    <p class="text-lg font-semibold text-gray-900">
                        <?= htmlspecialchars(date('d/m/Y H:i', strtotime($movimiento['creado_en']))) ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="<?= BASE_URL ?>movimientos-personales"
                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold transition duration-200 text-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al Listado
                </a>
                <div class="flex gap-2">
                    <a href="<?= BASE_URL ?>movimientos-personales/edit/<?= $movimiento['id_movimiento'] ?>"
                       class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-200 text-center">
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                    </a>
                    <form action="<?= BASE_URL ?>movimientos-personales/destroy/<?= $movimiento['id_movimiento'] ?>" method="POST" class="flex-1"
                          onsubmit="return confirm('¿Estás seguro de que deseas eliminar este movimiento? Esta acción no se puede deshacer.')">
                        <button type="submit"
                                class="w-full bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                            <i class="fas fa-trash mr-2"></i>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
