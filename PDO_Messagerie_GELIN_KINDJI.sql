-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 04 nov. 2019 à 09:00
-- Version du serveur :  5.7.26
-- Version de PHP :  7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `coc`
--

-- --------------------------------------------------------

--
-- Structure de la table `coc_admin`
--

DROP TABLE IF EXISTS `coc_admin`;
CREATE TABLE IF NOT EXISTS `coc_admin` (
  `COC_ADMIN_id` int(11) NOT NULL AUTO_INCREMENT,
  `COC_ADMIN_nom` varchar(45) NOT NULL,
  `COC_ADMIN_prenom` varchar(45) NOT NULL,
  `COC_ADMIN_email` varchar(45) NOT NULL,
  `COC_ADMIN_motdepasse` varchar(45) NOT NULL,
  `COC_ADMIN_statut` int(11) NOT NULL,
  `COC_ADMIN_correspondant` varchar(45) NOT NULL,
  PRIMARY KEY (`COC_ADMIN_id`)
) ENGINE=InnoDB AUTO_INCREMENT=255 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `coc_admin`
--

INSERT INTO `coc_admin` (`COC_ADMIN_id`, `COC_ADMIN_nom`, `COC_ADMIN_prenom`, `COC_ADMIN_email`, `COC_ADMIN_motdepasse`, `COC_ADMIN_statut`, `COC_ADMIN_correspondant`) VALUES
(252, 'KINDJI', 'Gaspard', 'kgaspard@partnercenter.com', '87acec17cd9dcd20a716cc2cf67417b71c8a7016', 0, 'PLANIFICATION'),
(253, 'GELIN', 'Déborah', 'gdeborah@partnercenter.com', '87acec17cd9dcd20a716cc2cf67417b71c8a7016', 0, 'FACTURATION'),
(254, 'SANS', 'Virginie', 'vsans@partnercenter.com', '87acec17cd9dcd20a716cc2cf67417b71c8a7016', 0, 'SUPPORT');

-- --------------------------------------------------------

--
-- Structure de la table `coc_apprenant`
--

DROP TABLE IF EXISTS `coc_apprenant`;
CREATE TABLE IF NOT EXISTS `coc_apprenant` (
  `COC_APPRENANT_id` int(11) NOT NULL AUTO_INCREMENT,
  `COC_APPRENANT_nom` varchar(45) NOT NULL,
  `COC_APPRENANT_prenom` varchar(45) NOT NULL,
  `COC_APPRENANT_email` varchar(45) NOT NULL,
  `COC_APPRENANT_motdepasse` varchar(45) NOT NULL,
  `COC_CLIENT_id` int(11) NOT NULL,
  PRIMARY KEY (`COC_APPRENANT_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `coc_apprenant`
--

INSERT INTO `coc_apprenant` (`COC_APPRENANT_id`, `COC_APPRENANT_nom`, `COC_APPRENANT_prenom`, `COC_APPRENANT_email`, `COC_APPRENANT_motdepasse`, `COC_CLIENT_id`) VALUES
(7, 'COLLEU', 'Aurore', 'louloutte@truc.fr', 'COL0218', 5);

-- --------------------------------------------------------

--
-- Structure de la table `coc_client`
--

DROP TABLE IF EXISTS `coc_client`;
CREATE TABLE IF NOT EXISTS `coc_client` (
  `COC_CLIENT_id` int(11) NOT NULL AUTO_INCREMENT,
  `COC_CLIENT_nom` varchar(45) NOT NULL,
  `COC_CLIENT_prenom` varchar(45) NOT NULL,
  `COC_CLIENT_email` varchar(45) NOT NULL,
  `COC_CLIENT_motdepasse` varchar(45) NOT NULL,
  `COC_CLIENT_statut` varchar(20) NOT NULL DEFAULT 'actif',
  `COC_CLIENT_nomcom` varchar(45) NOT NULL,
  `COC_CLIENT_tel` varchar(20) NOT NULL,
  `COC_CLIENT_adresse` varchar(45) NOT NULL,
  `COC_CLIENT_cp` varchar(10) NOT NULL,
  `COC_CLIENT_ville` varchar(45) NOT NULL,
  PRIMARY KEY (`COC_CLIENT_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `coc_client`
--

INSERT INTO `coc_client` (`COC_CLIENT_id`, `COC_CLIENT_nom`, `COC_CLIENT_prenom`, `COC_CLIENT_email`, `COC_CLIENT_motdepasse`, `COC_CLIENT_statut`, `COC_CLIENT_nomcom`, `COC_CLIENT_tel`, `COC_CLIENT_adresse`, `COC_CLIENT_cp`, `COC_CLIENT_ville`) VALUES
(1, 'Dupond', 'Marcel', 'dmarcel@gmail.com', '87acec17cd9dcd20a716cc2cf67417b71c8a7016', 'actif', 'UR1', '', '', '', ''),
(2, 'Alaska', 'Paterne', 'apaterne@gmail.com', '87acec17cd9dcd20a716cc2cf67417b71c8a7016', '0', 'COCOCORP', '+330608471565', '52 Avenue Hospice', '35000', 'Rennes'),
(3, 'Jinshiba', 'Clémentine', 'jclementine@gmail.com', '87acec17cd9dcd20a716cc2cf67417b71c8a7016', '0', 'CHACACORP', '+330648471578', '28 Rue du poivre', '35000', 'Rennes'),
(4, 'Dodo', 'Toto', 'dtoto@gmail.com', '87acec17cd9dcd20a716cc2cf67417b71c8a7016', '0', 'TOTOCORP', '+330655471565', '21 avenue Leclerc', '35000', 'Rennes'),
(5, 'Yuri', 'Yamamoto', 'yyamamoto@gmail.com', '87acec17cd9dcd20a716cc2cf67417b71c8a7016', '0', 'MOJITOCORP', '+330678471565', '28 Rue de la soif', '35000', 'Rennes'),
(6, 'Guenier', 'Estéban', 'gesteban@gmail.com', '87acec17cd9dcd20a716cc2cf67417b71c8a7016', '0', 'ESTECORP', '+330649671565', '40 Ibis', '35000', 'Rennes');

-- --------------------------------------------------------

--
-- Structure de la table `coc_support`
--

DROP TABLE IF EXISTS `coc_support`;
CREATE TABLE IF NOT EXISTS `coc_support` (
  `coc_support_id` int(11) NOT NULL AUTO_INCREMENT,
  `typeexp` varchar(45) NOT NULL,
  `idexp` int(11) NOT NULL,
  `typedesti` varchar(45) NOT NULL,
  `message` text NOT NULL,
  `iddest` int(11) NOT NULL,
  `date` varchar(45) NOT NULL,
  `statutexp` int(11) NOT NULL,
  `statutdest` varchar(45) NOT NULL,
  PRIMARY KEY (`coc_support_id`),
  KEY `fk_foreign_idexp` (`idexp`),
  KEY `fk_foreign_iddest` (`iddest`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `coc_support`
--

INSERT INTO `coc_support` (`coc_support_id`, `typeexp`, `idexp`, `typedesti`, `message`, `iddest`, `date`, `statutexp`, `statutdest`) VALUES
(114, 'CLIENT', 6, 'ADMIN', 'Salutations distinguées !', 254, '04/11/2019 07:48:04', 0, '0'),
(115, 'ADMIN', 254, 'CLIENT', 'Salutations réciproques cher client. En quoi puis-je vous aider ? ', 6, '04/11/2019 07:49:42', 0, '0'),
(116, 'CLIENT', 6, 'ADMIN', 'Je tenais juste à vous offrir une tournée générale de Mojito à toute l\'équipe. ', 254, '04/11/2019 07:53:21', 0, '0'),
(117, 'ADMIN', 254, 'CLIENT', 'Toute l\'équipe de Partenaire Center, par mes écrits, vous adresse ses sincères remerciements.', 6, '04/11/2019 08:51:31', 0, '0');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
