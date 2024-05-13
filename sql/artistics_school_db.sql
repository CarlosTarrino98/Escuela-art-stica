-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-03-2024 a las 15:35:46
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
-- Base de datos: `artistics_school_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consultations`
--

CREATE TABLE `consultations` (
  `consultation_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consultation_responses`
--

CREATE TABLE `consultation_responses` (
  `response_id` int(11) NOT NULL,
  `consultation_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `response_date` datetime NOT NULL DEFAULT current_timestamp(),
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `profesor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `courses`
--

INSERT INTO `courses` (`course_id`, `course_name`, `cover_image`, `profesor_id`) VALUES
(25, 'Dibujo y Pintura', '65f093e229507-pintura.jpg', 31),
(26, 'Escultura', '65f093d29a769-escultura.jpg', 32),
(27, 'Música y Artes Escénicas', '65f093c625e91-musica.jpg', 34),
(28, 'Diseño Gráfico y Multimedia', '65f093af50761-diseño-grafico.jpg', 33);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `forum_replies`
--

CREATE TABLE `forum_replies` (
  `reply_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `reply_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `forum_replies`
--

INSERT INTO `forum_replies` (`reply_id`, `topic_id`, `user_id`, `content`, `reply_date`) VALUES
(8, 29, 16, 'Contenido de la respuesta', '2024-03-15 09:46:25'),
(12, 29, 16, 'Contenido de la respuesta 2', '2024-03-15 09:57:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `forum_topics`
--

CREATE TABLE `forum_topics` (
  `topic_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `forum_topics`
--

INSERT INTO `forum_topics` (`topic_id`, `course_id`, `user_id`, `title`, `content`, `creation_date`) VALUES
(27, 25, 16, 'Tema de Dibujo y Pintura', 'Contenido del Tema de Dibujo y Pintura', '2024-03-14 16:50:18'),
(28, 26, 16, 'Tema de Escultura', 'Contenido del Tema de Escultura', '2024-03-14 17:01:24'),
(29, 27, 1, 'Tema de Música y artes Escénicas', 'Contenido del Tema de Música y artes Escénicas', '2024-03-14 19:16:21'),
(30, 28, 2, 'Tema de Diseño Gráfico y Multimedia', 'Contenido del Tema de Diseño Gráfico y Multimedia', '2024-03-14 19:17:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `student_progress`
--

CREATE TABLE `student_progress` (
  `student_calification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `calification` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `student_progress`
--

INSERT INTO `student_progress` (`student_calification_id`, `user_id`, `subject_id`, `calification`) VALUES
(21, 1, 164, 5),
(23, 1, 165, 4),
(25, 1, 166, 10),
(27, 1, 167, 2),
(29, 1, 168, 6),
(86, 30, 174, 1),
(87, 30, 175, 2),
(88, 30, 176, 2),
(89, 30, 177, 0),
(90, 30, 178, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`, `course_id`) VALUES
(164, 'Fundamentos del Diseño Gráfico', 28),
(165, 'Diseño Web y UX/UI', 28),
(166, 'Animación 2D y 3D', 28),
(167, 'Edición de Video y Postproducción', 28),
(168, 'Fotografía Digital', 28),
(169, 'Teoría Musical y Composición', 27),
(170, 'Técnicas Vocales y Canto', 27),
(171, 'Instrumentos Musicales', 27),
(172, 'Danza y Expresión Corporal', 27),
(173, 'Actuación y Dirección Teatral', 27),
(174, 'Escultura en Arcilla', 26),
(175, 'Técnicas de Modelado y Fundición', 26),
(176, 'Cerámica', 26),
(177, 'Talla en Madera o Piedra', 26),
(178, 'Escultura en Metal', 26),
(203, 'Fundamentos del Dibujo', 25),
(204, 'Técnicas de Pintura al Óleo', 25),
(205, 'Pintura Acrílica', 25),
(206, 'Acuarela', 25),
(207, 'Ilustración Digital', 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `first_name`, `last_name`, `description`, `photo`, `course_id`) VALUES
(31, 'Laura', 'Nieto', 'Experta en técnicas mixtas, Laura ha expuesto su trabajo en galerías de todo el mundo. Con un enfoque en fomentar la creatividad individual, inspira a sus estudiantes a explorar su propio estilo, combinando técnicas tradicionales y contemporáneas.', '65f19da887d27-65f08e5e507294.37584119.webp', 25),
(32, 'Carlos', 'Martínez', 'Un escultor renombrado, Carlos se especializa en escultura contemporánea y arte público. Su pasión es llevar el arte fuera de los estudios y galerías, animando a sus estudiantes a pensar en grande y de manera tridimensional.', '65f08e90a14293.50720467.webp', 26),
(33, 'Elena', 'Berrezueta', 'Con una sólida carrera en diseño gráfico y experiencia en proyectos multimedia de alta gama, Elena aporta una perspectiva moderna y práctica. Su enseñanza se centra en la combinación de habilidades técnicas con un enfoque conceptual sólido.', '65f08eb396f222.08342554.webp', 28),
(34, 'Daniel', 'López', 'Músico y actor con amplia experiencia en teatro y composición musical, Daniel motiva a sus alumnos a encontrar su voz artística a través de la experimentación y la práctica en escenarios reales.', '65f08ed4f37a70.45002767.webp', 27);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('alumno','profesor','director','administrador') NOT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `first_name`, `last_name`, `phone`, `address`, `role`, `course_id`) VALUES
(1, 'alumno@gmail.com', '$2y$10$a6HYbEGiARwIPAN/jVzc2.KxUvFi5QJitKCbfifKOE2.zg9nwsGxO', 'Alumno', 'ApellidoAlumno', '', '', 'alumno', 28),
(2, 'profesor@gmail.com', '$2y$10$CSc3a6juxwUPzaq817Nkt.df26w/v.lAfJ9ySoPNBsoGoGHKU7Paq', 'Profesor', 'ApellidoProfesor', '', '', 'profesor', NULL),
(3, 'director@gmail.com', '$2y$10$2THBJme/AdpkIKJIR9BTu./Vrh8hZeFF1S3q0LMc.kRc..lMlLC2C', 'Director', 'ApellidoDirector', '', '', 'director', NULL),
(16, 'administrador@gmail.com', '$2y$10$ilPvs5.22/7uZW6ca5C5Tuuj1Jx8IZtl2LqqxamMHPDEnYV5CqpOq', 'Administrador', 'ApellidoAdministrador', '', '', 'administrador', NULL),
(30, 'alumno2@gmail.com', '$2y$10$R4GCC2t/ms4uJAd4z5x9sO47lVS5.wWg9iWMs/3zlMc3UtVMmNgNK', 'Alumno2', 'ApellidoAlumno2', '', '', 'alumno', 26);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`consultation_id`);

--
-- Indices de la tabla `consultation_responses`
--
ALTER TABLE `consultation_responses`
  ADD PRIMARY KEY (`response_id`),
  ADD KEY `consultation_id` (`consultation_id`);

--
-- Indices de la tabla `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indices de la tabla `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD PRIMARY KEY (`topic_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `forum_topics_ibfk_1` (`course_id`);

--
-- Indices de la tabla `student_progress`
--
ALTER TABLE `student_progress`
  ADD PRIMARY KEY (`student_calification_id`),
  ADD UNIQUE KEY `unique_index` (`user_id`,`subject_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indices de la tabla `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indices de la tabla `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `consultations`
--
ALTER TABLE `consultations`
  MODIFY `consultation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `consultation_responses`
--
ALTER TABLE `consultation_responses`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `forum_replies`
--
ALTER TABLE `forum_replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `forum_topics`
--
ALTER TABLE `forum_topics`
  MODIFY `topic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `student_progress`
--
ALTER TABLE `student_progress`
  MODIFY `student_calification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT de la tabla `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT de la tabla `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `consultation_responses`
--
ALTER TABLE `consultation_responses`
  ADD CONSTRAINT `consultation_responses_ibfk_1` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`consultation_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD CONSTRAINT `forum_replies_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `forum_topics` (`topic_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD CONSTRAINT `forum_topics_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_topics_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Filtros para la tabla `student_progress`
--
ALTER TABLE `student_progress`
  ADD CONSTRAINT `student_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_progress_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Filtros para la tabla `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
