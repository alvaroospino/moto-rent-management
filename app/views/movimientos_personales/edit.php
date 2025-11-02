<?php
// /app/views/movimientos_personales/edit.php
?>

<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-yellow-500 via-amber-500 to-orange-500 rounded-xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center">
            <i class="fas fa-edit text-3xl mr-4 text-yellow-100"></i>
            <div>
                <h1 class="text-3xl font-bold">Editar Movimiento Personal</h1>
                <p class="text-yellow-100 mt-1">Modifica los detalles del movimiento #<?= htmlspecialchars($movimiento['id_movimiento']) ?></p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="<?= BASE_URL ?>movimientos-personales/update/<?= $movimiento['id_movimiento'] ?>" method="POST" class="space-y-6">
            <!-- Tipo de Movimiento -->
            <div>
                <label for="tipo" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-exchange-alt mr-2 text-yellow-600"></i>
                    Tipo de Movimiento *
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative">
                        <input type="radio" name="tipo" value="ingreso" class="sr-only peer" <?= $movimiento['tipo'] === 'ingreso' ? 'checked' : '' ?> required>
                        <div class="p-4 border-2 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 transition-all duration-200 hover:border-green-300">
                            <div class="flex items-center">
                                <i class="fas fa-arrow-up text-green-600 mr-3 text-xl"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Ingreso</div>
                                    <div class="text-sm text-gray-600">Dinero que recibo</div>
                                </div>
                            </div>
                        </div>
                    </label>
                    <label class="relative">
                        <input type="radio" name="tipo" value="gasto" class="sr-only peer" <?= $movimiento['tipo'] === 'gasto' ? 'checked' : '' ?> required>
                        <div class="p-4 border-2 rounded-lg cursor-pointer peer-checked:border-red-500 peer-checked:bg-red-50 transition-all duration-200 hover:border-red-300">
                            <div class="flex items-center">
                                <i class="fas fa-arrow-down text-red-600 mr-3 text-xl"></i>
                                <div>
                                    <div class="font-semibold text-gray-900">Gasto</div>
                                    <div class="text-sm text-gray-600">Dinero que utilizo</div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Fecha -->
            <div>
                <label for="fecha_movimiento" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar mr-2 text-yellow-600"></i>
                    Fecha *
                </label>
                <input type="date" id="fecha_movimiento" name="fecha_movimiento"
                       value="<?= htmlspecialchars($movimiento['fecha_movimiento']) ?>"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition duration-200"
                       required>
            </div>

            <!-- Monto -->
            <div>
                <label for="monto" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-dollar-sign mr-2 text-yellow-600"></i>
                    Monto *
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-500">$</span>
                    <input type="number" id="monto" name="monto" step="0.01" min="0.01"
                           value="<?= htmlspecialchars(number_format($movimiento['monto'], 2, '.', '')) ?>"
                           class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition duration-200"
                           required>
                </div>
            </div>

            <!-- Descripción -->
            <div>
                <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-align-left mr-2 text-yellow-600"></i>
                    Descripción *
                </label>
                <textarea id="descripcion" name="descripcion" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition duration-200 resize-vertical"
                          required><?= htmlspecialchars($movimiento['descripcion']) ?></textarea>
                <p class="text-sm text-gray-500 mt-1">Proporciona detalles claros para identificar fácilmente el movimiento</p>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                <a href="<?= BASE_URL ?>movimientos-personales/show/<?= $movimiento['id_movimiento'] ?>"
                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold transition duration-200 text-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Cancelar
                </a>
                <button type="submit"
                        class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-200 flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>
                    Actualizar Movimiento
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-format monto input
    $('#monto').on('input', function() {
        let value = $(this).val();
        if (value && !isNaN(value)) {
            // Ensure only 2 decimal places
            value = parseFloat(value).toFixed(2);
            $(this).val(value);
        }
    });

    // Radio button styling
    $('input[type="radio"]').on('change', function() {
        // Remove previous selections
        $('input[type="radio"]').each(function() {
            const label = $(this).closest('label');
            if ($(this).is(':checked')) {
                if ($(this).val() === 'ingreso') {
                    label.find('div').addClass('border-green-500 bg-green-50').removeClass('border-red-500 bg-red-50');
                } else {
                    label.find('div').addClass('border-red-500 bg-red-50').removeClass('border-green-500 bg-green-50');
                }
            } else {
                label.find('div').removeClass('border-green-500 bg-green-50 border-red-500 bg-red-50').addClass('border-gray-200');
            }
        });
    });

    // Initialize radio button styling on page load
    $('input[type="radio"]:checked').trigger('change');

    // Form validation
    $('form').on('submit', function(e) {
        const tipo = $('input[name="tipo"]:checked').val();
        const monto = parseFloat($('#monto').val());
        const descripcion = $('#descripcion').val().trim();

        if (!tipo) {
            e.preventDefault();
            alert('Por favor selecciona el tipo de movimiento.');
            return false;
        }

        if (!monto || monto <= 0) {
            e.preventDefault();
            alert('Por favor ingresa un monto válido mayor a 0.');
            $('#monto').focus();
            return false;
        }

        if (!descripcion) {
            e.preventDefault();
            alert('Por favor ingresa una descripción.');
            $('#descripcion').focus();
            return false;
        }
    });
});
</script>

<style>
/* Custom radio button styling */
input[type="radio"]:checked + div {
    border-color: #10b981 !important;
    background-color: #f0fdf4 !important;
}

input[type="radio"]:checked + div .fa-arrow-up {
    color: #059669 !important;
}

input[type="radio"]:checked + div .fa-arrow-down {
    color: #dc2626 !important;
}
</style>
