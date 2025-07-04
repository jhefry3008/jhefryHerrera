-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 04-07-2025 a las 20:50:15
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
-- Base de datos: `visor`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_libros`
--

CREATE TABLE `cliente_libros` (
  `cliente_id` int(11) NOT NULL,
  `libro_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente_libros`
--

INSERT INTO `cliente_libros` (`cliente_id`, `libro_id`) VALUES
(47, 31),
(47, 33),
(47, 34),
(48, 29),
(48, 31),
(48, 33),
(48, 35);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `contenido` text NOT NULL,
  `visible_para_cliente` tinyint(1) DEFAULT 0,
  `pdf_url` varchar(255) DEFAULT NULL,
  `portada_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `titulo`, `contenido`, `visible_para_cliente`, `pdf_url`, `portada_url`) VALUES
(29, 'Harry Potter y la piedra filosofal', 'Harry descubre que es un mago y empieza su primer año en Hogwarts, donde enfrenta el misterio de una piedra mágica.', 0, 'uploads/pdf/Harry_Potter_y_la_Piedra_Filosofal-J_K_Rowling.pdf', 'uploads/portadas/978958523404.gif'),
(30, 'Harry Potter y la cámara secreta', 'En su segundo año, Harry investiga ataques misteriosos en la escuela y descubre una cámara oculta bajo Hogwarts.', 0, 'uploads/pdf/HARRY POTTER Y LA CAMARA SECRETA.pdf', 'uploads/portadas/1a1b975ace9d300841c305826a644bea.webp'),
(31, 'Harry Potter y el prisionero de Azkaban', 'Harry se entera de la fuga de Sirius Black, un peligroso prisionero que parece tener conexión con su pasado.', 0, 'uploads/pdf/Harry_Potter_y_El_Prisionero_de_Azkaban_03.pdf', 'uploads/portadas/1a3145c3ba83287fabdb65514b893cf6.webp'),
(32, 'Harry Potter y el cáliz de fuego', 'Durante el Torneo de los Tres Magos, Harry es forzado a competir y se enfrenta al regreso de Lord Voldemort.\r\n\r\n', 0, 'uploads/pdf/HARRY POTTER Y EL CALIZ DE FUEGO.pdf', 'uploads/portadas/images.jfif'),
(33, 'Harry Potter y la Orden del Fénix', 'Harry lidia con el Ministerio de Magia, forma un grupo secreto de defensa y lucha contra la creciente oscuridad.', 0, 'uploads/pdf/Rowling, Joanne K.- 5. Harry Potter y la Orden del Fenix.doc.pdf', 'uploads/portadas/a0b46533ad541c885d6a406da0e9c429.webp'),
(34, 'Harry Potter y el misterio del príncipe', 'Harry descubre secretos del pasado de Voldemort mientras se prepara para el enfrentamiento final.', 0, 'uploads/pdf/Harry-Potter-y-el-misterio-del-príncipe-J.K-Rowling_.pdf', 'uploads/portadas/acd22d20d3bd39e68e81b69ebcbe4c54.webp'),
(35, 'Harry Potter y las Reliquias de la Muerte', 'En la última batalla, Harry, Ron y Hermione buscan destruir los horrocruxes de Voldemort para acabar con él de una vez por todas.', 0, 'uploads/pdf/Harry-Potter-y-las-reliquias-de-la-muerte-J.K-Rowling_.pdf', 'uploads/portadas/Portada---HARRY-POTTER-7-Y-LAS-RELIQUIAS-DE-LA-MUERTE.webp');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('cliente','admin') NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `nombre_cliente` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fecha_registro` date NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `contrasena`, `rol`, `telefono`, `nombre_cliente`, `email`, `fecha_registro`, `estado`) VALUES
(40, 'JhefryH', '$2y$10$QizQqDsWgSs9aN9dWfzcoOyIjr205O6Ww8AdIlKbmEAtnOHhiYAXm', 'admin', '3157762008', 'Jhefry Herrera', 'jhefryherrera3008@gmail.com', '2024-07-21', 'activo'),
(42, 'alejandrob', '$2y$10$pV61a8vcbjH079tbc3FrR.0bGteSYtrIy0oK14j0wyh1M5wclZI8G', 'cliente', '3157762008', 'Alejandro Beltran', 'jhefrygerador.herrera@gmail.com', '2025-04-12', 'activo'),
(46, 'admin', '$2y$10$nJqX45LJgI8/FuI.jyV0VeK7dJmIQdAHfc3UFZhNnBR84tkAk7kJi', 'admin', '987654321', 'administrador sistema', 'administrador@gmail.com', '2025-07-03', 'activo'),
(47, 'clien', '$2y$10$gwR2LZZfBlAh7t4X4s4BMOzKFk4PZ44YrGFXKxEjIUb1w850k5jS.', 'cliente', '321456789', 'cliente sistema', 'cliente@cliente.com', '2025-07-03', 'activo'),
(48, 'cleint2', '$2y$10$S8A8EkYMxBmmb68GJvyB/eS7EmIqv2JBSHimtO9qB7/qm37uvQ3/2', 'cliente', '987654321', 'cliente sistema 2', 'cliente2@cliente.com', '2025-07-03', 'activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente_libros`
--
ALTER TABLE `cliente_libros`
  ADD PRIMARY KEY (`cliente_id`,`libro_id`),
  ADD KEY `libro_id` (`libro_id`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente_libros`
--
ALTER TABLE `cliente_libros`
  ADD CONSTRAINT `cliente_libros_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `cliente_libros_ibfk_2` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
