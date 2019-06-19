-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 19 juin 2019 à 10:08
-- Version du serveur :  5.7.24
-- Version de PHP :  7.3.1

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données :  `crudoo`
--

--
-- Déchargement des données de la table `thesection`
--

INSERT INTO `thesection` (`idthesection`, `thetitle`, `thedesc`) VALUES
(1, 'Développeur Web', 'Le développeur Web réalise l’ensemble des fonctionnalités d’un site internet. Après analyse des besoins du client, il préconise et met en oeuvre une solution technique pour concevoir des sites sur mesure ou adapter des solutions techniques existantes.'),
(2, 'PAO', 'L’opérateur PAO est chargé de la conception graphique de projets pour divers types d’imprimés (livres, catalogues, logos, affiches…).');

--
-- Déchargement des données de la table `thesection_has_thestudent`
--

INSERT INTO `thesection_has_thestudent` (`thesection_idthesection`, `thestudent_idthestudent`) VALUES
(2, 2),
(2, 3);

--
-- Déchargement des données de la table `thestudent`
--

INSERT INTO `thestudent` (`idthestudent`, `thename`, `thesurname`) VALUES
(2, 'Vandenbore', 'John'),
(3, 'Logist', 'Steve');

--
-- Déchargement des données de la table `theuser`
--

INSERT INTO `theuser` (`idtheuser`, `thelogin`, `thepwd`) VALUES
(1, 'lulu', 'd2435e88f3575be3ee762a3183629548165f9ed6a81a6ab13725967e3c72ef36');
SET FOREIGN_KEY_CHECKS=1;
COMMIT;