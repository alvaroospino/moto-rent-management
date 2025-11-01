<?php
// /app/views/gastos/edit.php
// $gasto viene inyectado del controlador
// $motos viene inyectado del controlador

$fechaHoy = date('Y-m-d');
$categorias = ['mantenimiento', 'operativo', 'general', 'impuestos'];
?>

<!-- Header Section -->
<div class="bg-gradient-to-r from-yellow-500 via-amber-500 to-orange-500 rounded-xl shadow-lg p-6 mb-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold flex items-center">
                <i class="fas fa-edit mr-3 text-yellow-100"></i>
                Editar Gasto Operacional
            </h1>
            <p class="text-yellow-100 mt-2">Modifica la información del gasto #<?= htmlspecialchars($gasto['id_gasto']) ?></p>
        </div>
        <a href="<?= BASE_URL ?>gastos/show/<?= $gasto['id_gasto'] ?>" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center backdrop-blur-sm">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver
        </a>
    </div>
</div>

<!-- Main Form Card -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <!-- Important Notice -->
    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border-l-4 border-yellow-400 p-6">
        <div class="flex items-start">
            <div class="bg-yellow-100 p-2 rounded-full mr-4">
                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">Información Importante</h3>
                <p class="text-yellow-700">
                    Modifica los datos del gasto. Asegúrate de que toda la información sea correcta antes de guardar los cambios.
                </p>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mx-6 mt-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <p class="text-red-700"><?= htmlspecialchars($_GET['error']) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>gastos/update/<?= $gasto['id_gasto'] ?>" method="POST" class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- Amount Section -->
            <div class="lg:col-span-2">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6">
                    <label for="monto" class="block text-lg font-semibold text-green-800 mb-3 flex items-center">
                        <i class="fas fa-dollar-sign mr-2 text-green-600"></i>
                        Monto del Gasto
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-green-600 font-semibold">$</span>
                        <input type="number" step="0.01" name="monto" id="monto" required min="0.01"
                               value="<?= htmlspecialchars($gasto['monto']) ?>"
                               class="pl-8 pr-4 py-3 w-full text-lg border-2 border-green-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 transition duration-200 bg-white"
                               placeholder="0.00">
                    </div>
                    <p class="text-sm text-green-600 mt-2">Ingresa el monto exacto del gasto (requerido)</p>
                </div>
            </div>

            <!-- Date Section -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                <label for="fecha_gasto" class="block text-lg font-semibold text-blue-800 mb-3 flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                    Fecha del Gasto
                </label>
                <input type="date" name="fecha_gasto" id="fecha_gasto" required
                       value="<?= htmlspecialchars($gasto['fecha_gasto']) ?>"
                       class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 bg-white">
                <p class="text-sm text-blue-600 mt-2">Selecciona la fecha cuando ocurrió el gasto</p>
            </div>

            <!-- Category Section -->
            <div class="bg-gradient-to-r from-purple-50 to-violet-50 border border-purple-200 rounded-xl p-6">
                <label for="categoria" class="block text-lg font-semibold text-purple-800 mb-3 flex items-center">
                    <i class="fas fa-tag mr-2 text-purple-600"></i>
                    Categoría
                </label>
                <select name="categoria" id="categoria" required
                        class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition duration-200 bg-white">
                    <option value="">Seleccione una categoría</option>
                    <option value="mantenimiento" <?= $gasto['categoria'] === 'mantenimiento' ? 'selected' : '' ?>>🔧 Mantenimiento</option>
                    <option value="operativo" <?= $gasto['categoria'] === 'operativo' ? 'selected' : '' ?>>⚙️ Operativo</option>
                    <option value="general" <?= $gasto['categoria'] === 'general' ? 'selected' : '' ?>>📋 General</option>
                    <option value="impuestos" <?= $gasto['categoria'] === 'impuestos' ? 'selected' : '' ?>>💰 Impuestos</option>
                </select>
                <p class="text-sm text-purple-600 mt-2">Clasifica el tipo de gasto</p>
            </div>

            <!-- Motorcycle Section -->
            <div class="bg-gradient-to-r from-gray-50 to-slate-50 border border-gray-200 rounded-xl p-6">
                <label for="id_moto" class="block text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-motorcycle mr-2 text-gray-600"></i>
                    Moto Asociada
                </label>
                <select name="id_moto" id="id_moto"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-gray-500 focus:ring-2 focus:ring-gray-200 transition duration-200 bg-white">
                    <option value="">📋 Gasto General (No asociado a una moto)</option>
                    <?php foreach ($motos as $moto): ?>
                        <option value="<?= $moto['id_moto'] ?>" <?= $gasto['id_moto'] == $moto['id_moto'] ? 'selected' : '' ?>>
                            🏍️ <?= htmlspecialchars($moto['placa']) ?> - <?= htmlspecialchars($moto['marca']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-sm text-gray-600 mt-2">Opcional: asocia el gasto a una moto específica</p>
            </div>

            <!-- Description Section -->
            <div class="lg:col-span-2 bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-xl p-6">
                <label for="descripcion" class="block text-lg font-semibold text-indigo-800 mb-3 flex items-center">
                    <i class="fas fa-align-left mr-2 text-indigo-600"></i>
                    Descripción Detallada
                </label>
                <textarea name="descripcion" id="descripcion" rows="4" required
                          class="w-full px-4 py-3 border-2 border-indigo-300 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition duration-200 bg-white resize-vertical"
                          placeholder="Describe detalladamente el gasto. Ej: Cambio de aceite y filtro para la moto placa XYZ123, incluyendo mano de obra y repuestos."><?= htmlspecialchars($gasto['descripcion']) ?></textarea>
                <p class="text-sm text-indigo-600 mt-2">Proporciona una descripción completa y detallada del gasto</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row justify-end gap-4">
            <a href="<?= BASE_URL ?>gastos/show/<?= $gasto['id_gasto'] ?>"
               class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-xl font-semibold transition-all duration-200 hover:shadow-lg">
                <i class="fas fa-times mr-2"></i>
                Cancelar
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-600 hover:to-amber-600 text-white rounded-xl font-semibold transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-save mr-2"></i>
                Actualizar Gasto
            </button>
        </div>
    </form>
</div>

<script>
// Add some interactive enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Auto-format amount input
    const amountInput = document.getElementById('monto');
    amountInput.addEventListener('input', function(e) {
        let value = e.target.value;
        if (value && !isNaN(value)) {
            // Ensure only 2 decimal places
            value = parseFloat(value).toFixed(2);
            e.target.value = value;
        }
    });

    // Category change animation
    const categorySelect = document.getElementById('categoria');
    categorySelect.addEventListener('change', function() {
        this.style.transform = 'scale(1.02)';
        setTimeout(() => {
            this.style.transform = 'scale(1)';
        }, 150);
    });

    // Highlight current values
    const currentCategory = '<?= $gasto['categoria'] ?>';
    if (currentCategory) {
        const option = categorySelect.querySelector(`option[value="${currentCategory}"]`);
        if (option) {
            option.style.fontWeight = 'bold';
        }
    }
});
</script>
