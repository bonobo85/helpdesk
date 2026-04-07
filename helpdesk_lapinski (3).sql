-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 07 avr. 2026 à 12:03
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `helpdesk_lapinski`
--

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `ticket_id`, `user_id`, `message`, `created`) VALUES
(1, 1, 1, 'Je ne peux pas me connecter depuis ce matin', '2026-04-03 09:03:30'),
(2, 1, 1, 'Toujours le même problème', '2026-04-03 09:03:30'),
(3, 1, 1, 'Ça fonctionne maintenant', '2026-04-03 09:03:30'),
(4, 2, 1, 'Le design est cassé sur mobile', '2026-04-03 09:03:30'),
(5, 2, 1, 'Problème toujours présent', '2026-04-03 09:03:30'),
(6, 2, 1, 'Corrigé après mise à jour', '2026-04-03 09:03:30'),
(7, 3, 1, 'Carte refusée', '2026-04-03 09:03:30'),
(8, 3, 1, 'J’ai essayé avec une autre carte', '2026-04-03 09:03:30'),
(9, 3, 1, 'Toujours bloqué', '2026-04-03 09:03:30'),
(10, 4, 1, 'Le mode sombre serait utile', '2026-04-03 09:03:30'),
(11, 4, 1, 'Ça améliorerait le confort', '2026-04-03 09:03:30'),
(12, 4, 1, 'Merci de considérer', '2026-04-03 09:03:30'),
(13, 5, 1, 'Le site met 10 secondes à charger', '2026-04-03 09:03:30'),
(14, 5, 1, 'Même problème sur mobile', '2026-04-03 09:03:30'),
(15, 5, 1, 'Un peu mieux maintenant', '2026-04-03 09:03:30');

-- --------------------------------------------------------

--
-- Structure de la table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `statut` enum('ouvert','en cours','fermé') NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tickets`
--

INSERT INTO `tickets` (`id`, `titre`, `description`, `statut`, `user_id`, `created`) VALUES
(1, 'Problème connexion', 'Impossible de se connecter', 'ouvert', 1, '2026-04-07 09:51:56'),
(2, 'Bug affichage', 'Le tableau ne s’affiche pas correctement', 'en cours', 1, '2026-04-07 09:51:59'),
(3, 'Erreur paiement', 'Paiement refusé sans raison', 'ouvert', 1, '2026-04-07 09:52:04'),
(4, 'Suggestion amélioration', 'Ajouter un mode sombre', 'fermé', 1, '2026-04-07 09:52:10'),
(5, 'Problème lenteur', 'Le site est très lent', 'en cours', 1, '2026-04-07 09:52:15');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `perm` enum('admin','technicien','user') NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `email`, `mdp`, `perm`, `created`) VALUES
(1, 'boss', 'boss@boss.com\r\n', 'boss', 'admin', '2026-03-31 09:40:33');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
