-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 04, 2026 at 07:54 PM
-- Server version: 8.4.7
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tenant_ibm`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_01_01_000002_create_users_table', 1),
(2, '2025_01_01_000003_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', '948d680753dfcfde5a622e50f6eb640e39d9879b7301561e0350680440f6818d', '[\"*\"]', NULL, NULL, '2026-07-04 12:28:00', '2026-07-04 12:28:00'),
(2, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', '4d248f90957c4b521a7bd1937ae0082c050a04cac8782fdad86967c1e6584c0c', '[\"*\"]', '2026-07-04 12:45:26', NULL, '2026-07-04 12:43:43', '2026-07-04 12:45:26'),
(3, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', '5dda31e17e52d3891279d07f488d231500e6abee4c6f9c03a68bdea37198f33f', '[\"*\"]', NULL, NULL, '2026-07-04 12:47:40', '2026-07-04 12:47:40'),
(4, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', '3b21e3bd46c171e622ec7696fef8d15379a68a2d0dcecb2c0007a57028883346', '[\"*\"]', NULL, NULL, '2026-07-04 12:47:45', '2026-07-04 12:47:45'),
(5, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', 'ab69169a9cf2f56d8195fc464a7e044bce1f2cd0259a274fffe36083ec2226ba', '[\"*\"]', NULL, NULL, '2026-07-04 12:47:55', '2026-07-04 12:47:55'),
(6, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', '31f1a062236847fd3267cb95da95f4aec883cdb39f81de3e723d68a45848d9c7', '[\"*\"]', NULL, NULL, '2026-07-04 13:47:14', '2026-07-04 13:47:14'),
(7, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', '1b5ce6eb04036fbc585aefdbbb4b8fd498f46fa3ed21acd014b3680576c8a66f', '[\"*\"]', NULL, NULL, '2026-07-04 13:53:38', '2026-07-04 13:53:38'),
(8, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', '0f97c5816638003fa3541364cf54fa201d5ac6cb95800e8039418566953e29d8', '[\"*\"]', NULL, NULL, '2026-07-04 13:53:46', '2026-07-04 13:53:46');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'IBM Demo User', 'ibmuser@gmail.com', NULL, '$2y$12$8KzWTGlxxGw4QYhSIeycy.FRZjc.PRrf/SU.NYu.kno9fWCEPtYgC', NULL, '2026-07-04 12:27:54', '2026-07-04 12:27:54');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
