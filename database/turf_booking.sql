-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 21, 2025 at 06:05 PM
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
-- Database: `turf_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(10) UNSIGNED NOT NULL,
  `turf_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','rejected','cancelled','completed') NOT NULL DEFAULT 'pending',
  `payment_status` enum('unpaid','paid','refunded') NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `turf_id`, `customer_id`, `booking_date`, `start_time`, `end_time`, `total_price`, `status`, `payment_status`, `created_at`) VALUES
(1, 3, 3, '2025-11-20', '14:28:00', '17:28:00', 2400.00, 'pending', 'unpaid', '2025-11-21 10:28:41'),
(2, 2, 3, '2025-11-21', '09:00:00', '11:00:00', 3000.00, 'pending', 'unpaid', '2025-11-21 11:01:21'),
(3, 2, 3, '2025-11-21', '11:00:00', '13:00:00', 3000.00, 'pending', 'unpaid', '2025-11-21 11:01:23'),
(4, 2, 3, '2025-11-29', '11:00:00', '13:00:00', 3000.00, 'approved', 'unpaid', '2025-11-21 11:01:28'),
(5, 2, 3, '2025-11-21', '13:00:00', '15:00:00', 3000.00, 'rejected', 'unpaid', '2025-11-21 11:01:31'),
(6, 2, 3, '2025-11-26', '13:00:00', '15:00:00', 3000.00, 'approved', 'unpaid', '2025-11-21 11:01:40'),
(7, 3, 3, '2025-11-21', '09:00:00', '11:00:00', 1600.00, 'rejected', 'unpaid', '2025-11-21 11:12:32'),
(8, 3, 3, '2025-11-21', '11:00:00', '13:00:00', 1600.00, 'rejected', 'unpaid', '2025-11-21 11:12:40'),
(9, 2, 3, '2025-11-22', '13:00:00', '15:00:00', 3000.00, 'approved', 'unpaid', '2025-11-21 11:19:18'),
(10, 3, 4, '2025-11-21', '13:00:00', '15:00:00', 1600.00, 'approved', 'unpaid', '2025-11-21 11:55:30'),
(11, 3, 3, '2025-11-21', '09:00:00', '11:00:00', 1600.00, 'approved', 'unpaid', '2025-11-21 12:03:25'),
(12, 3, 3, '2025-11-22', '11:00:00', '13:00:00', 1600.00, 'pending', 'unpaid', '2025-11-21 12:03:26'),
(13, 3, 3, '2025-11-21', '11:00:00', '13:00:00', 1600.00, 'pending', 'unpaid', '2025-11-21 12:03:28');

-- --------------------------------------------------------

--
-- Table structure for table `booking_window`
--

CREATE TABLE `booking_window` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `days_to_show` int(11) NOT NULL DEFAULT 10,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_window`
--

INSERT INTO `booking_window` (`id`, `start_date`, `days_to_show`, `updated_at`) VALUES
(1, '2025-11-26', 10, '2025-11-21 12:05:10');

-- --------------------------------------------------------

--
-- Table structure for table `turfs`
--

CREATE TABLE `turfs` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `location` varchar(255) NOT NULL,
  `sport_type` enum('football','badminton') NOT NULL,
  `price_per_hour` decimal(10,2) NOT NULL,
  `manager_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `turfs`
--

INSERT INTO `turfs` (`id`, `name`, `location`, `sport_type`, `price_per_hour`, `manager_id`, `status`, `created_at`) VALUES
(2, 'Mohammadpur Football Turf', 'Mohammadpur, Dhaka', 'football', 1500.00, 2, 'active', '2025-11-21 09:45:50'),
(3, 'Mohammadpur Badminton Court', 'Mohammadpur, Dhaka', 'badminton', 800.00, 2, 'active', '2025-11-21 09:45:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(190) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','manager','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `created_at`) VALUES
(1, 'jahid', 'jahid@admin.com', '017', '$2y$10$xU5EgNMCfGFgfiuyUPKcUu3F7j2d4Lv9fAbfZvsej57EXf36EcU06', 'admin', '2025-11-20 20:06:32'),
(2, 'jihad', 'jahid@manager.com', '017', '$2y$10$4Zf9EzCHukjaKujsAqe/W.C2vWWoJVQ9uqfeLbfc3UqUFT2wtJMUi', 'manager', '2025-11-20 20:15:32'),
(3, 'kawser', 'kawser@gmail.com', '017', '$2y$10$Cp3a2CrdUG7704o7UxFX7ehA8XwbXcgVZRpMxFLQG8ARbkLYdVDC6', 'customer', '2025-11-21 10:28:12'),
(4, 'sana', 'sana@gmail.com', '017', '$2y$10$l4OE8B8tpVDyT.t3MwXOGeltGYG2rJa15imkjvXySyIHgDNHyQ.ye', 'customer', '2025-11-21 11:54:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_bookings_customer` (`customer_id`),
  ADD KEY `idx_bookings_turf` (`turf_id`),
  ADD KEY `idx_bookings_date` (`booking_date`);

--
-- Indexes for table `booking_window`
--
ALTER TABLE `booking_window`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `turfs`
--
ALTER TABLE `turfs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_turfs_manager` (`manager_id`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `turfs`
--
ALTER TABLE `turfs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_bookings_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bookings_turf` FOREIGN KEY (`turf_id`) REFERENCES `turfs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `turfs`
--
ALTER TABLE `turfs`
  ADD CONSTRAINT `fk_turfs_manager` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
