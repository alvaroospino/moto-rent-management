# TODO: Mejorar Diseño de Página de Clientes (index.php)

## Información Recopilada
- Página actual de clientes/index.php: Básica tabla con nombre, identificación, teléfono, dirección y acciones.
- Página de motos/index.php: Incluye KPIs, acciones rápidas, filtros, tabla mejorada con iconos, estados coloreados, estado vacío mejorado, responsivo.
- Modelo Cliente: Campos básicos (nombre_completo, identificacion, telefono, direccion). No hay campo de estado, pero se puede agregar lógica para KPIs basados en contratos.
- Controlador ClienteController: Maneja CRUD básico.

## Plan de Mejora
- Agregar KPIs específicos para clientes: Total Clientes, Clientes con Teléfono Registrado, Clientes con Dirección Registrada, Clientes con Contratos Activos (si es posible calcular).
- Agregar sección de Acciones Rápidas: Registrar Nuevo Cliente, Crear Nuevo Contrato.
- Agregar filtros de búsqueda: Por nombre completo o identificación.
- Mejorar la tabla: Agregar iconos de usuario, colores para estados (si aplicable), responsiva.
- Mejorar estado vacío: Mensaje específico con icono.
- Agregar JavaScript para filtros de búsqueda.
- Mantener diseño responsivo y consistente con motos/index.php.

## Pasos a Seguir
- [x] Actualizar app/views/clientes/index.php con KPIs, acciones rápidas, filtros, tabla mejorada y estado vacío.
- [x] Agregar JavaScript para funcionalidad de búsqueda.
- [x] Cambiar KPIs a métricas más útiles: Total Clientes, Con Contratos Activos (eliminado Sin Contratos).
- [x] Agregar métodos en Cliente.php para contar clientes con contratos activos.
- [ ] Verificar que el diseño sea responsivo y funcional.
- [ ] Probar la página para asegurar que funcione correctamente.

## Archivos Dependientes
- app/views/clientes/index.php (principal).
- Posiblemente app/controllers/ClienteController.php si se necesita lógica adicional para KPIs (ej. contar contratos por cliente).

## Pasos de Seguimiento
- Después de editar, probar la página localmente.
- Si se necesita lógica adicional en el controlador para KPIs, actualizar ClienteController.php.
