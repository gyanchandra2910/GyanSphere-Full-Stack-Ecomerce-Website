-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2025 at 12:17 PM
-- Server version: 8.0.41
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mystore`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_table`
--

CREATE TABLE `admin_table` (
  `admin_id` int NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `admin_email` varchar(200) NOT NULL,
  `admin_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_table`
--

INSERT INTO `admin_table` (`admin_id`, `admin_name`, `admin_email`, `admin_password`) VALUES
(1, 'Gyan Chandra', 'chandragyan2003@gmail.com', '$2y$10$GCbcv255Oys9yZiDgzTdQ.HYedM.HcgST3GXLaKKepjqZN8JOSu9S');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int NOT NULL,
  `brand_title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_title`) VALUES
(5, 'Puma'),
(9, 'OnePlus'),
(10, 'Reebok'),
(11, 'Asus'),
(13, 'Samsung'),
(14, 'Fitbit'),
(15, 'LG');

-- --------------------------------------------------------

--
-- Table structure for table `cart_details`
--

CREATE TABLE `cart_details` (
  `product_id` int NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart_details`
--

INSERT INTO `cart_details` (`product_id`, `ip_address`, `quantity`) VALUES
(4, '::1', 1),
(5, '::1', 1),
(7, '192.168.1.110', 1),
(8, '::1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int NOT NULL,
  `category_title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_title`) VALUES
(1, 'Fruits'),
(7, 'Clothing'),
(8, 'Mobiles'),
(9, 'Laptops'),
(11, 'Home Appliances'),
(12, 'Fitness'),
(13, 'Watches');

-- --------------------------------------------------------

--
-- Table structure for table `orders_pending`
--

CREATE TABLE `orders_pending` (
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `invoice_number` bigint NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `order_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders_pending`
--

INSERT INTO `orders_pending` (`order_id`, `user_id`, `invoice_number`, `product_id`, `quantity`, `order_status`) VALUES
(7, 2, 1744565763, 1, 1, 'pending'),
(8, 2, 1744565864, 2, 1, 'pending'),
(9, 2, 1744566577, 1, 5, 'pending'),
(10, 2, 1744570116, 1, 4, 'pending'),
(11, 2, 1744570425, 1, 1, 'pending'),
(12, 2, 1744570425, 2, 1, 'pending'),
(13, 2, 1744571238, 2, 1, 'pending'),
(14, 2, 20250410003, 3, 1, 'pending'),
(15, 4, 20250410004, 5, 2, 'pending'),
(16, 5, 20250410005, 7, 1, 'pending'),
(17, 2, 1744631956, 4, 1, 'pending'),
(18, 2, 1744633129, 2, 4, 'pending'),
(19, 2, 1744633129, 3, 1, 'pending'),
(20, 2, 1744633129, 4, 5, 'pending'),
(21, 2, 1744633129, 5, 1, 'pending'),
(22, 2, 1744633129, 6, 1, 'pending'),
(23, 2, 1744633129, 8, 8, 'pending'),
(24, 2, 1744633129, 9, 2, 'pending'),
(25, 2, 1744634644, 5, 1, 'pending'),
(26, 2, 1744634720, 8, 1, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `product_title` char(255) DEFAULT NULL,
  `product_description` varchar(255) NOT NULL,
  `product_keywords` varchar(255) NOT NULL,
  `category_id` int NOT NULL,
  `brand_id` int NOT NULL,
  `product_image1` varchar(255) NOT NULL,
  `product_image2` varchar(255) NOT NULL,
  `product_image3` varchar(255) NOT NULL,
  `product_price` int NOT NULL,
  `date` timestamp NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_title`, `product_description`, `product_keywords`, `category_id`, `brand_id`, `product_image1`, `product_image2`, `product_image3`, `product_price`, `date`, `status`) VALUES
(2, 'New apple', 'Good apple', 'green with red', 1, 1, 'red-apple.webp', 'green-apple.webp', 'falling-red-apple-slices-isolated-on-white-background.webp', 222, '2025-04-11 19:45:19', 'true'),
(3, 'Puma Velocity Nitro', 'Lightweight running shoes for daily training.', 'puma, shoes, running', 12, 5, 'velocity1.jpg', 'velocity2.jpg', 'velocity3.jpg', 99, '2025-04-14 09:40:42', 'true'),
(4, 'Samsung Galaxy Watch 5', 'Waterproof smartwatch with AMOLED display.', 'samsung, watch, fitness', 13, 13, 'watch1.jpg', 'watch2.jpg', 'watch3.jpg', 199, '2025-04-14 09:40:42', 'true'),
(5, 'Asus ROG Strix G17', 'High-end gaming laptop with RTX graphics.', 'asus, laptop, gaming', 9, 11, 'rog1.jpg', 'rog2.jpg', 'rog3.jpg', 1499, '2025-04-14 09:40:42', 'true'),
(6, 'OnePlus 11R', 'Flagship OnePlus smartphone with Snapdragon 8+ Gen 1.', 'oneplus, phone, android', 8, 9, '11r_2.jpg', '11r_3.jpg', '11r_3.jpg', 499, '2025-04-14 09:40:42', 'true'),
(7, 'Fitbit Charge 5', 'Advanced health and fitness tracker with built-in GPS.', 'fitbit, fitness, tracker', 12, 14, 'fitbit1.jpg', 'fitbit2.jpg', 'fitbit3.jpg', 129, '2025-04-14 09:40:42', 'true'),
(8, 'Reebok Classic Hoodie', 'Comfortable cotton hoodie.', 'reebok, clothing, hoodie', 7, 10, 'hoodie1.jpg', 'hoodie2.jpg', 'hoodie3.jpg', 45, '2025-04-14 09:40:42', 'true'),
(9, 'LG Front Load 7kg', 'Energy-efficient washing machine.', 'lg, washing machine, home', 11, 15, 'lgwm1.jpg', 'lgwm2.jpg', 'lgwm3.jpg', 349, '2025-04-14 09:40:42', 'true');

-- --------------------------------------------------------

--
-- Table structure for table `user_orders`
--

CREATE TABLE `user_orders` (
  `order_id` int NOT NULL,
  `user_id` int NOT NULL,
  `amount_due` int NOT NULL,
  `invoice_number` int NOT NULL,
  `total_products` int NOT NULL,
  `order_date` timestamp NOT NULL,
  `order_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_orders`
--

INSERT INTO `user_orders` (`order_id`, `user_id`, `amount_due`, `invoice_number`, `total_products`, `order_date`, `order_status`) VALUES
(10, 2, 123, 1744565763, 1, '2025-04-13 17:36:03', 'complete'),
(11, 2, 222, 1744565864, 1, '2025-04-13 17:37:44', 'complete'),
(12, 2, 615, 1744566577, 1, '2025-04-13 17:49:37', 'complete'),
(13, 2, 492, 1744570116, 1, '2025-04-13 18:48:36', 'complete'),
(14, 2, 345, 1744570425, 2, '2025-04-13 18:53:45', 'complete'),
(15, 2, 222, 1744571238, 1, '2025-04-13 19:07:18', 'complete'),
(16, 2, 1499, 100003, 1, '2025-04-14 09:42:16', 'pending'),
(17, 4, 258, 100004, 2, '2025-04-14 09:42:16', 'pending'),
(18, 5, 349, 100005, 1, '2025-04-14 09:42:16', 'pending'),
(19, 2, 199, 1744631956, 1, '2025-04-14 11:59:16', 'pending'),
(20, 2, 5038, 1744633129, 7, '2025-04-14 12:18:49', 'complete'),
(21, 2, 1499, 1744634644, 1, '2025-04-14 12:44:04', 'complete'),
(22, 2, 45, 1744634720, 1, '2025-04-14 12:45:20', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `user_payments`
--

CREATE TABLE `user_payments` (
  `payment_id` int NOT NULL,
  `order_id` int NOT NULL,
  `invoice_number` int NOT NULL,
  `amount` int NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `date` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_payments`
--

INSERT INTO `user_payments` (`payment_id`, `order_id`, `invoice_number`, `amount`, `payment_mode`, `date`) VALUES
(2, 1, 1744540540, 345, 'netbanking', '2025-04-13 13:42:12'),
(3, 1, 1744540540, 345, 'upi', '2025-04-13 13:44:05'),
(4, 1, 1744540540, 345, 'upi', '2025-04-13 13:44:20'),
(5, 2, 1744540800, 888, 'upi', '2025-04-13 13:53:35'),
(6, 6, 1744546232, 222, 'netbanking', '2025-04-13 13:57:33'),
(7, 7, 1744546439, 222, 'payoffline', '2025-04-13 13:58:07'),
(8, 9, 1744565394, 222, 'cod', '2025-04-13 14:00:26'),
(9, 12, 1744566577, 615, 'upi', '2025-04-13 14:20:30'),
(10, 11, 1744565864, 222, 'netbanking', '2025-04-13 14:21:21'),
(11, 10, 1744565763, 123, 'paypal', '2025-04-13 15:17:31'),
(12, 13, 1744570116, 492, 'netbanking', '2025-04-13 15:18:46'),
(15, 3, 100003, 1499, 'Credit Card', '2025-04-14 09:42:21'),
(16, 4, 100004, 258, 'UPI', '2025-04-14 09:42:21'),
(17, 5, 100005, 349, 'Cash on Delivery', '2025-04-14 09:42:21'),
(18, 20, 1744633129, 5038, 'netbanking', '2025-04-14 08:48:55'),
(19, 21, 1744634644, 1499, 'netbanking', '2025-04-14 09:14:14');

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `user_id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_image` varchar(255) NOT NULL,
  `user_ip` varchar(255) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `user_mobile` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`user_id`, `username`, `user_email`, `user_password`, `user_image`, `user_ip`, `user_address`, `user_mobile`) VALUES
(2, 'GyanChandra', 'chandragyan2003@gmail.com', '$2y$10$DXxtqbVqkwfCXhE49ZUpsurTMU5iZ2AZGBe276sM0vnVbJGMhDiBu', '67fbf541db792.jpg', '::1', 'Chennai', '9955187719');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_table`
--
ALTER TABLE `admin_table`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `cart_details`
--
ALTER TABLE `cart_details`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders_pending`
--
ALTER TABLE `orders_pending`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `user_orders`
--
ALTER TABLE `user_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `user_payments`
--
ALTER TABLE `user_payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_table`
--
ALTER TABLE `admin_table`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders_pending`
--
ALTER TABLE `orders_pending`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_orders`
--
ALTER TABLE `user_orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `user_payments`
--
ALTER TABLE `user_payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
