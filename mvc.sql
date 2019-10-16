-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 16, 2019 at 02:36 PM
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
(8, 60480014, 0, NULL, NULL),
(9, 83120010, 0, NULL, NULL),
(10, 4360010, 0, NULL, NULL),
(11, 33730027, 0, NULL, NULL),
(12, 72970051, 0, NULL, NULL),
(13, 81700017, 0, NULL, NULL),
(14, 66950056, 0, NULL, NULL);

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
(7, 60480014, 'syria', 'niedersachen', 'cuxhaven', '27472', 27472, ''),
(8, 83120010, 'germany', '', 'cuxhaven', '27472', 27472, ''),
(9, 4360010, NULL, '', 'cuxhaven', '27472', 27472, ''),
(10, 33730027, 'iraq', '', 'cuxhaven', '27472', 27472, ''),
(11, 72970051, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 81700017, 'syria', 'fdsfd', 'cuxhavenfd', 'fdfsdf', 27473, NULL),
(13, 66950056, 'Deutschland', NULL, 'cuxhaven', 'wernerstr.', 27472, NULL);

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
  `birthday` date NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(250) NOT NULL DEFAULT '0',
  `img` varchar(100) NOT NULL DEFAULT 'avatar.webp',
  `registration` timestamp NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `code`, `username`, `fname`, `lname`, `sex`, `birthday`, `email`, `password`, `img`, `registration`, `status`) VALUES
(3453423, '34536543543', 'admin', 'refat', 'alsakka', 'male', '2004-12-27', 'refatalsakka@gmail.com', '$2y$10$RZo9ppJXMCQTN.xxI3kbpeOgFQV.tQnMALPX/IN8jKg5S9NRQweEG', 'avatar.webp', '2019-10-16 02:11:10', 2),
(4360010, '04360043201916101013', 'fdsfdsfdsf', 'refat', 'alsakka', 'male', '2004-12-20', 'refatafdsflsakka@gmail.com', '0', 'avatar.webp', '2019-10-16 11:43:10', 1),
(33730027, '33730043201916271013', 'fdsfsfgs', 'refat', 'alsakka', 'male', '2004-12-27', 'refatalsadsgsgkka@gmail.com', '0', 'avatar.webp', '2019-10-16 11:43:27', 1),
(60480014, '60480038201916141013', 'aminamin', 'amin', 'amin', 'male', '2004-12-19', 'refatalsakka@gmail.comamin', '0', 'avatar.webp', '2019-10-16 11:38:14', 1),
(66950056, '66960033201916561014', 'fdfdsfds', 'refat', 'alsakka', 'male', '2004-12-28', 'refatafdfdlsakka@gmail.com', '0', 'avatar.webp', '2019-10-16 12:33:56', 1),
(72970051, '72970059201916511013', 'ahmad', 'ahmad', 'ahmad', 'male', '2004-12-14', 'refatalsakka@gmail.comahmad', '0', 'avatar.webp', '2019-10-16 11:59:51', 1),
(81700017, '81710012201916171014', 'rerere', 'refat', 'refataa', 'female', '2004-12-27', 'refatdsfdfdssdlsakka@gmail.com', '0', 'avatar.webp', '2019-10-16 12:12:17', 2),
(83120010, '83120039201916101013', 'addddda', 'amin', 'aminaminamin', 'male', '2004-12-20', 'refatalsadkka@gmail.comamin', '0', 'avatar.webp', '2019-10-16 11:39:10', 2);

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `user_address` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
