-- Schema for MySQL converted from PostgreSQL

-- Create database (if not exists)
-- Note: In MySQL, databases are created separately, but for local development, you can create it here

-- Set SQL mode and charset
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS moto_rent_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE moto_rent_db;

-- Create tables

-- Table: usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('administrador', 'operador', 'contador') DEFAULT 'operador',
    activo BOOLEAN DEFAULT TRUE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: clientes
CREATE TABLE IF NOT EXISTS clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    identificacion VARCHAR(50) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: motos
CREATE TABLE IF NOT EXISTS motos (
    id_moto INT AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(10) UNIQUE NOT NULL,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    valor_adquisicion DECIMAL(12,2) NOT NULL,
    estado ENUM('activo', 'alquilada', 'mantenimiento', 'vendida', 'baja') DEFAULT 'activo',
    fecha_adquisicion DATE NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: contratos
CREATE TABLE IF NOT EXISTS contratos (
    id_contrato INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_moto INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    valor_vehiculo DECIMAL(12,2) NOT NULL,
    plazo_meses INT NOT NULL,
    cuota_mensual DECIMAL(10,2) NOT NULL,
    saldo_restante DECIMAL(12,2) NOT NULL,
    estado ENUM('activo', 'finalizado', 'cancelado') DEFAULT 'activo',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    abono_capital_mensual DECIMAL(10,2) NOT NULL,
    ganancia_mensual DECIMAL(10,2) NOT NULL,
    cuota_diaria DECIMAL(12,2),
    observaciones TEXT,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente),
    FOREIGN KEY (id_moto) REFERENCES motos(id_moto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: periodos_contrato
CREATE TABLE IF NOT EXISTS periodos_contrato (
    id_periodo INT AUTO_INCREMENT PRIMARY KEY,
    id_contrato INT NOT NULL,
    numero_periodo INT NOT NULL,
    fecha_inicio_periodo DATE NOT NULL,
    fecha_fin_periodo DATE NOT NULL,
    cuota_acumulada DECIMAL(10,2) DEFAULT 0.00,
    estado_periodo ENUM('abierto', 'cerrado') DEFAULT 'abierto',
    cerrado_en TIMESTAMP NULL,
    pago_anticipado BOOLEAN DEFAULT FALSE,
    dias_generados BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_contrato) REFERENCES contratos(id_contrato)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pagos_contrato
CREATE TABLE IF NOT EXISTS pagos_contrato (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_contrato INT NOT NULL,
    id_periodo INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_pago DATE NOT NULL,
    monto_pago DECIMAL(10,2) NOT NULL,
    concepto VARCHAR(255) DEFAULT '',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (id_contrato) REFERENCES contratos(id_contrato),
    FOREIGN KEY (id_periodo) REFERENCES periodos_contrato(id_periodo),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pagos_contrato_historial
CREATE TABLE IF NOT EXISTS pagos_contrato_historial (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_pago INT NOT NULL,
    id_contrato INT NOT NULL,
    id_periodo INT NOT NULL,
    id_usuario INT NULL,
    fecha_pago DATE NOT NULL,
    monto_pago DECIMAL(12,2) NOT NULL,
    concepto VARCHAR(255),
    accion ENUM('creacion', 'edicion', 'eliminacion') NOT NULL,
    motivo TEXT,
    editado_por INT NULL,
    editado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pago) REFERENCES pagos_contrato(id_pago) ON DELETE CASCADE,
    FOREIGN KEY (id_contrato) REFERENCES contratos(id_contrato) ON DELETE CASCADE,
    FOREIGN KEY (id_periodo) REFERENCES periodos_contrato(id_periodo) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL,
    FOREIGN KEY (editado_por) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pagos_diarios_contrato
CREATE TABLE IF NOT EXISTS pagos_diarios_contrato (
    id_pago_diario INT AUTO_INCREMENT PRIMARY KEY,
    id_contrato INT NOT NULL,
    id_periodo INT NOT NULL,
    fecha DATE NOT NULL,
    es_domingo BOOLEAN DEFAULT FALSE,
    estado_dia ENUM('pagado', 'pendiente', 'no_pago') DEFAULT 'pendiente',
    monto_pagado DECIMAL(12,2) DEFAULT 0.00,
    observacion TEXT,
    id_usuario_registra INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (id_contrato) REFERENCES contratos(id_contrato) ON DELETE CASCADE,
    FOREIGN KEY (id_periodo) REFERENCES periodos_contrato(id_periodo) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario_registra) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pago_contrato_asignacion_dias
CREATE TABLE IF NOT EXISTS pago_contrato_asignacion_dias (
    id_asignacion INT AUTO_INCREMENT PRIMARY KEY,
    id_pago INT NOT NULL,
    id_pago_diario INT NOT NULL,
    monto_asignado DECIMAL(12,2) DEFAULT 0.00,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pago) REFERENCES pagos_contrato(id_pago) ON DELETE CASCADE,
    FOREIGN KEY (id_pago_diario) REFERENCES pagos_diarios_contrato(id_pago_diario) ON DELETE CASCADE,
    UNIQUE KEY unique_pago_diario (id_pago, id_pago_diario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: gastos_empresa
CREATE TABLE IF NOT EXISTS gastos_empresa (
    id_gasto INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_moto INT NULL,
    fecha_gasto DATE NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    categoria ENUM('mantenimiento', 'operativo', 'general', 'impuestos') NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_moto) REFERENCES motos(id_moto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: historico_estado_moto
CREATE TABLE IF NOT EXISTS historico_estado_moto (
    id_historico INT AUTO_INCREMENT PRIMARY KEY,
    id_moto INT NOT NULL,
    estado_anterior VARCHAR(50) NOT NULL,
    estado_nuevo VARCHAR(50) NOT NULL,
    fecha_cambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_moto) REFERENCES motos(id_moto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create indexes for better performance
CREATE INDEX idx_contrato_periodo ON periodos_contrato(id_contrato, numero_periodo);
CREATE INDEX idx_contrato_fecha ON pagos_contrato(id_contrato, fecha_pago);
CREATE INDEX idx_periodo_fecha ON pagos_contrato(id_periodo, fecha_pago);
CREATE INDEX idx_pdc_periodo_fecha ON pagos_diarios_contrato(id_periodo, fecha);
CREATE INDEX idx_pdc_contrato ON pagos_diarios_contrato(id_contrato);
CREATE INDEX idx_pdc_periodo ON pagos_diarios_contrato(id_periodo);
CREATE INDEX idx_pdc_fecha ON pagos_diarios_contrato(fecha);
CREATE INDEX idx_pch_pago ON pagos_contrato_historial(id_pago);
CREATE INDEX idx_pch_contrato ON pagos_contrato_historial(id_contrato, id_periodo);
CREATE INDEX idx_pcad_pago ON pago_contrato_asignacion_dias(id_pago);
CREATE INDEX idx_pcad_pago_diario ON pago_contrato_asignacion_dias(id_pago_diario);

COMMIT;
