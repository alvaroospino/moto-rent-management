<?php
// /app/views/clientes/index.php
// $clientes viene inyectado del controlador
?>

<div class="bg-white shadow-xl rounded-lg p-6">
    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($_GET['success']) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php endif; ?>

    <!-- KPIs Principales -->
    <div class="grid grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
        <div class="bg-white shadow-xl rounded-lg p-4 md:p-8 flex items-center justify-between border-l-4 border-indigo-500">
            <div>
                <p class="text-sm md:text-lg font-medium text-gray-500">Total Clientes</p>
                <p class="text-3xl md:text-5xl font-bold text-gray-900 mt-2"><?= count($clientes) ?></p>
            </div>
            <i class="fas fa-users text-4xl md:text-6xl text-indigo-400"></i>
        </div>

        <div class="bg-white shadow-xl rounded-lg p-4 md:p-8 flex items-center justify-between border-l-4 border-green-500">
            <div>
                <p class="text-sm md:text-lg font-medium text-gray-500">Con Contratos Activos</p>
                <p class="text-3xl md:text-5xl font-bold text-gray-900 mt-2">
                    <?php
                    $clienteModel = new Cliente();
                    echo $clienteModel->getClientesConContratosActivos();
                    ?>
                </p>
            </div>
            <i class="fas fa-file-contract text-4xl md:text-6xl text-green-400"></i>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
        <div class="bg-white shadow-xl rounded-lg p-4 md:p-6">
            <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-plus-circle text-green-500 mr-2"></i>
                Acciones Rápidas
            </h3>
            <div class="space-y-3">
                <a href="<?= BASE_URL ?>clientes/create" class="block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 text-center action-btn" data-url="<?= BASE_URL ?>clientes/create">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="action-text">Registrar Nuevo Cliente</span>
                    <div class="action-spinner hidden ml-2">
                        <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </a>
                <a href="<?= BASE_URL ?>contratos/create" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 text-center action-btn" data-url="<?= BASE_URL ?>contratos/create">
                    <i class="fas fa-file-contract mr-2"></i>
                    <span class="action-text">Crear Nuevo Contrato</span>
                    <div class="action-spinner hidden ml-2">
                        <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </a>
            </div>
        </div>

        <div class="bg-white shadow-xl rounded-lg p-4 md:p-6">
            <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-search text-purple-500 mr-2"></i>
                Búsqueda
            </h3>
            <div class="space-y-3">
                <input type="text" id="busquedaCliente" placeholder="Buscar por nombre o identificación..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm md:text-base">
                <button onclick="buscarClientes()" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
                    <i class="fas fa-search mr-2"></i> Buscar
                </button>
            </div>
        </div>
    </div>

    <!-- Lista de Clientes -->
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="px-4 md:px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg md:text-xl font-semibold text-gray-800">Directorio de Clientes</h3>
        </div>

        <?php if (empty($clientes)): ?>
            <div class="text-center py-12">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No hay clientes registrados aún.</p>
                <p class="text-gray-400 text-sm mt-2">Comienza registrando tu primer cliente para gestionar contratos.</p>
                <a href="<?= BASE_URL ?>clientes/create" class="inline-block mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
                    <i class="fas fa-plus mr-2"></i> Registrar Primer Cliente
                </a>
            </div>
        <?php else: ?>
            <!-- Tabla responsive -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Identificación</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Teléfono</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Dirección</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($clientes as $cliente): ?>
                            <tr class="hover:bg-gray-50 cliente-row">
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                                                <i class="fas fa-user text-white text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($cliente['nombre_completo']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                ID: <?= htmlspecialchars($cliente['id_cliente']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($cliente['identificacion']) ?>
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                    <?= htmlspecialchars($cliente['telefono'] ?? 'No registrado') ?>
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                    <?= htmlspecialchars($cliente['direccion'] ?? 'No registrada') ?>
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        <?php
                                        $completo = !empty($cliente['telefono']) && !empty($cliente['direccion']);
                                        echo $completo ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                                        ?>">
                                        <i class="fas fa-circle text-xs mr-1"></i>
                                        <?= $completo ? 'Completo' : 'Incompleto' ?>
                                    </span>
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?= BASE_URL ?>clientes/edit/<?= $cliente['id_cliente'] ?>" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>contratos/create?cliente=<?= $cliente['id_cliente'] ?>" class="text-green-600 hover:text-green-900" title="Crear Contrato">
                                            <i class="fas fa-plus-circle"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>clientes/delete/<?= $cliente['id_cliente'] ?>" class="text-red-600 hover:text-red-900"
                                           onclick="return confirm('¿Estás seguro de que deseas eliminar este cliente?')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Loading effect for action buttons
document.querySelectorAll('.action-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const actionBtn = this;
        const actionText = actionBtn.querySelector('.action-text');
        const actionSpinner = actionBtn.querySelector('.action-spinner');

        // Disable button and show loading
        actionBtn.style.pointerEvents = 'none';
        actionSpinner.classList.remove('hidden');

        // Navigate after a delay to show loading effect
        setTimeout(() => {
            window.location.href = actionBtn.dataset.url;
        }, 4000);
    });
});

function buscarClientes() {
    const busqueda = document.getElementById('busquedaCliente').value.toLowerCase();
    const filas = document.querySelectorAll('.cliente-row');

    filas.forEach(fila => {
        const nombre = fila.querySelector('.text-sm.font-medium').textContent.toLowerCase();
        const identificacion = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();

        if (nombre.includes(busqueda) || identificacion.includes(busqueda)) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

// Buscar en tiempo real
document.getElementById('busquedaCliente').addEventListener('input', function() {
    buscarClientes();
});
</script>
