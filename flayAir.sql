/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `shift_scheduler` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `shift_scheduler`;

CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `flights` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `flight_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('arrival','departure') COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduled_time` time NOT NULL,
  `date` date NOT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `airline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `flights` (`id`, `flight_number`, `type`, `scheduled_time`, `date`, `origin`, `destination`, `airline`, `status`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 'AK-0123', 'arrival', '14:00:00', '2025-09-06', 'CMD', 'AER', NULL, 'scheduled', NULL, NULL, NULL),
	(2, 'JK-025', 'departure', '00:30:00', '2025-09-06', 'CMB', 'DOH', 'Qatar Airways', 'scheduled', 'Its Overnight Flight', '2025-09-06 03:25:20', '2025-09-06 03:25:20');

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_08_18_051857_create_permission_tables', 1),
	(5, '2025_08_21_095654_create_flights_table', 2),
	(6, '2025_08_25_054740_add_role_to_users_table', 3),
	(7, '2025_08_26_081846_create_roles_table', 4),
	(8, '2025_08_26_081900_add_role_id_to_users_table', 5);

CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'user', 'Regular User', NULL, NULL),
	(2, 'admin', 'Administrator', NULL, NULL),
	(3, 'super_admin', 'Super Administrator', NULL, NULL);

CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('qCHXYzZVFld0MBm66H1N2NKamvX8So1yojTrVmb0', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiUERFcHJkT3Q1QWVhM0RpREtHN0V0eGZKaG5WYnNNbGxmQnZGS0RsViI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1756207268),
	('W84Aghsr8F5oCbmbY8ZXwybYvZwoG89yueeKIO0v', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoickNiVEtmbkhMc2FKa3A4bjQ5VG0zZXlMNmNUcXZrR3JYR3FSc0hvMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zaGlmdHMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1756403689);

CREATE TABLE IF NOT EXISTS `shifts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `worker_id` int NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `shift_type` enum('morning','afternoon','evening','night') NOT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') DEFAULT 'scheduled',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_start_time` (`start_time`),
  KEY `idx_end_time` (`end_time`),
  KEY `idx_worker_date` (`worker_id`,`start_time`),
  CONSTRAINT `shifts_ibfk_1` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `shifts` (`id`, `worker_id`, `start_time`, `end_time`, `shift_type`, `status`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 1, '2024-08-20 08:00:00', '2024-08-20 09:00:00', 'morning', 'scheduled', NULL, '2025-08-20 08:02:31', '2025-08-20 08:10:15'),
	(2, 2, '2024-01-15 12:00:00', '2024-01-15 20:00:00', 'afternoon', 'scheduled', NULL, '2025-08-20 08:02:31', '2025-08-20 08:02:31'),
	(3, 3, '2024-01-15 16:00:00', '2024-01-16 00:00:00', 'evening', 'scheduled', NULL, '2025-08-20 08:02:31', '2025-08-20 08:02:31'),
	(4, 4, '2024-01-15 22:00:00', '2024-01-16 06:00:00', 'night', 'scheduled', NULL, '2025-08-20 08:02:31', '2025-08-20 08:02:31'),
	(5, 5, '2024-01-16 08:00:00', '2024-01-16 16:00:00', 'morning', 'scheduled', NULL, '2025-08-20 08:02:31', '2025-08-20 08:02:31'),
	(9, 4, '2025-08-20 17:00:00', '2025-08-20 19:30:00', 'evening', 'scheduled', NULL, '2025-08-20 11:36:42', '2025-08-20 11:36:42'),
	(10, 1, '2025-08-20 20:00:00', '2025-08-20 23:30:00', 'night', 'scheduled', NULL, '2025-08-20 11:36:42', '2025-08-20 11:37:15'),
	(11, 2, '2025-08-21 00:00:00', '2025-08-21 06:00:00', 'night', 'scheduled', 'Flight Number 20115 Take Drainage before flying', '2025-08-20 11:41:54', '2025-08-21 05:41:40'),
	(13, 3, '2025-08-21 06:00:00', '2025-08-21 12:00:00', 'morning', 'scheduled', NULL, '2025-08-20 11:59:31', '2025-08-20 12:02:30'),
	(14, 4, '2025-08-21 12:00:00', '2025-08-21 18:00:00', 'afternoon', 'scheduled', NULL, '2025-08-20 11:59:31', '2025-08-20 12:02:30'),
	(15, 6, '2025-08-21 18:00:00', '2025-08-21 00:00:00', 'evening', 'scheduled', NULL, '2025-08-20 11:59:31', '2025-08-20 12:02:30'),
	(16, 1, '2025-08-21 06:00:00', '2025-08-21 12:00:00', 'morning', 'scheduled', NULL, '2025-08-21 05:36:27', '2025-08-21 05:36:27'),
	(17, 16, '2025-08-21 20:40:00', '2025-08-21 22:40:00', 'evening', 'scheduled', 'Test Notes', '2025-08-21 15:10:09', '2025-08-21 15:10:09'),
	(18, 8, '2025-08-25 11:02:56', '2025-08-25 14:02:57', 'afternoon', 'scheduled', 'Testing', '2025-08-25 05:33:11', '2025-08-25 05:33:11'),
	(19, 12, '2025-08-26 13:30:22', '2025-08-26 18:30:24', 'afternoon', 'scheduled', 'testing', '2025-08-26 08:00:37', '2025-08-26 08:00:38'),
	(20, 8, '2025-08-28 08:00:00', '2025-08-28 16:30:00', 'afternoon', 'scheduled', NULL, '2025-08-28 17:58:06', '2025-08-28 18:02:52'),
	(21, 10, '2025-08-28 00:00:07', '2025-08-28 04:30:00', 'night', 'scheduled', 'Testing ', '2025-08-28 18:07:18', '2025-08-28 18:07:29'),
	(22, 8, '2025-09-06 14:00:00', '2025-09-06 17:00:00', 'afternoon', 'scheduled', NULL, '2025-09-06 08:23:18', '2025-09-06 08:23:18'),
	(23, 8, '2025-09-06 08:00:00', '2025-09-06 13:00:00', 'morning', 'scheduled', 'Clean the Flight number JK-0123', '2025-09-06 03:35:02', '2025-09-06 03:35:02'),
	(24, 5, '2025-09-06 16:00:00', '2025-09-06 23:00:00', 'evening', 'scheduled', 'Make arrangement for Flight Jk0125', '2025-09-06 04:52:53', '2025-09-06 04:52:53');

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `role_id` bigint unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `role_id`) VALUES
	(1, 'User', 'user@example.com', NULL, '$2y$12$PzlgoesA/72vmIfpo.0MwO6kgrtK6gnolvSF/F375HEWzByEtgVrO', NULL, '2025-08-26 00:21:13', '2025-08-26 00:21:13', 'user', 1),
	(2, 'admin', 'admin@example.com', NULL, '$2y$12$XvuavxB6i3ibcG0RsIDfg.QoqNxYSlVEaYS6ab4rdX3VWMa3Lg5S6', NULL, '2025-08-26 05:47:41', '2025-08-26 05:47:41', 'admin', 1);

CREATE TABLE IF NOT EXISTS `workers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `workers` (`id`, `first_name`, `last_name`, `email`, `phone`, `position`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Emma', 'Johnson', 'emma@example.com', '555-0101', 'Manager', 'active', '2025-08-20 08:02:31', '2025-08-20 08:02:31'),
	(2, 'Liam', 'Smith', 'liam@example.com', '555-0102', 'Supervisor', 'active', '2025-08-20 08:02:31', '2025-08-20 08:02:31'),
	(3, 'Olivia', 'Williams', 'olivia@example.com', '555-0103', 'Staff', 'active', '2025-08-20 08:02:31', '2025-08-20 08:02:31'),
	(4, 'Noah', 'Brown', 'noah@example.com', '555-0104', 'Staff', 'active', '2025-08-20 08:02:31', '2025-08-20 08:02:31'),
	(5, 'Ava', 'Jones', 'ava@example.com', '555-0105', 'Staff', 'active', '2025-08-20 08:02:31', '2025-08-20 08:02:31'),
	(6, 'William', 'Garcia', 'william@example.com', '555-0106', 'Staff', 'active', '2025-08-20 08:02:31', '2025-08-20 08:02:31'),
	(7, 'Sophia', 'Miller', 'sophia@example.com', '555-0107', 'Staff', 'active', '2025-08-20 08:02:31', '2025-08-20 08:02:31'),
	(8, 'Amri', 'Shanab', 'amri@example.com', '555-0103', 'Staff', 'active', '2025-08-21 05:38:40', '2025-08-21 05:38:41'),
	(9, 'Arham', 'Ahmed', 'arham@example.com', '555-0105', 'Staff', 'active', '2025-08-21 12:36:18', '2025-08-21 12:36:19'),
	(10, 'Afdhal', 'Ahamed', 'Afdhal@example.com', '555-0104', 'Staff', 'active', '2025-08-21 12:36:40', '2025-08-21 12:36:46'),
	(11, 'Rusni', 'Ahmed', 'Rusni@examoke.com', '555-0108', 'Staff', 'active', '2025-08-21 12:37:15', '2025-08-21 12:37:20'),
	(12, 'test', 'User', 'test@example.com', '555-8652', 'Staff', 'active', '2025-08-21 12:37:40', '2025-08-21 12:38:34'),
	(13, 'David', 'Beckham', 'david@example.com', '555-0108', 'Staff', 'active', '2025-08-21 12:38:28', '2025-08-21 12:38:35'),
	(15, 'Leo', 'Messi', 'leo@example.com', '555-0108', 'Staff', 'active', '2025-08-21 12:38:28', '2025-08-21 12:38:35'),
	(16, 'Jude', 'BEllingham', 'jude@example.com', '555-0108', 'Staff', 'active', '2025-08-21 12:38:28', '2025-08-21 12:38:35'),
	(18, 'Mishab', 'Noohi', 'mishab@gmail.com', '+94783099340', 'Manager', 'active', '2025-09-06 04:36:40', '2025-09-06 04:40:57');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
