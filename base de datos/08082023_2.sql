-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         5.7.33 - MySQL Community Server (GPL)
-- SO del servidor:              Win64
-- HeidiSQL Versión:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para personal
CREATE DATABASE IF NOT EXISTS `personal` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci */;
USE `personal`;

-- Volcando estructura para tabla personal.direcciones
CREATE TABLE IF NOT EXISTS `direcciones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `departamento` varchar(25) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '0',
  `municipio` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '0',
  `ubicacion` varchar(150) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '0',
  `email` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '0',
  `telefono` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '0',
  `id_docente` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_direcciones_docente` (`id_docente`),
  CONSTRAINT `FK_direcciones_docente` FOREIGN KEY (`id_docente`) REFERENCES `docente` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla personal.direcciones: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `direcciones` DISABLE KEYS */;
INSERT INTO `direcciones` (`id`, `departamento`, `municipio`, `ubicacion`, `email`, `telefono`, `id_docente`) VALUES
	(4, 'Yoro', 'Morazan', 'barrio el centro', 'yoro.morazan@se.gob.hm', '94755043', NULL),
	(5, 'Yoro', 'Yorito', 'frente al parque central', 'yoro.yorito@se.gob.hm', '97917029', NULL);
/*!40000 ALTER TABLE `direcciones` ENABLE KEYS */;

-- Volcando estructura para tabla personal.docente
CREATE TABLE IF NOT EXISTS `docente` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Identidad` varchar(20) NOT NULL DEFAULT '',
  `Nombre1` varchar(50) NOT NULL,
  `Nombre2` varchar(50) NOT NULL,
  `Apellido1` varchar(50) NOT NULL,
  `Apellido2` varchar(50) NOT NULL,
  `Escalafon` varchar(50) NOT NULL,
  `Imprema` varchar(50) NOT NULL,
  `Telefono` varchar(16) NOT NULL DEFAULT '',
  `Correo` varchar(120) NOT NULL,
  `Foto` varchar(250) NOT NULL,
  `Status` varchar(10) DEFAULT NULL,
  `sexo` varchar(10) DEFAULT NULL,
  `fecha_nacimeito` varchar(10) DEFAULT NULL,
  `titulo` varchar(50) DEFAULT NULL,
  `edo_mail` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla personal.docente: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `docente` DISABLE KEYS */;
INSERT INTO `docente` (`id`, `Identidad`, `Nombre1`, `Nombre2`, `Apellido1`, `Apellido2`, `Escalafon`, `Imprema`, `Telefono`, `Correo`, `Foto`, `Status`, `sexo`, `fecha_nacimeito`, `titulo`, `edo_mail`) VALUES
	(3, '1804-1990-01507', 'Amilcar', 'Enrique', 'Irias', 'Aguilar', 'PTI00296', '1804-1990-01507', '94755043', 'amilcaririas@gmail.com', 'view/img/docentes/php4466.jpeg', 'activo', 'masculino', '1990-03-24', 'Licenciado En InformÃ¡tica Educativa', 'pendiente'),
	(4, '1111-1111-11111', 'Pnombre', 'Snombre', 'Papellido', 'Sapellido', 'escalafon', 'imprema', '9999999999', 'correo@gmail.com', 'view/img/docentes/php1CF9.jpeg', 'activo', 'masculino', '1999-10-09', 'titulo', 'pendiente');
/*!40000 ALTER TABLE `docente` ENABLE KEYS */;
-- Volcando estructura para tabla personal.centro
CREATE TABLE IF NOT EXISTS `centro` (
  `id_centro` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Codigo_centro` varchar(13) NOT NULL,
  `Nombre` varchar(70) NOT NULL,
  `Municipio` varchar(20) NOT NULL,
  `Direccion` varchar(190) NOT NULL,
  `Tipo_centro` varchar(10) NOT NULL,
  `Telefono` varchar(15) NOT NULL DEFAULT '',
  `N_acuerdo` varchar(15) NOT NULL DEFAULT '',
  `estatus` varchar(30) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `acuerdo` varchar(50) NOT NULL,
  PRIMARY KEY (`id_centro`),
  UNIQUE KEY `Índice 1` (`Codigo_centro`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- Volcando datos para la tabla personal.centro: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `centro` DISABLE KEYS */;
INSERT INTO `centro` (`id_centro`, `Codigo_centro`, `Nombre`, `Municipio`, `Direccion`, `Tipo_centro`, `Telefono`, `N_acuerdo`, `estatus`, `logo`, `acuerdo`) VALUES
	(8, '180600036M02', 'Republica de Cuba', '4', 'Nueva Esperanza', 'Media', '26911212', '0', 'Activo', 'view/img/escuelas/phpFC40.png', 'view/pdf/escuelas/phpFB16.pdf'),
	(9, '1807000027B1', 'Dionicio Herrera', '5', 'barrio el Centro', 'Prebasica', '26911322', '0', 'Activo', 'view/img/escuelas/php499C.png', 'view/pdf/escuelas/php498B.pdf');
/*!40000 ALTER TABLE `centro` ENABLE KEYS */;

-- Volcando estructura para tabla personal.designaciones
CREATE TABLE IF NOT EXISTS `designaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_docente` int(10) unsigned NOT NULL,
  `id_centro` smallint(5) unsigned DEFAULT NULL,
  `id_direccion` int(10) unsigned DEFAULT NULL,
  `puesto` varchar(30) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '',
  `condicion` varchar(12) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '',
  `estatus` varchar(12) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '',
  `horas` varchar(4) COLLATE utf8mb4_spanish_ci DEFAULT '',
  `fasignacion` varchar(10) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '',
  `fvencimiento` varchar(10) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `FK_designaciones_docente` (`id_docente`),
  KEY `FK_designaciones_centro` (`id_centro`),
  KEY `FK_designaciones_direcciones` (`id_direccion`),
  CONSTRAINT `FK_designaciones_centro` FOREIGN KEY (`id_centro`) REFERENCES `centro` (`id_centro`) ON UPDATE CASCADE,
  CONSTRAINT `FK_designaciones_direcciones` FOREIGN KEY (`id_direccion`) REFERENCES `direcciones` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_designaciones_docente` FOREIGN KEY (`id_docente`) REFERENCES `docente` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla personal.designaciones: ~6 rows (aproximadamente)
/*!40000 ALTER TABLE `designaciones` DISABLE KEYS */;
INSERT INTO `designaciones` (`id`, `id_docente`, `id_centro`, `id_direccion`, `puesto`, `condicion`, `estatus`, `horas`, `fasignacion`, `fvencimiento`) VALUES
	(9, 3, NULL, NULL, 'SysAdmin', 'Permanente', 'Activo', '', '01-08-2023', '01-08-2024'),
	(10, 3, NULL, 4, 'Director(a) Municipal', '--', 'activo', '', '31-07-2023', '06-08-2023'),
	(11, 3, NULL, 5, 'Director(a) Municipal', '--', 'activo', '', '31-07-2023', '06-08-2023'),
	(12, 3, 8, NULL, 'Director', 'Permanente', 'en propiedad', '0', '31-07-2023', '06-08-2023'),
	(13, 4, 8, NULL, 'maestro', 'Permanente', 'Reubicado', '36', '31-07-2023', '31-08-2023'),
	(14, 4, 9, NULL, 'Director', 'Permanente', 'en propiedad', '0', '31-07-2023', '06-08-2023');
/*!40000 ALTER TABLE `designaciones` ENABLE KEYS */;

-- Volcando estructura para tabla personal.operaciones
CREATE TABLE IF NOT EXISTS `operaciones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_proceso` varchar(30) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `descripcion_proceso` varchar(450) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `link_plantilla` varchar(120) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla personal.operaciones: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `operaciones` DISABLE KEYS */;
INSERT INTO `operaciones` (`id`, `nombre_proceso`, `descripcion_proceso`, `link_plantilla`) VALUES
	(2, 'Matricula Sin RestricciÃ³n', 'Matricular Alumno Com Problemas de registro de aÃ±os anteriores', 'https://drive.google.com/file/d/1MV_LNXR1U6DV75MRhDRMfPjVVczEYras/view?usp=drive_link');
/*!40000 ALTER TABLE `operaciones` ENABLE KEYS */;

-- Volcando estructura para tabla personal.detalles_operaciones
CREATE TABLE IF NOT EXISTS `detalles_operaciones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_operacion` int(10) unsigned DEFAULT NULL,
  `campo` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `valores` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `descripcion` varchar(120) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_detalles_operaciones_operaciones` (`id_operacion`),
  CONSTRAINT `FK_detalles_operaciones_operaciones` FOREIGN KEY (`id_operacion`) REFERENCES `operaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla personal.detalles_operaciones: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `detalles_operaciones` DISABLE KEYS */;
INSERT INTO `detalles_operaciones` (`id`, `id_operacion`, `campo`, `tipo`, `valores`, `descripcion`) VALUES
	(5, 2, 'identidad del alumno', 'texto', '', ''),
	(6, 2, 'nombre del Alumno', 'texto', '', ''),
	(7, 2, 'plantilla', 'archivo', '', '');
/*!40000 ALTER TABLE `detalles_operaciones` ENABLE KEYS */;

-- Volcando estructura para tabla personal.detalle_parte_mensual
CREATE TABLE IF NOT EXISTS `detalle_parte_mensual` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_parte_mensual` int(10) unsigned NOT NULL,
  `grado` varchar(10) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `mat_con_var` int(11) DEFAULT NULL,
  `mat_con_hem` int(11) DEFAULT NULL,
  `mat_con_tot` int(11) DEFAULT NULL,
  `mat_ant_var` int(11) DEFAULT NULL,
  `mat_ant_hem` int(11) DEFAULT NULL,
  `mat_ant_tot` int(11) DEFAULT NULL,
  `mat_act_var` int(11) DEFAULT NULL,
  `mat_act_hem` int(11) DEFAULT NULL,
  `mat_act_tot` int(11) DEFAULT NULL,
  `asis_med_var` int(11) DEFAULT NULL,
  `asis_med_hem` int(11) DEFAULT NULL,
  `asis_med_tot` int(11) DEFAULT NULL,
  `tant_porc_var` int(11) DEFAULT NULL,
  `tant_porc_hem` int(11) DEFAULT NULL,
  `tant_porc_tot` int(11) DEFAULT NULL,
  `inasistencia_var` int(11) DEFAULT NULL,
  `inasistencia_hem` int(11) DEFAULT NULL,
  `inasistencia_tot` int(11) DEFAULT NULL,
  `ingreso_var` int(11) DEFAULT NULL,
  `ingreso_hem` int(11) DEFAULT NULL,
  `ingreso_tot` int(11) DEFAULT NULL,
  `desertores_var` int(11) DEFAULT NULL,
  `desertores_hem` int(11) DEFAULT NULL,
  `desertores_tot` int(11) DEFAULT NULL,
  `traslados_var` int(11) DEFAULT NULL,
  `traslados_hem` int(11) DEFAULT NULL,
  `traslados_tot` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_detalle_parte_mensual_parte_mensual` (`id_parte_mensual`),
  CONSTRAINT `FK_detalle_parte_mensual_parte_mensual` FOREIGN KEY (`id_parte_mensual`) REFERENCES `parte_mensual` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla personal.detalle_parte_mensual: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `detalle_parte_mensual` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_parte_mensual` ENABLE KEYS */;

-- Volcando estructura para tabla personal.detalle_solicitud
CREATE TABLE IF NOT EXISTS `detalle_solicitud` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_solicitud` int(10) unsigned DEFAULT NULL,
  `campo` varchar(20) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `valor` varchar(100) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK__solicitudes` (`id_solicitud`),
  CONSTRAINT `FK__solicitudes` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla personal.detalle_solicitud: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `detalle_solicitud` DISABLE KEYS */;
INSERT INTO `detalle_solicitud` (`id`, `id_solicitud`, `campo`, `valor`) VALUES
	(13, 7, 'identidad_del_alumno', '1111-1111-11111'),
	(14, 7, 'nombre_del_Alumno', 'alumnp de Prueba'),
	(15, 7, 'plantilla', 'view/tramites/7/phpBD70.pdf');
/*!40000 ALTER TABLE `detalle_solicitud` ENABLE KEYS */;


-- Volcando estructura para tabla personal.docente_parte_mensual
CREATE TABLE IF NOT EXISTS `docente_parte_mensual` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_parte_mensual` int(10) unsigned NOT NULL,
  `numero` int(10) unsigned NOT NULL DEFAULT '0',
  `nombre` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `cargo` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `grado` varchar(10) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `n_alumnos` int(11) DEFAULT NULL,
  `inasistencia_autorizadas` int(11) DEFAULT NULL,
  `inasistencias_autorizadas` int(11) DEFAULT NULL,
  `total_inasistencia` int(11) DEFAULT NULL,
  `Observaciones` varchar(200) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK__parte_mensual` (`id_parte_mensual`),
  CONSTRAINT `FK__parte_mensual` FOREIGN KEY (`id_parte_mensual`) REFERENCES `parte_mensual` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla personal.docente_parte_mensual: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `docente_parte_mensual` DISABLE KEYS */;
/*!40000 ALTER TABLE `docente_parte_mensual` ENABLE KEYS */;

-- Volcando estructura para tabla personal.estructura_presupuestaria
CREATE TABLE IF NOT EXISTS `estructura_presupuestaria` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_designacion` int(11) NOT NULL,
  `dependencia` varchar(15) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `departamento` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `municipio` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `cod_centro` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `cod_plaza` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `horas` varchar(50) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_estructura_presupuestaria_designaciones` (`id_designacion`),
  CONSTRAINT `FK_estructura_presupuestaria_designaciones` FOREIGN KEY (`id_designacion`) REFERENCES `designaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla personal.estructura_presupuestaria: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `estructura_presupuestaria` DISABLE KEYS */;
INSERT INTO `estructura_presupuestaria` (`id`, `id_designacion`, `dependencia`, `departamento`, `municipio`, `cod_centro`, `cod_plaza`, `horas`) VALUES
	(1, 12, 'Gubernamental', 'Yoro', 'Morazan', '18', '501', '36');
/*!40000 ALTER TABLE `estructura_presupuestaria` ENABLE KEYS */;

-- Volcando estructura para tabla personal.notas_solicitudes
CREATE TABLE IF NOT EXISTS `notas_solicitudes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` varchar(10) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '0',
  `autor` varchar(10) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '0',
  `nota` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '0',
  `id_solicitud` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_notas_solicitudes_solicitudes` (`id_solicitud`),
  CONSTRAINT `FK_notas_solicitudes_solicitudes` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla personal.notas_solicitudes: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `notas_solicitudes` DISABLE KEYS */;
/*!40000 ALTER TABLE `notas_solicitudes` ENABLE KEYS */;

-- Volcando estructura para tabla personal.observaciones_solicitudes
CREATE TABLE IF NOT EXISTS `observaciones_solicitudes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_solicitud` int(10) unsigned NOT NULL DEFAULT '0',
  `id_autor` int(10) NOT NULL DEFAULT '0',
  `fecha` varchar(10) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '0',
  `Observacion` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_observaciones_solicitudes_solicitudes` (`id_solicitud`),
  KEY `FK_observaciones_solicitudes_designaciones` (`id_autor`),
  CONSTRAINT `FK_observaciones_solicitudes_designaciones` FOREIGN KEY (`id_autor`) REFERENCES `designaciones` (`id`),
  CONSTRAINT `FK_observaciones_solicitudes_solicitudes` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla personal.observaciones_solicitudes: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `observaciones_solicitudes` DISABLE KEYS */;
INSERT INTO `observaciones_solicitudes` (`id`, `id_solicitud`, `id_autor`, `fecha`, `Observacion`) VALUES
	(2, 7, 12, '01-08-2023', 'observaciones sobre la solicitud'),
	(3, 7, 9, '01-08-2023', 'observacion numero 2');
/*!40000 ALTER TABLE `observaciones_solicitudes` ENABLE KEYS */;

-- Volcando estructura para tabla personal.parte_mensual
CREATE TABLE IF NOT EXISTS `parte_mensual` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_centro` smallint(5) unsigned NOT NULL DEFAULT '0',
  `doc_mas` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `doc_fem` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `tot_doc` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `anno` smallint(5) unsigned NOT NULL DEFAULT '0',
  `mes` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL,
  `dias_trab` tinyint(4) NOT NULL DEFAULT '0',
  `anno_ant` smallint(5) unsigned NOT NULL DEFAULT '0',
  `mes_ant` varchar(15) COLLATE utf8mb4_spanish_ci NOT NULL DEFAULT '0',
  `director` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK__centro` (`id_centro`),
  KEY `FK_parte_mensual_docente` (`director`),
  CONSTRAINT `FK__centro` FOREIGN KEY (`id_centro`) REFERENCES `centro` (`id_centro`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_parte_mensual_docente` FOREIGN KEY (`director`) REFERENCES `docente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla personal.parte_mensual: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `parte_mensual` DISABLE KEYS */;
/*!40000 ALTER TABLE `parte_mensual` ENABLE KEYS */;

-- Volcando estructura para tabla personal.solicitudes
CREATE TABLE IF NOT EXISTS `solicitudes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_centro` smallint(5) unsigned NOT NULL,
  `id_solicitante` int(11) DEFAULT NULL,
  `fecha_solicitud` varchar(10) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `id_tipo_solicitud` int(10) unsigned DEFAULT NULL,
  `causa` varchar(220) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `status` varchar(15) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `resolucion` varchar(300) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_solicitudes_centro` (`id_centro`),
  KEY `FK_solicitudes_designaciones` (`id_solicitante`),
  KEY `FK_solicitudes_operaciones` (`id_tipo_solicitud`),
  CONSTRAINT `FK_solicitudes_centro` FOREIGN KEY (`id_centro`) REFERENCES `centro` (`id_centro`) ON UPDATE CASCADE,
  CONSTRAINT `FK_solicitudes_designaciones` FOREIGN KEY (`id_solicitante`) REFERENCES `designaciones` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_solicitudes_operaciones` FOREIGN KEY (`id_tipo_solicitud`) REFERENCES `operaciones` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla personal.solicitudes: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `solicitudes` DISABLE KEYS */;
INSERT INTO `solicitudes` (`id`, `id_centro`, `id_solicitante`, `fecha_solicitud`, `id_tipo_solicitud`, `causa`, `status`, `resolucion`) VALUES
	(7, 8, 12, '01-08-2023', 2, 'registro de aÃ±umno', 'Aprobado', 'aprobada');
/*!40000 ALTER TABLE `solicitudes` ENABLE KEYS */;

-- Volcando estructura para tabla personal.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `usuario` varchar(25) COLLATE utf8mb4_spanish_ci NOT NULL,
  `clave` varchar(25) COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_docente` int(10) unsigned DEFAULT NULL,
  `autorizar_cc` varchar(2) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `FK_usuarios_docente` (`id_docente`),
  CONSTRAINT `FK_usuarios_docente` FOREIGN KEY (`id_docente`) REFERENCES `docente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Volcando datos para la tabla personal.usuarios: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`id_usuario`, `nombre`, `usuario`, `clave`, `id_docente`, `autorizar_cc`) VALUES
	(2, 'Amilcar Enrique Irias Aguilar', 'amilcar', '12345', 3, NULL);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
