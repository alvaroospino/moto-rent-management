<?php
// /app/views/contratos/create.php
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-600 to-purple-600">
                <h1 class="text-2xl font-bold text-white">Crear Nuevo Contrato</h1>
                <p class="text-indigo-100 mt-1">Complete la información del contrato de alquiler</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-6 mt-4 rounded" role="alert">
                    <span class="block sm:inline"><?= htmlspecialchars($_SESSION['error']) ?></span>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>contratos/store" method="POST" class="p-6 space-y-6" id="contratoForm">
                <!-- Información del Cliente -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user mr-2 text-indigo-600"></i>
                        Información del Cliente
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="id_cliente" class="block text-sm font-medium text-gray-700 mb-1">Cliente *</label>
                            <select name="id_cliente" id="id_cliente" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Seleccione un cliente</option>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?= $cliente['id_cliente'] ?>">
                                        <?= htmlspecialchars($cliente['nombre_completo']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Información del Vehículo -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-motorcycle mr-2 text-indigo-600"></i>
                        Información del Vehículo
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="id_moto" class="block text-sm font-medium text-gray-700 mb-1">Vehículo *</label>
                            <select name="id_moto" id="id_moto" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Seleccione un vehículo</option>
                                <?php foreach ($motos as $moto): ?>
                                    <option value="<?= $moto['id_moto'] ?>" data-valor="<?= $moto['valor_adquisicion'] ?>">
                                        <?= htmlspecialchars(ucfirst($moto['marca']) . ' ' . $moto['modelo'] . ' (Placa: ' . $moto['placa'] . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio *</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                </div>

                <!-- Información Financiera -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-calculator mr-2 text-indigo-600"></i>
                        Información Financiera
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="valor_vehiculo" class="block text-sm font-medium text-gray-700 mb-1">Valor del Vehículo (Costo de Adquisición)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input type="number" name="valor_vehiculo" id="valor_vehiculo" readonly
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                                       placeholder="0" min="0" step="0.01">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Este es el costo de la moto para seguimiento de inversión</p>
                        </div>
                        <div>
                            <label for="abono_capital_mensual" class="block text-sm font-medium text-gray-700 mb-1">Abono Capital Mensual *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input type="number" name="abono_capital_mensual" id="abono_capital_mensual" required
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="0" min="0" step="0.01">
                            </div>
                        </div>
                        <div>
                            <label for="ganancia_mensual" class="block text-sm font-medium text-gray-700 mb-1">Ganancia Mensual *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input type="number" name="ganancia_mensual" id="ganancia_mensual" required
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="0" min="0" step="0.01">
                            </div>
                        </div>
                        <div>
                            <label for="plazo_meses" class="block text-sm font-medium text-gray-700 mb-1">Plazo (Meses) *</label>
                            <input type="number" name="plazo_meses" id="plazo_meses" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="0" min="1" max="120">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="cuota_mensual" class="block text-sm font-medium text-gray-700 mb-1">Cuota Mensual Total *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" name="cuota_mensual" id="cuota_mensual" required readonly
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                                   placeholder="0" min="0" step="0.01">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Calculado automáticamente: Abono Capital + Ganancia</p>
                    </div>
                </div>

                <!-- Cálculos Automáticos -->
                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-400">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-line mr-2 text-blue-600"></i>
                        Resumen del Contrato
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-3 rounded-md border">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total a Pagar</label>
                            <div class="text-2xl font-bold text-blue-600" id="totalPagar">$0</div>
                        </div>
                        <div class="bg-white p-3 rounded-md border">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ganancia Estimada</label>
                            <div class="text-2xl font-bold text-green-600" id="ganancia">$0</div>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-600">
                        <p><strong>Nota:</strong> El cliente paga la cuota mensual acordada. El valor del vehículo es solo para seguimiento interno.</p>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-sticky-note mr-2 text-indigo-600"></i>
                        Observaciones
                    </h3>
                    <div>
                        <textarea name="observaciones" id="observaciones" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Observaciones adicionales del contrato..."></textarea>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="<?= BASE_URL ?>contratos" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-300">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-save mr-2"></i> Crear Contrato
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Función para calcular y actualizar los valores
function calcularResumen() {
    const valorVehiculo = parseFloat(document.getElementById('valor_vehiculo').value) || 0;
    const abonoCapital = parseFloat(document.getElementById('abono_capital_mensual').value) || 0;
    const ganancia = parseFloat(document.getElementById('ganancia_mensual').value) || 0;
    const plazoMeses = parseInt(document.getElementById('plazo_meses').value) || 0;

    // Calcular cuota mensual total
    const cuotaMensual = abonoCapital + ganancia;
    document.getElementById('cuota_mensual').value = cuotaMensual.toFixed(2);

    if (cuotaMensual > 0 && plazoMeses > 0) {
        // Calcular total a pagar por el cliente
        const totalPagar = cuotaMensual * plazoMeses;
        // Calcular ganancia estimada (total pagado - costo de la moto)
        const gananciaTotal = totalPagar - valorVehiculo;

        // Actualizar la interfaz
        document.getElementById('totalPagar').textContent = '$' + totalPagar.toLocaleString('es-CO', {maximumFractionDigits: 0});
        document.getElementById('ganancia').textContent = '$' + gananciaTotal.toLocaleString('es-CO', {maximumFractionDigits: 0});
    } else {
        document.getElementById('totalPagar').textContent = '$0';
        document.getElementById('ganancia').textContent = '$0';
    }
}

// Event listeners para recalcular automáticamente
document.getElementById('abono_capital_mensual').addEventListener('input', calcularResumen);
document.getElementById('ganancia_mensual').addEventListener('input', calcularResumen);
document.getElementById('plazo_meses').addEventListener('input', calcularResumen);

// Calcular inicialmente
calcularResumen();

// Auto-fill valor del vehículo cuando se selecciona una moto
document.getElementById('id_moto').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const valor = selectedOption.getAttribute('data-valor');
    if (valor) {
        document.getElementById('valor_vehiculo').value = valor;
        calcularResumen();
    }
});
</script>
