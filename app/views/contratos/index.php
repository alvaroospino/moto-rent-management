<?php
// /app/views/contratos/index.php
?>

<div class="container mx-auto px-4 py-8">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            <span class="block sm:inline"><?= htmlspecialchars($_SESSION['success']) ?></span>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <span class="block sm:inline"><?= htmlspecialchars($_SESSION['error']) ?></span>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Estadísticas del Dashboard -->
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-col items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-file-contract text-2xl"></i>
                </div>
                <div class="mt-4 text-center">
                    <p class="text-sm font-medium text-gray-600">Contratos Activos</p>
                    <p class="text-sm md:text-lg font-bold text-gray-900"><?= $stats['total_contratos_activos'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-col items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
                <div class="mt-4 text-center">
                    <p class="text-sm font-medium text-gray-600">Valor Total</p>
                    <p class="text-sm md:text-lg font-bold text-gray-900">$<?= number_format($stats['valor_total_contratos'] ?? 0, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-col items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
                <div class="mt-4 text-center">
                    <p class="text-sm font-medium text-gray-600">Por Cobrar</p>
                    <p class="text-sm md:text-lg font-bold text-gray-900">$<?= number_format($stats['saldo_total_por_cobrar'] ?? 0, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-col items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
                <div class="mt-4 text-center">
                    <p class="text-sm font-medium text-gray-600">Pagos del Mes</p>
                    <p class="text-sm md:text-lg font-bold text-gray-900">$<?= number_format($stats['pagos_mes_actual'] ?? 0, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    <?php if (!empty($alertas['contratos_venciendo'])): ?>
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    <strong>Contratos próximos a vencer:</strong>
                    <?php foreach ($alertas['contratos_venciendo'] as $contrato): ?>
                        Contrato #<?= $contrato['id_contrato'] ?> (<?= $contrato['dias_restantes'] ?> días restantes)
                    <?php endforeach; ?>
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($alertas['contratos_sin_pagos'])): ?>
    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    <strong>Contratos sin pagos recientes:</strong>
                    <?php foreach ($alertas['contratos_sin_pagos'] as $contrato): ?>
                        Contrato #<?= $contrato['id_contrato'] ?> (<?= $contrato['dias_sin_pago'] ?> días sin pago)
                    <?php endforeach; ?>
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Acciones Rápidas -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones Rápidas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="<?= BASE_URL ?>contratos/create" class="flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 shadow-lg hover:shadow-xl">
                <i class="fas fa-plus mr-2"></i> Nuevo Contrato
            </a>
            <a href="<?= BASE_URL ?>reportes" class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 shadow-lg hover:shadow-xl">
                <i class="fas fa-chart-bar mr-2"></i> Ver Reportes
            </a>
            <a href="<?= BASE_URL ?>clientes" class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 shadow-lg hover:shadow-xl">
                <i class="fas fa-users mr-2"></i> Gestionar Clientes
            </a>
        </div>
    </div>

    
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Contratos Activos</h2>
        </div>
        <div class="p-6">
            <?php if (empty($contratos)): ?>
                <p class="text-center text-gray-500">No hay contratos activos registrados.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($contratos as $contrato): ?>
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-200">
                            <div class="p-4">
                                <!-- Header -->
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="text-base font-semibold text-gray-900">#<?= htmlspecialchars($contrato['id_contrato']) ?> - <?= htmlspecialchars($contrato['cliente_nombre']) ?></h3>
                                        <p class="text-sm text-gray-600 flex items-center mt-1">
                                            <i class="fas fa-motorcycle text-gray-400 mr-2"></i>
                                            <?= htmlspecialchars($contrato['moto_marca'] . ' ' . $contrato['moto_modelo']) ?>
                                        </p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        <?php
                                        switch($contrato['estado']) {
                                            case 'activo':
                                                echo 'bg-green-100 text-green-800';
                                                break;
                                            case 'finalizado':
                                                echo 'bg-blue-100 text-blue-800';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= ucfirst(htmlspecialchars($contrato['estado'])) ?>
                                    </span>
                                </div>

                                <!-- Financial Info -->
                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-sm text-gray-600">Valor Vehículo:</span>
                                        <span class="font-semibold text-gray-900">$<?= number_format($contrato['valor_vehiculo'], 0, ',', '.') ?></span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                        <span class="text-sm text-gray-600">Cuota Mensual:</span>
                                        <span class="font-semibold text-gray-900">$<?= number_format($contrato['cuota_mensual'], 0, ',', '.') ?></span>
                                    </div>
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-sm text-gray-600">Saldo Restante:</span>
                                        <span class="font-semibold text-red-600">$<?= number_format($contrato['saldo_por_pagar'] ?? max(0, ($contrato['valor_vehiculo'] ?? 0) - ($contrato['saldo_restante'] ?? 0)), 0, ',', '.') ?></span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-2">
                                    <a href="<?= BASE_URL ?>contratos/detail/<?= $contrato['id_contrato'] ?>" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-3 rounded-md transition duration-200 flex items-center justify-center text-sm">
                                        <i class="fas fa-eye mr-2"></i> Ver
                                    </a>
                                    <a href="<?= BASE_URL ?>pagos/contrato/<?= $contrato['id_contrato'] ?>" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-3 rounded-md transition duration-200 flex items-center justify-center text-sm">
                                        <i class="fas fa-dollar-sign mr-2"></i> Pagos
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
