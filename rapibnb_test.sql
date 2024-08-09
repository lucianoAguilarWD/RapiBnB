-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 12-11-2023 a las 23:18:32
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
-- Base de datos: `rapibnb_test`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `admiID` int(11) NOT NULL,
  `nombreUsuario` varchar(255) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `nombreCompleto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`admiID`, `nombreUsuario`, `contrasena`, `nombreCompleto`) VALUES
(1, 'administrador', '$2y$10$YVS/K138VJN5ROfv8RKY8eTZRsDhBM1akJalIbDHYX5Z4qd3MbSKe', 'RapiBnB');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aplicacion_a_oferta_alquiler`
--

CREATE TABLE `aplicacion_a_oferta_alquiler` (
  `aplicacionID` int(11) NOT NULL,
  `fechaAplico` timestamp NOT NULL DEFAULT current_timestamp(),
  `fechaInicio` date DEFAULT NULL,
  `fechaFin` date DEFAULT NULL,
  `estado` enum('Aceptado','Rechazado','Espera') DEFAULT NULL,
  `usuarioAplicoID` int(11) DEFAULT NULL,
  `ofertaAlquilerID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `aplicacion_a_oferta_alquiler`
--

INSERT INTO `aplicacion_a_oferta_alquiler` (`aplicacionID`, `fechaAplico`, `fechaInicio`, `fechaFin`, `estado`, `usuarioAplicoID`, `ofertaAlquilerID`) VALUES
(1, '2023-11-12 18:37:15', '2023-11-13', '2023-11-20', 'Aceptado', 4, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificacion`
--

CREATE TABLE `certificacion` (
  `certificacionID` int(11) NOT NULL,
  `documentoAdjunto` varchar(255) DEFAULT NULL,
  `usarioAVerfID` int(11) DEFAULT NULL,
  `fechaDeVencimiento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `interes`
--

CREATE TABLE `interes` (
  `interesID` int(11) NOT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `etiquetas` varchar(255) DEFAULT NULL,
  `listServicios` varchar(255) DEFAULT NULL,
  `userInteresesID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `interes`
--

INSERT INTO `interes` (`interesID`, `ubicacion`, `etiquetas`, `listServicios`, `userInteresesID`) VALUES
(1, 'Buenos Aires, Argentina', 'casa', 'gas, internet, electricidad', 4),
(2, '', 'cabana', 'gas, internet, electricidad, amoblado, estacionamiento', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oferta_de_alquiler`
--

CREATE TABLE `oferta_de_alquiler` (
  `ofertaID` int(11) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `etiquetas` varchar(255) DEFAULT NULL,
  `galeriaFotos` varchar(255) DEFAULT NULL,
  `listServicios` varchar(255) DEFAULT NULL,
  `costoAlquilerPorDia` decimal(10,2) DEFAULT NULL,
  `tiempoMinPermanencia` int(11) DEFAULT NULL,
  `tiempoMaxPermanencia` int(11) DEFAULT NULL,
  `cupo` int(11) DEFAULT NULL,
  `fechaInicio` date DEFAULT NULL,
  `fechaFin` date DEFAULT NULL,
  `creadorID` int(11) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `userVerificado` varchar(255) DEFAULT NULL,
  `fechaRegistro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `oferta_de_alquiler`
--

INSERT INTO `oferta_de_alquiler` (`ofertaID`, `titulo`, `descripcion`, `ubicacion`, `etiquetas`, `galeriaFotos`, `listServicios`, `costoAlquilerPorDia`, `tiempoMinPermanencia`, `tiempoMaxPermanencia`, `cupo`, `fechaInicio`, `fechaFin`, `creadorID`, `estado`, `userVerificado`, `fechaRegistro`) VALUES
(1, 'Vista Panorámica Residence', 'Experimenta la elegancia moderna en esta residencia de lujo con impresionantes vistas panorámicas. Cuatro habitaciones, piscina privada y acabados de alta gama.', 'Buenos Aires, Argentina', 'casa', '1699809138_655107725ed6a.jpg, 1699809138_655107725f08e.jpg, 1699809138_6551077263e95.jpg', 'gas, internet, electricidad, amoblado, estacionamiento', 15000.00, 30, 60, 10, '2023-11-12', '2024-01-31', 1, 'publicado', 'si', '2023-11-12 17:12:18'),
(2, 'Moderna Morada Urbana', 'Diseño contemporáneo en el corazón de la ciudad. Esta casa de dos pisos cuenta con características modernas, una cocina de chef y acceso conveniente a las atracciones locales.', 'Córdoba, Argentina', 'casa', '1699809522_655108f202a23.jpg, 1699809522_655108f202d33.jpg', 'gas, internet, electricidad, amoblado, estacionamiento', 8000.00, 10, 30, 6, '2023-11-12', '2024-04-30', 1, 'publicado', 'si', '2023-11-12 17:18:42'),
(3, 'Suite Jardín Encantado', 'Un apartamento independiente con un jardín encantador. Esta suite de un dormitorio ofrece privacidad, comodidades modernas y acceso a un hermoso jardín para relajarse.', 'Misiones, Argentina', 'departamento', '1699810035_65510af37a05d.jpg, 1699810035_65510af37a484.jpg', 'gas, internet, electricidad, amoblado, estacionamiento', 12000.00, 5, 10, 10, '2023-11-12', '2024-02-29', 1, 'publicado', 'si', '2023-11-12 17:27:15'),
(4, 'Refugio Sereno', 'Un refugio tranquilo con encanto rústico. Esta casa de campo ofrece una escapada relajante con una chimenea acogedora, cocina gourmet y un patio privado.', 'Formosa, Argentina', 'cabana', '1699810617_65510d39ed6bb.jpg', 'amoblado, estacionamiento', 5000.00, 1, 10, 2, '0000-00-00', '0000-00-00', 2, 'publicado', 'no', '2023-11-12 17:36:57'),
(5, 'Oasis Junto al Lago', ' Disfruta de la vida junto al agua en esta encantadora casa frente al lago. Con muelle privado, terraza con vista al lago y comodidades modernas, es perfecta para los amantes de la naturaleza.', 'Neuquén, Argentina', 'cabana', '1699811113_65510f29d7f55.jpg', 'gas, internet, electricidad, amoblado, estacionamiento', 15.00, 1, 10, 5, '0000-00-00', '0000-00-00', 2, 'publicado', 'si', '2023-11-12 17:45:13'),
(6, 'Casa del Sol', 'Encantadora casa de tres dormitorios con amplios espacios abiertos, mucha luz natural y un hermoso jardín. Ideal para familias que buscan comodidad y tranquilidad.', 'Mendoza, Argentina', 'cabana', '1699811645_6551113d76672.jpg', 'amoblado, estacionamiento', 4000.00, 1, 10, 4, '0000-00-00', '0000-00-00', 2, 'publicado', 'si', '2023-11-12 17:54:05'),
(7, 'Bosque Encantado', ' Sumérgete en la magia de la naturaleza en este encantador cottage rodeado de bosques. Ideal para los amantes de la paz y la serenidad, cuenta con una chimenea, senderos privados y comodidades modernas.', 'Buenos Aires', 'cabana', '1699812481_655114814834a.jpg, 1699812481_655114814d496.jpg', 'gas, internet, electricidad, amoblado, estacionamiento', 8000.00, 1, 12, 5, '0000-00-00', '0000-00-00', 4, 'publicado', 'no', '2023-11-12 17:58:24'),
(8, 'Apartamento Bohemio', ' Sumérgete en la creatividad en este apartamento bohemio lleno de colores y arte. Ubicado en el corazón del distrito artístico, ofrece una experiencia única para aquellos que buscan inspiración.', 'San Juan, Argentina', 'departamento', '1699812191_6551135f39a0f.jpg', 'gas, internet, electricidad, amoblado, estacionamiento', 1500.00, 30, 35, 5, '0000-00-00', '0000-00-00', 3, 'publicado', 'no', '2023-11-12 18:03:11'),
(9, 'Loft Urbano Chic', 'Experimenta el lujo urbano en este loft contemporáneo. Techos altos, grandes ventanales y una ubicación céntrica hacen de este espacio un lugar ideal para aquellos que buscan la vida moderna en la ciudad.', 'Córdoba, Argentina', 'departamento', '1699812305_655113d140a05.jpg, 1699812305_655113d140e15.jpg, 1699812305_655113d141360.jpg', 'gas, internet, electricidad, amoblado, estacionamiento', 10000.00, 1, 15, 5, '0000-00-00', '0000-00-00', 5, 'publicado', 'no', '2023-11-12 18:05:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `reservaID` int(11) NOT NULL,
  `textoReserva` varchar(255) DEFAULT NULL,
  `puntaje` int(11) DEFAULT NULL,
  `respuesta` varchar(255) DEFAULT NULL,
  `estado` enum('en curso','finalizada') DEFAULT NULL,
  `fechaRegistro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ofertaAlquilerID` int(11) DEFAULT NULL,
  `autorID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`reservaID`, `textoReserva`, `puntaje`, `respuesta`, `estado`, `fechaRegistro`, `ofertaAlquilerID`, `autorID`) VALUES
(1, NULL, NULL, NULL, 'en curso', '2023-11-12 19:08:45', 3, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuarioID` int(11) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `nombreUsuario` varchar(255) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `nombreCompleto` varchar(255) DEFAULT NULL,
  `fotoRostro` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) NOT NULL,
  `bio` varchar(255) DEFAULT NULL,
  `fechaRegistro` timestamp NOT NULL DEFAULT current_timestamp(),
  `documentacionID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuarioID`, `correo`, `nombreUsuario`, `contrasena`, `nombreCompleto`, `fotoRostro`, `telefono`, `bio`, `fechaRegistro`, `documentacionID`) VALUES
(1, 'lucho.aguilar.hl.38@gmail.com', 'lucho', '$2y$10$bsuSs2B5tpROjqasZP3m8ubobxsIXl.GEXJHOcRuYtfBVrZ5nBbki', 'Aguilar Luciano Ivan', 'user.png', '2664005897', 'Desarrollador web', '2023-11-12 16:06:44', NULL),
(2, 'pepe@gmail.com', 'pepe', '$2y$10$vlzd6qL4CI1tcATA9za.IuvPYlHUR5GvNgqBRY3Bq4ZaI5/oRwVoG', 'Pepe pepito', '1699810532_A 18861.jpg', '', 'CEO de PEPITO.INC', '2023-11-12 17:33:58', NULL),
(3, 'roman@gmail.com', 'Roman', '$2y$10$r/6r6ufk4gvsdQMjyYHStei7nhof9w1iwkolslbKSpi0lvep4gIFq', NULL, 'user.png', '', NULL, '2023-11-12 17:42:20', NULL),
(4, 'irina@gmail.com', 'Iri', '$2y$10$xHxS4s7H4.QQ2LtyAVTHDeWmSq7HIPaI7bewc0Ec3sNKJAzWhygBO', 'Irina Shayk', '1699811802_fotoCaraPrueba.png', '2664706483', 'Modelo de passarela, alta costura.', '2023-11-12 17:55:40', NULL),
(5, 'carlitos@gmail.com', 'carlos', '$2y$10$VQoplp0KFSTkFZJ.EOK/rOj1InJguVsHBdpqtMSGldoM51htE7FOq', NULL, 'user.png', '', NULL, '2023-11-12 18:04:05', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `verificacion_cuenta`
--

CREATE TABLE `verificacion_cuenta` (
  `verificacionID` int(11) NOT NULL,
  `fechaVencimiento` date DEFAULT NULL,
  `usuarioPropuestaID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `verificacion_cuenta`
--

INSERT INTO `verificacion_cuenta` (`verificacionID`, `fechaVencimiento`, `usuarioPropuestaID`) VALUES
(1, '2023-12-12', 1),
(2, '2023-12-12', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`admiID`);

--
-- Indices de la tabla `aplicacion_a_oferta_alquiler`
--
ALTER TABLE `aplicacion_a_oferta_alquiler`
  ADD PRIMARY KEY (`aplicacionID`),
  ADD KEY `ofertaAlquilerID` (`ofertaAlquilerID`),
  ADD KEY `usuarioAplicoID` (`usuarioAplicoID`);

--
-- Indices de la tabla `certificacion`
--
ALTER TABLE `certificacion`
  ADD PRIMARY KEY (`certificacionID`),
  ADD KEY `usarioAVerfID` (`usarioAVerfID`);

--
-- Indices de la tabla `interes`
--
ALTER TABLE `interes`
  ADD PRIMARY KEY (`interesID`),
  ADD KEY `userInteresesID` (`userInteresesID`);

--
-- Indices de la tabla `oferta_de_alquiler`
--
ALTER TABLE `oferta_de_alquiler`
  ADD PRIMARY KEY (`ofertaID`),
  ADD KEY `creadorID` (`creadorID`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`reservaID`),
  ADD KEY `ofertaAlquilerID` (`ofertaAlquilerID`),
  ADD KEY `autorID` (`autorID`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuarioID`),
  ADD KEY `fk_usuarios_certificacion` (`documentacionID`);

--
-- Indices de la tabla `verificacion_cuenta`
--
ALTER TABLE `verificacion_cuenta`
  ADD PRIMARY KEY (`verificacionID`),
  ADD KEY `usuarioPropuestaID` (`usuarioPropuestaID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `admiID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `aplicacion_a_oferta_alquiler`
--
ALTER TABLE `aplicacion_a_oferta_alquiler`
  MODIFY `aplicacionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `certificacion`
--
ALTER TABLE `certificacion`
  MODIFY `certificacionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `interes`
--
ALTER TABLE `interes`
  MODIFY `interesID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `oferta_de_alquiler`
--
ALTER TABLE `oferta_de_alquiler`
  MODIFY `ofertaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `reservaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuarioID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `verificacion_cuenta`
--
ALTER TABLE `verificacion_cuenta`
  MODIFY `verificacionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `aplicacion_a_oferta_alquiler`
--
ALTER TABLE `aplicacion_a_oferta_alquiler`
  ADD CONSTRAINT `aplicacion_a_oferta_alquiler_ibfk_1` FOREIGN KEY (`ofertaAlquilerID`) REFERENCES `oferta_de_alquiler` (`ofertaID`),
  ADD CONSTRAINT `aplicacion_a_oferta_alquiler_ibfk_2` FOREIGN KEY (`usuarioAplicoID`) REFERENCES `usuarios` (`usuarioID`);

--
-- Filtros para la tabla `certificacion`
--
ALTER TABLE `certificacion`
  ADD CONSTRAINT `certificacion_ibfk_1` FOREIGN KEY (`usarioAVerfID`) REFERENCES `usuarios` (`usuarioID`);

--
-- Filtros para la tabla `interes`
--
ALTER TABLE `interes`
  ADD CONSTRAINT `interes_ibfk_1` FOREIGN KEY (`userInteresesID`) REFERENCES `usuarios` (`usuarioID`);

--
-- Filtros para la tabla `oferta_de_alquiler`
--
ALTER TABLE `oferta_de_alquiler`
  ADD CONSTRAINT `oferta_de_alquiler_ibfk_1` FOREIGN KEY (`creadorID`) REFERENCES `usuarios` (`usuarioID`);

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`ofertaAlquilerID`) REFERENCES `oferta_de_alquiler` (`ofertaID`),
  ADD CONSTRAINT `reserva_ibfk_2` FOREIGN KEY (`autorID`) REFERENCES `usuarios` (`usuarioID`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_certificacion` FOREIGN KEY (`documentacionID`) REFERENCES `certificacion` (`certificacionID`);

--
-- Filtros para la tabla `verificacion_cuenta`
--
ALTER TABLE `verificacion_cuenta`
  ADD CONSTRAINT `verificacion_cuenta_ibfk_1` FOREIGN KEY (`usuarioPropuestaID`) REFERENCES `usuarios` (`usuarioID`);

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`lucho`@`localhost` EVENT `cambiar_estado_rentas` ON SCHEDULE EVERY 1 DAY STARTS '2023-11-12 12:42:29' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    UPDATE aplicacion_a_oferta_alquiler
    SET estado = 'Rechazado'
    WHERE estado = 'Espera' AND DATEDIFF(CURDATE(), fechaRegistro) >= 3;
END$$

CREATE DEFINER=`lucho`@`localhost` EVENT `eliminar_certificaciones_vencidas` ON SCHEDULE EVERY 1 HOUR STARTS '2023-10-29 19:46:42' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    UPDATE usuarios
    SET documentacionID = NULL
    WHERE documentacionID IN (SELECT certificacionID FROM certificacion WHERE fechaDeVencimiento <= CURDATE());

    DELETE FROM certificacion
    WHERE fechaDeVencimiento <= CURDATE();
END$$

CREATE DEFINER=`lucho`@`localhost` EVENT `ActualizarEstadoReservas` ON SCHEDULE EVERY 1 DAY STARTS '2023-11-12 12:44:33' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
  UPDATE reserva, aplicacion_a_oferta_alquiler
  SET reserva.estado = 'finalizada'
  WHERE reserva.ofertaAlquilerID = aplicacion_a_oferta_alquiler.ofertaAlquilerID
    AND aplicacion_a_oferta_alquiler.fechaFin <= CURDATE()
    AND reserva.estado = 'en curso'
    AND aplicacion_a_oferta_alquiler.estado = 'Aceptado';
END$$

CREATE DEFINER=`lucho`@`localhost` EVENT `cambiar_estado_ofertas` ON SCHEDULE EVERY 1 DAY STARTS '2023-11-12 12:44:50' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    UPDATE oferta_de_alquiler
    SET estado = 'publicado'
    WHERE estado = 'espera' AND DATEDIFF(CURDATE(), fechaRegistro) >= 3;
END$$

CREATE DEFINER=`lucho`@`localhost` EVENT `eliminar_verificaciones_vencidas` ON SCHEDULE EVERY 1 HOUR STARTS '2023-11-12 12:45:06' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    DELETE FROM verificacion_cuenta
    WHERE fechaVencimiento <= CURDATE();
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
