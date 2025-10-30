<?php
// /app/views/motos/index.php
// $motos viene inyectado del controlador
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
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
        <div class="bg-white shadow-xl rounded-lg p-4 md:p-6 flex items-center justify-between border-l-4 border-indigo-500">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Motos</p>
                <p class="text-2xl md:text-3xl font-bold text-gray-900 mt-1"><?= count($motos) ?></p>
            </div>
            <i class="fas fa-motorcycle text-3xl md:text-4xl text-indigo-400"></i>
        </div>

        <div class="bg-white shadow-xl rounded-lg p-4 md:p-6 flex items-center justify-between border-l-4 border-green-500">
            <div>
                <p class="text-sm font-medium text-gray-500">Disponibles</p>
                <p class="text-2xl md:text-3xl font-bold text-gray-900 mt-1">
                    <?= count(array_filter($motos, function($m) { return $m['estado'] === 'activo'; })) ?>
                </p>
            </div>
            <i class="fas fa-check-circle text-3xl md:text-4xl text-green-400"></i>
        </div>

        <div class="bg-white shadow-xl rounded-lg p-4 md:p-6 flex items-center justify-between border-l-4 border-blue-500">
            <div>
                <p class="text-sm font-medium text-gray-500">En Uso</p>
                <p class="text-2xl md:text-3xl font-bold text-gray-900 mt-1">
                    <?= count(array_filter($motos, function($m) { return $m['estado'] === 'alquilada'; })) ?>
                </p>
            </div>
            <i class="fas fa-play-circle text-3xl md:text-4xl text-blue-400"></i>
        </div>

        <div class="bg-white shadow-xl rounded-lg p-4 md:p-6 flex items-center justify-between border-l-4 border-yellow-500">
            <div>
                <p class="text-sm font-medium text-gray-500">Mantenimiento</p>
                <p class="text-2xl md:text-3xl font-bold text-gray-900 mt-1">
                    <?= count(array_filter($motos, function($m) { return $m['estado'] === 'mantenimiento'; })) ?>
                </p>
            </div>
            <i class="fas fa-tools text-3xl md:text-4xl text-yellow-400"></i>
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
                <a href="<?= BASE_URL ?>motos/create" class="block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 text-center">
                    <i class="fas fa-plus mr-2"></i> Registrar Nueva Moto
                </a>
                <a href="<?= BASE_URL ?>contratos/create" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-300 text-center">
                    <i class="fas fa-file-contract mr-2"></i> Crear Nuevo Contrato
                </a>
            </div>
        </div>

        <div class="bg-white shadow-xl rounded-lg p-4 md:p-6">
            <h3 class="text-lg md:text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-filter text-purple-500 mr-2"></i>
                Filtros
            </h3>
            <div class="space-y-3">
                <select id="filtroEstado" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm md:text-base">
                    <option value="">Todos los Estados</option>
                    <option value="activo">Disponibles</option>
                    <option value="alquilada">En Uso</option>
                    <option value="mantenimiento">En Mantenimiento</option>
                    <option value="inactivo">Inactivas</option>
                </select>
                <button onclick="filtrarMotos()" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-300">
                    <i class="fas fa-search mr-2"></i> Aplicar Filtro
                </button>
            </div>
        </div>
    </div>

    <!-- Lista de Motos -->
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="px-4 md:px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg md:text-xl font-semibold text-gray-800">Inventario de Motos</h3>
        </div>

        <?php if (empty($motos)): ?>
            <div class="text-center py-12">
                <i class="fas fa-motorcycle text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No hay motos registradas aún.</p>
                <p class="text-gray-400 text-sm mt-2">Comienza registrando tu primera moto para gestionar contratos.</p>
            </div>
        <?php else: ?>
            <!-- Tabla responsive -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Moto</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Valor</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Fecha Adq.</th>
                            <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($motos as $moto): ?>
                            <tr class="hover:bg-gray-50 moto-row" data-estado="<?= $moto['estado'] ?>">
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                                                <i class="fas fa-motorcycle text-white text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($moto['placa']) ?>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <?= htmlspecialchars($moto['marca']) ?> <?= htmlspecialchars($moto['modelo']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        <?php
                                        switch($moto['estado']) {
                                            case 'disponible':
                                                echo 'bg-green-100 text-green-800';
                                                break;
                                            case 'activo':
                                                echo 'bg-blue-100 text-blue-800';
                                                break;
                                            case 'mantenimiento':
                                                echo 'bg-yellow-100 text-yellow-800';
                                                break;
                                            case 'inactivo':
                                                echo 'bg-red-100 text-red-800';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <i class="fas fa-circle text-xs mr-1"></i>
                                        <?= htmlspecialchars(ucfirst($moto['estado'])) ?>
                                    </span>
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden md:table-cell">
                                    $<?= number_format($moto['valor_adquisicion'], 2) ?>
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                    <?= date('d/m/Y', strtotime($moto['fecha_adquisicion'])) ?>
                                </td>
                                <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?= BASE_URL ?>motos/edit/<?= $moto['id_moto'] ?>" class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>contratos/create?moto=<?= $moto['id_moto'] ?>" class="text-green-600 hover:text-green-900" title="Crear Contrato">
                                            <i class="fas fa-plus-circle"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>motos/delete/<?= $moto['id_moto'] ?>" class="text-red-600 hover:text-red-900"
                                           onclick="return confirm('¿Estás seguro de que deseas eliminar esta moto?')">
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
function filtrarMotos() {
    const filtro = document.getElementById('filtroEstado').value;
    const filas = document.querySelectorAll('.moto-row');

    filas.forEach(fila => {
        if (filtro === '' || fila.getAttribute('data-estado') === filtro) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

// Limpiar filtro al cambiar selección
document.getElementById('filtroEstado').addEventListener('change', function() {
    if (this.value === '') {
        filtrarMotos();
    }
});
</script>
