-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2024 at 01:28 PM
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
-- Database: `waridei661010170`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'youuukai', 'tammarathugues@gmail.com', 'hiwaddi', '2024-12-06 21:08:05'),
(2, 'youuukai', 'tammarathugues@gmail.com', 'hi', '2024-12-06 21:10:37');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `name`, `address`, `payment_method`, `order_date`, `status`) VALUES
(3, 2, 'youuukai', 'wadwada', 'Cash on Delivery', '2024-12-07 03:02:52', 'Pending'),
(4, 2, 'awdad', 'wadwada', 'Cash on Delivery', '2024-12-07 03:03:12', 'Pending'),
(5, 1, 'wdad', 'wadwad', 'PayPal', '2024-12-07 03:08:47', 'Cancelled'),
(6, 1, 'awdadad', 'wadwadadadad', 'Credit Card', '2024-12-07 03:43:40', 'Shipped'),
(7, 1, 'โยกไก่', 'chonburi', 'Credit Card', '2024-12-07 17:57:57', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(4, 3, 4, 1, 350.00),
(5, 3, 5, 1, 280.00),
(6, 4, 5, 1, 280.00),
(7, 5, 5, 1, 280.00),
(8, 6, 4, 1, 350.00),
(9, 6, 5, 2, 280.00),
(10, 6, 6, 2, 450.00),
(11, 7, 4, 1, 350.00),
(12, 7, 5, 1, 99.00),
(13, 7, 6, 1, 450.00),
(14, 7, 7, 1, 500.00),
(15, 7, 8, 1, 600.00),
(16, 7, 9, 1, 400.00),
(17, 7, 10, 1, 300.00),
(18, 7, 11, 1, 320.00),
(19, 7, 12, 1, 550.00),
(20, 7, 13, 1, 250.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `price`, `description`, `image`, `stock_quantity`) VALUES
(4, 'กล้วยไม้ฟาแลนนอปซิส (Phalaenopsis)', 350.00, 'กล้วยไม้พันธุ์ยอดนิยม มีดอกขนาดใหญ่ สีสันสดใส เหมาะสำหรับตั้งโชว์ในบ้าน', 'img/product1.jpg', 5),
(5, 'กล้วยไม้หวายสีขาว (Dendrobium White)', 99.00, 'พันธุ์หวายที่ออกดอกเป็นพวง มีสีขาวสะอาดตา ดูแลง่าย', 'img/product2.jpg', 5),
(6, 'กล้วยไม้แวนด้าสีม่วง (Vanda Purple)', 450.00, 'แวนด้าเน้นดอกสีม่วงเข้ม บานได้นานและให้ความรู้สึกหรูหรา', 'img/product3.jpg', 5),
(7, 'กล้วยไม้รองเท้านารี (Paphiopedilum)', 500.00, 'พันธุ์หายาก ดอกมีลักษณะคล้ายรองเท้าบู๊ต มีกลิ่นหอมอ่อน ๆ', 'img/product4.jpg', 5),
(8, 'กล้วยไม้ช้างกระ (Rhynchostylis gigantea)', 600.00, 'ดอกกล้วยไม้ที่มีกลิ่นหอมแรง ดอกเป็นพวงสวยงาม สีขาวแต้มม่วง', 'img/product5.jpg', 5),
(9, 'กล้วยไม้แคทลียา (Cattleya)', 400.00, 'กล้วยไม้ราชินีแห่งดอกไม้ มีกลีบดอกขนาดใหญ่และหลากหลายสี', 'img/product6.jpg', 5),
(10, 'กล้วยไม้เอื้องคำ (Dendrobium lindleyi)', 300.00, 'ดอกสีเหลืองสดใส ออกเป็นช่อ ดูแลรักษาง่าย', 'img/product7.jpg', 5),
(11, 'กล้วยไม้หวายสีชมพู (Dendrobium Pink)', 320.00, 'กล้วยไม้หวายพันธุ์ดอกสีชมพูหวาน ให้ความรู้สึกสดใส', 'img/product8.jpg', 5),
(12, 'กล้วยไม้แวนด้าลายเสือ (Vanda Tiger Stripes)', 550.00, 'กล้วยไม้ที่มีลวดลายดอกไม่เหมือนใคร สีสันโดดเด่น', 'img/product9.jpg', 5),
(13, 'กล้วยไม้หวายจิ๋ว (Mini Dendrobium)', 250.00, 'กล้วยไม้ขนาดเล็ก กระถางเล็ก เหมาะสำหรับตกแต่งพื้นที่จำกัด', 'img/product10.jpg', 5),
(17, 'wadwa', 5555.00, NULL, 'img/product11.jpg', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `created_at`, `role`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin@example.com', '2024-12-06 07:39:48', 'admin'),
(2, 'user', '6ad14ba9986e3615423dfca256d04e3f', 'user@example.com', '2024-12-06 20:01:35', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_orders_users` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
