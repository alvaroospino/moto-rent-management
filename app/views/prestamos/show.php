<style>
    /* Mobile-first design */
    .page-container {
        background: #f8fafc;
        min-height: 100vh;
        padding: 1rem;
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

    .detail-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .detail-card {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-body {
        padding: 2rem;
    }

    .info-section {
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    .info-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .info-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: #374151;
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

    .description-section {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    .description-title {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .description-text {
        color: #6b7280;
        line-height: 1.6;
    }

    .actions-section {
        background: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
        transform: translateY(-1px);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-danger {
        background: #dc2626;
        color: white;
    }

    .btn-danger:hover {
        background: #b91c1c;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .actions-section {
            flex-direction: column;
        }

        .btn {
            justify-content: center;
        }
    }
</style>

<div class="page-container">
    <!-- Botón de Regreso -->
    <a href="<?= BASE_URL ?>prestamos/<?= $contrato['id_contrato'] ?>" class="back-button">
        <i class="fas fa-arrow-left"></i>
        <span>Volver a Préstamos</span>
    </a>

    <div class="detail-container">
        <!-- Información del Préstamo -->
        <div class="detail-card">
            <div class="card-header">
                <h1 class="card-title">
                    <i class="fas fa-hand-holding-usd"></i>
                    Detalles del Préstamo
                </h1>
                <div class="status-badge status-<?= $prestamo['estado'] ?>">
                    <i class="fas fa-circle"></i>
                    <?= ucfirst($prestamo['estado']) ?>
                </div>
            </div>
            <div class="card-body">
                <!-- Información Principal -->
                <div class="info-section">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle text-blue-600"></i>
                        Información del Préstamo
                    </h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">ID Préstamo</div>
                            <div class="info-value">#<?= $prestamo['id_prestamo'] ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Monto del Préstamo</div>
                            <div class="info-value">$<?= number_format($prestamo['monto_prestamo'], 0, ',', '.') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Saldo Restante</div>
                            <div class="info-value">$<?= number_format($prestamo['saldo_restante'], 0, ',', '.') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Fecha del Préstamo</div>
                            <div class="info-value"><?= date('d/m/Y', strtotime($prestamo['fecha_prestamo'])) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Información del Contrato -->
                <div class="info-section">
                    <h2 class="section-title">
                        <i class="fas fa-file-contract text-green-600"></i>
                        Información del Contrato
                    </h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">ID Contrato</div>
                            <div class="info-value">#<?= $contrato['id_contrato'] ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Cliente</div>
                            <div class="info-value"><?= htmlspecialchars($cliente['nombre_completo']) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Moto</div>
                            <div class="info-value"><?= htmlspecialchars($moto['marca'] . ' ' . $moto['modelo']) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Placa</div>
                            <div class="info-value"><?= htmlspecialchars($moto['placa']) ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Valor del Vehículo</div>
                            <div class="info-value">$<?= number_format($contrato['valor_vehiculo'], 0, ',', '.') ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Cuota Mensual</div>
                            <div class="info-value">$<?= number_format($contrato['cuota_mensual'], 0, ',', '.') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Descripción -->
                <?php if (!empty($prestamo['descripcion'])): ?>
                <div class="info-section">
                    <div class="description-section">
                        <h3 class="description-title">
                            <i class="fas fa-comment text-purple-600"></i>
                            Descripción
                        </h3>
                        <p class="description-text">
                            <?= nl2br(htmlspecialchars($prestamo['descripcion'])) ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Acciones -->
                <div class="actions-section">
                    <a href="<?= BASE_URL ?>prestamos/edit/<?= $prestamo['id_prestamo'] ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i>
                        Editar Préstamo
                    </a>
                    <?php if ($prestamo['saldo_restante'] == $prestamo['monto_prestamo']): ?>
                    <button onclick="confirmarEliminacion(<?= $prestamo['id_prestamo'] ?>)" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        Eliminar Préstamo
                    </button>
                    <?php endif; ?>
                </div>
            </div>
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
