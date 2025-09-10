-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2025 at 08:23 AM
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
-- Database: `gjp`
--

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `approvals`
--

INSERT INTO `approvals` (`id`, `module`, `role_id`, `order`, `created_at`, `updated_at`) VALUES
(13, 'requisition', 1, 1, '2024-09-02 04:49:39', '2024-09-02 04:49:39'),
(16, 'requisition', 3, 2, '2024-09-04 05:39:38', '2024-09-04 05:39:38'),
(17, 'requisition', 7, 3, '2024-09-04 05:39:45', '2024-09-04 05:39:45');

-- --------------------------------------------------------

--
-- Table structure for table `approval_statuses`
--

CREATE TABLE `approval_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module` varchar(255) NOT NULL,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `approval_statuses`
--

INSERT INTO `approval_statuses` (`id`, `module`, `module_id`, `user_id`, `role_id`, `status`, `created_at`, `updated_at`) VALUES
(10, 'requisition', 13, 1, 1, 1, '2024-09-04 05:56:41', '2024-09-04 05:56:41'),
(11, 'requisition', 14, 1, 1, 0, '2024-09-04 05:56:45', '2024-09-04 05:56:45'),
(12, 'requisition', 13, 27, 3, 1, '2024-09-04 07:22:07', '2024-09-04 07:22:07'),
(13, 'requisition', 13, 28, 7, 0, '2024-09-04 07:25:10', '2024-09-04 07:25:10'),
(14, 'requisition', 15, 1, 1, 1, '2024-09-04 07:48:44', '2024-09-04 07:48:44'),
(15, 'requisition', 16, 1, 1, 1, '2024-09-04 07:48:50', '2024-09-04 07:48:50'),
(16, 'requisition', 17, 1, 1, 1, '2024-09-04 11:34:55', '2024-09-04 11:34:55'),
(17, 'requisition', 18, 1, 1, 0, '2024-09-04 11:45:52', '2024-09-04 11:45:52'),
(18, 'requisition', 17, 27, 3, 1, '2024-09-04 11:55:03', '2024-09-04 11:55:03'),
(19, 'requisition', 17, 28, 7, 1, '2024-09-04 11:56:00', '2024-09-04 11:56:00'),
(20, 'requisition', 20, 1, 1, 1, '2024-09-06 06:53:33', '2024-09-06 06:53:33'),
(21, 'requisition', 22, 1, 1, 1, '2024-09-09 10:10:26', '2024-09-09 10:10:26'),
(22, 'requisition', 208, 39, 13, 0, '2025-01-09 05:16:55', '2025-01-09 05:16:55'),
(23, 'requisition', 208, 39, 13, 0, '2025-01-09 05:18:33', '2025-01-09 05:18:33'),
(24, 'requisition', 208, 39, 13, 0, '2025-01-09 05:23:27', '2025-01-09 05:23:27'),
(25, 'requisition', 208, 39, 13, 0, '2025-01-09 05:24:53', '2025-01-09 05:24:53');

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `holder_name` varchar(191) NOT NULL,
  `bank_name` varchar(191) NOT NULL,
  `account_number` varchar(191) NOT NULL,
  `chart_account_id` int(11) NOT NULL DEFAULT 0,
  `opening_balance` double(15,2) NOT NULL DEFAULT 0.00,
  `contact_number` varchar(191) NOT NULL,
  `bank_address` text NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`id`, `holder_name`, `bank_name`, `account_number`, `chart_account_id`, `opening_balance`, `contact_number`, `bank_address`, `created_by`, `created_at`, `updated_at`) VALUES
(10, 'Mr Shakil', 'IFIC Bank', '302632738479', 116, 1550500.00, '01736928111', 'Test', 1, '2024-02-27 06:13:36', '2024-09-09 05:54:34'),
(13, 'John Snow', 'DBBL', '302632738479', 119, 502400.00, '01736928111', 'test', 1, '2024-03-01 09:34:13', '2024-03-04 05:43:07');

-- --------------------------------------------------------

--
-- Table structure for table `bank_transfers`
--

CREATE TABLE `bank_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_account` int(11) NOT NULL DEFAULT 0,
  `to_account` int(11) NOT NULL DEFAULT 0,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `date` date NOT NULL,
  `payment_method` int(11) NOT NULL DEFAULT 0,
  `reference` varchar(191) DEFAULT NULL,
  `description` text NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_transfers`
--

INSERT INTO `bank_transfers` (`id`, `from_account`, `to_account`, `amount`, `date`, `payment_method`, `reference`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(6, 10, 13, 500.00, '2024-02-20', 0, 'demo', 'test', 1, '2024-02-27 12:45:11', '2024-03-01 18:11:55'),
(8, 13, 10, 100.00, '2024-02-27', 0, 'test', 'test', 1, '2024-02-27 17:10:06', '2024-03-01 18:12:16');

-- --------------------------------------------------------

--
-- Table structure for table `batches`
--

CREATE TABLE `batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `batch_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `batches`
--

INSERT INTO `batches` (`id`, `batch_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 240001, 'Dai Lowery1', 'Omnis magni consequa1', '2024-02-13 18:04:21', '2024-02-14 03:46:52'),
(3, 240002, 'Preston Molina', 'Facere ullam aut tot', '2024-02-15 13:05:11', '2024-02-15 13:05:11');

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bill_id` varchar(191) NOT NULL DEFAULT '0',
  `vender_id` int(11) NOT NULL,
  `bill_date` date NOT NULL,
  `due_date` date NOT NULL,
  `order_number` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `type` varchar(191) DEFAULT NULL,
  `user_type` varchar(191) DEFAULT NULL,
  `shipping_display` int(11) NOT NULL DEFAULT 1,
  `send_date` date DEFAULT NULL,
  `discount_apply` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `bill_id`, `vender_id`, `bill_date`, `due_date`, `order_number`, `status`, `type`, `user_type`, `shipping_display`, `send_date`, `discount_apply`, `category_id`, `created_by`, `created_at`, `updated_at`) VALUES
(5, '1', 5, '2024-03-04', '2024-04-02', 100, 0, 'Bill', 'vendor', 1, NULL, 0, 18, 1, '2024-03-01 17:07:40', '2024-03-01 17:07:40'),
(16, '3', 5, '2024-03-02', '2024-04-02', 100, 0, 'Bill', 'vendor', 1, NULL, 0, 18, 1, '2024-03-02 06:42:24', '2024-03-02 08:07:12'),
(19, '1', 5, '2024-02-28', '2024-02-28', 0, 4, 'Expense', 'vendor', 1, NULL, 0, 18, 1, '2024-03-02 08:08:15', '2024-03-02 09:04:33'),
(20, '2', 6, '2024-03-14', '2024-03-14', 0, 4, 'Expense', 'customer', 1, NULL, 0, 18, 1, '2024-03-02 08:55:07', '2024-03-02 09:02:25');

-- --------------------------------------------------------

--
-- Table structure for table `bill_accounts`
--

CREATE TABLE `bill_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chart_account_id` int(11) NOT NULL DEFAULT 0,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `description` varchar(191) DEFAULT NULL,
  `type` varchar(191) NOT NULL,
  `ref_id` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bill_accounts`
--

INSERT INTO `bill_accounts` (`id`, `chart_account_id`, `price`, `description`, `type`, `ref_id`, `created_at`, `updated_at`) VALUES
(2, 119, 10000.00, 'ERP application', 'Bill', 5, '2024-03-01 17:07:40', '2024-03-01 17:07:40'),
(5, 118, 10000.00, 'ERP application', 'Bill', 5, '2024-03-02 06:07:59', '2024-03-02 08:05:37'),
(13, 118, 1000.00, NULL, 'Bill', 16, '2024-03-02 08:07:12', '2024-03-02 08:07:12'),
(14, 116, 1000.00, NULL, 'Bill', 16, '2024-03-02 08:07:20', '2024-03-02 08:07:20'),
(15, 131, 50000.00, 'ERP application', 'Bill', 19, '2024-03-02 08:08:15', '2024-03-02 08:08:15'),
(16, 131, 5000.00, 'ERP application', 'Bill', 19, '2024-03-02 08:08:23', '2024-03-02 08:08:23'),
(17, 131, 50000.00, 'ERP application', 'Bill', 19, '2024-03-02 08:08:41', '2024-03-02 08:08:41'),
(18, 131, 50000.00, 'ERP application', 'Bill', 19, '2024-03-02 08:08:48', '2024-03-02 08:08:48'),
(19, 131, 50000.00, 'ERP application', 'Bill', 19, '2024-03-02 08:10:03', '2024-03-02 08:10:03'),
(20, 127, 500.00, 'MSI Monitor IPS panel', 'Bill', 20, '2024-03-02 08:55:07', '2024-03-02 08:55:07');

-- --------------------------------------------------------

--
-- Table structure for table `bill_payments`
--

CREATE TABLE `bill_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bill_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `account_id` int(11) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `add_receipt` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bill_payments`
--

INSERT INTO `bill_payments` (`id`, `bill_id`, `date`, `amount`, `account_id`, `payment_method`, `reference`, `add_receipt`, `description`, `created_at`, `updated_at`) VALUES
(8, 19, '2024-02-28', 550000.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 08:08:15', '2024-03-02 08:08:15'),
(9, 19, '2024-02-28', 505000.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 08:08:23', '2024-03-02 08:08:23'),
(10, 19, '2024-02-28', 550000.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 08:08:41', '2024-03-02 08:08:41'),
(11, 19, '2024-02-28', 550000.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 08:08:48', '2024-03-02 08:08:48'),
(12, 19, '2024-02-28', 550000.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 08:10:03', '2024-03-02 08:10:03'),
(13, 19, '2024-02-28', 550000.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 08:10:14', '2024-03-02 08:10:14'),
(14, 19, '2024-02-28', 550000.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 08:10:24', '2024-03-02 08:10:24'),
(15, 19, '2024-02-28', 550000.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 08:10:33', '2024-03-02 08:10:33'),
(16, 19, '2024-02-28', 550000.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 08:10:44', '2024-03-02 08:10:44'),
(17, 19, '2024-02-28', 550000.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 08:12:34', '2024-03-02 08:12:34'),
(18, 19, '2024-02-28', 550000.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 08:31:28', '2024-03-02 08:31:28'),
(19, 19, '2024-02-28', 550000.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 08:31:45', '2024-03-02 08:31:45'),
(20, 20, '2024-03-14', 45500.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 08:55:07', '2024-03-02 08:55:07'),
(21, 20, '2024-03-14', 45500.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 08:55:56', '2024-03-02 08:55:56'),
(22, 20, '2024-03-14', 45500.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 08:59:09', '2024-03-02 08:59:09'),
(23, 20, '2024-03-14', 45500.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 08:59:37', '2024-03-02 08:59:37'),
(24, 20, '2024-03-14', 45500.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 09:02:04', '2024-03-02 09:02:04'),
(25, 20, '2024-03-14', 45500.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 09:02:26', '2024-03-02 09:02:26'),
(26, 19, '2024-02-28', 550000.00, 10, 0, 'NULL', 'NULL', 'NULL', '2024-03-02 09:04:33', '2024-03-02 09:04:33');

-- --------------------------------------------------------

--
-- Table structure for table `bill_products`
--

CREATE TABLE `bill_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bill_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` double(25,2) NOT NULL DEFAULT 0.00,
  `tax` varchar(50) DEFAULT NULL,
  `discount` double(8,2) NOT NULL DEFAULT 0.00,
  `price` decimal(16,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bill_products`
--

INSERT INTO `bill_products` (`id`, `bill_id`, `product_id`, `quantity`, `tax`, `discount`, `price`, `description`, `created_at`, `updated_at`) VALUES
(5, 5, 5, 1.00, NULL, 0.00, 500000.00, 'ERP application', '2024-03-01 17:07:40', '2024-03-01 17:07:40'),
(16, 16, 5, 1.00, NULL, 0.00, 500000.00, NULL, '2024-03-02 06:42:24', '2024-03-02 06:42:24'),
(19, 19, 5, 1.00, NULL, 0.00, 500000.00, 'ERP application', '2024-03-02 08:08:15', '2024-03-02 08:08:48'),
(20, 20, 7, 1.00, NULL, 0.00, 45000.00, 'MSI Monitor IPS panel', '2024-03-02 08:55:07', '2024-03-02 08:55:07');

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `mobile_no` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`id`, `name`, `address`, `type`, `mobile_no`, `email`, `created_at`, `updated_at`) VALUES
(1, 'Dhaka', 'Dhaka', 'Headoffice', '01998345678', 'headofficedhaka779@gmail.com', '2024-09-11 10:40:00', '2024-09-11 11:08:42'),
(2, 'Mymensingh', 'Mymensingh', 'Warehouse', '01798345678', 'warehousemymensingh109@gmail.com', '2024-09-11 10:44:06', '2024-09-11 11:01:34'),
(5, 'Rajshahi', 'Rajshahi', 'Branch', '01998345078', 'rajshahibranch553@gmail.com', '2024-09-17 06:43:05', '2024-09-17 06:43:05');

-- --------------------------------------------------------

--
-- Table structure for table `branch_headoffice_logs`
--

CREATE TABLE `branch_headoffice_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` varchar(255) DEFAULT NULL,
  `requisition_id` varchar(255) DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `price_quantity` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branch_headoffice_logs`
--

INSERT INTO `branch_headoffice_logs` (`id`, `branch_id`, `requisition_id`, `product_id`, `price_quantity`, `date`, `user_id`, `created_at`, `updated_at`) VALUES
(1, '5', '3', '1', '[{\"price\":\"500.00\",\"quantity\":\"5\"}]', '2025-05-29', 34, '2025-05-29 12:10:11', '2025-05-29 12:10:11'),
(2, '5', '3', '2', '[{\"price\":\"150.00\",\"quantity\":\"1\"},{\"price\":\"250.00\",\"quantity\":\"2\"},{\"price\":\"670.00\",\"quantity\":\"4\"}]', '2025-05-29', 34, '2025-05-29 12:10:11', '2025-05-29 12:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `branch__products`
--

CREATE TABLE `branch__products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` varchar(255) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` varchar(255) NOT NULL,
  `batch` int(11) DEFAULT NULL,
  `details_stockin` text DEFAULT NULL,
  `remain_details` text DEFAULT NULL,
  `details_stockout` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branch__products`
--

INSERT INTO `branch__products` (`id`, `branch_id`, `product_id`, `price`, `stock`, `batch`, `details_stockin`, `remain_details`, `details_stockout`, `created_at`, `updated_at`) VALUES
(1, '2', '1', 500.00, '1', 1001, '[{\"requisition\":1,\"quantity\":\"7\",\"date\":\"22-05-2025\",\"store_by\":40}]', NULL, '[{\"qty\":\"1\",\"prc\":\"500.00\",\"type\":\"return\"},{\"requisition\":3,\"quantity\":\"5\",\"date\":\"29-05-2025\",\"store_by\":39,\"invoice_no\":\"INV-68384EA2D37C8\"}]', '2025-05-22 10:52:30', '2025-05-29 12:10:10'),
(2, '1', '1', NULL, '0', NULL, NULL, NULL, NULL, '2025-05-22 10:52:30', '2025-05-22 10:52:30'),
(3, '5', '1', NULL, '0', NULL, NULL, NULL, NULL, '2025-05-22 10:52:30', '2025-05-22 10:52:30'),
(4, '2', '2', 150.00, '0', 1002, '[{\"requisition\":\"warehouse\",\"quantity\":\"1\",\"date\":\"27-05-2025\",\"store_by\":39,\"invoice_no\":\"INV-68356BB405D7F\"}]', NULL, '[{\"requisition\":3,\"quantity\":\"1\",\"date\":\"29-05-2025\",\"store_by\":39,\"invoice_no\":\"INV-68384EA2D37C8\"}]', '2025-05-22 10:53:27', '2025-05-29 12:10:11'),
(5, '1', '2', NULL, '0', NULL, NULL, NULL, NULL, '2025-05-22 10:53:27', '2025-05-22 10:53:27'),
(6, '5', '2', NULL, '0', NULL, NULL, NULL, NULL, '2025-05-22 10:53:27', '2025-05-22 10:53:27'),
(7, '2', '3', 80.00, '2', 1003, '[{\"requisition\":1,\"quantity\":\"2\",\"date\":\"22-05-2025\",\"store_by\":40}]', NULL, NULL, '2025-05-22 10:54:04', '2025-05-22 10:57:07'),
(8, '1', '3', NULL, '0', NULL, NULL, NULL, NULL, '2025-05-22 10:54:04', '2025-05-22 10:54:04'),
(9, '5', '3', NULL, '0', NULL, NULL, NULL, NULL, '2025-05-22 10:54:04', '2025-05-22 10:54:04'),
(10, '2', '2', 250.00, '0', 1004, '{\"requisition\":1,\"quantity\":\"6\",\"date\":\"22-05-2025\",\"store_by\":40,\"0\":{\"requisition\":\"warehouse\",\"quantity\":\"2\",\"date\":\"27-05-2025\",\"store_by\":39,\"invoice_no\":\"INV-68356BBB3697B\"}}', NULL, '[{\"qty\":\"4\",\"prc\":\"250.00\",\"type\":\"return\"},{\"requisition\":3,\"quantity\":\"2\",\"date\":\"29-05-2025\",\"store_by\":39,\"invoice_no\":\"INV-68384EA2D37C8\"}]', '2025-05-22 10:57:07', '2025-05-29 12:10:11'),
(11, '2', '2', 670.00, '2', 1005, '{\"requisition\":2,\"quantity\":6,\"date\":\"27-05-2025\",\"store_by\":40}', NULL, '[{\"requisition\":3,\"quantity\":4,\"date\":\"29-05-2025\",\"store_by\":39,\"invoice_no\":\"INV-68384EA2D37C8\"}]', '2025-05-27 07:34:41', '2025-05-29 12:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `period` varchar(191) NOT NULL,
  `from` varchar(191) DEFAULT NULL,
  `to` varchar(191) DEFAULT NULL,
  `income_data` text DEFAULT NULL,
  `expense_data` text DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`id`, `name`, `period`, `from`, `to`, `income_data`, `expense_data`, `created_by`, `created_at`, `updated_at`) VALUES
(4, 'Quarterly', 'quarterly', '2024', NULL, '{\"14\":{\"January\":\"0\",\"February\":\"0\",\"March\":\"0\",\"April\":\"0\",\"May\":\"0\",\"June\":\"0\",\"July\":\"0\",\"August\":\"0\",\"September\":\"0\",\"October\":\"0\",\"November\":\"0\",\"December\":\"0\",\"Jan-Mar\":\"2000000\",\"Apr-Jun\":\"0\",\"Jul-Sep\":\"0\",\"Oct-Dec\":\"0\",\"Jan-Jun\":\"0\",\"Jul-Dec\":\"0\",\"Jan-Dec\":\"0\"}}', '{\"15\":{\"January\":\"0\",\"February\":\"0\",\"March\":\"0\",\"April\":\"0\",\"May\":\"0\",\"June\":\"0\",\"July\":\"0\",\"August\":\"0\",\"September\":\"0\",\"October\":\"0\",\"November\":\"0\",\"December\":\"0\",\"Jan-Mar\":\"20000\",\"Apr-Jun\":\"0\",\"Jul-Sep\":\"0\",\"Oct-Dec\":\"0\",\"Jan-Jun\":\"0\",\"Jul-Dec\":\"0\",\"Jan-Dec\":\"0\"}}', 1, '2024-01-26 04:30:50', '2024-01-27 05:11:19'),
(5, 'Test', 'half-yearly', '2024', NULL, '{\"17\":{\"January\":\"0\",\"February\":\"0\",\"March\":\"0\",\"April\":\"0\",\"May\":\"0\",\"June\":\"0\",\"July\":\"0\",\"August\":\"0\",\"September\":\"0\",\"October\":\"0\",\"November\":\"0\",\"December\":\"0\",\"Jan-Mar\":\"0\",\"Apr-Jun\":\"0\",\"Jul-Sep\":\"0\",\"Oct-Dec\":\"0\",\"Jan-Jun\":\"800\",\"Jul-Dec\":\"0\",\"Jan-Dec\":\"0\"}}', '{\"18\":{\"January\":\"0\",\"February\":\"0\",\"March\":\"0\",\"April\":\"0\",\"May\":\"0\",\"June\":\"0\",\"July\":\"0\",\"August\":\"0\",\"September\":\"0\",\"October\":\"0\",\"November\":\"0\",\"December\":\"0\",\"Jan-Mar\":\"0\",\"Apr-Jun\":\"0\",\"Jul-Sep\":\"0\",\"Oct-Dec\":\"0\",\"Jan-Jun\":\"0\",\"Jul-Dec\":\"0\",\"Jan-Dec\":\"0\"}}', 1, '2024-01-26 04:31:20', '2024-03-03 11:01:20'),
(6, 'Yearly', 'yearly', '2024', NULL, 'null', 'null', 1, '2024-01-26 04:31:35', '2024-01-26 04:31:35'),
(7, 'Monthly', 'monthly', '2024', NULL, 'null', 'null', 1, '2024-01-26 04:31:49', '2024-01-26 04:31:49'),
(9, 'Kirsten Short', 'monthly', '2023', NULL, '{\"17\":{\"January\":\"703\",\"February\":\"571\",\"March\":\"65\",\"April\":\"367\",\"May\":\"124\",\"June\":\"405\",\"July\":\"167\",\"August\":\"575\",\"September\":\"421\",\"October\":\"932\",\"November\":\"935\",\"December\":\"498\",\"Jan-Mar\":\"0\",\"Apr-Jun\":\"0\",\"Jul-Sep\":\"0\",\"Oct-Dec\":\"0\",\"Jan-Jun\":\"0\",\"Jul-Dec\":\"0\",\"Jan-Dec\":\"0\"}}', '{\"18\":{\"January\":\"50\",\"February\":\"40\",\"March\":\"91\",\"April\":\"24\",\"May\":\"15\",\"June\":\"25\",\"July\":\"47\",\"August\":\"49\",\"September\":\"41\",\"October\":\"57\",\"November\":\"82\",\"December\":\"60\",\"Jan-Mar\":\"0\",\"Apr-Jun\":\"0\",\"Jul-Sep\":\"0\",\"Oct-Dec\":\"0\",\"Jan-Jun\":\"0\",\"Jul-Dec\":\"0\",\"Jan-Dec\":\"0\"}}', 1, '2024-09-09 12:08:15', '2024-09-09 12:08:15');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(2, 'Orator', 'Motorhead', '2024-02-14 02:16:26', '2024-02-14 02:17:55'),
(3, 'Kelsey Patterson', 'Illum minus aperiam', '2024-02-15 00:57:23', '2024-02-15 00:57:23');

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_accounts`
--

CREATE TABLE `chart_of_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT 0,
  `sub_type` int(11) NOT NULL DEFAULT 0,
  `is_enabled` int(11) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chart_of_accounts`
--

INSERT INTO `chart_of_accounts` (`id`, `name`, `code`, `type`, `sub_type`, `is_enabled`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Salary & Allowance', 4000, 5, 21, 1, 'Salary & Allowance', 1, '2025-04-29 07:43:09', '2025-04-29 07:43:09'),
(2, 'Lunch Allowance', 4001, 5, 21, 1, 'Lunch Allowance', 1, '2025-04-29 07:43:48', '2025-04-29 07:43:48'),
(3, 'Travelling & Conveyance', 4002, 5, 21, 1, 'Travelling & Conveyance', 1, '2025-04-29 07:45:03', '2025-04-29 07:45:03'),
(4, 'Printing & Stationery', 4003, 5, 21, 1, 'Printing & Stationery', 1, '2025-04-29 07:46:46', '2025-04-29 07:46:46'),
(5, 'Office Rent', 4004, 5, 21, 1, 'Office Rent', 1, '2025-04-29 07:52:37', '2025-04-29 07:52:37'),
(6, 'Telephone & Postage', 4005, 5, 21, 1, 'Telephone & Postage', 1, '2025-04-29 07:53:14', '2025-04-29 07:53:14'),
(7, 'Electricity/Water/Gas bill', 4006, 5, 21, 1, 'Electricity/Water/Gas bill', 1, '2025-04-29 07:55:12', '2025-04-29 07:55:12'),
(8, 'Entertainment', 4007, 5, 21, 1, 'Entertainment', 1, '2025-04-29 07:55:58', '2025-04-29 07:55:58'),
(9, 'Bank Charge & Commission', 4008, 5, 21, 1, 'Bank Charge & Commission', 1, '2025-04-29 07:57:08', '2025-04-29 07:57:08'),
(10, 'Fuel Cost', 4009, 5, 21, 1, 'Fuel Cost', 1, '2025-04-29 07:58:34', '2025-04-29 07:58:34'),
(11, 'Advance Income Tax', 4010, 5, 21, 1, 'Advance Income Tax', 1, '2025-04-29 07:59:11', '2025-04-29 07:59:11'),
(12, 'Annual Conference', 4011, 5, 21, 1, 'Annual Conference', 1, '2025-04-29 08:00:07', '2025-04-29 08:00:07'),
(13, 'Automation Expenses', 4012, 5, 21, 1, 'Automation Expenses', 1, '2025-04-29 08:00:57', '2025-04-29 08:00:57'),
(14, 'Gratuity Expenses', 4013, 5, 21, 1, 'Gratuity Expenses', 1, '2025-04-29 08:01:52', '2025-04-29 08:01:52'),
(15, 'Other Expenses', 4014, 5, 21, 1, 'Other Expenses', 1, '2025-04-29 08:02:44', '2025-04-29 08:02:44'),
(16, 'Depreciation Expenses', 4015, 5, 21, 1, 'Depreciation Expenses', 1, '2025-04-29 08:03:29', '2025-04-29 08:03:29'),
(17, 'Printing', 4016, 5, 21, 1, 'Printing', 1, '2025-04-29 08:10:00', '2025-04-29 08:10:00'),
(18, 'Register', 4017, 5, 21, 1, 'Register', 1, '2025-04-29 08:11:12', '2025-04-29 08:11:12'),
(19, 'Binding books', 4018, 5, 21, 1, 'Binding books', 1, '2025-04-29 08:12:20', '2025-04-29 08:12:20'),
(20, 'Paper', 4019, 5, 21, 1, 'Paper', 1, '2025-04-29 08:13:11', '2025-04-29 08:13:11'),
(21, 'Stationary', 4020, 5, 21, 1, 'Stationary', 1, '2025-04-29 08:14:18', '2025-04-29 08:14:18'),
(22, 'Clener Meterials', 4021, 5, 21, 1, 'Clener Meterials', 1, '2025-04-29 08:16:55', '2025-04-29 08:16:55'),
(23, 'Electrice', 4022, 5, 21, 1, 'Electrice', 1, '2025-04-29 08:17:40', '2025-04-29 08:17:40'),
(24, 'Electronic', 4023, 5, 21, 1, 'Electronic', 1, '2025-04-29 08:18:33', '2025-04-29 08:18:33'),
(25, 'Cookeris', 4024, 5, 21, 1, 'Cookeris', 1, '2025-04-29 08:20:10', '2025-04-29 08:20:10'),
(26, 'Sales (Profit)', 3000, 4, 19, 1, 'Sales (Profit)', 1, '2025-04-29 08:23:26', '2025-04-29 08:23:26'),
(27, 'Damage Materials', 3001, 4, 19, 1, 'Damage Materials', 1, '2025-04-29 08:24:31', '2025-04-29 08:24:31'),
(28, 'Donation', 3002, 4, 20, 1, 'Donation', 1, '2025-04-29 08:25:46', '2025-04-29 08:25:46'),
(29, 'Bank Interest', 3003, 4, 20, 1, 'Bank Interest', 1, '2025-04-29 08:26:50', '2025-04-29 08:26:50'),
(30, 'Other Income', 3004, 4, 20, 1, 'Other Income', 1, '2025-04-29 08:27:33', '2025-04-29 08:27:33'),
(31, 'Service Charge', 3005, 4, 20, 1, 'Service Charge', 1, '2025-04-29 08:28:26', '2025-04-29 08:28:26'),
(32, 'Printing', 3006, 4, 19, 1, 'Printing', 1, '2025-04-29 08:29:33', '2025-04-29 08:29:33'),
(33, 'Register', 3007, 4, 19, 1, 'Register', 1, '2025-04-29 08:30:31', '2025-04-29 08:30:31'),
(34, 'Binding books', 3008, 4, 19, 1, 'Binding books', 1, '2025-04-29 08:36:14', '2025-04-29 08:36:14'),
(35, 'Paper', 3009, 4, 19, 1, 'Paper', 1, '2025-04-29 08:37:00', '2025-04-29 08:37:00'),
(36, 'Stationary', 3010, 4, 19, 1, 'Stationary', 1, '2025-04-29 08:37:46', '2025-04-29 08:37:46'),
(37, 'Clener Meterials', 3011, 4, 19, 1, 'Clener Meterials', 1, '2025-04-29 08:38:33', '2025-04-29 08:38:33'),
(38, 'Electrice', 3012, 4, 19, 1, 'Electrice', 1, '2025-04-29 08:39:16', '2025-04-29 08:39:16'),
(39, 'Electronic', 3013, 4, 19, 1, 'Electronic', 1, '2025-04-29 08:40:43', '2025-04-29 08:40:43'),
(40, 'Cookeris', 3014, 4, 19, 1, 'Cookeris', 1, '2025-04-29 08:41:44', '2025-04-29 08:41:44'),
(41, 'EQUITY/CAPITAL FUND', 5000, 3, 18, 1, 'EQUITY/CAPITAL FUND', 1, '2025-04-29 08:44:46', '2025-04-29 08:44:46'),
(42, 'Retained Surplus', 5001, 3, 18, 1, 'Retained Surplus', 1, '2025-04-29 08:45:50', '2025-04-29 08:45:50'),
(43, 'Surplus/Deficit from Income Statement', 5002, 3, 18, 1, 'Surplus/Deficit from Income Statement', 1, '2025-04-29 08:46:38', '2025-04-29 08:46:38'),
(44, 'Total EQUITY/CAPITAL FUND', 5003, 3, 18, 1, 'Total EQUITY/CAPITAL FUND', 1, '2025-04-29 08:47:15', '2025-04-29 08:47:15'),
(45, 'Provission For Expenses', 1000, 2, 16, 1, 'Provission For Expenses', 1, '2025-04-29 08:48:56', '2025-04-29 08:48:56'),
(46, 'Current Liabilities', 1001, 2, 16, 1, 'Current Liabilities', 1, '2025-04-29 08:49:51', '2025-04-29 08:49:51'),
(47, 'Savings', 1002, 2, 16, 1, 'Savings', 1, '2025-04-29 08:50:51', '2025-04-29 08:50:51'),
(48, 'Other Liabilities', 1003, 2, 16, 1, 'Other Liabilities', 1, '2025-04-29 08:52:30', '2025-04-29 08:52:30'),
(49, 'Payable', 1004, 2, 16, 1, 'Payable', 1, '2025-04-29 08:53:49', '2025-04-29 08:53:49'),
(50, 'Long Term Liabilities', 1005, 2, 17, 1, 'Long Term Liabilities', 1, '2025-04-29 08:55:16', '2025-04-29 08:55:16'),
(51, 'Fund Branch', 1006, 2, 17, 1, 'Fund Branch', 1, '2025-04-29 08:56:25', '2025-04-29 08:56:25'),
(52, 'Advanced', 2000, 1, 14, 1, 'Advanced', 1, '2025-04-29 08:58:14', '2025-04-29 08:58:14'),
(53, 'Donation Receivable', 2001, 1, 14, 1, 'Donation Receivable', 1, '2025-04-29 09:01:19', '2025-04-29 09:01:19'),
(54, 'Cash In Hand', 2002, 1, 14, 1, 'Cash In Hand', 1, '2025-04-29 09:02:03', '2025-04-29 09:02:03'),
(55, 'Cash at Bank', 2003, 1, 14, 1, 'Cash at Bank', 1, '2025-04-29 09:02:43', '2025-04-29 09:02:43'),
(56, 'Furniture & Fixture', 2004, 1, 15, 1, 'Furniture & Fixture', 1, '2025-04-29 09:03:31', '2025-04-29 09:03:31'),
(57, 'Electronics', 2005, 1, 15, 1, 'Electronics', 1, '2025-04-29 09:04:09', '2025-04-29 09:04:09'),
(58, 'Accounts Receivable', 2006, 1, 14, 1, 'Accounts Receivable', 1, '2025-04-29 10:08:29', '2025-04-29 10:47:14'),
(167, 'Sales Return A/C', 4025, 5, 21, 1, 'Sales Return A/C', 1, '2025-05-20 06:15:37', '2025-05-20 06:15:37'),
(168, 'Purchase Return A/C', 3015, 4, 19, 1, 'Purchase Return A/C', 1, '2025-05-20 06:28:36', '2025-05-20 06:28:36'),
(181, 'Inventory - Paper', 2007, 1, 14, 1, 'Paper', 29, '2025-05-22 10:52:30', '2025-05-22 10:52:30'),
(182, 'Sales - Paper', 3016, 4, 19, 1, 'Paper', 29, '2025-05-22 10:52:30', '2025-05-22 10:52:30'),
(183, 'Purchase - Paper', 4026, 5, 21, 1, 'Paper', 29, '2025-05-22 10:52:30', '2025-05-22 10:52:30'),
(184, 'Inventory - Mouse', 2008, 1, 14, 1, 'Mouse', 29, '2025-05-22 10:53:27', '2025-05-22 10:53:27'),
(185, 'Sales - Mouse', 3017, 4, 19, 1, 'Mouse', 29, '2025-05-22 10:53:27', '2025-05-22 10:53:27'),
(186, 'Purchase - Mouse', 4027, 5, 21, 1, 'Mouse', 29, '2025-05-22 10:53:27', '2025-05-22 10:53:27'),
(187, 'Inventory - Switch', 2009, 1, 14, 1, 'Switch', 29, '2025-05-22 10:54:04', '2025-05-22 10:54:04'),
(188, 'Sales - Switch', 3018, 4, 19, 1, 'Switch', 29, '2025-05-22 10:54:04', '2025-05-22 10:54:04'),
(189, 'Purchase - Switch', 4028, 5, 21, 1, 'Switch', 29, '2025-05-22 10:54:04', '2025-05-22 10:54:04');

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_account_sub_types`
--

CREATE TABLE `chart_of_account_sub_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL DEFAULT '1',
  `type` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chart_of_account_sub_types`
--

INSERT INTO `chart_of_account_sub_types` (`id`, `name`, `type`, `created_at`, `updated_at`) VALUES
(14, 'Current Asset', 1, '2024-01-25 18:00:00', '2024-01-25 18:00:00'),
(15, 'Fixed Asset', 1, '2024-01-25 18:00:00', '2024-01-25 18:00:00'),
(16, 'Current Liabilities', 2, '2024-01-25 18:00:00', '2024-01-25 18:00:00'),
(17, 'Fixed Liabilities', 2, '2024-01-25 18:00:00', '2024-01-25 18:00:00'),
(18, 'Owner\'s Equity', 3, '2024-01-25 18:00:00', '0000-00-00 00:00:00'),
(19, 'Operating Income', 4, '2024-01-25 18:00:00', '2024-01-25 18:00:00'),
(20, 'Non-Operating Income', 4, '2024-01-25 18:00:00', '2024-01-25 18:00:00'),
(21, 'Variable Expense', 5, '2024-01-25 18:00:00', '2024-01-25 18:00:00'),
(22, 'Semi Variable Expense', 5, '2024-01-25 18:00:00', '2024-01-25 18:00:00'),
(23, 'Fixed Expense', 5, '2024-01-25 18:00:00', '2024-01-25 18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_account_types`
--

CREATE TABLE `chart_of_account_types` (
  `id` bigint(20) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chart_of_account_types`
--

INSERT INTO `chart_of_account_types` (`id`, `name`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Assets', 1, '2023-12-17 10:23:32', '2023-12-17 10:23:32'),
(2, 'Liabilities', 1, '2023-12-17 10:23:32', '2023-12-17 10:23:32'),
(3, 'Equity', 1, '2023-12-17 10:23:32', '2023-12-17 10:23:32'),
(4, 'Income', 1, '2023-12-17 10:23:32', '2023-12-17 10:23:32'),
(5, 'Expenses', 1, '2023-12-17 10:23:32', '2023-12-17 10:23:32');

-- --------------------------------------------------------

--
-- Table structure for table `credit_notes`
--

CREATE TABLE `credit_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice` int(11) NOT NULL DEFAULT 0,
  `customer` int(11) NOT NULL DEFAULT 0,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `date` date NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `credit_notes`
--

INSERT INTO `credit_notes` (`id`, `invoice`, `customer`, `amount`, `date`, `description`, `created_at`, `updated_at`) VALUES
(4, 8, 6, 100000.00, '2024-03-12', 'test', '2024-03-01 08:59:22', '2024-03-01 09:28:51');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `tax_number` varchar(191) DEFAULT NULL,
  `contact` varchar(191) DEFAULT NULL,
  `avatar` varchar(100) NOT NULL DEFAULT '',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `billing_name` varchar(191) DEFAULT NULL,
  `billing_country` varchar(191) DEFAULT NULL,
  `billing_state` varchar(191) DEFAULT NULL,
  `billing_city` varchar(191) DEFAULT NULL,
  `billing_phone` varchar(191) DEFAULT NULL,
  `billing_zip` varchar(191) DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `shipping_name` varchar(191) DEFAULT NULL,
  `shipping_country` varchar(191) DEFAULT NULL,
  `shipping_state` varchar(191) DEFAULT NULL,
  `shipping_city` varchar(191) DEFAULT NULL,
  `shipping_phone` varchar(191) DEFAULT NULL,
  `shipping_zip` varchar(191) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `lang` varchar(191) NOT NULL DEFAULT 'en',
  `balance` double(20,2) NOT NULL DEFAULT 0.00,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_id`, `name`, `email`, `tax_number`, `contact`, `avatar`, `created_by`, `is_active`, `email_verified_at`, `billing_name`, `billing_country`, `billing_state`, `billing_city`, `billing_phone`, `billing_zip`, `billing_address`, `shipping_name`, `shipping_country`, `shipping_state`, `shipping_city`, `shipping_phone`, `shipping_zip`, `shipping_address`, `lang`, `balance`, `remember_token`, `created_at`, `updated_at`) VALUES
(6, 2, 'Mat Henry', 'company@tgcl.com', 'test', '09865435678', '', 1, 1, NULL, 'Mat Henry', 'test', 'test', 'test', '09865435678', '1200', 'test', 'Mat Henry', 'test', 'test', 'test', '09865435678', '1200', 'test', 'en', -251100.00, NULL, '2024-02-28 11:18:59', '2024-03-04 09:23:21'),
(9, 3, 'Ross Geller', 'ross@ossgmail.com', '984567', '09865435678', '', 1, 1, NULL, 'Ross Geller', 'England', 'London', 'London', '09865423423', '1100', 'test', 'Ross Geller', 'England', 'London', 'London', '09865423423', '1100', 'test', 'en', 2000.00, NULL, '2024-03-01 09:45:54', '2024-03-04 05:43:07');

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `type` varchar(191) NOT NULL,
  `module` varchar(191) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_field_values`
--

CREATE TABLE `custom_field_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `record_id` bigint(20) UNSIGNED NOT NULL,
  `field_id` bigint(20) UNSIGNED NOT NULL,
  `value` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `debit_notes`
--

CREATE TABLE `debit_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bill` int(11) NOT NULL DEFAULT 0,
  `vendor` int(11) NOT NULL DEFAULT 0,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `date` date NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` varchar(255) DEFAULT NULL,
  `department_name` varchar(255) DEFAULT NULL,
  `head_of_department` varchar(255) DEFAULT NULL,
  `department_start_date` varchar(255) DEFAULT NULL,
  `no_of_students` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

CREATE TABLE `goals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `type` varchar(191) NOT NULL,
  `from` varchar(191) DEFAULT NULL,
  `to` varchar(191) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `is_display` int(11) NOT NULL DEFAULT 1,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goals`
--

INSERT INTO `goals` (`id`, `name`, `type`, `from`, `to`, `amount`, `is_display`, `created_by`, `created_at`, `updated_at`) VALUES
(8, 'Test', '1', '2024-04-03', '2024-06-27', 500.00, 1, 1, '2024-03-03 11:50:05', '2024-03-03 11:51:42');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `send_date` date DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `ref_number` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `shipping_display` int(11) NOT NULL DEFAULT 1,
  `discount_apply` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_id`, `customer_id`, `issue_date`, `due_date`, `send_date`, `category_id`, `ref_number`, `status`, `shipping_display`, `discount_apply`, `created_by`, `created_at`, `updated_at`) VALUES
(8, 1, 6, '2024-02-29', '2024-02-29', '2024-03-04', 17, '34', 1, 1, 0, 1, '2024-02-28 18:00:00', '2024-03-04 09:23:21'),
(14, 3, 6, '2024-03-03', '2024-02-29', '2024-03-04', 17, NULL, 1, 1, 0, 1, '2024-03-03 13:38:11', '2024-03-04 07:06:11'),
(17, 4, 9, '2024-02-01', '2024-03-25', NULL, 17, '43798', 0, 1, 0, 1, '2024-03-04 09:16:40', '2024-03-04 09:17:26');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_bank_transfers`
--

CREATE TABLE `invoice_bank_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` varchar(191) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `receipt` varchar(191) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_payments`
--

CREATE TABLE `invoice_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `account_id` int(11) NOT NULL DEFAULT 0,
  `payment_method` int(11) NOT NULL DEFAULT 0,
  `order_id` varchar(191) DEFAULT NULL,
  `currency` varchar(191) DEFAULT NULL,
  `txn_id` varchar(191) DEFAULT NULL,
  `payment_type` varchar(191) NOT NULL DEFAULT 'Manually',
  `receipt` varchar(191) DEFAULT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `add_receipt` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_products`
--

CREATE TABLE `invoice_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` double(25,2) NOT NULL DEFAULT 0.00,
  `tax` varchar(50) DEFAULT NULL,
  `discount` double(8,2) NOT NULL DEFAULT 0.00,
  `price` decimal(16,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_products`
--

INSERT INTO `invoice_products` (`id`, `invoice_id`, `product_id`, `quantity`, `tax`, `discount`, `price`, `description`, `created_at`, `updated_at`) VALUES
(12, 8, 8, 1.00, NULL, 0.00, 100000.00, 'HP EliteBook super AMOLED display with touch screen', '2024-03-01 05:09:39', '2024-03-01 05:09:39'),
(13, 14, 5, 1.00, NULL, 500.00, 1000000.00, NULL, '2024-03-03 13:38:11', '2024-03-03 13:38:11'),
(17, 14, 7, 1.00, NULL, 0.00, 55000.00, 'MSI Monitor IPS panel', '2024-03-04 07:09:49', '2024-03-04 07:09:49'),
(19, 17, 8, 1.00, NULL, 500.00, 100000.00, 'HP EliteBook super AMOLED display with touch screen', '2024-03-04 09:16:40', '2024-03-04 09:16:40');

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `journal_id` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entries`
--

INSERT INTO `journal_entries` (`id`, `date`, `reference`, `description`, `journal_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '2025-05-22', 'INV-682F03038930A', 'Purchase for requisition #1', 100, 40, '2025-05-22 10:57:07', '2025-05-22 10:57:07'),
(2, '2025-05-26', 'INV-6834325E0E174', 'Received for return', 101, 40, '2025-05-26 09:20:30', '2025-05-26 09:20:30'),
(3, '2025-05-26', 'INV-6834325E0E174', 'Payment for return', 102, 40, '2025-05-26 09:20:30', '2025-05-26 09:20:30'),
(4, '2025-05-26', 'INV-68343D9F8D0AB', 'Received for return', 103, 40, '2025-05-26 10:08:31', '2025-05-26 10:08:31'),
(5, '2025-05-26', 'INV-68343D9F8D0AB', 'Payment for return', 104, 40, '2025-05-26 10:08:31', '2025-05-26 10:08:31'),
(6, '2025-05-26', 'INV-68343DE070206', 'Received for return', 105, 40, '2025-05-26 10:09:36', '2025-05-26 10:09:36'),
(7, '2025-05-26', 'INV-68343DE070206', 'Payment for return', 106, 40, '2025-05-26 10:09:36', '2025-05-26 10:09:36'),
(8, '2025-05-27', '56221-SD', 'Demo', 106, 29, '2025-05-27 07:09:45', '2025-05-27 07:09:45'),
(9, '2025-05-27', 'INV-68356B1128520', 'Purchase for requisition #2', 107, 40, '2025-05-27 07:34:41', '2025-05-27 07:34:41'),
(10, '2025-05-27', 'INV-68356BB405D7F', 'Purchase by warehouse own', 108, 39, '2025-05-27 07:37:24', '2025-05-27 07:37:24'),
(11, '2025-05-27', 'INV-68356BBB3697B', 'Purchase by warehouse own', 109, 39, '2025-05-27 07:37:31', '2025-05-27 07:37:31'),
(12, '2025-05-29', 'INV-68384EA2D37C8', 'Sales for requisition #3', 110, 39, '2025-05-29 12:10:10', '2025-05-29 12:10:10');

-- --------------------------------------------------------

--
-- Table structure for table `journal_items`
--

CREATE TABLE `journal_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `journal` int(11) NOT NULL DEFAULT 0,
  `account` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `debit` double(15,2) NOT NULL DEFAULT 0.00,
  `credit` double(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_items`
--

INSERT INTO `journal_items` (`id`, `date`, `journal`, `account`, `description`, `debit`, `credit`, `created_at`, `updated_at`) VALUES
(1, '2025-05-22', 1, 183, 'Paper', 3500.00, 0.00, '2025-05-22 10:57:07', '2025-05-22 10:57:07'),
(2, '2025-05-22', 1, 186, 'Mouse', 1500.00, 0.00, '2025-05-22 10:57:07', '2025-05-22 10:57:07'),
(3, '2025-05-22', 1, 189, 'Switch', 160.00, 0.00, '2025-05-22 10:57:07', '2025-05-22 10:57:07'),
(4, '2025-05-22', 1, 54, 'Payment by Cash In Hand', 0.00, 5160.00, '2025-05-22 10:57:07', '2025-05-22 10:57:07'),
(5, '2025-05-26', 2, 54, 'Mouse-reaosn', 250.00, 0.00, '2025-05-26 09:20:30', '2025-05-26 09:20:30'),
(6, '2025-05-26', 2, 168, 'Received by Cash In Hand', 0.00, 250.00, '2025-05-26 09:20:30', '2025-05-26 09:20:30'),
(7, '2025-05-26', 3, 168, 'Mouse-reaosn', 250.00, 0.00, '2025-05-26 09:20:30', '2025-05-26 09:20:30'),
(8, '2025-05-26', 3, 185, 'Payment by-Purchase Return A/C', 0.00, 250.00, '2025-05-26 09:20:30', '2025-05-26 09:20:30'),
(9, '2025-05-26', 4, 55, 'Paper-Paper again', 500.00, 0.00, '2025-05-26 10:08:31', '2025-05-26 10:08:31'),
(10, '2025-05-26', 4, 168, 'Received by Cash at Bank', 0.00, 500.00, '2025-05-26 10:08:31', '2025-05-26 10:08:31'),
(11, '2025-05-26', 5, 168, 'Paper-Paper again', 500.00, 0.00, '2025-05-26 10:08:31', '2025-05-26 10:08:31'),
(12, '2025-05-26', 5, 182, 'Payment by-Purchase Return A/C', 0.00, 500.00, '2025-05-26 10:08:31', '2025-05-26 10:08:31'),
(13, '2025-05-26', 6, 54, 'Mouse-Check 4 items', 250.00, 0.00, '2025-05-26 10:09:36', '2025-05-26 10:09:36'),
(14, '2025-05-26', 6, 168, 'Received by Cash In Hand', 0.00, 250.00, '2025-05-26 10:09:36', '2025-05-26 10:09:36'),
(15, '2025-05-26', 7, 168, 'Mouse-Check 4 items', 250.00, 0.00, '2025-05-26 10:09:36', '2025-05-26 10:09:36'),
(16, '2025-05-26', 7, 185, 'Payment by-Purchase Return A/C', 0.00, 250.00, '2025-05-26 10:09:36', '2025-05-26 10:09:36'),
(17, '2025-05-27', 8, 6, 'demo', 1250.00, 0.00, '2025-05-27 07:09:45', '2025-05-27 07:09:45'),
(18, '2025-05-27', 8, 54, 'demo', 0.00, 1250.00, '2025-05-27 07:09:45', '2025-05-27 07:09:45'),
(19, '2025-05-27', 9, 186, 'Mouse', 4020.00, 0.00, '2025-05-27 07:34:41', '2025-05-27 07:34:41'),
(20, '2025-05-27', 9, 54, 'Payment by Cash In Hand', 0.00, 4020.00, '2025-05-27 07:34:41', '2025-05-27 07:34:41'),
(21, '2025-05-27', 10, 186, 'Mouse', 150.00, 0.00, '2025-05-27 07:37:24', '2025-05-27 07:37:24'),
(22, '2025-05-27', 10, 54, 'Payment by Cash In Hand', 0.00, 150.00, '2025-05-27 07:37:24', '2025-05-27 07:37:24'),
(23, '2025-05-27', 11, 186, 'Mouse', 500.00, 0.00, '2025-05-27 07:37:31', '2025-05-27 07:37:31'),
(24, '2025-05-27', 11, 55, 'Payment by Cash at Bank', 0.00, 500.00, '2025-05-27 07:37:31', '2025-05-27 07:37:31'),
(25, '2025-05-29', 12, 182, 'Paper', 0.00, 2500.00, '2025-05-29 12:10:11', '2025-05-29 12:10:11'),
(26, '2025-05-29', 12, 185, 'Mouse', 0.00, 150.00, '2025-05-29 12:10:11', '2025-05-29 12:10:11'),
(27, '2025-05-29', 12, 185, 'Mouse', 0.00, 500.00, '2025-05-29 12:10:11', '2025-05-29 12:10:11'),
(28, '2025-05-29', 12, 185, 'Mouse', 0.00, 2680.00, '2025-05-29 12:10:11', '2025-05-29 12:10:11'),
(29, '2025-05-29', 12, 54, 'Received by Cash In Hand', 5830.00, 0.00, '2025-05-29 12:10:11', '2025-05-29 12:10:11');

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
(1, '2014_08_12_000000_create_users_table', 1),
(2, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(3, '2022_08_03_061844_create_user_types_table', 1),
(4, '2022_08_03_061918_create_role_type_users_table', 1),
(5, '2023_02_26_224452_create_students_table', 1),
(6, '2023_04_17_223959_create_teachers_table', 1),
(7, '2023_10_15_120501_create_subjects_table', 1),
(8, '2023_11_06_120643_create_departments_table', 1),
(15, '2024_01_31_121641_role', 3),
(18, '2024_02_03_060531_create_page_permissions_table', 5),
(20, '2024_02_07_122323_create_warehouses_table', 6),
(21, '2024_02_13_125154_create_batches_table', 6),
(22, '2024_02_14_054726_create_categories_table', 7),
(24, '2024_03_22_152634_create_requisitions_table', 9),
(25, '2024_03_23_111707_create_requisition_items_table', 9),
(26, '2024_04_16_130316_create_approvals_table', 9),
(28, '2024_04_19_170907_create_approval_statuses_table', 10),
(30, '2024_01_30_100015_2024_01_30_095657_create_branch_table', 12),
(31, '2024_09_19_055511_create_branch__products_table', 13),
(32, '2024_09_23_163509_create_settings_table', 14),
(33, '2024_10_25_151502_create_product_expenses_table', 15),
(34, '2024_10_28_155446_create_product_returns_table', 16),
(35, '2024_12_05_153241_create_product_ledger', 17),
(36, '2024_12_05_161051_create_product_ledgers_table', 18),
(37, '2024_12_09_160017_create_projects_table', 19),
(38, '2025_02_17_131740_create_product_categories_table', 20),
(39, '2024_02_14_095445_create_products_table', 21),
(41, '2024_02_22_122425_create_chart_of_accounts_table', 23),
(42, '2025_05_05_165138_create_product_account_maps_table', 24),
(43, '2025_03_24_125350_create_branch_headoffice_logs_table', 25),
(44, '2025_05_13_183405_create_product_ledger_b_h_s_table', 26),
(45, '2025_05_21_135917_create_product_return_warehouses_table', 27);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\Admin', 1),
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\Admin', 3),
(11, 'App\\Models\\User', 29),
(12, 'App\\Models\\User', 40),
(12, 'App\\Models\\User', 42),
(13, 'App\\Models\\User', 39),
(14, 'App\\Models\\User', 34),
(14, 'App\\Models\\User', 41),
(14, 'App\\Models\\User', 45),
(14, 'App\\Models\\User', 46),
(14, 'App\\Models\\User', 47);

-- --------------------------------------------------------

--
-- Table structure for table `page_permissions`
--

CREATE TABLE `page_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `role_type` varchar(255) DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `page_permissions`
--

INSERT INTO `page_permissions` (`id`, `user_id`, `role_type`, `route`, `created_at`, `updated_at`) VALUES
(6, NULL, 'Super Admin', 'student/add/save, student/update, student/delete', '2024-02-05 00:49:06', '2024-02-05 00:49:06'),
(7, NULL, 'Normal User', 'role/list/page, role/add/page, role/save', '2024-02-06 00:38:20', '2024-02-06 00:38:20'),
(8, NULL, 'Admin', 'branch/list, branch/grid, branch/add/page, branch/add/save', '2024-02-06 00:44:15', '2024-02-06 00:44:15'),
(9, NULL, NULL, 'page/list/page, page/add/page, page/update', '2024-02-16 20:10:50', '2024-02-16 20:10:50');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `account_id` int(11) NOT NULL,
  `chart_account_id` int(11) NOT NULL DEFAULT 0,
  `vender_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `recurring` varchar(191) DEFAULT NULL,
  `payment_method` int(11) NOT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `add_receipt` varchar(191) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `date`, `amount`, `account_id`, `chart_account_id`, `vender_id`, `description`, `category_id`, `recurring`, `payment_method`, `reference`, `add_receipt`, `created_by`, `created_at`, `updated_at`) VALUES
(3, '2024-03-13', 1000.00, 10, 0, 5, 'January Bill', 18, NULL, 0, '23867', '1709372511_1709279935_the-sad-side-of-deadpool-g2.jpg', 1, '2024-03-02 09:41:51', '2024-03-04 06:36:33'),
(4, '2024-03-13', 100.00, 10, 0, 5, 'Electricity of February', 18, NULL, 0, '123434', '1709372562_1709279935_the-sad-side-of-deadpool-g2.jpg', 1, '2024-03-02 09:42:42', '2024-03-04 06:36:56');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `group_name`, `created_at`, `updated_at`) VALUES
(1, 'View Unverify User', 'user', 'User', '2024-11-12 05:53:06', '2024-11-12 05:53:06'),
(2, 'Edit Unverify User', 'user', 'User', '2024-11-12 05:53:06', '2024-11-12 05:53:06'),
(3, 'Delete Unverify User', 'user', 'User', '2024-11-12 05:54:13', '2024-11-12 05:54:13'),
(4, 'View Verify User', 'user', 'User', '2024-11-12 05:57:42', '2024-11-12 05:57:42'),
(5, 'Edit Verify User', 'user', 'User', '2024-11-12 05:59:36', '2024-11-12 05:59:36'),
(6, 'role.create', 'user', 'Role', '2024-11-12 06:22:54', '2024-11-12 06:22:54'),
(7, 'role.view', 'user', 'Role', '2024-11-12 06:22:54', '2024-11-12 06:22:54'),
(8, 'role.edit', 'user', 'Role', '2024-11-12 06:30:04', '2024-11-12 06:30:04'),
(9, 'role.delete', 'user', 'Role', '2024-11-12 06:30:13', '2024-11-12 06:30:13'),
(10, 'Create Requisition', 'user', 'Requisition', '2024-11-12 07:07:33', '2024-11-12 07:07:33'),
(11, 'View Requisition', 'user', 'Requisition', '2024-11-12 07:07:40', '2024-11-12 07:07:40'),
(12, 'Edit Requisition', 'user', 'Requisition', '2024-11-12 07:07:49', '2024-11-12 07:07:49'),
(13, 'Delete Requisition', 'user', 'Requisition', '2024-11-12 07:07:57', '2024-11-12 07:07:57'),
(14, 'List', 'user', 'Completed Order', '2024-11-12 07:08:06', '2024-11-12 07:08:06'),
(15, 'List Approval', 'user', 'Approval', '2024-11-13 07:49:54', '2024-11-13 07:49:54'),
(16, 'Accept List', 'user', 'Approval', '2024-11-12 07:51:42', '2024-11-12 07:51:42'),
(17, 'Reject List', 'user', 'Approval', '2024-11-12 07:51:51', '2024-11-12 07:51:51'),
(18, 'Pending Purchase', 'user', 'Pending Purchase', '2024-11-12 08:04:26', '2024-11-12 08:04:26'),
(19, 'Purchase Collection', 'user', 'Purchase Collection', '2024-11-21 12:38:02', '2024-11-21 12:38:02'),
(20, 'Reject Collection', 'user', 'Reject Collection', '2024-11-22 05:49:58', '2024-11-22 05:49:58'),
(21, 'Approved List', 'user', 'Purchase Approvals', '2024-11-22 06:00:59', '2024-11-22 06:00:59'),
(22, 'Reject List', 'user ', 'Purchase Approvals', '2024-11-22 06:01:52', '2024-11-22 06:01:52'),
(23, 'Stock List and Details', 'user', 'Stock', '2024-11-25 04:47:04', '2024-11-25 04:47:10'),
(24, 'Expense Entry', 'user', 'Expense', '2024-11-25 04:57:43', '2024-11-25 04:57:43'),
(25, 'Expense List', 'user', 'Expense', '2024-11-25 04:58:24', '2024-11-25 04:58:24'),
(26, 'Damage/Return List', 'user', 'Damage/Return', '2024-11-25 05:04:01', '2024-11-25 05:04:01'),
(27, 'Order List', 'user', 'Order Request', '2024-11-25 05:41:08', '2024-11-25 05:41:08'),
(28, 'Create', 'user', 'Branch', '2024-11-25 05:50:39', '2024-11-25 05:50:39'),
(29, 'Branch List', 'user', 'Branch', '2024-11-25 06:03:58', '2024-11-25 06:03:58'),
(30, 'Product Create', 'user', 'Product', '2024-11-26 06:08:32', '2024-11-26 06:08:32'),
(31, 'Product List', 'user', 'Product', '2024-11-25 06:10:10', '2024-11-25 06:10:10'),
(32, 'Barcode', 'user', 'Product', '2024-11-25 06:11:29', '2024-11-25 06:11:29'),
(33, 'Ledger Report', 'user', 'Report', '2024-11-25 06:23:16', '2024-11-25 06:23:16'),
(34, 'Settings', 'user', 'Settings', '2024-11-25 06:30:14', '2024-11-25 06:30:14'),
(35, 'Project List', 'user', 'Project', '2024-12-11 12:03:18', '2024-12-11 12:03:18');

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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` bigint(20) DEFAULT NULL,
  `product_category_id` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `batch` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `code`, `product_category_id`, `description`, `price`, `batch`, `image`, `sku`, `created_at`, `updated_at`) VALUES
(1, 'Paper', 25001, '4', 'Paper', 500.00, 1001, NULL, 'SK101', '2025-05-22 10:52:30', '2025-05-22 10:52:30'),
(2, 'Mouse', 25002, '5', 'Mouse', 150.00, 1002, NULL, 'SK102', '2025-05-22 10:53:27', '2025-05-22 10:53:27'),
(3, 'Switch', 25003, '6', 'Switch', 80.00, 1003, NULL, 'SK103', '2025-05-22 10:54:04', '2025-05-22 10:54:04');

-- --------------------------------------------------------

--
-- Table structure for table `product_account_maps`
--

CREATE TABLE `product_account_maps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_category_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_code` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `account_asset_inventory_code` int(11) DEFAULT NULL,
  `account_expense_code` int(11) DEFAULT NULL,
  `account_income_code` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_account_maps`
--

INSERT INTO `product_account_maps` (`id`, `product_category_id`, `product_id`, `product_code`, `product_name`, `account_asset_inventory_code`, `account_expense_code`, `account_income_code`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 25001, 'Paper', 2007, 4026, 3016, '2025-05-22 10:52:30', '2025-05-22 10:52:30'),
(2, 5, 2, 25002, 'Mouse', 2008, 4027, 3017, '2025-05-22 10:53:27', '2025-05-22 10:53:27'),
(3, 6, 3, 25003, 'Switch', 2009, 4028, 3018, '2025-05-22 10:54:04', '2025-05-22 10:54:04');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Printing Goods', 1, '2025-02-17 01:41:45', '2025-02-17 01:41:45'),
(2, 'Register & Goods', 1, '2025-02-17 01:42:38', '2025-02-17 01:42:38'),
(3, 'Binding Books', 1, '2025-02-17 01:43:04', '2025-02-17 01:43:04'),
(4, 'Paper Goods', 1, '2025-02-17 01:43:31', '2025-02-17 01:43:31'),
(5, 'Stationary', 1, '2025-02-17 01:43:56', '2025-02-17 01:43:56'),
(6, 'Electrice Goods', 1, '2025-02-17 01:44:30', '2025-02-17 01:44:30'),
(7, 'Cookeris Goods', 1, '2025-02-17 01:45:32', '2025-02-17 02:02:45'),
(8, 'Cleaner Materials', 1, '2025-02-17 01:46:41', '2025-02-17 02:02:35');

-- --------------------------------------------------------

--
-- Table structure for table `product_expenses`
--

CREATE TABLE `product_expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` varchar(255) NOT NULL,
  `consignee_name` varchar(255) NOT NULL,
  `expense_date` date NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `expense_amount` varchar(255) NOT NULL,
  `expense_price` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_ledgers`
--

CREATE TABLE `product_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) NOT NULL,
  `chart_of_account_id` bigint(20) NOT NULL,
  `chart_of_account_code` bigint(20) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `entry_date` date NOT NULL,
  `narration` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `branch_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `consignee_name` varchar(255) DEFAULT NULL,
  `quantity` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `batch` varchar(255) DEFAULT NULL,
  `requisition_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_ledgers`
--

INSERT INTO `product_ledgers` (`id`, `invoice_no`, `chart_of_account_id`, `chart_of_account_code`, `payment_method`, `entry_date`, `narration`, `type`, `user_id`, `branch_id`, `product_id`, `consignee_name`, `quantity`, `price`, `batch`, `requisition_id`, `created_at`, `updated_at`) VALUES
(1, 'INV-682F03038930A', 54, 2002, 'Cash In Hand', '2025-05-22', 'purchaseteam', 'stockin', 40, 2, 1, NULL, '7', 500.00, '1001', '1', '2025-05-22 10:57:07', '2025-05-22 10:57:07'),
(2, 'INV-682F03038930A', 54, 2002, 'Cash In Hand', '2025-05-22', 'purchaseteam', 'stockin', 40, 2, 2, NULL, '6', 250.00, '1004', '1', '2025-05-22 10:57:07', '2025-05-22 10:57:07'),
(3, 'INV-682F03038930A', 54, 2002, 'Cash In Hand', '2025-05-22', 'purchaseteam', 'stockin', 40, 2, 3, NULL, '2', 80.00, '1003', '1', '2025-05-22 10:57:07', '2025-05-22 10:57:07'),
(4, 'INV-6834325E0E174', 54, 2002, 'Cash In Hand', '2025-05-26', 'warehouse', 'stockout', 40, 2, 2, NULL, '2', 250.00, '1004', 'return', '2025-05-26 09:20:30', '2025-05-26 09:20:30'),
(5, 'INV-68343D9F8D0AB', 55, 2003, 'Cash at Bank', '2025-05-26', 'warehouse', 'stockout', 40, 2, 1, NULL, '1', 500.00, '1001', 'return', '2025-05-26 10:08:31', '2025-05-26 10:08:31'),
(6, 'INV-68343DE070206', 54, 2002, 'Cash In Hand', '2025-05-26', 'warehouse', 'stockout', 40, 2, 2, NULL, '4', 250.00, '1004', 'return', '2025-05-26 10:09:36', '2025-05-26 10:09:36'),
(7, 'INV-68356B1128520', 54, 2002, 'Cash In Hand', '2025-05-27', 'purchaseteam', 'stockin', 40, 2, 2, NULL, '6', 670.00, '1005', '2', '2025-05-27 07:34:41', '2025-05-27 07:34:41'),
(8, 'INV-68356BB405D7F', 54, 2002, 'Cash In Hand', '2025-05-27', 'warehouse', 'stockin', 39, 2, 2, NULL, '1', 150.00, '1002', 'own', '2025-05-27 07:37:24', '2025-05-27 07:37:24'),
(9, 'INV-68356BBB3697B', 55, 2003, 'Cash at Bank', '2025-05-27', 'warehouse', 'stockin', 39, 2, 2, NULL, '2', 250.00, '1004', 'own', '2025-05-27 07:37:31', '2025-05-27 07:37:31'),
(10, 'INV-68384EA2D37C8', 54, 2002, 'Cash In Hand', '2025-05-29', 'warehouse', 'stockout', 39, 2, 1, NULL, '5', 500.00, '1001', '3', '2025-05-29 12:10:10', '2025-05-29 12:10:10'),
(11, 'INV-68384EA2D37C8', 54, 2002, 'Cash In Hand', '2025-05-29', 'warehouse', 'stockout', 39, 2, 2, NULL, '1', 150.00, '1002', '3', '2025-05-29 12:10:11', '2025-05-29 12:10:11'),
(12, 'INV-68384EA2D37C8', 54, 2002, 'Cash In Hand', '2025-05-29', 'warehouse', 'stockout', 39, 2, 2, NULL, '2', 250.00, '1004', '3', '2025-05-29 12:10:11', '2025-05-29 12:10:11'),
(13, 'INV-68384EA2D37C8', 54, 2002, 'Cash In Hand', '2025-05-29', 'warehouse', 'stockout', 39, 2, 2, NULL, '4', 670.00, '1005', '3', '2025-05-29 12:10:11', '2025-05-29 12:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `product_ledger_b_h_s`
--

CREATE TABLE `product_ledger_b_h_s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entry_date` date DEFAULT NULL,
  `narration` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `branch_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) DEFAULT NULL,
  `consignee_name` varchar(255) DEFAULT NULL,
  `quantity` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `requisition_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_returns`
--

CREATE TABLE `product_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` varchar(255) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `return_quantity` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `notification_status` varchar(255) DEFAULT NULL,
  `deny_status` varchar(255) DEFAULT NULL,
  `deny_reason_note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_return_warehouses`
--

CREATE TABLE `product_return_warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` varchar(255) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `return_quantity` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `notification_status` varchar(255) DEFAULT NULL,
  `deny_status` varchar(255) DEFAULT NULL,
  `deny_reason_note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_return_warehouses`
--

INSERT INTO `product_return_warehouses` (`id`, `branch_id`, `product_id`, `return_quantity`, `price`, `reason`, `date`, `user_id`, `status`, `notification_status`, `deny_status`, `deny_reason_note`, `created_at`, `updated_at`) VALUES
(1, '2', '1', '1', 500.00, 'Return this paper', '2025-05-22', '39', NULL, NULL, NULL, NULL, '2025-05-22 11:12:07', '2025-05-22 12:49:03'),
(2, '2', '1', '1', 500.00, 'for return purchase', '2025-05-23', '39', NULL, NULL, '1', 'just reject', '2025-05-23 04:58:54', '2025-05-23 05:47:17'),
(3, '2', '2', '2', 250.00, 'reaosn', '2025-05-23', '39', '1', NULL, NULL, NULL, '2025-05-23 05:49:16', '2025-05-26 09:20:30'),
(4, '2', '1', '1', 500.00, 'Paper again', '2025-05-26', '39', '1', NULL, NULL, NULL, '2025-05-26 10:06:53', '2025-05-26 10:08:31'),
(5, '2', '2', '4', 250.00, 'Check 4 items', '2025-05-26', '39', '1', NULL, NULL, NULL, '2025-05-26 10:09:20', '2025-05-26 10:09:36');

-- --------------------------------------------------------

--
-- Table structure for table `product_services`
--

CREATE TABLE `product_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `sku` varchar(191) NOT NULL,
  `sale_price` decimal(16,2) NOT NULL DEFAULT 0.00,
  `purchase_price` decimal(16,2) NOT NULL DEFAULT 0.00,
  `quantity` double(8,2) NOT NULL DEFAULT 0.00,
  `tax_id` varchar(50) DEFAULT NULL,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `unit_id` int(11) NOT NULL DEFAULT 0,
  `type` varchar(191) NOT NULL,
  `sale_chartaccount_id` int(11) NOT NULL DEFAULT 0,
  `expense_chartaccount_id` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `pro_image` varchar(191) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_services`
--

INSERT INTO `product_services` (`id`, `name`, `sku`, `sale_price`, `purchase_price`, `quantity`, `tax_id`, `category_id`, `unit_id`, `type`, `sale_chartaccount_id`, `expense_chartaccount_id`, `description`, `pro_image`, `created_by`, `created_at`, `updated_at`) VALUES
(5, 'ERP solution', 'e22r00p', 1000000.00, 500000.00, 13.00, '', 13, 2, 'product', 131, 132, 'ERP application', 'cashFlowAc.jpg', 1, '2024-01-26 23:15:42', '2024-03-04 09:10:45'),
(7, 'Monitor', 'T0F201', 55000.00, 45000.00, 100.00, '', 13, 2, 'product', 117, 132, 'MSI Monitor IPS panel', NULL, 1, '2024-01-30 00:43:26', '2024-03-04 07:09:49'),
(8, 'LAPTOP', 'L0912P', 100000.00, 60000.00, 97.00, '', 16, 2, 'product', 117, 132, 'HP EliteBook super AMOLED display with touch screen', NULL, 1, '2024-01-30 00:43:26', '2024-03-04 09:17:26');

-- --------------------------------------------------------

--
-- Table structure for table `product_service_categories`
--

CREATE TABLE `product_service_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `type` varchar(191) NOT NULL DEFAULT '0',
  `chart_account_id` int(11) NOT NULL DEFAULT 0,
  `color` varchar(191) NOT NULL DEFAULT '#fc544b',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_service_categories`
--

INSERT INTO `product_service_categories` (`id`, `name`, `type`, `chart_account_id`, `color`, `created_by`, `created_at`, `updated_at`) VALUES
(13, 'Software', 'product & service', 0, 'FFFFFF', 1, '2024-01-26 23:11:44', '2024-01-26 23:11:44'),
(16, 'Hardware', 'product & service', 0, '191CFF', 1, '2024-01-31 01:03:54', '2024-01-31 01:05:23'),
(17, 'Good sold', 'income', 117, '#fc544b', 1, '2024-02-27 18:00:00', '2024-03-03 13:10:45'),
(18, 'Electricity Bill', 'expense', 118, '#fc544b', 1, '2024-02-29 18:00:00', '2024-03-03 13:10:33');

-- --------------------------------------------------------

--
-- Table structure for table `product_service_units`
--

CREATE TABLE `product_service_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_service_units`
--

INSERT INTO `product_service_units` (`id`, `name`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'BDT', 1, '2023-12-26 05:45:01', '2023-12-26 05:45:01'),
(3, 'USD', 1, '2023-12-26 05:45:08', '2023-12-26 05:45:08');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'ABC', 1, '2024-12-09 10:38:25', '2024-12-09 10:38:25'),
(3, 'Test', 1, '2024-12-09 10:41:44', '2024-12-09 10:41:44'),
(5, 'Damian', 1, '2024-12-11 10:29:24', '2024-12-11 10:29:24'),
(6, 'New', 1, '2024-12-17 06:41:49', '2024-12-17 06:41:49'),
(7, 'Software', 1, '2024-12-17 06:42:00', '2024-12-17 06:42:00');

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

CREATE TABLE `proposals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proposal_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `issue_date` date NOT NULL,
  `send_date` date DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `discount_apply` int(11) NOT NULL DEFAULT 0,
  `is_convert` int(11) NOT NULL DEFAULT 0,
  `converted_invoice_id` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proposals`
--

INSERT INTO `proposals` (`id`, `proposal_id`, `customer_id`, `issue_date`, `send_date`, `category_id`, `status`, `discount_apply`, `is_convert`, `converted_invoice_id`, `created_by`, `created_at`, `updated_at`) VALUES
(6, 3, 6, '2024-02-29', NULL, 17, 1, 0, 0, 0, 1, '2024-02-29 10:30:49', '2024-02-29 10:46:28'),
(8, 5, 6, '2024-03-03', NULL, 17, 0, 0, 0, 0, 1, '2024-03-03 13:30:06', '2024-03-03 13:30:06'),
(11, 6, 6, '2024-03-03', NULL, 17, 0, 0, 0, 0, 1, '2024-03-03 13:34:10', '2024-03-03 13:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `proposal_products`
--

CREATE TABLE `proposal_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `proposal_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` double(25,2) NOT NULL DEFAULT 0.00,
  `tax` varchar(50) DEFAULT NULL,
  `discount` double(8,2) NOT NULL DEFAULT 0.00,
  `price` decimal(16,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proposal_products`
--

INSERT INTO `proposal_products` (`id`, `proposal_id`, `product_id`, `quantity`, `tax`, `discount`, `price`, `description`, `created_at`, `updated_at`) VALUES
(7, 6, 5, 1.00, NULL, 500.00, 1000000.00, NULL, '2024-02-29 10:30:49', '2024-02-29 10:30:49'),
(9, 8, 5, 1.00, NULL, 500.00, 1000000.00, NULL, '2024-03-03 13:30:06', '2024-03-03 13:30:06'),
(12, 11, 5, 1.00, NULL, 500.00, 1000000.00, NULL, '2024-03-03 13:34:10', '2024-03-03 13:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `requisitions`
--

CREATE TABLE `requisitions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` varchar(255) NOT NULL,
  `project_id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `alldone_status` varchar(255) DEFAULT NULL,
  `partial_delivery` varchar(255) DEFAULT NULL,
  `partial_reject` varchar(255) DEFAULT NULL,
  `partial_stock` varchar(255) DEFAULT NULL,
  `partial_purchase` varchar(255) DEFAULT NULL,
  `document` varchar(191) DEFAULT NULL,
  `date_from` varchar(255) NOT NULL,
  `reject_note` varchar(255) DEFAULT NULL,
  `pending_purchase_status` varchar(255) DEFAULT NULL,
  `purchase_approve` varchar(255) DEFAULT NULL,
  `purchase_reject` varchar(255) DEFAULT NULL,
  `purchaseteam_reject_note` varchar(255) DEFAULT NULL,
  `pending_approval_status_headoffice` varchar(255) DEFAULT NULL,
  `headoffice_approve` varchar(255) DEFAULT NULL,
  `headoffice_reject` varchar(255) DEFAULT NULL,
  `headoffice_reject_note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `requisitions`
--

INSERT INTO `requisitions` (`id`, `branch_id`, `project_id`, `user_id`, `status`, `alldone_status`, `partial_delivery`, `partial_reject`, `partial_stock`, `partial_purchase`, `document`, `date_from`, `reject_note`, `pending_purchase_status`, `purchase_approve`, `purchase_reject`, `purchaseteam_reject_note`, `pending_approval_status_headoffice`, `headoffice_approve`, `headoffice_reject`, `headoffice_reject_note`, `created_at`, `updated_at`) VALUES
(1, '5', '7', 34, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '29/05/2025', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-29 06:39:33', '2025-05-29 06:39:33'),
(2, '2', '1', 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '29/05/2025', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-29 10:20:53', '2025-05-29 10:20:53'),
(3, '5', '5', 34, '1', '0', '0', NULL, NULL, '0', NULL, '29/05/2025', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-29 12:01:26', '2025-05-29 12:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `requisition_items`
--

CREATE TABLE `requisition_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requisition_id` varchar(255) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `product_description` varchar(255) NOT NULL,
  `single_product_name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `newprice_qty` text DEFAULT NULL,
  `demand_amount` varchar(255) NOT NULL,
  `delivery` varchar(255) NOT NULL DEFAULT '0',
  `reject` varchar(255) NOT NULL DEFAULT '0',
  `purchase` varchar(255) NOT NULL DEFAULT '0',
  `stock_status` varchar(255) NOT NULL DEFAULT '0',
  `purchase_team_reject` varchar(255) NOT NULL DEFAULT '0',
  `headoffice_approval` varchar(255) DEFAULT NULL,
  `headoffice_rejected` varchar(255) NOT NULL DEFAULT '0',
  `total_price` varchar(255) NOT NULL,
  `reject_note` varchar(255) DEFAULT NULL,
  `stock_level` varchar(255) DEFAULT NULL,
  `purchase_authorization_amount` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `requisition_items`
--

INSERT INTO `requisition_items` (`id`, `requisition_id`, `product_id`, `product_description`, `single_product_name`, `price`, `newprice_qty`, `demand_amount`, `delivery`, `reject`, `purchase`, `stock_status`, `purchase_team_reject`, `headoffice_approval`, `headoffice_rejected`, `total_price`, `reject_note`, `stock_level`, `purchase_authorization_amount`, `comment`, `created_at`, `updated_at`) VALUES
(1, '1', '1', 'Paper', 'Paper', '500.00', NULL, '5', '0', '0', '0', '0', '0', NULL, '0', '2500', NULL, NULL, NULL, 'Reasonable', '2025-05-29 06:39:33', '2025-05-29 06:39:33'),
(2, '1', '2', 'Mouse', 'Mouse', '150.00', NULL, '3', '0', '0', '0', '0', '0', NULL, '0', '450', NULL, NULL, NULL, 'Reasonable', '2025-05-29 06:39:33', '2025-05-29 06:39:33'),
(3, '1', '3', 'Switch', 'Switch', '80.00', NULL, '4', '0', '0', '0', '0', '0', NULL, '0', '320', NULL, NULL, NULL, 'Reasonable', '2025-05-29 06:39:33', '2025-05-29 06:39:33'),
(6, '2', '1', 'Paper', 'Paper', '500.00', NULL, '70', '0', '0', '0', '0', '0', NULL, '0', '35000', NULL, NULL, NULL, 'Okay', '2025-05-29 11:38:05', '2025-05-29 11:38:05'),
(7, '2', '2', 'Mouse', 'Mouse', '150.00', NULL, '100', '0', '0', '0', '0', '0', NULL, '0', '15000', NULL, NULL, NULL, 'Okay', '2025-05-29 11:38:05', '2025-05-29 11:38:05'),
(8, '3', '1', 'Paper', 'Paper', '500.00', NULL, '5', '1', '0', '0', '0', '0', NULL, '0', '2500', NULL, NULL, NULL, 'Reasonable', '2025-05-29 12:01:26', '2025-05-29 12:10:10'),
(9, '3', '2', 'Mouse', 'Mouse', '150.00', NULL, '7', '1', '0', '0', '0', '0', NULL, '0', '1050', NULL, NULL, NULL, 'Esse laboriosam aut', '2025-05-29 12:01:26', '2025-05-29 12:10:11'),
(10, '3', '3', 'Switch', 'Switch', '80.00', NULL, '6', '0', '0', '1', '0', '0', NULL, '0', '480', NULL, NULL, NULL, 'Reasonable', '2025-05-29 12:01:26', '2025-05-29 12:09:40');

-- --------------------------------------------------------

--
-- Table structure for table `revenues`
--

CREATE TABLE `revenues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `account_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `add_receipt` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `revenues`
--

INSERT INTO `revenues` (`id`, `date`, `amount`, `account_id`, `customer_id`, `category_id`, `payment_method`, `reference`, `add_receipt`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(11, '2024-03-08', 5000.00, 10, 6, 17, 0, '239723', '1709316798_the-sad-side-of-deadpool-g2.jpg', 'Income Revenue', 1, '2024-03-01 07:58:55', '2024-03-04 05:42:54'),
(12, '2024-03-07', 2000.00, 13, 9, 17, 0, '23683', '1709530909_the-sad-side-of-deadpool-g2.jpg', 'Sold Goods', 1, '2024-03-04 05:41:49', '2024-03-04 05:43:07');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'user', '2024-02-08 05:51:39', '2024-02-08 05:51:39'),
(11, 'Headoffice', 'user', '2024-11-12 05:38:57', '2024-11-12 05:38:57'),
(12, 'PurchaseTeam', 'user', '2024-11-12 05:43:23', '2024-11-12 05:43:23'),
(13, 'Warehouse', 'user', '2024-11-12 05:43:48', '2024-11-12 05:43:48'),
(14, 'Branch', 'user', '2024-11-12 05:44:09', '2024-11-12 05:44:09');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(10, 11),
(10, 13),
(10, 14),
(11, 1),
(11, 11),
(11, 13),
(11, 14),
(12, 1),
(12, 11),
(12, 13),
(12, 14),
(13, 1),
(13, 11),
(13, 13),
(13, 14),
(14, 1),
(14, 11),
(14, 14),
(15, 1),
(15, 11),
(16, 1),
(16, 11),
(17, 1),
(17, 11),
(17, 12),
(18, 1),
(18, 12),
(19, 1),
(19, 12),
(20, 1),
(20, 12),
(21, 12),
(23, 1),
(23, 11),
(23, 13),
(23, 14),
(24, 1),
(24, 11),
(24, 14),
(25, 1),
(25, 11),
(25, 14),
(26, 1),
(26, 11),
(26, 13),
(26, 14),
(27, 1),
(27, 13),
(28, 1),
(28, 11),
(29, 1),
(29, 11),
(30, 1),
(30, 11),
(30, 13),
(31, 1),
(31, 11),
(31, 13),
(32, 1),
(32, 11),
(32, 13),
(33, 1),
(33, 11),
(33, 12),
(33, 13),
(33, 14),
(34, 1),
(35, 1),
(35, 11);

-- --------------------------------------------------------

--
-- Table structure for table `role_type_users`
--

CREATE TABLE `role_type_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` varchar(255) DEFAULT NULL,
  `role_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_type_users`
--

INSERT INTO `role_type_users` (`id`, `role_id`, `role_type`, `created_at`, `updated_at`) VALUES
(1, 'PRE001', 'Admin', NULL, NULL),
(2, 'PRE002', 'Super Admin', NULL, NULL),
(3, 'PRE003', 'Normal User', NULL, NULL),
(9, 'PRE004', 'Client', '2024-02-01 00:42:22', '2024-02-01 03:33:25');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `website_name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `website_name`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'Inventory', '1733468326.png', '2024-09-23 11:27:43', '2024-12-13 11:26:07');

-- --------------------------------------------------------

--
-- Table structure for table `stock_reports`
--

CREATE TABLE `stock_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `type` varchar(191) NOT NULL,
  `type_id` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_reports`
--

INSERT INTO `stock_reports` (`id`, `product_id`, `quantity`, `type`, `type_id`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 2, 1, 'bill', 1, '1  quantity purchase in bill #BILL00001', 1, '2023-12-26 22:41:47', '2023-12-26 22:41:47'),
(3, 2, 1, 'bill', 2, '1  quantity purchase in bill #EXP00001', 1, '2023-12-26 22:42:28', '2023-12-26 22:42:28'),
(5, 2, 1, 'invoice', 2, '1   quantity sold in invoice #INVO00002', 1, '2023-12-27 00:28:05', '2023-12-27 00:28:05'),
(6, 2, 10, 'purchase', 1, '10   quantity add in purchase #PUR00001', 1, '2023-12-27 00:31:58', '2023-12-27 00:31:58'),
(7, 2, 2, 'pos', 1, '2   quantity sold in pos #POS00001', 1, '2023-12-27 00:33:31', '2023-12-27 00:33:31'),
(8, 4, 5, 'purchase', 2, '5   quantity add in purchase #PUR00002', 1, '2023-12-27 00:35:22', '2023-12-27 00:35:22'),
(9, 4, 1, 'purchase', 3, '1   quantity add in purchase #PUR00003', 1, '2023-12-27 00:43:18', '2023-12-27 00:43:18'),
(11, 2, 1, 'pos', 2, '1   quantity sold in pos #POS00002', 1, '2023-12-27 00:47:09', '2023-12-27 00:47:09'),
(12, 2, 20, 'manually', 0, '20  quantity added by manually', 1, '2023-12-27 02:41:45', '2023-12-27 02:41:45'),
(14, 4, 1, 'pos', 3, '1   quantity sold in pos #POS00003', 1, '2023-12-27 02:42:36', '2023-12-27 02:42:36'),
(15, 5, 1, 'invoice', 3, '1   quantity sold in invoice #INVO00001', 1, '2024-01-26 23:23:13', '2024-01-26 23:23:13'),
(16, 5, 1, 'invoice', 6, '1   quantity sold in invoice #INVO00001', 1, '2024-01-27 04:14:50', '2024-01-27 04:14:50'),
(17, 5, 1, 'invoice', 7, '1   quantity sold in invoice #INVO00001', 1, '2024-01-27 04:21:06', '2024-01-27 04:21:06'),
(18, 5, 1, 'bill', 3, '1  quantity purchase in bill #BILL00001', 1, '2024-01-27 04:40:34', '2024-01-27 04:40:34'),
(19, 5, 1, 'bill', 4, '1  quantity purchase in bill #EXP00001', 1, '2024-01-27 04:47:40', '2024-01-27 04:47:40'),
(20, 5, 10, 'manually', 0, '10  quantity added by manually', 1, '2024-01-30 00:47:13', '2024-01-30 00:47:13'),
(21, 7, 1, 'invoice', 10, '1   quantity sold in invoice #INVO00003', 1, '2024-02-29 12:54:02', '2024-02-29 12:54:02'),
(22, 8, 1, 'invoice', 9, '1   quantity sold in invoice #INVO00002', 1, '2024-03-01 05:09:20', '2024-03-01 05:09:20'),
(25, 5, 1, 'bill', 10, '1  quantity purchase in bill #BILL00006', 1, '2024-03-02 04:56:14', '2024-03-02 04:56:14'),
(26, 5, 1, 'bill', 13, '1  quantity purchase in bill #BILL00007', 1, '2024-03-02 05:17:41', '2024-03-02 05:17:41'),
(27, 5, 1, 'bill', 11, '1   quantity purchase in bill #BILL00005', 1, '2024-03-02 06:08:08', '2024-03-02 06:08:08'),
(32, 7, 1, 'bill', 18, '1  quantity purchase in bill #EXP00002', 1, '2024-03-02 07:46:50', '2024-03-02 07:46:50'),
(35, 5, 1, 'bill', 17, '1   quantity purchase in bill #EXP00001', 1, '2024-03-02 08:05:14', '2024-03-02 08:05:14'),
(37, 5, 1, 'bill', 5, '1   quantity purchase in bill #BILL00001', 1, '2024-03-02 08:05:45', '2024-03-02 08:05:45'),
(39, 5, 1, 'bill', 16, '1   quantity purchase in bill #BILL00003', 1, '2024-03-02 08:07:20', '2024-03-02 08:07:20'),
(57, 7, 1, 'bill', 20, '1   quantity purchase in bill #EXP00002', 1, '2024-03-02 09:02:26', '2024-03-02 09:02:26'),
(58, 5, 1, 'bill', 19, '1   quantity purchase in bill #EXP00001', 1, '2024-03-02 09:04:33', '2024-03-02 09:04:33'),
(59, 7, 1, 'invoice', 14, '1   quantity sold in invoice #INVO00003', 1, '2024-03-04 07:09:49', '2024-03-04 07:09:49');

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `rate` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `taxes`
--

INSERT INTO `taxes` (`id`, `name`, `rate`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'National', '5', 1, '2023-12-26 05:42:42', '2023-12-26 05:42:42'),
(2, 'Import', '12', 1, '2023-12-26 05:42:52', '2023-12-26 05:42:52');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` varchar(191) NOT NULL,
  `account` int(11) NOT NULL,
  `type` varchar(191) DEFAULT NULL,
  `amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `date` date NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `payment_id` int(11) NOT NULL DEFAULT 0,
  `category` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `user_type`, `account`, `type`, `amount`, `description`, `date`, `created_by`, `payment_id`, `category`, `created_at`, `updated_at`) VALUES
(15, 6, 'Customer', 10, 'Revenue', 5000.00, 'Income Revenue', '2024-03-08', 1, 11, 'Good sold', '2024-03-01 07:58:55', '2024-03-04 05:42:54'),
(16, 5, 'Vender', 10, 'Payment', 1000.00, 'January Bill', '2024-03-13', 1, 3, 'Electricity Bill', '2024-03-02 09:41:51', '2024-03-04 06:36:33'),
(17, 5, 'Vender', 10, 'Payment', 100.00, 'Electricity of February', '2024-03-13', 1, 4, 'Electricity Bill', '2024-03-02 09:42:42', '2024-03-04 06:36:56'),
(19, 9, 'Customer', 13, 'Revenue', 2000.00, 'Sold Goods', '2024-03-07', 1, 12, 'Good sold', '2024-03-04 05:41:49', '2024-03-04 05:43:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `branch_id` varchar(255) DEFAULT NULL,
  `branch_name` varchar(255) DEFAULT NULL,
  `branch_type` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `date_of_birth` varchar(255) DEFAULT NULL,
  `join_date` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `is_verified` int(11) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `role_name` varchar(255) DEFAULT NULL,
  `navigate_to` varchar(255) DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `branch_id`, `branch_name`, `branch_type`, `name`, `email`, `date_of_birth`, `join_date`, `phone_number`, `is_verified`, `status`, `role_name`, `navigate_to`, `blood_group`, `address`, `avatar`, `position`, `department`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, '000001', '1', 'Dhaka', 'Headoffice', 'Sumon Sheikh', 'admin123@gmail.com', '1995-12-08', '2024-01-29', '01703615503', 1, 'Active', 'Admin', NULL, NULL, 'Dhaka, Bangladesh', '1728297925.jpg', 'Super Admin', NULL, NULL, '$2y$10$qnKJk5/BV4FYIci3wIEE9Ol6MjW5rqdZm26BgNva8UnDVBU2t/MiC', NULL, '2024-01-29 04:47:41', '2024-11-25 13:00:01'),
(29, '000027', '1', 'Dhaka', 'Headoffice', 'Shobuj Sheikh', 'adminho123@gmail.com', '1999-01-01', '2024-09-12', '01807652344', 1, 'Active', 'Headoffice', NULL, NULL, 'Dhaka, Bangladesh', '1728301793.jpg', 'Headoffice Manager', NULL, NULL, '$2y$10$dKkSrRQvC4pfr3.uXzTH.epa9qFVQ511v7WZEDtplvG/NgplikxYK', NULL, '2024-09-12 05:11:31', '2024-11-25 12:58:16'),
(34, '000032', '5', 'Rajshahi', 'Branch', 'Ahsan Khan', 'adminb123@gmail.com', '1994-02-08', '2024-10-07', '01877652340', 1, 'Active', 'Branch', NULL, NULL, 'RajShahi, Bangladesh', 'photo_defaults.jpg', 'Branch Manager', NULL, NULL, '$2y$10$vhXq1aSx9GkJeGpaTagnQONc512GKvVokWMv409oDR0jSzFhZoGxC', NULL, '2024-10-07 07:28:53', '2025-05-21 07:36:33'),
(39, '000037', '2', 'Mymensingh', 'Warehouse', 'Niloy Sheikh', 'adminwh123@gmail.com', '1992-01-01', '2024-10-23', '01778234560', 1, 'Active', 'Warehouse', NULL, NULL, 'Dhaka, Bangladesh', 'photo_defaults.jpg', 'Store Manager', NULL, NULL, '$2y$10$ngH/yXSdQrqj5F31yXV81eHurOTsyuY8TSndpipzKj0UnWYR8Nv4i', NULL, '2024-10-23 08:39:51', '2024-11-25 13:01:02'),
(40, '000038', '5', 'Rajshahi', 'Branch', 'Rohan Khan', 'adminpt123@gmail.com', '1994-12-12', '2024-11-08', '01875752344', 1, 'Active', 'PurchaseTeam', NULL, NULL, 'Comilla, Bangladesh', 'photo_defaults.jpg', 'Purchase Manager', NULL, NULL, '$2y$10$PngjePYFuqhhHVIIsJavn.nJrV0DqUuCWO7bkzwOuQOR0pyBmyk92', NULL, '2024-11-08 08:34:21', '2024-12-11 07:14:07');

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `type_name`, `created_at`, `updated_at`) VALUES
(1, 'Active', NULL, NULL),
(2, 'Inactive', NULL, NULL),
(3, 'Disable', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `venders`
--

CREATE TABLE `venders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vender_id` int(11) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `tax_number` varchar(191) DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `contact` varchar(191) DEFAULT NULL,
  `avatar` varchar(100) NOT NULL DEFAULT '',
  `created_by` int(11) NOT NULL DEFAULT 0,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `billing_name` varchar(191) DEFAULT NULL,
  `billing_country` varchar(191) DEFAULT NULL,
  `billing_state` varchar(191) DEFAULT NULL,
  `billing_city` varchar(191) DEFAULT NULL,
  `billing_phone` varchar(191) DEFAULT NULL,
  `billing_zip` varchar(191) DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `shipping_name` varchar(191) DEFAULT NULL,
  `shipping_country` varchar(191) DEFAULT NULL,
  `shipping_state` varchar(191) DEFAULT NULL,
  `shipping_city` varchar(191) DEFAULT NULL,
  `shipping_phone` varchar(191) DEFAULT NULL,
  `shipping_zip` varchar(191) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `lang` varchar(191) NOT NULL DEFAULT 'en',
  `balance` double(15,2) NOT NULL DEFAULT 0.00,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `venders`
--

INSERT INTO `venders` (`id`, `vender_id`, `name`, `email`, `tax_number`, `password`, `contact`, `avatar`, `created_by`, `is_active`, `email_verified_at`, `billing_name`, `billing_country`, `billing_state`, `billing_city`, `billing_phone`, `billing_zip`, `billing_address`, `shipping_name`, `shipping_country`, `shipping_state`, `shipping_city`, `shipping_phone`, `shipping_zip`, `shipping_address`, `lang`, `balance`, `remember_token`, `created_at`, `updated_at`) VALUES
(5, 2, 'Alex Home', 'alex@example.com', '725319', NULL, '09865435678', '', 1, 1, NULL, 'Alex Home', 'Bangladesh', 'Dhaka', 'London', '09865423423', '1000', 'London', 'Alex Home', 'Bangladesh', 'Dhaka', 'London', '09865423423', '1000', 'London', 'en', -1000.00, NULL, '2024-03-01 11:00:36', '2024-12-19 12:44:03');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `tittle` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `upload` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warehouse`
--

INSERT INTO `warehouse` (`id`, `name`, `tittle`, `address`, `status`, `upload`, `created_at`, `updated_at`) VALUES
(1, 'Melinda Soto', 'Blanditiis nostrum a', 'Earum porro sed cumq', 'Inactive', '1509129988.png', '2024-02-08 01:56:12', '2024-02-22 09:30:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `approvals_role_id_unique` (`role_id`),
  ADD KEY `approvals_order_index` (`order`);

--
-- Indexes for table `approval_statuses`
--
ALTER TABLE `approval_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_transfers`
--
ALTER TABLE `bank_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `batches`
--
ALTER TABLE `batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill_accounts`
--
ALTER TABLE `bill_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill_payments`
--
ALTER TABLE `bill_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill_products`
--
ALTER TABLE `bill_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_headoffice_logs`
--
ALTER TABLE `branch_headoffice_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch__products`
--
ALTER TABLE `branch__products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chart_of_account_sub_types`
--
ALTER TABLE `chart_of_account_sub_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chart_of_account_types`
--
ALTER TABLE `chart_of_account_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credit_notes`
--
ALTER TABLE `credit_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_field_values`
--
ALTER TABLE `custom_field_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `custom_field_values_record_id_field_id_unique` (`record_id`,`field_id`),
  ADD KEY `custom_field_values_field_id_foreign` (`field_id`);

--
-- Indexes for table `debit_notes`
--
ALTER TABLE `debit_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_bank_transfers`
--
ALTER TABLE `invoice_bank_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_payments`
--
ALTER TABLE `invoice_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_products`
--
ALTER TABLE `invoice_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_items`
--
ALTER TABLE `journal_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `page_permissions`
--
ALTER TABLE `page_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_batch_unique` (`batch`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `product_account_maps`
--
ALTER TABLE `product_account_maps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_expenses`
--
ALTER TABLE `product_expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_ledgers`
--
ALTER TABLE `product_ledgers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_ledger_b_h_s`
--
ALTER TABLE `product_ledger_b_h_s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_returns`
--
ALTER TABLE `product_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_return_warehouses`
--
ALTER TABLE `product_return_warehouses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_services`
--
ALTER TABLE `product_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_service_categories`
--
ALTER TABLE `product_service_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_service_units`
--
ALTER TABLE `product_service_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proposals`
--
ALTER TABLE `proposals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proposal_products`
--
ALTER TABLE `proposal_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requisitions`
--
ALTER TABLE `requisitions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requisition_items`
--
ALTER TABLE `requisition_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `revenues`
--
ALTER TABLE `revenues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `role_type_users`
--
ALTER TABLE `role_type_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_reports`
--
ALTER TABLE `stock_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `venders`
--
ALTER TABLE `venders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `venders_email_unique` (`email`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approvals`
--
ALTER TABLE `approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `approval_statuses`
--
ALTER TABLE `approval_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `bank_transfers`
--
ALTER TABLE `bank_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `batches`
--
ALTER TABLE `batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `bill_accounts`
--
ALTER TABLE `bill_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `bill_payments`
--
ALTER TABLE `bill_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `bill_products`
--
ALTER TABLE `bill_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `branch_headoffice_logs`
--
ALTER TABLE `branch_headoffice_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `branch__products`
--
ALTER TABLE `branch__products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT for table `chart_of_account_sub_types`
--
ALTER TABLE `chart_of_account_sub_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `credit_notes`
--
ALTER TABLE `credit_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `custom_field_values`
--
ALTER TABLE `custom_field_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `debit_notes`
--
ALTER TABLE `debit_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `invoice_bank_transfers`
--
ALTER TABLE `invoice_bank_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_payments`
--
ALTER TABLE `invoice_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invoice_products`
--
ALTER TABLE `invoice_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `journal_items`
--
ALTER TABLE `journal_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `page_permissions`
--
ALTER TABLE `page_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_account_maps`
--
ALTER TABLE `product_account_maps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_expenses`
--
ALTER TABLE `product_expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_ledgers`
--
ALTER TABLE `product_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `product_ledger_b_h_s`
--
ALTER TABLE `product_ledger_b_h_s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_returns`
--
ALTER TABLE `product_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_return_warehouses`
--
ALTER TABLE `product_return_warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_services`
--
ALTER TABLE `product_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_service_categories`
--
ALTER TABLE `product_service_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `product_service_units`
--
ALTER TABLE `product_service_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `proposals`
--
ALTER TABLE `proposals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `proposal_products`
--
ALTER TABLE `proposal_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `requisitions`
--
ALTER TABLE `requisitions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `requisition_items`
--
ALTER TABLE `requisition_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `revenues`
--
ALTER TABLE `revenues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `role_type_users`
--
ALTER TABLE `role_type_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock_reports`
--
ALTER TABLE `stock_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `venders`
--
ALTER TABLE `venders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `custom_field_values`
--
ALTER TABLE `custom_field_values`
  ADD CONSTRAINT `custom_field_values_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES `custom_fields` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
