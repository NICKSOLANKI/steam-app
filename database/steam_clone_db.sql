-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 11, 2026 at 08:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `steam_clone_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `avatar`, `phone`, `bio`, `role`, `is_active`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'NIKHIL', 'dhavalsolanki615@gmail.com', '$2y$10$P5sLasQ1ZB1UeZsaj0UGHeRNXgNfnEteASYRXdUT64FqaTkZODGVq', NULL, NULL, NULL, 'super_admin', 1, '2026-09-10 23:20:08', NULL, '2026-09-10 23:16:23', '2026-09-10 23:20:26');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banner_sliders`
--

CREATE TABLE `banner_sliders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `game_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `original_price` decimal(10,2) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banner_sliders`
--

INSERT INTO `banner_sliders` (`id`, `game_id`, `title`, `slug`, `image_path`, `price`, `original_price`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Wolverine', 'wolverine', 'games/yw2xPSIOzDyH9aNxihOt4zVTioGefZ7Klx73kbg3.jpg', 2299.00, 4599.00, 1, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(2, 2, 'The Last of Us Part II', 'the-last-of-us-part-ii', 'games/7yar6ZRpVbwqBbS8C2wCzx50ufr0CGhMsN6LzTfx.jpg', 1899.00, 3999.00, 2, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(3, 3, 'Spider-Man: Miles Morales', 'spider-man-miles-morales', 'games/HI0lGbCSLlGnt2H9c22wcx6tVFQASSOfIYLBL3qZ.jpg', 1799.00, 4199.00, 3, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `game_title` varchar(255) NOT NULL,
  `game_image` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_channels`
--

CREATE TABLE `community_channels` (
  `id` int(11) NOT NULL,
  `server_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` enum('text','voice') DEFAULT 'text',
  `description` text DEFAULT NULL,
  `position` int(11) DEFAULT 0,
  `is_private` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_channels`
--

INSERT INTO `community_channels` (`id`, `server_id`, `name`, `type`, `description`, `position`, `is_private`, `created_at`, `updated_at`) VALUES
(1, 1, 'general', 'text', NULL, 1, 0, '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(2, 1, 'game-discussion', 'text', NULL, 2, 0, '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(3, 1, 'General Voice', 'voice', NULL, 3, 0, '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(4, 1, 'Gaming Voice', 'voice', NULL, 4, 0, '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(5, 2, 'welcome', 'text', NULL, 1, 0, '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(6, 2, 'announcements', 'text', NULL, 2, 0, '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(7, 2, 'General', 'voice', NULL, 3, 0, '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(8, 3, 'tech-news', 'text', NULL, 1, 0, '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(9, 3, 'help', 'text', NULL, 2, 0, '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(10, 3, 'Tech Voice', 'voice', NULL, 3, 0, '2026-09-10 23:35:50', '2026-09-10 23:35:50');

-- --------------------------------------------------------

--
-- Table structure for table `community_members`
--

CREATE TABLE `community_members` (
  `id` int(11) NOT NULL,
  `server_id` int(11) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role` enum('owner','admin','moderator','member') DEFAULT 'member',
  `nickname` varchar(255) DEFAULT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_members`
--

INSERT INTO `community_members` (`id`, `server_id`, `user_id`, `role`, `nickname`, `joined_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'owner', NULL, '2026-09-10 23:35:50', '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(2, 1, 2, 'member', NULL, '2026-09-10 23:35:50', '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(3, 1, 3, 'member', NULL, '2026-09-10 23:35:50', '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(4, 2, 1, 'owner', NULL, '2026-09-10 23:35:50', '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(5, 2, 2, 'member', NULL, '2026-09-10 23:35:50', '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(6, 2, 3, 'member', NULL, '2026-09-10 23:35:50', '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(7, 3, 1, 'owner', NULL, '2026-09-10 23:35:50', '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(8, 3, 2, 'member', NULL, '2026-09-10 23:35:50', '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(9, 3, 3, 'member', NULL, '2026-09-10 23:35:50', '2026-09-10 23:35:50', '2026-09-10 23:35:50');

-- --------------------------------------------------------

--
-- Table structure for table `community_servers`
--

CREATE TABLE `community_servers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `owner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT 1,
  `max_members` int(11) DEFAULT 100,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `community_servers`
--

INSERT INTO `community_servers` (`id`, `name`, `description`, `icon`, `owner_id`, `is_public`, `max_members`, `created_at`, `updated_at`) VALUES
(1, 'Gaming Hub', 'A place for gamers to connect and chat', NULL, 1, 1, 100, '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(2, 'Steam Community', 'Official STEAM community server', NULL, 1, 1, 100, '2026-09-10 23:35:50', '2026-09-10 23:35:50'),
(3, 'Tech Talk', 'Discuss technology and gaming', NULL, 1, 1, 100, '2026-09-10 23:35:50', '2026-09-10 23:35:50');

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `library_id` bigint(20) UNSIGNED NOT NULL,
  `game_title` varchar(255) NOT NULL,
  `game_image` varchar(255) DEFAULT NULL,
  `total_size` decimal(15,2) DEFAULT 0.00,
  `downloaded_size` decimal(15,2) DEFAULT 0.00,
  `current_speed` decimal(15,2) DEFAULT 0.00,
  `peak_speed` decimal(15,2) DEFAULT 0.00,
  `status` varchar(255) DEFAULT 'queued',
  `progress` int(11) DEFAULT 0,
  `install_path` varchar(255) DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `downloads`
--

INSERT INTO `downloads` (`id`, `user_id`, `library_id`, `game_title`, `game_image`, `total_size`, `downloaded_size`, `current_speed`, `peak_speed`, `status`, `progress`, `install_path`, `started_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'Spider-Man Miles Morales', 'http://127.0.0.1:8000/storage/games/LGXv248fFOgT2NU2y2aUZGdJYfRAKECgFQ2zsQ2d.jpg', 0.00, 0.00, 35.36, 35.36, 'downloading', 0, 'Downloads\\Spider_Man_Miles_Morales.zip', '2026-09-11 00:57:34', NULL, '2026-09-11 00:56:14', '2026-09-11 00:57:34'),
(2, 3, 2, 'Marvel Wolverine', 'http://127.0.0.1:8000/storage/games/ig7xCK8TZLJCPyvyYQz0NOjSwbbDMdMR62SGpj6E.webp', 0.00, 0.00, 35.10, 51.30, 'downloading', 0, 'Downloads\\Marvel_Wolverine.zip', '2026-09-11 00:56:42', NULL, '2026-09-11 00:56:33', '2026-09-11 01:27:45');

-- --------------------------------------------------------

--
-- Table structure for table `email_verifications`
--

CREATE TABLE `email_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_verifications`
--

INSERT INTO `email_verifications` (`id`, `email`, `token`, `created_at`, `updated_at`) VALUES
(1, 'nsolanki952@rku.ac.in', 'k787PKjSScpaOxZ4oJCTaVpDxjrYuvHa', '2026-09-10 23:21:11', '2026-09-10 23:21:11');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `original_price` decimal(10,2) DEFAULT NULL,
  `genre` varchar(100) NOT NULL,
  `developer` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `trailer_url` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `supports_windows` tinyint(1) NOT NULL DEFAULT 1,
  `supports_controller` tinyint(1) NOT NULL DEFAULT 1,
  `is_single_player` tinyint(1) NOT NULL DEFAULT 1,
  `min_requirements` text DEFAULT NULL,
  `rec_requirements` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `title`, `slug`, `description`, `price`, `original_price`, `genre`, `developer`, `release_date`, `trailer_url`, `tags`, `supports_windows`, `supports_controller`, `is_single_player`, `min_requirements`, `rec_requirements`, `image_path`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Wolverine', 'wolverine', 'Wolverine is a Action game available in our store.', 2299.00, 4599.00, 'Action', 'Insomniac Games', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/u2uue7y4xr2Rekf4HTEbDmUKCa57kdozCBpcNAOT.jpg', 1, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(2, 'The Last of Us Part II', 'the-last-of-us-part-ii', 'The Last of Us Part II is a Adventure game available in our store.', 1899.00, 3999.00, 'Adventure', 'Naughty Dog', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/mTGyazW74CA6o5kZc1yPrWrgmCtyWW1hyteSlyix.jpg', 1, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(3, 'Spider-Man: Miles Morales', 'spider-man-miles-morales', 'Spider-Man: Miles Morales is a Action game available in our store.', 1799.00, 4199.00, 'Action', 'Insomniac Games', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/HDETxX2OhuozaJtZL8RQ6QtOqyWZrUZ1MHavHzGC.jpg', 1, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(4, 'God of War', 'god-of-war', 'God of War is a Action game available in our store.', 1899.00, 3999.00, 'Action', 'Santa Monica Studio', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/g2IazMKj0iVQlIVkotN8l7HXcT6jJR4djYxHF9Xn.webp', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(5, 'Marvel Wolverine', 'marvel-wolverine', 'Marvel Wolverine is a Action game available in our store.', 1599.00, 2999.00, 'Action', 'Insomniac Games', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/ig7xCK8TZLJCPyvyYQz0NOjSwbbDMdMR62SGpj6E.webp', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(6, 'GTA V', 'gta-v', 'GTA V is a Racing game available in our store.', 1299.00, 2499.00, 'Racing', 'Rockstar Games', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/H9Yz10cKl6kR2dM4vliLnJsnvlXG2pqpMWMAxUPR.jpg', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(7, 'Assassin\'s Creed Valhalla', 'assassins-creed-valhalla', 'Assassin\'s Creed Valhalla is a Action game available in our store.', 2199.00, 4299.00, 'Action', 'Ubisoft', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/IbFvyU6wVaNki4mz2PUpaoXbn839o18FnuZjRQkj.jpg', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(8, 'Ghost of Tsushima', 'ghost-of-tsushima', 'Ghost of Tsushima is a Adventure game available in our store.', 2399.00, 4999.00, 'Adventure', 'Sucker Punch Productions', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/Le631dswitiyDqLesmArJSMNbd7CWHtR5OhbL5pp.avif', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(9, 'Tekken 8', 'tekken-8', 'Tekken 8 is a Adventure game available in our store.', 1499.00, 2999.00, 'Adventure', 'Bandai Namco', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/CYicX07zWrc1qYMOOEWq6tDU9o0k0C5GQ2pC6Hrz.webp', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(10, 'Hogwarts Legacy', 'hogwarts-legacy', 'Hogwarts Legacy is a Adventure game available in our store.', 1799.00, 3499.00, 'Adventure', 'Avalanche', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/yV4mDw4aCazHwg5ddeBPwBHvVaw3rWcBJYfV60rL.webp', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(11, 'Cyberpunk 2077', 'cyberpunk-2077', 'Cyberpunk 2077 is a RPG game available in our store.', 1999.00, 3999.00, 'RPG', 'CD Projekt Red', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/dajkb204pYpfXxLJzu15PCCpW2JQmesdy33A4yGq.jpg', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(12, 'Red Dead Redemption 2', 'red-dead-redemption-2', 'Red Dead Redemption 2 is a RPG game available in our store.', 1299.00, 2499.00, 'RPG', 'Rockstar Games', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/V4MKHIaLJt8Jbpu1T7sSH4VqXTu42Ba2jAPHu9EF.webp', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(13, 'Elden Ring', 'elden-ring', 'Elden Ring is a RPG game available in our store.', 2999.00, 4999.00, 'RPG', 'FromSoftware', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/9NcAyTt4b2v0pAMw7OyTqUABZqo9vWfpvRTcjJSv.webp', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(14, 'Alan Wake 2', 'alan-wake-2', 'Alan Wake 2 is a RPG game available in our store.', 2499.00, 4299.00, 'RPG', 'Remedy', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/ASVxn2rUyc4Buc1OMb5G0XrJJlOngUkLHRExAKRD.webp', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(15, 'Counter Strike', 'counter-strike', 'Counter Strike is a Shooter game available in our store.', 999.00, 1999.00, 'Shooter', 'Valve', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/6vG3xeU49z2PkcnETp6dMw6PlZHV5rhKrgq2PN5R.jpg', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(16, 'VALORANT', 'valorant', 'VALORANT is a Shooter game available in our store.', 0.00, 2499.00, 'Shooter', 'Riot Games', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/RR7fW6b1mVdFjLFPkxCivDYjeED9ISFyBzVibOLU.webp', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(17, 'Death Stranding', 'death-stranding', 'Death Stranding is a Shooter game available in our store.', 3499.00, 4999.00, 'Shooter', 'Kojima Productions', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/eQTrfRV25FEbpEeFcOQdT0nnZmqxrTjPSMZaR3l0.webp', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(18, 'Starfield', 'starfield', 'Starfield is a Shooter game available in our store.', 1799.00, 3499.00, 'Shooter', 'Bethesda', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/kkussUJurlSj34Q1A6gjV6K5hmdjgDbIdyadt4w7.webp', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(19, 'Forza Horizon 5', 'forza-horizon-5', 'Forza Horizon 5 is a Racing game available in our store.', 1799.00, 3499.00, 'Racing', 'Playground Games', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/qN7F8W4RhsYUt7NEorq0paKMqKECCyizuG0Mg1Av.jpg', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(20, 'FIFA 18', 'fifa-18', 'FIFA 18 is a Action game available in our store.', 1499.00, 2999.00, 'Action', 'EA', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/6IEc2JDorKPg1UqHJYuItIQ3C6GmUL9tGjeWeMCS.webp', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(21, 'Call of Duty Warzone 2', 'call-of-duty-warzone-2', 'Call of Duty Warzone 2 is a Action game available in our store.', 0.00, 1999.00, 'Action', 'Activision', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/LZswICZoB47THBIQGo3sLWchUGd25MTZu21wlBj0.jpg', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(22, 'Apex Legends', 'apex-legends', 'Apex Legends is a Shooter game available in our store.', 0.00, 2499.00, 'Shooter', 'Respawn', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/PkGpq5PlyRvKCK9vuDnANLxJYPyK3dUoPsfTqJlL.webp', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(23, 'Spider-Man Miles Morales', 'spider-man-miles', 'Spider-Man Miles Morales is a Shooter game available in our store.', 0.00, 2999.00, 'Shooter', 'Insomniac Games', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/LGXv248fFOgT2NU2y2aUZGdJYfRAKECgFQ2zsQ2d.jpg', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(24, 'Gran Turismo 7', 'gran-turismo-7', 'Gran Turismo 7 is a Racing game available in our store.', 3499.00, 4999.00, 'Racing', 'Polyphony Digital', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/cjnKMFi424ZZzXIrtVWqQ6W9YDY9bGsJ4Fjj74Ox.jpg', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03'),
(25, 'F1 2023', 'f1-2023', 'F1 2023 is a Action game available in our store.', 2499.00, 3999.00, 'Action', 'EA', NULL, NULL, NULL, 1, 1, 1, NULL, NULL, 'games/TwQjcYOgNwhx1baO6YLpeAYXafy79fVxnyTfAQN1.jpg', 0, 1, '2026-09-10 23:17:03', '2026-09-10 23:17:03');

-- --------------------------------------------------------

--
-- Table structure for table `libraries`
--

CREATE TABLE `libraries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `game_title` varchar(255) NOT NULL,
  `game_image` varchar(255) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `libraries`
--

INSERT INTO `libraries` (`id`, `user_id`, `game_title`, `game_image`, `price`, `created_at`, `updated_at`) VALUES
(1, 3, 'Spider-Man Miles Morales', 'http://127.0.0.1:8000/storage/games/LGXv248fFOgT2NU2y2aUZGdJYfRAKECgFQ2zsQ2d.jpg', 0.00, '2026-09-10 23:25:13', '2026-09-10 23:25:13'),
(2, 3, 'Marvel Wolverine', 'http://127.0.0.1:8000/storage/games/ig7xCK8TZLJCPyvyYQz0NOjSwbbDMdMR62SGpj6E.webp', 1599.00, '2026-09-11 00:56:10', '2026-09-11 00:56:10');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `channel` varchar(255) NOT NULL DEFAULT 'general-help',
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `channel`, `content`, `created_at`, `updated_at`) VALUES
(1, 3, 'general-help', 'jo', '2026-09-10 23:41:30', '2026-09-10 23:41:30');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_09_03_133806_create_users_table', 1),
(2, '2025_09_09_054449_create_libraries_table', 1),
(3, '2025_09_09_081332_create_carts_table', 1),
(4, '2025_09_10_162605_create_user_profiles_table', 1),
(5, '2025_09_11_044555_create_messages_table', 1),
(6, '2025_09_16_040938_create_email_verifications_table', 1),
(7, '2025_09_16_044346_add_email_verified_at_to_users_table', 1),
(8, '2025_09_16_060324_add_profile_images_to_users_table', 1),
(9, '2025_09_20_083834_create_password_resets_table', 1),
(10, '2025_09_20_103739_create_subscriptions_table', 1),
(11, '2025_09_24_070503_create_admins_table', 1),
(12, '2025_09_25_050417_create_games_table', 1),
(13, '2025_09_28_181413_add_plain_password_to_users_table', 1),
(14, '2025_09_29_175430_add_plan_to_subscriptions_table', 1),
(15, '2025_09_30_124427_fix_games_table', 1),
(16, '2025_09_30_124620_drop_image_column_from_games_table', 1),
(17, '2025_10_01_105913_create_banners_table', 1),
(18, '2025_10_01_121814_create_banner_sliders_table', 1),
(19, '2025_10_01_130000_add_dynamic_fields_to_games_table', 1),
(20, '2025_10_02_043813_create_subscription_plans_table', 1),
(21, '2025_10_02_044546_update_admins_table_add_profile_fields', 1),
(22, '2025_10_02_055719_update_admin_credentials', 1),
(23, '2025_10_02_062323_make_game_id_nullable_in_banner_sliders_table', 2),
(24, '2025_10_03_104000_drop_plain_password_from_users_table', 2),
(25, '2025_10_03_104500_add_temp_password_to_users_table', 2),
(26, '2026_09_11_045805_create_community_servers_table', 2),
(27, '2026_09_11_045810_create_community_channels_table', 2),
(28, '2026_09_11_045815_create_voice_sessions_table', 2),
(29, '2026_09_11_045820_create_community_members_table', 2),
(30, '2026_09_11_045846_create_voice_chat_messages_table', 2),
(31, '2026_09_11_050000_create_voice_signals_table', 2),
(32, '2026_09_11_050100_create_downloads_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `plan` varchar(255) NOT NULL DEFAULT 'monthly',
  `type` varchar(255) DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `description` text DEFAULT NULL,
  `price` int(11) NOT NULL DEFAULT 299,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_days` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `name`, `slug`, `description`, `price`, `duration_days`, `is_active`, `features`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Monthly Premium', 'monthly', 'Access all games for one month', 299.00, 30, 1, '[\"Unlimited game access\",\"Premium support\",\"Early access to new games\"]', 1, '2026-09-10 23:16:24', '2026-09-10 23:16:24'),
(2, 'Lifetime Access', 'lifetime', 'Unlimited access to all games forever', 2999.00, NULL, 1, '[\"Unlimited game access\",\"Premium support\",\"Early access to new games\",\"Exclusive content\"]', 2, '2026-09-10 23:16:24', '2026-09-10 23:16:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `temp_password` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `profile_bg` varchar(255) DEFAULT NULL,
  `mini_profile` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `temp_password`, `avatar`, `profile_bg`, `mini_profile`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'dhaval@gmail.com', 'nick', NULL, NULL, NULL, NULL, '2026-09-10 23:16:23', NULL, '2026-09-10 23:16:23', '2026-09-10 23:16:23'),
(2, 'NIICK', 'nsolanki952@rku.ac.in', '$2y$10$XTlNTh/RV/FYONhfxRV/7OkoMtNBps/l5GC8p.n3oVwGhUdt104z2', NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-10 23:21:11', '2026-09-10 23:21:11'),
(3, 'NICK', 'nicksolanki615@gmail.com', '$2y$10$XTlNTh/RV/FYONhfxRV/7OkoMtNBps/l5GC8p.n3oVwGhUdt104z2', NULL, NULL, NULL, NULL, '2026-09-10 23:24:21', NULL, '2026-09-10 23:24:04', '2026-09-10 23:24:21');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `background` varchar(255) DEFAULT NULL,
  `mini_profile` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voice_chat_messages`
--

CREATE TABLE `voice_chat_messages` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `content` text DEFAULT NULL,
  `is_edited` tinyint(1) DEFAULT 0,
  `edited_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voice_sessions`
--

CREATE TABLE `voice_sessions` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `is_muted` tinyint(1) DEFAULT 0,
  `is_deafened` tinyint(1) DEFAULT 0,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `left_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voice_sessions`
--

INSERT INTO `voice_sessions` (`id`, `channel_id`, `user_id`, `session_id`, `is_muted`, `is_deafened`, `joined_at`, `left_at`, `created_at`, `updated_at`) VALUES
(1, 3, 3, '6fb367a2-4272-4620-b416-4e8bae0bf769', 0, 0, '2026-09-11 06:25:17', '2026-09-11 00:55:17', '2026-09-11 00:55:17', '2026-09-11 00:55:17'),
(2, 3, 3, 'eabb8106-ef6c-42d7-b01d-1b91b3f0eb32', 0, 0, '2026-09-11 06:25:18', '2026-09-11 00:55:18', '2026-09-11 00:55:17', '2026-09-11 00:55:18'),
(3, 3, 3, '3ffd38c8-fd0b-45a8-b08c-95315ab5a17f', 0, 0, '2026-09-11 06:25:18', '2026-09-11 00:55:18', '2026-09-11 00:55:18', '2026-09-11 00:55:18'),
(4, 3, 3, 'c6c9fb1b-102c-4811-9641-76e0e93bd830', 0, 0, '2026-09-11 00:55:18', NULL, '2026-09-11 00:55:18', '2026-09-11 00:55:18');

-- --------------------------------------------------------

--
-- Table structure for table `voice_signals`
--

CREATE TABLE `voice_signals` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) DEFAULT NULL,
  `sender_id` bigint(20) UNSIGNED DEFAULT NULL,
  `target_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `data` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voice_signals`
--

INSERT INTO `voice_signals` (`id`, `channel_id`, `sender_id`, `target_user_id`, `type`, `data`, `created_at`) VALUES
(1, 3, 3, NULL, 'join', '{\"user_id\":3,\"name\":\"NICK\"}', '2026-09-11 00:55:18'),
(2, 3, 3, NULL, 'join', '{\"user_id\":3,\"name\":\"NICK\"}', '2026-09-11 00:55:22'),
(3, 3, 3, NULL, 'join', '{\"user_id\":3,\"name\":\"NICK\"}', '2026-09-11 00:55:23'),
(4, 3, 3, NULL, 'join', '{\"user_id\":3,\"name\":\"NICK\"}', '2026-09-11 00:55:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banner_sliders`
--
ALTER TABLE `banner_sliders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `banner_sliders_display_order_unique` (`display_order`),
  ADD KEY `banner_sliders_game_id_foreign` (`game_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_channels`
--
ALTER TABLE `community_channels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `server_id` (`server_id`);

--
-- Indexes for table `community_members`
--
ALTER TABLE `community_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_server_user` (`server_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `community_servers`
--
ALTER TABLE `community_servers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `downloads_user_id_index` (`user_id`),
  ADD KEY `downloads_status_index` (`status`),
  ADD KEY `downloads_library_id_foreign` (`library_id`);

--
-- Indexes for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_verifications_email_unique` (`email`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `games_slug_unique` (`slug`),
  ADD KEY `games_genre_index` (`genre`),
  ADD KEY `games_is_active_index` (`is_active`),
  ADD KEY `games_is_featured_index` (`is_featured`);

--
-- Indexes for table `libraries`
--
ALTER TABLE `libraries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `libraries_user_id_foreign` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_user_id_foreign` (`user_id`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_plans_slug_unique` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_profiles_user_id_foreign` (`user_id`);

--
-- Indexes for table `voice_chat_messages`
--
ALTER TABLE `voice_chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `voice_sessions`
--
ALTER TABLE `voice_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_id` (`session_id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `voice_signals`
--
ALTER TABLE `voice_signals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `target_user_id` (`target_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banner_sliders`
--
ALTER TABLE `banner_sliders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `community_channels`
--
ALTER TABLE `community_channels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `community_members`
--
ALTER TABLE `community_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `community_servers`
--
ALTER TABLE `community_servers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `email_verifications`
--
ALTER TABLE `email_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `libraries`
--
ALTER TABLE `libraries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voice_chat_messages`
--
ALTER TABLE `voice_chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voice_sessions`
--
ALTER TABLE `voice_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `voice_signals`
--
ALTER TABLE `voice_signals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `banner_sliders`
--
ALTER TABLE `banner_sliders`
  ADD CONSTRAINT `banner_sliders_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `community_channels`
--
ALTER TABLE `community_channels`
  ADD CONSTRAINT `community_channels_ibfk_1` FOREIGN KEY (`server_id`) REFERENCES `community_servers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `community_members`
--
ALTER TABLE `community_members`
  ADD CONSTRAINT `community_members_ibfk_1` FOREIGN KEY (`server_id`) REFERENCES `community_servers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `community_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `community_servers`
--
ALTER TABLE `community_servers`
  ADD CONSTRAINT `community_servers_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `downloads`
--
ALTER TABLE `downloads`
  ADD CONSTRAINT `downloads_library_id_foreign` FOREIGN KEY (`library_id`) REFERENCES `libraries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `downloads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `libraries`
--
ALTER TABLE `libraries`
  ADD CONSTRAINT `libraries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `voice_chat_messages`
--
ALTER TABLE `voice_chat_messages`
  ADD CONSTRAINT `voice_chat_messages_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `community_channels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voice_chat_messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `voice_sessions`
--
ALTER TABLE `voice_sessions`
  ADD CONSTRAINT `voice_sessions_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `community_channels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voice_sessions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `voice_signals`
--
ALTER TABLE `voice_signals`
  ADD CONSTRAINT `voice_signals_ibfk_1` FOREIGN KEY (`channel_id`) REFERENCES `community_channels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voice_signals_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voice_signals_ibfk_3` FOREIGN KEY (`target_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
