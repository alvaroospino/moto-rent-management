<?php
// /app/views/motos/create.php
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="<?= BASE_URL ?>motos"
                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-100 rounded-md transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Regresar
                </a>
                <div class="h-6 w-px bg-gray-300"></div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Nuevo Activo</h1>
                    <p class="text-sm text-gray-600">Registro de motocicleta al inventario</p>
                </div>
            </div>
            <div class="flex items-center justify-center w-12 h-12 bg-indigo-100 rounded-lg">
                <i class="fas fa-plus-circle text-indigo-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <?php if (isset($_GET['error'])): ?>
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-400 mr-3"></i>
                <p class="text-sm text-red-700 font-medium"><?= htmlspecialchars($_GET['error']) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <form action="<?= BASE_URL ?>motos/store" method="POST" class="divide-y divide-gray-200">
            <!-- Información Básica -->
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center w-8 h-8 bg-indigo-100 rounded-lg mr-3">
                        <i class="fas fa-info-circle text-indigo-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Información Básica</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Placa -->
                    <div class="md:col-span-2 lg:col-span-3">
                        <label for="placa" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-id-card mr-1 text-indigo-500"></i>
                            Número de Placa <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="placa" id="placa" required
                               class="block w-full px-4 py-3 text-base font-mono uppercase border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                               placeholder="ABC-123" maxlength="10">
                        <p class="mt-1 text-xs text-gray-500">Identificador único del vehículo</p>
                    </div>

                    <!-- Marca -->
                    <div>
                        <label for="marca" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-1 text-green-500"></i>
                            Marca <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="marca" id="marca" required
                               class="block w-full px-4 py-3 text-base capitalize border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 transition-colors"
                               placeholder="Yamaha, Honda...">
                    </div>

                    <!-- Modelo -->
                    <div>
                        <label for="modelo" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-cogs mr-1 text-blue-500"></i>
                            Modelo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="modelo" id="modelo" required
                               class="block w-full px-4 py-3 text-base capitalize border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="FZ-16, CBR 150...">
                    </div>
                </div>
            </div>

            <!-- Información Financiera -->
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg mr-3">
                        <i class="fas fa-dollar-sign text-green-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Información Financiera</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Valor de Adquisición -->
                    <div>
                        <label for="valor_adquisicion" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-money-bill-wave mr-1 text-emerald-500"></i>
                            Valor de Adquisición <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-medium">$</span>
                            </div>
                            <input type="number" step="0.01" name="valor_adquisicion" id="valor_adquisicion" required
                                   class="block w-full pl-8 pr-3 py-3 text-base border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                   placeholder="0.00" min="0">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Valor base para rentabilidad</p>
                    </div>

                    <!-- Fecha de Adquisición -->
                    <div>
                        <label for="fecha_adquisicion" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-1 text-purple-500"></i>
                            Fecha de Adquisición <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="fecha_adquisicion" id="fecha_adquisicion" required
                               class="block w-full px-3 py-3 text-base border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition-colors"
                               max="<?= date('Y-m-d') ?>">
                        <p class="mt-1 text-xs text-gray-500">Fecha de incorporación al inventario</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                <a href="<?= BASE_URL ?>motos"
                   class="inline-flex items-center justify-center px-6 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors submit-btn">
                    <i class="fas fa-save mr-2"></i>
                    <span class="submit-text">Registrar Activo</span>
                    <div class="submit-spinner hidden ml-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-formatear placa en mayúsculas
document.getElementById('placa').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});

// Auto-capitalizar marca y modelo
['marca', 'modelo'].forEach(function(id) {
    document.getElementById(id).addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\b\w/g, function(l) { return l.toUpperCase(); });
    });
});

// Loading effect on form submit
document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = document.querySelector('.submit-btn');
    const submitText = document.querySelector('.submit-text');
    const submitSpinner = document.querySelector('.submit-spinner');

    // Disable button and show loading
    submitBtn.disabled = true;
    submitText.textContent = 'Registrando...';
    submitSpinner.classList.remove('hidden');

    // Re-enable after 3 seconds (in case of error)
    setTimeout(function() {
        submitBtn.disabled = false;
        submitText.textContent = 'Registrar Activo';
        submitSpinner.classList.add('hidden');
    }, 3000);
});
</script>
