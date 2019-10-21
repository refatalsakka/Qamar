-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 21, 2019 at 02:38 PM
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
(14, 66950056, 0, NULL, NULL),
(15, 32590052, 0, NULL, NULL),
(16, 3980002, 0, NULL, NULL),
(18, 580028, 0, NULL, NULL),
(19, 56550005, 0, NULL, NULL),
(20, 6380034, 0, NULL, NULL),
(21, 26400059, 0, NULL, NULL),
(22, 83610019, 0, NULL, NULL),
(23, 32120018, 0, NULL, NULL),
(24, 22150052, 0, NULL, NULL),
(25, 58590052, 0, NULL, NULL),
(26, 46510013, 0, NULL, NULL),
(27, 95490016, 0, NULL, NULL),
(28, 51660058, 0, NULL, NULL),
(29, 45380012, 0, NULL, NULL),
(30, 19910038, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip` int(5) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `house_number` smallint(5) DEFAULT NULL,
  `additional` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `user_id`, `country`, `state`, `zip`, `city`, `street`, `house_number`, `additional`) VALUES
(7, 60480014, 'mauritania', 'niedersachen', 27472, 'cuxhaven', '27472', NULL, ''),
(8, 83120010, 'jordan', '', 27472, 'cuxhaven', '27472', NULL, ''),
(9, 4360010, 'sudan', '', 27472, 'cuxhaven', '27472', NULL, ''),
(10, 33730027, 'iraq', '', 27472, 'cuxhaven', '27472', NULL, ''),
(11, 72970051, 'libya', NULL, NULL, NULL, NULL, NULL, NULL),
(12, 81700017, 'syria', 'fdsfd', 27473, 'cuxhavenfd', 'fdfsdf', NULL, NULL),
(13, 66950056, 'egypt', NULL, NULL, 'alexandria', NULL, NULL, NULL),
(14, 32590052, 'yemen', NULL, 27472, 'cuxhaven', 'wernerstr.', NULL, NULL),
(15, 3980002, 'oman', NULL, 27472, 'cuxhaven', 'wernerstr.', NULL, NULL),
(17, 580028, 'qatar', NULL, 27472, 'cuxhaven', 'wernerstr.', NULL, NULL),
(18, 56550005, 'emirates', NULL, 27472, 'cuxhaven', 'wernerstr.', NULL, NULL),
(19, 6380034, 'timor-leste', NULL, 27472, 'cuxhaven', 'wernerstr.', NULL, NULL),
(20, 26400059, 'lebanon', NULL, NULL, 'beirut', NULL, NULL, NULL),
(21, 83610019, 'algeria', NULL, NULL, 'algiers', NULL, NULL, NULL),
(22, 32120018, 'tunisia', 'tunisia', NULL, 'tunisia', NULL, NULL, NULL),
(23, 3453423, 'syria', NULL, 27472, 'cuxhaven', NULL, NULL, NULL),
(24, 22150052, 'comoros', NULL, NULL, 'moroni', NULL, NULL, NULL),
(25, 58590052, 'djibouti', NULL, NULL, 'djibouti', NULL, NULL, NULL),
(26, 46510013, 'saudi arabia', NULL, NULL, 'riyadh', 'wernerstr.', NULL, NULL),
(27, 95490016, 'slovenia', NULL, NULL, 'mogadishu', NULL, NULL, NULL),
(28, 51660058, 'algeria', NULL, NULL, 'manama', NULL, NULL, NULL),
(29, 45380012, 'germany', 'fdsfsd', 27472, 'cuxhaven', 'wernerstr.', NULL, 'fdsfdsfdsfsd'),
(30, 19910038, 'ghana', NULL, 27472, 'testts', 'testts', 33, 'testts');

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
(580028, '00580044201917281010', 'fdsfdsfsdfdsfdsf', 'refat', 'alsakka', 'male', '2004-12-14', 'refatafdfdfdsfsflsakka@gmail.com', '0', 'avatar.webp', '2019-10-17 08:44:28', 1),
(3453423, '34536543543', 'admin', 'refat', 'alsakka', 'male', '2004-12-27', 'refatalsakka@gmail.com', '$2y$10$RZo9ppJXMCQTN.xxI3kbpeOgFQV.tQnMALPX/IN8jKg5S9NRQweEG', 'avatar.webp', '2019-10-16 02:11:10', 2),
(3980002, '03980043201917021010', 'fdsfsd', 'refat', 'alsakka', 'male', '2004-12-28', 'refatalsadsfkka@gmail.com', '0', 'avatar.webp', '2019-10-17 08:43:02', 1),
(4360010, '04360043201916101013', 'fdsfdsfdsf', 'refat', 'alsakka', 'male', '2004-12-20', 'refatafdsflsakka@gmail.com', '0', 'avatar.webp', '2019-10-16 11:43:10', 1),
(6380034, '06380058201917341010', 'fdfdfdd', 'refat', 'alsakka', 'male', '2004-12-22', 'refadtalfdsakka@gmail.com', '0', 'avatar.webp', '2019-10-17 08:58:34', 0),
(19910038, '19910057201918381010', 'efefe', 'testts', 'alsakkatestts', 'male', '2004-12-27', 'refatalsakka@gmail.comtestts', '0', '1.webp', '2019-10-18 08:57:38', 2),
(22150052, '22150039201918521007', 'nasser', 'nasser', 'nasser', 'male', '2004-12-14', 'nasser@gmail.com', '0', 'avatar.webp', '2019-10-18 05:39:52', 1),
(26400059, '26400058201917591010', 'yousef', 'yousef', 'yousef', 'male', '2004-12-08', 'yousef@gmail.com', '0', 'avatar.webp', '2019-10-17 08:58:59', 1),
(32120018, '32120018201917181014', 'amira', 'amira', 'amira', 'female', '2004-12-27', 'amira@gmail.com', '0', 'avatar.webp', '2019-10-17 12:18:18', 2),
(32590052, '32600040201917521010', 'dfsdfsd', 'refat', 'alsakka', 'male', '2004-12-22', 'refatalsfdsfakka@gmail.com', '0', 'avatar.webp', '2019-10-17 08:40:52', 0),
(33730027, '33730043201916271013', 'fdsfsfgs', 'refat', 'alsakka', 'male', '2004-12-27', 'refatalsadsgsgkka@gmail.com', '0', 'avatar.webp', '2019-10-16 11:43:27', 1),
(45380012, '45380048201918121010', 'erefdsf', 'ddd', 'alsakka', 'male', '2004-12-20', 'refatalsffffffffffffffffffffffakka@gmail.com', '0', 'avatar.webp', '2019-10-18 08:48:12', 1),
(46510013, '46510008201918131009', 'basma', 'basma', 'basma', 'female', '2004-12-28', 'basma@gmail.com', '0', 'avatar.webp', '2019-10-18 07:08:13', 1),
(51660058, '51660010201918581009', 'sarah', 'sarah', 'sarah', 'female', '2004-12-28', 'sarah@gmail.com', '0', 'avatar.webp', '2019-10-18 07:10:58', 2),
(56550005, '56560055201917051010', 'fdsfsdfd', 'refat', 'alsakka', 'male', '2004-12-23', 'refatalsfdakka@gmail.com', '0', 'avatar.webp', '2019-10-17 08:55:05', 1),
(58590052, '58600027201918521008', 'nesreen', 'nesreen', 'nesreen', 'female', '1997-06-20', 'nesreen@gmail.com', '0', 'avatar.webp', '2019-10-18 06:27:52', 1),
(60480014, '60480038201916141013', 'aminamin', 'amin', 'amin', 'male', '2004-12-19', 'refatalsakka@gmail.comamin', '0', 'avatar.webp', '2019-10-16 11:38:14', 1),
(66950056, '66960033201916561014', 'osama', 'osama', 'elzero', 'male', '2004-12-28', 'osamaelzero@gmail.com', '0', 'avatar.webp', '2019-10-16 12:33:56', 1),
(72970051, '72970059201916511013', 'ahmad', 'ahmad', 'ahmad', 'male', '2004-12-14', 'refatalsakka@gmail.comahmad', '0', 'avatar.webp', '2019-10-16 11:59:51', 1),
(81700017, '81710012201916171014', 'rerere', 'refat', 'refataa', 'female', '2004-12-27', 'refatdsfdfdssdlsakka@gmail.com', '0', 'avatar.webp', '2019-10-16 12:12:17', 0),
(83120010, '83120039201916101013', 'addddda', 'amin', 'aminaminamin', 'male', '2004-12-20', 'refatalsadkka@gmail.comamin', '0', 'avatar.webp', '2019-10-16 11:39:10', 0),
(83610019, '83610024201917191012', 'nazeer', 'nazeer', 'nazeer', 'male', '2004-12-27', 'nazeer@gmail.comnazeer', '0', 'avatar.webp', '2019-10-17 10:24:19', 1),
(95490016, '95500010201918161009', 'muhanad', 'muhanad', 'muhanad', 'male', '2004-12-28', 'muhanad@gmail.com', '0', 'avatar.webp', '2019-10-18 07:10:16', 1);

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
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
