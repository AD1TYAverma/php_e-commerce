-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 25, 2026 at 02:30 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-commerce_php`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_table`
--

DROP TABLE IF EXISTS `admin_table`;
CREATE TABLE IF NOT EXISTS `admin_table` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_table`
--

INSERT INTO `admin_table` (`admin_id`, `admin_name`, `admin_password`) VALUES
(1, 'AdityaAdmin', '$2y$10$dVK0iEMinPA8VzIS3voBFOTld/RuWRNbjfQIb19Q21ffeT58uXIES');

-- --------------------------------------------------------

--
-- Table structure for table `card_details`
--

DROP TABLE IF EXISTS `card_details`;
CREATE TABLE IF NOT EXISTS `card_details` (
  `card_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`card_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `card_details`
--

INSERT INTO `card_details` (`card_id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(18, 10, 1, 1, '2026-04-18 08:16:11'),
(16, 13, 1, 7, '2026-04-18 05:47:24'),
(3, 12, 1, 1, '2026-04-17 12:33:36'),
(19, 10, 2, 1, '2026-04-18 08:16:13'),
(23, 13, 2, 4, '2026-04-18 18:45:45');

-- --------------------------------------------------------

--
-- Table structure for table `gift_categories`
--

DROP TABLE IF EXISTS `gift_categories`;
CREATE TABLE IF NOT EXISTS `gift_categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_title` varchar(255) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `gift_categories`
--

INSERT INTO `gift_categories` (`category_id`, `category_title`) VALUES
(1, 'Greeting Card'),
(6, 'Bonquets'),
(3, 'Gift Hampers'),
(4, 'Scrunchies');

-- --------------------------------------------------------

--
-- Table structure for table `occasions`
--

DROP TABLE IF EXISTS `occasions`;
CREATE TABLE IF NOT EXISTS `occasions` (
  `occasion_id` int NOT NULL AUTO_INCREMENT,
  `occasion_title` varchar(255) NOT NULL,
  PRIMARY KEY (`occasion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `occasions`
--

INSERT INTO `occasions` (`occasion_id`, `occasion_title`) VALUES
(8, 'Birth Day'),
(2, 'Anniversary'),
(3, 'New Year'),
(7, 'Bady Shower'),
(6, 'Mothers Day');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(7, 6, 6, 3, 2970.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `product_title` varchar(255) NOT NULL,
  `product_description` varchar(255) DEFAULT NULL,
  `product_keywords` varchar(255) NOT NULL,
  `occasion_id` int NOT NULL,
  `category_id` int NOT NULL,
  `product_image1` varchar(255) NOT NULL,
  `product_image2` varchar(255) NOT NULL,
  `product_image3` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_title`, `product_description`, `product_keywords`, `occasion_id`, `category_id`, `product_image1`, `product_image2`, `product_image3`, `product_price`, `status`, `created_at`) VALUES
(5, 'Birth day Photo Frame', 'Capture your most cherished birthday memories with this beautifully designed birthday photo frame. Crafted with high-quality materials and a stylish finish, it adds a personal and decorative touch to your special moments', 'Birthday photo frame, photo frame gift, birthday gift, picture frame, decorative frame, memory frame, gift for birthday, personalized gift, home decor, photo display, stylish frame, modern photo frame, keepsake frame, celebration gift, wall decor, table f', 8, 3, 'birthday1.jpg', 'birthday2.jpg', 'birthday3.jpg', 1099.00, 'active', '2026-04-25 14:19:49'),
(4, 'Bady Shower', 'Experience a refreshing and rejuvenating cleanse with our premium body shower gel. Specially formulated to gently remove dirt, oil, and impurities, it leaves your skin feeling soft, smooth, and hydrated.', 'Body shower, body wash, shower gel, skin care, daily cleanser, moisturizing body wash, refreshing shower gel, hydrating formula, soft skin, smooth skin, long-lasting fragrance, bath essentials, personal care, skincare product, gentle cleansing, nourishing', 7, 3, 'babyshower1.jpg', 'babyshower2.jpg', 'babyshower3.jpg', 989.00, 'active', '2026-04-25 14:16:19'),
(3, 'New Year Gift', 'Celebrate the joy of new beginnings with this thoughtfully curated New Year Gift. Perfect for friends, family, and loved ones, this gift set is designed to spread happiness, positivity, and good wishes for the year ahead.', 'Gift, New Year Gift, Happy New Year Present, Holiday Gifts,  Celebration Gift, Premium Gift Set, Best Gift', 3, 3, 'newyaer1.jpg', 'newyaer2.jpg', 'newyaer3.jpg', 1999.00, 'active', '2026-04-23 05:47:25'),
(6, 'Gift Hamper', 'Make every occasion extra special with this thoughtfully curated gift hamper. Packed with a delightful assortment of premium goodies, it’s designed to bring joy, warmth, and a touch of luxury to your loved ones.', 'Gift hamper, gift basket, premium gift set, luxury hamper, assorted gifts, gift combo, festive hamper, birthday hamper, anniversary gift, corporate gift, surprise gift, curated hamper, gift box, celebration gift, special occasion hamper, elegant gift set,', 3, 6, 'gift_hamper1.jpg', 'gift_hamper2.jpg', 'gift_hamper3.jpg', 990.00, 'active', '2026-04-25 14:22:59'),
(7, 'Bonqutes', 'Express your emotions beautifully with our stunning flower bouquets. Handcrafted with fresh, vibrant blooms, each bouquet is thoughtfully arranged to create a perfect blend of color, fragrance, and elegance. Whether it’s love, gratitude, celebration, or s', 'Bouquets, flower bouquet, fresh flowers, floral arrangement, gift bouquet, rose bouquet, mixed flowers, romantic flowers, anniversary flowers, birthday flowers, wedding bouquet, floral gift, elegant flowers, hand bouquet, fresh bloom arrangement, flower g', 6, 2, 'bonqutes1.jpg', 'bonqutes2.jpg', 'bonqutes3.jpg', 2099.00, 'active', '2026-04-25 14:26:53'),
(8, 'Mothers Day', 'Celebrate love, care, and endless sacrifices with a special Mother’s Day gift. This beautiful product is designed to express gratitude and appreciation for the most important woman in your life.', 'Mother’s Day gift, mom gift, gift for mother, Mother’s Day special, love for mom, appreciation gift, mom surprise, heartfelt gift, family gift, personalized gift, best mom gift, special occasion gift, thank you mom, emotional gift, celebration gift', 3, 6, 'mother_day1.jpg', 'mother_day2.jpg', 'mother_day3.jpg', 3099.00, 'active', '2026-04-25 14:29:12');

-- --------------------------------------------------------

--
-- Table structure for table `user_orders`
--

DROP TABLE IF EXISTS `user_orders`;
CREATE TABLE IF NOT EXISTS `user_orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `amount_due` decimal(10,2) NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `total_product` int NOT NULL,
  `payment_id` varchar(255) DEFAULT NULL,
  `payment_mode` varchar(50) NOT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `order_status` enum('pending','completed','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `track_status` enum('Pending','Shipped','Out for Delivery','Delivered') NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_orders`
--

INSERT INTO `user_orders` (`order_id`, `user_id`, `amount_due`, `invoice_number`, `total_product`, `payment_id`, `payment_mode`, `order_date`, `order_status`, `track_status`) VALUES
(6, 18, 2970.00, 'INV69ECCEA55D798', 3, 'pay_Shl1gIfEKdCLoY', 'Razorpay', '2026-04-25 19:54:37', 'completed', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

DROP TABLE IF EXISTS `user_table`;
CREATE TABLE IF NOT EXISTS `user_table` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_image` varchar(255) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `user_mobile` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_status` enum('active','inactive') DEFAULT 'active',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`user_id`, `user_name`, `user_email`, `user_password`, `user_image`, `user_address`, `user_mobile`, `created_at`, `user_date`, `user_status`) VALUES
(18, 'Aditya Verma', 'adityaverma6300@gmail.com', '$2y$10$ROF.rKZERSIq8WRvoPOpa.C98kfdokVetDhytqCK1TAu8VLq1JAFm', 'f1fd31c1-fb7d-4865-992e-152a0f47e91b.jpg', 'Azamgarh', '8299652187', '2026-04-25 14:11:15', '2026-04-25 14:11:15', 'active');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
