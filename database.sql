CREATE DATABASE IF NOT EXISTS aplicacion CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE aplicacion;


CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nic_name VARCHAR(100) NOT NULL,
    cedula VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    modulos TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    cod VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    id_ai VARCHAR(100),
    modulos TEXT,
    created_by_admin INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by_admin) REFERENCES admins(id) ON DELETE SET NULL
);


CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    serial VARCHAR(150) NOT NULL,
    id_ai VARCHAR(100),
    created_by_user INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by_user) REFERENCES users(id) ON DELETE SET NULL
);

INSERT INTO admins (nic_name, cedula, password, modulos) VALUES (
    'admin',
    '0000000000',
    '12345678', 
    '["usuarios","productos"]'
);
