<style>
    /* Mobile-first design */
    .page-container {
        background: #f8fafc;
        min-height: 100vh;
        padding: 1rem;
    }

    .page-header {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .contract-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 1rem;
        background: #dbeafe;
        color: #1d4ed8;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: #f3f4f6;
        color: #374151;
        border: none;
        border-radius: 0.75rem;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }

    .back-button:hover {
        background: #e5e7eb;
        transform: translateY(-1px);
    }

    .financial-summary {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 1rem;
        padding: 2rem 1rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
    }

    .financial-summary h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .summary-item {
        text-align: center;
        background: rgba(255,255,255,0.9);
        border-radius: 0.75rem;
        border: 1px solid rgba(252, 211, 77, 0.3);
    }

    .profit-summary {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border-radius: 1rem;
        padding: 2rem 1rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #a7f3d0;
    }

    .profit-summary h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #065f46;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .profit-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .profit-item {
        text-align: center;
        background: rgba(255,255,255,0.9);
        border-radius: 0.75rem;
        border: 1px solid #6ee7b7;
    }

    .profit-value {
        font-size: 1.125rem;
        font-weight: 800;
        color: #065f46;
        display: block;
        margin-bottom: 0.25rem;
    }

    .profit-label {
        font-size: 0.75rem;
        color: #065f46;
        opacity: 0.8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .summary-value {
        font-size: 1.125rem;
        font-weight: 800;
        color: #92400e;
        display: block;
        margin-bottom: 0.25rem;
    }

    .summary-label {
        font-size: 0.75rem;
        color: #92400e;
        opacity: 0.8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .details-section {
        background: white;
        border-radius: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .section-header {
        padding: 1.25rem 1.5rem;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background-color 0.2s ease;
    }

    .section-header:hover {
        background: #f3f4f6;
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }

    .section-icon {
        width: 32px;
        height: 32px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .section-toggle {
        color: #6b7280;
        font-size: 1.125rem;
        transition: transform 0.3s ease;
    }

    .details-section[open] .section-toggle {
        transform: rotate(180deg);
    }

    .section-content {
        padding: 1.5rem;
        animation: slideDown 0.3s ease-out;
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
    }

    .info-label {
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
    }

    .info-value {
        font-weight: 600;
        color: #1f2937;
        font-size: 0.875rem;
        text-align: right;
        flex: 1;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 0.5rem;
        text-transform: capitalize;
    }

    .status-activo {
        background: #dcfce7;
        color: #166534;
    }

    .status-finalizado {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-alquilada {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-mantenimiento {
        background: #fef3c7;
        color: #92400e;
    }

    .observations-section {
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }

    .observations-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .observations-icon {
        width: 24px;
        height: 24px;
        background: #fef3c7;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #92400e;
    }

    .observations-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: 0;
    }

    .observations-content {
        background: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: 0.5rem;
        padding: 1rem;
        color: #92400e;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            max-height: 1000px;
            transform: translateY(0);
        }
    }

    /* Desktop adjustments */
    @media (min-width: 768px) {
        .page-container {
            padding: 2rem;
        }

        .page-header {
            padding: 2rem;
        }

        .page-title {
            font-size: 2rem;
        }

        .summary-grid {
            grid-template-columns: repeat(4, 1fr);
        }

        .summary-item {
            padding: 1.5rem;
        }

        .summary-value {
            font-size: 1.5rem;
        }

        .section-content {
            padding: 2rem;
        }

        .info-list {
            gap: 1.25rem;
        }

        .info-item {
            padding: 1.25rem;
        }
    }

    @media (min-width: 1024px) {
        .summary-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }
    }
</style>

<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-file-contract"></i>
            Contrato #<?= htmlspecialchars($contrato['id_contrato'] ?? 'N/A') ?>
        </h1>
        <div class="contract-badge">
            <i class="fas fa-circle"></i>
            <?= ucfirst(htmlspecialchars($contrato['estado'] ?? 'Desconocido')) ?>
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex justify-between items-center mb-4">
        <a href="<?= BASE_URL ?>contratos" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Volver
        </a>
        <?php if ($contrato['estado'] === 'activo'): ?>
        <a href="<?= BASE_URL ?>contratos/edit/<?= $contrato['id_contrato'] ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-300 shadow-lg hover:shadow-xl">
            <i class="fas fa-edit mr-2"></i> Editar Contrato
        </a>
        <?php endif; ?>
    </div>

    <!-- Financial Summary -->
    <div class="financial-summary">
        <h3>
            <i class="fas fa-chart-line"></i>
            Resumen Financiero
        </h3>
        <div class="summary-grid">
            <div class="summary-item" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-color: #3b82f6;">
                <span class="summary-value" style="color: #1e40af;">$<?= number_format($contrato['valor_vehiculo'] ?? 0, 0, ',', '.') ?></span>
                <span class="summary-label" style="color: #1e40af;">Valor del Vehículo</span>
            </div>
            <div class="summary-item" style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-color: #10b981;">
                <span class="summary-value" style="color: #047857;">$<?= number_format($contrato['cuota_mensual'] ?? 0, 0, ',', '.') ?></span>
                <span class="summary-label" style="color: #047857;">Cuota Mensual</span>
            </div>
            <div class="summary-item" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-color: #f59e0b;">
                <span class="summary-value" style="color: #92400e;">$<?= number_format($contrato['saldo_restante'] ?? 0, 0, ',', '.') ?></span>
                <span class="summary-label" style="color: #92400e;">Capital Pagado</span>
            </div>
            <div class="summary-item" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-color: #ef4444;">
                <span class="summary-value" style="color: #dc2626;">$<?= number_format(($contrato['valor_vehiculo'] ?? 0) - ($contrato['saldo_restante'] ?? 0), 0, ',', '.') ?></span>
                <span class="summary-label" style="color: #dc2626;">Capital Pendiente</span>
            </div>
            <div class="summary-item" style="background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); border-color: #8b5cf6;">
                <span class="summary-value" style="color: #6d28d9;">$<?= number_format($total_a_pagar ?? 0, 0, ',', '.') ?></span>
                <span class="summary-label" style="color: #6d28d9;">Total a Pagar Contrato</span>
            </div>
            <div class="summary-item" style="background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%); border-color: #ec4899;">
                <span class="summary-value" style="color: #be185d;">$<?= number_format($total_prestamos ?? 0, 0, ',', '.') ?></span>
                <span class="summary-label" style="color: #be185d;">Total Préstamos</span>
            </div>
            <div class="summary-item" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-color: #0284c7;">
                <span class="summary-value" style="color: #0369a1;">$<?= number_format(($total_a_pagar ?? 0) + ($total_prestamos ?? 0), 0, ',', '.') ?></span>
                <span class="summary-label" style="color: #0369a1;">Total de Deudas</span>
            </div>
            <div class="summary-item" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-color: #f87171;">
                <span class="summary-value" style="color: #dc2626;">$<?= number_format($saldo_restante_deuda_total ?? 0, 0, ',', '.') ?></span>
                <span class="summary-label" style="color: #dc2626;">Saldo Restante Deuda Total</span>
            </div>
        </div>
    </div>

    <!-- Profit Summary -->
    <?php if (isset($utilidad) && is_array($utilidad)): ?>
    <div class="profit-summary">
        <h3>
            <i class="fas fa-chart-pie"></i>
            Utilidad del Contrato
        </h3>
        <div class="profit-grid">
            <div class="profit-item" style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-color: #10b981;">
                <span class="profit-value" style="color: #047857;">$<?= number_format($utilidad['ingresos_totales'] ?? 0, 0, ',', '.') ?></span>
                <span class="profit-label" style="color: #047857;">Ingresos Totales</span>
            </div>
            <div class="profit-item" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-color: #f59e0b;">
                <span class="profit-value" style="color: #92400e;">$<?= number_format($utilidad['gastos_totales'] ?? 0, 0, ',', '.') ?></span>
                <span class="profit-label" style="color: #92400e;">Gastos Totales</span>
            </div>
            <div class="profit-item" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-color: #3b82f6;">
                <span class="profit-value" style="color: #1e40af;">$<?= number_format($utilidad['utilidad_neta'] ?? 0, 0, ',', '.') ?></span>
                <span class="profit-label" style="color: #1e40af;">Utilidad Neta</span>
            </div>
            <div class="profit-item" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-color: #ef4444;">
                <span class="profit-value" style="color: #dc2626;"><?= number_format($utilidad['rentabilidad'] ?? 0, 2, ',', '.') ?>%</span>
                <span class="profit-label" style="color: #dc2626;">Rentabilidad</span>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Client Information -->
    <?php if ($cliente): ?>
    <details class="details-section">
        <summary class="section-header">
            <h2 class="section-title">
                <div class="section-icon bg-blue-100 text-blue-600">
                    <i class="fas fa-user"></i>
                </div>
                Información del Cliente
            </h2>
            <i class="fas fa-chevron-down section-toggle"></i>
        </summary>
        <div class="section-content">
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-id-card text-blue-500"></i>
                        Nombre Completo
                    </span>
                    <span class="info-value"><?= htmlspecialchars($cliente['nombre_completo'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-address-card text-blue-500"></i>
                        Identificación
                    </span>
                    <span class="info-value"><?= htmlspecialchars($cliente['identificacion'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-phone text-blue-500"></i>
                        Teléfono
                    </span>
                    <span class="info-value">
                        <a href="tel:<?= htmlspecialchars($cliente['telefono'] ?? '') ?>" class="text-blue-600">
                            <?= htmlspecialchars($cliente['telefono'] ?? 'N/A') ?>
                        </a>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-envelope text-blue-500"></i>
                        Email
                    </span>
                    <span class="info-value">
                        <?php if (!empty($cliente['email'])): ?>
                            <a href="mailto:<?= htmlspecialchars($cliente['email']) ?>" class="text-blue-600">
                                <?= htmlspecialchars($cliente['email']) ?>
                            </a>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-map-marker-alt text-blue-500"></i>
                        Dirección
                    </span>
                    <span class="info-value"><?= htmlspecialchars($cliente['direccion'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-calendar text-blue-500"></i>
                        Fecha de Registro
                    </span>
                    <span class="info-value">
                        <?php if (!empty($cliente['fecha_registro'])): ?>
                            <?= date('d/m/Y', strtotime($cliente['fecha_registro'])) ?>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
    </details>
    <?php endif; ?>

    <!-- Vehicle Information -->
    <?php if ($moto): ?>
    <details class="details-section">
        <summary class="section-header">
            <h2 class="section-title">
                <div class="section-icon bg-green-100 text-green-600">
                    <i class="fas fa-motorcycle"></i>
                </div>
                Información del Vehículo
            </h2>
            <i class="fas fa-chevron-down section-toggle"></i>
        </summary>
        <div class="section-content">
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-tag text-green-500"></i>
                        Marca y Modelo
                    </span>
                    <span class="info-value"><?= htmlspecialchars(($moto['marca'] ?? '') . ' ' . ($moto['modelo'] ?? '')) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-certificate text-green-500"></i>
                        Placa
                    </span>
                    <span class="info-value"><?= htmlspecialchars($moto['placa'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-palette text-green-500"></i>
                        Color
                    </span>
                    <span class="info-value"><?= htmlspecialchars($moto['color'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-cogs text-green-500"></i>
                        Estado
                    </span>
                    <span class="info-value">
                        <span class="status-badge
                            <?php
                            switch($moto['estado']) {
                                case 'activo':
                                    echo 'status-activo';
                                    break;
                                case 'alquilada':
                                    echo 'status-alquilada';
                                    break;
                                case 'mantenimiento':
                                    echo 'status-mantenimiento';
                                    break;
                                default:
                                    echo 'bg-gray-100 text-gray-800';
                            }
                            ?>">
                            <i class="fas fa-circle"></i>
                            <?= ucfirst(htmlspecialchars($moto['estado'] ?? 'desconocido')) ?>
                        </span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-calendar text-green-500"></i>
                        Año
                    </span>
                    <span class="info-value"><?= htmlspecialchars($moto['anio'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-file-alt text-green-500"></i>
                        Descripción
                    </span>
                    <span class="info-value">
                        <?php if (!empty($moto['descripcion'])): ?>
                            <?= htmlspecialchars($moto['descripcion']) ?>
                        <?php else: ?>
                            Sin descripción
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
    </details>
    <?php endif; ?>

    <!-- Contract Details -->
    <details class="details-section">
        <summary class="section-header">
            <h2 class="section-title">
                <div class="section-icon bg-purple-100 text-purple-600">
                    <i class="fas fa-file-contract"></i>
                </div>
                Detalles del Contrato
            </h2>
            <i class="fas fa-chevron-down section-toggle"></i>
        </summary>
        <div class="section-content">
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-hashtag text-purple-500"></i>
                        ID Contrato
                    </span>
                    <span class="info-value">#<?= htmlspecialchars($contrato['id_contrato'] ?? 'N/A') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-play-circle text-purple-500"></i>
                        Fecha de Inicio
                    </span>
                    <span class="info-value">
                        <?php if (!empty($contrato['fecha_inicio'])): ?>
                            <?= date('d/m/Y', strtotime($contrato['fecha_inicio'])) ?>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-clock text-purple-500"></i>
                        Plazo Total
                    </span>
                    <span class="info-value"><?= $contrato['plazo_meses'] ?? 0 ?> meses</span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-check-circle text-purple-500"></i>
                        Estado
                    </span>
                    <span class="info-value">
                        <span class="status-badge
                            <?php
                            switch($contrato['estado'] ?? 'desconocido') {
                                case 'activo':
                                    echo 'status-activo';
                                    break;
                                case 'finalizado':
                                    echo 'status-finalizado';
                                    break;
                                default:
                                    echo 'bg-gray-100 text-gray-800';
                            }
                            ?>">
                            <i class="fas fa-circle"></i>
                            <?= ucfirst(htmlspecialchars($contrato['estado'] ?? 'Desconocido')) ?>
                        </span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-money-bill-wave text-purple-500"></i>
                        Cuota Mensual
                    </span>
                    <span class="info-value">$<?= number_format($contrato['cuota_mensual'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-piggy-bank text-purple-500"></i>
                        Abono Capital Mensual
                    </span>
                    <span class="info-value">$<?= number_format($contrato['abono_capital_mensual'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-chart-line text-purple-500"></i>
                        Ganancia Mensual
                    </span>
                    <span class="info-value">$<?= number_format($contrato['ganancia_mensual'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-dollar-sign text-purple-500"></i>
                        Valor del Vehículo
                    </span>
                    <span class="info-value">$<?= number_format($contrato['valor_vehiculo'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-wallet text-purple-500"></i>
                        Capital Pagado
                    </span>
                    <span class="info-value">$<?= number_format($contrato['saldo_restante'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-exclamation-triangle text-purple-500"></i>
                        Capital Pendiente
                    </span>
                    <span class="info-value">$<?= number_format(($contrato['valor_vehiculo'] ?? 0) - ($contrato['saldo_restante'] ?? 0), 0, ',', '.') ?></span>
                </div>
            </div>

            <!-- Observations -->
            <?php if (!empty($contrato['observaciones'])): ?>
            <div class="observations-section">
                <div class="observations-header">
                    <div class="observations-icon">
                        <i class="fas fa-sticky-note"></i>
                    </div>
                    <h4 class="observations-title">Observaciones</h4>
                </div>
                <div class="observations-content">
                    <?= nl2br(htmlspecialchars($contrato['observaciones'])) ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </details>
</div>
