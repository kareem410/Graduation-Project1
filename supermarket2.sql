-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 17, 2025 at 07:36 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `supermarket2`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(22, '2025_02_27_140934_create_cart_table', 2),
(32, '2014_10_12_000000_create_users_table', 3),
(33, '2014_10_12_100000_create_password_reset_tokens_table', 3),
(34, '2019_08_19_000000_create_failed_jobs_table', 3),
(35, '2019_12_14_000001_create_personal_access_tokens_table', 3),
(36, '2025_02_17_082710_create_products_table', 3),
(37, '2025_02_17_082740_create_myusers_table', 3),
(38, '2025_02_17_082756_create_wishlist_table', 3),
(39, '2025_02_27_143511_create_cart_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `myusers`
--

CREATE TABLE `myusers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imageUrl` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('customer','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `myusers`
--

INSERT INTO `myusers` (`id`, `name`, `email`, `password`, `imageUrl`, `card`, `type`, `created_at`, `updated_at`) VALUES
(1, 'John4 Doe', 'john4@example.com', '$2y$12$jkfKdf7if0CU8TfJjFils.ATltnrzxaJGkeqNHg3lejN4eHhh3Ini', 'users/BmheUc38ZEOCH7nVBsNjMRmSy9W1mxALVLEeEwdx.jpg', 'A1a1a1', 'admin', '2025-04-17 02:29:44', '2025-04-17 02:29:44'),
(3, 'Kareem Gamalll', 'kareemgamal06@gmail.com', '$2y$12$4pEfeyYs0gbpD8G2tz9hUudVoI3Awwji3b5H4awJrWTeMLPDXjETG', 'users/prVuwW7XpzAczTsiVpTKKI16oaEy3CpXGP6AsfaS.jpg', 'z00', 'admin', '2025-04-17 02:39:26', '2025-04-17 02:41:57');

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\MyUser', 1, 'auth_token', '8f93b21f4f5fbeb2fd480366aaad23e8589694081ac428cdea284fba8291b668', '[\"*\"]', NULL, NULL, '2025-04-17 02:29:45', '2025-04-17 02:29:45'),
(2, 'App\\Models\\MyUser', 1, 'auth_token', '817797ec625d1e61443bb1671868afc32cb4aef01751e870190d423a67bb5acc', '[\"*\"]', '2025-04-17 04:35:29', NULL, '2025-04-17 02:32:01', '2025-04-17 04:35:29');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stockAvailability` tinyint(1) NOT NULL DEFAULT '1',
  `inStock` int NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `rating` decimal(3,2) NOT NULL DEFAULT '0.00',
  `offers` tinyint(1) NOT NULL DEFAULT '0',
  `bestDeal` tinyint(1) NOT NULL DEFAULT '0',
  `topSelling` tinyint(1) NOT NULL DEFAULT '0',
  `everydayNeeds` tinyint(1) NOT NULL DEFAULT '0',
  `imageUrl` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_arrival` tinyint(1) NOT NULL DEFAULT '0',
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `unit`, `stockAvailability`, `inStock`, `description`, `rating`, `offers`, `bestDeal`, `topSelling`, `everydayNeeds`, `imageUrl`, `new_arrival`, `barcode`, `created_at`, `updated_at`) VALUES
(1, 'Laptop', 'Electronics', 899.99, 'Piece', 0, 0, 'High-performance laptop', 4.50, 10, 1, 1, 0, 'products/cCs2czOmcRpzmvt4HlTiiLbV16alD3UFVvVn6GU3.webp', 1, '11115', '2025-04-17 03:07:39', '2025-04-17 03:07:39'),
(2, 'Camera', 'Electronics', 549.99, 'Piece', 0, 0, 'High-resolution digital camera', 4.60, 0, 0, 1, 0, 'products/voPfMo6Gj8RhjpaVIapT5lreHh29bD0qX3iF2p2o.webp', 1, '11116', '2025-04-17 03:09:57', '2025-04-17 03:09:57'),
(3, 'TV', 'Electronics', 1299.99, 'Piece', 0, 0, '4K Ultra HD Smart TV', 4.80, 5, 1, 1, 0, 'products/gbGueOhjDvW6bbPW6qSviMJD22AmHpQP3o0Zp8z5.webp', 1, '11117', '2025-04-17 03:17:12', '2025-04-17 03:17:12'),
(4, 'Milk', 'Dairy, Eggs & Cheese', 2.99, 'Liter', 0, 0, 'Fresh whole milk', 4.70, 0, 0, 0, 0, 'products/3GJpPafGbt8AMEU7wn1NdBYq5Q75G1AS2RPOzjz5.webp', 0, '11148', '2025-04-17 04:21:17', '2025-04-17 04:21:17'),
(5, 'Cheese', 'Dairy, Eggs & Cheese', 5.49, 'Piece', 0, 0, 'Cheddar cheese block', 4.60, 0, 0, 0, 0, 'products/a3yI41JSXsudTrGgL9ssiK9rZ2dqyCCejP21W4HG.webp', 0, '11158', '2025-04-17 04:22:04', '2025-04-17 04:22:04'),
(6, 'Apple', 'Fruits & Vegetables', 3.99, 'Kg', 0, 0, 'Fresh red apples', 4.80, 0, 0, 1, 0, 'products/nQ2crzW0hsEqHHek0xSkHaZ0ORvugIYxIwj0VbEI.webp', 0, '11168', '2025-04-17 04:22:46', '2025-04-17 04:22:46'),
(7, 'Potato', 'Fruits & Vegetables', 1.49, 'Kg', 0, 0, 'Organic potatoes', 4.50, 0, 0, 0, 0, 'products/O2LRWCZxOsTmswHrmfspC2cXin0auWqzQQxN4R6j.webp', 0, '11178', '2025-04-17 04:23:39', '2025-04-17 04:23:39'),
(8, 'Chocolate Bar', 'Snacks & Sweets', 1.99, 'Piece', 0, 0, 'Dark chocolate bar', 4.70, 0, 0, 1, 0, 'products/XcQPS9osOdxx9AyDaYtDz7a9QF86gKbGuKC4CmLo.webp', 0, '11188', '2025-04-17 04:24:28', '2025-04-17 04:24:28'),
(9, 'Chips', 'Snacks & Sweets', 2.49, 'Pack', 0, 0, 'Potato chips pack', 4.60, 0, 0, 1, 0, 'products/tRPRFuPDrJ3ff09ZQC22mOjf5yue7OWkBkIVAoG5.webp', 0, '11198', '2025-04-17 04:24:57', '2025-04-17 04:24:57'),
(10, 'Chicken Breast', 'Meat & Poultry', 7.99, 'Kg', 0, 0, 'Fresh chicken breast', 4.90, 10, 1, 0, 0, 'products/0fbqT0c7V4U72EdklVt5g7iFB6VxtbzPOOyTdyfK.webp', 0, '11208', '2025-04-17 04:25:37', '2025-04-17 04:25:37'),
(11, 'Beef Steak', 'Meat & Poultry', 15.99, 'Kg', 0, 0, 'Premium beef steak', 4.80, 5, 1, 0, 0, 'products/MHi6kV1Ev3rgq0ZgEYbmM73gU1w6NIz1UCCV7Io8.webp', 0, '11218', '2025-04-17 04:26:21', '2025-04-17 04:26:21'),
(12, 'Butter', 'Dairy, Eggs & Cheese', 4.99, 'Piece', 0, 0, 'Organic butter', 4.50, 12, 1, 0, 0, 'products/6CLl7tPIlcEjQIqSqPfgQo6R38G7mpEH1iDDHzvg.webp', 0, '11228', '2025-04-17 04:27:05', '2025-04-17 04:27:05'),
(13, 'Orange', 'Fruits & Vegetables', 2.99, 'Kg', 0, 0, 'Fresh oranges', 4.70, 0, 0, 1, 0, 'products/LBFw7cr4F8bAZkREzlIKYfbQkAjXzF9ke0VtQYyU.webp', 0, '11238', '2025-04-17 04:27:41', '2025-04-17 04:27:41'),
(14, 'Cookies', 'Snacks & Sweets', 3.49, 'Pack', 0, 0, 'Chocolate chip cookies', 4.60, 20, 1, 1, 0, 'products/CMzxbK7HYiKzPnGVdv5hlvKlf63R9JIAtFQdllXz.webp', 0, '11248', '2025-04-17 04:28:19', '2025-04-17 04:28:19'),
(15, 'Salmon', 'Meat & Poultry', 19.99, 'Kg', 0, 0, 'Fresh salmon fillet', 4.90, 15, 1, 0, 0, 'products/li4Ese2qQGYdDfXfUCWmcywIROiplsEsBKXko5uw.webp', 0, '11258', '2025-04-17 04:28:59', '2025-04-17 04:28:59'),
(16, 'Salmon', 'Dairy, Eggs & Cheese', 2.99, 'Dozen', 1, 100, 'Fresh farm eggs', 4.80, 0, 0, 1, 0, 'products/UxdVgZ5Y0O2foea44Z7uYyXdlsG0UmVF8DX4L1mZ.webp', 0, '11268', '2025-04-17 04:29:31', '2025-04-17 04:29:31'),
(17, 'Yogurt', 'Dairy, Eggs & Cheese', 3.49, 'Pack', 1, 80, 'Organic yogurt pack', 4.70, 10, 1, 0, 0, 'products/zcHeB9n39l4kbjTLoooeLR4efqy3dQii7gNJpxZ9.webp', 0, '11278', '2025-04-17 04:30:01', '2025-04-17 04:30:01'),
(18, 'Banana', 'Fruits & Vegetables', 1.99, 'Kg', 1, 120, 'Fresh bananas', 4.90, 0, 0, 1, 0, 'products/DvynAtOI3OqeHvopNyDWu9gbDEhdamLWAN1AGvsA.webp', 0, '11288', '2025-04-17 04:30:30', '2025-04-17 04:30:30'),
(19, 'Tomato', 'Fruits & Vegetables', 2.29, 'Kg', 1, 90, 'Organic tomatoes', 4.60, 0, 0, 0, 0, 'products/0ygEHhCyQDymjpAzGy0DdaACXiQrdDV8YWXb5QWQ.webp', 0, '11298', '2025-04-17 04:30:59', '2025-04-17 04:30:59'),
(20, 'Ice Cream', 'Snacks & Sweets', 5.99, 'Pack', 1, 50, 'Vanilla ice cream tub', 4.70, 0, 0, 1, 0, 'products/TbcMqt7QAqxSa7vf8sadgnev2VRUn7tJ8Xo2TVa0.webp', 0, '11308', '2025-04-17 04:31:31', '2025-04-17 04:31:31'),
(21, 'Strawberries', 'Fruits & Vegetables', 3.99, 'Pack', 1, 60, 'Fresh strawberries', 4.80, 0, 0, 1, 0, 'products/bqNBSXEZFFOcVTlZQ0YDouMshEgFBCGzZwjkY6KG.webp', 0, '11318', '2025-04-17 04:32:01', '2025-04-17 04:32:01'),
(22, 'Bucket', 'Cleaning & Household', 3.99, 'Pack', 1, 60, 'White Tissues', 1.20, 0, 0, 1, 0, 'products/7CqXd8t31bjZ4VuhdAx2dPuFAmqWzim7Ugeel9ds.webp', 0, '11328', '2025-04-17 04:34:17', '2025-04-17 04:34:17'),
(23, 'Bread', 'Bakery', 3.50, 'Pack', 1, 60, 'fresh bread', 1.20, 0, 0, 1, 0, 'products/eDZl26LRyAueUUgmUUqyQk6Mk85fYtqRsRWWaIVO.webp', 0, '11338', '2025-04-17 04:34:54', '2025-04-17 04:34:54'),
(24, 'Water', 'Water', 3.50, 'Pack', 1, 60, 'water bottle 300ml', 1.20, 0, 0, 1, 0, 'products/iIA2OZo0B2XIJtZGZVnXkHRhM4wFJoEkuU0Ge9B5.webp', 0, '11348', '2025-04-17 04:35:29', '2025-04-17 04:35:29');

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
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`user_id`,`product_id`),
  ADD KEY `cart_product_id_foreign` (`product_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `myusers`
--
ALTER TABLE `myusers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `myusers_email_unique` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

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
  ADD UNIQUE KEY `products_barcode_unique` (`barcode`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`user_id`,`product_id`),
  ADD KEY `wishlist_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `myusers`
--
ALTER TABLE `myusers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `myusers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `myusers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
