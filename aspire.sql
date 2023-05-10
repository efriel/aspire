-- -------------------------------------------------------------
-- TablePlus 5.3.5(494)
--
-- https://tableplus.com/
--
-- Database: aspire
-- Generation Time: 2023-05-10 08:14:34.5600
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `account_loan`;
CREATE TABLE `account_loan` (
  `account_number` int(11) NOT NULL,
  `account_type_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `day_term` int(11) NOT NULL,
  `installment` int(11) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`account_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `account_savings`;
CREATE TABLE `account_savings` (
  `account_number` int(11) NOT NULL,
  `account_type_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `day_term` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`account_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `master_code`;
CREATE TABLE `master_code` (
  `code` varchar(1) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `master_gl_codes`;
CREATE TABLE `master_gl_codes` (
  `gl_code` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`gl_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `master_loan`;
CREATE TABLE `master_loan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_name` varchar(255) NOT NULL,
  `limit` int(11) NOT NULL,
  `tenor` int(11) NOT NULL,
  `gl_code` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `master_savings`;
CREATE TABLE `master_savings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_name` varchar(255) NOT NULL,
  `balance_minimum` int(11) NOT NULL,
  `gl_code` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `transaction_credit`;
CREATE TABLE `transaction_credit` (
  `transaction_id` int(11) NOT NULL,
  `account_number` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `transaction_credit_detail`;
CREATE TABLE `transaction_credit_detail` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) NOT NULL,
  `gl_code` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `transaction_debit`;
CREATE TABLE `transaction_debit` (
  `transaction_id` int(11) NOT NULL,
  `account_number` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `transaction_debit_detail`;
CREATE TABLE `transaction_debit_detail` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) NOT NULL,
  `gl_code` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `workflow`;
CREATE TABLE `workflow` (
  `name` varchar(255) NOT NULL,
  `flow` varchar(255) NOT NULL,
  UNIQUE KEY `workflow_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `account_loan` (`account_number`, `account_type_id`, `customer_id`, `day_term`, `installment`, `code`, `created_at`, `updated_at`) VALUES
(1000, 1, 98, 7, NULL, 'I', '2023-05-08 22:49:36', '2023-05-08 22:49:36'),
(1001, 2, 99, 30, NULL, 'I', '2023-05-08 22:49:36', '2023-05-08 22:49:36'),
(4625, 1, 435, 7, 3, 'I', '2023-05-10 00:02:45', '2023-05-10 00:10:00');

INSERT INTO `account_savings` (`account_number`, `account_type_id`, `customer_id`, `day_term`, `code`, `created_at`, `updated_at`) VALUES
(1000, 1, 98, 0, 'J', '2023-05-08 22:52:33', '2023-05-08 22:52:33'),
(1002, 2, 99, 30, 'J', '2023-05-08 22:52:33', '2023-05-08 22:52:33'),
(6173, 1, 435, 0, 'J', '2023-05-10 00:01:52', '2023-05-10 00:01:52');

INSERT INTO `customer` (`customer_id`, `user_id`, `name`, `address`, `created_at`, `updated_at`) VALUES
(98, 3, 'Customer A', 'Jakarta', '2023-05-08 22:49:36', '2023-05-08 22:49:36'),
(99, 4, 'Customer B', 'Singapore', '2023-05-08 22:49:36', '2023-05-08 22:49:36'),
(435, 435, 'Efriel', 'Jakarta', '2023-05-10 00:01:52', '2023-05-10 00:01:52');

INSERT INTO `master_code` (`code`, `name`) VALUES
('A', 'Customer Registered'),
('B', 'Saving Deposit'),
('C', 'Loan Request / Pending'),
('D', 'Loan Repayment / Pending'),
('E', 'Admin Approval'),
('F', 'Other'),
('G', 'Loan Approved'),
('H', 'Accounting Process'),
('I', 'Loan Paid'),
('J', 'Active');

INSERT INTO `master_gl_codes` (`gl_code`, `name`) VALUES
(1000, 'Saving Type Gold'),
(1001, 'Saving Type Platinum'),
(2000, 'Loan A'),
(2001, 'Loan B'),
(2003, 'Loan C'),
(4000, 'Service Charge');

INSERT INTO `master_loan` (`id`, `account_name`, `limit`, `tenor`, `gl_code`) VALUES
(1, 'Loan A', 20000, 3, 2000),
(2, 'Loan B', 50000, 6, 2001),
(3, 'Loan C', 100000, 12, 2003);

INSERT INTO `master_savings` (`id`, `account_name`, `balance_minimum`, `gl_code`) VALUES
(1, 'Gold', 25, 1000),
(2, 'Platinum', 100, 1001);

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2023_05_08_134008_create_master_loans_table', 1),
(7, '2023_05_08_134019_create_master_savings_table', 1),
(8, '2023_05_08_134025_create_master_gl_codes_table', 1),
(9, '2023_05_08_134047_create_master_codes_table', 1),
(10, '2023_05_08_134053_create_account_loans_table', 1),
(11, '2023_05_08_134100_create_account_savings_table', 1),
(13, '2023_05_08_134106_create_customers_table', 2),
(14, '2023_05_08_134111_create_staff_table', 2),
(15, '2023_05_08_134123_create_roles_table', 2),
(16, '2023_05_08_134134_create_transactions_table', 2),
(17, '2023_05_08_134141_create_transaction_details_table', 3),
(18, '2023_05_08_150125_create_transaction_savings_table', 3),
(19, '2023_05_08_150135_create_transaction_savings_details_table', 3),
(20, '2023_05_08_162357_create_workflows_table', 4);

INSERT INTO `role` (`role_id`, `name`) VALUES
(1, 'customer'),
(2, 'admin'),
(3, 'accounting');

INSERT INTO `staff` (`staff_id`, `user_id`, `name`, `address`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin Staff', 'Jakarta', 2, '2023-05-08 22:49:36', '2023-05-08 22:49:36'),
(2, 2, 'Accounting Staff', 'Singapore', 3, '2023-05-08 22:49:36', '2023-05-08 22:49:36');

INSERT INTO `transaction_credit` (`transaction_id`, `account_number`, `customer_id`, `total`, `staff_id`, `code`, `created_at`, `updated_at`) VALUES
(1683676965, 4625, 435, 10000, 1, 'H', '2023-05-10 00:02:45', '2023-05-10 00:03:31');

INSERT INTO `transaction_credit_detail` (`id`, `transaction_id`, `gl_code`, `amount`) VALUES
(6, '1683676965', 2000, 10000);

INSERT INTO `transaction_debit` (`transaction_id`, `account_number`, `customer_id`, `total`, `staff_id`, `code`, `created_at`, `updated_at`) VALUES
(1683676912, 6173, 435, 25, 1, 'H', '2023-05-10 00:01:52', '2023-05-10 00:01:52'),
(1683677271, 4625, 435, 3333, 1, 'I', '2023-05-10 00:07:51', '2023-05-10 00:07:51'),
(1683677309, 4625, 435, 3333, 1, 'I', '2023-05-10 00:08:29', '2023-05-10 00:08:29'),
(1683677400, 4625, 435, 3334, 1, 'I', '2023-05-10 00:10:00', '2023-05-10 00:10:00');

INSERT INTO `transaction_debit_detail` (`id`, `transaction_id`, `gl_code`, `amount`) VALUES
(13, '1683676912', 1000, 25),
(14, '1683677271', 2000, 3333),
(15, '1683677309', 2000, 3333),
(16, '1683677400', 2000, 3334);

INSERT INTO `users` (`id`, `username`, `password`, `email`, `email_verified_at`, `remember_token`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$X3Hf4nIX.PymDplXg0ZHu.7ysmfXAS/V0ZfJuKli.Jk3/CNOZqQGq', 'admin@email.com', NULL, NULL, 2, '2023-05-08 22:49:36', '2023-05-08 22:49:36'),
(2, 'accounting', '$2y$10$X3Hf4nIX.PymDplXg0ZHu.7ysmfXAS/V0ZfJuKli.Jk3/CNOZqQGq', 'accounting@email.com', NULL, NULL, 3, '2023-05-08 22:49:36', '2023-05-08 22:49:36'),
(3, 'user1', '$2y$10$X3Hf4nIX.PymDplXg0ZHu.7ysmfXAS/V0ZfJuKli.Jk3/CNOZqQGq', 'user1@email.com', NULL, NULL, 1, '2023-05-08 22:49:36', '2023-05-08 22:49:36'),
(4, 'user2', '$2y$10$X3Hf4nIX.PymDplXg0ZHu.7ysmfXAS/V0ZfJuKli.Jk3/CNOZqQGq', 'user2@email.com', NULL, NULL, 1, '2023-05-08 22:49:36', '2023-05-08 22:49:36'),
(435, 'efriel', '$2y$10$rW9XE5QqAGTa.qfG/8uxMezaopzwy/zbQUmmCBNz3w7m4did4WeYa', 'efriel@ymail.com', NULL, NULL, 1, '2023-05-10 00:01:52', '2023-05-10 00:01:52');

INSERT INTO `workflow` (`name`, `flow`) VALUES
('loan', 'C, E, F, B'),
('registration', 'A, B, E, F'),
('repayment', 'D, E, F, F, I'),
('savings', 'B, E, F');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;