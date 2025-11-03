<?php
// /app/views/contratos/edit.php
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-purple-600">
                <h1 class="text-2xl font-bold text-white">Editar Contrato</h1>
                <p class="text-blue-100 mt-1">Modifique la información del contrato de alquiler</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-6 mt-4 rounded" role="alert">
                    <span class="block sm:inline"><?= htmlspecialchars($_SESSION['error']) ?></span>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mx-6 mt-4 rounded" role="alert">
                    <span class="block sm:inline"><?= htmlspecialchars($_SESSION['success']) ?></span>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>contratos/update/<?= $contrato['id_contrato'] ?>" method="POST" class="p-6 space-y-6" id="contratoForm">
                <!-- Información del Cliente (Solo lectura) -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-600"></i>
                        Información del Cliente
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                            <input type="text" value="<?= htmlspecialchars($cliente['nombre_completo']) ?>" readonly
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100">
                        </div>
                    </div>
                </div>

                <!-- Información del Vehículo (Solo lectura) -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-motorcycle mr-2 text-blue-600"></i>
                        Información del Vehículo
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Vehículo</label>
                            <input type="text" value="<?= htmlspecialchars(ucfirst($moto['marca']) . ' ' . $moto['modelo'] . ' (Placa: ' . $moto['placa'] . ')') ?>" readonly
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio</label>
                            <input type="date" value="<?= htmlspecialchars($contrato['fecha_inicio']) ?>" readonly
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100">
                        </div>
                    </div>
                </div>

                <!-- Información Financiera (Editable) -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-calculator mr-2 text-blue-600"></i>
                        Información Financiera
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="valor_vehiculo" class="block text-sm font-medium text-gray-700 mb-1">Valor del Vehículo (Costo de Adquisición) *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input type="number" name="valor_vehiculo" id="valor_vehiculo" required
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0" min="0" step="0.01" value="<?= htmlspecialchars($contrato['valor_vehiculo']) ?>">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Valor actual de la moto para seguimiento de inversión</p>
                        </div>
                        <div>
                            <label for="abono_capital_mensual" class="block text-sm font-medium text-gray-700 mb-1">Abono Capital Mensual *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input type="number" name="abono_capital_mensual" id="abono_capital_mensual" required
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0" min="0" step="0.01" value="<?= htmlspecialchars($contrato['abono_capital_mensual']) ?>">
                            </div>
                        </div>
                        <div>
                            <label for="ganancia_mensual" class="block text-sm font-medium text-gray-700 mb-1">Ganancia Mensual *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input type="number" name="ganancia_mensual" id="ganancia_mensual" required
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0" min="0" step="0.01" value="<?= htmlspecialchars($contrato['ganancia_mensual']) ?>">
                            </div>
                        </div>
                        <div>
                            <label for="plazo_meses" class="block text-sm font-medium text-gray-700 mb-1">Plazo (Meses)</label>
                            <input type="number" name="plazo_meses" id="plazo_meses" readonly
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                                   placeholder="0" min="1" max="120" value="<?= htmlspecialchars($contrato['plazo_meses']) ?>">
                            <p class="text-xs text-gray-500 mt-1">El plazo no se puede modificar</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="cuota_mensual" class="block text-sm font-medium text-gray-700 mb-1">Cuota Mensual Total *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" name="cuota_mensual" id="cuota_mensual" required readonly
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100"
                                   placeholder="0" min="0" step="0.01" value="<?= htmlspecialchars($contrato['cuota_mensual']) ?>">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Calculado automáticamente: Abono Capital + Ganancia</p>
                    </div>
                </div>

                <!-- Cálculos Automáticos -->
                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-400">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-line mr-2 text-blue-600"></i>
                        Resumen del Contrato Actualizado
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-3 rounded-md border">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total a Pagar</label>
                            <div class="text-2xl font-bold text-blue-600" id="totalPagar">$<?= number_format($contrato['cuota_mensual'] * $contrato['plazo_meses'], 0, ',', '.') ?></div>
                        </div>
                        <div class="bg-white p-3 rounded-md border">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ganancia Estimada</label>
                            <div class="text-2xl font-bold text-green-600" id="ganancia">$<?= number_format(($contrato['cuota_mensual'] * $contrato['plazo_meses']) - $contrato['valor_vehiculo'], 0, ',', '.') ?></div>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-600">
                        <p><strong>Nota:</strong> El cliente paga la cuota mensual acordada. El valor del vehículo es solo para seguimiento interno.</p>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-sticky-note mr-2 text-blue-600"></i>
                        Observaciones
                    </h3>
                    <div>
                        <textarea name="observaciones" id="observaciones" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Observaciones adicionales del contrato..."><?= htmlspecialchars($contrato['observaciones'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="<?= BASE_URL ?>contratos/details/<?= $contrato['id_contrato'] ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-300">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </a>
                    <button type="submit" id="submitBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-save mr-2"></i> Actualizar Contrato
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
document.getElementById('valor_vehiculo').addEventListener('input', calcularResumen);

// Calcular inicialmente
calcularResumen();

// Desactivar botón al enviar el formulario para evitar duplicaciones
document.getElementById('contratoForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Actualizando Contrato...';
});
</script>
