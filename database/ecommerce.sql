-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2023 at 04:30 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Shirts', NULL, '2023-10-10 11:59:49', '2023-10-10 11:59:49'),
(3, 'Laptops', NULL, '2023-10-10 12:00:11', '2023-10-10 12:00:11'),
(4, 'Tablets', NULL, '2023-10-10 12:00:21', '2023-10-10 12:00:21'),
(5, 'T shirts', NULL, '2023-10-10 12:00:27', '2023-10-10 12:00:27'),
(6, 'Uncategorized', NULL, '2023-10-10 13:43:34', '2023-10-11 14:14:31');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `image`, `price`, `stock`, `category_id`, `created_at`, `updated_at`) VALUES
(51, 'Acer Aspire 5 Gaming Laptop ', 'Acer Aspire 5 Gaming laptop is powered by the latest 12th Gen Intel Core i5 processor consisting of 12 cores for multitasking and productivitys On : NVIDIA GeForce RTX 2050 - 4G-GDDR6 laptops deliver ray tracing and cutting-edge AI features. The RTX 2050 also works seamlessly with NVIDIA Optimus technology to give you the perfect balance between long battery life and optimum performance. Over 150 top games and applications use RTX to deliver realistic graphics or cutting-edge new AI features like NVIDIA DLSS and NVIDIA Broadcast. RTX is the new standard.', '6526a5f7d0d80.jpg,6527fe26ad89d.jpg,6527fe26ade77.jpg', '49990.00', 50, 3, '2023-10-11 10:04:11', '2023-10-12 14:09:42'),
(53, 'HP Laptop 15s ', '6-core 12th Gen Intel Core i3-1215U】 8 threads and 10MB L3 cache deliver high performance and instant responsiveness. The Intel UHD graphics help you dive into crisp, stunning visuals.Upgraded memory and storage】 8GB DDR4 RAM and 512GB SSD help you undertake improved multitasking with ample of storage and higher-bandwidth memory. Now, create, browse, and work as you please.', '6526a659302d1.jpg,6526a65930858.', '39790.00', 20, 3, '2023-10-11 10:08:27', '2023-10-11 13:42:49'),
(54, 'Lenovo Yoga Slim 7 Pro', 'Processor: 11th Gen Intel Core i5-11320H | Speed: 3.2 GHz (Base) - 4.5 GHz (Max) | 4 Cores | 8 Threads | 8MB Cache OS: Pre-Loaded Windows 11 Home with Lifetime Validity | MS Office Home and Student 2021 | Xbox GamePass Ultimate 3-month subscription*Display: 14\\\\\\\" 2.8K (2880x1800) | IPS | Brightness: 400nits | Anti-glare | 90Hz | 100% sRGB | Dolby Vision', '6526a685d0ec7.jpg,6527fe409e9c6.jpg,6527fe409eca1.jpg', '72000.00', 15, 3, '2023-10-11 10:11:17', '2023-10-12 14:10:08'),
(55, 'Apple MacBook Air Laptop M1', 'All-Day Battery Life – Go longer than ever with up to 18 hours of battery life. Powerful Performance – Take on everything from professional-quality editing to action-packed gaming with ease. The Apple M1 chip with an 8-core CPU delivers up to 3.5x faster performance than the previous generation while using way less power.', '6526a69e336a5.jpg', '69990.00', 10, 3, '2023-10-11 10:13:21', '2023-10-11 13:43:58'),
(56, 'Symbol Men Regular Fit Casual Shirt', 'Care Instructions: Machine Wash Fit Type: Regular Fit Soft and Breathable 100% Cotton Fabric Mandarin collar solid color shirt', '6526a6da4d3df.jpg,6527fd713f5cb.jpg', '479.00', 50, 1, '2023-10-11 10:15:16', '2023-10-12 14:06:41'),
(57, 'Dennis Lingo Mens Checkered Slim Fit Cotton Casual Shirt', 'SIze L Care Instructions: Machine Wash Fit Type: Slim Fit Sleeve Type: Long Sleeve; Collar Style: Spread Collar; Color Name: Dusty Blue', '6526a70997fc5.jpg,6527fdf9ef73a.jpg', '500.00', 25, 1, '2023-10-11 10:17:08', '2023-10-12 14:08:57'),
(58, 'INKAST Men Slim Fit Denim Shirt', 'Care Instructions: Machine Wash Fit Type: Slim Fit Fabric - 100% Cotton Long sleeve Shirt', '6526a72db97ef.jpg,6527fe03e1569.jpg', '579.00', 40, 1, '2023-10-11 10:18:14', '2023-10-12 14:09:07'),
(59, 'Lymio Casual Shirt for Men', 'Care Instructions: Machine Wash Fit Type: Regular Fit Half Sleeve Style - Enhance your look by wearing this Casual Stylish Men\\\\\\\'s shirt, Team it with a pair of tapered denims Or Solid Chinos and Loafers for a fun Smart Casual look', '6526a76988cc6.jpg,6527fe0dc0104.jpg', '339.00', 80, 1, '2023-10-11 10:19:42', '2023-10-12 14:09:17'),
(60, 'Mens Fashion Dress Trouser', 'Care Instructions: Machine-wash or hand-wash Fit Type: Slim Fit Care Instructions: Machine-wash or hand-wash Fit Type: Slim Fit', '6526a79f45028.jpg', '1599.00', 25, 6, '2023-10-11 10:20:54', '2023-10-11 14:17:09'),
(61, 'Symbol Men Dress Pants', 'Care Instructions: Machine Wash Fit Type: Slim If you are in between sizes, go for a size higher Care Instructions: Machine Wash Fit Type: Slim Stretch Poly Viscose belnd for all day comfort Slant pocket with back welt pocket. Number of Pockets: 4', '6526a7f81dc02.jpg', '940.00', 55, 6, '2023-10-11 10:22:12', '2023-10-11 14:17:09'),
(62, 'LEOTUDE Men T-Shirt', 'Care Instructions: Hand Wash Only\\\\r\\\\nFit Type: Oversized Fit\\\\r\\\\nColor : Black\\\\r\\\\nFabric: Cottonblend\\\\r\\\\nSleeve Type: Half Sleeve\\\\r\\\\nFit: Oversized\\\\r\\\\nPattern : Skull Printed', '65269ab653f19.jpg,65269ab6541e7.jpg,65269ab654420.jpg', '270.00', 80, 5, '2023-10-11 10:24:09', '2023-10-11 12:53:10'),
(63, ' Round Neck Full Sleeve T Shirt ', 'Care Instructions: Machine Wash Fit Type: Regular Fit Unique Design with Excellent durable fabric Soft flow dyed 60% Cotton, 40% Polyester Fabric Fabric GSM is 180 Machine wash Cold Regular Fit and dimensionally accurate size', '6526a855a5bab.jpg', '199.00', 50, 5, '2023-10-11 10:25:27', '2023-10-11 13:51:17'),
(64, 'Van Heusen Men\\\'s Polo Shirt', 'Care Instructions: Machine Wash Fit Type: Regular Fit Unique Design with Excellent durable fabric Soft flow dyed 60% Cotton, 40% Polyester Fabric Fabric GSM is 180 Machine wash Cold Regular Fit and dimensionally accurate size', '6526a8a668942.jpg', '499.00', 45, 5, '2023-10-11 10:26:38', '2023-10-11 13:52:38'),
(65, 'Lenovo Tab P11 (2nd Gen)', 'Brand: Lenovo Model Name: Tab P11 (2nd Gen) Memory Storage Capacity: 128 GB Screen Size: 11.5 Inches Operating System: Android 12, Android', '6526a8cba72a4.jpg', '18999.00', 110, 4, '2023-10-11 10:28:02', '2023-10-11 13:53:15'),
(66, 'realme Pad X ', 'Pattern Name: Pad X Brand: realme Model Name: realme Pad X Memory Storage Capacity: 128 GB Screen Size: 10.95 Inches Display Resolution Maximum: 2000 x 1200 Pixels', '6526a8f11a797.jpg', '25699.00', 50, 4, '2023-10-11 10:29:07', '2023-10-11 13:53:53'),
(67, 'Apple 2022 10.9-inch iPad', 'Brand: Apple Model Name: iPad Memory Storage Capacity: 64 GB Screen Size: 10.9 Inches Operating System: iPadOS', '6526a91d4de65.jpg', '42990.00', 55, 4, '2023-10-11 10:30:33', '2023-10-11 13:54:37'),
(68, 'Sparx Men s Sf0023g Flip Flop', 'Colour: NAVY YELLOW Material: Velvet Lifestyle: Casual Closure Type: Slip On', '6526a95dc7e1b.jpg', '299.00', 150, 1, '2023-10-11 10:32:22', '2023-10-11 13:55:41'),
(69, 'Noise ColorFit Pro 4 ', 'Brand: Noise Model Name: ColorFit Pro 4 Alpha Style: Modern Colour: Jet Black Screen Size: 1.78 Inches', '6526a9819578b.jpg,6527fde8687ce.jpg,6527fde868a98.jpg', '2199.00', 88, 1, '2023-10-11 10:33:47', '2023-10-12 14:08:40'),
(73, 'fsd', 'dfgdfgdfg', '65269c46e8074.jpg,65269c46e86e6.jpg,65269c46e89bf.jpg', '232.00', 32, 6, '2023-10-11 12:59:50', '2023-10-11 14:14:54'),
(75, 'Laptop ', 'asdghg', '6526a9e8eaffd.jpg,6527fdd3b4e96.jpg', '3455.00', 23, 3, '2023-10-11 13:58:00', '2023-10-12 14:08:26'),
(76, 'Dell 15 Laptop', 'Processor: Intel Core i5-1135G7 11th Generation (up to 4.20 GHz) 8MB, 4 Cores RAM & Storage: 8GB, 1x8GB, DDR4, 3200MHz, (2 DIMM Slots, Expandable up to 16GB) & 512GB SSD Software: Win 11 Home + Office H&S 2021, 15 Months McAfee antivirus subscription, Dell Mobile Connect, My Dell, Dell Power Manager, McAfee, Support Assist, Dell ComfortView', '652800239b20e.jpg,652800239c385.jpg', '46990.00', 55, 3, '2023-10-12 14:18:11', '2023-10-12 14:18:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `type` enum('0','1') NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `pincode` int(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `language` varchar(255) NOT NULL,
  `status` enum('1','0') NOT NULL,
  `profile_image_url` varchar(255) NOT NULL DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `mobile`, `type`, `email`, `password`, `address`, `pincode`, `country`, `language`, `status`, `profile_image_url`) VALUES
(10, 'syed akkeal 11', '123456789011', '1', 'syed261111@gmail.com', '$2y$10$J6FKd65dQaxJpMMQQsmAPutSq58ytN9113Dfb4co3oEbOeixYZ/qa', '', 0, '', '', '1', '../uploads/65263760b875d_image 24.png'),
(11, 'syed akkeal 1234', '111', '0', 'syed123@gmail.com', '$2y$10$bZVLCFX9qn.5go8vbQjw5udRRfRc5DSR2rwQwOj115CnCXCNIOSUq', '', 0, '', '', '1', 'uploads/6527e85acc79b_image 24.png'),
(12, 'syed akkeal', '0987654321', '0', 'sales12@gmail.com', '$2y$10$FiYsxdMncHNrkevmT7js0Od0oljL3BplHAN0zBGlnZaSfinN.OWNS', '', 0, '', '', '1', 'uploads/65268fb0f18e3_714PwkJGDfL._UX569_.jpg'),
(18, 'syed akkeal', '111', '0', 'nithi@gmiail.com', '$2y$10$j6CssCfWgOm7YntRcPyU4.jtSZurr2fdTPCec8Y6le6fgPGozkwva', '', 0, '', '', '1', 'uploads/65268fdb55446_61DGAlvxRLL._UY741_.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user_cart`
--

CREATE TABLE `user_cart` (
  `user_id` int(11) NOT NULL,
  `total_items` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_cart`
--

INSERT INTO `user_cart` (`user_id`, `total_items`) VALUES
(11, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_cart`
--
ALTER TABLE `user_cart`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `user_cart`
--
ALTER TABLE `user_cart`
  ADD CONSTRAINT `user_cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
