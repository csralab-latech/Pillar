-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 26, 2017 at 06:29 PM
-- Server version: 5.5.52-0+deb8u1
-- PHP Version: 5.6.27-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `homeautomation`
--

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE IF NOT EXISTS `devices` (
`id` int(11) NOT NULL,
  `entity_id` varchar(200) NOT NULL,
  `friendly_name` varchar(200) NOT NULL,
  `room` varchar(100) NOT NULL,
  `domain` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=354 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `entity_id`, `friendly_name`, `room`, `domain`) VALUES
(350, 'lock.front_door_lock', 'Front Door Lock', 'Hall', 'lock'),
(351, 'light.kitchen_ceiling_lights', 'Kitchen Ceiling Lights', 'Kitchen', 'light'),
(352, 'light.kitchen_hanging_lights', 'Kitchen Hanging Lights', 'Kitchen', 'light'),
(353, 'switch.kitchen_sink_plug', 'Kitchen Sink Plug', 'Kitchen', 'switch');

-- --------------------------------------------------------

--
-- Table structure for table `light`
--

CREATE TABLE IF NOT EXISTS `light` (
`id` int(11) NOT NULL,
  `devices_id` int(11) NOT NULL,
  `state` varchar(10) NOT NULL,
  `brightness` int(11) NOT NULL,
  `color` varchar(50) NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `light`
--

INSERT INTO `light` (`id`, `devices_id`, `state`, `brightness`, `color`, `time_stamp`) VALUES
(1, 1, 'on', 255, '', '2016-11-03 18:47:18'),
(2, 1, 'off', 0, '', '2016-11-03 21:48:18'),
(3, 1, 'on', 255, '', '2016-11-05 19:20:04'),
(4, 1, 'on', 150, '', '2016-11-06 01:20:04'),
(5, 1, 'off', 0, '', '2016-11-06 03:21:35'),
(6, 1, 'on', 255, '', '2016-11-06 15:21:35');

-- --------------------------------------------------------

--
-- Table structure for table `lock`
--

CREATE TABLE IF NOT EXISTS `lock` (
`id` int(11) NOT NULL,
  `devices_id` int(11) NOT NULL,
  `state` varchar(10) NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `switch`
--

CREATE TABLE IF NOT EXISTS `switch` (
`id` int(11) NOT NULL,
  `devices_id` int(11) NOT NULL,
  `state` varchar(10) NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `light`
--
ALTER TABLE `light`
 ADD PRIMARY KEY (`id`), ADD KEY `devices_id` (`devices_id`);

--
-- Indexes for table `lock`
--
ALTER TABLE `lock`
 ADD PRIMARY KEY (`id`), ADD KEY `devices_id` (`devices_id`);

--
-- Indexes for table `switch`
--
ALTER TABLE `switch`
 ADD PRIMARY KEY (`id`), ADD KEY `devices_id` (`devices_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=354;
--
-- AUTO_INCREMENT for table `light`
--
ALTER TABLE `light`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `lock`
--
ALTER TABLE `lock`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `switch`
--
ALTER TABLE `switch`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
