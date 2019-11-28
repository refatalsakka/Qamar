-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 28, 2019 at 08:40 AM
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
  `id` int(11) NOT NULL,
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
(19, 56550005, 0, NULL, NULL),
(20, 6380034, 0, NULL, NULL),
(21, 26400059, 0, NULL, NULL),
(22, 83610019, 0, NULL, NULL),
(23, 32120018, 0, NULL, NULL),
(24, 22150052, 0, NULL, NULL),
(25, 58590052, 0, NULL, NULL),
(26, 46510013, 1, NULL, NULL),
(28, 51660058, 0, NULL, NULL),
(30, 19910038, 0, NULL, NULL),
(32, 76490003, 0, NULL, NULL),
(35, 382241, 0, NULL, NULL),
(36, 3453423, 1, '2019-10-24 20:00:00', '2019-10-23 20:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
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
(7, 60480014, 'mauritania', NULL, NULL, 'nouakchott', '27472', NULL, ''),
(8, 83120010, 'jordan', '', NULL, 'amman', '27472', NULL, ''),
(9, 4360010, 'sudan', '', NULL, 'khartoum', NULL, NULL, ''),
(10, 33730027, 'iraq', '', NULL, 'baghdad', NULL, NULL, ''),
(11, 72970051, 'libya', NULL, NULL, 'tripoli', NULL, NULL, NULL),
(12, 81700017, 'bahrain', NULL, 27473, 'manama', NULL, NULL, NULL),
(13, 66950056, 'egypt', NULL, NULL, 'alexandria', NULL, NULL, NULL),
(14, 32590052, 'yemen', NULL, NULL, 'sana\'a', NULL, NULL, NULL),
(15, 3980002, 'oman', NULL, NULL, 'muscat', NULL, NULL, NULL),
(18, 56550005, 'emirates', NULL, NULL, 'abu dhabi', NULL, NULL, NULL),
(19, 6380034, 'morocco', NULL, NULL, 'rabat', NULL, NULL, NULL),
(20, 26400059, 'lebanon', NULL, NULL, 'beirut', NULL, NULL, NULL),
(21, 83610019, 'kuwait', NULL, NULL, 'kuwait', NULL, NULL, NULL),
(22, 32120018, 'tunisia', 'tunisia', NULL, 'tunisia', NULL, NULL, NULL),
(23, 3453423, 'syria', NULL, NULL, 'damascus', NULL, NULL, NULL),
(24, 22150052, 'comoros', NULL, NULL, 'moroni', NULL, NULL, NULL),
(25, 58590052, 'djibouti', NULL, NULL, 'djibouti', NULL, NULL, NULL),
(26, 46510013, 'saudi arabia', NULL, NULL, 'riyadh', 'wernerstr.', NULL, NULL),
(28, 51660058, 'algeria', NULL, NULL, 'manama', NULL, NULL, NULL),
(30, 19910038, 'palestine', NULL, NULL, 'jerusalem', NULL, NULL, NULL),
(32, 76490003, 'somalia', NULL, 27472, 'mogadishu', NULL, NULL, NULL),
(35, 382241, 'qatar', NULL, 26497, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `users_group_id` tinyint(1) NOT NULL DEFAULT '2',
  `code` varchar(100) NOT NULL,
  `username` varchar(20) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `gender` varchar(6) NOT NULL,
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

INSERT INTO `users` (`id`, `users_group_id`, `code`, `username`, `fname`, `lname`, `gender`, `birthday`, `email`, `password`, `img`, `registration`, `status`) VALUES
(382241, 2, '93350046201922221012', 'yazan_', 'yazan', 'yazan', 'male', '2000-12-28', 'yazan@gmail.com', '$2y$10$RZo9ppJXMCQTN.xxI3kbpeOgFQV.tQnMALPX/IN8jKg5S9NRQweEG', 'avatar.webp', '2019-10-22 08:46:22', 1),
(3453423, 1, '34536543543', 'refatalsakka', 'refat', 'alsakka', 'male', '1998-01-01', 'refatalsakka@gmail.com', '$2y$10$RZo9ppJXMCQTN.xxI3kbpeOgFQV.tQnMALPX/IN8jKg5S9NRQweEG', 'avatar.webp', '2019-10-16 00:11:10', 2),
(3980002, 2, '03980043201917021010', 'nabil', 'nabil', 'nabil', 'male', '2004-12-28', 'nabil@gmail.com', '0', 'avatar.webp', '2019-10-17 06:43:02', 1),
(4360010, 2, '04360043201916101013', 'mahomoud', 'mahomoud', 'mahomoud', 'male', '2004-12-20', 'mahomoud@gmail.com', '0', 'avatar.webp', '2019-10-16 09:43:10', 1),
(6380034, 2, '06380058201917341010', 'ranin', 'ranin', 'ranin', 'female', '2004-12-22', 'ranin@gmail.com', '0', 'avatar.webp', '2019-10-17 06:58:34', 0),
(19910038, 2, '19910057201918381010', 'amira', 'amira', 'amira', 'female', '2004-12-27', 'amira@gmail.com', '0', '1.webp', '2019-10-18 06:57:38', 2),
(22150052, 2, '22150039201918521007', 'nasser', 'nasser', 'nasser', 'male', '2004-12-14', 'nasser@gmail.com', '0', 'avatar.webp', '2019-10-18 03:39:52', 1),
(26400059, 2, '26400058201917591010', 'yousef', 'yousef', 'yousef', 'male', '2004-12-08', 'yousef@gmail.com', '0', 'avatar.webp', '2019-10-17 06:58:59', 1),
(32120018, 2, '32120018201917181014', 'marwan', 'marwan', 'marwan', 'male', '1993-08-07', 'marwan@gmail.com', '0', 'avatar.webp', '2019-10-17 10:18:18', 2),
(32590052, 2, '32600040201917521010', 'omar98', 'omar', 'omar', 'male', '2004-12-22', 'omar@gmail.com', '0', 'avatar.webp', '2019-10-17 06:40:52', 0),
(33730027, 2, '33730043201916271013', 'fdsfsfgs', 'nazeer', 'nazeer', 'male', '2004-12-27', 'refatalsadsgsgkka@gmail.com', '0', 'avatar.webp', '2019-10-16 09:43:27', 1),
(46510013, 2, '46510008201918131009', 'basma', 'basma', 'basma', 'female', '2004-12-28', 'basma@gmail.com', '0', 'avatar.webp', '2019-10-18 05:08:13', 1),
(51660058, 2, '51660010201918581009', 'sarah', 'sarah', 'sarah', 'female', '2004-12-28', 'sarah@gmail.com', '0', 'avatar.webp', '2019-10-18 05:10:58', 2),
(56550005, 2, '56560055201917051010', 'zain25', 'zain', 'zain', 'male', '2004-12-23', 'zain@gmail.com', '0', 'avatar.webp', '2019-10-17 06:55:05', 1),
(58590052, 2, '58600027201918521008', 'nesreen', 'nesreen', 'nesreen', 'female', '1997-06-20', 'nesreen@gmail.com', '0', 'avatar.webp', '2019-10-18 04:27:52', 1),
(60480014, 2, '60480038201916141013', 'amin95', 'amin', 'amin', 'male', '2004-12-19', 'amin@gmail.com', '0', 'avatar.webp', '2019-10-16 09:38:14', 1),
(66950056, 2, '66960033201916561014', 'osama', 'osama', 'elzero', 'male', '2004-12-28', 'osamaelzero@gmail.com', '0', 'avatar.webp', '2019-10-16 10:33:56', 1),
(72970051, 2, '72970059201916511013', 'ahmad', 'ahmad', 'ahmad', 'male', '2004-12-14', 'refatalsakka@gmail.comahmad', '0', 'avatar.webp', '2019-10-16 09:59:51', 1),
(76490003, 2, '76490046201922031011', 'mustafa', 'mustafa', 'mustafa', 'male', '2004-12-13', 'refatalsakka@gmail.commustafa', '0', 'avatar.webp', '2019-10-22 07:46:03', 1),
(81700017, 2, '81710012201916171014', 'rania', 'rania', 'rania', 'female', '2004-12-27', 'rania@gmail.com', '0', 'avatar.webp', '2019-10-16 10:12:17', 0),
(83120010, 2, '83120039201916101013', 'anas12', 'anas', 'anas', 'male', '2004-12-20', 'anas@gmail.com', '0', 'avatar.webp', '2019-10-16 09:39:10', 0),
(83610019, 2, '83610024201917191012', 'nazeer', 'nazeer', 'nazeer', 'male', '2004-12-27', 'nazeer@gmail.comnazeer', '0', 'avatar.webp', '2019-10-17 08:24:19', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `id` tinyint(1) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `name`) VALUES
(1, 'super user'),
(2, 'users');

-- --------------------------------------------------------

--
-- Table structure for table `users_group_permissions`
--

CREATE TABLE `users_group_permissions` (
  `id` int(11) NOT NULL,
  `users_group_id` tinyint(4) NOT NULL,
  `page` json NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `group_id` (`users_group_id`);

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_group_permissions`
--
ALTER TABLE `users_group_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_groups_id` (`users_group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users_group_permissions`
--
ALTER TABLE `users_group_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `group_id` FOREIGN KEY (`users_group_id`) REFERENCES `users_groups` (`id`);

--
-- Constraints for table `users_group_permissions`
--
ALTER TABLE `users_group_permissions`
  ADD CONSTRAINT `user_groups_id` FOREIGN KEY (`users_group_id`) REFERENCES `users_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
