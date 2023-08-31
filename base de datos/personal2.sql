-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-08-2023 a las 05:30:04
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `personal2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centro`
--

CREATE TABLE `centro` (
  `id_centro` smallint(5) UNSIGNED NOT NULL,
  `Codigo_centro` varchar(13) NOT NULL,
  `Nombre` varchar(70) NOT NULL,
  `Municipio` varchar(20) NOT NULL,
  `Direccion` varchar(190) NOT NULL,
  `Tipo_centro` varchar(10) NOT NULL,
  `Telefono` varchar(15) NOT NULL DEFAULT '',
  `N_acuerdo` varchar(15) NOT NULL DEFAULT '',
  `estatus` varchar(30) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `acuerdo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `designaciones`
--

CREATE TABLE `designaciones` (
  `id` int(11) NOT NULL,
  `id_docente` int(10) UNSIGNED NOT NULL,
  `id_centro` smallint(5) UNSIGNED DEFAULT NULL,
  `id_direccion` int(10) UNSIGNED DEFAULT NULL,
  `puesto` varchar(30) NOT NULL DEFAULT '',
  `condicion` varchar(12) NOT NULL DEFAULT '',
  `estatus` varchar(12) NOT NULL DEFAULT '',
  `horas` varchar(4) DEFAULT '',
  `fasignacion` varchar(10) NOT NULL DEFAULT '',
  `fvencimiento` varchar(10) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_operaciones`
--

CREATE TABLE `detalles_operaciones` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_operacion` int(10) UNSIGNED DEFAULT NULL,
  `campo` varchar(50) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `valores` varchar(50) DEFAULT NULL,
  `descripcion` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_parte_mensual`
--

CREATE TABLE `detalle_parte_mensual` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_parte_mensual` int(10) UNSIGNED NOT NULL,
  `grado` varchar(10) DEFAULT NULL,
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
  `traslados_tot` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_solicitud`
--

CREATE TABLE `detalle_solicitud` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_solicitud` int(10) UNSIGNED DEFAULT NULL,
  `campo` varchar(20) DEFAULT NULL,
  `valor` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones`
--

CREATE TABLE `direcciones` (
  `id` int(10) UNSIGNED NOT NULL,
  `departamento` varchar(25) NOT NULL DEFAULT '0',
  `municipio` varchar(50) NOT NULL DEFAULT '0',
  `ubicacion` varchar(150) NOT NULL DEFAULT '0',
  `email` varchar(50) NOT NULL DEFAULT '0',
  `telefono` varchar(15) NOT NULL DEFAULT '0',
  `id_docente` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente`
--

CREATE TABLE `docente` (
  `id` int(10) UNSIGNED NOT NULL,
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
  `edo_mail` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente_parte_mensual`
--

CREATE TABLE `docente_parte_mensual` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_parte_mensual` int(10) UNSIGNED NOT NULL,
  `numero` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `nombre` varchar(50) DEFAULT NULL,
  `cargo` varchar(50) DEFAULT NULL,
  `grado` varchar(10) DEFAULT NULL,
  `n_alumnos` int(11) DEFAULT NULL,
  `inasistencia_autorizadas` int(11) DEFAULT NULL,
  `inasistencias_autorizadas` int(11) DEFAULT NULL,
  `total_inasistencia` int(11) DEFAULT NULL,
  `Observaciones` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estructura_presupuestaria`
--

CREATE TABLE `estructura_presupuestaria` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_designacion` int(11) NOT NULL,
  `dependencia` varchar(15) DEFAULT NULL,
  `departamento` varchar(50) DEFAULT NULL,
  `municipio` varchar(50) DEFAULT NULL,
  `cod_centro` varchar(50) DEFAULT NULL,
  `cod_plaza` varchar(50) DEFAULT NULL,
  `horas` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas_solicitudes`
--

CREATE TABLE `notas_solicitudes` (
  `id` int(10) UNSIGNED NOT NULL,
  `fecha` varchar(10) NOT NULL DEFAULT '0',
  `autor` varchar(10) NOT NULL DEFAULT '0',
  `nota` varchar(200) NOT NULL DEFAULT '0',
  `id_solicitud` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `observaciones_solicitudes`
--

CREATE TABLE `observaciones_solicitudes` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_solicitud` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `id_autor` int(10) NOT NULL DEFAULT 0,
  `fecha` varchar(10) NOT NULL DEFAULT '0',
  `Observacion` varchar(200) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operaciones`
--

CREATE TABLE `operaciones` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre_proceso` varchar(30) DEFAULT NULL,
  `descripcion_proceso` varchar(450) DEFAULT NULL,
  `link_plantilla` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parte_mensual`
--

CREATE TABLE `parte_mensual` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_centro` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `doc_mas` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `doc_fem` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `tot_doc` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `anno` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `mes` varchar(15) NOT NULL,
  `dias_trab` tinyint(4) NOT NULL DEFAULT 0,
  `anno_ant` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `mes_ant` varchar(15) NOT NULL DEFAULT '0',
  `director` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_centro` smallint(5) UNSIGNED NOT NULL,
  `id_solicitante` int(11) DEFAULT NULL,
  `fecha_solicitud` varchar(10) DEFAULT NULL,
  `id_tipo_solicitud` int(10) UNSIGNED DEFAULT NULL,
  `causa` varchar(220) DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  `resolucion` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `usuario` varchar(25) NOT NULL,
  `clave` varchar(25) NOT NULL,
  `id_docente` int(10) UNSIGNED DEFAULT NULL,
  `autorizar_cc` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `centro`
--
ALTER TABLE `centro`
  ADD PRIMARY KEY (`id_centro`),
  ADD UNIQUE KEY `Índice 1` (`Codigo_centro`);

--
-- Indices de la tabla `designaciones`
--
ALTER TABLE `designaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_designaciones_docente` (`id_docente`),
  ADD KEY `FK_designaciones_centro` (`id_centro`),
  ADD KEY `FK_designaciones_direcciones` (`id_direccion`);

--
-- Indices de la tabla `detalles_operaciones`
--
ALTER TABLE `detalles_operaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_detalles_operaciones_operaciones` (`id_operacion`);

--
-- Indices de la tabla `detalle_parte_mensual`
--
ALTER TABLE `detalle_parte_mensual`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_detalle_parte_mensual_parte_mensual` (`id_parte_mensual`);

--
-- Indices de la tabla `detalle_solicitud`
--
ALTER TABLE `detalle_solicitud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__solicitudes` (`id_solicitud`);

--
-- Indices de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_direcciones_docente` (`id_docente`);

--
-- Indices de la tabla `docente`
--
ALTER TABLE `docente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `docente_parte_mensual`
--
ALTER TABLE `docente_parte_mensual`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__parte_mensual` (`id_parte_mensual`);

--
-- Indices de la tabla `estructura_presupuestaria`
--
ALTER TABLE `estructura_presupuestaria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_estructura_presupuestaria_designaciones` (`id_designacion`);

--
-- Indices de la tabla `notas_solicitudes`
--
ALTER TABLE `notas_solicitudes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_notas_solicitudes_solicitudes` (`id_solicitud`);

--
-- Indices de la tabla `observaciones_solicitudes`
--
ALTER TABLE `observaciones_solicitudes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_observaciones_solicitudes_solicitudes` (`id_solicitud`),
  ADD KEY `FK_observaciones_solicitudes_designaciones` (`id_autor`);

--
-- Indices de la tabla `operaciones`
--
ALTER TABLE `operaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `parte_mensual`
--
ALTER TABLE `parte_mensual`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK__centro` (`id_centro`),
  ADD KEY `FK_parte_mensual_docente` (`director`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_solicitudes_centro` (`id_centro`),
  ADD KEY `FK_solicitudes_designaciones` (`id_solicitante`),
  ADD KEY `FK_solicitudes_operaciones` (`id_tipo_solicitud`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `FK_usuarios_docente` (`id_docente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `centro`
--
ALTER TABLE `centro`
  MODIFY `id_centro` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `designaciones`
--
ALTER TABLE `designaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalles_operaciones`
--
ALTER TABLE `detalles_operaciones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_parte_mensual`
--
ALTER TABLE `detalle_parte_mensual`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_solicitud`
--
ALTER TABLE `detalle_solicitud`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `docente`
--
ALTER TABLE `docente`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `docente_parte_mensual`
--
ALTER TABLE `docente_parte_mensual`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estructura_presupuestaria`
--
ALTER TABLE `estructura_presupuestaria`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notas_solicitudes`
--
ALTER TABLE `notas_solicitudes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `observaciones_solicitudes`
--
ALTER TABLE `observaciones_solicitudes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `operaciones`
--
ALTER TABLE `operaciones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `parte_mensual`
--
ALTER TABLE `parte_mensual`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `designaciones`
--
ALTER TABLE `designaciones`
  ADD CONSTRAINT `FK_designaciones_centro` FOREIGN KEY (`id_centro`) REFERENCES `centro` (`id_centro`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_designaciones_direcciones` FOREIGN KEY (`id_direccion`) REFERENCES `direcciones` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_designaciones_docente` FOREIGN KEY (`id_docente`) REFERENCES `docente` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_operaciones`
--
ALTER TABLE `detalles_operaciones`
  ADD CONSTRAINT `FK_detalles_operaciones_operaciones` FOREIGN KEY (`id_operacion`) REFERENCES `operaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_parte_mensual`
--
ALTER TABLE `detalle_parte_mensual`
  ADD CONSTRAINT `FK_detalle_parte_mensual_parte_mensual` FOREIGN KEY (`id_parte_mensual`) REFERENCES `parte_mensual` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_solicitud`
--
ALTER TABLE `detalle_solicitud`
  ADD CONSTRAINT `FK__solicitudes` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD CONSTRAINT `FK_direcciones_docente` FOREIGN KEY (`id_docente`) REFERENCES `docente` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `docente_parte_mensual`
--
ALTER TABLE `docente_parte_mensual`
  ADD CONSTRAINT `FK__parte_mensual` FOREIGN KEY (`id_parte_mensual`) REFERENCES `parte_mensual` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estructura_presupuestaria`
--
ALTER TABLE `estructura_presupuestaria`
  ADD CONSTRAINT `FK_estructura_presupuestaria_designaciones` FOREIGN KEY (`id_designacion`) REFERENCES `designaciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `notas_solicitudes`
--
ALTER TABLE `notas_solicitudes`
  ADD CONSTRAINT `FK_notas_solicitudes_solicitudes` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `observaciones_solicitudes`
--
ALTER TABLE `observaciones_solicitudes`
  ADD CONSTRAINT `FK_observaciones_solicitudes_designaciones` FOREIGN KEY (`id_autor`) REFERENCES `designaciones` (`id`),
  ADD CONSTRAINT `FK_observaciones_solicitudes_solicitudes` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `parte_mensual`
--
ALTER TABLE `parte_mensual`
  ADD CONSTRAINT `FK__centro` FOREIGN KEY (`id_centro`) REFERENCES `centro` (`id_centro`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_parte_mensual_docente` FOREIGN KEY (`director`) REFERENCES `docente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `FK_solicitudes_centro` FOREIGN KEY (`id_centro`) REFERENCES `centro` (`id_centro`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_solicitudes_designaciones` FOREIGN KEY (`id_solicitante`) REFERENCES `designaciones` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_solicitudes_operaciones` FOREIGN KEY (`id_tipo_solicitud`) REFERENCES `operaciones` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `FK_usuarios_docente` FOREIGN KEY (`id_docente`) REFERENCES `docente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
