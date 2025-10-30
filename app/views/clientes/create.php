<?php
// /app/views/clientes/create.php
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="flex items-center justify-center w-12 h-12 bg-indigo-100 rounded-lg">
                    <i class="fas fa-user-plus text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Nuevo Cliente</h1>
                    <p class="text-sm text-gray-600">Registro de cliente al sistema</p>
                </div>
            </div>
            <a href="<?= BASE_URL ?>clientes"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-gray-100 rounded-md transition-colors border border-gray-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Regresar
            </a>
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
        <form action="<?= BASE_URL ?>clientes/store" method="POST" class="divide-y divide-gray-200">
            <!-- Información Personal -->
            <div class="p-6">
                <div class="flex items-center mb-6">
                     <div class="flex items-center justify-center w-8 h-8 bg-indigo-100 rounded-lg mr-3">
                        <i class="fas fa-user text-indigo-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Información Personal</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre Completo -->
                    <div class="md:col-span-2">
                        <label for="nombre_completo" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-1 text-indigo-500"></i>
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre_completo" id="nombre_completo" required
                               class="block w-full px-4 py-3 text-base capitalize border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                               placeholder="Juan Pérez González">
                        <p class="mt-1 text-xs text-gray-500">Nombre completo del cliente</p>
                    </div>

                    <!-- Identificación -->
                    <div>
                        <label for="identificacion" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-id-card mr-1 text-green-500"></i>
                            Identificación (Cédula/ID) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="identificacion" id="identificacion" required
                               class="block w-full px-4 py-3 text-base font-mono border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 transition-colors"
                               placeholder="1234567890" maxlength="20">
                        <p class="mt-1 text-xs text-gray-500">Número de identificación único</p>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-1 text-blue-500"></i>
                            Teléfono
                        </label>
                        <input type="tel" name="telefono" id="telefono"
                               class="block w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="+593 987654321" maxlength="15">
                        <p class="mt-1 text-xs text-gray-500">Número de contacto (opcional)</p>
                    </div>
                </div>
            </div>

            <!-- Información de Ubicación -->
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg mr-3">
                        <i class="fas fa-map-marker-alt text-green-600"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">Información de Ubicación</h2>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Dirección -->
                    <div>
                        <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-home mr-1 text-purple-500"></i>
                            Dirección
                        </label>
                        <textarea name="direccion" id="direccion" rows="3"
                                  class="block w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition-colors resize-none"
                                  placeholder="Dirección completa del cliente"></textarea>
                        <p class="mt-1 text-xs text-gray-500">Dirección de residencia (opcional)</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                <a href="<?= BASE_URL ?>clientes"
                   class="inline-flex items-center justify-center px-6 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors submit-btn">
                    <i class="fas fa-save mr-2"></i>
                    <span class="submit-text">Registrar Cliente</span>
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
// Auto-capitalizar nombre completo
document.getElementById('nombre_completo').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/\b\w/g, function(l) { return l.toUpperCase(); });
});

// Formatear teléfono
document.getElementById('telefono').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 0) {
        if (value.length <= 3) {
            value = value;
        } else if (value.length <= 6) {
            value = value.slice(0, 3) + ' ' + value.slice(3);
        } else {
            value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6, 10);
        }
    }
    e.target.value = value;
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
        submitText.textContent = 'Registrar Cliente';
        submitSpinner.classList.add('hidden');
    }, 3000);
});
</script>
