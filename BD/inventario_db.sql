-- BASE DE DATOS  
CREATE DATABASE taller_mecanico;
USE taller_mecanico;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    dni VARCHAR(20) NOT NULL UNIQUE,
    patente VARCHAR(20),
    modelo VARCHAR(50),
    contrasenia VARCHAR(255) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    rol VARCHAR(20) DEFAULT 'Cliente'
);

-- TABLA PARA RECIBIR LOS MENSAJES DE L0S USUSARIOS
CREATE TABLE mensajes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50),
  email VARCHAR(100),
  telefono VARCHAR(15),
  asunto VARCHAR(150),
  mensaje TEXT,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE stock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(100) NOT NULL,
    marca VARCHAR(50),
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2),
    imagen VARCHAR(255)
);

/*CREATE TABLE empleado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    dni VARCHAR(20) NOT NULL UNIQUE,
    telefono VARCHAR(20) NOT NULL,
    direccion VARCHAR(40) NOT NULL,
    id_especialidad INT NOT NULL,
    FOREIGN KEY (id_especialidad) REFERENCES especialidades(id),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
*/

-- Crear primero la tabla de especialidades
CREATE TABLE especialidades(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL
);

-- Luego crear la tabla de empleados
CREATE TABLE empleado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    dni VARCHAR(20) NOT NULL UNIQUE,
    telefono VARCHAR(20) NOT NULL,
    direccion VARCHAR(40) NOT NULL,
    id_especialidad INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valor_hora DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    FOREIGN KEY (id_especialidad) REFERENCES especialidades(id)
    ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_id_especialidad (id_especialidad)
);

CREATE TABLE trabajos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_empleado INT NOT NULL,
    descripcion TEXT NOT NULL,
    estado ENUM('Pendiente', 'En progreso', 'Finalizado') NOT NULL DEFAULT 'Pendiente',
    informe TEXT,
    fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_empleado) REFERENCES empleado(id)
);

CREATE TABLE consultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    servicio VARCHAR(100) NOT NULL,
    mensaje TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE mensajes_respondidos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(100),
  asunto VARCHAR(150),
  mensaje TEXT,
  respuesta TEXT,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/*ALTER TABLE empleado
ADD valor_hora DECIMAL(10,2) NOT NULL DEFAULT 0.00;*/

ALTER TABLE servicios
ADD costo_base DECIMAL(10,2) NOT NULL DEFAULT 0.00;

CREATE TABLE facturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_trabajo INT NOT NULL,
    fecha_emision TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(10,2) NOT NULL,
    iva DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado_pago ENUM('Pendiente', 'Pagado') DEFAULT 'Pendiente',
    archivo_pdf VARCHAR(255),
    FOREIGN KEY (id_trabajo) REFERENCES trabajos(id)
);

CREATE TABLE factura_productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_factura INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_factura) REFERENCES facturas(id),
    FOREIGN KEY (id_producto) REFERENCES stock(id)
);

ALTER TABLE trabajos
ADD horas_estimadas DECIMAL(5,2) DEFAULT 0.00;

ALTER TABLE trabajos
ADD id_servicio INT,
ADD FOREIGN KEY (id_servicio) REFERENCES servicios(id);

CREATE TABLE productos_usados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_factura INT NOT NULL,
    id_stock INT NOT NULL,
    cantidad INT NOT NULL,
    FOREIGN KEY (id_factura) REFERENCES facturas(id),
    FOREIGN KEY (id_stock) REFERENCES stock(id)
);

