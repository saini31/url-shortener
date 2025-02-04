-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2025 at 05:31 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `url_shortener`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'mahesh', '2025-02-04 02:36:25', '2025-02-04 02:36:25'),
(2, 'mahesh saini', '2025-02-04 02:39:02', '2025-02-04 02:39:02');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invitations`
--

CREATE TABLE `invitations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','member') NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','accepted','declined') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invitations`
--

INSERT INTO `invitations` (`id`, `client_id`, `email`, `role`, `admin_id`, `token`, `expires_at`, `created_at`, `updated_at`, `status`) VALUES
(1, 1, 'm@gmail.com', 'admin', NULL, 'BNOO9kdgmgDKwnTMc1EviNUtVaagnRh2', '2025-02-11 02:36:25', '2025-02-04 02:36:25', '2025-02-04 02:36:25', NULL),
(3, 1, 'mhh@gmail.com', 'admin', NULL, 'yQWvCqNOS5iSAdpeD7JvCypGHY1GXae8', '2025-02-11 02:37:39', '2025-02-04 02:37:39', '2025-02-04 02:37:39', NULL),
(4, 1, 'mhaah@gmail.com', 'admin', NULL, 'obAR3vR4EVGlIiyfHrm2nBWxhGgwZxYj', '2025-02-11 02:38:04', '2025-02-04 02:38:04', '2025-02-04 02:38:04', NULL),
(9, NULL, 'hindumahesh3@gmail.com', 'member', NULL, 'J472v9PPDVL3iGbKYz3rO1Rd9D3HRhCV', '2025-02-11 09:12:32', '2025-02-04 09:12:32', '2025-02-04 09:12:32', NULL),
(11, NULL, 'maheshsaini724077@gmail.com', 'admin', NULL, 'P6yoc4heskFQ8ZvAJuUHkjLFjhY6fa1e', '2025-02-04 14:49:42', '2025-02-04 09:19:08', '2025-02-04 09:19:42', 'accepted'),
(12, NULL, 'maheshsainii1168@gmail.com', 'admin', 11, 'qDkBUIJ1OfInLUBBE8iQxjXFLIOrKuGg', '2025-02-04 15:28:15', '2025-02-04 09:57:42', '2025-02-04 09:58:15', 'accepted'),
(13, NULL, 'maheshsaini61649@gmail.com', 'member', NULL, 'cX87pij6YXskoHs7wT8BJ6OJiWTqVziE', '2025-02-11 10:32:24', '2025-02-04 10:32:24', '2025-02-04 10:32:24', NULL);

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_02_04_050147_create_clients_table', 1),
(6, '2025_02_04_050247_create_short_urls_table', 1),
(7, '2025_02_04_050313_create_invitations_table', 1),
(8, '2025_02_04_064720_add_status_to_invitations_table', 2),
(9, '2025_02_04_151618_add_admin_id_to_invitations_table', 3);

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `short_urls`
--

CREATE TABLE `short_urls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `long_url` varchar(255) NOT NULL,
  `short_code` varchar(255) NOT NULL,
  `hits` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `short_urls`
--

INSERT INTO `short_urls` (`id`, `user_id`, `long_url`, `short_code`, `hits`, `created_at`, `updated_at`) VALUES
(1, 3, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'gdLWqP', 3, '2025-02-04 00:36:26', '2025-02-04 00:36:55'),
(2, 1, 'https://www.amazon.com', 'ki32Qg', 1, '2025-02-04 03:07:02', '2025-02-04 09:35:29'),
(5, 11, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'l2VwfJ', 0, '2025-02-04 10:01:08', '2025-02-04 10:01:08'),
(6, 12, 'https://www.amazon.com', 'LgIxaJ', 0, '2025-02-04 10:01:17', '2025-02-04 10:01:17'),
(7, 11, 'https://www.wikipedia.org/wiki/Artificial_intelligence', 'leH3EK', 0, '2025-02-04 10:49:54', '2025-02-04 10:49:54'),
(8, 11, 'https://www.nationalgeographic.com/animals/mammals/f/ferruginous-pygmy-owl/', 'fbV8L3', 0, '2025-02-04 10:58:02', '2025-02-04 10:58:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','member') NOT NULL DEFAULT 'member',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'm', 'm@gmail.com', '$2y$10$VHeS2mjlSKjgb2luvHSzd.tPxvkoubxhF/55ShQsUdxpNHxCUvRSa', 'admin', '2025-02-03 23:54:40', '2025-02-03 23:54:40'),
(2, 'Super Admin', 'superadmin@example.com', '$2y$10$ukSS3HoO7apBbzFACTpJ9euiS2JrvLkqPkYtC9iz7P47AITEsqvuG', 'super_admin', '2025-02-04 00:03:36', '2025-02-04 00:03:36'),
(3, 'Admin User', 'admin@example.com', '$2y$10$tPNKSHJwiZL0w/nJA3OA6O9UMSMhoJrRwXYQ9Nb3DD0MhhII.csPu', 'admin', '2025-02-04 00:03:36', '2025-02-04 00:03:36'),
(9, 'we', 'hindumahesh3@gmail.com', '$2y$10$82C6bAF6/jc1pXE7ZyLhYOO2i09j8j1aNphzEowEhaEl2gjABjtLu', 'member', '2025-02-04 09:13:19', '2025-02-04 09:13:19'),
(11, 'kishan', 'maheshsaini724077@gmail.com', '$2y$10$8W9cT7/41v.87dBICdDUReigeGsGrX1VWaG2.2WHKQk9qZ4kiRBB.', 'admin', '2025-02-04 09:19:42', '2025-02-04 09:19:42'),
(12, 'ishita', 'maheshsainii1168@gmail.com', '$2y$10$RiFcxv.0mZ7AKk0EddOBouTIWypwrp2ZaeWwjXwYyeXJViBEOhvBO', 'admin', '2025-02-04 09:58:15', '2025-02-04 09:58:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invitations`
--
ALTER TABLE `invitations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invitations_email_unique` (`email`),
  ADD KEY `invitations_client_id_foreign` (`client_id`),
  ADD KEY `invitations_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `short_urls`
--
ALTER TABLE `short_urls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short_urls_short_code_unique` (`short_code`),
  ADD KEY `short_urls_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invitations`
--
ALTER TABLE `invitations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `short_urls`
--
ALTER TABLE `short_urls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invitations`
--
ALTER TABLE `invitations`
  ADD CONSTRAINT `invitations_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invitations_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `short_urls`
--
ALTER TABLE `short_urls`
  ADD CONSTRAINT `short_urls_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
