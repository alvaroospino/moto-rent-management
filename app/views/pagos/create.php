<?php
// /app/views/pagos/create.php
$contentView = __DIR__ . '/create_content.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-emerald-600">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Registrar Pago</h1>
                        <p class="text-green-100 mt-1">Contrato #<?= htmlspecialchars($contrato['id'] ?? 'N/A') ?></p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="<?= BASE_URL ?>contratos/detail/<?= $contrato['id'] ?? '' ?>" class="bg-white text-green-600 hover:bg-gray-100 font-semibold py-2 px-4 rounded-lg transition duration-300">
                            <i class="fas fa-arrow-left mr-2"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Pago -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-dollar-sign mr-2 text-green-600"></i>
                    Información del Pago
                </h2>
            </div>
            <div class="p-6">
                <form action="<?= BASE_URL ?>pagos/store" method="POST" class="space-y-6">
                    <input type="hidden" name="id_contrato" value="<?= $contrato['id'] ?? '' ?>">

                    <!-- Fecha del Pago -->
                    <div>
                        <label for="fecha_pago" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha del Pago *
                        </label>
                        <input type="date" id="fecha_pago" name="fecha_pago"
                               value="<?= htmlspecialchars($_GET['fecha'] ?? date('Y-m-d')) ?>"
                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" required>
                        <p class="mt-1 text-sm text-gray-500">
                            Seleccione la fecha a la que corresponde este pago.
                        </p>
                    </div>

                    <!-- Monto del Pago -->
                    <div>
                        <label for="monto_pago" class="block text-sm font-medium text-gray-700 mb-2">
                            Monto del Pago *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" id="monto_pago" name="monto_pago" step="0.01" min="0.01"
                                   class="pl-7 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                   placeholder="0.00" required>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            Ingrese el monto que el cliente está pagando hoy.
                        </p>
                    </div>

                    <!-- Información del Contrato (Solo lectura) -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Información del Contrato</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Cliente:</span>
                                <span class="font-medium ml-2"><?= htmlspecialchars($cliente['nombre_completo'] ?? 'N/A') ?></span>
                            </div>
                            <div>
                                <span class="text-gray-600">Vehículo:</span>
                                <span class="font-medium ml-2">
                                    <?= htmlspecialchars(($moto['marca'] ?? '') . ' ' . ($moto['modelo'] ?? '')) ?>
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600">Saldo Capital Restante:</span>
                                <span class="font-medium ml-2 text-orange-600">
                                    $<?= number_format($contrato['saldo_restante'] ?? 0, 0, ',', '.') ?>
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600">Cuota Diaria Capital:</span>
                                <span class="font-medium ml-2 text-blue-600">
                                    $<?= number_format($contrato['cuota_diaria_capital'] ?? 0, 0, ',', '.') ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="<?= BASE_URL ?>contratos/detail/<?= $contrato['id'] ?? '' ?>"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-300">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
                            <i class="fas fa-save mr-2"></i> Registrar Pago
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
