-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2025 at 06:04 AM
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
-- Database: `sharefood`
--

-- --------------------------------------------------------

--
-- Table structure for table `ngos`
--

CREATE TABLE `ngos` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `state` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ngos`
--

INSERT INTO `ngos` (`id`, `name`, `email`, `password`, `phone`, `city`, `address`, `approved`, `created_at`, `state`) VALUES
(1, 'abcd', 'abcd@gmail.com', '$2y$10$QKh2r7YDWNCbXViv4XntGu.tWukyQ8yMDBThKMsvNH0TxI5mGllAK', NULL, 'vengurlla', NULL, 0, '2025-08-16 16:12:35', 'maharashtra'),
(2, 'sarvesh', 'srm222@gmail.com', '$2y$10$yTSN6avnir5NH.arfGTc/OHaplmneiuECYXz9ozJZbeBr0OfeuNWu', NULL, 'vengurlla', NULL, 0, '2025-08-17 03:25:23', 'maharashtra');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('food','cloth') NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `pickup_time` datetime DEFAULT NULL,
  `status` enum('open','claimed','removed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `type`, `title`, `description`, `image`, `city`, `pickup_time`, `status`, `created_at`, `expires_at`) VALUES
(2, 2, 'food', 'four plate biryani', 'or kindness', '1755273865_5675.jpg', 'shiroda', '2025-08-15 21:37:00', 'open', '2025-08-15 16:04:25', NULL),
(3, 2, 'cloth', 'for peace', 'enjoy', '1755274044_2535.jpg', 'shiroda', '2025-08-15 12:37:00', 'open', '2025-08-15 16:07:24', NULL),
(4, 2, 'food', 'for kindness', '10 kg of rice', '1755331728_1631.jpg', 'kudal', '2025-08-16 13:43:00', 'open', '2025-08-16 08:08:48', NULL),
(5, 2, 'cloth', 'for need person', 't shert', '1755331836_6164.jpg', 'kudal', '2025-08-16 13:45:00', 'open', '2025-08-16 08:10:36', NULL),
(6, 5, 'cloth', 'abcd', 'jjjfjjjgjjh', '1755364639_7833.jpg', 'vengurlla', '2025-08-16 22:48:00', 'open', '2025-08-16 17:17:19', '2025-08-16 22:48:00'),
(7, 5, 'cloth', 'aaaaa', 'bbbbbb', '1755364767_6599.jpg', 'sawantwadi', '2025-08-16 22:50:00', 'open', '2025-08-16 17:19:27', '2025-08-16 22:50:00'),
(9, 1, 'food', 'for kindness', 'enjoy', '1755401701_5177.jpg', 'vengurlla', '2025-08-17 09:06:00', 'open', '2025-08-17 03:35:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `state` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `photo`, `email`, `password`, `phone`, `city`, `is_admin`, `created_at`, `state`) VALUES
(1, 'sarvesh', NULL, 'sarveshnabar@gmail.com', '$2y$10$Gd9D0vGaZeqjSAlpnee4seGl6kV5GkuDJfWPwtz/S8OltIYyBPACq', '9878767689', 'vengurlla', 0, '2025-08-15 15:09:30', NULL),
(2, 'srm', 'user_2_1755350488.jpg', 'srmcollage73@gmail.com', '$2y$10$D96ByFvJWe/jsNKLz8eiquwGSrV62AL8ET4qs8lJUyBmiNJvCS9Pq', '4543432345', 'shiroda', 0, '2025-08-15 16:01:12', NULL),
(3, 'ngo', NULL, 'ngo123@gmail.com', '$2y$10$60ioZ8xc8vioByfmOj08Zet074Ee3C/oUoAVgGWEyaFVIK8BDwU0q', '6767876765', 'sawantwadi', 0, '2025-08-16 13:31:43', NULL),
(4, 'sarvesh', NULL, 'ngo12@gmail.com', '$2y$10$RFjshcGIuxcnfXNRTVTPuOV95LzjjxPK1QPII9Pq0CSilAveJ0VUy', '9898787676', 'Sindhudurg', 0, '2025-08-16 13:57:20', NULL),
(5, 'srm', 'user_5_1755400269.jpg', 'srm@gmail.com', '$2y$10$C6Tsf/GnK5NSuv2Doyqqve6.c9PCe9Zuf8fjw78npu8VwhoqXg3TW', '8888888888', 'Sindhudurg', 0, '2025-08-16 13:58:41', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ngos`
--
ALTER TABLE `ngos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ngos`
--
ALTER TABLE `ngos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
