-- =====================================================================
--  BASE DE DATOS DE PRÁCTICA:  empresadb
--  Tablas: departamentos (1) ---< (N) empleados
--
--  Ejecútalo entero en phpMyAdmin (pestaña SQL).
--  Como empieza con DROP DATABASE, puedes volver a ejecutarlo cuando
--  quieras para dejar los datos como al principio (después de subir
--  sueldos, bloquear, borrar...).
-- =====================================================================

DROP DATABASE IF EXISTS empresadb;
CREATE DATABASE empresadb CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE empresadb;

-- ---------------------------------------------------------------------
-- Tabla departamentos
--   presupuesto = dinero MENSUAL disponible para pagar sueldos
-- ---------------------------------------------------------------------
CREATE TABLE departamentos (
  cod_dep      INT          NOT NULL AUTO_INCREMENT,
  nombre       VARCHAR(30)  NOT NULL,
  localidad    VARCHAR(30)  NOT NULL,
  presupuesto  DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (cod_dep)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabla empleados
--   cod_emp   -> hace de "login"
--   bloqueado -> 0 activo, 1 bloqueado
--   accesos   -> número de veces que ha entrado en la aplicación
-- ---------------------------------------------------------------------
CREATE TABLE empleados (
  cod_emp     VARCHAR(10)  NOT NULL,
  nombre      VARCHAR(40)  NOT NULL,
  clave       VARCHAR(20)  NOT NULL,
  puesto      VARCHAR(20)  NOT NULL,
  sueldo      DECIMAL(8,2) NOT NULL,
  cod_dep     INT          NOT NULL,
  bloqueado   TINYINT      NOT NULL DEFAULT 0,
  accesos     INT          NOT NULL DEFAULT 0,
  fecha_alta  DATE         NOT NULL,
  PRIMARY KEY (cod_emp),
  CONSTRAINT fk_empleados_departamentos
      FOREIGN KEY (cod_dep) REFERENCES departamentos (cod_dep)
      ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Datos
-- ---------------------------------------------------------------------
INSERT INTO departamentos (cod_dep, nombre, localidad, presupuesto) VALUES
(1, 'Desarrollo',     'Madrid',    12000.00),
(2, 'Sistemas',       'Madrid',     7000.00),
(3, 'Administración', 'Barcelona',  5000.00),   -- sus sueldos suman MÁS que el presupuesto
(4, 'Marketing',      'Valencia',   4000.00);   -- departamento SIN empleados

INSERT INTO empleados (cod_emp, nombre, clave, puesto, sueldo, cod_dep, bloqueado, accesos, fecha_alta) VALUES
('ana01',   'Ana López',     '1234',  'Jefe',           3200.00, 1, 0, 5, '2015-03-01'),
('luis02',  'Luis García',   'abcd',  'Programador',    2100.00, 1, 0, 2, '2019-09-15'),
('marta03', 'Marta Ruiz',    'marta', 'Programador',    1950.00, 1, 0, 0, '2021-01-10'),
('pablo04', 'Pablo Sanz',    'pablo', 'Becario',         850.00, 1, 0, 1, '2025-10-01'),
('sara05',  'Sara Gil',      'sara',  'Analista',       2600.00, 1, 1, 3, '2018-06-20'),
('jorge06', 'Jorge Martín',  'jorge', 'Jefe',           3000.00, 2, 0, 7, '2012-11-05'),
('elena07', 'Elena Torres',  'elena', 'Programador',    2300.00, 2, 0, 0, '2020-02-17'),
('david08', 'David Romero',  'david', 'Becario',         980.00, 2, 0, 0, '2026-09-01'),
('lucia09', 'Lucía Navarro', 'lucia', 'Jefe',           2800.00, 3, 0, 4, '2016-04-12'),
('raul10',  'Raúl Ortega',   'raul',  'Administrativo', 1500.00, 3, 0, 1, '2022-07-03'),
('irene11', 'Irene Castro',  'irene', 'Administrativo', 1450.00, 3, 1, 0, '2023-03-27');

-- ---------------------------------------------------------------------
-- Resumen para que compruebes tus resultados:
--   Desarrollo     -> 5 empleados, gasto 10700.00 / 12000  (sara05 bloqueada)
--   Sistemas       -> 3 empleados, gasto  6280.00 /  7000
--   Administración -> 3 empleados, gasto  5750.00 /  5000  ¡EXCEDIDO! (irene11 bloqueada)
--   Marketing      -> 0 empleados, gasto     0.00 /  4000
--   Becarios: pablo04 (850) y david08 (980) -> ojo con el tope de 1000 del Ej2
-- ---------------------------------------------------------------------
