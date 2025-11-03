-- Schema for PostgreSQL converted from MySQL

-- Create database (if not exists)
-- Note: In PostgreSQL, databases are created separately, but for Render, it will be created via their interface

-- Set timezone
SET timezone = 'UTC';

-- Create tables

-- Table: usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    rol VARCHAR(20) CHECK (rol IN ('administrador', 'operador', 'contador')) DEFAULT 'operador',
    activo BOOLEAN DEFAULT TRUE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: clientes
CREATE TABLE IF NOT EXISTS clientes (
    id_cliente SERIAL PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    identificacion VARCHAR(50) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: motos
CREATE TABLE IF NOT EXISTS motos (
    id_moto SERIAL PRIMARY KEY,
    placa VARCHAR(10) UNIQUE NOT NULL,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    valor_adquisicion DECIMAL(12,2) NOT NULL,
    estado VARCHAR(20) CHECK (estado IN ('activo', 'alquilada', 'mantenimiento', 'vendida', 'baja')) DEFAULT 'activo',
    fecha_adquisicion DATE NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: contratos
CREATE TABLE IF NOT EXISTS contratos (
    id_contrato SERIAL PRIMARY KEY,
    id_cliente INTEGER NOT NULL REFERENCES clientes(id_cliente),
    id_moto INTEGER NOT NULL REFERENCES motos(id_moto),
    fecha_inicio DATE NOT NULL,
    valor_vehiculo DECIMAL(12,2) NOT NULL,
    plazo_meses INTEGER NOT NULL,
    cuota_mensual DECIMAL(10,2) NOT NULL,
    saldo_restante DECIMAL(12,2) NOT NULL,
    estado VARCHAR(20) CHECK (estado IN ('activo', 'finalizado', 'cancelado')) DEFAULT 'activo',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    abono_capital_mensual DECIMAL(10,2) NOT NULL,
    ganancia_mensual DECIMAL(10,2) NOT NULL,
    cuota_diaria DECIMAL(12,2),
    observaciones TEXT
);

-- Table: periodos_contrato
CREATE TABLE IF NOT EXISTS periodos_contrato (
    id_periodo SERIAL PRIMARY KEY,
    id_contrato INTEGER NOT NULL REFERENCES contratos(id_contrato),
    numero_periodo INTEGER NOT NULL,
    fecha_inicio_periodo DATE NOT NULL,
    fecha_fin_periodo DATE NOT NULL,
    cuota_acumulada DECIMAL(10,2) DEFAULT 0.00,
    estado_periodo VARCHAR(20) CHECK (estado_periodo IN ('abierto', 'cerrado')) DEFAULT 'abierto',
    cerrado_en TIMESTAMP,
    pago_anticipado BOOLEAN DEFAULT FALSE,
    dias_generados BOOLEAN DEFAULT FALSE
);

-- Table: pagos_contrato
CREATE TABLE IF NOT EXISTS pagos_contrato (
    id_pago SERIAL PRIMARY KEY,
    id_contrato INTEGER NOT NULL REFERENCES contratos(id_contrato),
    id_periodo INTEGER NOT NULL REFERENCES periodos_contrato(id_periodo),
    id_usuario INTEGER NOT NULL REFERENCES usuarios(id_usuario),
    fecha_pago DATE NOT NULL,
    monto_pago DECIMAL(10,2) NOT NULL,
    concepto VARCHAR(255) DEFAULT '',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP
);

-- Table: pagos_contrato_historial
CREATE TABLE IF NOT EXISTS pagos_contrato_historial (
    id_historial SERIAL PRIMARY KEY,
    id_pago INTEGER NOT NULL REFERENCES pagos_contrato(id_pago) ON DELETE CASCADE,
    id_contrato INTEGER NOT NULL REFERENCES contratos(id_contrato) ON DELETE CASCADE,
    id_periodo INTEGER NOT NULL REFERENCES periodos_contrato(id_periodo) ON DELETE CASCADE,
    id_usuario INTEGER REFERENCES usuarios(id_usuario) ON DELETE SET NULL,
    fecha_pago DATE NOT NULL,
    monto_pago DECIMAL(12,2) NOT NULL,
    concepto VARCHAR(255),
    accion VARCHAR(20) CHECK (accion IN ('creacion', 'edicion', 'eliminacion')) NOT NULL,
    motivo TEXT,
    editado_por INTEGER REFERENCES usuarios(id_usuario) ON DELETE SET NULL,
    editado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: pagos_diarios_contrato
CREATE TABLE IF NOT EXISTS pagos_diarios_contrato (
    id_pago_diario SERIAL PRIMARY KEY,
    id_contrato INTEGER NOT NULL REFERENCES contratos(id_contrato) ON DELETE CASCADE,
    id_periodo INTEGER NOT NULL REFERENCES periodos_contrato(id_periodo) ON DELETE CASCADE,
    fecha DATE NOT NULL,
    es_domingo BOOLEAN DEFAULT FALSE,
    estado_dia VARCHAR(20) CHECK (estado_dia IN ('pagado', 'pendiente', 'no_pago')) DEFAULT 'pendiente',
    monto_pagado DECIMAL(12,2) DEFAULT 0.00,
    observacion TEXT,
    id_usuario_registra INTEGER REFERENCES usuarios(id_usuario) ON DELETE SET NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP
);

-- Table: pago_contrato_asignacion_dias
CREATE TABLE IF NOT EXISTS pago_contrato_asignacion_dias (
    id_asignacion SERIAL PRIMARY KEY,
    id_pago INTEGER NOT NULL REFERENCES pagos_contrato(id_pago) ON DELETE CASCADE,
    id_pago_diario INTEGER NOT NULL REFERENCES pagos_diarios_contrato(id_pago_diario) ON DELETE CASCADE,
    monto_asignado DECIMAL(12,2) DEFAULT 0.00,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(id_pago, id_pago_diario)
);

-- Table: gastos_empresa
CREATE TABLE IF NOT EXISTS gastos_empresa (
    id_gasto SERIAL PRIMARY KEY,
    id_usuario INTEGER NOT NULL REFERENCES usuarios(id_usuario),
    id_moto INTEGER REFERENCES motos(id_moto),
    fecha_gasto DATE NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    categoria VARCHAR(20) CHECK (categoria IN ('mantenimiento', 'operativo', 'general', 'impuestos')) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: historico_estado_moto
CREATE TABLE IF NOT EXISTS historico_estado_moto (
    id_historico SERIAL PRIMARY KEY,
    id_moto INTEGER NOT NULL REFERENCES motos(id_moto),
    estado_anterior VARCHAR(50) NOT NULL,
    estado_nuevo VARCHAR(50) NOT NULL,
    fecha_cambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_contrato_periodo ON periodos_contrato(id_contrato, numero_periodo);
CREATE INDEX IF NOT EXISTS idx_contrato_fecha ON pagos_contrato(id_contrato, fecha_pago);
CREATE INDEX IF NOT EXISTS idx_periodo_fecha ON pagos_contrato(id_periodo, fecha_pago);
CREATE INDEX IF NOT EXISTS idx_pdc_periodo_fecha ON pagos_diarios_contrato(id_periodo, fecha);
CREATE INDEX IF NOT EXISTS idx_pdc_contrato ON pagos_diarios_contrato(id_contrato);
CREATE INDEX IF NOT EXISTS idx_pdc_periodo ON pagos_diarios_contrato(id_periodo);
CREATE INDEX IF NOT EXISTS idx_pdc_fecha ON pagos_diarios_contrato(fecha);
CREATE INDEX IF NOT EXISTS idx_pch_pago ON pagos_contrato_historial(id_pago);
CREATE INDEX IF NOT EXISTS idx_pch_contrato ON pagos_contrato_historial(id_contrato, id_periodo);
CREATE INDEX IF NOT EXISTS idx_pcad_pago ON pago_contrato_asignacion_dias(id_pago);
CREATE INDEX IF NOT EXISTS idx_pcad_pago_diario ON pago_contrato_asignacion_dias(id_pago_diario);

-- Insert sample data (optional, for testing)
-- Uncomment the following lines if you want to insert sample data

/*
INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES
('alvaro', 'alvaro@gmail.com', '$2y$10$yFL2DIrWL6Rw/4AtQdgXCuPp40Kz9cfzaxFfnsu9y/uOBv9em9mxC', 'administrador');

INSERT INTO clientes (nombre_completo, identificacion, telefono, direccion) VALUES
('juan perez', '1234567789', '3244060909', 'en mi casa');

INSERT INTO motos (placa, marca, modelo, valor_adquisicion, fecha_adquisicion) VALUES
('shn19b', 'yamha', '2017', 5500000.00, '2025-10-27'),
('aty12b', 'yamha', '2017', 5500000.00, '2025-10-27');
*/
