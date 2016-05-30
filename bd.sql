-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 30 Mai 2016 à 15:01
-- Version du serveur :  5.7.9
-- Version de PHP :  7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `projet_techno_web`
--
CREATE DATABASE IF NOT EXISTS `projet_techno_web` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `projet_techno_web`;

-- --------------------------------------------------------

--
-- Structure de la table `accompagnateur_benevole`
--

DROP TABLE IF EXISTS `accompagnateur_benevole`;
CREATE TABLE IF NOT EXISTS `accompagnateur_benevole` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_personne` int(11) NOT NULL,
  `id_competition` int(11) NOT NULL,
  `role` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `numeroPersonne` (`id_personne`),
  KEY `numeroCompetition` (`id_competition`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `accompagnateur_officiel`
--

DROP TABLE IF EXISTS `accompagnateur_officiel`;
CREATE TABLE IF NOT EXISTS `accompagnateur_officiel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_licence` int(11) NOT NULL,
  `id_competition` int(11) NOT NULL,
  `role` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `numero_adherent` (`id_licence`),
  KEY `numeroCompetition` (`id_competition`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `accompagnateur_officiel`
--

INSERT INTO `accompagnateur_officiel` (`id`, `id_licence`, `id_competition`, `role`) VALUES
(2, 12345, 1, 'Buvette');

-- --------------------------------------------------------

--
-- Structure de la table `adherent`
--

DROP TABLE IF EXISTS `adherent`;
CREATE TABLE IF NOT EXISTS `adherent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num_personne` int(11) NOT NULL,
  `categorie` varchar(30) NOT NULL,
  `specialite` varchar(5) NOT NULL,
  `objectif_saison` text NOT NULL,
  `certif_med` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `numeroPersonne` (`num_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `adherent`
--

INSERT INTO `adherent` (`id`, `num_personne`, `categorie`, `specialite`, `objectif_saison`, `certif_med`) VALUES
(9, 7, 'cadet', 'kayak', '', 1),
(13, 6, 'senior', 'kayak', '', 0);

-- --------------------------------------------------------

--
-- Structure de la table `adherent_equipage`
--

DROP TABLE IF EXISTS `adherent_equipage`;
CREATE TABLE IF NOT EXISTS `adherent_equipage` (
  `num_competiteur` int(11) NOT NULL,
  `num_equipage` int(11) NOT NULL,
  `valide` tinyint(1) NOT NULL,
  PRIMARY KEY (`num_competiteur`,`num_equipage`),
  KEY `numeroAdherent` (`num_competiteur`),
  KEY `numeroEquipage` (`num_equipage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `adherent_equipage_invite`
--

DROP TABLE IF EXISTS `adherent_equipage_invite`;
CREATE TABLE IF NOT EXISTS `adherent_equipage_invite` (
  `id_competiteur` int(11) NOT NULL,
  `id_equipage` int(11) NOT NULL,
  PRIMARY KEY (`id_competiteur`,`id_equipage`),
  KEY `id_equipage` (`id_equipage`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `adherent_transport`
--

DROP TABLE IF EXISTS `adherent_transport`;
CREATE TABLE IF NOT EXISTS `adherent_transport` (
  `id_competiteur` int(11) NOT NULL,
  `id_competition` int(11) NOT NULL,
  PRIMARY KEY (`id_competiteur`,`id_competition`),
  KEY `adherent_transport_ibfk_2` (`id_competition`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `competition`
--

DROP TABLE IF EXISTS `competition`;
CREATE TABLE IF NOT EXISTS `competition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `niveau` varchar(30) NOT NULL,
  `date_competition` date NOT NULL,
  `adresse` text NOT NULL,
  `code_postal` int(5) NOT NULL,
  `ville` varchar(30) NOT NULL,
  `meteo` varchar(30) NOT NULL,
  `type_hebergement` varchar(30) NOT NULL,
  `mode_transport` varchar(30) NOT NULL,
  `nb_places_dispo` int(11) NOT NULL,
  `club_organisateur` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `competition`
--

INSERT INTO `competition` (`id`, `niveau`, `date_competition`, `adresse`, `code_postal`, `ville`, `meteo`, `type_hebergement`, `mode_transport`, `nb_places_dispo`, `club_organisateur`) VALUES
(1, 'departemental', '2017-02-01', 'ici', 21000, 'Dijon', 'Soleil', 'particulier', 'Car', 1, 'Dijon Kayak'),
(6, 'national', '2016-05-30', 'lac', 71250, 'Massy', '', '', 'Car', 10, 'Dijon Kayak');

-- --------------------------------------------------------

--
-- Structure de la table `equipage`
--

DROP TABLE IF EXISTS `equipage`;
CREATE TABLE IF NOT EXISTS `equipage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `specialite` varchar(5) NOT NULL,
  `categorie` varchar(30) NOT NULL,
  `nb_places` int(11) NOT NULL,
  `id_competition` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `numeroCompetition` (`id_competition`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `equipage`
--

INSERT INTO `equipage` (`id`, `specialite`, `categorie`, `nb_places`, `id_competition`) VALUES
(56, 'canoe', 'junior', 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `licence`
--

DROP TABLE IF EXISTS `licence`;
CREATE TABLE IF NOT EXISTS `licence` (
  `num` int(11) NOT NULL,
  `id_personne` int(11) NOT NULL,
  `type` varchar(30) NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`num`),
  KEY `numeroAherent` (`id_personne`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `licence`
--

INSERT INTO `licence` (`num`, `id_personne`, `type`, `activated`) VALUES
(1111, 4, 'Dirigeant', 1),
(2222, 5, 'Dirigeant', 1),
(3333, 6, 'Competiteur', 1),
(4444, 7, 'Competiteur', 1),
(12345, 1, 'admin', 1);

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

DROP TABLE IF EXISTS `personne`;
CREATE TABLE IF NOT EXISTS `personne` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `num_tel` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `adresse` text NOT NULL,
  `date_naissance` date NOT NULL,
  `sexe` varchar(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `numero` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `personne`
--

INSERT INTO `personne` (`id`, `nom`, `prenom`, `num_tel`, `email`, `adresse`, `date_naissance`, `sexe`) VALUES
(1, 'Nomadmin', 'Prenomadmin', '0102030406', 'coucou@lalala.com', 'toujours la', '1998-05-13', 'H'),
(4, 'Nomentraineur', 'Prenomentraineur', '0123456789', 'email@coucou.fr', 'adresse', '2000-01-01', 'H'),
(5, 'Nomsecretaire', 'Prenomsecretaire', '0123456789', 'email@coucou.fr', 'ici', '1992-01-01', 'F'),
(6, 'Nomcompetiteur', 'Prenomcompetiteur', '0123456789', 'email@coucou.fr', 'ici', '1992-01-01', 'F'),
(7, 'Nomcompetiteur2', 'Prenomcompetiteur2', '0123456789', 'email@coucou.fr', 'ici', '2000-01-01', 'F'),
(8, 'Nompersonne', 'Prenompersonne', '0123456789', 'email@coucou.fr', 'ici', '2000-01-01', 'F');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_personne` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roles` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_personne` (`id_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `id_personne`, `username`, `password`, `roles`) VALUES
(4, 1, 'admin', '$2y$10$i16peYiZ5kNj9qJE5l757OYVuqLEYm31xI.NDlsDUIUAWFtfMDrQe', 'admin'),
(10, 4, 'entraineur', '$2y$10$UM9NPmtcCWOSEs.XpKn09eo06OiuFer6dZmvF1zIxqhhfUDiVOfF2', 'entraineur'),
(11, 5, 'secretaire', '$2y$10$dqo5QT.8MaU2KrPhTG4mw.s9MIB0zrShl.5j1mPUrOJwEl.4LkOla', 'secretaire'),
(21, 7, 'competiteur2', '$2y$10$im6YEBYncTYxXsNc7oEmEOvX9pqK4IKMbTvIrpWwN3I2V9O8TPsbm', 'competiteur'),
(24, 6, 'competiteur', '$2y$10$uTJ0g18gKhsNoMG/pT4RgeliwPkbnTljyKWzb1ZodKWYlT/.8Sg7m', 'competiteur');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `accompagnateur_benevole`
--
ALTER TABLE `accompagnateur_benevole`
  ADD CONSTRAINT `accompagnateurbenevole_ibfk_1` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `accompagnateurbenevole_ibfk_2` FOREIGN KEY (`id_competition`) REFERENCES `competition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `accompagnateur_officiel`
--
ALTER TABLE `accompagnateur_officiel`
  ADD CONSTRAINT `accompagnateur_officiel_ibfk_1` FOREIGN KEY (`id_licence`) REFERENCES `licence` (`num`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `accompagnateur_officiel_ibfk_2` FOREIGN KEY (`id_competition`) REFERENCES `competition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `adherent`
--
ALTER TABLE `adherent`
  ADD CONSTRAINT `adherent_ibfk_1` FOREIGN KEY (`num_personne`) REFERENCES `personne` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `adherent_equipage`
--
ALTER TABLE `adherent_equipage`
  ADD CONSTRAINT `adherent_equipage_ibfk_1` FOREIGN KEY (`num_competiteur`) REFERENCES `adherent` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `adherent_equipage_ibfk_2` FOREIGN KEY (`num_equipage`) REFERENCES `equipage` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `adherent_equipage_invite`
--
ALTER TABLE `adherent_equipage_invite`
  ADD CONSTRAINT `adherent_equipage_invite_ibfk_1` FOREIGN KEY (`id_competiteur`) REFERENCES `adherent` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `adherent_equipage_invite_ibfk_2` FOREIGN KEY (`id_equipage`) REFERENCES `equipage` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `adherent_transport`
--
ALTER TABLE `adherent_transport`
  ADD CONSTRAINT `adherent_transport_ibfk_1` FOREIGN KEY (`id_competiteur`) REFERENCES `adherent` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `adherent_transport_ibfk_2` FOREIGN KEY (`id_competition`) REFERENCES `competition` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `equipage`
--
ALTER TABLE `equipage`
  ADD CONSTRAINT `equipage_ibfk_1` FOREIGN KEY (`id_competition`) REFERENCES `competition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `licence`
--
ALTER TABLE `licence`
  ADD CONSTRAINT `licence_ibfk_1` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
