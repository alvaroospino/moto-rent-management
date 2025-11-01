# Mejora al Modal de Periodos de Contratos

## Tareas Pendientes

### 1. Actualizar Controlador (ContratoController.php)
- [ ] Modificar el método `detail` para incluir pagos por periodo en `$historialPeriodos`
- [ ] Agregar consulta para obtener pagos de cada periodo usando `PagoContrato::getPagosPorContrato` filtrados por periodo

### 2. Modificar Vista (app/views/contratos/detail.php)
- [ ] Cambiar el modal de historial para mostrar tarjetas en lugar de lista
- [ ] Cada tarjeta mostrará: número de periodo, fechas, estado, cuota acumulada
- [ ] Agregar funcionalidad de expand/collapse con JavaScript
- [ ] En el detalle expandido mostrar:
  - Lista de pagos realizados en el periodo
  - Días marcados como no_pago
  - Tabla de días con estados

### 3. Agregar JavaScript (contratos.js)
- [ ] Función para expandir/colapsar tarjetas
- [ ] Animaciones suaves para la transición

### 4. Pruebas
- [ ] Verificar que el modal se carga correctamente
- [ ] Probar expand/collapse de tarjetas
- [ ] Confirmar que se muestran pagos y días no_pago
