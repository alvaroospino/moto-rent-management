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

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .summary-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
    }

    .summary-card h3 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .summary-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1f2937;
    }

    .summary-value.positive {
        color: #dc2626;
    }

    .loans-table {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
    }

    .table-header {
        background: #f9fafb;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: between;
        align-items: center;
    }

    .table-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #374151;
    }

    .add-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 0.5rem;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .add-button:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 1rem 1.5rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    th {
        background: #f9fafb;
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    tr:hover {
        background: #f9fafb;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-activo {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-pagado {
        background: #d1fae5;
        color: #047857;
    }

    .status-cancelado {
        background: #fee2e2;
        color: #dc2626;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        border: none;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-view {
        background: #e5e7eb;
        color: #374151;
    }

    .btn-view:hover {
        background: #d1d5db;
    }

    .btn-edit {
        background: #fef3c7;
        color: #92400e;
    }

    .btn-edit:hover {
        background: #fde68a;
    }

    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #6b7280;
    }

    .empty-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #374151;
    }

    .empty-description {
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .summary-cards {
            grid-template-columns: 1fr;
        }

        .table-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .add-button {
            justify-content: center;
        }

        th, td {
            padding: 0.75rem 1rem;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="page-container">
    <!-- Botón de Regreso -->
    <a href="<?= BASE_URL ?>contratos/detail/<?= $contrato['id_contrato'] ?>" class="back-button">
        <i class="fas fa-arrow-left"></i>
        <span>Volver al Contrato</span>
    </a>

    <!-- Header -->
    <div class="page-header">
        <div class="page-title">
            <i class="fas fa-hand-holding-usd text-blue-600"></i>
            Préstamos del Contrato
        </div>
        <div class="contract-badge">
            <i class="fas fa-file-contract"></i>
            Contrato #<?= $contrato['id_contrato'] ?>
        </div>
        <p class="text-gray-600 mt-2">
            Cliente: <strong><?= htmlspecialchars($cliente['nombre_completo']) ?></strong> |
            Moto: <strong><?= htmlspecialchars($moto['marca'] . ' ' . $moto['modelo']) ?></strong>
        </p>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <h3>Total Préstamos</h3>
            <div class="summary-value">$<?= number_format($totalPrestamos, 0, ',', '.') ?></div>
        </div>
        <div class="summary-card">
            <h3>Saldo Pendiente</h3>
            <div class="summary-value positive">$<?= number_format($totalSaldoPrestamos, 0, ',', '.') ?></div>
        </div>
        <div class="summary-card">
            <h3>Préstamos Activos</h3>
            <div class="summary-value"><?= count(array_filter($prestamos, fn($p) => $p['estado'] === 'activo')) ?></div>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="loans-table">
        <div class="table-header">
            <h2 class="table-title">Lista de Préstamos</h2>
            <a href="<?= BASE_URL ?>prestamos/create/<?= $contrato['id_contrato'] ?>" class="add-button">
                <i class="fas fa-plus"></i>
                <span>Nuevo Préstamo</span>
            </a>
        </div>

        <div class="table-container">
            <?php if (empty($prestamos)): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <h3 class="empty-title">No hay préstamos registrados</h3>
                    <p class="empty-description">Este contrato aún no tiene préstamos adicionales registrados.</p>
                    <a href="<?= BASE_URL ?>prestamos/create/<?= $contrato['id_contrato'] ?>" class="add-button">
                        <i class="fas fa-plus"></i>
                        <span>Crear Primer Préstamo</span>
                    </a>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Saldo Restante</th>
                            <th>Estado</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prestamos as $prestamo): ?>
                            <tr>
                                <td>#<?= $prestamo['id_prestamo'] ?></td>
                                <td><?= date('d/m/Y', strtotime($prestamo['fecha_prestamo'])) ?></td>
                                <td>$<?= number_format($prestamo['monto_prestamo'], 0, ',', '.') ?></td>
                                <td>$<?= number_format($prestamo['saldo_restante'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="status-badge status-<?= $prestamo['estado'] ?>">
                                        <i class="fas fa-circle"></i>
                                        <?= ucfirst($prestamo['estado']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($prestamo['descripcion'])): ?>
                                        <?= htmlspecialchars(substr($prestamo['descripcion'], 0, 50)) ?><?php if (strlen($prestamo['descripcion']) > 50): ?>...<?php endif; ?>
                                    <?php else: ?>
                                        <em class="text-gray-500">Sin descripción</em>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= BASE_URL ?>prestamos/show/<?= $prestamo['id_prestamo'] ?>" class="btn-action btn-view" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                            <span class="hidden sm:inline">Ver</span>
                                        </a>
                                        <?php if ($prestamo['estado'] === 'activo'): ?>
                                            <a href="<?= BASE_URL ?>prestamos/edit/<?= $prestamo['id_prestamo'] ?>" class="btn-action btn-edit" title="Editar">
                                                <i class="fas fa-edit"></i>
                                                <span class="hidden sm:inline">Editar</span>
                                            </a>
                                            <button onclick="confirmarEliminacion(<?= $prestamo['id_prestamo'] ?>)" class="btn-action btn-delete" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                                <span class="hidden sm:inline">Eliminar</span>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function confirmarEliminacion(idPrestamo) {
    if (confirm('¿Está seguro de que desea eliminar este préstamo? Esta acción no se puede deshacer.')) {
        window.location.href = '<?= BASE_URL ?>prestamos/delete/' + idPrestamo;
    }
}
</script>
