<?php
// /app/views/reportes/index.php
// $reporteMotos, $f_inicio, $f_fin vienen del controlador
?>

<div class="container mx-auto px-4 py-8">
    <!-- Header con título y descripción -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Reportes de Rentabilidad</h1>
        <p class="text-gray-600">Análisis detallado de la utilidad real por motocicleta y rendimiento general del negocio</p>
    </div>

    <!-- Filtros de fecha mejorados -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Filtros de Período</h3>
                <p class="text-sm text-gray-600 mt-1">Selecciona el rango de fechas para analizar la rentabilidad</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Período actual:</span>
                <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">
                    <?= date('d/m/Y', strtotime($f_inicio)) ?> - <?= date('d/m/Y', strtotime($f_fin)) ?>
                </span>
            </div>
        </div>

        <form method="GET" class="space-y-6">
            <!-- Rango de fechas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label for="f_inicio" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-calendar-alt mr-2 text-indigo-500"></i>Fecha de Inicio
                    </label>
                    <input type="date" name="f_inicio" id="f_inicio" value="<?= $f_inicio ?>"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3 border-2 transition duration-200"
                           required>
                    <p class="text-xs text-gray-500">Selecciona la fecha inicial del período</p>
                </div>

                <div class="space-y-2">
                    <label for="f_fin" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-calendar-check mr-2 text-indigo-500"></i>Fecha de Fin
                    </label>
                    <input type="date" name="f_fin" id="f_fin" value="<?= $f_fin ?>"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3 border-2 transition duration-200"
                           required>
                    <p class="text-xs text-gray-500">Selecciona la fecha final del período</p>
                </div>

                <!-- Periodos predefinidos -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-clock mr-2 text-indigo-500"></i>Períodos Rápidos
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" onclick="setPeriod('current_month')" class="px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition duration-200">
                            Este Mes
                        </button>
                        <button type="button" onclick="setPeriod('last_month')" class="px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition duration-200">
                            Mes Anterior
                        </button>
                        <button type="button" onclick="setPeriod('current_year')" class="px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition duration-200">
                            Este Año
                        </button>
                        <button type="button" onclick="setPeriod('last_30_days')" class="px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition duration-200">
                            Últimos 30 Días
                        </button>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between pt-4 border-t border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="include_zeros" name="include_zeros" value="1"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <label for="include_zeros" class="text-sm text-gray-700">Incluir motos sin actividad</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="detailed_view" name="detailed_view" value="1" checked
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <label for="detailed_view" class="text-sm text-gray-700">Vista detallada</label>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <button type="button" onclick="resetFilters()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                        <i class="fas fa-undo mr-2"></i>Limpiar
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-search mr-2"></i>Generar Reporte
                    </button>
                    <button type="button" onclick="exportToExcel()"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-download mr-2"></i>Exportar Excel
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Resumen Ejecutivo -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <?php
        $totalInversion = array_sum(array_column($reporteMotos, 'inversion_inicial'));
        $totalIngresos = array_sum(array_column($reporteMotos, 'ingreso_alquiler'));
        $totalGastos = array_sum(array_column($reporteMotos, 'gasto_operativo'));
        $totalUtilidad = array_sum(array_column($reporteMotos, 'utilidad_neta'));
        $roiGlobal = $totalInversion > 0 ? ($totalUtilidad / $totalInversion) * 100 : 0;
        ?>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-dollar-sign text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Inversión Total</p>
                    <p class="text-2xl font-bold text-gray-900">$<?= number_format($totalInversion, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-arrow-up text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Ingresos del Período</p>
                    <p class="text-2xl font-bold text-green-700">$<?= number_format($totalIngresos, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-arrow-down text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Gastos Operativos</p>
                    <p class="text-2xl font-bold text-red-700">$<?= number_format($totalGastos, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-<?= $totalUtilidad >= 0 ? 'green' : 'red' ?>-100 text-<?= $totalUtilidad >= 0 ? 'green' : 'red' ?>-600">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Utilidad Neta</p>
                    <p class="text-2xl font-bold text-<?= $totalUtilidad >= 0 ? 'green' : 'red' ?>-700">$<?= number_format($totalUtilidad, 0, ',', '.') ?></p>
                    <p class="text-xs text-gray-500">ROI: <?= number_format($roiGlobal, 1) ?>%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Rendimiento -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Rendimiento por Motocicleta</h3>
        <div class="relative" style="height: 300px;">
            <canvas id="rendimientoChart"></canvas>
        </div>
    </div>

    <!-- Tabla Detallada -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Detalle por Motocicleta</h3>
            <p class="text-sm text-gray-600 mt-1">Análisis individual del rendimiento de cada vehículo</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motocicleta</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Inversión</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ingresos</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Gastos</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Utilidad</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ROI</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($reporteMotos as $moto): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <i class="fas fa-motorcycle text-indigo-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($moto['placa']) ?></div>
                                    <div class="text-sm text-gray-500">ID: <?= $moto['id_moto'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                            $<?= number_format($moto['inversion_inicial'], 0, ',', '.') ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-700 text-right font-medium">
                            $<?= number_format($moto['ingreso_alquiler'], 0, ',', '.') ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 text-right">
                            $<?= number_format($moto['gasto_operativo'], 0, ',', '.') ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right <?= $moto['utilidad_neta'] >= 0 ? 'text-green-700' : 'text-red-700' ?>">
                            $<?= number_format($moto['utilidad_neta'], 0, ',', '.') ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                <?php if ($moto['roi_parcial'] >= 20): ?>bg-green-100 text-green-800
                                <?php elseif ($moto['roi_parcial'] >= 10): ?>bg-yellow-100 text-yellow-800
                                <?php else: ?>bg-red-100 text-red-800<?php endif; ?>">
                                <?= number_format($moto['roi_parcial'], 1) ?>%
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <?php if ($moto['utilidad_neta'] > 0): ?>
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Rentable</span>
                            <?php elseif ($moto['ingreso_alquiler'] > 0): ?>
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">En proceso</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Sin actividad</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr class="font-semibold">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">TOTALES</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-right">$<?= number_format($totalInversion, 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-sm text-green-700 text-right">$<?= number_format($totalIngresos, 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-sm text-red-600 text-right">$<?= number_format($totalGastos, 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-sm font-bold text-right <?= $totalUtilidad >= 0 ? 'text-green-700' : 'text-red-700' ?>">
                            $<?= number_format($totalUtilidad, 0, ',', '.') ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-right">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                <?php if ($roiGlobal >= 20): ?>bg-green-100 text-green-800
                                <?php elseif ($roiGlobal >= 10): ?>bg-yellow-100 text-yellow-800
                                <?php else: ?>bg-red-100 text-red-800<?php endif; ?>">
                                <?= number_format($roiGlobal, 1) ?>%
                            </span>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos para el gráfico
    const chartData = <?= json_encode($reporteMotos) ?>;

    // Gráfico de rendimiento
    const ctx = document.getElementById('rendimientoChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.map(item => item.placa),
            datasets: [{
                label: 'Utilidad Neta',
                data: chartData.map(item => item.utilidad_neta),
                backgroundColor: chartData.map(item =>
                    item.utilidad_neta >= 0 ? 'rgba(34, 197, 94, 0.8)' : 'rgba(239, 68, 68, 0.8)'
                ),
                borderColor: chartData.map(item =>
                    item.utilidad_neta >= 0 ? 'rgb(34, 197, 94)' : 'rgb(239, 68, 68)'
                ),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Utilidad: $' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});

function setPeriod(period) {
    const today = new Date();
    let startDate, endDate;

    switch(period) {
        case 'current_month':
            startDate = new Date(today.getFullYear(), today.getMonth(), 1);
            endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            break;
        case 'last_month':
            startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            endDate = new Date(today.getFullYear(), today.getMonth(), 0);
            break;
        case 'current_year':
            startDate = new Date(today.getFullYear(), 0, 1);
            endDate = new Date(today.getFullYear(), 11, 31);
            break;
        case 'last_30_days':
            startDate = new Date(today);
            startDate.setDate(today.getDate() - 30);
            endDate = new Date(today);
            break;
    }

    document.getElementById('f_inicio').value = startDate.toISOString().split('T')[0];
    document.getElementById('f_fin').value = endDate.toISOString().split('T')[0];
}

function resetFilters() {
    document.getElementById('f_inicio').value = '';
    document.getElementById('f_fin').value = '';
    document.getElementById('include_zeros').checked = false;
    document.getElementById('detailed_view').checked = true;
}

function exportToExcel() {
    // Obtener datos del período
    const fechaInicio = document.getElementById('f_inicio').value;
    const fechaFin = document.getElementById('f_fin').value;
    const chartData = <?= json_encode($reporteMotos) ?>;

    // Calcular totales
    const totalInversion = chartData.reduce((sum, item) => sum + item.inversion_inicial, 0);
    const totalIngresos = chartData.reduce((sum, item) => sum + item.ingreso_alquiler, 0);
    const totalGastos = chartData.reduce((sum, item) => sum + item.gasto_operativo, 0);
    const totalUtilidad = chartData.reduce((sum, item) => sum + item.utilidad_neta, 0);
    const roiGlobal = totalInversion > 0 ? (totalUtilidad / totalInversion) * 100 : 0;

    // Crear workbook y worksheet
    const wb = XLSX.utils.book_new();

    // Hoja 1: Resumen Ejecutivo
    const resumenData = [
        ['REPORTE DE RENTABILIDAD POR MOTOCICLETA'],
        [`Período: ${fechaInicio} - ${fechaFin}`],
        [`Fecha de generación: ${new Date().toLocaleDateString('es-ES')}`],
        [''],
        ['RESUMEN EJECUTIVO'],
        ['Métrica', 'Valor'],
        ['Inversión Total', totalInversion],
        ['Ingresos del Período', totalIngresos],
        ['Gastos Operativos', totalGastos],
        ['Utilidad Neta', totalUtilidad],
        ['ROI Global', `${roiGlobal.toFixed(2)}%`],
        [''],
        ['DETALLE POR MOTOCICLETA'],
        ['ID Moto', 'Placa', 'Inversión Inicial', 'Ingresos del Período', 'Gastos Operativos', 'Utilidad Neta', 'ROI (%)', 'Estado']
    ];

    // Función para formatear números como moneda colombiana
    const formatCurrency = (amount) => {
        return '$' + amount.toLocaleString('es-CO', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    };

    // Agregar datos de cada motocicleta
    chartData.forEach(moto => {
        const estado = moto.utilidad_neta > 0 ? 'Rentable' :
                      moto.ingreso_alquiler > 0 ? 'En proceso' : 'Sin actividad';

        resumenData.push([
            moto.id_moto,
            moto.placa,
            formatCurrency(moto.inversion_inicial),
            formatCurrency(moto.ingreso_alquiler),
            formatCurrency(moto.gasto_operativo),
            formatCurrency(moto.utilidad_neta),
            `${moto.roi_parcial.toFixed(2)}%`,
            estado
        ]);
    });

    // Agregar totales
    resumenData.push([''], ['TOTALES'], ['', 'TOTALES', formatCurrency(totalInversion), formatCurrency(totalIngresos), formatCurrency(totalGastos), formatCurrency(totalUtilidad), `${roiGlobal.toFixed(2)}%`, '']);

    // Agregar notas
    resumenData.push([''], ['NOTAS:'], ['- Los valores están expresados en pesos colombianos (COP)'], ['- El ROI se calcula como (Utilidad Neta / Inversión Inicial) * 100'], ['- Los gastos operativos incluyen mantenimiento y otros costos asociados a cada motocicleta'], ['- Los ingresos corresponden únicamente a pagos por alquileres en el período seleccionado']);

    const wsResumen = XLSX.utils.aoa_to_sheet(resumenData);

    // Aplicar formato a las celdas numéricas
    const range = XLSX.utils.decode_range(wsResumen['!ref']);
    for (let row = 0; row <= range.e.r; row++) {
        for (let col = 0; col <= range.e.c; col++) {
            const cellAddress = XLSX.utils.encode_cell({ r: row, c: col });
            const cell = wsResumen[cellAddress];
            if (cell && typeof cell.v === 'number' && !isNaN(cell.v)) {
                // Formatear números como moneda colombiana
                cell.t = 'n';
                cell.z = '#,##0';
            }
        }
    }

    // Ajustar ancho de columnas
    wsResumen['!cols'] = [
        { wch: 10 }, // ID Moto
        { wch: 15 }, // Placa
        { wch: 18 }, // Inversión Inicial
        { wch: 18 }, // Ingresos del Período
        { wch: 18 }, // Gastos Operativos
        { wch: 15 }, // Utilidad Neta
        { wch: 10 }, // ROI (%)
        { wch: 15 }  // Estado
    ];

    XLSX.utils.book_append_sheet(wb, wsResumen, 'Resumen Ejecutivo');

    // Hoja 2: Datos para gráficos (opcional)
    const chartDataSheet = [
        ['Placa', 'Utilidad Neta', 'ROI (%)', 'Estado']
    ];

    chartData.forEach(moto => {
        const estado = moto.utilidad_neta > 0 ? 'Rentable' :
                      moto.ingreso_alquiler > 0 ? 'En proceso' : 'Sin actividad';

        chartDataSheet.push([
            moto.placa,
            moto.utilidad_neta,
            moto.roi_parcial,
            estado
        ]);
    });

    const wsChart = XLSX.utils.aoa_to_sheet(chartDataSheet);
    XLSX.utils.book_append_sheet(wb, wsChart, 'Datos Gráficos');

    // Generar y descargar el archivo Excel
    XLSX.writeFile(wb, `reporte_rentabilidad_${fechaInicio}_${fechaFin}.xlsx`);
}
</script>
