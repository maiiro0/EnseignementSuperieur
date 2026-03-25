-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 25 mars 2026 à 15:48
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
CREATE DATABASE IF NOT EXISTS `bdd_gestion_licence` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
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
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_course_module` (`module_id`),
  KEY `id_intervention` (`intervention_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `course`
--

INSERT INTO `course` (`id`, `start_date`, `end_date`, `intervention_type_id`, `module_id`, `remotely`, `title`) VALUES
(1, '2026-03-10 00:00:00', '2026-04-10 00:00:00', 1, 4, 0, 'Course 1'),
(2, '2026-03-11 00:00:00', '2026-03-12 00:00:00', 2, 4, 1, 'Course 2'),
(3, '2026-03-13 00:00:00', '2026-03-13 00:00:00', 2, 2, 0, 'Course 3'),
(4, '2026-03-10 00:00:00', '2026-04-10 00:00:00', 12, 8, 0, 'course 4'),
(5, '2026-03-14 00:00:00', '2026-03-14 00:00:00', 1, 2, 1, 'course 5'),
(6, '2026-03-15 00:00:00', '2026-03-15 00:00:00', 5, 7, 0, 'course 6'),
(7, '2026-03-15 00:00:00', '2026-03-15 00:00:00', 8, 4, 1, 'course 7'),
(8, '2026-03-16 00:00:00', '2026-03-16 00:00:00', 8, 3, 0, 'course 8'),
(9, '2026-03-17 00:00:00', '2026-03-17 00:00:00', 9, 6, 1, 'course 9'),
(10, '2026-03-18 00:00:00', '2026-03-18 00:00:00', 1, 2, 1, 'course 10'),
(11, '2026-03-19 00:00:00', '2026-03-19 00:00:00', 8, 1, 0, 'course 11'),
(12, '2026-03-20 00:00:00', '2026-03-20 00:00:00', 8, 1, 0, 'course 12');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `course_instructor`
--

INSERT INTO `course_instructor` (`course_id`, `instructor_id`) VALUES
(3, 1),
(4, 1),
(1, 2),
(2, 2),
(4, 2),
(1, 3),
(2, 3),
(5, 3),
(5, 6),
(2, 7),
(4, 7),
(12, 12);

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `instructor`
--

INSERT INTO `instructor` (`id`, `user_id`) VALUES
(1, 1),
(2, 1),
(3, 2),
(4, 3),
(5, 4),
(10, 5),
(7, 7),
(8, 7),
(11, 8),
(6, 9),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(3, 5),
(9, 5),
(12, 5),
(1, 6),
(9, 7),
(10, 7),
(1, 9),
(11, 10),
(11, 11),
(11, 12);

-- --------------------------------------------------------

--
-- Structure de la table `intervention_type`
--

DROP TABLE IF EXISTS `intervention_type`;
CREATE TABLE IF NOT EXISTS `intervention_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `color` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `intervention_type`
--

INSERT INTO `intervention_type` (`id`, `name`, `description`, `color`) VALUES
(1, 'Présentation d\'entreprise', 'Présentation de l\'entreprise et des dernier projet de celle ci. ', '#FFFF00'),
(2, 'Conférence ', 'Conférence sur la gestion de projet et de l\'importance des soft skils dans le monde du travail.', '#FF0000'),
(3, 'Conférence sur L\'IA', 'conférence sur les bonnes conduites d\'un étudiant avec l\'IA', '#FF0000'),
(4, 'cour de français', 'cour de communication et expression', '#0000FF'),
(5, 'cour de Maths', 'cour de Mathématique pour l\'informatique', '#0000FF'),
(6, 'cour d\'Algorithmie ', 'Cour d\'algorithmie encadré', '#0000FF'),
(7, 'Projet', 'Séance sur un Projet', '#7F00FF'),
(8, 'Cour sur Git', 'Apprentissage sur l\'utilisation de git', '#0000FF'),
(9, 'Cour sur la gestion de base de données', 'Cour sur la gestion de base de données dans le cadre de projet.', '#0000FF'),
(10, 'Conférence sur l\'attitude professionnelle', 'Conférence sur l\'attitude professionnelle à tenir en alternance ou stage', '#FF0000'),
(11, 'Présentation de projet', 'Explication du projet et du cahier des charges', '#FFFF00'),
(12, 'Conférence l\'importance du travail d\'équipe', 'conférence et atelier sur le thème du travail d\'équipe', '#FF0000');

-- --------------------------------------------------------

--
-- Structure de la table `module`
--

DROP TABLE IF EXISTS `module`;
CREATE TABLE IF NOT EXISTS `module` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `parent_id` int DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `hours_count` int DEFAULT NULL,
  `capstone_project` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `module`
--

INSERT INTO `module` (`id`, `code`, `parent_id`, `name`, `description`, `hours_count`, `capstone_project`) VALUES
(1, '1', NULL, 'Algo', 'Cour d\'algo pour une logique de programmation.', 5, 1),
(2, '1.1', 1, 'Fonctions', 'Les Fonctions et procédures', 4, 1),
(3, '1.2', 1, 'Poo', 'Programmation orienté objet', 1, 0),
(4, '1.1.1', 2, 'Les Fonction', 'Cour sur les fonction', 2, 1),
(5, '1.1.2', 2, 'Les Procédures', 'Cour sur les procédures', 6, 0),
(6, '2', NULL, 'C#', 'Apprentissage du Langage C#', 15, 1),
(7, '2.1', 6, 'Variable', 'Création de variable en C#', 1, 0),
(8, '2.1.1', 7, 'Type de varaible', 'Apprentissage des différent de variable et et du \"tryparse\" ', 1, 0),
(9, '2.2', 6, 'Les Constantes en C#', 'Apprentissage de l\'utilisation des constantes en C#', 2, 0),
(10, '2.3', 6, 'Tableaux et Liste', 'Découverte des tableaux et des Listes en C#', 2, 1),
(11, '2.3.1', 10, 'Les tableaux', 'Apprentissage des tableau en C#', 1, 0),
(12, '2.3.2', 10, 'Les listes', 'Apprentissage des listes en C#', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Enseignant',
  `email` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `role`, `email`, `last_name`, `first_name`, `password`) VALUES
(1, '', 'elenaL@gmail.com', 'Longuet', 'Elena', ''),
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
  ADD CONSTRAINT `id_course_module` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `id_intervention` FOREIGN KEY (`intervention_type_id`) REFERENCES `intervention_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `course_instructor`
--
ALTER TABLE `course_instructor`
  ADD CONSTRAINT `id_course` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `id_course_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `instructor`
--
ALTER TABLE `instructor`
  ADD CONSTRAINT `id_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `instructor_module`
--
ALTER TABLE `instructor_module`
  ADD CONSTRAINT `id_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructor` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `id_module` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `module`
--
ALTER TABLE `module`
  ADD CONSTRAINT `id_parent` FOREIGN KEY (`parent_id`) REFERENCES `module` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
