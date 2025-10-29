<?php 
// /app/views/dashboard/index.php
// $stats viene inyectado del controlador
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <div class="bg-white shadow-xl rounded-lg p-6 flex items-center justify-between border-l-4 border-indigo-500">
        <div>
            <p class="text-sm font-medium text-gray-500">Motos Disponibles/Alquiladas</p>
            <p class="text-3xl font-bold text-gray-900 mt-1"><?= $stats['motos_activas'] ?></p>
        </div>
        <i class="fas fa-motorcycle text-4xl text-indigo-400"></i>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-6 flex items-center justify-between border-l-4 border-green-500">
        <div>
            <p class="text-sm font-medium text-gray-500">Ingreso Diario (Hoy)</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">$<?= number_format($stats['ingreso_dia'], 2) ?></p>
        </div>
        <i class="fas fa-hand-holding-dollar text-4xl text-green-400"></i>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-6 flex items-center justify-between border-l-4 border-yellow-500">
        <div>
            <p class="text-sm font-medium text-gray-500">Saldo Total Pendiente</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">$<?= number_format($stats['total_por_cobrar'], 2) ?></p>
        </div>
        <i class="fas fa-clock text-4xl text-yellow-400"></i>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-6 flex items-center justify-between border-l-4 border-red-500">
        <div>
            <p class="text-sm font-medium text-gray-500">Contratos en Morosidad</p>
            <p class="text-3xl font-bold text-gray-900 mt-1"><?= $stats['contratos_mora'] ?></p>
        </div>
        <i class="fas fa-triangle-exclamation text-4xl text-red-400"></i>
    </div>
</div>

<div class="bg-white shadow-xl rounded-lg p-6">
    <h3 class="text-xl font-semibold text-gray-800 mb-4">Actividad Reciente</h3>
    <p class="text-gray-500">Gráficos de Rentabilidad y Últimos Pagos aquí...</p>
</div>