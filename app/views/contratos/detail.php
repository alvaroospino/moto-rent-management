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
        background: #d1fae5;
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
    <div class="container mx-auto px-4 max-w-7xl">

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
                <li class="text-gray-600 font-medium">
                    Contrato #<?= htmlspecialchars($contrato['id_contrato'] ?? 'N/A') ?>
                </li>
            </ol>
        </nav>

        <!-- Header Compacto -->
        <div class="header-compact">
            <div class="header-compact-top">
                <div class="header-compact-title">
                    <div>
                        <h1>
                            <i class="fas fa-file-contract text-indigo-600 mr-2"></i>
                            Contrato #<?= htmlspecialchars($contrato['id_contrato'] ?? 'N/A') ?>
                        </h1>
                        <div class="subtitle mt-1">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Inicio: <?= date('d/m/Y', strtotime($contrato['fecha_inicio'] ?? 'now')) ?>
                            <span class="mx-2">•</span>
                            <i class="fas fa-hourglass-half mr-1"></i>
                            <?= $contrato['plazo_meses'] ?? 0 ?> meses
                        </div>
                    </div>
                </div>
                <div class="header-compact-actions">
                    <a href="<?= BASE_URL ?>contratos" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Volver
                    </a>
                    <a href="<?= BASE_URL ?>pagos/contrato/<?= $contrato['id_contrato'] ?? '' ?>" class="btn-primary">
                        <i class="fas fa-dollar-sign"></i>
                        Pago
                    </a>
                    <a href="<?= BASE_URL ?>contratos/edit/<?= $contrato['id_contrato'] ?? '' ?>" class="btn-secondary">
                        <i class="fas fa-edit"></i>
                        Editar
                    </a>
                </div>
            </div>

            <!-- Info rápida en header -->
            <div class="header-compact-info">
                <div class="header-info-item">
                    <i class="fas fa-check-circle text-green-600"></i>
                    <span>Estado: <strong><?= ucfirst(htmlspecialchars($contrato['estado'] ?? 'Desconocido')) ?></strong></span>
                </div>
                <div class="header-info-item">
                    <i class="fas fa-money-bill-wave text-blue-600"></i>
                    <span>Cuota: <strong>$<?= number_format($contrato['cuota_mensual'] ?? 0, 0, ',', '.') ?></strong></span>
                </div>
                <div class="header-info-item">
                    <i class="fas fa-chart-line text-purple-600"></i>
                    <span>Pagado: <strong>$<?= number_format($totalPagado ?? 0, 0, ',', '.') ?></strong></span>
                </div>
                <div class="header-info-item">
                    <i class="fas fa-piggy-bank text-emerald-600"></i>
                    <span>Saldo: <strong>$<?= number_format($contrato['saldo_restante'] ?? 0, 0, ',', '.') ?></strong></span>
                </div>
            </div>
        </div>

        <!-- Layout Principal: Contenido + Sidebar -->
        <div class="main-layout">

            <!-- COLUMNA PRINCIPAL (70%) -->
            <div>

                <!-- Dashboard Financiero Compacto -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                    <!-- Cuota Mensual -->
                    <div class="stat-card stat-card-compact rounded-lg text-white shadow-sm card-hover" style="--bg-start: #10b981; --bg-end: #059669;">
                        <div class="flex items-center justify-between mb-2">
                            <i class="fas fa-money-bill-wave stat-icon"></i>
                            <span class="text-xs font-medium bg-white/20 px-1.5 py-0.5 rounded">Mensual</span>
                        </div>
                        <div class="stat-value">$<?= number_format($contrato['cuota_mensual'] ?? 0, 0, ',', '.') ?></div>
                        <div class="stat-label">Cuota Mensual</div>
                    </div>

                    <!-- Abono Capital -->
                    <div class="stat-card stat-card-compact rounded-lg text-white shadow-sm card-hover" style="--bg-start: #3b82f6; --bg-end: #2563eb;">
                        <div class="flex items-center justify-between mb-2">
                            <i class="fas fa-piggy-bank stat-icon"></i>
                            <span class="text-xs font-medium bg-white/20 px-1.5 py-0.5 rounded">Capital</span>
                        </div>
                        <div class="stat-value">$<?= number_format($contrato['abono_capital_mensual'] ?? 0, 0, ',', '.') ?></div>
                        <div class="stat-label">Abono Capital</div>
                    </div>

                    <!-- Capital Amortizado -->
                    <div class="stat-card stat-card-compact rounded-lg text-white shadow-sm card-hover" style="--bg-start: #8b5cf6; --bg-end: #7c3aed;">
                        <div class="flex items-center justify-between mb-2">
                            <i class="fas fa-chart-line stat-icon"></i>
                            <span class="text-xs font-medium bg-white/20 px-1.5 py-0.5 rounded">Pagado</span>
                        </div>
                        <div class="stat-value">$<?= number_format($contrato['saldo_restante'] ?? 0, 0, ',', '.') ?></div>
                        <div class="stat-label">Capital Pagado</div>
                    </div>

                    <!-- Total Pagado -->
                    <div class="stat-card stat-card-compact rounded-lg text-white shadow-sm card-hover" style="--bg-start: #f59e0b; --bg-end: #d97706;">
                        <div class="flex items-center justify-between mb-2">
                            <i class="fas fa-wallet stat-icon"></i>
                            <span class="text-xs font-medium bg-white/20 px-1.5 py-0.5 rounded">Total</span>
                        </div>
                        <div class="stat-value">$<?= number_format($totalPagado ?? 0, 0, ',', '.') ?></div>
                        <div class="stat-label">Total Pagado</div>
                    </div>
                </div>

                <!-- Detalles del Contrato (Ancho completo con grid) -->
                <div class="section-card mb-6">
                    <div class="section-card-header">
                        <div class="section-card-header-icon bg-indigo-100 text-indigo-600">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h2>Detalles del Contrato</h2>
                    </div>
                    <div class="section-card-body">
                        <!-- Grid de detalles principales -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            <!-- Estado -->
                            <div class="detail-card">
                                <div class="detail-card-icon bg-green-100 text-green-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="detail-card-content">
                                    <div class="detail-card-label">Estado</div>
                                    <span class="status-badge inline-flex px-2 py-1 text-xs font-semibold rounded-md w-fit
                                        <?php
                                        switch($contrato['estado'] ?? 'desconocido') {
                                            case 'activo':
                                                echo 'bg-green-100 text-green-800 border border-green-200';
                                                break;
                                            case 'finalizado':
                                                echo 'bg-blue-100 text-blue-800 border border-blue-200';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-800 border border-gray-200';
                                        }
                                        ?>">
                                        <i class="fas fa-circle text-xs mr-1"></i>
                                        <?= ucfirst(htmlspecialchars($contrato['estado'] ?? 'Desconocido')) ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Fecha de Inicio -->
                            <div class="detail-card">
                                <div class="detail-card-icon bg-blue-100 text-blue-600">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="detail-card-content">
                                    <div class="detail-card-label">Fecha de Inicio</div>
                                    <p class="detail-card-value"><?= date('d/m/Y', strtotime($contrato['fecha_inicio'] ?? 'now')) ?></p>
                                </div>
                            </div>

                            <!-- Plazo Total -->
                            <div class="detail-card">
                                <div class="detail-card-icon bg-purple-100 text-purple-600">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                                <div class="detail-card-content">
                                    <div class="detail-card-label">Plazo Total</div>
                                    <p class="detail-card-value"><?= $contrato['plazo_meses'] ?? 0 ?> meses</p>
                                </div>
                            </div>

                            <!-- Valor del Vehículo -->
                            <div class="detail-card highlight">
                                <div class="detail-card-icon bg-green-100 text-green-600">
                                    <i class="fas fa-motorcycle"></i>
                                </div>
                                <div class="detail-card-content">
                                    <div class="detail-card-label">Valor del Vehículo</div>
                                    <p class="detail-card-value text-green-600">$<?= number_format($contrato['valor_vehiculo'] ?? 0, 0, ',', '.') ?></p>
                                </div>
                            </div>

                            <!-- Capital Amortizado -->
                            <div class="detail-card highlight">
                                <div class="detail-card-icon bg-indigo-100 text-indigo-600">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="detail-card-content">
                                    <div class="detail-card-label">Capital Amortizado</div>
                                    <p class="detail-card-value text-indigo-600">$<?= number_format($contrato['saldo_restante'] ?? 0, 0, ',', '.') ?></p>
                                </div>
                            </div>

                            <!-- Saldo Pendiente -->
                            <div class="detail-card highlight">
                                <div class="detail-card-icon bg-red-100 text-red-600">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="detail-card-content">
                                    <div class="detail-card-label">Saldo Pendiente</div>
                                    <p class="detail-card-value text-red-600">$<?= number_format(($contrato['valor_vehiculo'] ?? 0) - ($contrato['saldo_restante'] ?? 0), 0, ',', '.') ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Observaciones (si existen) -->
                        <?php if (!empty($contrato['observaciones'])): ?>
                            <div class="border-t pt-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-sticky-note text-amber-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Observaciones</div>
                                        <p class="text-sm text-gray-700 bg-amber-50 p-3 rounded-md border border-amber-200">
                                            <?= nl2br(htmlspecialchars($contrato['observaciones'])) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Control Diario del Periodo Actual -->
                <div class="section-card mb-6">
                    <div class="section-card-header">
                        <div class="section-card-header-icon bg-amber-100 text-amber-600">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <h2>Control Diario (Periodo Actual)</h2>
                    </div>
                    <div class="section-card-body">
                        <?php
                        // Determinar periodo actual de la lista disponible
                        $periodoActualVista = null;
                        if (!empty($periodos)) {
                            foreach ($periodos as $p) {
                                if (($p['estado_periodo'] ?? '') === 'abierto') { $periodoActualVista = $p; break; }
                            }
                            if (!$periodoActualVista) { $periodoActualVista = $periodos[0]; }
                        }
                        ?>

                        <?php if ($periodoActualVista): ?>
                            <?php
                            // Cargar días del periodo (requiere PeriodoContrato::getDiasPeriodo)
                            $diasPeriodo = PeriodoContrato::getDiasPeriodo($contrato['id_contrato'], $periodoActualVista['id_periodo']);
                            // Resumen
                            $habiles = 0; $pagados = 0; $pendientes = 0; $nopago = 0; $totalDia = 0;
                            foreach ($diasPeriodo as $d) {
                                if ((int)$d['es_domingo'] === 1) { continue; }
                                $habiles++;
                                $totalDia += (float)$d['monto_pagado'];
                                switch ($d['estado_dia']) {
                                    case 'pagado': $pagados++; break;
                                    case 'no_pago': $nopago++; break;
                                    default: $pendientes++; break;
                                }
                            }
                            ?>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4 text-xs">
                                <div class="bg-gray-50 border rounded p-2">
                                    <div class="text-gray-500">Días Hábiles</div>
                                    <div class="font-bold text-gray-800"><?= $habiles ?></div>
                                </div>
                                <div class="bg-green-50 border border-green-200 rounded p-2">
                                    <div class="text-green-700">Días Pagados</div>
                                    <div class="font-bold text-green-800"><?= $pagados ?></div>
                                </div>
                                <div class="bg-amber-50 border border-amber-200 rounded p-2">
                                    <div class="text-amber-700">Pendientes</div>
                                    <div class="font-bold text-amber-800"><?= $pendientes ?></div>
                                </div>
                                <div class="bg-red-50 border border-red-200 rounded p-2">
                                    <div class="text-red-700">No Pago</div>
                                    <div class="font-bold text-red-800"><?= $nopago ?></div>
                                </div>
                            </div>

                            <div class="table-container scrollable-table">
                                <table class="compact-table">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                            <th>Monto Día</th>
                                            <th>Obs.</th>
                                            <th class="text-right">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($diasPeriodo as $dia): ?>
                                            <?php if ((int)$dia['es_domingo'] === 1) continue; ?>
                                            <tr>
                                                <td><?= date('d/m/Y', strtotime($dia['fecha'])) ?></td>
                                                <td>
                                                    <span class="badge-small <?php 
                                                        echo $dia['estado_dia']==='pagado'?'badge-green':($dia['estado_dia']==='no_pago'?'badge-amber':''); ?>">
                                                        <i class="fas fa-circle"></i>
                                                        <?= htmlspecialchars($dia['estado_dia']) ?>
                                                    </span>
                                                </td>
                                                <td>$<?= number_format($dia['monto_pagado'], 0, ',', '.') ?></td>
                                                <td class="text-xs text-gray-600"><?= htmlspecialchars($dia['observacion'] ?? '') ?></td>
                                                <td class="text-right">
                                                    <div class="flex gap-2 justify-end">
                                                        <a class="text-xs px-2 py-1 bg-green-600 hover:bg-green-700 text-white rounded" href="<?= BASE_URL ?>pagos/contrato/<?= $contrato['id_contrato'] ?>?fecha=<?= htmlspecialchars($dia['fecha']) ?>">
                                                            <i class="fas fa-plus mr-1"></i>Pago
                                                        </a>
                                                        <form method="POST" action="<?= BASE_URL ?>pagos/marcar-no-pago">
                                                            <input type="hidden" name="id_contrato" value="<?= $contrato['id_contrato'] ?>">
                                                            <input type="hidden" name="id_periodo" value="<?= $periodoActualVista['id_periodo'] ?>">
                                                            <input type="hidden" name="fecha" value="<?= htmlspecialchars($dia['fecha']) ?>">
                                                            <button class="text-xs px-2 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded" type="submit" onclick="return confirm('¿Marcar no pago el <?= date('d/m/Y', strtotime($dia['fecha'])) ?>?')">
                                                                <i class="fas fa-ban mr-1"></i>No pago
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-6 text-gray-500 text-sm">
                                No hay periodo actual disponible.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Grid de Periodos e Historial de Pagos -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Periodos del Contrato -->
                    <div class="section-card">
                        <div class="section-card-header">
                            <div class="section-card-header-icon bg-indigo-100 text-indigo-600">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h2>Periodos del Contrato</h2>
                        </div>
                        <div class="section-card-body">
                            <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                                <?php if (!empty($periodos)): ?>
                                    <?php foreach ($periodos as $periodo): ?>
                                        <div class="periodo-item">
                                            <div class="flex items-start justify-between mb-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="badge-small badge-blue">
                                                        <i class="fas fa-hashtag"></i>
                                                        <?= htmlspecialchars($periodo['numero_periodo']) ?>
                                                    </span>
                                                    <span class="badge-small
                                                        <?php
                                                        switch($periodo['estado_periodo']) {
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
                                                        <?= ucfirst(htmlspecialchars($periodo['estado_periodo'])) ?>
                                                    </span>
                                                </div>
                                                <?php if (isset($periodo['pago_anticipado']) && $periodo['pago_anticipado'] == 1): ?>
                                                    <span class="badge-small badge-emerald">
                                                        <i class="fas fa-bolt"></i>
                                                        Anticipado
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="text-xs text-gray-600 mb-2">
                                                <i class="fas fa-calendar-days mr-1"></i>
                                                <?= date('d/m/Y', strtotime($periodo['fecha_inicio_periodo'])) ?> 
                                                <span class="text-gray-400">a</span> 
                                                <?= date('d/m/Y', strtotime($periodo['fecha_fin_periodo'])) ?>
                                            </div>

                                            <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                                                <div>
                                                    <span class="text-xs text-gray-600">Acumulado:</span>
                                                    <p class="font-bold text-green-600">
                                                        $<?= number_format($periodo['cuota_acumulada'] ?? 0, 0, ',', '.') ?>
                                                    </p>
                                                </div>
                                                <?php if ($periodo['estado_periodo'] === 'abierto'): ?>
                                                    <form method="POST" action="<?= BASE_URL ?>contratos/<?= $contrato['id_contrato'] ?>/cerrar-periodo/<?= $periodo['id_periodo'] ?>" style="display: inline;">
                                                        <button type="submit" 
                                                                class="text-xs px-2 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded transition-colors"
                                                                onclick="return confirm('¿Cerrar este período?')">
                                                            <i class="fas fa-lock mr-1"></i>Cerrar
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="text-xs text-gray-400">
                                                        <i class="fas fa-check-circle mr-1"></i>Cerrado
                                                    </span>
                                                <?php endif; ?>
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

                    <!-- Historial de Pagos -->
                    <div class="section-card">
                        <div class="section-card-header">
                            <div class="section-card-header-icon bg-green-100 text-green-600">
                                <i class="fas fa-history"></i>
                            </div>
                            <h2>Historial de Pagos</h2>
                        </div>
                        <div class="section-card-body">
                            <div class="table-container scrollable-table">
                                <table class="compact-table">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Periodo</th>
                                            <th>Monto</th>
                                            <th>Concepto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($pagos)): ?>
                                            <?php foreach ($pagos as $pago): ?>
                                                <tr>
                                                    <td>
                                                        <div class="text-xs">
                                                            <div><?= date('d/m/Y', strtotime($pago['fecha_pago'])) ?></div>
                                                            <div class="text-gray-500"><?= date('H:i', strtotime($pago['fecha_pago'])) ?></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge-small badge-blue">
                                                            <i class="fas fa-hashtag"></i>
                                                            <?= htmlspecialchars($pago['numero_periodo']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="font-bold text-green-600">
                                                            $<?= number_format($pago['monto_pago'], 0, ',', '.') ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-xs text-gray-600">
                                                        <?= htmlspecialchars($pago['concepto'] ?? 'Pago diario') ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center py-6 text-gray-400">
                                                    <i class="fas fa-receipt text-2xl mb-2 block"></i>
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

            <!-- SIDEBAR (30%) -->
            <div class="sidebar-compact">

                <!-- Mes Actual -->
                <div class="sidebar-card">
                    <div class="sidebar-card-header">
                        <h3>
                            <i class="fas fa-calendar-check text-green-600"></i>
                            Mes Actual
                        </h3>
                    </div>
                    <div class="sidebar-card-body">
                        <div class="text-center mb-4">
                            <div class="text-2xl font-bold text-green-600">
                                $<?= number_format($pagosRealizadosMes ?? 0, 0, ',', '.') ?>
                            </div>
                            <div class="text-xs text-gray-600 mt-1">Pagado este mes</div>
                        </div>

                        <div class="mb-3">
                            <div class="flex justify-between text-xs font-medium text-gray-600 mb-1">
                                <span>Progreso</span>
                                <span><?= ($contrato['cuota_mensual'] ?? 0) > 0 ? min(100, round((($pagosRealizadosMes ?? 0) / ($contrato['cuota_mensual'] ?? 1)) * 100, 1)) : 0 ?>%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="progress-bar bg-gradient-to-r from-green-500 to-emerald-600 h-2 rounded-full"
                                     style="width: <?= ($contrato['cuota_mensual'] ?? 0) > 0 ? min(100, (($pagosRealizadosMes ?? 0) / ($contrato['cuota_mensual'] ?? 1)) * 100) : 0 ?>%"></div>
                            </div>
                        </div>

                        <?php 
                        $porcentajeMeta = ($contrato['cuota_mensual'] ?? 0) > 0 ? (($pagosRealizadosMes ?? 0) / ($contrato['cuota_mensual'] ?? 1)) * 100 : 0;
                        ?>

                        <?php if ($porcentajeMeta >= 100): ?>
                            <div class="text-xs bg-green-50 border border-green-200 rounded p-2 text-green-800 font-medium">
                                <i class="fas fa-check-circle mr-1"></i>¡Meta cumplida!
                            </div>
                        <?php elseif ($porcentajeMeta >= 75): ?>
                            <div class="text-xs bg-blue-50 border border-blue-200 rounded p-2 text-blue-800 font-medium">
                                <i class="fas fa-info-circle mr-1"></i>Faltan $<?= number_format(($contrato['cuota_mensual'] ?? 0) - ($pagosRealizadosMes ?? 0), 0, ',', '.') ?>
                            </div>
                        <?php else: ?>
                            <div class="text-xs bg-amber-50 border border-amber-200 rounded p-2 text-amber-800 font-medium">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Faltan $<?= number_format(($contrato['cuota_mensual'] ?? 0) - ($pagosRealizadosMes ?? 0), 0, ',', '.') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Cliente -->
                <?php if ($cliente): ?>
                <div class="sidebar-card">
                    <div class="sidebar-card-header">
                        <h3>
                            <i class="fas fa-user text-indigo-600"></i>
                            Cliente
                        </h3>
                    </div>
                    <div class="sidebar-card-body">
                        <div class="text-center mb-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-2 border-2 border-white shadow">
                                <i class="fas fa-user text-lg text-indigo-600"></i>
                            </div>
                            <div class="text-sm font-bold text-gray-900">
                                <?= htmlspecialchars($cliente['nombre_completo'] ?? 'N/A') ?>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="sidebar-info-item">
                                <span class="sidebar-info-label">
                                    <i class="fas fa-id-card text-xs"></i>
                                    ID
                                </span>
                                <span class="sidebar-info-value text-xs"><?= htmlspecialchars($cliente['identificacion'] ?? 'N/A') ?></span>
                            </div>

                            <div class="sidebar-info-item">
                                <span class="sidebar-info-label">
                                    <i class="fas fa-phone text-xs"></i>
                                    Tel
                                </span>
                                <a href="tel:<?= htmlspecialchars($cliente['telefono'] ?? '') ?>" class="sidebar-info-value text-xs text-indigo-600 hover:text-indigo-700">
                                    <?= htmlspecialchars($cliente['telefono'] ?? 'N/A') ?>
                                </a>
                            </div>

                            <div class="sidebar-info-item">
                                <span class="sidebar-info-label">
                                    <i class="fas fa-map-marker-alt text-xs"></i>
                                    Dirección
                                </span>
                                <span class="sidebar-info-value text-xs text-right"><?= htmlspecialchars($cliente['direccion'] ?? 'N/A') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Vehículo -->
                <?php if ($moto): ?>
                <div class="sidebar-card">
                    <div class="sidebar-card-header">
                        <h3>
                            <i class="fas fa-motorcycle text-indigo-600"></i>
                            Vehículo
                        </h3>
                    </div>
                    <div class="sidebar-card-body">
                        <div class="text-center mb-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-blue-100 rounded-full flex items-center justify-center mx-auto mb-2 border-2 border-white shadow">
                                <i class="fas fa-motorcycle text-lg text-indigo-600"></i>
                            </div>
                            <div class="text-sm font-bold text-gray-900">
                                <?= htmlspecialchars(($moto['marca'] ?? '') . ' ' . ($moto['modelo'] ?? '')) ?>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-certificate mr-1"></i>
                                <?= htmlspecialchars($moto['placa'] ?? 'N/A') ?>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="sidebar-info-item">
                                <span class="sidebar-info-label">
                                    <i class="fas fa-info-circle text-xs"></i>
                                    Estado
                                </span>
                                <span class="badge-small
                                    <?php
                                    switch($moto['estado']) {
                                        case 'activo':
                                            echo 'badge-green';
                                            break;
                                        case 'alquilada':
                                            echo 'badge-blue';
                                            break;
                                        case 'mantenimiento':
                                            echo 'badge-amber';
                                            break;
                                        default:
                                            echo 'badge-blue';
                                    }
                                    ?>">
                                    <i class="fas fa-circle"></i>
                                    <?= ucfirst(htmlspecialchars($moto['estado'] ?? 'desconocido')) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Acciones Rápidas -->
                <div class="sidebar-card">
                    <div class="sidebar-card-header">
                        <h3>
                            <i class="fas fa-bolt text-amber-600"></i>
                            Acciones
                        </h3>
                    </div>
                    <div class="sidebar-card-body">
                        <a href="<?= BASE_URL ?>pagos/contrato/<?= $contrato['id_contrato'] ?? '' ?>" class="quick-action-btn green">
                            <div class="flex items-center">
                                <div class="quick-action-icon bg-green-100 text-green-600">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <span>Pago</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>

                        <a href="<?= BASE_URL ?>contratos/edit/<?= $contrato['id_contrato'] ?? '' ?>" class="quick-action-btn blue">
                            <div class="flex items-center">
                                <div class="quick-action-icon bg-blue-100 text-blue-600">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <span>Editar</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>

                        <button onclick="window.print()" class="quick-action-btn purple">
                            <div class="flex items-center">
                                <div class="quick-action-icon bg-purple-100 text-purple-600">
                                    <i class="fas fa-print"></i>
                                </div>
                                <span>Imprimir</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
