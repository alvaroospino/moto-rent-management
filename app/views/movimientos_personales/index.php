<?php
// /app/views/movimientos_personales/index.php
?>

<!-- Header Section -->
<div class="bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 rounded-xl shadow-lg p-6 mb-6 text-white">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold mb-2 flex items-center">
                <i class="fas fa-wallet mr-3 text-green-100"></i>
                Control de Gastos Personales
            </h1>
            <p class="text-green-100 text-lg">Registra tus ingresos y gastos para mantener el control de tu dinero</p>
        </div>
        <a href="<?= BASE_URL ?>movimientos-personales/create" class="bg-white text-green-600 hover:bg-green-50 px-6 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            <i class="fas fa-plus-circle mr-2 text-lg"></i>
            Nuevo Movimiento
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-green-700 mb-1">Total Recibido</p>
                <p class="text-2xl font-bold text-green-800">$<?= number_format($totales['total_ingresos'], 2) ?></p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-arrow-up text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-red-50 to-rose-50 border border-red-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-red-700 mb-1">Total Gastado</p>
                <p class="text-2xl font-bold text-red-800">$<?= number_format($totales['total_gastos'], 2) ?></p>
            </div>
            <div class="bg-red-100 p-3 rounded-full">
                <i class="fas fa-arrow-down text-red-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-blue-700 mb-1">Saldo Disponible</p>
                <p class="text-2xl font-bold <?= $totales['saldo'] >= 0 ? 'text-blue-800' : 'text-red-800' ?>">
                    $<?= number_format($totales['saldo'], 2) ?>
                </p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-balance-scale text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Card -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="fas fa-list-ul mr-2 text-green-600"></i>
                Historial de Movimientos
            </h2>
            <div class="flex items-center space-x-2">
                <input type="text" id="searchInput" placeholder="Buscar movimientos..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <select id="typeFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Todos los tipos</option>
                    <option value="ingreso">Ingresos</option>
                    <option value="gasto">Gastos</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="movimientosTable">
            <thead class="bg-gradient-to-r from-green-50 to-emerald-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-green-700 uppercase tracking-wider">
                        <i class="fas fa-hashtag mr-1"></i>ID
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-green-700 uppercase tracking-wider">
                        <i class="fas fa-calendar mr-1"></i>Fecha
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-green-700 uppercase tracking-wider">
                        <i class="fas fa-exchange-alt mr-1"></i>Tipo
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-green-700 uppercase tracking-wider">
                        <i class="fas fa-align-left mr-1"></i>Descripción
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-green-700 uppercase tracking-wider">
                        <i class="fas fa-dollar-sign mr-1"></i>Monto
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-green-700 uppercase tracking-wider">
                        <i class="fas fa-cogs mr-1"></i>Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                <?php if (empty($movimientos)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="bg-green-100 p-4 rounded-full mb-4">
                                    <i class="fas fa-wallet text-green-600 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay movimientos registrados</h3>
                                <p class="text-gray-500 mb-4">Comienza registrando tu primer ingreso o gasto</p>
                                <a href="<?= BASE_URL ?>movimientos-personales/create" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center">
                                    <i class="fas fa-plus mr-2"></i> Registrar Primer Movimiento
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($movimientos as $movimiento): ?>
                        <tr class="hover:bg-green-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                #<?= htmlspecialchars($movimiento['id_movimiento']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-day text-gray-400 mr-2"></i>
                                    <?= htmlspecialchars(date('d/m/Y', strtotime($movimiento['fecha_movimiento']))) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($movimiento['tipo'] === 'ingreso'): ?>
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-arrow-up mr-1"></i>
                                        Ingreso
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-arrow-down mr-1"></i>
                                        Gasto
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs">
                                <div class="truncate" title="<?= htmlspecialchars($movimiento['descripcion']) ?>">
                                    <?= htmlspecialchars($movimiento['descripcion']) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold <?= $movimiento['tipo'] === 'ingreso' ? 'text-green-600' : 'text-red-600' ?>">
                                <div class="flex items-center">
                                    <i class="fas fa-dollar-sign <?= $movimiento['tipo'] === 'ingreso' ? 'text-green-500' : 'text-red-500' ?> mr-1"></i>
                                    $<?= number_format($movimiento['monto'], 2) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="<?= BASE_URL ?>movimientos-personales/show/<?= $movimiento['id_movimiento'] ?>"
                                       class="text-blue-600 hover:text-blue-800 transition duration-200 p-1 rounded hover:bg-blue-50"
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>movimientos-personales/edit/<?= $movimiento['id_movimiento'] ?>"
                                       class="text-yellow-600 hover:text-yellow-800 transition duration-200 p-1 rounded hover:bg-yellow-50"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?= BASE_URL ?>movimientos-personales/destroy/<?= $movimiento['id_movimiento'] ?>" method="POST" class="inline"
                                          onsubmit="return confirm('¿Estás seguro de que deseas eliminar este movimiento? Esta acción no se puede deshacer.')">
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
        <?php if (empty($movimientos)): ?>
            <div class="text-center py-12">
                <div class="bg-green-100 p-4 rounded-full mb-4 inline-block">
                    <i class="fas fa-wallet text-green-600 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay movimientos registrados</h3>
                <p class="text-gray-500 mb-4">Comienza registrando tu primer ingreso o gasto</p>
                <a href="<?= BASE_URL ?>movimientos-personales/create" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition duration-200 inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i> Registrar Primer Movimiento
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($movimientos as $movimiento): ?>
                <div class="movimiento-card bg-white rounded-xl shadow-md border border-gray-200 p-4" data-tipo="<?= htmlspecialchars($movimiento['tipo']) ?>">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center">
                            <span class="text-sm font-semibold text-gray-600">#<?= htmlspecialchars($movimiento['id_movimiento']) ?></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="<?= BASE_URL ?>movimientos-personales/show/<?= $movimiento['id_movimiento'] ?>"
                               class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition duration-200"
                               title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?= BASE_URL ?>movimientos-personales/edit/<?= $movimiento['id_movimiento'] ?>"
                               class="text-yellow-600 hover:text-yellow-800 p-2 rounded-lg hover:bg-yellow-50 transition duration-200"
                               title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?= BASE_URL ?>movimientos-personales/destroy/<?= $movimiento['id_movimiento'] ?>" method="POST" class="inline"
                                  onsubmit="return confirm('¿Estás seguro de que deseas eliminar este movimiento? Esta acción no se puede deshacer.')">
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
                                <?= htmlspecialchars(date('d/m/Y', strtotime($movimiento['fecha_movimiento']))) ?>
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Tipo:</span>
                            <?php if ($movimiento['tipo'] === 'ingreso'): ?>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    Ingreso
                                </span>
                            <?php else: ?>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-arrow-down mr-1"></i>
                                    Gasto
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Monto:</span>
                            <span class="text-lg font-bold <?= $movimiento['tipo'] === 'ingreso' ? 'text-green-600' : 'text-red-600' ?> flex items-center">
                                <i class="fas fa-dollar-sign <?= $movimiento['tipo'] === 'ingreso' ? 'text-green-500' : 'text-red-500' ?> mr-1"></i>
                                $<?= number_format($movimiento['monto'], 2) ?>
                            </span>
                        </div>

                        <div class="border-t pt-2 mt-2">
                            <p class="text-sm text-gray-700 line-clamp-2" title="<?= htmlspecialchars($movimiento['descripcion']) ?>">
                                <?= htmlspecialchars($movimiento['descripcion']) ?>
                            </p>
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
    const table = $('#movimientosTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        },
        "order": [[1, "desc"]], // Ordenar por fecha descendente
        "pageLength": 25,
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": [5] } // Disable sorting on actions column
        ],
        "dom": '<"flex flex-col md:flex-row justify-between items-center mb-4"<"flex items-center space-x-2"l><"flex items-center space-x-2"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"text-sm text-gray-700"i><"flex flex-col sm:flex-row justify-center sm:justify-end items-center space-y-2 sm:space-y-0 sm:space-x-2"p>>',
        "initComplete": function() {
            // Custom styling for DataTable elements
            $('.dataTables_length select').addClass('px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent');
            $('.dataTables_filter input').addClass('px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent');
            $('.dataTables_info').addClass('text-sm text-gray-600');
            $('.dataTables_paginate .paginate_button').addClass('px-3 py-2 mx-1 border border-gray-300 rounded-lg hover:bg-green-50 hover:border-green-300 transition duration-200');
            $('.dataTables_paginate .paginate_button.current').addClass('bg-green-500 text-white border-green-500 hover:bg-green-600');
            $('.dataTables_paginate .paginate_button.disabled').addClass('opacity-50 cursor-not-allowed');
        }
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Type filter
    $('#typeFilter').on('change', function() {
        const type = this.value;
        if (type) {
            table.column(2).search(type, true, false).draw();
        } else {
            table.column(2).search('').draw();
        }
    });

    // Mobile search and filter functionality
    function filterMobileCards() {
        const searchTerm = $('#searchInput').val().toLowerCase();
        const typeFilter = $('#typeFilter').val();

        $('.movimiento-card').each(function() {
            const card = $(this);
            const text = card.text().toLowerCase();
            const tipo = card.data('tipo');

            const matchesSearch = text.includes(searchTerm);
            const matchesType = !typeFilter || tipo === typeFilter;

            if (matchesSearch && matchesType) {
                card.show();
            } else {
                card.hide();
            }
        });
    }

    // Apply filters to mobile cards
    $('#searchInput, #typeFilter').on('input change', filterMobileCards);

    // Add smooth animations
    $('.hover\\:bg-green-50').hover(
        function() { $(this).addClass('bg-green-50'); },
        function() { $(this).removeClass('bg-green-50'); }
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
    background: #f0fdf4;
    border-color: #10b981;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #10b981;
    color: white;
    border-color: #10b981;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: #059669;
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
    .movimiento-card {
        transition: all 0.2s ease;
    }
}
</style>
