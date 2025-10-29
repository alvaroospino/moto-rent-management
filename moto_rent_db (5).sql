-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-10-2025 a las 20:53:21
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `moto_rent_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(10) UNSIGNED NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `identificacion` varchar(50) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Información básica de los clientes.';

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre_completo`, `identificacion`, `telefono`, `direccion`, `creado_en`) VALUES
(1, 'juan perez', '1234567789', '3244060909', 'en mi casa', '2025-10-27 21:05:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratos`
--

CREATE TABLE `contratos` (
  `id_contrato` int(10) UNSIGNED NOT NULL,
  `id_cliente` int(10) UNSIGNED NOT NULL,
  `id_moto` int(10) UNSIGNED NOT NULL,
  `fecha_inicio` date NOT NULL,
  `valor_vehiculo` decimal(12,2) NOT NULL COMMENT 'Valor total del vehículo',
  `plazo_meses` int(11) NOT NULL COMMENT 'Plazo en meses',
  `cuota_mensual` decimal(10,2) NOT NULL COMMENT 'Cuota mensual fija = valor_vehiculo / plazo_meses',
  `saldo_restante` decimal(12,2) NOT NULL COMMENT 'Saldo pendiente por pagar',
  `estado` enum('activo','finalizado','cancelado') NOT NULL DEFAULT 'activo',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `abono_capital_mensual` decimal(10,2) NOT NULL COMMENT 'Abono mensual',
  `ganancia_mensual` decimal(10,2) NOT NULL COMMENT 'Ganancia mensual del contrato'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contratos`
--

INSERT INTO `contratos` (`id_contrato`, `id_cliente`, `id_moto`, `fecha_inicio`, `valor_vehiculo`, `plazo_meses`, `cuota_mensual`, `saldo_restante`, `estado`, `creado_en`, `abono_capital_mensual`, `ganancia_mensual`) VALUES
(2, 1, 3, '2025-10-29', 5500000.00, 18, 650000.00, 300000.00, 'activo', '2025-10-29 04:10:52', 300000.00, 350000.00),
(4, 1, 3, '2025-10-29', 5500000.00, 18, 600000.00, 0.00, 'activo', '2025-10-29 04:47:57', 200000.00, 400000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos_empresa`
--

CREATE TABLE `gastos_empresa` (
  `id_gasto` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `id_moto` int(10) UNSIGNED DEFAULT NULL,
  `fecha_gasto` date NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `categoria` enum('mantenimiento','operativo','general','impuestos') NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Gastos de la operación y mantenimiento de las motos. Clave para el cálculo de utilidad.';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historico_estado_moto`
--

CREATE TABLE `historico_estado_moto` (
  `id_historico` int(10) UNSIGNED NOT NULL,
  `id_moto` int(10) UNSIGNED NOT NULL,
  `estado_anterior` varchar(50) NOT NULL,
  `estado_nuevo` varchar(50) NOT NULL,
  `fecha_cambio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motos`
--

CREATE TABLE `motos` (
  `id_moto` int(10) UNSIGNED NOT NULL,
  `placa` varchar(10) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `valor_adquisicion` decimal(12,2) NOT NULL,
  `estado` enum('activo','alquilada','mantenimiento','vendida','baja') NOT NULL DEFAULT 'activo',
  `fecha_adquisicion` date NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Inventario de motocicletas y su inversión.';

--
-- Volcado de datos para la tabla `motos`
--

INSERT INTO `motos` (`id_moto`, `placa`, `marca`, `modelo`, `valor_adquisicion`, `estado`, `fecha_adquisicion`, `creado_en`, `actualizado_en`) VALUES
(1, 'shn19b', 'yamha', '2017', 5500000.00, 'alquilada', '2025-10-27', '2025-10-27 20:54:00', '2025-10-29 04:31:29'),
(3, 'aty12b', 'yamha', '2017', 5500000.00, 'alquilada', '2025-10-27', '2025-10-28 02:36:10', '2025-10-29 04:47:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_contrato`
--

CREATE TABLE `pagos_contrato` (
  `id_pago` int(10) UNSIGNED NOT NULL,
  `id_contrato` int(10) UNSIGNED NOT NULL,
  `id_periodo` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `fecha_pago` date NOT NULL,
  `monto_pago` decimal(10,2) NOT NULL,
  `concepto` varchar(255) DEFAULT '',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos_contrato`
--

INSERT INTO `pagos_contrato` (`id_pago`, `id_contrato`, `id_periodo`, `id_usuario`, `fecha_pago`, `monto_pago`, `concepto`, `creado_en`) VALUES
(5, 2, 19, 1, '2025-10-29', 50000.00, 'Pago del cliente.', '2025-10-29 04:14:57'),
(6, 2, 19, 1, '2025-10-29', 600000.00, 'Pago del cliente.', '2025-10-29 04:15:45'),
(7, 2, 20, 1, '2025-10-29', 25000.00, 'Pago del cliente.', '2025-10-29 17:57:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos_contrato`
--

CREATE TABLE `periodos_contrato` (
  `id_periodo` int(10) UNSIGNED NOT NULL,
  `id_contrato` int(10) UNSIGNED NOT NULL,
  `numero_periodo` int(11) NOT NULL COMMENT 'Número del periodo (1 a plazo_meses)',
  `fecha_inicio_periodo` date NOT NULL,
  `fecha_fin_periodo` date NOT NULL,
  `cuota_acumulada` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Suma de pagos en el periodo',
  `estado_periodo` enum('abierto','cerrado') NOT NULL DEFAULT 'abierto',
  `cerrado_en` timestamp NULL DEFAULT NULL,
  `pago_anticipado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `periodos_contrato`
--

INSERT INTO `periodos_contrato` (`id_periodo`, `id_contrato`, `numero_periodo`, `fecha_inicio_periodo`, `fecha_fin_periodo`, `cuota_acumulada`, `estado_periodo`, `cerrado_en`, `pago_anticipado`) VALUES
(19, 2, 1, '2025-10-29', '2025-11-28', 649999.99, 'cerrado', '2025-10-29 10:21:05', 1),
(20, 2, 2, '2025-11-29', '2025-12-28', 25000.00, 'abierto', NULL, 0),
(21, 2, 3, '2025-12-29', '2026-01-28', 0.00, 'abierto', NULL, 0),
(22, 2, 4, '2026-01-29', '2026-02-28', 0.00, 'abierto', NULL, 0),
(23, 2, 5, '2026-03-01', '2026-03-31', 0.00, 'abierto', NULL, 0),
(24, 2, 6, '2026-04-01', '2026-04-30', 0.00, 'abierto', NULL, 0),
(25, 2, 7, '2026-05-01', '2026-05-31', 0.00, 'abierto', NULL, 0),
(26, 2, 8, '2026-06-01', '2026-06-30', 0.00, 'abierto', NULL, 0),
(27, 2, 9, '2026-07-01', '2026-07-31', 0.00, 'abierto', NULL, 0),
(28, 2, 10, '2026-08-01', '2026-08-31', 0.00, 'abierto', NULL, 0),
(29, 2, 11, '2026-09-01', '2026-09-30', 0.00, 'abierto', NULL, 0),
(30, 2, 12, '2026-10-01', '2026-10-31', 0.00, 'abierto', NULL, 0),
(31, 2, 13, '2026-11-01', '2026-11-30', 0.00, 'abierto', NULL, 0),
(32, 2, 14, '2026-12-01', '2026-12-31', 0.00, 'abierto', NULL, 0),
(33, 2, 15, '2027-01-01', '2027-01-31', 0.00, 'abierto', NULL, 0),
(34, 2, 16, '2027-02-01', '2027-02-28', 0.00, 'abierto', NULL, 0),
(35, 2, 17, '2027-03-01', '2027-03-31', 0.00, 'abierto', NULL, 0),
(36, 2, 18, '2027-04-01', '2027-04-30', 0.00, 'abierto', NULL, 0),
(55, 4, 1, '2025-10-29', '2025-11-28', 0.00, 'abierto', NULL, 0),
(56, 4, 2, '2025-11-29', '2025-12-28', 0.00, 'abierto', NULL, 0),
(57, 4, 3, '2025-12-29', '2026-01-28', 0.00, 'abierto', NULL, 0),
(58, 4, 4, '2026-01-29', '2026-02-28', 0.00, 'abierto', NULL, 0),
(59, 4, 5, '2026-03-01', '2026-03-31', 0.00, 'abierto', NULL, 0),
(60, 4, 6, '2026-04-01', '2026-04-30', 0.00, 'abierto', NULL, 0),
(61, 4, 7, '2026-05-01', '2026-05-31', 0.00, 'abierto', NULL, 0),
(62, 4, 8, '2026-06-01', '2026-06-30', 0.00, 'abierto', NULL, 0),
(63, 4, 9, '2026-07-01', '2026-07-31', 0.00, 'abierto', NULL, 0),
(64, 4, 10, '2026-08-01', '2026-08-31', 0.00, 'abierto', NULL, 0),
(65, 4, 11, '2026-09-01', '2026-09-30', 0.00, 'abierto', NULL, 0),
(66, 4, 12, '2026-10-01', '2026-10-31', 0.00, 'abierto', NULL, 0),
(67, 4, 13, '2026-11-01', '2026-11-30', 0.00, 'abierto', NULL, 0),
(68, 4, 14, '2026-12-01', '2026-12-31', 0.00, 'abierto', NULL, 0),
(69, 4, 15, '2027-01-01', '2027-01-31', 0.00, 'abierto', NULL, 0),
(70, 4, 16, '2027-02-01', '2027-02-28', 0.00, 'abierto', NULL, 0),
(71, 4, 17, '2027-03-01', '2027-03-31', 0.00, 'abierto', NULL, 0),
(72, 4, 18, '2027-04-01', '2027-04-30', 0.00, 'abierto', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol` enum('administrador','operador','contador') NOT NULL DEFAULT 'operador',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Usuarios del sistema con roles y permisos.';

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `email`, `password_hash`, `rol`, `activo`, `creado_en`) VALUES
(1, 'alvaro', 'alvaro@gmail.com', '$2y$10$yFL2DIrWL6Rw/4AtQdgXCuPp40Kz9cfzaxFfnsu9y/uOBv9em9mxC', 'administrador', 1, '2025-10-27 20:28:26');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `identificacion` (`identificacion`);

--
-- Indices de la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`id_contrato`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_moto` (`id_moto`);

--
-- Indices de la tabla `gastos_empresa`
--
ALTER TABLE `gastos_empresa`
  ADD PRIMARY KEY (`id_gasto`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_moto` (`id_moto`);

--
-- Indices de la tabla `historico_estado_moto`
--
ALTER TABLE `historico_estado_moto`
  ADD PRIMARY KEY (`id_historico`),
  ADD KEY `id_moto` (`id_moto`);

--
-- Indices de la tabla `motos`
--
ALTER TABLE `motos`
  ADD PRIMARY KEY (`id_moto`),
  ADD UNIQUE KEY `placa` (`placa`);

--
-- Indices de la tabla `pagos_contrato`
--
ALTER TABLE `pagos_contrato`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `idx_contrato_fecha` (`id_contrato`,`fecha_pago`),
  ADD KEY `idx_periodo_fecha` (`id_periodo`,`fecha_pago`);

--
-- Indices de la tabla `periodos_contrato`
--
ALTER TABLE `periodos_contrato`
  ADD PRIMARY KEY (`id_periodo`),
  ADD KEY `idx_contrato_periodo` (`id_contrato`,`numero_periodo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `contratos`
--
ALTER TABLE `contratos`
  MODIFY `id_contrato` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `gastos_empresa`
--
ALTER TABLE `gastos_empresa`
  MODIFY `id_gasto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `historico_estado_moto`
--
ALTER TABLE `historico_estado_moto`
  MODIFY `id_historico` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `motos`
--
ALTER TABLE `motos`
  MODIFY `id_moto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pagos_contrato`
--
ALTER TABLE `pagos_contrato`
  MODIFY `id_pago` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `periodos_contrato`
--
ALTER TABLE `periodos_contrato`
  MODIFY `id_periodo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD CONSTRAINT `contratos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `contratos_ibfk_2` FOREIGN KEY (`id_moto`) REFERENCES `motos` (`id_moto`);

--
-- Filtros para la tabla `gastos_empresa`
--
ALTER TABLE `gastos_empresa`
  ADD CONSTRAINT `gastos_empresa_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `gastos_empresa_ibfk_2` FOREIGN KEY (`id_moto`) REFERENCES `motos` (`id_moto`);

--
-- Filtros para la tabla `historico_estado_moto`
--
ALTER TABLE `historico_estado_moto`
  ADD CONSTRAINT `historico_estado_moto_ibfk_1` FOREIGN KEY (`id_moto`) REFERENCES `motos` (`id_moto`);

--
-- Filtros para la tabla `pagos_contrato`
--
ALTER TABLE `pagos_contrato`
  ADD CONSTRAINT `pagos_contrato_ibfk_1` FOREIGN KEY (`id_contrato`) REFERENCES `contratos` (`id_contrato`),
  ADD CONSTRAINT `pagos_contrato_ibfk_2` FOREIGN KEY (`id_periodo`) REFERENCES `periodos_contrato` (`id_periodo`),
  ADD CONSTRAINT `pagos_contrato_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `periodos_contrato`
--
ALTER TABLE `periodos_contrato`
  ADD CONSTRAINT `periodos_contrato_ibfk_1` FOREIGN KEY (`id_contrato`) REFERENCES `contratos` (`id_contrato`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
