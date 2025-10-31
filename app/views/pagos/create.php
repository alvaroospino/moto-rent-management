<?php
// /app/views/pagos/create.php
$contentView = __DIR__ . '/create_content.php';
?>

<div class="container mx-auto px-4 py-8">
    <!-- Botón de Regreso Mejorado -->
    <div class="max-w-4xl mx-auto mb-4">
        <a href="<?= BASE_URL ?>contratos/detail/<?= $contrato['id_contrato'] ?? '' ?>"
           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-200 hover:border-gray-300 rounded-lg shadow-sm transition-all duration-200 hover:shadow-md hover:scale-105 group">
            <i class="fas fa-arrow-left mr-2 text-gray-500 group-hover:text-gray-700 transition-colors duration-200 text-sm"></i>
            <span class="text-gray-600 font-medium group-hover:text-gray-800 transition-colors duration-200 text-sm">Volver</span>
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        
        <!-- Formulario Principal -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden transform hover:scale-[1.01] transition-all duration-300">
                <div class="px-6 md:px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center">
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3 md:mr-4 shadow-lg">
                            <i class="fas fa-credit-card text-sm md:text-lg text-white"></i>
                        </div>
                        Información del Pago
                    </h2>
                    <p class="text-gray-600 mt-2 ml-11 md:ml-14 text-sm md:text-base">Complete los detalles del pago a registrar</p>
                </div>
                <div class="p-6 md:p-8">
                    <form action="<?= BASE_URL ?>pagos/store" method="POST" class="space-y-6 md:space-y-8">
                        <input type="hidden" name="id_contrato" value="<?= $contrato['id_contrato'] ?? '' ?>">

                        <!-- Fecha del Pago -->
                        <div class="group">
                            <label for="fecha_pago" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                                Fecha del Pago *
                            </label>
                            <div class="relative">
                                <input type="date" id="fecha_pago" name="fecha_pago"
                                       value="<?= htmlspecialchars($_GET['fecha'] ?? date('Y-m-d')) ?>"
                                       class="block w-full pl-4 pr-12 py-3 md:py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300 text-base md:text-lg hover:border-green-300" required>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar text-gray-400"></i>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Seleccione la fecha a la que corresponde este pago
                            </p>
                        </div>

                        <!-- Monto del Pago -->
                        <div class="group">
                            <label for="monto_pago" class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>
                                Monto del Pago *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-lg md:text-xl font-bold">$</span>
                                </div>
                                <input type="number" id="monto_pago" name="monto_pago" step="0.01" min="0.01"
                                       class="pl-10 md:pl-12 pr-12 py-3 md:py-4 block w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300 text-base md:text-lg hover:border-green-300"
                                       placeholder="0.00" required>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <i class="fas fa-coins text-gray-400"></i>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Ingrese el monto que el cliente está pagando hoy
                            </p>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 md:pt-8 border-t border-gray-100">
                            <a href="<?= BASE_URL ?>contratos/detail/<?= $contrato['id_contrato'] ?? '' ?>"
                               class="bg-gradient-to-r from-gray-200 to-gray-300 hover:from-gray-300 hover:to-gray-400 text-gray-800 font-bold py-3 md:py-4 px-6 md:px-8 rounded-xl transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center text-sm md:text-base">
                                <i class="fas fa-times mr-2"></i> Cancelar
                            </a>
                            <button type="submit"
                                    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-3 md:py-4 px-6 md:px-8 rounded-xl transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center text-sm md:text-base">
                                <i class="fas fa-save mr-2"></i> Registrar Pago
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
