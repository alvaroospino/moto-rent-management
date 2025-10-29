<?php
$contentView = __DIR__ . '/detail_content.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-600 to-purple-600">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Contrato #<?= htmlspecialchars($contrato['id'] ?? 'N/A') ?></h1>
                        <p class="text-indigo-100 mt-1">Detalles del contrato de alquiler</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="<?= BASE_URL ?>contratos" class="bg-white text-indigo-600 hover:bg-gray-100 font-semibold py-2 px-4 rounded-lg transition duration-300">
                            <i class="fas fa-arrow-left mr-2"></i> Volver
                        </a>
                        <a href="<?= BASE_URL ?>pagos/contrato/<?= $contrato['id'] ?? '' ?>" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
                            <i class="fas fa-dollar-sign mr-2"></i> Registrar Pago
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información del Contrato -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Detalles Principales -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-file-contract mr-2 text-indigo-600"></i>
                            Información del Contrato
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Estado</label>
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                        <?php
                                        switch($contrato['estado'] ?? 'desconocido') {
                                            case 'activo':
                                                echo 'bg-green-100 text-green-800';
                                                break;
                                            case 'finalizado':
                                                echo 'bg-blue-100 text-blue-800';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= ucfirst(htmlspecialchars($contrato['estado'] ?? 'Desconocido')) ?>
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Fecha de Inicio</label>
                                    <p class="text-lg font-semibold text-gray-900">
                                        <?= date('d/m/Y', strtotime($contrato['fecha_inicio'] ?? 'now')) ?>
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Plazo Total</label>
                                    <p class="text-lg font-semibold text-gray-900"><?= $contrato['plazo_meses'] ?? 0 ?> meses</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Valor del Vehículo (Costo)</label>
                                    <p class="text-2xl font-bold text-green-600">
                                        $<?= number_format($contrato['valor_vehiculo'] ?? 0, 0, ',', '.') ?>
                                    </p>
                                    <p class="text-xs text-gray-500">Costo de adquisición para seguimiento</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Saldo Restante</label>
                                    <p class="text-2xl font-bold text-orange-600">
                                        $<?= number_format($contrato['saldo_restante'] ?? 0, 0, ',', '.') ?>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Cuota Mensual</label>
                                    <p class="text-lg font-semibold text-gray-900">
                                        $<?= number_format($contrato['cuota_mensual'] ?? 0, 0, ',', '.') ?>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600">Plazo</label>
                                    <p class="text-lg font-semibold text-gray-900">
                                        <?= $contrato['plazo_meses'] ?? 0 ?> meses
                                    </p>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($contrato['observaciones'])): ?>
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <label class="block text-sm font-medium text-gray-600 mb-2">Observaciones</label>
                                <p class="text-gray-700 bg-gray-50 p-3 rounded-md">
                                    <?= nl2br(htmlspecialchars($contrato['observaciones'])) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Información Financiera Detallada -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-calculator mr-2 text-indigo-600"></i>
                            Información Financiera
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                                <div class="text-3xl font-bold text-green-600 mb-2">
                                    $<?= number_format($contrato['cuota_mensual'] ?? 0, 0, ',', '.') ?>
                                </div>
                                <div class="text-sm font-medium text-green-800">Cuota Mensual</div>
                            </div>
                            <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="text-3xl font-bold text-blue-600 mb-2">
                                    $<?= number_format($contrato['abono_capital_mensual'] ?? 0, 0, ',', '.') ?>
                                </div>
                                <div class="text-sm font-medium text-blue-800">Abono Capital Mensual</div>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                                <div class="text-3xl font-bold text-purple-600 mb-2">
                                    $<?= number_format($contrato['saldo_restante'] ?? 0, 0, ',', '.') ?>
                                </div>
                                <div class="text-sm font-medium text-purple-800">Saldo Restante</div>
                            </div>
                            <div class="text-center p-4 bg-orange-50 rounded-lg border border-orange-200">
                                <div class="text-3xl font-bold text-orange-600 mb-2">
                                    $<?= number_format(($contrato['cuota_mensual'] * $contrato['plazo_meses'] - $contrato['saldo_restante']) ?? 0, 0, ',', '.') ?>
                                </div>
                                <div class="text-sm font-medium text-orange-800">Total Pagado</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="space-y-6">
                <!-- Información del Cliente -->
                <?php if ($cliente): ?>
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-user mr-2 text-indigo-600"></i>
                            Cliente
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-user text-2xl text-indigo-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                <?= htmlspecialchars($cliente['nombre_completo'] ?? 'N/A') ?>
                            </h3>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Identificación:</span>
                                <span class="font-medium"><?= htmlspecialchars($cliente['identificacion'] ?? 'N/A') ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Teléfono:</span>
                                <span class="font-medium"><?= htmlspecialchars($cliente['telefono'] ?? 'N/A') ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Dirección:</span>
                                <span class="font-medium"><?= htmlspecialchars($cliente['direccion'] ?? 'N/A') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Información del Vehículo -->
                <?php if ($moto): ?>
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-motorcycle mr-2 text-indigo-600"></i>
                            Vehículo
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-motorcycle text-2xl text-indigo-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                <?= htmlspecialchars(($moto['marca'] ?? '') . ' ' . ($moto['modelo'] ?? '')) ?>
                            </h3>
                            <p class="text-sm text-gray-600">Placa: <?= htmlspecialchars($moto['placa'] ?? 'N/A') ?></p>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Estado:</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    <?php
                                    switch($moto['estado']) {
                                        case 'activo':
                                            echo 'bg-green-100 text-green-800';
                                            break;
                                        case 'alquilada':
                                            echo 'bg-blue-100 text-blue-800';
                                            break;
                                        case 'mantenimiento':
                                            echo 'bg-yellow-100 text-yellow-800';
                                            break;
                                        default:
                                            echo 'bg-gray-100 text-gray-800';
                                    }
                                    ?>">
                                    <?= ucfirst(htmlspecialchars($moto['estado'] ?? 'desconocido')) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Resumen de Pagos del Mes -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-indigo-600"></i>
                            Pagos del Mes Actual
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600 mb-2">
                                $<?= number_format($pagosRealizadosMes ?? 0, 0, ',', '.') ?>
                            </div>
                            <div class="text-sm font-medium text-gray-600">Pagado este mes</div>
                            <div class="text-xs text-gray-500 mt-1">
                                de $<?= number_format($contrato['cuota_mensual'] ?? 0, 0, ',', '.') ?> cuota mensual
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full"
                                     style="width: <?= ($contrato['cuota_mensual'] ?? 0) > 0 ? min(100, (($pagosRealizadosMes ?? 0) / ($contrato['cuota_mensual'] ?? 1)) * 100) : 0 ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Periodos y Pagos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <!-- Tabla de Periodos -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-calendar-check mr-2 text-indigo-600"></i>
                        Periodos del Contrato
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periodo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fechas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pago Acumulado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($periodos)): ?>
                                <?php foreach ($periodos as $periodo): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #<?= htmlspecialchars($periodo['numero_periodo']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('d/m/Y', strtotime($periodo['fecha_inicio_periodo'])) ?> -<br>
                                            <?= date('d/m/Y', strtotime($periodo['fecha_fin_periodo'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                <?php
                                                switch($periodo['estado_periodo']) {
                                                    case 'abierto':
                                                        echo 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'cerrado':
                                                        echo 'bg-blue-100 text-blue-800';
                                                        break;
                                                    default:
                                                        echo 'bg-gray-100 text-gray-800';
                                                }
                                                ?>">
                                                <?= ucfirst(htmlspecialchars($periodo['estado_periodo'])) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            $<?= number_format($periodo['cuota_acumulada'] ?? 0, 0, ',', '.') ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <?php if ($periodo['estado_periodo'] === 'abierto' && ($periodo['cuota_acumulada'] ?? 0) >= $contrato['cuota_mensual']): ?>
                                                <button class="text-indigo-600 hover:text-indigo-900 font-medium" onclick="cerrarPeriodo(<?= $periodo['id_periodo'] ?>)">
                                                    Cerrar Periodo
                                                </button>
                                            <?php else: ?>
                                                <span class="text-gray-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No hay periodos registrados
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Historial de Pagos -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-history mr-2 text-indigo-600"></i>
                        Historial de Pagos
                    </h2>
                </div>
                <div class="overflow-x-auto max-h-96">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periodo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Concepto</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($pagos)): ?>
                                <?php foreach ($pagos as $pago): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= date('d/m/Y H:i', strtotime($pago['fecha_pago'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            #<?= htmlspecialchars($pago['numero_periodo']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                            $<?= number_format($pago['monto_pago'], 0, ',', '.') ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= ucfirst(htmlspecialchars($pago['tipo_pago'])) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <?= htmlspecialchars($pago['concepto'] ?? 'Pago diario') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No hay pagos registrados
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>
</div>
