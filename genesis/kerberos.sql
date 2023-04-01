-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2022 at 11:44 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kerberos`
--

-- --------------------------------------------------------

--
-- Table structure for table `domainpathmaper`
--

CREATE TABLE `domainpathmaper` (
  `domain` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `domainpathmaper`
--

INSERT INTO `domainpathmaper` (`domain`, `url`) VALUES
('Edmingle', 'http://localhost/Saarthi-main/saarthi/');

-- --------------------------------------------------------

--
-- Table structure for table `krbtgt`
--

CREATE TABLE `krbtgt` (
  `domain_id` int(100) NOT NULL,
  `domain_name` varchar(100) NOT NULL,
  `password` varchar(500) NOT NULL DEFAULT '12345678912345678912345678912345'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `krbtgt`
--

INSERT INTO `krbtgt` (`domain_id`, `domain_name`, `password`) VALUES
(121, 'edmingle', '12345678912345678912345678912345'),
(321, 'boa', '12345678912345678912345678912345');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `realm` varchar(30) NOT NULL,
  `domain` varchar(30) NOT NULL,
  `domain_id` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `realm`, `domain`, `domain_id`) VALUES
(1, 'Sagar', '123', 'admin', 'Edmingle', 121),
(2, 'Robin', 'diablo', 'admin', 'Edmingle', 121);

-- --------------------------------------------------------

--
-- Table structure for table `user_auth_tickets`
--

CREATE TABLE `user_auth_tickets` (
  `uid` int(100) NOT NULL,
  `tgt` varchar(150) NOT NULL,
  `sessionkey` varchar(150) NOT NULL,
  `timecreated` datetime NOT NULL,
  `iv` blob DEFAULT NULL,
  `tgt_raw` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_auth_tickets`
--

INSERT INTO `user_auth_tickets` (`uid`, `tgt`, `sessionkey`, `timecreated`, `iv`, `tgt_raw`) VALUES
(1, '78fe9caf5f5b9bb04a80f74f4737ccf3b8db13d337f3ba9ad23b57c95c7a8c355229248c26dd0f8895d303a149424a8893964053941d5fa03e6f4195ee177037', 'b2fd4b2e11a1a7e7bf147698e310771de243a8934ad44595c36a30ac1e15054106110780cc62dc69db1769514cc1a45019e3c8e71c519c0e63f8787bad6977bd', '0000-00-00 00:00:00', 0xbbad5a140082f2f8d411a73d65e7aa3b, '24fb847f41b0fc5c6c44818ff475f20b6c4a0e9f76b3ce0c');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `krbtgt`
--
ALTER TABLE `krbtgt`
  ADD PRIMARY KEY (`domain_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `krbtgt`
--
ALTER TABLE `krbtgt`
  MODIFY `domain_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=322;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
