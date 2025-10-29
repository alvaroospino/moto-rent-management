<?php 
// /app/views/reportes/index.php
// $reporteMotos, $f_inicio, $f_fin vienen del controlador
?>
<div class="bg-white p-6 rounded-lg shadow-xl">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Reporte de Utilidad Real por Moto</h2>
    
    <form method="GET" class="flex items-center space-x-4 mb-6 p-4 bg-gray-50 rounded-lg border">
        <div>
            <label for="f_inicio" class="block text-sm font-medium text-gray-700">Desde</label>
            <input type="date" name="f_inicio" id="f_inicio" value="<?= $f_inicio ?>" class="mt-1 block rounded-md border-gray-300 p-2">
        </div>
        <div>
            <label for="f_fin" class="block text-sm font-medium text-gray-700">Hasta</label>
            <input type="date" name="f_fin" id="f_fin" value="<?= $f_fin ?>" class="mt-1 block rounded-md border-gray-300 p-2">
        </div>
        <button type="submit" class="self-end bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-150">
            Filtrar Reporte
        </button>
    </form>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Moto (Placa)</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Inversión Inicial</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ingreso por Contrato (Período)</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Gastos Operativos (Período)</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Utilidad Neta (Período)</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ROI Parcial (%)</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $granTotalUtilidad = 0; ?>
                <?php foreach ($reporteMotos as $moto): ?>
                <?php $granTotalUtilidad += $moto['utilidad_neta']; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600"><?= htmlspecialchars($moto['placa']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">$<?= number_format($moto['inversion_inicial'], 2) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">$<?= number_format($moto['ingreso_alquiler'], 2) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">-$<?= number_format($moto['gasto_operativo'], 2) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right <?= $moto['utilidad_neta'] >= 0 ? 'text-green-700' : 'text-red-700' ?>">
                        $<?= number_format($moto['utilidad_neta'], 2) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                        <?= number_format($moto['roi_parcial'], 2) ?>%
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr class="bg-gray-100 font-extrabold border-t-2 border-indigo-300">
                    <td class="px-6 py-4">Total Utilidad Neta Global</td>
                    <td colspan="3"></td>
                    <td class="px-6 py-4 text-right <?= $granTotalUtilidad >= 0 ? 'text-green-700' : 'text-red-700' ?>">
                        $<?= number_format($granTotalUtilidad, 2) ?>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>