-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 16 avr. 2026 à 11:45
-- Version du serveur : 8.0.31
-- Version de PHP : 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `helpdesk_irigaray`
--

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `cree_le` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `ticket_id`, `user_id`, `message`, `cree_le`) VALUES
(1, 1, 1, 'Je ne peux pas me connecter depuis ce matin', '2026-04-03 09:00:59'),
(2, 1, 1, 'Toujours le même problème', '2026-04-03 09:00:59'),
(3, 1, 1, 'Ça fonctionne maintenant', '2026-04-03 09:00:59'),
(4, 2, 1, 'Le design est cassé sur mobile', '2026-04-03 09:00:59'),
(5, 2, 1, 'Problème toujours présent', '2026-04-03 09:00:59'),
(6, 2, 1, 'Corrigé après mise à jour', '2026-04-03 09:00:59'),
(7, 3, 1, 'Carte refusée', '2026-04-03 09:00:59'),
(8, 3, 1, 'J’ai essayé avec une autre carte', '2026-04-03 09:00:59'),
(9, 3, 1, 'Toujours bloqué', '2026-04-03 09:00:59'),
(10, 4, 1, 'Le mode sombre serait utile', '2026-04-03 09:00:59'),
(11, 4, 1, 'Ça améliorerait le confort', '2026-04-03 09:00:59'),
(12, 4, 1, 'Merci de considérer', '2026-04-03 09:00:59'),
(13, 5, 1, 'Le site met 10 secondes à charger', '2026-04-03 09:00:59'),
(14, 5, 1, 'Même problème sur mobile', '2026-04-03 09:00:59'),
(15, 5, 1, 'Un peu mieux maintenant', '2026-04-03 09:00:59');

-- --------------------------------------------------------

--
-- Structure de la table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `statut` enum('ouvert','en_cours','ferme') COLLATE utf8mb4_general_ci DEFAULT 'ouvert',
  `user_id` int NOT NULL,
  `cree_le` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tickets`
--

INSERT INTO `tickets` (`id`, `titre`, `description`, `statut`, `user_id`, `cree_le`) VALUES
(1, 'Problème connexion', 'Impossible de se connecter', 'ouvert', 1, '2026-04-03 09:00:29'),
(2, 'Bug affichage', 'Le tableau ne s’affiche pas correctement', 'en_cours', 1, '2026-04-03 09:00:29'),
(3, 'Erreur paiement', 'Paiement refusé sans raison', 'ouvert', 1, '2026-04-03 09:00:29'),
(4, 'Suggestion amélioration', 'Ajouter un mode sombre', 'ferme', 1, '2026-04-03 09:00:29'),
(5, 'Problème lenteur', 'Le site est très lent', 'en_cours', 1, '2026-04-03 09:00:29'),
(9, 'test1', 'yyy', 'ouvert', 1, '2026-04-13 14:20:47'),
(10, 'test1', 'yyy', 'ouvert', 1, '2026-04-13 14:21:28'),
(11, 'connexion', 'rrr', 'ouvert', 1, '2026-04-14 08:37:14'),
(12, 'Connexion', 'je n\'ai plus de réseau.', 'ouvert', 1, '2026-04-14 09:54:27'),
(13, 'Connexion', 'je n\'ai plus de réseau.', 'ouvert', 1, '2026-04-14 09:58:01'),
(17, 'test num1', 'etettetetete', 'ouvert', 4, '2026-04-14 14:03:48'),
(18, 'gastro', 'isac a une gastro', 'ouvert', 5, '2026-04-14 14:20:12'),
(20, 'zeg<<f', 'ZQFqZF', 'ouvert', 4, '2026-04-14 14:21:05'),
(21, 'test azerty', 'azerty', 'ouvert', 7, '2026-04-16 08:42:35'),
(22, 'rgz<e', '<ezgez', 'ouvert', 7, '2026-04-16 09:04:36'),
(28, 'zz', 'zz', 'ouvert', 1, '2026-04-16 09:34:14'),
(29, 'ez', 'oui\r\n', 'ouvert', 1, '2026-04-16 09:37:43'),
(30, 'lahi n3el vos', 'il est ou mon argent', 'ferme', 9, '2026-04-16 09:41:43'),
(31, 'J\'ai des problemes dans ma plantation', 'Comment cuire 6 million de cookies en 5 ans ?', 'ouvert', 10, '2026-04-16 09:59:09');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('user','technicien','admin') COLLATE utf8mb4_general_ci DEFAULT 'user',
  `cree_le` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `email`, `mot_de_passe`, `role`, `cree_le`) VALUES
(1, 'boss', 'boss@boss.com', '1234', 'user', '2026-03-30 13:16:38'),
(2, 'papiboss', 'papiboss@gmail.com', '$2y$10$YcXZSgao4fuHds.TqMS.qepcad4M1ff7qMggSSTkyPQm6Id0b4M/q', 'user', '2026-04-14 09:19:09'),
(3, 'azda', 'zaza@azfla', '\"zfqqegfq', 'user', '2026-04-14 13:51:14'),
(4, 'zaza', 'zazazi@gmail.com', 'azerty', 'user', '2026-04-14 13:55:18'),
(5, 'test', 'test@test.com', 'test', 'admin', '2026-04-14 14:17:05'),
(6, 'ouo', 'azizi@gmai', 'azerty', 'user', '2026-04-14 14:20:34'),
(7, 'azerty', 'azerty@gmail.com', 'azerty', 'user', '2026-04-16 08:42:00'),
(8, 'test123', 'test123@test.com', 'test123', 'technicien', '2026-04-16 08:57:17'),
(9, 'tmr', 'tmr@gmail.com', '123', 'user', '2026-04-16 09:39:02'),
(10, 'dorian yates', 'itwasonly271k@hh88.com', 'HEILHITLER', 'user', '2026-04-16 09:57:55');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
