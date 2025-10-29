-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-10-2025 a las 00:12:37
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hs_foodexpress_db`
--
CREATE DATABASE IF NOT EXISTS `hs_foodexpress_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `hs_foodexpress_db`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hs_categorias`
--

DROP TABLE IF EXISTS `hs_categorias`;
CREATE TABLE `hs_categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `hs_categorias`
--

INSERT INTO `hs_categorias` (`id`, `nombre`, `slug`) VALUES
(1, 'Pizzas', 'pizzas'),
(2, 'Bebidas', 'bebidas'),
(3, 'Postres', 'postres'),
(4, 'Empanadas', 'empanadas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hs_detalle_pedido`
--

DROP TABLE IF EXISTS `hs_detalle_pedido`;
CREATE TABLE `hs_detalle_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `nombre_producto` varchar(150) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hs_pedidos`
--

DROP TABLE IF EXISTS `hs_pedidos`;
CREATE TABLE `hs_pedidos` (
  `id` int(11) NOT NULL,
  `nombre_cliente` varchar(120) NOT NULL,
  `telefono` varchar(40) NOT NULL,
  `direccion` varchar(180) NOT NULL,
  `notas` text DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hs_productos`
--

DROP TABLE IF EXISTS `hs_productos`;
CREATE TABLE `hs_productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `hs_productos`
--

INSERT INTO `hs_productos` (`id`, `nombre`, `descripcion`, `precio`, `imagen`, `categoria_id`, `activo`) VALUES
(1, 'Pizza Muzarella', 'Muzarella, aceitunas, orégano', 6000.00, 'hs_assets/hs_pizza_muza.jpg', 1, 1),
(2, 'Pizza Napolitana', 'Muzarella, tomate en rodajas, ajo, albahaca', 7000.00, 'hs_assets/hs_pizza_napo.jpg', 1, 1),
(3, 'Pizza Especial', 'Jamón, morrón, aceitunas', 7500.00, 'hs_assets/hs_pizza_especial.jpg', 1, 1),
(4, 'Pizza Fugazzeta', 'Queso y cebolla', 7200.00, 'hs_assets/hs_pizza_fugazzeta.jpg', 1, 1),
(5, 'Gaseosa 500ml', 'Sabor cola', 1500.00, 'hs_assets/hs_bebida_cola.jpg', 2, 1),
(6, 'Agua Mineral 500ml', 'Sin gas', 1200.00, 'hs_assets/hs_agua.jpg', 2, 1),
(7, 'Cerveza Lata', 'Rubia 473ml', 2200.00, 'hs_assets/hs_cerveza.jpg', 2, 1),
(8, 'Flan Casero', 'Con dulce de leche', 2500.00, 'hs_assets/hs_flan.jpg', 3, 1),
(9, 'Helado 1/4 kg', 'Dos sabores', 3200.00, 'hs_assets/hs_helado.jpg', 3, 1),
(10, 'Empanada Carne', 'Carne cortada a cuchillo', 1000.00, 'hs_assets/hs_emp_carne.jpg', 4, 1),
(11, 'Empanada JyQ', 'Jamón y queso', 1000.00, 'hs_assets/hs_emp_jyq.jpg', 4, 1),
(12, 'Empanada Humita', 'Choclo y salsa blanca', 1000.00, 'hs_assets/hs_emp_humita.jpg', 4, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `hs_categorias`
--
ALTER TABLE `hs_categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `hs_detalle_pedido`
--
ALTER TABLE `hs_detalle_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `hs_pedidos`
--
ALTER TABLE `hs_pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `hs_productos`
--
ALTER TABLE `hs_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `hs_categorias`
--
ALTER TABLE `hs_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `hs_detalle_pedido`
--
ALTER TABLE `hs_detalle_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `hs_pedidos`
--
ALTER TABLE `hs_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `hs_productos`
--
ALTER TABLE `hs_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `hs_detalle_pedido`
--
ALTER TABLE `hs_detalle_pedido`
  ADD CONSTRAINT `hs_detalle_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `hs_pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hs_detalle_pedido_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `hs_productos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `hs_productos`
--
ALTER TABLE `hs_productos`
  ADD CONSTRAINT `hs_productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `hs_categorias` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
