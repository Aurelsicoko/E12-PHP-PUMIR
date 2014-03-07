-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Ven 07 Mars 2014 à 03:48
-- Version du serveur: 5.5.33
-- Version de PHP: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `lego`
--

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `photos` text NOT NULL,
  `video` varchar(255) NOT NULL,
  `user_vote` text NOT NULL,
  `admin_vote` text NOT NULL,
  `beginDay` varchar(255) NOT NULL,
  `lastDay` varchar(255) NOT NULL,
  `lego` int(11) NOT NULL,
  `block` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `admin` int(11) NOT NULL DEFAULT '0',
  `block` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `viewproject`
--
CREATE TABLE `viewproject` (
`id` int(11)
,`id_user` int(11)
,`title` varchar(255)
,`description` text
,`photos` text
,`video` varchar(255)
,`user_vote` text
,`admin_vote` text
,`beginDay` varchar(255)
,`lastDay` varchar(255)
,`block` int(11)
,`firstname` varchar(255)
,`lastname` varchar(255)
,`lego` int(11)
);
-- --------------------------------------------------------

--
-- Structure de la table `vote`
--

CREATE TABLE `vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_project` int(11) NOT NULL,
  `originality` int(11) NOT NULL,
  `difficulty` int(11) NOT NULL,
  `style` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- Structure de la vue `viewproject`
--
DROP TABLE IF EXISTS `viewproject`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `viewproject` AS select `projects`.`id` AS `id`,`projects`.`id_user` AS `id_user`,`projects`.`title` AS `title`,`projects`.`description` AS `description`,`projects`.`photos` AS `photos`,`projects`.`video` AS `video`,`projects`.`user_vote` AS `user_vote`,`projects`.`admin_vote` AS `admin_vote`,`projects`.`beginDay` AS `beginDay`,`projects`.`lastDay` AS `lastDay`,`projects`.`block` AS `block`,`users`.`firstname` AS `firstname`,`users`.`lastname` AS `lastname`,`projects`.`lego` AS `lego` from (`projects` left join `users` on((`projects`.`id_user` = `users`.`id`)));
