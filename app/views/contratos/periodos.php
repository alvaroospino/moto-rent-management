<style>
    .status-badge {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .progress-bar {
        transition: width 0.5s ease;
    }

    .stat-card {
        background: linear-gradient(135deg, var(--bg-start) 0%, var(--bg-end) 100%);
    }

    .tab-button {
        transition: all 0.3s ease;
    }

    .tab-button.active {
        background-color: #4f46e5;
        color: white;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Optimizaciones de espacio */
    .compact-table td, .compact-table th {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }

    .compact-table th {
        font-size: 0.75rem;
    }

    .info-grid-compact {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .info-item-compact {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .info-item-compact label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
    }

    .info-item-compact p {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1f2937;
    }

    .stat-card-compact {
        padding: 1rem;
    }

    .stat-card-compact .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .stat-card-compact .stat-label {
        font-size: 0.75rem;
        opacity: 0.9;
    }

    .stat-card-compact .stat-icon {
        font-size: 1.5rem;
        opacity: 0.7;
    }

    /* Layout de dos columnas */
    .main-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 1.5rem;
    }

    @media (max-width: 1280px) {
        .main-layout {
            grid-template-columns: 1fr;
        }
    }

    .sidebar-compact {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .sidebar-compact {
            grid-template-columns: 1fr;
        }
    }

    .sidebar-card {
        background: white;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .sidebar-card-header {
        background: linear-gradient(to right, #f3f4f6, #e5e7eb);
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .sidebar-card-header h3 {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .sidebar-card-body {
        padding: 1rem;
    }

    .sidebar-info-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 0;
        font-size: 0.85rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .sidebar-info-item:last-child {
        border-bottom: none;
    }

    .sidebar-info-label {
        color: #6b7280;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .sidebar-info-value {
        color: #1f2937;
        font-weight: 600;
    }

    .quick-action-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 0.75rem 1rem;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 0.5rem;
    }

    .quick-action-btn:hover {
        transform: translateX(2px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .quick-action-btn.green:hover {
        background: #f0fdf4;
        border-color: #86efac;
    }

    .quick-action-btn.blue:hover {
        background: #f0f9ff;
        border-color: #7dd3fc;
    }

    .quick-action-btn.purple:hover {
        background: #faf5ff;
        border-color: #e9d5ff;
    }

    .quick-action-icon {
        width: 32px;
        height: 32px;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        margin-right: 0.75rem;
    }

    .header-compact {
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .header-compact-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .header-compact-title {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        flex: 1;
        min-width: 0;
    }

    .header-compact-title h1 {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1f2937;
        margin: 0;
        line-height: 1.2;
    }

    .header-compact-title .subtitle {
        font-size: 0.8rem;
        color: #6b7280;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .header-compact-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .header-compact-actions button,
    .header-compact-actions a {
        padding: 0.625rem 1.25rem;
        font-size: 0.85rem;
        border-radius: 0.625rem;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .header-compact-actions .btn-back {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .header-compact-actions .btn-back:hover {
        background: #e5e7eb;
        border-color: #9ca3af;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header-compact-actions .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .header-compact-actions .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .header-compact-actions .btn-secondary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }

    .header-compact-actions .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .header-compact-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
        font-size: 0.8rem;
    }

    .header-info-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #6b7280;
        padding: 0.75rem;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-radius: 0.625rem;
        border: 1px solid #e5e7eb;
    }

    .header-info-item i {
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .header-info-item strong {
        color: #1f2937;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .header-compact {
            padding: 1rem;
        }

        .header-compact-top {
            flex-direction: column;
            margin-bottom: 1rem;
        }

        .header-compact-title h1 {
            font-size: 1.5rem;
        }

        .header-compact-actions {
            width: 100%;
            justify-content: stretch;
        }

        .header-compact-actions button,
        .header-compact-actions a {
            flex: 1;
            justify-content: center;
            padding: 0.75rem 0.5rem;
            font-size: 0.8rem;
        }

        .header-compact-info {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .header-compact {
            padding: 0.875rem;
            margin-bottom: 1rem;
        }

        .header-compact-title h1 {
            font-size: 1.25rem;
        }

        .header-compact-title .subtitle {
            font-size: 0.75rem;
        }

        .header-compact-actions button,
        .header-compact-actions a {
            padding: 0.625rem 0.5rem;
            font-size: 0.75rem;
        }

        .header-compact-actions i {
            display: none;
        }

        .header-info-item {
            padding: 0.5rem;
            font-size: 0.75rem;
        }
    }

    /* Tabla compacta */
    .table-container {
        overflow-x: auto;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
    }

    .table-container table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-container thead {
        background: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }

    .table-container th {
        padding: 0.75rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #374151;
    }

    .table-container td {
        padding: 0.75rem;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.85rem;
    }

    .table-container tbody tr:hover {
        background: #f9fafb;
    }

    .table-container tbody tr:last-child td {
        border-bottom: none;
    }

    .badge-small {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.7rem;
        font-weight: 600;
        border-radius: 0.25rem;
        border: 1px solid;
    }

    .badge-green {
        background: #dcfce7;
        color: #166534;
        border-color: #86efac;
    }

    .badge-blue {
        background: #dbeafe;
        color: #1e40af;
        border-color: #7dd3fc;
    }

    .badge-amber {
        background: #fef3c7;
        color: #92400e;
        border-color: #fcd34d;
    }

    .badge-emerald {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border-color: #6ee7b7;
    }

    .section-card {
        background: white;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .section-card-header {
        background: linear-gradient(to right, #f3f4f6, #e5e7eb);
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-card-header h2 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .section-card-header-icon {
        width: 32px;
        height: 32px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .section-card-body {
        padding: 1rem;
    }

    .scrollable-table {
        max-height: 400px;
        overflow-y: auto;
    }

    .scrollable-table::-webkit-scrollbar {
        width: 6px;
    }

    .scrollable-table::-webkit-scrollbar-track {
        background: #f3f4f6;
    }

    .scrollable-table::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }

    .scrollable-table::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    /* Estilos para detalles del contrato */
    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .detail-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        display: flex;
        align-items: center;
    }

    .detail-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1f2937;
    }

    /* Estilos para periodos */
    .periodo-item {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
        transition: all 0.3s ease;
    }

    .periodo-item:hover {
        background: linear-gradient(135deg, #f3f4f6 0%, #eeeff2 100%);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-color: #d1d5db;
    }

    /* Estilos para cards de detalles */
    .detail-card {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }

    .detail-card:hover {
        background: linear-gradient(135deg, #f3f4f6 0%, #eeeff2 100%);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-color: #d1d5db;
        transform: translateY(-2px);
    }

    .detail-card.highlight {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-color: #fcd34d;
    }

    .detail-card.highlight:hover {
        background: linear-gradient(135deg, #fde68a 0%, #fcd34d 100%);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15);
    }

    .detail-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .detail-card-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .detail-card-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
    }

    .detail-card-value {
        font-size: 1rem;
        font-weight: 700;
        color: #1f2937;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        .container, .container * {
            visibility: visible;
        }
        .container {
            position: absolute;
            left: 0;
            top: 0;
        }
        button, .no-print {
            display: none !important;
        }
    }
</style>

<div class="min-h-screen bg-gray-50 py-6">
    <div class="container mx-auto max-w-7xl">

        <!-- Breadcrumb -->
        <nav class="flex mb-4 text-sm" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li>
                    <a href="<?= BASE_URL ?>contratos" class="text-indigo-600 hover:text-indigo-700 font-medium">
                        <i class="fas fa-list mr-1"></i>Contratos
                    </a>
                </li>
                <li class="text-gray-400">
                    <i class="fas fa-chevron-right"></i>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>contratos/detail/<?= $contrato['id_contrato'] ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">
                        Contrato #<?= htmlspecialchars($contrato['id_contrato'] ?? 'N/A') ?>
                    </a>
                </li>
                <li class="text-gray-400">
                    <i class="fas fa-chevron-right"></i>
                </li>
                <li class="text-gray-600 font-medium">
                    Periodos
                </li>
            </ol>
        </nav>

        <!-- Header Compacto -->
        <div class="header-compact">
            <div class="header-compact-top">
                <div class="header-compact-title">
                    <div>
                        <h1>
                            <i class="fas fa-calendar-check text-indigo-600 mr-2"></i>
                            Periodos del Contrato
                        </h1>
                        <div class="subtitle mt-1">
                            <i class="fas fa-file-contract mr-1"></i>
                            Contrato #<?= htmlspecialchars($contrato['id_contrato'] ?? 'N/A') ?>
                            <span class="mx-2">•</span>
                            <i class="fas fa-user mr-1"></i>
                            <?= htmlspecialchars($cliente['nombre_completo'] ?? 'N/A') ?>
                        </div>
                    </div>
                </div>
                <div class="header-compact-actions">
                    <a href="<?= BASE_URL ?>contratos/detail/<?= $contrato['id_contrato'] ?>" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Volver al Control Diario
                    </a>
                    <a href="<?= BASE_URL ?>contratos/details/<?= $contrato['id_contrato'] ?>" class="btn-secondary">
                        <i class="fas fa-info-circle"></i>
                        Detalles Completos
                    </a>
                </div>
            </div>

            <!-- Info rápida en header -->
            <div class="header-compact-info">
                <div class="header-info-item">
                    <i class="fas fa-calendar-alt text-blue-600"></i>
                    <span>Inicio: <strong><?= date('d/m/Y', strtotime($contrato['fecha_inicio'] ?? 'now')) ?></strong></span>
                </div>
                <div class="header-info-item">
                    <i class="fas fa-clock text-purple-600"></i>
                    <span>Plazo: <strong><?= $contrato['plazo_meses'] ?? 0 ?> meses</strong></span>
                </div>
                <div class="header-info-item">
                    <i class="fas fa-money-bill-wave text-green-600"></i>
                    <span>Cuota: <strong>$<?= number_format($contrato['cuota_mensual'] ?? 0, 0, ',', '.') ?></strong></span>
                </div>
                <div class="header-info-item">
                    <i class="fas fa-chart-line text-emerald-600"></i>
                    <span>Total Pagado: <strong>$<?= number_format($totalPagado ?? 0, 0, ',', '.') ?></strong></span>
                </div>
            </div>
        </div>

        <!-- Grid de Periodos e Historial Completo -->
        <div class="grid grid-cols-1 gap-6">

            <!-- Todos los Periodos del Contrato -->
            <div class="section-card">
                <div class="section-card-header">
                    <div class="section-card-header-icon bg-indigo-100 text-indigo-600">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h2>Historial Completo de Periodos</h2>
                </div>
                <div class="section-card-body">
                    <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                        <?php if (!empty($historialPeriodos)): ?>
                            <?php foreach ($historialPeriodos as $historial): ?>
                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <span class="badge-small badge-blue">
                                                <i class="fas fa-hashtag"></i>
                                                <?= htmlspecialchars($historial['periodo']['numero_periodo']) ?>
                                            </span>
                                            <span class="badge-small
                                                <?php
                                                switch($historial['periodo']['estado_periodo']) {
                                                    case 'abierto':
                                                        echo 'badge-green';
                                                        break;
                                                    case 'cerrado':
                                                        echo 'badge-blue';
                                                        break;
                                                    default:
                                                        echo 'badge-blue';
                                                }
                                                ?>">
                                                <i class="fas fa-circle"></i>
                                                <?= ucfirst(htmlspecialchars($historial['periodo']['estado_periodo'])) ?>
                                            </span>
                                            <span class="text-sm text-gray-600">
                                                <?= date('d/m/Y', strtotime($historial['periodo']['fecha_inicio_periodo'])) ?> -
                                                <?= date('d/m/Y', strtotime($historial['periodo']['fecha_fin_periodo'])) ?>
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-gray-900">
                                                Total Pagado: $<?= number_format($historial['metricas']['total_pagado'], 0, ',', '.') ?>
                                            </div>
                                            <?php if ($historial['periodo']['estado_periodo'] === 'abierto'): ?>
                                                <button type="button"
                                                        class="text-xs px-2 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded transition-colors mt-1"
                                                        onclick="cerrarPeriodo(<?= $contrato['id_contrato'] ?>, <?= $historial['periodo']['id_periodo'] ?>)">
                                                    <i class="fas fa-lock mr-1"></i>Cerrar
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                                        <div class="bg-white border rounded p-2 text-center">
                                            <div class="text-xs text-gray-500">Días Hábiles</div>
                                            <div class="font-bold text-gray-800 text-lg"><?= $historial['metricas']['habiles'] ?></div>
                                        </div>
                                        <div class="bg-green-50 border border-green-200 rounded p-2 text-center">
                                            <div class="text-xs text-green-700">Pagados</div>
                                            <div class="font-bold text-green-800 text-lg"><?= $historial['metricas']['pagados'] ?></div>
                                        </div>
                                        <div class="bg-amber-50 border border-amber-200 rounded p-2 text-center">
                                            <div class="text-xs text-amber-700">Pendientes</div>
                                            <div class="font-bold text-amber-800 text-lg"><?= $historial['metricas']['pendientes'] ?></div>
                                        </div>
                                        <div class="bg-red-50 border border-red-200 rounded p-2 text-center">
                                            <div class="text-xs text-red-700">No Pago</div>
                                            <div class="font-bold text-red-800 text-lg"><?= $historial['metricas']['nopago'] ?></div>
                                        </div>
                                    </div>

                                    <!-- Botón para ver detalles del periodo -->
                                    <div class="flex justify-center">
                                        <button onclick="togglePeriodoDetail(<?= $historial['periodo']['id_periodo'] ?>)"
                                                class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors">
                                            <i class="fas fa-eye"></i>
                                            Ver Detalles del Periodo
                                            <i id="chevron-<?= $historial['periodo']['id_periodo'] ?>" class="fas fa-chevron-down transition-transform"></i>
                                        </button>
                                    </div>

                                    <!-- Detalle expandible -->
                                    <div id="detail-<?= $historial['periodo']['id_periodo'] ?>" class="hidden border-t border-gray-200 bg-gray-50 mt-3">
                                        <div class="p-4 space-y-4">
                                            <!-- Pagos realizados en este periodo -->
                                            <?php if (!empty($historial['pagos'])): ?>
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                                        <i class="fas fa-dollar-sign text-green-600"></i>
                                                        Pagos Realizados (<?= count($historial['pagos']) ?>)
                                                    </h4>
                                                    <div class="space-y-2">
                                                        <?php foreach ($historial['pagos'] as $pago): ?>
                                                            <div class="bg-white border border-gray-200 rounded p-3">
                                                                <div class="flex items-center justify-between">
                                                                    <div class="flex items-center gap-3">
                                                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                                            <i class="fas fa-dollar-sign text-green-600 text-xs"></i>
                                                                        </div>
                                                                        <div>
                                                                            <div class="text-sm font-medium text-gray-900">
                                                                                $<?= number_format($pago['monto_pago'], 0, ',', '.') ?>
                                                                            </div>
                                                                            <div class="text-xs text-gray-500">
                                                                                <?php
                                                                                $dias_semana = [
                                                                                    'Monday' => 'Lunes',
                                                                                    'Tuesday' => 'Martes',
                                                                                    'Wednesday' => 'Miércoles',
                                                                                    'Thursday' => 'Jueves',
                                                                                    'Friday' => 'Viernes',
                                                                                    'Saturday' => 'Sábado',
                                                                                    'Sunday' => 'Domingo'
                                                                                ];
                                                                                $dia_semana = $dias_semana[date('l', strtotime($pago['fecha_pago']))] ?? '';
                                                                                echo $dia_semana . ' ' . date('d/m/Y H:i', strtotime($pago['fecha_pago']));
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="text-right">
                                                                        <?php if (!empty($pago['comprobante'])): ?>
                                                                            <a href="<?= BASE_URL ?><?= htmlspecialchars($pago['comprobante']) ?>"
                                                                               target="_blank"
                                                                               class="text-xs px-2 py-1 bg-green-600 hover:bg-green-700 text-white rounded transition-colors inline-flex items-center gap-1"
                                                                               title="Ver comprobante">
                                                                                <i class="fas fa-eye"></i>
                                                                                Ver
                                                                            </a>
                                                                        <?php else: ?>
                                                                            <span class="text-xs text-gray-400">Sin comprobante</span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Días del periodo -->
                                            <div>
                                                <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                                    <i class="fas fa-calendar-day text-blue-600"></i>
                                                    Días del Periodo
                                                </h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                                    <?php foreach ($historial['dias'] as $dia): ?>
                                                        <div class="bg-white border border-gray-200 rounded p-2 text-xs">
                                                            <div class="flex items-center justify-between mb-1">
                                                                <span class="font-medium text-gray-900">
                                                                    <?php
                                                                    $dias_semana = [
                                                                        'Monday' => 'Lunes',
                                                                        'Tuesday' => 'Martes',
                                                                        'Wednesday' => 'Miércoles',
                                                                        'Thursday' => 'Jueves',
                                                                        'Friday' => 'Viernes',
                                                                        'Saturday' => 'Sábado',
                                                                        'Sunday' => 'Domingo'
                                                                    ];
                                                                    $dia_semana = $dias_semana[date('l', strtotime($dia['fecha']))] ?? '';
                                                                    echo $dia_semana . ' ' . date('d/m/Y', strtotime($dia['fecha']));
                                                                    ?>
                                                                </span>
                                                                <span class="badge-small
                                                                    <?php
                                                                    switch($dia['estado_dia']) {
                                                                        case 'pagado':
                                                                            echo 'badge-green';
                                                                            break;
                                                                        case 'no_pago':
                                                                            echo 'badge-amber';
                                                                            break;
                                                                        default:
                                                                            echo 'badge-blue';
                                                                    }
                                                                    ?>">
                                                                    <i class="fas fa-circle"></i>
                                                                    <?= ucfirst($dia['estado_dia']) ?>
                                                                </span>
                                                            </div>
                                                            <div class="text-gray-600">
                                                                Monto: $<?= number_format($dia['monto_pagado'], 0, ',', '.') ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-inbox text-3xl mb-2 block"></i>
                                <p class="text-sm font-medium">No hay periodos registrados</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
// Función para cerrar periodo
function cerrarPeriodo(idContrato, idPeriodo) {
    if (confirm('¿Está seguro de cerrar este periodo? Esta acción no se puede deshacer.')) {
        fetch('<?= BASE_URL ?>contratos/cerrarPeriodo/' + idContrato + '/' + idPeriodo, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cerrar el periodo');
        });
    }
}

// Función para toggle detalles del periodo
function togglePeriodoDetail(idPeriodo) {
    const detailDiv = document.getElementById('detail-' + idPeriodo);
    const chevron = document.getElementById('chevron-' + idPeriodo);

    if (detailDiv.classList.contains('hidden')) {
        detailDiv.classList.remove('hidden');
        chevron.classList.add('rotate-180');
    } else {
        detailDiv.classList.add('hidden');
        chevron.classList.remove('rotate-180');
    }
}
</script>
