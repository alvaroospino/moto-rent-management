<?php
// /app/views/dashboard/index.php
// $stats, $contratos_activos vienen inyectados del controlador
?>

<!-- Mensajes de éxito/error -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        <?= $_SESSION['success'] ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <?= $_SESSION['error'] ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- KPIs Principales -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">

    <div class="bg-white shadow-xl rounded-lg p-4 md:p-6 flex items-center justify-between border-l-4 border-indigo-500">
        <div>
            <p class="text-sm font-medium text-gray-500">Motos Activas</p>
            <p class="text-2xl md:text-3xl font-bold text-gray-900 mt-1"><?= $stats['motos_activas'] ?></p>
            <p class="text-xs text-gray-400 mt-1">Disponibles: <?= $stats['motos_disponibles'] ?></p>
        </div>
        <i class="fas fa-motorcycle text-3xl md:text-4xl text-indigo-400"></i>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-4 md:p-6 flex items-center justify-between border-l-4 border-green-500">
        <div>
            <p class="text-sm font-medium text-gray-500">Ingreso del Mes</p>
            <p class="text-2xl md:text-3xl font-bold text-gray-900 mt-1">$<?= number_format($stats['ingreso_mes'], 2) ?></p>
            <p class="text-xs text-gray-400 mt-1">Hoy: $<?= number_format($stats['ingreso_dia'], 2) ?></p>
        </div>
        <i class="fas fa-hand-holding-dollar text-3xl md:text-4xl text-green-400"></i>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-4 md:p-6 flex items-center justify-between border-l-4 border-blue-500">
        <div>
            <p class="text-sm font-medium text-gray-500">Total Clientes</p>
            <p class="text-2xl md:text-3xl font-bold text-gray-900 mt-1"><?= $stats['total_clientes'] ?></p>
        </div>
        <i class="fas fa-users text-3xl md:text-4xl text-blue-400"></i>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-4 md:p-6 flex items-center justify-between border-l-4 border-orange-500">
        <div>
            <p class="text-sm font-medium text-gray-500">Gastos del Mes</p>
            <p class="text-2xl md:text-3xl font-bold text-gray-900 mt-1">$<?= number_format($stats['gastos_mes'], 2) ?></p>
        </div>
        <i class="fas fa-wallet text-3xl md:text-4xl text-orange-400"></i>
    </div>
</div>

<!-- Acciones Rápidas y Alertas -->
<div class="grid grid-cols-1 gap-4 md:gap-6 mb-6 md:mb-8">

    <!-- Pago Rápido -->
    <div class="bg-white shadow-xl rounded-lg p-4 md:p-6">
        <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-4 flex items-center justify-between cursor-pointer" onclick="togglePagoRapido()">
            <span><i class="fas fa-bolt text-yellow-500 mr-2"></i>Pago Rápido</span>
            <i class="fas fa-chevron-down text-gray-500 transition-transform duration-200" id="pagoRapidoIcon"></i>
        </h3>

        <form id="pagoRapidoForm" action="/dashboard/pago-rapido" method="POST" class="space-y-4 hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="id_contrato" class="block text-sm font-medium text-gray-700 mb-1">Seleccionar Contrato</label>
                    <select name="id_contrato" id="id_contrato" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base">
                        <option value="">-- Seleccionar Contratos --</option>
                        <?php foreach ($contratos_activos as $contrato): ?>
                            <option value="<?= $contrato['id_contrato'] ?>">
                                #<?= $contrato['id_contrato'] ?> - <?= $contrato['nombre_completo'] ?? 'Cliente' ?> (<?= $contrato['placa'] ?? 'Moto' ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="monto_pago" class="block text-sm font-medium text-gray-700 mb-1">Monto del Pago</label>
                    <input type="number" name="monto_pago" id="monto_pago" step="0.01" min="0.01" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base"
                           placeholder="0.00">
                </div>

                <div>
                    <label for="fecha_pago" class="block text-sm font-medium text-gray-700 mb-1">Fecha del Pago</label>
                    <input type="date" name="fecha_pago" id="fecha_pago" value="<?= date('Y-m-d') ?>" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base">
                </div>

                <div>
                    <label for="concepto" class="block text-sm font-medium text-gray-700 mb-1">Concepto (Opcional)</label>
                    <input type="text" name="concepto" id="concepto"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base"
                           placeholder="Pago mensual, etc.">
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 text-sm md:text-base">
                    <i class="fas fa-credit-card mr-2"></i>
                    Registrar Pago
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Gráficos y Estadísticas -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">

    <!-- Gráfico de Rentabilidad -->
    <div class="bg-white shadow-xl rounded-lg p-4 md:p-6 xl:col-span-2">
        <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-chart-line text-green-500 mr-2"></i>
            Rentabilidad Mensual
        </h3>
        <div class="h-64 md:h-80">
            <canvas id="rentabilidadChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Últimos Pagos -->
    <div class="bg-white shadow-xl rounded-lg p-4 md:p-6">
        <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-credit-card text-blue-500 mr-2"></i>
            Últimos Pagos
        </h3>
        <div class="space-y-3 max-h-64 md:max-h-80 overflow-y-auto">
            <?php if (!empty($chartData['pagos_recientes'])): ?>
                <?php foreach ($chartData['pagos_recientes'] as $pago): ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900 text-sm md:text-base"><?= htmlspecialchars($pago['nombre_completo']) ?></p>
                            <p class="text-sm text-gray-500">Placa: <?= htmlspecialchars($pago['placa']) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600 text-sm md:text-base">$<?= number_format($pago['monto_pago'], 2) ?></p>
                            <p class="text-xs text-gray-400"><?= $pago['fecha_formateada'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 text-center py-4 text-sm md:text-base">No hay pagos recientes</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Estadísticas Detalladas -->
<div class="bg-white shadow-xl rounded-lg p-4 md:p-6">
    <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-chart-bar text-purple-500 mr-2"></i>
        Estadísticas Detalladas
    </h3>
    <div class="text-center">
        <h4 class="text-base md:text-lg font-medium text-gray-700 mb-2">Ingresos vs Gastos</h4>
        <div class="inline-block w-64 h-64 md:w-48 md:h-48 lg:w-56 lg:h-56">
            <canvas id="ingresosGastosChart"></canvas>
        </div>
    </div>
</div>

<script>
// Función para toggle del formulario de pago rápido
function togglePagoRapido() {
    const form = document.getElementById('pagoRapidoForm');
    const icon = document.getElementById('pagoRapidoIcon');
    if (form.classList.contains('hidden')) {
        form.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        form.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

// JavaScript para mejorar la UX del formulario de pago rápido
document.getElementById('pagoRapidoForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';
    submitBtn.disabled = true;

    // Re-enable after 3 seconds in case of error
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 3000);
});

// Gráficos con Chart.js
document.addEventListener('DOMContentLoaded', function() {
    // Datos de rentabilidad
    const rentabilidadData = <?php echo json_encode($chartData['rentabilidad_mensual']); ?>;
    const labels = rentabilidadData.map(item => {
        const date = new Date(item.mes + '-01');
        return date.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
    });
    const ingresos = rentabilidadData.map(item => parseFloat(item.ingresos));
    const gastos = rentabilidadData.map(item => parseFloat(item.gastos));
    const utilidad = rentabilidadData.map(item => parseFloat(item.utilidad));

    // Gráfico de rentabilidad
    const ctxRentabilidad = document.getElementById('rentabilidadChart').getContext('2d');
    new Chart(ctxRentabilidad, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Ingresos',
                data: ingresos,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4
            }, {
                label: 'Gastos',
                data: gastos,
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4
            }, {
                label: 'Utilidad',
                data: utilidad,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Rentabilidad de los últimos 6 meses'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Gráfico de ingresos vs gastos (dona)
    const ctxIngresosGastos = document.getElementById('ingresosGastosChart').getContext('2d');
    new Chart(ctxIngresosGastos, {
        type: 'doughnut',
        data: {
            labels: ['Ingresos', 'Gastos'],
            datasets: [{
                data: [<?php echo $stats['ingreso_mes']; ?>, <?php echo $stats['gastos_mes']; ?>],
                backgroundColor: [
                    'rgb(34, 197, 94)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });


});
</script>
