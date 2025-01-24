CREATE DATABASE immobilier;

USE immobilier;

-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 05 Décembre 2024 à 11:59
-- Version du serveur :  5.7.11
-- Version de PHP :  7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `immobilier`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonce`
--

CREATE TABLE `annonce` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `complementAdresse` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `codePostal` int(11) NOT NULL,
  `ville` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `departement` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `idUtilisateur` int(11) NOT NULL,
  `type` tinyint(1) DEFAULT '0',
  `surface` decimal(10,2) NOT NULL,
  `nombrePiece` int(11) NOT NULL,
  `chambre` int(11) NOT NULL,
  `cuisine` int(11) NOT NULL,
  `salleDeBain` int(11) NOT NULL,
  `toilette` int(11) NOT NULL,
  `salon` int(11) NOT NULL,
  `garage` int(11) NOT NULL,
  `terrasse` int(11) NOT NULL,
  `cave` int(11) NOT NULL,
  `grenier` int(11) NOT NULL,
  `parking` tinyint(1) DEFAULT '0',
  `balcon` int(11) NOT NULL,
  `datedpe` date NOT NULL,
  `classeEnergetique` varchar(1) NOT NULL,
  `meuble` tinyint(1) DEFAULT '0',
  `latitude` DECIMAL(10, 7),
  `longitude` DECIMAL(10, 7),
  `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `annonce`
--

INSERT INTO `annonce` (`id`, `titre`, `prix`, `description`, `complementAdresse`, `codePostal`, `ville`, `departement`, `idUtilisateur`, `type`, `surface`, `nombrePiece`, `chambre`, `cuisine`, `salleDeBain`, `toilette`, `salon`, `garage`, `terrasse`, `cave`, `grenier`, `balcon`, `parking`, `datedpe`, `classeEnergetique`, `meuble`, `latitude`, `longitude`) VALUES
(20, 'studio 3 pieces', '350.00', 'je sais pas quoi mettre', '4 rue albert premier', 58000, 'Nevers', 'Nievre', 1, 0, 29, 3, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 1, '2025-01-07', 'D', 1, 46.9896, 3.1590);

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `chemin` varchar(255) DEFAULT NULL,
  `idAnnonce` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `images`
--

INSERT INTO `images` (`id`, `chemin`, `idAnnonce`) VALUES
(8, 'jesaispas.jpg', 20);
INSERT INTO `images` (`id`, `chemin`, `idAnnonce`) VALUES
(9, 'cuisine.jpg', 20);
INSERT INTO `images` (`id`, `chemin`, `idAnnonce`) VALUES
(10, 'chambre.jpg', 20);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `mdp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `pseudo`, `mail`, `tel`, `mdp`) VALUES
(1, 'Regala', 'Anthony', 'Bluewarrior', 'anthony.regala58@gmail.com', '0745398682', '$2y$10$Z5urewXM5Pdx5i31nbK28Of5CBlHXBRMTzwrI7KjHN.5.2N1rYbHG');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `annonce`
--
ALTER TABLE `annonce`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_utilisateur` (`idUtilisateur`);

--
-- Index pour la table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idAnnonce` (`idAnnonce`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pseudo` (`pseudo`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `annonce`
--
ALTER TABLE `annonce`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT pour la table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `annonce`
--
ALTER TABLE `annonce`
  ADD CONSTRAINT `fk_utilisateur` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `images`
--

ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`idAnnonce`) REFERENCES `annonce` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE TABLE favories(
  `utilisateurid` INT NOT NULL,
  `annonceid` INT NOT NULL,
  PRIMARY KEY (`utilisateurid`, `annonceid`), -- Clé primaire combinée
  FOREIGN KEY (`utilisateurid`) REFERENCES `utilisateurs`(`id`), -- Clé étrangère vers utilisateurs
  FOREIGN KEY (`annonceid`) REFERENCES `annonce`(`id`) ON DELETE CASCADE -- Clé étrangère avec suppression en cascade
);

INSERT INTO favories (utilisateurid, annonceid) 
VALUES (1, 20);