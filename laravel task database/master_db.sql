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
-- Database: `master_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `db_server` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `db_port` int UNSIGNED NOT NULL DEFAULT '3306',
  `db_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `db_user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `db_password` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Encrypted at rest via the Client model cast',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_client_code_unique` (`client_code`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `client_name`, `client_code`, `db_server`, `db_port`, `db_name`, `db_user`, `db_password`, `created_at`, `updated_at`) VALUES
(1, 'IBM', 'IBM', '127.0.0.1', 3306, 'tenant_ibm', 'root', 'eyJpdiI6IlZJK3JHTUd2bmZUSnRYVnBtTmx1c0E9PSIsInZhbHVlIjoiQ0RCV0g0MVNNUm9HK0VCL3p0L3ZYQT09IiwibWFjIjoiMWZkOGE1ZDJmNzc2ZDVhMjgyYjk0ZGI5YTE5ZTU2YTE5YWE0MjgxN2Y5ZTg4M2U3YWU4NjVhNjNiNmEwNmYxZSIsInRhZyI6IiJ9', '2026-07-04 12:26:20', '2026-07-04 12:26:20'),
(2, 'HCL Technologies', 'HCL', '127.0.0.1', 3306, 'tenant_hcl', 'root', 'eyJpdiI6IkZCVWRMMTRMVmdZOEIxc3BLMjBpaVE9PSIsInZhbHVlIjoiQ2gyTHM2UXQ2RGRWUVBlaVZ2bVJ4QT09IiwibWFjIjoiOTBiOWQxZWFkNDYwMzZlZTE5MGVjYWEyMjQyYzdhYWYxODBmYmE5MjBiN2MwZDdlMWFiNTgyNTljYWM5NmNhNiIsInRhZyI6IiJ9', '2026-07-04 12:26:20', '2026-07-04 12:26:20'),
(3, 'Infosys', 'INFY', '127.0.0.1', 3306, 'tenant_infosys', 'root', 'eyJpdiI6ImIrV1VoeFdnUUhNU0FLSzdpcTVRMWc9PSIsInZhbHVlIjoiczNKTjBQaStnSEVaNURGWkVpQ0tkUT09IiwibWFjIjoiNmM3YjYwODAyZWJmYWEwYmQ4MGQ0ZjhiMjhjZjkxMjYxZjUyMmNhN2ZiMGMxN2Q4NTY5NjgwODk4MWQzMDZlNCIsInRhZyI6IiJ9', '2026-07-04 12:26:20', '2026-07-04 12:26:20');

-- --------------------------------------------------------

--
-- Table structure for table `client_users`
--

DROP TABLE IF EXISTS `client_users`;
CREATE TABLE IF NOT EXISTS `client_users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_users_client_code_index` (`client_code`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_users`
--

INSERT INTO `client_users` (`id`, `email`, `client_code`, `created_at`, `updated_at`) VALUES
(1, 'ibmuser@gmail.com', 'IBM', '2026-07-04 12:26:20', '2026-07-04 12:26:20'),
(2, 'hcluser@gmail.com', 'HCL', '2026-07-04 12:26:20', '2026-07-04 12:26:20'),
(3, 'infyuser@gmail.com', 'INFY', '2026-07-04 12:26:20', '2026-07-04 12:26:20');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_01_01_000000_create_clients_table', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
