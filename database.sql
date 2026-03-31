-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : jeu. 26 mars 2026 à 16:10
-- Version du serveur : 8.0.44
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `keys_boards`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `profile_pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `display_name`, `pseudo`, `bio`, `city`, `country`, `lat`, `lng`, `profile_pic`, `banner_pic`, `created_at`) VALUES
(8, 'selmagossetpetelet@gmail.com', '$2y$10$EDydjHk9THmpXNoNfoH9b.DySVHfSdEnWRg7hVeeuTljQ6yrK.ecG', 'Selma Gosset--Petelet', '@selma', '', 'Paris', 'FR', NULL, NULL, 'default_user.jpg', 'default_banner.jpg', '2026-03-26 15:24:38'),
(9, 'selmagelet@gmail.com', '$2y$10$jAqjx/5lG9wCmC0O5JW9O.0SzZmq7IaLLjLKNGh.dgW0hY8VFTmN.', 'Selma Gosset--Petelet', '@jtyjnthy', '', 'Paris', 'FR', NULL, NULL, 'default_user.jpg', 'default_banner.jpg', '2026-03-26 15:25:02'),
(10, 'selmagosetelet@gmail.com', '$2y$10$u8f5juKLDO7CZrBrxvo0E.EJ6fhEdLv24zbWlQIjmkS1atCkcecQ.', 'Selma Gosset--Petelet', '@aaaaa', '', 'Paris', 'FR', NULL, NULL, 'default_user.jpg', 'default_banner.jpg', '2026-03-26 15:36:34');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `handle` (`pseudo`),
  ADD UNIQUE KEY `handle_2` (`pseudo`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
