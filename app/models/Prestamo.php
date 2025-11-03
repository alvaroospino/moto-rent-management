<?php
// /app/models/Prestamo.php

require_once __DIR__ . '/BaseModel.php';

class Prestamo extends BaseModel {
    public function __construct() {
        parent::__construct('prestamos', 'id_prestamo');
    }

    protected $fillable = [
        'id_contrato',
        'id_usuario',
        'monto_prestamo',
        'fecha_prestamo',
        'descripcion',
        'estado',
        'saldo_restante'
    ];

    /**
     * Crear un nuevo préstamo
     */
    public function crearPrestamo(array $data) {
        // Validación básica
        if ($data['monto_prestamo'] <= 0) {
            throw new Exception("El monto del préstamo debe ser positivo.");
        }

        // Preparar datos
        $prestamoData = [
            'id_contrato' => $data['id_contrato'],
            'id_usuario' => $data['id_usuario'],
            'monto_prestamo' => $data['monto_prestamo'],
            'fecha_prestamo' => $data['fecha_prestamo'],
            'descripcion' => $data['descripcion'] ?? '',
            'estado' => 'activo',
            'saldo_restante' => $data['monto_prestamo'] // Inicialmente el saldo restante es el monto total
        ];

        return $this->create($prestamoData);
    }

    /**
     * Obtener préstamos por contrato
     */
    public static function getPrestamosPorContrato($idContrato) {
        $prestamo = new self();
        return $prestamo->where(['id_contrato' => $idContrato])->get();
    }

    /**
     * Obtener total de préstamos activos por contrato
     */
    public static function getTotalPrestamosActivos($idContrato) {
        $prestamo = new self();
        $sql = "SELECT SUM(monto_prestamo) as total FROM {$prestamo->table} WHERE id_contrato = :id_contrato AND estado = 'activo'";
        $stmt = $prestamo->db->prepare($sql);
        $stmt->execute([':id_contrato' => $idContrato]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (float)$result['total'] : 0.00;
    }

    /**
     * Obtener total de préstamos activos por contrato (saldo restante)
     */
    public static function getTotalSaldoPrestamos($idContrato) {
        $prestamo = new self();
        $sql = "SELECT SUM(saldo_restante) as total FROM {$prestamo->table} WHERE id_contrato = :id_contrato AND estado = 'activo'";
        $stmt = $prestamo->db->prepare($sql);
        $stmt->execute([':id_contrato' => $idContrato]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (float)$result['total'] : 0.00;
    }

    /**
     * Actualizar saldo restante del préstamo
     */
    public function actualizarSaldo($idPrestamo, $nuevoSaldo) {
        $this->update($idPrestamo, [
            'saldo_restante' => $nuevoSaldo,
            'actualizado_en' => date('Y-m-d H:i:s')
        ]);

        // Si el saldo llega a cero, marcar como pagado
        if ($nuevoSaldo <= 0) {
            $this->update($idPrestamo, ['estado' => 'pagado']);
        }
    }

    /**
     * Aplicar pago a préstamos de un contrato (lógica proporcional)
     */
    public static function aplicarPagoAPrestamos($idContrato, $montoPago) {
        $prestamos = self::getPrestamosPorContrato($idContrato);
        $montoRestante = $montoPago;

        foreach ($prestamos as $prestamo) {
            if ($prestamo['estado'] === 'activo' && $prestamo['saldo_restante'] > 0 && $montoRestante > 0) {
                $montoParaEstePrestamo = min($montoRestante, $prestamo['saldo_restante']);
                $nuevoSaldo = $prestamo['saldo_restante'] - $montoParaEstePrestamo;

                $prestamoModel = new self();
                $prestamoModel->actualizarSaldo($prestamo['id_prestamo'], $nuevoSaldo);

                $montoRestante -= $montoParaEstePrestamo;
            }
        }

        return $montoPago - $montoRestante; // Retorna cuánto se aplicó a préstamos
    }
}
