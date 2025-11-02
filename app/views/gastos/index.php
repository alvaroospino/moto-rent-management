<?php
// /app/views/gastos/index.php

// Calcular estadísticas
$totalGastos = 0;
$gastosEsteMes = 0;
$gastosEsteAnio = 0;
$mesActual = date('m');
$anioActual = date('Y');

if (!empty($gastos)) {
    foreach ($gastos as $gasto) {
        $totalGastos += $gasto['monto'];
        $fechaGasto = strtotime($gasto['fecha_gasto']);
        if (date('m', $fechaGasto) == $mesActual && date('Y', $fechaGasto) == $anioActual) {
            $gastosEsteMes += $gasto['monto'];
        }
        if (date('Y', $fechaGasto) == $anioActual) {
            $gastosEsteAnio += $gasto['monto'];
        }
    }
}
?>

<!-- Header Section -->
<div class="bg-gradient-to-r from-yellow-500 via-amber-500 to-orange-500 rounded-xl shadow-lg p-6 mb-6 text-white">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold mb-2 flex items-center">
                <i class="fas fa-wallet mr-3 text-yellow-100"></i>
                Gestión de Gastos Operacionales
            </h1>
            <p class="text-yellow-100 text-lg">Controla y administra todos los gastos de tu negocio</p>
        </div>
        <a href="<?= BASE_URL ?>gastos/create" class="bg-white text-yellow-600 hover:bg-yellow-50 px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            <i class="fas fa-plus-circle mr-2 text-lg"></i>
            Nuevo Gasto
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-gradient-to-br from-yellow-50 to-amber-50 border border-yellow-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-yellow-700 mb-1">Total Gastos</p>
                <p class="text-2xl font-bold text-yellow-800">$<?= number_format($totalGastos, 2) ?></p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-amber-700 mb-1">Este Mes</p>
                <p class="text-2xl font-bold text-amber-800">$<?= number_format($gastosEsteMes, 2) ?></p>
            </div>
            <div class="bg-amber-100 p-3 rounded-full">
                <i class="fas fa-calendar-alt text-amber-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Card -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="fas fa-list-ul mr-2 text-yellow-600"></i>
                Lista de Gastos
            </h2>
            <div class="flex items-center space-x-2">
                <input type="text" id="searchInput" placeholder="Buscar gastos..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                <select id="categoryFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    <option value="">Todas las categorías</option>
                    <option value="mantenimiento">Mantenimiento</option>
                    <option value="operativo">Operativo</option>
                    <option value="general">General</option>
                    <option value="impuestos">Impuestos</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="gastosTable">
            <thead class="bg-gradient-to-r from-yellow-50 to-amber-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-yellow-700 uppercase tracking-wider">
                        <i class="fas fa-hashtag mr-1"></i>ID
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-yellow-700 uppercase tracking-wider">
                        <i class="fas fa-calendar mr-1"></i>Fecha
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-yellow-700 uppercase tracking-wider">
                        <i class="fas fa-align-left mr-1"></i>Descripción
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-yellow-700 uppercase tracking-wider">
                        <i class="fas fa-tag mr-1"></i>Categoría
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-yellow-700 uppercase tracking-wider">
                        <i class="fas fa-motorcycle mr-1"></i>Moto
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-yellow-700 uppercase tracking-wider">
                        <i class="fas fa-dollar-sign mr-1"></i>Monto
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-yellow-700 uppercase tracking-wider">
                        <i class="fas fa-user mr-1"></i>Usuario
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-yellow-700 uppercase tracking-wider">
                        <i class="fas fa-cogs mr-1"></i>Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php if (empty($gastos)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="bg-yellow-100 p-4 rounded-full mb-4">
                                    <i class="fas fa-receipt text-yellow-600 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay gastos registrados</h3>
                                <p class="text-gray-500 mb-4">Comienza registrando tu primer gasto operativo</p>
                                <a href="<?= BASE_URL ?>gastos/create" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                                    <i class="fas fa-plus mr-2"></i> Registrar Primer Gasto
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($gastos as $gasto): ?>
                        <tr class="hover:bg-yellow-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                #<?= htmlspecialchars($gasto['id_gasto']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-day text-gray-400 mr-2"></i>
                                    <?= htmlspecialchars(date('d/m/Y', strtotime($gasto['fecha_gasto']))) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                                <div class="truncate" title="<?= htmlspecialchars($gasto['descripcion']) ?>">
                                    <?= htmlspecialchars($gasto['descripcion']) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $categoriaClass = 'bg-gray-100 text-gray-800';
                                switch($gasto['categoria']) {
                                    case 'mantenimiento': $categoriaClass = 'bg-blue-100 text-blue-800'; break;
                                    case 'operativo': $categoriaClass = 'bg-green-100 text-green-800'; break;
                                    case 'general': $categoriaClass = 'bg-purple-100 text-purple-800'; break;
                                    case 'impuestos': $categoriaClass = 'bg-red-100 text-red-800'; break;
                                }
                                ?>
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full <?= $categoriaClass ?>">
                                    <i class="fas fa-tag mr-1"></i>
                                    <?= htmlspecialchars(ucfirst($gasto['categoria'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <?php if ($gasto['id_moto']): ?>
                                    <div class="flex items-center">
                                        <i class="fas fa-motorcycle text-gray-400 mr-2"></i>
                                        <?= htmlspecialchars($gasto['moto_placa'] ?? 'N/A') ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-400 italic">General</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                <div class="flex items-center">
                                    <i class="fas fa-dollar-sign text-green-500 mr-1"></i>
                                    $<?= number_format($gasto['monto'], 2) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-user text-gray-400 mr-2"></i>
                                    <?= htmlspecialchars($gasto['usuario_nombre']) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="<?= BASE_URL ?>gastos/show/<?= $gasto['id_gasto'] ?>"
                                       class="text-blue-600 hover:text-blue-800 transition duration-200 p-1 rounded hover:bg-blue-50"
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>gastos/edit/<?= $gasto['id_gasto'] ?>"
                                       class="text-yellow-600 hover:text-yellow-800 transition duration-200 p-1 rounded hover:bg-yellow-50"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?= BASE_URL ?>gastos/destroy/<?= $gasto['id_gasto'] ?>" method="POST" class="inline"
                                          onsubmit="return confirm('¿Estás seguro de que deseas eliminar este gasto? Esta acción no se puede deshacer.')">
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition duration-200 p-1 rounded hover:bg-red-50"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards View -->
    <div class="md:hidden space-y-4 p-4">
        <?php if (empty($gastos)): ?>
            <div class="text-center py-12">
                <div class="bg-yellow-100 p-4 rounded-full mb-4 inline-block">
                    <i class="fas fa-receipt text-yellow-600 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay gastos registrados</h3>
                <p class="text-gray-500 mb-4">Comienza registrando tu primer gasto operativo</p>
                <a href="<?= BASE_URL ?>gastos/create" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i> Registrar Primer Gasto
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($gastos as $gasto): ?>
                <div class="gasto-card bg-white rounded-xl shadow-md border border-gray-200 p-4" data-categoria="<?= htmlspecialchars($gasto['categoria']) ?>">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center">
                            <span class="text-sm font-semibold text-gray-600">#<?= htmlspecialchars($gasto['id_gasto']) ?></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="<?= BASE_URL ?>gastos/show/<?= $gasto['id_gasto'] ?>"
                               class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition duration-200"
                               title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?= BASE_URL ?>gastos/edit/<?= $gasto['id_gasto'] ?>"
                               class="text-yellow-600 hover:text-yellow-800 p-2 rounded-lg hover:bg-yellow-50 transition duration-200"
                               title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?= BASE_URL ?>gastos/destroy/<?= $gasto['id_gasto'] ?>" method="POST" class="inline"
                                  onsubmit="return confirm('¿Estás seguro de que deseas eliminar este gasto? Esta acción no se puede deshacer.')">
                                <button type="submit" class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition duration-200"
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Fecha:</span>
                            <span class="text-sm font-medium text-gray-900 flex items-center">
                                <i class="fas fa-calendar-day text-gray-400 mr-1"></i>
                                <?= htmlspecialchars(date('d/m/Y', strtotime($gasto['fecha_gasto']))) ?>
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Monto:</span>
                            <span class="text-lg font-bold text-green-600 flex items-center">
                                <i class="fas fa-dollar-sign text-green-500 mr-1"></i>
                                $<?= number_format($gasto['monto'], 2) ?>
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Categoría:</span>
                            <?php
                            $categoriaClass = 'bg-gray-100 text-gray-800';
                            switch($gasto['categoria']) {
                                case 'mantenimiento': $categoriaClass = 'bg-blue-100 text-blue-800'; break;
                                case 'operativo': $categoriaClass = 'bg-green-100 text-green-800'; break;
                                case 'general': $categoriaClass = 'bg-purple-100 text-purple-800'; break;
                                case 'impuestos': $categoriaClass = 'bg-red-100 text-red-800'; break;
                            }
                            ?>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $categoriaClass ?>">
                                <i class="fas fa-tag mr-1"></i>
                                <?= htmlspecialchars(ucfirst($gasto['categoria'])) ?>
                            </span>
                        </div>

                        <?php if ($gasto['id_moto']): ?>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Moto:</span>
                                <span class="text-sm text-gray-900 flex items-center">
                                    <i class="fas fa-motorcycle text-gray-400 mr-1"></i>
                                    <?= htmlspecialchars($gasto['moto_placa'] ?? 'N/A') ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <div class="border-t pt-2 mt-2">
                            <p class="text-sm text-gray-700 line-clamp-2" title="<?= htmlspecialchars($gasto['descripcion']) ?>">
                                <?= htmlspecialchars($gasto['descripcion']) ?>
                            </p>
                        </div>

                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>Por: <?= htmlspecialchars($gasto['usuario_nombre']) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable with custom styling
    const table = $('#gastosTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        },
        "order": [[1, "desc"]], // Ordenar por fecha descendente
        "pageLength": 25,
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": [7] } // Disable sorting on actions column
        ],
        "dom": '<"flex flex-col md:flex-row justify-between items-center mb-4"<"flex items-center space-x-2"l><"flex items-center space-x-2"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"text-sm text-gray-700"i><"flex flex-col sm:flex-row justify-center sm:justify-end items-center space-y-2 sm:space-y-0 sm:space-x-2"p>>',
        "initComplete": function() {
            // Custom styling for DataTable elements
            $('.dataTables_length select').addClass('px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent');
            $('.dataTables_filter input').addClass('px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent');
            $('.dataTables_info').addClass('text-sm text-gray-600');
            $('.dataTables_paginate .paginate_button').addClass('px-3 py-2 mx-1 border border-gray-300 rounded-lg hover:bg-yellow-50 hover:border-yellow-300 transition duration-200');
            $('.dataTables_paginate .paginate_button.current').addClass('bg-yellow-500 text-white border-yellow-500 hover:bg-yellow-600');
            $('.dataTables_paginate .paginate_button.disabled').addClass('opacity-50 cursor-not-allowed');
        }
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Category filter
    $('#categoryFilter').on('change', function() {
        const category = this.value;
        if (category) {
            table.column(3).search(category, true, false).draw();
        } else {
            table.column(3).search('').draw();
        }
    });

    // Mobile search and filter functionality
    function filterMobileCards() {
        const searchTerm = $('#searchInput').val().toLowerCase();
        const categoryFilter = $('#categoryFilter').val();

        $('.gasto-card').each(function() {
            const card = $(this);
            const text = card.text().toLowerCase();
            const categoria = card.data('categoria');

            const matchesSearch = text.includes(searchTerm);
            const matchesCategory = !categoryFilter || categoria === categoryFilter;

            if (matchesSearch && matchesCategory) {
                card.show();
            } else {
                card.hide();
            }
        });
    }

    // Apply filters to mobile cards
    $('#searchInput, #categoryFilter').on('input change', filterMobileCards);

    // Add smooth animations
    $('.hover\\:bg-yellow-50').hover(
        function() { $(this).addClass('bg-yellow-50'); },
        function() { $(this).removeClass('bg-yellow-50'); }
    );
});
</script>

<style>
/* Custom DataTable styling */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    font-family: inherit;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    background: white;
    color: #374151;
    cursor: pointer;
    transition: all 0.2s;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #fef3c7;
    border-color: #fbbf24;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #f59e0b;
    color: white;
    border-color: #f59e0b;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: #d97706;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Line clamp utility for mobile */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Mobile card styling */
@media (max-width: 768px) {
    .gasto-card {
        transition: all 0.2s ease;
    }
}
</style>
