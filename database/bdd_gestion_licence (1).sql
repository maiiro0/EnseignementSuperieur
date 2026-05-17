-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 17 mai 2026 à 20:58
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bdd_gestion_licence`
--
CREATE DATABASE IF NOT EXISTS `bdd_gestion_licence` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bdd_gestion_licence`;

-- --------------------------------------------------------

--
-- Structure de la table `course`
--

DROP TABLE IF EXISTS `course`;
CREATE TABLE IF NOT EXISTS `course` (
  `id` int NOT NULL AUTO_INCREMENT,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `intervention_type_id` int NOT NULL,
  `module_id` int NOT NULL,
  `remotely` tinyint(1) DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_course_module` (`module_id`),
  KEY `id_intervention` (`intervention_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `course`
--

INSERT INTO `course` (`id`, `start_date`, `end_date`, `intervention_type_id`, `module_id`, `remotely`, `title`) VALUES
(1, '2026-03-29 08:30:00', '2026-03-29 11:30:00', 5, 9, 1, 'Cours 1'),
(2, '2026-03-29 14:30:00', '2026-03-29 16:30:00', 2, 2, 0, 'Cours 2'),
(3, '2026-03-30 09:30:00', '2026-03-30 11:30:00', 7, 4, 1, 'Cour 3'),
(4, '2026-03-10 10:00:00', '2026-03-10 12:00:00', 12, 1, 0, 'cours 4'),
(5, '2026-03-30 13:30:00', '2026-03-30 15:30:00', 8, 6, 0, 'Cour 5'),
(6, '2026-03-15 15:00:00', '2026-03-15 17:00:00', 5, 7, 0, 'cours 6'),
(7, '2026-03-15 10:00:00', '2026-03-15 14:00:00', 8, 4, 1, 'cours 7'),
(8, '2026-03-16 14:00:00', '2026-03-16 18:00:00', 8, 3, 0, 'cours 8'),
(9, '2026-03-17 13:00:00', '2026-03-17 16:00:00', 9, 6, 1, 'cours 9'),
(10, '2026-03-31 10:30:00', '2026-03-31 12:30:00', 6, 5, 0, 'Cour 10'),
(11, '2026-03-19 09:00:00', '2026-03-19 11:00:00', 8, 1, 0, 'cours 11'),
(12, '2026-03-20 08:00:00', '2026-03-20 10:00:00', 8, 1, 0, 'cours 12'),
(13, '2026-03-31 15:30:00', '2026-03-31 17:30:00', 10, 5, 0, 'cour 13\r\n'),
(14, '2026-05-04 15:58:00', '2026-05-04 16:58:00', 5, 1, 0, 'cour 14'),
(15, '2026-05-04 17:10:00', '2026-05-04 18:10:00', 13, 1, 1, NULL),
(17, '2026-05-04 09:30:00', '2026-05-04 10:30:00', 13, 4, 1, NULL),
(18, '2026-05-04 16:30:00', '2026-05-04 17:30:00', 7, 4, 1, NULL),
(19, '2026-05-10 16:22:18', '2026-05-10 18:22:18', 11, 4, 1, NULL),
(20, '2026-05-10 18:22:18', '2026-05-10 19:22:18', 12, 4, NULL, NULL),
(21, '2026-05-11 17:12:16', '2026-05-11 18:12:16', 1, 4, 1, NULL),
(22, '2026-05-12 17:12:16', '2026-05-12 18:12:16', 2, 4, 1, NULL),
(23, '2026-05-13 12:12:16', '2026-05-13 13:12:16', 3, 4, 1, NULL),
(24, '2026-05-13 17:12:16', '2026-05-13 18:12:16', 9, 4, NULL, NULL),
(25, '2026-05-14 17:12:16', '2026-05-14 18:12:16', 6, 4, NULL, NULL),
(27, '2026-05-11 15:00:00', '2026-05-11 17:00:00', 11, 1, 0, NULL),
(32, '2026-05-15 16:00:00', '2026-05-15 17:00:00', 13, 8, 1, NULL),
(33, '2026-05-16 11:25:00', '2026-05-16 13:25:00', 2, 8, 1, '');

-- --------------------------------------------------------

--
-- Structure de la table `course_instructor`
--

DROP TABLE IF EXISTS `course_instructor`;
CREATE TABLE IF NOT EXISTS `course_instructor` (
  `course_id` int NOT NULL,
  `instructor_id` int NOT NULL,
  PRIMARY KEY (`course_id`,`instructor_id`),
  KEY `id_course_instructor` (`instructor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `course_instructor`
--

INSERT INTO `course_instructor` (`course_id`, `instructor_id`) VALUES
(1, 1),
(3, 1),
(5, 1),
(7, 1),
(9, 1),
(14, 1),
(15, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(27, 1),
(4, 2),
(11, 2),
(2, 3),
(8, 3),
(10, 3),
(13, 3),
(3, 4),
(17, 4),
(4, 5),
(11, 5),
(12, 5),
(3, 7),
(17, 7),
(18, 7),
(32, 8),
(33, 8),
(6, 9),
(10, 9),
(6, 10),
(10, 12),
(13, 12);

-- --------------------------------------------------------

--
-- Structure de la table `instructor`
--

DROP TABLE IF EXISTS `instructor`;
CREATE TABLE IF NOT EXISTS `instructor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `instructor`
--

INSERT INTO `instructor` (`id`, `user_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(10, 6),
(7, 7),
(8, 8),
(11, 9),
(6, 10),
(12, 11),
(9, 12);

-- --------------------------------------------------------

--
-- Structure de la table `instructor_module`
--

DROP TABLE IF EXISTS `instructor_module`;
CREATE TABLE IF NOT EXISTS `instructor_module` (
  `instructor_id` int NOT NULL,
  `module_id` int NOT NULL,
  PRIMARY KEY (`instructor_id`,`module_id`),
  KEY `id_module` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `instructor_module`
--

INSERT INTO `instructor_module` (`instructor_id`, `module_id`) VALUES
(1, 1),
(2, 1),
(5, 1),
(3, 2),
(3, 3),
(11, 3),
(1, 4),
(4, 4),
(7, 4),
(3, 5),
(9, 5),
(12, 5),
(1, 6),
(9, 7),
(10, 7),
(6, 8),
(8, 8),
(1, 9),
(11, 10),
(10, 11),
(11, 11),
(11, 12);

-- --------------------------------------------------------

--
-- Structure de la table `intervention_type`
--

DROP TABLE IF EXISTS `intervention_type`;
CREATE TABLE IF NOT EXISTS `intervention_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `intervention_type`
--

INSERT INTO `intervention_type` (`id`, `name`, `description`, `color`) VALUES
(1, 'Conférence', 'Intervention d\'un professionnel', '#FF0000'),
(2, 'Cours', 'Cours théorique', '#FFFF00'),
(3, 'Autonomie', 'Elèves en autonomie', '#FFFF00'),
(4, 'Conseil de classe', 'Réunion sur le suivi de la classe', '#FF0000'),
(5, 'TP', 'Travaux pratique', '#0000FF'),
(6, 'Soutenance', 'Présentation oral des étudiant', '#0000FF'),
(7, 'Projet encadré', 'Séance de travail sur un Projet', '#7F00FF'),
(8, 'Evaluation', 'Vérification des connaissances ', '#0000FF'),
(9, 'TD', 'Travaux dirigés', '#0000FF'),
(10, 'Présentation d\'entreprise', 'Intervention d\'un professionnel pour présenter son entreprise', '#FF0000'),
(11, 'Evènement', 'Activité spécifique', '#FFFF00'),
(12, 'Veille technologique', 'Recherche des étudiants sur une techno', '#FF0000'),
(13, 'Formation', 'Formation de l\'intervenant', '#0000FF');

-- --------------------------------------------------------

--
-- Structure de la table `module`
--

DROP TABLE IF EXISTS `module`;
CREATE TABLE IF NOT EXISTS `module` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `parent_id` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hours_count` int DEFAULT NULL,
  `capstone_project` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `module`
--

INSERT INTO `module` (`id`, `code`, `parent_id`, `name`, `description`, `hours_count`, `capstone_project`) VALUES
(1, '1', NULL, 'Algorithmie', 'Cour d\'algorithmie pour une logique de programmation.', 5, 1),
(2, '1.1', 1, 'Fonctions', 'Les Fonctions et procédures', 4, 1),
(3, '1.2', 1, 'POO', 'Programmation orientée objet', 1, 0),
(4, '1.1.1', 2, 'Les Fonctions', 'Cour sur les fonctions', 2, 1),
(5, '1.1.2', 2, 'Les Procédures', 'Cour sur les procédures', 6, 0),
(6, '2', NULL, 'C#', 'Apprentissage du Langage C#', 15, 1),
(7, '2.1', 6, 'Variables', 'Création de variables en C#', 1, 0),
(8, '2.1.1', 7, 'Type de variables', 'Apprentissage des différents types de variables et du \"tryparse\" ', 1, 0),
(9, '2.2', 6, 'Les Constantes en C#', 'Apprentissage de l\'utilisation des constantes en C#', 2, 0),
(10, '2.3', 6, 'Tableaux et Listes', 'Découverte des tableaux et des Listes en C#', 2, 1),
(11, '2.3.1', 10, 'Les tableaux', 'Apprentissage des tableaux en C#', 1, 0),
(12, '2.3.2', 10, 'Les listes', 'Apprentissage des listes en C#', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Enseignant',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `role`, `email`, `last_name`, `first_name`, `password`) VALUES
(1, '', 'elenaL@gmail.com', 'Longuet', 'Elena', '$2y$10$Nb7Xdulwagu1C/bb9QkJWO1LHBtr0ipORsw6kV.4MAtNKrZ6Nm4ka'),
(2, '', 'Leo@gmail.com', 'Martin', 'Léo', ''),
(3, '', 'Cassandrechr@gmail.com', 'Chardron', 'Cassandre', ''),
(4, '', 'Eloane@gmail.com', 'Chardron', 'Eloane', ''),
(5, '', 'Lilou.chardron@gmail.com', 'Chardron', 'Lilou', ''),
(6, '', 'Elsa@gmail.com', 'Leroy', 'Elsa', ''),
(7, '', 'William@gmail.com', 'Gonon', 'William', ''),
(8, '', 'julesgonon@gmail.com', 'Gonon', 'Jules', ''),
(9, '', 'roman@gmail.com', 'Malossane', 'Roman', ''),
(10, '', 'Jeanlf@gmail.com', 'Frebourg', 'Jean', ''),
(11, '', 'HugoP@gmail.com', 'Plus', 'Hugo', ''),
(12, '', 'Sacha@gmail.com', 'ouadi', 'Sacha', '');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `id_course_module` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`),
  ADD CONSTRAINT `id_intervention` FOREIGN KEY (`intervention_type_id`) REFERENCES `intervention_type` (`id`);

--
-- Contraintes pour la table `course_instructor`
--
ALTER TABLE `course_instructor`
  ADD CONSTRAINT `id_course` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `id_course_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`id`);

--
-- Contraintes pour la table `instructor`
--
ALTER TABLE `instructor`
  ADD CONSTRAINT `id_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `instructor_module`
--
ALTER TABLE `instructor_module`
  ADD CONSTRAINT `id_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`id`),
  ADD CONSTRAINT `id_module` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`);

--
-- Contraintes pour la table `module`
--
ALTER TABLE `module`
  ADD CONSTRAINT `id_parent` FOREIGN KEY (`parent_id`) REFERENCES `module` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
