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

    .form-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .form-card {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
    }

    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }

    .form-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .form-subtitle {
        opacity: 0.9;
        font-size: 0.875rem;
    }

    .contract-info {
        background: #f8fafc;
        padding: 1rem;
        margin: 0 2rem 2rem;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
    }

    .contract-info h4 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .contract-detail {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .contract-detail:last-child {
        border-bottom: none;
    }

    .contract-label {
        font-weight: 600;
        color: #6b7280;
    }

    .contract-value {
        font-weight: 700;
        color: #374151;
    }

    .form-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-input, .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 0.5rem;
        font-size: 1rem;
        transition: all 0.2s ease;
        background: white;
    }

    .form-input:focus, .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .required {
        color: #dc2626;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
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

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .help-text {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    @media (max-width: 768px) {
        .form-header {
            padding: 1.5rem;
        }

        .form-title {
            font-size: 1.25rem;
        }

        .contract-info {
            margin: 0 1rem 1.5rem;
        }

        .contract-detail {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }

        .form-body {
            padding: 1.5rem;
        }

        .form-actions {
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

    <div class="form-container">
        <div class="form-card">
            <!-- Header -->
            <div class="form-header">
                <h1 class="form-title">
                    <i class="fas fa-plus-circle"></i>
                    Nuevo Préstamo
                </h1>
                <p class="form-subtitle">Registre un préstamo adicional para este contrato</p>
            </div>

            <!-- Contract Info -->
            <div class="contract-info">
                <h4>Información del Contrato</h4>
                <div class="contract-detail">
                    <span class="contract-label">Contrato:</span>
                    <span class="contract-value">#<?= $contrato['id_contrato'] ?></span>
                </div>
                <div class="contract-detail">
                    <span class="contract-label">Cliente:</span>
                    <span class="contract-value"><?= htmlspecialchars($cliente['nombre_completo']) ?></span>
                </div>
                <div class="contract-detail">
                    <span class="contract-label">Moto:</span>
                    <span class="contract-value"><?= htmlspecialchars($moto['marca'] . ' ' . $moto['modelo']) ?></span>
                </div>
                <div class="contract-detail">
                    <span class="contract-label">Valor Vehículo:</span>
                    <span class="contract-value">$<?= number_format($contrato['valor_vehiculo'], 0, ',', '.') ?></span>
                </div>
            </div>

            <!-- Form -->
            <form action="<?= BASE_URL ?>prestamos/store" method="POST" class="form-body">
                <input type="hidden" name="id_contrato" value="<?= $contrato['id_contrato'] ?>">

                <!-- Monto del Préstamo -->
                <div class="form-group">
                    <label for="monto_prestamo" class="form-label">
                        <i class="fas fa-money-bill-wave text-green-600"></i>
                        Monto del Préstamo <span class="required">*</span>
                    </label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #6b7280; font-weight: bold; font-size: 1.1rem;">$</span>
                        <input type="number" id="monto_prestamo" name="monto_prestamo" step="0.01" min="0.01"
                               class="form-input" style="padding-left: 2.5rem;" placeholder="0.00" required>
                    </div>
                    <p class="help-text">
                        <i class="fas fa-info-circle"></i>
                        Monto adicional que se suma a la deuda total del contrato
                    </p>
                </div>

                <!-- Fecha del Préstamo -->
                <div class="form-group">
                    <label for="fecha_prestamo" class="form-label">
                        <i class="fas fa-calendar-alt text-blue-600"></i>
                        Fecha del Préstamo <span class="required">*</span>
                    </label>
                    <input type="date" id="fecha_prestamo" name="fecha_prestamo"
                           value="<?= date('Y-m-d') ?>" class="form-input" required>
                    <p class="help-text">
                        <i class="fas fa-info-circle"></i>
                        Fecha en que se realizó el préstamo
                    </p>
                </div>

                <!-- Descripción -->
                <div class="form-group">
                    <label for="descripcion" class="form-label">
                        <i class="fas fa-comment text-purple-600"></i>
                        Descripción
                    </label>
                    <textarea id="descripcion" name="descripcion" class="form-textarea"
                              placeholder="Describa el motivo del préstamo (opcional)"></textarea>
                    <p class="help-text">
                        <i class="fas fa-info-circle"></i>
                        Información adicional sobre el préstamo (ej: reparaciones, daños, etc.)
                    </p>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <a href="<?= BASE_URL ?>prestamos/<?= $contrato['id_contrato'] ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Crear Préstamo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Desactivar botón al enviar el formulario para evitar duplicaciones
document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = document.querySelector('.btn-primary');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
});
</script>
