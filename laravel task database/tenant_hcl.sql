-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 04, 2026 at 07:53 PM
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
-- Database: `tenant_hcl`
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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', 'f4e6fe4a11d82e3e0f2154619d3566d00706ae5aff35f0f4cb2180e613fb1b43', '[\"*\"]', NULL, NULL, '2026-07-04 12:35:51', '2026-07-04 12:35:51'),
(2, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', 'e2a06ac5d25658a6304c06912a56b95a3cee44a569e1235167a57744fae0d6bb', '[\"*\"]', NULL, NULL, '2026-07-04 12:42:20', '2026-07-04 12:42:20'),
(3, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', '2871a4c2aaddee134154d0241f525f958af2a08888a47910d891a720bc5461c7', '[\"*\"]', NULL, NULL, '2026-07-04 12:53:42', '2026-07-04 12:53:42'),
(4, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', 'c7c8502d621c99eb25f300e3da0fa6c4d23540ebdd694768e2efe1db43b666e8', '[\"*\"]', NULL, NULL, '2026-07-04 12:54:53', '2026-07-04 12:54:53'),
(5, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', '782f0310fada78b6cae48b72ac4da71fcfcb25cb5f332787b821422fdd7d4810', '[\"*\"]', NULL, NULL, '2026-07-04 12:57:01', '2026-07-04 12:57:01'),
(6, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', '2737668ac9cc16ff0bdcbfa305ba5e765592c2e2f6c4cfd4d913c804e48b414d', '[\"*\"]', NULL, NULL, '2026-07-04 12:57:18', '2026-07-04 12:57:18'),
(7, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', 'e537b48e7438f76f974fdf96224dd9f413cd99c605ca69bb06d3ce8c2f2ff02a', '[\"*\"]', NULL, NULL, '2026-07-04 13:40:52', '2026-07-04 13:40:52'),
(8, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', 'a147aaa0e8df73edb2f8d251830c1cd36a9551d42a8b18a55f364605aca258e3', '[\"*\"]', NULL, NULL, '2026-07-04 13:41:49', '2026-07-04 13:41:49'),
(9, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', '367470ba7395d2317a6b385f864a7ee4cfe67dd0c2459bb3e49cd8764735c491', '[\"*\"]', NULL, NULL, '2026-07-04 13:46:50', '2026-07-04 13:46:50'),
(10, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', 'dec62f40d7bbdad4740f9a7119703e19bc6591da06440914bc1c7df380ae3f2c', '[\"*\"]', NULL, NULL, '2026-07-04 13:46:56', '2026-07-04 13:46:56'),
(11, 'App\\Models\\Tenant\\TenantUser', 1, 'web-session-token', 'bb6b1857fb3ee95451bf1ca8a9791b3518915713c74eb861628961567d35aced', '[\"*\"]', NULL, NULL, '2026-07-04 13:57:20', '2026-07-04 13:57:20');

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
(1, 'HCL Technologies Demo User', 'hcluser@gmail.com', NULL, '$2y$12$G.CPqhiRNk3MxTia8l1DPuqpQ9/Oj/TGx/A9KaGQQEqqWN8JfOgmi', NULL, '2026-07-04 12:27:54', '2026-07-04 12:27:54');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
