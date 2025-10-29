# Plan para Simplificar el Módulo de Contratos

## Información Recopilada
- El sistema actual es complejo con cálculos de amortización, intereses, periodos mensuales con días hábiles, penalizaciones, etc.
- El usuario quiere un sistema simple: valor de la moto, meses, cuota mensual fija, total a pagar = cuota * meses, acumulación de pagos diarios/mensuales sin impuestos ni penalizaciones.
- Archivos clave: Contrato.php, PeriodoContrato.php, PagoContrato.php, ContratoController.php, PagoController.php, vistas create.php y detail.php.

## Plan de Cambios
1. **Crear nuevas tablas SQL simplificadas**:
   - contratos: id, id_cliente, id_moto, fecha_inicio, valor_vehiculo, plazo_meses, cuota_mensual, saldo_restante, estado
   - periodos_contrato: id, id_contrato, numero_periodo, fecha_inicio, fecha_fin, cuota_acumulada, estado
   - pagos_contrato: id, id_contrato, id_periodo, id_usuario, fecha_pago, monto_pago, concepto

2. **Modificar Contrato.php**:
   - Simplificar createContrato: cuota mensual = valor_vehiculo / plazo_meses
   - Eliminar cálculos de intereses y amortización
   - Total a pagar = valor_vehiculo

3. **Modificar PeriodoContrato.php**:
   - Periodos mensuales simples (1 mes calendario)
   - Acumular pagos hasta completar cuota mensual

4. **Modificar PagoContrato.php**:
   - Registro simple de pagos sin tipos complejos

5. **Actualizar ContratoController.php**:
   - Cambiar store para cálculos simples
   - Actualizar detail para mostrar info simplificada

6. **Actualizar PagoController.php**:
   - Simplificar registro de pagos

7. **Modificar vistas**:
   - create.php: quitar campos de intereses, simplificar cálculos
   - detail.php: mostrar info básica, acumulación de pagos

## Dependencias
- Cambios en modelos afectan controladores y vistas
- Actualizar base de datos con nuevas tablas

## Pasos de Implementación
1. Crear script SQL para nuevas tablas simplificadas
2. Modificar Contrato.php para lógica simple
3. Actualizar PeriodoContrato.php
4. Cambiar PagoContrato.php
5. Actualizar controladores
6. Modificar vistas
7. Probar el sistema

## Seguimiento
- [x] Crear script SQL nuevas tablas
- [x] Modificar Contrato.php
- [x] Actualizar PeriodoContrato.php
- [x] Cambiar PagoContrato.php
- [x] Actualizar ContratoController.php
- [x] Actualizar PagoController.php
- [x] Modificar vistas create.php y detail.php
- [ ] Probar sistema
