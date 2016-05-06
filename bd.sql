-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 06 Mai 2016 à 20:08
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

-- --------------------------------------------------------

--
-- Structure de la table `accompagnateur_benevole`
--

DROP TABLE IF EXISTS `accompagnateur_benevole`;
CREATE TABLE IF NOT EXISTS `accompagnateur_benevole` (
  `numero` int(11) NOT NULL AUTO_INCREMENT,
  `numeroPersonne` int(11) NOT NULL,
  `numeroCompetition` int(11) NOT NULL,
  `role` varchar(30) NOT NULL,
  PRIMARY KEY (`numero`),
  KEY `numeroPersonne` (`numeroPersonne`),
  KEY `numeroCompetition` (`numeroCompetition`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `accompagnateur_officiel`
--

DROP TABLE IF EXISTS `accompagnateur_officiel`;
CREATE TABLE IF NOT EXISTS `accompagnateur_officiel` (
  `numero` int(11) NOT NULL AUTO_INCREMENT,
  `numeroAdherent` int(11) NOT NULL,
  `numeroCompetition` int(11) NOT NULL,
  `role` varchar(30) NOT NULL,
  PRIMARY KEY (`numero`),
  KEY `numero_adherent` (`numeroAdherent`),
  KEY `numeroCompetition` (`numeroCompetition`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `adherent`
--

DROP TABLE IF EXISTS `adherent`;
CREATE TABLE IF NOT EXISTS `adherent` (
  `numero` int(11) NOT NULL AUTO_INCREMENT,
  `numeroPersonne` int(11) NOT NULL,
  `categorie` varchar(30) NOT NULL,
  `specialite` varchar(5) NOT NULL,
  `objectifSaison` text NOT NULL,
  `certif_med` tinyint(1) NOT NULL,
  PRIMARY KEY (`numero`),
  KEY `numeroPersonne` (`numeroPersonne`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `adherent_equipage`
--

DROP TABLE IF EXISTS `adherent_equipage`;
CREATE TABLE IF NOT EXISTS `adherent_equipage` (
  `numeroAdherent` int(11) NOT NULL,
  `numeroEquipage` int(11) NOT NULL,
  PRIMARY KEY (`numeroAdherent`,`numeroEquipage`),
  KEY `numeroAdherent` (`numeroAdherent`),
  KEY `numeroEquipage` (`numeroEquipage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `competition`
--

DROP TABLE IF EXISTS `competition`;
CREATE TABLE IF NOT EXISTS `competition` (
  `numero` int(11) NOT NULL AUTO_INCREMENT,
  `niveau` varchar(30) NOT NULL,
  `adresse` text NOT NULL,
  `codePostal` int(5) NOT NULL,
  `ville` varchar(30) NOT NULL,
  `meteo` varchar(30) NOT NULL,
  `typeHebergement` varchar(30) NOT NULL,
  `modeTransport` varchar(30) NOT NULL,
  `clubOrganisateur` varchar(30) NOT NULL,
  PRIMARY KEY (`numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `equipage`
--

DROP TABLE IF EXISTS `equipage`;
CREATE TABLE IF NOT EXISTS `equipage` (
  `numero` int(11) NOT NULL AUTO_INCREMENT,
  `specialite` varchar(5) NOT NULL,
  `categorie` varchar(30) NOT NULL,
  `nbPlaces` int(11) NOT NULL,
  `nbParticipants` int(11) NOT NULL,
  `numeroCompetition` int(11) NOT NULL,
  PRIMARY KEY (`numero`),
  KEY `numeroCompetition` (`numeroCompetition`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(12345, 1, 'bleu', 0);

-- --------------------------------------------------------

--
-- Structure de la table `parent`
--

DROP TABLE IF EXISTS `parent`;
CREATE TABLE IF NOT EXISTS `parent` (
  `numeroAdherent` int(11) NOT NULL,
  `numeroPersonne` int(11) NOT NULL,
  PRIMARY KEY (`numeroAdherent`,`numeroPersonne`),
  KEY `numeroAdherent` (`numeroAdherent`),
  KEY `numeroPersonne` (`numeroPersonne`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `personne`
--

INSERT INTO `personne` (`id`, `nom`, `prenom`, `num_tel`, `email`, `adresse`, `date_naissance`, `sexe`) VALUES
(1, 'nomadmin', 'prenomadmin', '0102030405', 'coucou@lalala.fr', 'ici', '2016-04-01', 'F');

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `id_personne`, `username`, `password`, `roles`) VALUES
(4, 1, 'admin', '$2y$10$i16peYiZ5kNj9qJE5l757OYVuqLEYm31xI.NDlsDUIUAWFtfMDrQe', 'bleu');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `accompagnateur_benevole`
--
ALTER TABLE `accompagnateur_benevole`
  ADD CONSTRAINT `accompagnateurbenevole_ibfk_1` FOREIGN KEY (`numeroPersonne`) REFERENCES `personne` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `accompagnateurbenevole_ibfk_2` FOREIGN KEY (`numeroCompetition`) REFERENCES `competition` (`numero`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `accompagnateur_officiel`
--
ALTER TABLE `accompagnateur_officiel`
  ADD CONSTRAINT `accompagnateur_officiel_ibfk_1` FOREIGN KEY (`numeroAdherent`) REFERENCES `adherent` (`numero`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `accompagnateur_officiel_ibfk_2` FOREIGN KEY (`numeroCompetition`) REFERENCES `competition` (`numero`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `adherent`
--
ALTER TABLE `adherent`
  ADD CONSTRAINT `adherent_ibfk_1` FOREIGN KEY (`numeroPersonne`) REFERENCES `personne` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `adherent_equipage`
--
ALTER TABLE `adherent_equipage`
  ADD CONSTRAINT `adherent_equipage_ibfk_1` FOREIGN KEY (`numeroAdherent`) REFERENCES `adherent` (`numero`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `adherent_equipage_ibfk_2` FOREIGN KEY (`numeroEquipage`) REFERENCES `equipage` (`numero`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `equipage`
--
ALTER TABLE `equipage`
  ADD CONSTRAINT `equipage_ibfk_1` FOREIGN KEY (`numeroCompetition`) REFERENCES `competition` (`numero`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `parent`
--
ALTER TABLE `parent`
  ADD CONSTRAINT `parent_ibfk_1` FOREIGN KEY (`numeroAdherent`) REFERENCES `adherent` (`numero`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `parent_ibfk_2` FOREIGN KEY (`numeroPersonne`) REFERENCES `personne` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
