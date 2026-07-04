-- ============================================================
-- MASTER DATABASE SCHEMA
--   CREATE DATABASE master_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- ============================================================

CREATE TABLE IF NOT EXISTS `clients` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `client_name` VARCHAR(255) NOT NULL,
    `client_code` VARCHAR(50) NOT NULL,
    `db_server` VARCHAR(255) NOT NULL,
    `db_port` INT UNSIGNED NOT NULL DEFAULT 3306,
    `db_name` VARCHAR(255) NOT NULL,
    `db_user` VARCHAR(255) NOT NULL,
    `db_password` TEXT NOT NULL COMMENT 'Encrypted at rest by the application (Laravel encrypted cast)',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `clients_client_code_unique` (`client_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- email -> client_code lookup index. This single indexed table is what
-- lets login resolve the correct tenant in one query, without ever
-- scanning tenant databases or storing per-user rows on `clients`.
CREATE TABLE IF NOT EXISTS `client_users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL,
    `client_code` VARCHAR(50) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `client_users_email_unique` (`email`),
    KEY `client_users_client_code_index` (`client_code`),
    CONSTRAINT `client_users_client_code_foreign`
        FOREIGN KEY (`client_code`) REFERENCES `clients` (`client_code`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
