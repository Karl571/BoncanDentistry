-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 09, 2025 at 05:17 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boncan_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_tbl`
--

DROP TABLE IF EXISTS `access_tbl`;
CREATE TABLE IF NOT EXISTS `access_tbl` (
  `access_id` int NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `module_id` int NOT NULL,
  PRIMARY KEY (`access_id`),
  UNIQUE KEY `unique_role_module` (`role_id`,`module_id`),
  KEY `module_id` (`module_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 ;
--
-- Dumping data for table `access_tbl`
--

INSERT INTO `access_tbl` (`access_id`, `role_id`, `module_id`) VALUES
(1, 1, 1),
(11, 1, 11),
(10, 1, 10),
(9, 1, 9),
(8, 1, 8),
(7, 1, 7),
(6, 1, 6),
(5, 1, 5),
(4, 1, 4),
(3, 1, 3),
(2, 1, 2),
(12, 1, 12),
(13, 14, 1),
(14, 14, 2),
(15, 15, 2),
(16, 15, 3),
(17, 15, 4),
(18, 16, 1),
(19, 17, 1),
(20, 18, 1),
(21, 19, 3),
(22, 19, 4),
(23, 1, 13),
(24, 20, 11),
(25, 20, 13);

-- --------------------------------------------------------

--
-- Table structure for table `account_tbl`
--

DROP TABLE IF EXISTS `account_tbl`;
CREATE TABLE IF NOT EXISTS `account_tbl` (
  `account_id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `surname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `username` (`username`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account_tbl`
--

INSERT INTO `account_tbl` (`account_id`, `firstname`, `middlename`, `surname`, `username`, `password`, `role_id`, `status`) VALUES
(1, 'JM', 'G', 'Caurel', 'jmcaurel', '1234', 1, 'Active'),
(12, 'sada', 'asdas', 'ada', 'asda', 'asdasda', 1, 'Archived'),
(11, 'Sample applied Role', 'App', 'nasdh', 'user1', '123456', 14, 'Active'),
(10, 'Jay', 'Nucum', 'Galang', 'ryjkrenzo', 'jjhay7314', 1, 'Active'),
(13, 'clurt', 'a', 'jjasdgka', 'clurt', '123456', 19, 'Active'),
(14, 'Boncan', 'asdf', 'fdsf', 'boncan', '123456', 20, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `module_tbl`
--

DROP TABLE IF EXISTS `module_tbl`;
CREATE TABLE IF NOT EXISTS `module_tbl` (
  `module_id` int NOT NULL AUTO_INCREMENT,
  `module` varchar(100) NOT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `module` (`module`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `module_tbl`
--

INSERT INTO `module_tbl` (`module_id`, `module`) VALUES
(1, 'Dashboard'),
(2, 'Account Maintenance'),
(3, 'Product Maintenance'),
(4, 'Product Stocking'),
(5, 'Service Maintenance'),
(6, 'Reports'),
(7, 'Request Service'),
(9, 'User Profile'),
(10, 'System Settings'),
(11, 'Transaction Logs'),
(12, 'Audit Trail'),
(13, 'Doctors Module');

-- --------------------------------------------------------

--
-- Table structure for table `product_brand_tbl`
--

DROP TABLE IF EXISTS `product_brand_tbl`;
CREATE TABLE IF NOT EXISTS `product_brand_tbl` (
  `brand_id` int NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(100) NOT NULL,
  PRIMARY KEY (`brand_id`),
  UNIQUE KEY `brand_name` (`brand_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Table structure for table `product_category_tbl`
--

DROP TABLE IF EXISTS `product_category_tbl`;
CREATE TABLE IF NOT EXISTS `product_category_tbl` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Table structure for table `product_supplier_tbl`
--

DROP TABLE IF EXISTS `product_supplier_tbl`;
CREATE TABLE IF NOT EXISTS `product_supplier_tbl` (
  `supplier_id` int NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(100) NOT NULL,
  PRIMARY KEY (`supplier_id`),
  UNIQUE KEY `supplier_name` (`supplier_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Table structure for table `product_tbl`
--

DROP TABLE IF EXISTS `product_tbl`;
CREATE TABLE IF NOT EXISTS `product_tbl` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int NOT NULL,
  `brand_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `product_status` varchar(20) NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`product_id`),
  KEY `category_id` (`category_id`),
  KEY `brand_id` (`brand_id`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Table structure for table `role_tbl`
--

DROP TABLE IF EXISTS `role_tbl`;
CREATE TABLE IF NOT EXISTS `role_tbl` (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `role_tbl`
--

INSERT INTO `role_tbl` (`role_id`, `role_name`, `status`) VALUES
(1, 'Admin', ''),
(18, 'wews', 'active'),
(17, 'wow', 'active'),
(16, 'ngi', 'active'),
(15, 'Sample role2', 'active'),
(14, 'Sample Role', 'active'),
(19, 'Inventory Crew', 'active'),
(20, 'Doctors Account', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `service_category_tbl`
--

DROP TABLE IF EXISTS `service_category_tbl`;
CREATE TABLE IF NOT EXISTS `service_category_tbl` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 ;

-- --------------------------------------------------------

--
-- Table structure for table `service_tbl`
--

DROP TABLE IF EXISTS `service_tbl`;
CREATE TABLE IF NOT EXISTS `service_tbl` (
  `service_id` int NOT NULL AUTO_INCREMENT,
  `service_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int NOT NULL,
  `service_status` varchar(20) NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`service_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
