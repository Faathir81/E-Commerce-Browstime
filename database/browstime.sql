-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 23, 2025 at 06:42 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `browstime`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Cookies', 'cookies', '2025-10-22 23:12:34', '2025-10-22 23:12:34'),
(2, 'Brownies', 'brownies', '2025-10-22 23:12:35', '2025-10-22 23:12:35'),
(3, 'Package', 'package', '2025-10-22 23:12:35', '2025-10-22 23:12:35');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `name`, `unit`, `created_at`, `updated_at`) VALUES
(1, 'Tepung Terigu', 'gram', '2025-10-22 23:12:35', '2025-10-22 23:12:35'),
(2, 'Gula Pasir', 'gram', '2025-10-22 23:12:35', '2025-10-22 23:12:35'),
(3, 'Mentega', 'gram', '2025-10-22 23:12:35', '2025-10-22 23:12:35'),
(4, 'Cokelat Chip', 'gram', '2025-10-22 23:12:36', '2025-10-22 23:12:36'),
(5, 'Telur', 'pcs', '2025-10-22 23:12:36', '2025-10-22 23:12:36'),
(6, 'Kemasan', 'pcs', '2025-10-22 23:12:36', '2025-10-22 23:12:36');

-- --------------------------------------------------------

--
-- Table structure for table `material_stocks`
--

CREATE TABLE `material_stocks` (
  `id` bigint UNSIGNED NOT NULL,
  `material_id` bigint UNSIGNED NOT NULL,
  `type` enum('in','out','adjust') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` enum('usage','expired','damaged','correction') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'usage',
  `qty` decimal(12,3) NOT NULL,
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_stocks`
--

INSERT INTO `material_stocks` (`id`, `material_id`, `type`, `reason`, `qty`, `note`, `created_at`) VALUES
(1, 1, 'in', 'usage', '10000.000', 'Stock awal', '2025-10-22 23:12:35'),
(2, 2, 'in', 'usage', '10000.000', 'Stock awal', '2025-10-22 23:12:35'),
(3, 3, 'in', 'usage', '10000.000', 'Stock awal', '2025-10-22 23:12:36'),
(4, 4, 'in', 'usage', '10000.000', 'Stock awal', '2025-10-22 23:12:36'),
(5, 5, 'in', 'usage', '200.000', 'Stock awal', '2025-10-22 23:12:36'),
(6, 6, 'in', 'usage', '200.000', 'Stock awal', '2025-10-22 23:12:36');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_23_100001_create_categories_table', 2),
(5, '2025_10_23_100002_create_products_table', 2),
(6, '2025_10_23_100003_create_product_images_table', 2),
(7, '2025_10_23_100004_create_materials_table', 2),
(8, '2025_10_23_100005_create_material_stocks_table', 2),
(9, '2025_10_23_100006_create_product_recipes_table', 2),
(10, '2025_10_23_100007_create_orders_table', 2),
(11, '2025_10_23_100008_create_order_items_table', 2),
(12, '2025_10_23_100009_create_payments_table', 2),
(13, '2025_10_23_100010_create_reviews_table', 2),
(14, '2025_10_23_100011_create_views_makeable_qty_and_stock_current', 2),
(15, '2025_10_23_120001_add_shipping_meta_and_eta_to_orders_table', 3),
(16, '2025_10_23_120002_add_midtrans_fields_to_payments_table', 3),
(17, '2025_10_23_120003_add_reason_to_material_stocks_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','awaiting_payment','paid','processing','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `buyer_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer_phone` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ship_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ship_city` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_postal` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_cost` int UNSIGNED NOT NULL DEFAULT '0',
  `shipping_courier` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_service` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_etd` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight_gram` int UNSIGNED NOT NULL DEFAULT '0',
  `origin_city_id` int UNSIGNED DEFAULT NULL,
  `destination_city_id` int UNSIGNED DEFAULT NULL,
  `subtotal` int UNSIGNED NOT NULL,
  `grand_total` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `estimated_ready_at` datetime DEFAULT NULL,
  `estimated_delivery_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `qty` int UNSIGNED NOT NULL,
  `price_frozen` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `gateway` enum('manual','midtrans') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `gateway_order_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` enum('transfer','qris','cash') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'transfer',
  `amount` int UNSIGNED NOT NULL,
  `currency` char(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `status` enum('pending','verified','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `channel` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `va_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_string` text COLLATE utf8mb4_unicode_ci,
  `payload` json DEFAULT NULL,
  `proof_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(140) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `price`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Choco Chip Cookies 200g', 'choco-chip-cookies-200g', 35000, 1, '2025-10-22 23:12:36', '2025-10-22 23:12:36'),
(2, 1, 'Classic Butter Cookies 200g', 'classic-butter-cookies-200g', 32000, 1, '2025-10-22 23:12:36', '2025-10-22 23:12:36');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_cover` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `url`, `is_cover`, `created_at`, `updated_at`) VALUES
(1, 1, '/storage/products/sample.jpg', 1, '2025-10-22 23:12:36', '2025-10-22 23:12:36'),
(2, 2, '/storage/products/sample.jpg', 1, '2025-10-22 23:12:36', '2025-10-22 23:12:36');

-- --------------------------------------------------------

--
-- Table structure for table `product_recipes`
--

CREATE TABLE `product_recipes` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `material_id` bigint UNSIGNED NOT NULL,
  `qty_per_unit` decimal(12,3) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_recipes`
--

INSERT INTO `product_recipes` (`id`, `product_id`, `material_id`, `qty_per_unit`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '120.000', '2025-10-22 23:12:36', '2025-10-22 23:12:36'),
(2, 1, 2, '50.000', '2025-10-22 23:12:36', '2025-10-22 23:12:36'),
(3, 1, 3, '30.000', '2025-10-22 23:12:37', '2025-10-22 23:12:37'),
(4, 1, 5, '1.000', '2025-10-22 23:12:37', '2025-10-22 23:12:37'),
(5, 1, 6, '1.000', '2025-10-22 23:12:37', '2025-10-22 23:12:37'),
(6, 1, 4, '40.000', '2025-10-22 23:12:37', '2025-10-22 23:12:37'),
(7, 2, 1, '120.000', '2025-10-22 23:12:37', '2025-10-22 23:12:37'),
(8, 2, 2, '50.000', '2025-10-22 23:12:37', '2025-10-22 23:12:37'),
(9, 2, 3, '30.000', '2025-10-22 23:12:37', '2025-10-22 23:12:37'),
(10, 2, 5, '1.000', '2025-10-22 23:12:37', '2025-10-22 23:12:37'),
(11, 2, 6, '1.000', '2025-10-22 23:12:37', '2025-10-22 23:12:37');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `rating` tinyint NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_material_stock_current`
-- (See below for the actual view)
--
CREATE TABLE `v_material_stock_current` (
`material_id` bigint unsigned
,`name` varchar(120)
,`qty_current` decimal(34,3)
,`unit` varchar(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_product_makeable_qty`
-- (See below for the actual view)
--
CREATE TABLE `v_product_makeable_qty` (
`makeable_qty` decimal(35,0)
,`product_id` bigint unsigned
);

-- --------------------------------------------------------

--
-- Structure for view `v_material_stock_current`
--
DROP TABLE IF EXISTS `v_material_stock_current`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_material_stock_current`  AS SELECT `m`.`id` AS `material_id`, `m`.`name` AS `name`, `m`.`unit` AS `unit`, coalesce(sum((case when (`ms`.`type` = 'in') then `ms`.`qty` when (`ms`.`type` = 'adjust') then `ms`.`qty` when (`ms`.`type` = 'out') then -(`ms`.`qty`) end)),0) AS `qty_current` FROM (`materials` `m` left join `material_stocks` `ms` on((`ms`.`material_id` = `m`.`id`))) GROUP BY `m`.`id`, `m`.`name`, `m`.`unit``unit`  ;

-- --------------------------------------------------------

--
-- Structure for view `v_product_makeable_qty`
--
DROP TABLE IF EXISTS `v_product_makeable_qty`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_product_makeable_qty`  AS SELECT `pr`.`product_id` AS `product_id`, min(floor((`v`.`qty_current` / nullif(`pr`.`qty_per_unit`,0)))) AS `makeable_qty` FROM (`product_recipes` `pr` join `v_material_stock_current` `v` on((`v`.`material_id` = `pr`.`material_id`))) GROUP BY `pr`.`product_id``product_id`  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `materials_name_unit_unique` (`name`,`unit`);

--
-- Indexes for table `material_stocks`
--
ALTER TABLE `material_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_stocks_material_id_type_index` (`material_id`,`type`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_code_unique` (`code`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_status_user_id_index` (`status`,`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_order_id_product_id_index` (`order_id`,`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_status_index` (`order_id`,`status`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_recipes`
--
ALTER TABLE `product_recipes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_recipes_product_id_material_id_unique` (`product_id`,`material_id`),
  ADD KEY `product_recipes_material_id_foreign` (`material_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_user_id_product_id_order_id_unique` (`user_id`,`product_id`,`order_id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`),
  ADD KEY `reviews_order_id_foreign` (`order_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `material_stocks`
--
ALTER TABLE `material_stocks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_recipes`
--
ALTER TABLE `product_recipes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `material_stocks`
--
ALTER TABLE `material_stocks`
  ADD CONSTRAINT `material_stocks_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_recipes`
--
ALTER TABLE `product_recipes`
  ADD CONSTRAINT `product_recipes_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `product_recipes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
