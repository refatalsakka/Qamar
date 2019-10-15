-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 15, 2019 at 08:30 AM
-- Server version: 5.7.24
-- PHP Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mvc`
--
CREATE DATABASE IF NOT EXISTS `mvc` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `mvc`;

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `id` int(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_login` tinyint(4) NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `last_logout` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`id`, `user_id`, `is_login`, `last_login`, `last_logout`) VALUES
(1, 649856, 1, '2019-10-09 07:25:24', '2019-10-03 03:16:17'),
(2, 2, 0, NULL, '2019-10-09 02:15:29'),
(5, 57442, 0, '2019-10-08 04:22:20', '2019-10-07 07:20:15'),
(6, 32323, 0, '2019-10-09 06:18:08', '2019-10-10 07:15:12'),
(7, 46676, 1, '2019-10-13 22:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `zip` int(5) DEFAULT NULL,
  `additional` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `user_id`, `country`, `state`, `city`, `street`, `zip`, `additional`) VALUES
(1, 649856, 'syria', 'heomso', 'homs', 'bahnhofstr', NULL, NULL),
(2, 2, 'egypt', '', 'cuxhaven', '', NULL, NULL),
(3, 57442, 'iraq', '', 'baghdad', 'wernerstr.', NULL, '1 edage'),
(4, 32323, 'yemen', '', 'sana\'a', '', NULL, NULL),
(5, 46676, 'germany', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `code` varchar(100) NOT NULL,
  `username` varchar(20) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `sex` varchar(6) NOT NULL,
  `birthday` date DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(250) NOT NULL,
  `img` varchar(100) DEFAULT 'avatar.webp',
  `registration` timestamp NOT NULL,
  `group` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `code`, `username`, `fname`, `lname`, `sex`, `birthday`, `email`, `password`, `img`, `registration`, `group`, `status`) VALUES
(2, '45445', 'amin111', 'amin', 'amin', 'female', '1998-02-25', 'amin@amin.com', '4447987kjhkjhk', '2.webp', '2019-10-05 01:06:17', 0, 0),
(32323, '5554324', 'leena2000', 'leena', 'leena', 'female', '2000-07-12', 'leena@gmail.com', 'fsf4sd4f65sdf', '4.webp', '2019-10-09 04:17:10', 0, 2),
(46676, '446464687648', 'marvel', 'marvel', 'marvel', 'female', '2004-12-31', 'maravel@gmail.com', '546464687', '6.webp', '2019-10-13 22:00:00', 0, 2),
(57442, '23543', 'ahmad', 'ahmad', 'ahmad', 'male', '1938-11-30', 'ahmad@gmail.com', 'gfg54fd654g6df4', '3.webp', '2019-05-08 02:14:14', 1, 1),
(649856, '12345', 'admin', 'refat', 'alsakka', 'male', '1998-01-01', 'refatalsakka@hotmail.com', '$2y$10$RZo9ppJXMCQTN.xxI3kbpeOgFQV.tQnMALPX/IN8jKg5S9NRQweEG', '1.webp', '2019-08-13 15:40:14', 0, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `user_address` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
