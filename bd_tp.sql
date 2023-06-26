-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-06-2023 a las 21:14:33
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_tp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `id_encuesta` int(5) NOT NULL,
  `id_mesa` int(5) NOT NULL,
  `puntuacionmozo` int(2) NOT NULL,
  `puntuacionRestaurante` int(2) NOT NULL,
  `puntuacionMesa` int(2) NOT NULL,
  `puntuacionCocinero` int(2) NOT NULL,
  `comentario` varchar(66) NOT NULL,
  `fechaEncuesta` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`id_encuesta`, `id_mesa`, `puntuacionmozo`, `puntuacionRestaurante`, `puntuacionMesa`, `puntuacionCocinero`, `comentario`, `fechaEncuesta`) VALUES
(2, 10001, 10, 10, 9, 9, 'La comida estuvo excelente', '2023-06-19 19:02:38'),
(3, 10003, 10, 10, 9, 9, 'La comida estuvo excelente', '2023-06-25 21:25:27'),
(4, 10003, 10, 10, 9, 9, 'La comida estuvo excelente', '2023-06-25 23:22:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id_mesa` int(5) NOT NULL,
  `numeroMesa` int(2) NOT NULL,
  `usuario_mozo` varchar(30) NOT NULL,
  `nombreCliente` varchar(30) NOT NULL,
  `estado` varchar(30) NOT NULL,
  `cantidadComensales` int(2) NOT NULL,
  `ruta_foto` varchar(70) DEFAULT NULL,
  `importeTotal` float DEFAULT NULL,
  `fechaApertura` datetime NOT NULL,
  `fechaCierre` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id_mesa`, `numeroMesa`, `usuario_mozo`, `nombreCliente`, `estado`, `cantidadComensales`, `ruta_foto`, `importeTotal`, `fechaApertura`, `fechaCierre`) VALUES
(10004, 19, 'petu_moza', 'maia', 'con cliente esperando pedido', 3, NULL, 0, '2023-06-26 15:51:26', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` varchar(5) NOT NULL,
  `id_producto` int(10) NOT NULL,
  `id_mesa` int(5) NOT NULL,
  `estado` varchar(30) NOT NULL,
  `cantidadProducto` int(2) NOT NULL,
  `tiempoEstimado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_producto`, `id_mesa`, `estado`, `cantidadProducto`, `tiempoEstimado`) VALUES
('F85FI', 1, 10001, 'entregado', 3, '2023-06-19 00:21:45'),
('XBCEA', 1, 10003, 'entregado', 3, '2023-06-25 21:45:52'),
('RX8V1', 1, 10003, 'en preparacion', 1, '2023-06-26 16:14:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(5) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `tiempo_preparacion` int(3) NOT NULL,
  `sector_preparacion` varchar(30) NOT NULL,
  `precio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `tiempo_preparacion`, `sector_preparacion`, `precio`) VALUES
(1, 'papas fritas', 30, 'cocina', 1000),
(2, 'Cerveza Brahma', 5, 'barra_cerveza', 800),
(3, 'Pastel De Papa', 90, 'cocina', 1000),
(4, 'milanesa', 60, 'cocina', 1500);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(4) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `dni` int(8) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `sector` varchar(30) NOT NULL,
  `puesto` varchar(30) NOT NULL,
  `fecha_de_contratacion` date NOT NULL,
  `fecha_de_egreso` date DEFAULT NULL,
  `usuario` varchar(30) NOT NULL,
  `clave` int(8) NOT NULL,
  `estado` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `dni`, `fecha_nacimiento`, `sector`, `puesto`, `fecha_de_contratacion`, `fecha_de_egreso`, `usuario`, `clave`, `estado`) VALUES
(6, 'jeremias', 'parziale', 42839805, '2000-12-11', 'socio', 'socio', '2019-01-06', NULL, 'jeremias42839805', 42839805, 1),
(7, 'maia', 'parziale', 42839805, '2000-12-11', 'cocina', 'cocinero', '2019-01-06', NULL, 'maia_cocinera', 42839805, 1),
(8, 'oriana', 'godoy', 42839805, '2000-12-11', 'barra_cerveza', 'cervecero', '2019-01-06', NULL, 'oriana_cervecera', 42839805, 1),
(9, 'eduardo', 'parziale', 42839805, '2000-12-11', 'barra_tragos_vinos', 'bartender', '2019-01-06', NULL, 'edu_bartender', 42839805, 1),
(10, 'natalia', 'caballero', 42839805, '2000-12-11', 'candybar', 'pastelero', '2019-01-06', NULL, 'naty_pastelera', 42839805, 1),
(12, 'javi', 'rondinelli', 42839805, '2000-12-11', 'cocina', 'cocinero', '2019-01-06', NULL, 'javi_cocinero', 42839805, 1),
(26, 'petunia', 'godoy', 42839805, '2000-12-11', 'salon', 'mozo', '2019-01-06', NULL, 'petu_moza', 42839805, 1),
(29, 'petunia', 'godoy', 42839805, '2000-12-11', 'salon', 'mozo', '2019-01-06', NULL, 'petu_mozaa', 42839805, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`id_encuesta`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id_mesa`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `id_encuesta` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id_mesa` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10005;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
