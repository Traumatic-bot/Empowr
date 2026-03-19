-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2026 at 11:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cs2team6_db`
--
CREATE DATABASE IF NOT EXISTS `cs2team6_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `cs2team6_db`;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(37, 2, 5, 3, '2025-12-04 19:28:15'),
(38, 2, 7, 3, '2025-12-04 19:28:17');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Processing',
  `shipping_address` text DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `payment_method` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_amount`, `status`, `shipping_address`, `billing_address`, `payment_method`) VALUES
(1, 1, '2025-12-04 06:56:12', 1404.97, 'Processing', '1 place road\n\nbham\nB01 ABC', NULL, 'Credit Card'),
(2, 1, '2025-12-04 06:59:11', 934.95, 'Processing', '1 place road\n\nbham\nB01 ABC', NULL, 'Credit Card'),
(3, 1, '2025-12-04 08:13:51', 304.96, 'Processing', '1 place road\n\nbham\nB01 ABC', NULL, 'Credit Card'),
(5, 1, '2025-12-04 09:28:01', 34.98, 'Processing', '1 place road\n\nbham\nB01 ABC', NULL, 'Credit Card'),
(6, 1, '2025-12-04 09:30:04', 34.98, 'Processing', '1 place road\n\nbham\nB01 ABC', NULL, 'Credit Card'),
(7, 1, '2025-12-04 09:34:09', 34.98, 'Processing', '1 place road\n1\n1\n1', NULL, 'Credit Card'),
(8, 1, '2025-12-04 09:36:04', 734.97, 'Processing', '1\n\n1\n1', NULL, 'Credit Card'),
(9, 1, '2025-12-04 09:37:53', 704.98, 'Processing', '1\n\n1\n1', NULL, 'Credit Card'),
(10, 1, '2025-12-04 09:38:12', 4.99, 'Processing', '1\n\n1\n1', NULL, 'Credit Card'),
(11, 1, '2025-12-04 09:40:40', 704.98, 'Processing', '1\n1\n1\n1', NULL, 'Credit Card'),
(12, 1, '2025-12-04 09:47:44', 704.98, 'Processing', '1\n\n1\n1', NULL, 'Credit Card'),
(13, 1, '2025-12-04 09:49:07', 34.98, 'Processing', '1\n1\n1\n1', NULL, 'Credit Card'),
(14, 1, '2025-12-04 09:52:44', 1404.97, 'Processing', '1\n\n1\n1', NULL, 'Credit Card'),
(15, 1, '2025-12-04 09:54:06', 704.98, 'Processing', '1\n1\n1\n1', NULL, 'Credit Card'),
(16, 1, '2025-12-04 09:55:12', 34.98, 'Processing', '1\n1\n1\n1', NULL, 'Credit Card'),
(17, 1, '2025-12-04 09:55:34', 274.96, 'Processing', '1\n1\n1\n1', NULL, 'Credit Card'),
(18, 1, '2025-12-04 13:13:04', 104.98, 'Processing', '1\n\n1\n1', NULL, 'Credit Card'),
(19, 1, '2025-12-04 16:34:13', 34.98, 'Processing', '1 place road\n1\n1\n1', NULL, 'Credit Card'),
(20, 1, '2025-12-05 00:05:00', 64.97, 'Processing', '1\n1\n1\n1', NULL, 'Credit Card'),
(21, 1, '2025-12-05 00:19:09', 174.97, 'Processing', '1\n1\n1\n1', NULL, 'Credit Card'),
(22, 1, '2025-12-05 04:51:20', 834.96, 'Processing', '1\n1\n1\n1', NULL, 'Credit Card'),
(23, 1, '2025-12-05 05:04:37', 891.96, 'Processing', '1\n1\n1\n1', NULL, 'Credit Card'),
(24, 1, '2025-12-05 06:49:32', 104.99, 'Processing', '1\n1\n1\n1', NULL, 'Credit Card'),
(25, 1, '2025-12-05 06:50:01', 104.99, 'Processing', '1\n1\n1\n1', NULL, 'Credit Card'),
(26, 1, '2025-12-05 06:50:28', 104.99, 'Processing', '1\n1\n1\n1', NULL, 'Credit Card'),
(27, 1, '2025-12-05 06:57:58', 104.99, 'Processing', '1\n1\n1\n1', NULL, 'Credit Card'),
(28, 1, '2025-12-05 06:58:22', 104.98, 'Processing', '1\n1\n1\n1', NULL, 'Credit Card'),
(29, 1, '2025-12-05 07:04:20', 384.98, 'Processing', '1\n1\n1\n1', NULL, 'Credit Card'),
(30, 1, '2025-12-05 07:48:28', 104.99, 'Processing', '1\n1\n1\n1', NULL, 'Credit Card');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `discounted_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `discounted_price`) VALUES
(1, 1, 7, 2, 699.99, 1399.98, 1399.98),
(2, 2, 7, 1, 699.99, 699.99, 699.99),
(3, 2, 5, 1, 29.99, 29.99, 29.99),
(4, 2, 8, 1, 69.99, 69.99, 69.99),
(5, 2, 10, 1, 129.99, 129.99, 129.99),
(6, 3, 1, 3, 99.99, 299.97, 299.97),
(7, 5, 5, 1, 29.99, 29.99, 29.99),
(8, 6, 5, 1, 29.99, 29.99, 29.99),
(9, 7, 5, 1, 29.99, 29.99, 29.99),
(10, 8, 7, 1, 699.99, 699.99, 699.99),
(11, 8, 5, 1, 29.99, 29.99, 29.99),
(12, 9, 7, 1, 699.99, 699.99, 699.99),
(13, 11, 7, 1, 699.99, 699.99, 699.99),
(14, 12, 7, 1, 699.99, 699.99, 699.99),
(15, 13, 5, 1, 29.99, 29.99, 29.99),
(16, 14, 7, 2, 699.99, 1399.98, 1399.98),
(17, 15, 7, 1, 699.99, 699.99, 699.99),
(18, 16, 5, 1, 29.99, 29.99, 29.99),
(19, 17, 2, 1, 139.99, 139.99, 139.99),
(20, 17, 5, 1, 29.99, 29.99, 29.99),
(21, 17, 1, 1, 99.99, 99.99, 99.99),
(22, 18, 1, 1, 99.99, 99.99, 99.99),
(23, 19, 5, 1, 29.99, 29.99, 29.99),
(24, 20, 5, 2, 29.99, 59.98, 59.98),
(25, 21, 5, 1, 29.99, 29.99, 29.99),
(26, 21, 2, 1, 139.99, 139.99, 139.99),
(27, 22, 5, 1, 100.00, 100.00, 100.00),
(28, 22, 1, 1, 99.99, 99.99, 99.99),
(29, 22, 11, 1, 129.99, 129.99, 129.99),
(30, 22, 14, 1, 499.99, 499.99, 499.99),
(31, 23, 5, 1, 100.00, 100.00, 100.00),
(32, 23, 6, 1, 379.99, 379.99, 379.99),
(33, 23, 11, 1, 129.99, 129.99, 129.99),
(34, 23, 13, 1, 276.99, 276.99, 276.99),
(35, 24, 5, 1, 100.00, 100.00, 100.00),
(36, 25, 5, 1, 100.00, 100.00, 100.00),
(37, 26, 5, 1, 100.00, 100.00, 100.00),
(38, 27, 5, 1, 100.00, 100.00, 100.00),
(39, 28, 1, 1, 99.99, 99.99, 99.99),
(40, 29, 6, 1, 379.99, 379.99, 379.99),
(41, 30, 5, 1, 100.00, 100.00, 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `price`, `discount_percent`, `stock_quantity`, `category`, `description`, `image_url`) VALUES
(1, 'BigKeys lx Keyboard', 99.99, 0.00, 498, 'Keyboards', 'This keyboard assists users who are unable to type efficiently, this is achieved through the use of bigger keys, vibrant colours to allow the user to see better and improve key recognition, and is available in both ABC and QWERTY format to better suit the customers needs and improve their familiarity with the layout \r\n', 'https://www.keyboardspecialists.co.uk/cdn/shop/products/K-LXQCLC1_473x473.jpg?v=1516043621'),
(2, 'Maltron Expanded Keyboard', 139.99, 0.00, 0, 'Keyboards', 'This keyboard is designed for customers who have severe disabilities and enables users who suffer from advanced disabilities to type clearly and efficiently, by expanding the space between the keys. This is a keyboard that was built to endure all types of typing methods, such as facilitating users who use feet, fists or assistive walking devices to type', 'https://abilitynet.org.uk/sites/abilitynet.org.uk/files/image11.jpeg'),
(3, 'Logitech Adaptive Mouse', 29.99, 0.00, 0, 'Mice', 'This mouse is perfect for customers who have limited mobility or have special ergonomic needs. It was designed with a customisable frame with magnetic attachments for comfortability, customers are able to adjust the sizes of the mouses features, and adapts to the users physical capabilities ensuring that the customer may freely and independently use it no matter how limited their mobility is.', 'https://media.johnlewiscontent.com/i/JohnLewis/112786403?fmt=auto&$background-off-white$'),
(4, 'Hippus HandShoe Mouse', 139.99, 0.00, 500, 'Mice', 'This is an ergonomic mouse that was designed for users who have limited grip functions, hand pain, or limited wrist capabilities. This mouse fully supports the hand in all movements to ensure smooth use. This product is available in a variety of sizes to accommodate for different hand structures. It is a great way for users to maintain a natural grip position which alleviates stress to their wrists.', 'https://www.keyboardco.com/product-images/handshoe_mouse_right_large_large.jpg'),
(5, 'Ablenet BIGtrack Trackball Mouse', 100.00, 0.00, 493, 'Mice', 'This mouse is a large tracking ball which was designed for users with reduced motor capabilities, the 3 inch tracking ball allows for the customer to utilise the mouse using their palms, is customised with bigger buttons and requires way less precision to operate effectively compared to normal computer mice.', 'https://as-images.apple.com/is/HJ322?wid=1200&hei=630&fmt=jpeg&qlt=95&op_usm=0.5,0.5&.v=0'),
(6, 'Dell P2418HT', 379.99, 0.00, 498, 'Monitors', 'This is a 24-inch monitor that is equipped with touchscreen technology allowing for simple use and is used by many people who find this more beneficial than using a standard mouse to navigate their screen. This monitor is equipped with a flexible stand which allows the user to position the monitor to their preferred position whether that be flat or angled to allow users with limited arm mobility to independently use this device.', 'https://cdn.cs.1worldsync.com/c0/f8/c0f89e09-215e-4013-a95e-85d395fb92b9.jpg'),
(7, 'BenQ GW2785TC', 109.99, 0.00, 0, 'Monitors', 'This monitor was designed to cater towards individuals with visual sensitivity, and those who suffer from persistent migraines caused by light based discomfort. The monitors ergonomic stand allows for users to reposition the screen to their preferability, comes equipped with a blue light setting which protects users who suffer due to harsh lights, and also automatically adjusts its brightness to match the surrounding light levels, alleviating eye strained by lighting differences.', 'https://www.laptopsdirect.co.uk/Images/9H.LKNLB.QBE_2_Supersize.jpg?v=7'),
(8, 'Index Basic-D V5 Braille Embosser \r\n', 2499.99, 0.00, 500, 'Printers', 'This printer was designed with the intention of serving users who are visually impaired on a large scale, through converting digital text to on – paper braille. This printer is able to operate over a network, WiFi, or USB, is able to print up to 140 characters per second, and supports most braille translation software making this a widely popular device within the blind community.', 'https://store.humanware.com/media/catalog/product/cache/af31f082d815f0cbf68dfc31e7356880/e/m/embosser_everest-d_v5.jpg'),
(9, 'Xbox Adaptive Controller', 74.99, 0.00, 500, 'Controllers', 'This controller was created by Microsoft to assist users  with limited mobility. This controller comes with large buttons which are able to be customised to suit the customers needs and fit the desired functions for their use. This controller allows users to design their own setup which assists with easy accessibility.', 'https://i0.wp.com/caniplaythat.com/wp-content/uploads/2020/09/Xbox-Adaptive-Controller-Top-View.jpg?resize=800%2C450&ssl=1'),
(10, 'QuadStick FPS ', 940.00, 0.00, 500, 'Controllers', 'This is a hands-free controller that was designed to assist users with severely limited mobility through the use of “sip and puff” technology to allow this to control movements. The sensors in this mouth operated controller allows the customer to continue their gaming experience without the need for thumb sticks.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTc86puXlRNmIgl4wPxJuCGLI6x2eg4dQkJRw&s'),
(11, 'Canon PIXMA TS9550', 129.99, 0.00, 498, 'Printers', 'This printer was designed to accommodate for users who struggle with low vision and visual impairments by supporting massive fonts. The main focus of this device is to print A3 paper style documents, works through integration with accessibility software on computers, and comes equipped with a large touchscreen on the printer for simple accessibility.', 'https://cdn.cs.1worldsync.com/9e/9e/9e9e5d17-1c09-47e4-a5dc-cf71f0584bbe.jpg'),
(12, 'Herman Miller Aeron Chair', 899.99, 0.00, 500, 'Desks & Chairs', 'This chair was designed for the use of customers who suffer from extreme back pains, limited mobility, or poor posture. This chair comes fitted with adjustable back support to accommodate for users who are unable to sit properly, comes with breathable technology to accommodate users who sit for extended periods of time, and also reduces back strains through promoting healthy seating positions and long-term comfort.', 'https://ukstore.hermanmiller.com/cdn/shop/products/01-Herman_Miller-Aeron-Graphite-Standard_01a3c410-e443-4aae-a2d1-009f32cdabfd.jpg?height=1180&v=1665747538&width=960'),
(13, 'Fully Adjustable Standing Desk', 276.99, 0.00, 499, 'Desks & Chairs', 'This is a free-standing desk which is able to adjust to different heights to assist with comfortability for users who are in wheelchairs, suffer from chronic pains, or limited mobility. This desk comes equipped with electric height customisation through the use of simply clicking a button.', 'https://m.media-amazon.com/images/I/61G1jVwInTL._AC_UF894,1000_QL80_.jpg'),
(14, 'Canon PowerShot SX740 HS', 499.99, 0.00, 499, 'Cameras and Microphones', 'This camera is built to accommodate users who suffer from limited hand mobility, hand tremors, and weaker arms through the use of its weightless design and advanced stabilisation. The camera is lightweight which assists in keeping the camera stable, the camera is fitted with larger buttons making this accessible for users with limited vision, and is also fitted with state of the art image stabilisation technology which reduces the likelihood of images turning out blurry from movement while the picture/video is being taken.', 'https://m.media-amazon.com/images/I/61coV46OzML.jpg'),
(15, 'GoPro Hero12', 217.99, 0.00, 500, 'Cameras and Microphone', 'GoPro Hero12', 'https://static.gopro.com/assets/blta2b8522e5372af40/blt86b2d5c67d4f1ed5/64d0e286369276296caf7a71/02-pdp-h12b-gallery-1920.png'),
(16, 'Shure MV7X XLR Dynamic Microphone', 199.99, 0.00, 15, 'Microphones', 'Professional XLR dynamic microphone', 'https://m.media-amazon.com/images/I/712Xa1xLMIL._AC_SY879_.jpg'),
(17, 'Shure BETA 58A Vocal Microphone', 179.99, 0.00, 20, 'Microphones', 'Vocal microphone for live performances', 'https://m.media-amazon.com/images/I/71-RRWoERbL._AC_SY550_.jpg'),
(18, 'Shure Super 55 Deluxe Vocal Microphone', 159.99, 0.00, 18, 'Microphones', 'Vintage style vocal microphone', 'https://m.media-amazon.com/images/I/61w7BmiDcUL._AC_SY879_.jpg'),
(19, 'BUERTT Xbox One Controller', 59.99, 0.00, 25, 'Controllers', 'Official Xbox One controller by BUERTT', 'https://m.media-amazon.com/images/I/51vTx+sefrL._SX425_.jpg'),
(20, 'GameSir G7 SE Wired Controller for Xbox', 49.99, 0.00, 20, 'Controllers', 'Wired controller compatible with Xbox', 'https://m.media-amazon.com/images/I/51iXILIT27L._SX425_.jpg'),
(21, 'NINIFEI For Xbox One Controller', 39.99, 0.00, 22, 'Controllers', 'Third-party controller for Xbox One', 'https://m.media-amazon.com/images/I/51v0ZNhBo6L._SX425_.jpg'),
(22, 'Lufeiya Gaming Desk with LED Lights and Storage Shelves', 299.99, 0.00, 8, 'Desks & Chairs', 'Gaming desk with LED lights and shelves', 'https://m.media-amazon.com/images/I/71ujWChhVPL._AC_SX569_.jpg'),
(23, 'Lufeiya Reversible Gaming Desk with LED Lights and Power Outlets', 349.99, 0.00, 5, 'Desks & Chairs', 'Reversible desk with LED lights and outlets', 'https://m.media-amazon.com/images/I/71LU4nJD3rL._AC_SX569_.jpg'),
(24, 'Lufeiya 47 inch Computer Desk with 4 Drawers', 279.99, 0.00, 7, 'Desks & Chairs', '47 inch computer desk with 4 drawers', 'https://m.media-amazon.com/images/I/71nwBbod7uL._AC_SX569_.jpg'),
(25, 'Epson EcoTank ET-2803', 299.99, 0.00, 10, 'Printers', 'EcoTank printer with refillable ink', 'https://m.media-amazon.com/images/I/71DRljgLdvL._AC_SX425_.jpg'),
(26, 'Epson Expression Home XP-4200', 149.99, 0.00, 12, 'Printers', 'All-in-one color inkjet printer', 'https://m.media-amazon.com/images/I/71t9MdkSaML._AC_SX425_.jpg'),
(27, 'Epson Workforce WF-2960', 199.99, 0.00, 8, 'Printers', 'Wireless printer with scanning and copying', 'https://m.media-amazon.com/images/I/81FxNlcL57L._AC_SX425_.jpg'),
(28, 'Dell 27 Plus Monitor - S2725HSM', 399.99, 0.00, 7, 'Monitors', '27 inch monitor with high resolution', 'https://m.media-amazon.com/images/I/71P5Rv9+DwL._AC_SX425_.jpg'),
(29, 'Dell S2425HS Monitor - 23.8 Inch', 249.99, 0.00, 10, 'Monitors', '23.8 inch monitor with vibrant colors', 'https://m.media-amazon.com/images/I/710HmaQgX3L._AC_SY300_SX300_QL70_FMwebp_.jpg'),
(30, 'Dell E2420H 23.8 Inch', 199.99, 0.00, 12, 'Monitors', 'Business monitor 23.8 inch', 'https://m.media-amazon.com/images/I/71MB2+sAHtL._AC_SX425_.jpg'),
(31, 'Dell Mobile Wireless Mouse - MS3320W', 49.99, 0.00, 20, 'Mice', 'Wireless mobile mouse', 'https://m.media-amazon.com/images/I/61yhJuYUoEL._AC_SX425_.jpg'),
(32, 'Dell MS700 Bluetooth Travel Mouse', 39.99, 0.00, 15, 'Mice', 'Bluetooth travel mouse', 'https://m.media-amazon.com/images/I/51zGjgek1WL._AC_SX425_.jpg'),
(33, 'Logitech MX Keys S Wireless Keyboard', 129.99, 0.00, 15, 'Keyboards', 'Wireless keyboard with perfect typing experience', 'https://m.media-amazon.com/images/I/71G7uXAb9BL._AC_SX425_.jpg'),
(34, 'Logitech Wave Keys Wireless Ergonomic Keyboard with Cushioned Palm Rest', 149.99, 0.00, 12, 'Keyboards', 'Ergonomic wireless keyboard', 'https://m.media-amazon.com/images/I/71Yp7pxBFOL._AC_SX425_.jpg'),
(35, 'Logitech MX Mechanical Wireless Illuminated Performance Keyboard', 179.99, 0.00, 10, 'Keyboards', 'Mechanical wireless keyboard with illumination', 'https://m.media-amazon.com/images/I/61++ok6AqtL._AC_SY300_SX300_QL70_FMwebp_.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `title` varchar(10) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `user_type` enum('customer','staff') NOT NULL DEFAULT 'customer',
  `dark_mode` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `title`, `first_name`, `last_name`, `email`, `password`, `phone`, `created_at`, `user_type`, `dark_mode`) VALUES
(1, 'Mr', 'guest', 'acount', 'guest@email.com', 'password', '01234567891', '2025-12-04 03:57:01', 'customer', 0),
(2, 'Mr', 'ted', 'testtt', 'abc@def.ghi', 'b', '', '2025-12-04 19:26:41', 'customer', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_type` enum('shipping','billing') DEFAULT 'shipping',
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `postcode` varchar(20) NOT NULL,
  `country` varchar(100) DEFAULT 'United Kingdom',
  `is_default` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

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
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
