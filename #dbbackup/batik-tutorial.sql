-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2017 at 08:13 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `batik-tutorial`
--

-- --------------------------------------------------------

--
-- Table structure for table `crud_cat_model`
--

CREATE TABLE IF NOT EXISTS `crud_cat_model` (
  `name` varchar(255) DEFAULT NULL,
  `order_` int(11) DEFAULT NULL,
`id` int(11) NOT NULL,
  `globalId` varchar(500) DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `clientCreated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `clientModified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `crud_cat_model`
--

INSERT INTO `crud_cat_model` (`name`, `order_`, `id`, `globalId`, `modified`, `created`, `clientCreated`, `clientModified`) VALUES
('hgbj', NULL, 1, NULL, '0000-00-00 00:00:00', '2017-04-11 16:29:17', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `crud_cat_model`
--
ALTER TABLE `crud_cat_model`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `crud_cat_model`
--
ALTER TABLE `crud_cat_model`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
