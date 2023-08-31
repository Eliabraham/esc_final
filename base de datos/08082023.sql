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

-- Volcando datos para la tabla personal.centro: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `centro` DISABLE KEYS */;
INSERT INTO `centro` (`id_centro`, `Codigo_centro`, `Nombre`, `Municipio`, `Direccion`, `Tipo_centro`, `Telefono`, `N_acuerdo`, `estatus`, `logo`, `acuerdo`) VALUES
	(8, '180600036M02', 'Republica de Cuba', '4', 'Nueva Esperanza', 'Media', '26911212', '0', 'Activo', 'view/img/escuelas/phpFC40.png', 'view/pdf/escuelas/phpFB16.pdf'),
	(9, '1807000027B1', 'Dionicio Herrera', '5', 'barrio el Centro', 'Prebasica', '26911322', '0', 'Activo', 'view/img/escuelas/php499C.png', 'view/pdf/escuelas/php498B.pdf');
/*!40000 ALTER TABLE `centro` ENABLE KEYS */;

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

-- Volcando datos para la tabla personal.detalles_operaciones: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `detalles_operaciones` DISABLE KEYS */;
INSERT INTO `detalles_operaciones` (`id`, `id_operacion`, `campo`, `tipo`, `valores`, `descripcion`) VALUES
	(5, 2, 'identidad del alumno', 'texto', '', ''),
	(6, 2, 'nombre del Alumno', 'texto', '', ''),
	(7, 2, 'plantilla', 'archivo', '', '');
/*!40000 ALTER TABLE `detalles_operaciones` ENABLE KEYS */;

-- Volcando datos para la tabla personal.detalle_parte_mensual: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `detalle_parte_mensual` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_parte_mensual` ENABLE KEYS */;

-- Volcando datos para la tabla personal.detalle_solicitud: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `detalle_solicitud` DISABLE KEYS */;
INSERT INTO `detalle_solicitud` (`id`, `id_solicitud`, `campo`, `valor`) VALUES
	(13, 7, 'identidad_del_alumno', '1111-1111-11111'),
	(14, 7, 'nombre_del_Alumno', 'alumnp de Prueba'),
	(15, 7, 'plantilla', 'view/tramites/7/phpBD70.pdf');
/*!40000 ALTER TABLE `detalle_solicitud` ENABLE KEYS */;

-- Volcando datos para la tabla personal.direcciones: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `direcciones` DISABLE KEYS */;
INSERT INTO `direcciones` (`id`, `departamento`, `municipio`, `ubicacion`, `email`, `telefono`, `id_docente`) VALUES
	(4, 'Yoro', 'Morazan', 'barrio el centro', 'yoro.morazan@se.gob.hm', '94755043', NULL),
	(5, 'Yoro', 'Yorito', 'frente al parque central', 'yoro.yorito@se.gob.hm', '97917029', NULL);
/*!40000 ALTER TABLE `direcciones` ENABLE KEYS */;

-- Volcando datos para la tabla personal.docente: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `docente` DISABLE KEYS */;
INSERT INTO `docente` (`id`, `Identidad`, `Nombre1`, `Nombre2`, `Apellido1`, `Apellido2`, `Escalafon`, `Imprema`, `Telefono`, `Correo`, `Foto`, `Status`, `sexo`, `fecha_nacimeito`, `titulo`, `edo_mail`) VALUES
	(3, '1804-1990-01507', 'Amilcar', 'Enrique', 'Irias', 'Aguilar', 'PTI00296', '1804-1990-01507', '94755043', 'amilcaririas@gmail.com', 'view/img/docentes/php4466.jpeg', 'activo', 'masculino', '1990-03-24', 'Licenciado En InformÃ¡tica Educativa', 'pendiente'),
	(4, '1111-1111-11111', 'Pnombre', 'Snombre', 'Papellido', 'Sapellido', 'escalafon', 'imprema', '9999999999', 'correo@gmail.com', 'view/img/docentes/php1CF9.jpeg', 'activo', 'masculino', '1999-10-09', 'titulo', 'pendiente');
/*!40000 ALTER TABLE `docente` ENABLE KEYS */;

-- Volcando datos para la tabla personal.docente_parte_mensual: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `docente_parte_mensual` DISABLE KEYS */;
/*!40000 ALTER TABLE `docente_parte_mensual` ENABLE KEYS */;

-- Volcando datos para la tabla personal.estructura_presupuestaria: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `estructura_presupuestaria` DISABLE KEYS */;
INSERT INTO `estructura_presupuestaria` (`id`, `id_designacion`, `dependencia`, `departamento`, `municipio`, `cod_centro`, `cod_plaza`, `horas`) VALUES
	(1, 12, 'Gubernamental', 'Yoro', 'Morazan', '18', '501', '36');
/*!40000 ALTER TABLE `estructura_presupuestaria` ENABLE KEYS */;

-- Volcando datos para la tabla personal.notas_solicitudes: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `notas_solicitudes` DISABLE KEYS */;
/*!40000 ALTER TABLE `notas_solicitudes` ENABLE KEYS */;

-- Volcando datos para la tabla personal.observaciones_solicitudes: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `observaciones_solicitudes` DISABLE KEYS */;
INSERT INTO `observaciones_solicitudes` (`id`, `id_solicitud`, `id_autor`, `fecha`, `Observacion`) VALUES
	(2, 7, 12, '01-08-2023', 'observaciones sobre la solicitud'),
	(3, 7, 9, '01-08-2023', 'observacion numero 2');
/*!40000 ALTER TABLE `observaciones_solicitudes` ENABLE KEYS */;

-- Volcando datos para la tabla personal.operaciones: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `operaciones` DISABLE KEYS */;
INSERT INTO `operaciones` (`id`, `nombre_proceso`, `descripcion_proceso`, `link_plantilla`) VALUES
	(2, 'Matricula Sin RestricciÃ³n', 'Matricular Alumno Com Problemas de registro de aÃ±os anteriores', 'https://drive.google.com/file/d/1MV_LNXR1U6DV75MRhDRMfPjVVczEYras/view?usp=drive_link');
/*!40000 ALTER TABLE `operaciones` ENABLE KEYS */;

-- Volcando datos para la tabla personal.parte_mensual: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `parte_mensual` DISABLE KEYS */;
/*!40000 ALTER TABLE `parte_mensual` ENABLE KEYS */;

-- Volcando datos para la tabla personal.solicitudes: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `solicitudes` DISABLE KEYS */;
INSERT INTO `solicitudes` (`id`, `id_centro`, `id_solicitante`, `fecha_solicitud`, `id_tipo_solicitud`, `causa`, `status`, `resolucion`) VALUES
	(7, 8, 12, '01-08-2023', 2, 'registro de aÃ±umno', 'Aprobado', 'aprobada');
/*!40000 ALTER TABLE `solicitudes` ENABLE KEYS */;

-- Volcando datos para la tabla personal.usuarios: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` (`id_usuario`, `nombre`, `usuario`, `clave`, `id_docente`, `autorizar_cc`) VALUES
	(2, 'Amilcar Enrique Irias Aguilar', 'amilcar', '12345', 3, NULL);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
