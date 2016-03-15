-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Mar 15, 2016 at 12:59 PM
-- Server version: 5.5.38
-- PHP Version: 5.5.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `jbguitar`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer_type`
--

CREATE TABLE `customer_type` (
`customer_type_id` int(11) NOT NULL,
  `customer_type` varchar(400) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer_type`
--

INSERT INTO `customer_type` (`customer_type_id`, `customer_type`) VALUES
(1, 'customer_unit'),
(2, 'dealers_unit'),
(3, 'branch_unit');

-- --------------------------------------------------------

--
-- Table structure for table `jb_branch`
--

CREATE TABLE `jb_branch` (
`branch_id` int(11) NOT NULL,
  `branch_name` varchar(300) NOT NULL,
  `contactperson` varchar(300) NOT NULL,
  `email` varchar(300) NOT NULL,
  `address` varchar(300) NOT NULL,
  `number` varchar(300) NOT NULL,
  `customer_type` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jb_branch`
--

INSERT INTO `jb_branch` (`branch_id`, `branch_name`, `contactperson`, `email`, `address`, `number`, `customer_type`, `isdeleted`, `created_at`, `updated_at`) VALUES
(1, 'Sta Rosa', 'Argie C Navora', 'branchemail@gmail.com', 'Sta Rosa', '12345678909', 0, 0, '2016-02-26 04:50:02', '2016-02-26 07:21:52'),
(4, 'Sm Megamall', 'Arthur C Pascual', 'megamall@gmail.com', 'Manila', '09177777777', 0, 0, '2016-03-07 11:15:52', '2016-03-07 03:15:52');

-- --------------------------------------------------------

--
-- Table structure for table `jb_brands`
--

CREATE TABLE `jb_brands` (
`brandid` int(11) NOT NULL,
  `brandname` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jb_brands`
--

INSERT INTO `jb_brands` (`brandid`, `brandname`, `created_at`, `updated_at`) VALUES
(1, 'A &AMP; H', '2016-02-26 04:55:21', '2016-11-03 09:20:17'),
(2, 'NUMARK', '2016-02-25 05:53:38', '2016-02-26 04:54:44'),
(3, 'ALTO', '2016-02-25 05:53:49', '2016-02-26 04:54:44'),
(4, 'CHINA', '2016-02-25 05:56:07', '2016-02-26 04:54:44'),
(5, 'QSC', '2016-02-25 05:56:14', '2016-02-26 04:54:44'),
(6, 'MARTIN AUDIO', '2016-02-25 05:56:24', '2016-02-26 04:54:44'),
(7, 'N/M', '2016-02-25 05:56:31', '2016-02-26 04:54:44'),
(8, 'ORANGE', '2016-02-25 05:56:41', '2016-02-26 04:54:44'),
(9, 'CARLSBRO', '2016-02-26 04:47:29', '2016-02-26 04:54:44'),
(10, 'TEST', '2016-03-03 09:20:05', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `jb_cost`
--

CREATE TABLE `jb_cost` (
`cost_id` int(11) NOT NULL,
  `jobid` varchar(23) NOT NULL,
  `totalpartscost` varchar(400) NOT NULL,
  `service_charges` varchar(500) NOT NULL,
  `total_charges` varchar(400) NOT NULL,
  `less_deposit` varchar(500) NOT NULL,
  `less_discount` varchar(10) NOT NULL,
  `balance` varchar(500) NOT NULL,
  `computed_by` varchar(500) NOT NULL,
  `accepted_by` varchar(500) NOT NULL,
  `ispaid` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jb_cost`
--

INSERT INTO `jb_cost` (`cost_id`, `jobid`, `totalpartscost`, `service_charges`, `total_charges`, `less_deposit`, `less_discount`, `balance`, `computed_by`, `accepted_by`, `ispaid`, `created_at`, `updated_at`) VALUES
(1, '40489363', '0', '0', '0.00', '0.00', '0.00', '0', 'Mc Clynrey A Arboleda', 'Argie C Navora', 1, '2016-03-04 17:54:07', '2016-03-04 09:55:01'),
(2, '95651090', '3200', '800', '0.00', '0.00', '0.00', '4000', 'Mc Clynrey A Arboleda', 'Argie C Navora', 1, '2016-03-04 18:22:11', '2016-03-04 10:22:35'),
(3, '24732036', '500', '800', '0.00', '0.00', '0.00', '1300', 'Mark S Pagsisihan', 'Cyrus S Pagsisihan', 1, '2016-03-06 10:00:06', '2016-03-06 02:00:34'),
(4, '52435307', '500', '800', '0.00', '0.00', '0.00', '1300', 'Mark S Pagsisihan', 'Cyrus S Pagsisihan', 1, '2016-03-06 10:19:27', '2016-03-06 02:20:00'),
(5, '75735846', '0', '0', '0.00', '0.00', '0.00', '0', 'Mark S Pagsisihan', 'Cyrus S Pagsisihan', 1, '2016-03-06 10:28:17', '2016-03-06 02:30:46'),
(6, '82595736', '3505.75', '800', '0.00', '0.00', '0.00', '4305.75', 'Mc Clynrey A Arboleda', 'Argie C Navora', 1, '2016-03-11 09:52:54', '2016-03-11 09:53:54'),
(7, '50250564', '3505.75', '800', '0.00', '0.00', '0.00', '4305.75', 'Mc Clynrey A Arboleda', '', 0, '2016-03-15 12:15:29', '0000-00-00 00:00:00'),
(8, '54186920', '500', '800', '0.00', '0.00', '0.00', '1300', 'Mc Clynrey A Arboleda', 'Argie C Navora', 1, '2016-03-15 12:19:39', '2016-03-15 12:26:10'),
(9, '34831976', '500', '800', '0.00', '0.00', '0.00', '1300', 'Mc Clynrey A Arboleda', 'Argie C Navora', 1, '2016-03-15 01:13:23', '2016-03-15 01:19:55'),
(10, '53645030', '500', '800', '0.00', '0.00', '0.00', '1300', 'Mc Clynrey A Arboleda', 'Argie C Navora', 1, '2016-03-15 01:49:51', '2016-03-15 01:50:19'),
(11, '01470820', '1000', '800', '0.00', '0.00', '0.00', '1800', 'Mc Clynrey A Arboleda', 'Argie C Navora', 1, '2016-03-15 02:03:29', '2016-03-15 02:04:01'),
(12, '70912625', '3505.75', '800', '0.00', '0.00', '0.00', '4305.75', 'Mc Clynrey A Arboleda', 'Argie C Navora', 1, '2016-03-15 08:47:59', '2016-03-15 08:48:21'),
(13, '72195206', '500', '800', '0.00', '0.00', '0.00', '1300', 'Mc Clynrey A Arboleda', 'Argie C Navora', 1, '2016-03-15 10:25:38', '2016-03-15 10:26:19');

-- --------------------------------------------------------

--
-- Table structure for table `jb_customer`
--

CREATE TABLE `jb_customer` (
`customerid` int(11) NOT NULL,
  `branchid` varchar(200) NOT NULL,
  `customer_type_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `number` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jb_customer`
--

INSERT INTO `jb_customer` (`customerid`, `branchid`, `customer_type_id`, `name`, `email`, `address`, `number`, `status`, `isdeleted`, `created_at`, `updated_at`) VALUES
(1, '1', 1, 'Claribel Navora', 'claribel.navora@gmail.com', 'Santa Rosa', '09177777777', '', 0, '2016-01-23 09:17:09', '0000-00-00 00:00:00'),
(2, '1', 1, 'Rodnee Lozada', 'rodnee@yahoo.com', 'Sta Rosa Laguna', '09177777777', '', 0, '2016-02-23 08:09:41', '2016-02-27 10:31:22'),
(3, '1', 1, 'Erwin Pascual', 'erwin@gmail.com', 'Sta Rosa Laguna', '09177777777', '', 0, '2016-02-27 15:27:59', '2016-02-27 10:31:26'),
(4, '1', 1, 'Argie Navora', 'navora08_27@yahoo.com', 'Sta Rosa', '09177777777', '', 0, '2016-03-06 00:00:34', '2016-03-06 01:45:25');

-- --------------------------------------------------------

--
-- Table structure for table `jb_diagnosis`
--

CREATE TABLE `jb_diagnosis` (
`id` int(11) NOT NULL,
  `diagnosis` varchar(400) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jb_diagnosis`
--

INSERT INTO `jb_diagnosis` (`id`, `diagnosis`, `created_at`, `updated_at`) VALUES
(1, 'No Power', '2016-02-25 17:19:11', '2016-02-26 11:03:58'),
(3, 'No Display', '2016-02-25 17:19:11', '2016-02-25 17:19:11'),
(4, 'Testing testing', '2016-02-25 17:19:11', '2016-02-25 17:19:11'),
(5, 'No Design', '2016-03-11 08:41:35', '2016-03-11 00:41:35');

-- --------------------------------------------------------

--
-- Table structure for table `jb_email`
--

CREATE TABLE `jb_email` (
`email_id` int(11) NOT NULL,
  `feedback` varchar(500) NOT NULL,
  `admin` varchar(500) NOT NULL,
  `isbranch` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jb_email`
--

INSERT INTO `jb_email` (`email_id`, `feedback`, `admin`, `isbranch`, `branchid`, `created_at`, `updated_at`) VALUES
(2, 'mcclynrey@gmail.com', 'mcclynrey@gmail.com', 1, 2, '2015-10-01 14:29:37', '2016-02-26 05:03:29'),
(5, 'mcclynrey@gmail.com', 'cloydreveras@gmail.com', 0, -1, '2015-10-01 14:35:20', '2016-02-26 05:03:29'),
(6, 'mcclynrey@gmail.coms', 'mcclynrey@gmail.com', 1, 1, '2016-02-23 06:39:18', '2016-02-26 05:03:29');

-- --------------------------------------------------------

--
-- Table structure for table `jb_history`
--

CREATE TABLE `jb_history` (
`id` int(11) NOT NULL,
  `description` varchar(300) NOT NULL,
  `branch` varchar(300) NOT NULL,
  `name` varchar(300) NOT NULL,
  `branchid` int(11) NOT NULL,
  `isbranch` int(11) NOT NULL,
  `jobnumber` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jb_history`
--

INSERT INTO `jb_history` (`id`, `description`, `branch`, `name`, `branchid`, `isbranch`, `jobnumber`, `created_at`, `updated_at`) VALUES
(1, 'Job Order Created', 'Sta Rosa', 'Chiee', 1, 1, 53645030, '2016-03-15 01:49:11', '0000-00-00 00:00:00'),
(2, 'Set Job Order Delivery Date To Service Department', 'Sta Rosa', 'Chiee', 1, 1, 53645030, '2016-03-15 01:49:22', '0000-00-00 00:00:00'),
(3, 'Job Order Arrived Service Department', 'Main Office', 'Mc Clynrey', 0, 1, 53645030, '2016-03-15 01:49:32', '0000-00-00 00:00:00'),
(4, 'Statement of Account Generated', 'Main Office', 'Mc Clynrey', 0, 1, 53645030, '2016-03-15 01:49:50', '0000-00-00 00:00:00'),
(5, 'Customer Approved Job Order', 'Sta Rosa', 'Chiee', 1, 1, 53645030, '2016-03-15 01:50:18', '0000-00-00 00:00:00'),
(6, 'Job Order Ongoing Repair', 'Main Office', 'Mc Clynrey', 0, 1, 53645030, '2016-03-15 01:50:51', '0000-00-00 00:00:00'),
(7, 'Repair Done', 'Main Office', 'Mc Clynrey', 0, 1, 53645030, '2016-03-15 01:51:16', '0000-00-00 00:00:00'),
(8, 'Set Job Order Delivery Date To Branch', 'Main Office', 'Mc Clynrey', 0, 1, 53645030, '2016-03-15 01:51:43', '0000-00-00 00:00:00'),
(9, 'Job Order Arrived', 'Sta Rosa', 'Chiee', 1, 1, 53645030, '2016-03-15 01:52:18', '0000-00-00 00:00:00'),
(10, 'Item Claimed', 'Sta Rosa', 'Chiee', 1, 1, 53645030, '2016-03-15 01:52:53', '0000-00-00 00:00:00'),
(11, 'Job Order Created', 'Sta Rosa', 'Chiee', 1, 1, 1470820, '2016-03-15 02:02:45', '0000-00-00 00:00:00'),
(12, 'Set Job Order Delivery Date To Service Department', 'Sta Rosa', 'Chiee', 1, 1, 1470820, '2016-03-15 02:02:56', '0000-00-00 00:00:00'),
(13, 'Job Order Arrived Service Department', 'Main Office', 'Mc Clynrey', 0, 1, 1470820, '2016-03-15 02:03:06', '0000-00-00 00:00:00'),
(14, 'Statement of Account Generated', 'Main Office', 'Mc Clynrey', 0, 1, 1470820, '2016-03-15 02:03:28', '0000-00-00 00:00:00'),
(15, 'Customer Approved Job Order', 'Sta Rosa', 'Chiee', 1, 1, 1470820, '2016-03-15 02:04:00', '0000-00-00 00:00:00'),
(16, 'Job Order Ongoing Repair', 'Main Office', 'Mc Clynrey', 0, 1, 1470820, '2016-03-15 02:04:46', '0000-00-00 00:00:00'),
(17, 'Cant Repair Job Order', 'Main Office', 'Mc Clynrey', 0, 1, 1470820, '2016-03-15 02:06:40', '0000-00-00 00:00:00'),
(18, 'Set Job Order Delivery Date To Branch', 'Main Office', 'Mc Clynrey', 0, 1, 1470820, '2016-03-15 02:07:11', '0000-00-00 00:00:00'),
(19, 'Job Order Arrived', 'Sta Rosa', 'Chiee', 1, 1, 1470820, '2016-03-15 02:07:19', '0000-00-00 00:00:00'),
(20, 'Item Claimed', 'Sta Rosa', 'Chiee', 1, 1, 1470820, '2016-03-15 02:07:41', '0000-00-00 00:00:00'),
(21, 'Job Order Created', 'Sta Rosa', 'Chiee', 1, 1, 70912625, '2016-03-15 08:47:00', '0000-00-00 00:00:00'),
(22, 'Set Job Order Delivery Date To Service Department', 'Sta Rosa', 'Chiee', 1, 1, 70912625, '2016-03-15 08:47:23', '0000-00-00 00:00:00'),
(23, 'Job Order Arrived Service Department', 'Main Office', 'Mc Clynrey', 0, 1, 70912625, '2016-03-15 08:47:31', '0000-00-00 00:00:00'),
(24, 'Statement of Account Generated', 'Main Office', 'Mc Clynrey', 0, 1, 70912625, '2016-03-15 08:47:59', '0000-00-00 00:00:00'),
(25, 'Customer Approved Job Order', 'Sta Rosa', 'Chiee', 1, 1, 70912625, '2016-03-15 08:48:21', '0000-00-00 00:00:00'),
(26, 'Job Order Ongoing Repair', 'Main Office', 'Mc Clynrey', 0, 1, 70912625, '2016-03-15 08:50:11', '0000-00-00 00:00:00'),
(27, 'Repair Done', 'Main Office', 'Mc Clynrey', 0, 1, 70912625, '2016-03-15 08:50:42', '0000-00-00 00:00:00'),
(28, 'Set Job Order Delivery Date To Branch', 'Main Office', 'Mc Clynrey', 0, 1, 70912625, '2016-03-15 08:51:59', '0000-00-00 00:00:00'),
(29, 'Job Order Arrived', 'Sta Rosa', 'Chiee', 1, 1, 70912625, '2016-03-15 08:53:34', '0000-00-00 00:00:00'),
(30, 'Job Order Created', 'Sta Rosa', 'Chiee', 1, 1, 72195206, '2016-03-15 10:24:52', '0000-00-00 00:00:00'),
(31, 'Set Job Order Delivery Date To Service Department', 'Sta Rosa', 'Chiee', 1, 1, 72195206, '2016-03-15 10:25:06', '0000-00-00 00:00:00'),
(32, 'Job Order Arrived Service Department', 'Main Office', 'Mc Clynrey', 0, 1, 72195206, '2016-03-15 10:25:15', '0000-00-00 00:00:00'),
(33, 'Statement of Account Generated', 'Main Office', 'Mc Clynrey', 0, 1, 72195206, '2016-03-15 10:25:36', '0000-00-00 00:00:00'),
(34, 'Customer Approved Job Order', 'Sta Rosa', 'Chiee', 1, 1, 72195206, '2016-03-15 10:26:15', '0000-00-00 00:00:00'),
(35, 'Job Order Ongoing Repair', 'Main Office', 'Mc Clynrey', 0, 1, 72195206, '2016-03-15 10:26:36', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `jb_joborder`
--

CREATE TABLE `jb_joborder` (
`id` int(11) NOT NULL,
  `jobid` varchar(200) NOT NULL,
  `soaid` varchar(200) NOT NULL,
  `customerid` varchar(200) NOT NULL,
  `branchid` varchar(200) NOT NULL,
  `catid` int(11) NOT NULL,
  `partsid` varchar(200) NOT NULL,
  `parts` varchar(400) NOT NULL,
  `technicianid` varchar(200) NOT NULL,
  `item` varchar(500) NOT NULL,
  `diagnosis` varchar(500) NOT NULL,
  `remarks` varchar(1000) NOT NULL,
  `jobclear` int(11) NOT NULL,
  `estimated_finish_date` date NOT NULL,
  `status_id` varchar(500) NOT NULL,
  `repair_status` varchar(250) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `isunder_warranty` int(11) NOT NULL,
  `payment_type` int(11) NOT NULL,
  `referenceno` varchar(500) NOT NULL,
  `servicefee` varchar(500) NOT NULL,
  `date_delivery` date NOT NULL,
  `done_date_delivery` date NOT NULL,
  `isdeleted` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jb_joborder`
--

INSERT INTO `jb_joborder` (`id`, `jobid`, `soaid`, `customerid`, `branchid`, `catid`, `partsid`, `parts`, `technicianid`, `item`, `diagnosis`, `remarks`, `jobclear`, `estimated_finish_date`, `status_id`, `repair_status`, `payment_id`, `isunder_warranty`, `payment_type`, `referenceno`, `servicefee`, `date_delivery`, `done_date_delivery`, `isdeleted`, `created_at`, `updated_at`) VALUES
(1, '53645030', '', '1', '1', 15, '6216056', '# 6216056-test Part (500*1)&lt;br&gt;', '2', 'Test Test', '3', 'Test', 0, '2016-03-18', '1', 'Claimed', 0, 1, 0, '123123', '0', '0000-00-00', '0000-00-00', 0, '2016-03-15 01:49:11', '2016-03-15 01:52:51'),
(2, '01470820', '', '3', '1', 15, '6216056', '# 6216056-test Part (500*2)&lt;br&gt;', '2', 'Test Test', '3', 'Test<br> -- cant repair<br>', 1, '2016-03-19', '1', 'Claimed', 0, 1, 0, '123213', '0', '0000-00-00', '0000-00-00', 0, '2016-03-15 02:02:45', '2016-03-15 02:07:39'),
(3, '70912625', '', '2', '1', 15, '24564078', '# 24564078-test Part 2 (3505.75*1)&lt;br&gt;', '2', 'Test Test', '4', 'Test', 0, '2016-03-19', '1', 'Ready for Claiming', 0, 1, 0, '213213', '0', '0000-00-00', '0000-00-00', 0, '2016-03-15 08:47:00', '2016-03-15 08:53:34'),
(4, '72195206', '', '3', '1', 15, '6216056', '# 6216056-test Part (500*1)&lt;br&gt;', '2', 'Test Test', '1', 'Test', 0, '2016-03-19', '1', 'Ongoing Repair', 0, 1, 0, '21312312', '0', '0000-00-00', '0000-00-00', 0, '2016-03-15 10:24:52', '2016-03-15 10:26:35');

-- --------------------------------------------------------

--
-- Table structure for table `jb_models`
--

CREATE TABLE `jb_models` (
`modelid` int(11) NOT NULL,
  `modelname` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `brandid` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `sub_catid` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jb_models`
--

INSERT INTO `jb_models` (`modelid`, `modelname`, `description`, `brandid`, `cat_id`, `sub_catid`, `created_at`, `updated_at`) VALUES
(1, 'AL3407', 'Jack Socket Unswitched For Wz3', 1, 14, 90, '2016-02-25 05:48:22', '2016-02-26 05:06:22'),
(2, 'AG 8377-2/004-274X', 'JO RACK COMMS', 2, 14, 89, '2016-01-23 09:03:07', '2016-02-26 05:06:22'),
(3, 'AG 8439-2/004-201X', 'GL RACK PSU', 3, 14, 88, '2016-01-23 08:59:08', '2016-02-26 05:06:22'),
(4, 'AG 8438-1/004/401X', 'GL RACK AUDIO PCB', 3, 13, 84, '2016-01-23 08:47:40', '2016-02-26 05:06:22'),
(5, 'G 8740', 'GL RACK 8X4 PSU', 4, 13, 85, '2016-01-23 08:46:37', '2016-02-26 05:06:22'),
(7, 'Test model', 'This Is Test Model Description', 9, 15, 96, '2016-02-25 02:16:29', '2016-02-27 06:18:25');

-- --------------------------------------------------------

--
-- Table structure for table `jb_part`
--

CREATE TABLE `jb_part` (
  `stocknumber` varchar(255) NOT NULL,
  `name` varchar(300) NOT NULL,
`id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `modelid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `quantityfree` int(11) NOT NULL,
  `cost` varchar(255) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  `date` date NOT NULL,
  `bacth_quantity` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jb_part`
--

INSERT INTO `jb_part` (`stocknumber`, `name`, `id`, `part_id`, `modelid`, `quantity`, `quantityfree`, `cost`, `isdeleted`, `date`, `bacth_quantity`, `created_at`, `updated_at`) VALUES
('ESPAH-1', 'Power cord', 1, 345345, 1, 199, 0, '200', 0, '2015-11-11', 200, '2016-01-14 18:45:37', '2016-02-27 09:55:00'),
('ESPAH-2', 'Normal Guitar String', 2, 346834, 1, 85, 0, '120', 0, '0000-00-00', 100, '2016-01-14 18:45:42', '2016-02-27 09:55:05'),
('ESPAH-3', 'Drum Sticks', 3, 9674564, 2, 482, 0, '310', 0, '2015-11-17', 500, '2016-01-14 18:45:49', '2016-02-27 09:55:11'),
('ESPAH-4', 'Guitar Bridges', 4, 456853, 2, 2, 0, '1200', 0, '0000-00-00', 5, '2016-01-14 18:45:55', '2016-02-28 01:30:59'),
('ESPAH-5', 'Guitar necks', 5, 73456345, 3, 119, 0, '4000', 0, '0000-00-00', 120, '2016-01-14 18:45:59', '2016-02-27 09:55:19'),
('ESPAH-6', 'Electric Guitar Pickgaurds', 6, 23454643, 3, 98, 0, '2000', 0, '2015-11-24', 100, '2016-01-14 18:46:03', '2016-02-27 09:55:25'),
('ESPAH-7', 'Guitar Pickups', 7, 348293, 4, 143, 0, '3400', 0, '0000-00-00', 150, '2016-01-23 10:51:59', '2016-02-27 09:55:40'),
('ESPAH-8', 'Drum Tom', 8, 348453, 4, 99, 0, '5000', 0, '2015-11-18', 100, '2016-01-18 04:31:09', '2016-02-27 09:55:46'),
('ESPAH-9', 'Rack toms', 9, 3452834, 5, 96, 1, '3200', 0, '0000-00-00', 100, '2016-02-21 06:10:21', '2016-03-06 02:30:46'),
('ESPAH-10', 'Drum Memory', 10, 326658, 5, 116, 1, '2500', 0, '0000-00-00', 119, '2016-01-23 07:08:39', '2016-03-06 02:30:46'),
('ESPAH-11', 'Piano Agraffee', 11, 5783567, 1, 4, 3, '5400', 0, '2015-11-13', 10, '2016-01-14 18:56:22', '2016-03-04 09:55:01'),
('ESPAH-12', 'Piano Belly', 12, 134565, 1, 197, 1, '7000', 0, '0000-00-00', 200, '2016-01-14 18:46:35', '2016-03-02 15:02:03'),
('ESPAH-13', 'Tset', 13, 34312136, 2, 1400, 0, '1200', 0, '2015-11-17', 1400, '2016-01-18 04:31:09', '2016-02-28 01:28:08'),
('ESPAH-6', 'Electric Guitar Pickgaurds', 14, 23454643, 3, 123, 0, '2000', 0, '2015-11-17', 130, '2016-01-16 05:57:08', '2016-02-27 09:56:21'),
('ESPAH-3', 'Drum Sticks', 15, 9674564, 2, 482, 0, '310', 0, '2015-11-17', 500, '2016-01-16 05:59:16', '2016-02-27 09:56:31'),
('ESPAH-3', 'Drum Sticks', 16, 9674564, 2, 482, 0, '310', 0, '2015-11-17', 500, '2016-01-16 05:59:21', '2016-02-27 09:56:34'),
('ESPAH-10', 'Drum Memory', 17, 326658, 5, 123, 0, '2500', 0, '2015-11-19', 123, '2016-01-16 04:50:32', NULL),
('ESPAH-233', 'Test Stocks', 23, 59486181, 1, 197, 3, '2500', 0, '2016-01-17', 200, '2016-01-23 05:49:05', '2016-03-04 09:55:01'),
('ESPAH-233', 'Test Stocks', 24, 59486181, 1, 150, 0, '2500', 0, '2016-01-17', 150, '2016-01-17 01:07:43', NULL),
('ESPAH-200', 'Test Part', 25, 6216056, 7, 0, 1, '500', 0, '2016-01-18', 1, '2016-01-23 10:38:15', '2016-01-23 10:36:17'),
('ESPAH-200', 'Test Part', 26, 6216056, 7, 0, 1, '500', 0, '2016-01-22', 2, '2016-01-23 11:35:00', '2016-01-23 11:35:00'),
('ESPAH-200', 'Test Part', 27, 6216056, 7, 489, 0, '500', 0, '2016-02-22', 500, '2016-02-22 12:47:05', '2016-03-15 02:26:19'),
('ESPAH-977', 'Test Part 2', 28, 24564078, 7, 189, 0, '3505.75', 0, '2016-02-26', 200, '2016-02-26 02:28:22', '2016-03-15 00:48:21'),
('ESPAH-922', 'Additional Test Part', 29, 80393482, 4, 97, 2, '50.25', 0, '2016-03-01', 100, '2016-03-01 00:02:42', '2016-03-04 10:22:35');

-- --------------------------------------------------------

--
-- Table structure for table `jb_partscat`
--

CREATE TABLE `jb_partscat` (
`cat_id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jb_partscat`
--

INSERT INTO `jb_partscat` (`cat_id`, `category`, `created_at`, `updated_at`) VALUES
(13, 'Test Category', '2015-12-23 07:51:45', '0000-00-00 00:00:00'),
(14, 'Test Category 2', '2016-01-23 08:13:05', '0000-00-00 00:00:00'),
(15, 'Guitar Items', '2016-02-27 14:17:17', '2016-02-27 06:17:17');

-- --------------------------------------------------------

--
-- Table structure for table `jb_partssubcat`
--

CREATE TABLE `jb_partssubcat` (
`subcat_id` int(11) NOT NULL,
  `subcategory` varchar(255) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `parts_free` varchar(255) NOT NULL,
  `diagnostic_free` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jb_partssubcat`
--

INSERT INTO `jb_partssubcat` (`subcat_id`, `subcategory`, `cat_id`, `parts_free`, `diagnostic_free`, `created_at`, `updated_at`) VALUES
(84, 'Sub Category 1 (Test Category)', 13, '8,8', '8,8', '2016-02-21 05:57:07', '2016-02-26 05:10:25'),
(85, 'Sub Category 2 (Test Category)', 13, '4,4', '4,4', '2016-02-21 05:57:07', '2016-02-26 05:10:25'),
(88, 'Sub Category 1 (Test Category 2)', 14, '210,7', '365,1', '2016-02-21 05:58:11', '2016-02-26 05:10:25'),
(89, 'Sub Category 2 (Test Category 2)', 14, '15,15', '300,10', '2016-02-21 05:58:11', '2016-02-26 05:10:25'),
(90, 'Sub Category 3 (Test Category 2)', 14, '300,10', '730,2', '2016-02-21 05:58:11', '2016-02-26 05:10:25'),
(96, 'Pickups', 15, '5,5', '5,5', '2016-02-27 14:17:17', '2016-02-27 06:17:17'),
(97, 'Woofer', 15, '300,10', '12,12', '2016-02-27 14:17:17', '2016-02-27 06:17:17'),
(98, 'Strings', 15, '150,5', '60,2', '2016-02-27 14:17:17', '2016-02-27 06:17:17');

-- --------------------------------------------------------

--
-- Table structure for table `jb_payment`
--

CREATE TABLE `jb_payment` (
`payment_id` int(11) NOT NULL,
  `joborder_id` varchar(400) NOT NULL,
  `payment_type` varchar(400) NOT NULL,
  `advanced_payment` varchar(400) NOT NULL,
  `total_part_cost` varchar(400) NOT NULL,
  `service_charges` varchar(400) NOT NULL,
  `total_charges` varchar(400) NOT NULL,
  `less_deposit` varchar(400) NOT NULL,
  `less_discount` varchar(400) NOT NULL,
  `balance` varchar(400) NOT NULL,
  `status` varchar(400) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jb_permission`
--

CREATE TABLE `jb_permission` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission_type_id` int(11) NOT NULL,
  `add_status` enum('no','yes') NOT NULL,
  `edit_status` enum('no','yes') NOT NULL,
  `delete_status` enum('no','yes') NOT NULL,
  `view_status` enum('no','yes') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=547 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jb_permission`
--

INSERT INTO `jb_permission` (`id`, `user_id`, `permission_type_id`, `add_status`, `edit_status`, `delete_status`, `view_status`, `created_at`, `updated_at`) VALUES
(519, 3, 1, 'no', 'no', 'no', 'no', '2016-03-11 08:17:02', '2016-03-11 00:17:02'),
(520, 3, 2, 'no', 'yes', 'no', 'yes', '2016-03-11 08:17:02', '2016-03-11 00:17:02'),
(521, 3, 3, 'no', 'no', 'no', 'yes', '2016-03-11 08:17:02', '2016-03-11 00:17:02'),
(522, 3, 4, 'no', 'yes', 'no', 'yes', '2016-03-11 08:17:02', '2016-03-11 00:17:02'),
(523, 3, 5, 'yes', 'no', 'no', 'yes', '2016-03-11 08:17:02', '2016-03-11 00:17:02'),
(524, 3, 6, 'yes', 'no', 'yes', 'yes', '2016-03-11 08:17:02', '2016-03-11 00:17:02'),
(525, 3, 7, 'yes', 'no', 'no', 'yes', '2016-03-11 08:17:02', '2016-03-11 00:17:02'),
(526, 3, 8, 'yes', 'yes', 'yes', 'no', '2016-03-11 08:17:02', '2016-03-11 00:17:02'),
(527, 3, 9, 'yes', 'yes', 'yes', 'no', '2016-03-11 08:17:02', '2016-03-11 00:17:02'),
(528, 3, 10, 'yes', 'yes', 'yes', 'no', '2016-03-11 08:17:02', '2016-03-11 00:17:02'),
(529, 3, 11, 'yes', 'yes', 'yes', 'no', '2016-03-11 08:17:02', '2016-03-11 00:17:02'),
(530, 3, 12, 'no', 'no', 'no', 'no', '2016-03-11 08:17:02', '2016-03-11 00:17:02'),
(542, 4, 2, 'no', 'no', 'no', 'yes', '2016-03-11 09:38:24', '0000-00-00 00:00:00'),
(543, 4, 4, 'yes', 'yes', 'no', 'yes', '2016-03-11 09:38:24', '0000-00-00 00:00:00'),
(544, 4, 7, 'yes', 'no', 'no', 'yes', '2016-03-11 09:38:24', '0000-00-00 00:00:00'),
(545, 4, 8, 'yes', 'yes', 'yes', 'no', '2016-03-11 09:38:24', '0000-00-00 00:00:00'),
(546, 4, 12, 'no', 'no', 'no', 'no', '2016-03-11 09:38:24', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `jb_permission_type`
--

CREATE TABLE `jb_permission_type` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jb_permission_type`
--

INSERT INTO `jb_permission_type` (`id`, `name`) VALUES
(1, 'job_orders'),
(2, 'statements_of_account'),
(3, 'branch'),
(4, 'customers'),
(5, 'technicians'),
(6, 'parts'),
(7, 'staff'),
(8, 'diagnosis'),
(9, 'brands'),
(10, 'main_category'),
(11, 'models'),
(12, 'sales_report');

-- --------------------------------------------------------

--
-- Table structure for table `jb_revenue`
--

CREATE TABLE `jb_revenue` (
`id` int(11) NOT NULL,
  `branch_id` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jb_soa`
--

CREATE TABLE `jb_soa` (
`id` int(11) NOT NULL,
  `soa_id` varchar(200) NOT NULL,
  `jobid` varchar(200) NOT NULL,
  `customerid` varchar(200) NOT NULL,
  `branchid` varchar(200) NOT NULL,
  `technicianid` varchar(200) NOT NULL,
  `cost_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `conforme` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jb_soa`
--

INSERT INTO `jb_soa` (`id`, `soa_id`, `jobid`, `customerid`, `branchid`, `technicianid`, `cost_id`, `status`, `conforme`, `created_at`, `updated_at`) VALUES
(1, 'SOA-67375', '53645030', '1', '1', '2', 10, 1, '0', '2016-03-15 01:49:51', '2016-03-15 01:50:18'),
(2, 'SOA-85312', '01470820', '3', '1', '2', 11, 1, '0', '2016-03-15 02:03:29', '2016-03-15 02:04:00'),
(3, 'SOA-98068', '70912625', '2', '1', '2', 12, 1, '0', '2016-03-15 08:47:59', '2016-03-15 08:48:21'),
(4, 'SOA-09793', '72195206', '3', '1', '2', 13, 1, '0', '2016-03-15 10:25:38', '2016-03-15 10:26:15');

-- --------------------------------------------------------

--
-- Table structure for table `jb_status`
--

CREATE TABLE `jb_status` (
`id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `description` varchar(300) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jb_status`
--

INSERT INTO `jb_status` (`id`, `status_id`, `description`, `created_at`, `updated_at`) VALUES
(1, 0, 'finish', '2016-02-26 05:13:37', '2016-02-26 05:13:37'),
(2, 1, 'waiting_for_approval', '2016-02-26 05:13:37', '2016-02-26 05:13:37'),
(3, 2, 'waiting_to_finish', '2016-02-26 05:13:37', '2016-02-26 05:13:37'),
(4, 3, 'claimed', '2016-02-26 05:13:37', '2016-02-26 05:13:37'),
(5, 4, 'unclaimed', '2016-02-26 05:13:37', '2016-02-26 05:13:37'),
(6, 5, 'unpaid_jobs', '2016-02-26 05:13:37', '2016-02-26 05:13:37');

-- --------------------------------------------------------

--
-- Table structure for table `jb_technicians`
--

CREATE TABLE `jb_technicians` (
`tech_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `number` varchar(200) NOT NULL,
  `nickname` varchar(400) NOT NULL,
  `status` varchar(200) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  `date_hired` date NOT NULL,
  `tech_status` enum('active','inactive') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jb_technicians`
--

INSERT INTO `jb_technicians` (`tech_id`, `name`, `email`, `address`, `number`, `nickname`, `status`, `isdeleted`, `date_hired`, `tech_status`, `created_at`, `updated_at`) VALUES
(1, 'Gyver Caronongan', 'gyverc@gmail.com', 'Test', '0907204227', 'Gyver', '0', 0, '2016-03-01', 'active', '2016-01-23 10:51:59', '2016-03-15 10:26:35'),
(2, 'Richellee Lou Ancheta', 'rich@gmail.com', 'Lingayen', '09129392093', 'Macs', '1', 0, '2016-02-29', 'active', '2016-02-26 02:03:36', '2016-03-02 01:10:41'),
(3, 'Rex Reyes', 'super@gmail.com', '02304320', '12345678909', 'Poging Lamig', '0', 0, '2016-03-29', 'active', '2016-01-21 00:54:35', '2016-03-02 18:45:57'),
(4, 'Robert Manzano', 'robertmanzano@gmail.com', '0234954395', 'Lingayen, Pangasinan', 'Berto', '0', 0, '2016-03-28', 'active', '2016-01-23 11:35:00', '2016-02-27 09:57:41'),
(5, 'Nicko Manangan', 'nicksmanangan@gmail.com', '09138192831', 'Lingayen, Pangasinan', 'Nicks', '0', 0, '2016-03-24', 'active', '2015-11-25 12:16:18', '2016-03-02 10:29:59'),
(6, 'Jeff Straight', 'jeff@gmail.com', '09121239172', 'San Juan', 'Jeff', '0', 0, '2016-03-23', 'active', '2016-02-26 02:05:26', '0000-00-00 00:00:00'),
(7, 'Jayson Tamayo', 'jaysontamayo@gmai.com', 'Labrador', '12321234234', 'Kikass', '0', 0, '2016-03-21', 'active', '2016-01-23 11:32:09', '2016-02-27 09:57:41'),
(8, 'Test Technician Nameas', 'tech@yahoo.comas', '091777777777', '213123123213', 'Tech Edir', '0', 0, '2016-03-13', 'active', '2016-02-27 17:48:23', '2016-02-27 09:52:13');

-- --------------------------------------------------------

--
-- Table structure for table `jb_user`
--

CREATE TABLE `jb_user` (
`id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `image` varchar(255) NOT NULL,
  `name` varchar(200) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `midname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `nicknake` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `contact_number` varchar(200) NOT NULL,
  `position` varchar(200) NOT NULL,
  `level` int(11) NOT NULL,
  `job_title` varchar(200) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `customer_type_id` int(11) NOT NULL,
  `isdeleted` int(11) NOT NULL,
  `forgot_code` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jb_user`
--

INSERT INTO `jb_user` (`id`, `username`, `password`, `email`, `image`, `name`, `firstname`, `midname`, `lastname`, `nicknake`, `address`, `contact_number`, `position`, `level`, `job_title`, `branch_id`, `customer_type_id`, `isdeleted`, `forgot_code`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'ffe4d72bb9a9754dc4965e12a28a644f5549d458', 'mcclynrey@gmail.com', '59537104.jpg', 'Mc Clynrey A Arboleda', 'Mc Clynrey', 'A', 'Arboleda', 'Mc Clynrey', 'Manila', '09173061309', '-1', 1, 'Administrator', 0, 0, 0, '', 'active', '2016-02-25 07:01:25', '2016-03-06 03:22:28'),
(2, 'argie', '29afc39fc6313e68c3a06d61d9ff72f3ad32b0be', 'argie.navora@gmail.com', '38732357.jpg', 'Argie C Navora', 'Argie', 'C', 'Navora', 'Chiee', 'Manila', '09177777778', '2', 1, 'Staffs', 1, 0, 0, '', 'active', '2016-02-26 04:53:13', '2016-03-06 03:40:41'),
(3, 'marky', 'a2f9c0acee2a035b7d7850ab7cdaa586d9e1503e', 'mark@yahoo.com', '', 'Mark S Pagsisihan', 'Mark', 'S', 'Pagsisihan', 'Marky', 'Sta Rosa Laguna', '09177777777', '0', 1, 'Admin Staff', 0, 0, 0, '', 'active', '2016-03-04 19:19:28', '2016-03-06 14:33:23'),
(4, 'cyrus', 'a2f9c0acee2a035b7d7850ab7cdaa586d9e1503e', 'cyrus@yahoo.com', '49590182.jpg', 'Cyrus S Pagsisihan', 'Cyrus', 'S', 'Pagsisihan', 'Cyrus', 'Sta Rosa', '09177777777', '3', 3, 'Branch Staff', 1, 0, 0, '', 'active', '2016-03-04 22:10:39', '2016-03-11 09:38:24'),
(5, 'arthur', 'a2f9c0acee2a035b7d7850ab7cdaa586d9e1503e', 'arthur@yahoo.com', '', 'Arthur C Pascual', 'Arthur', 'C', 'Pascual', 'Arthur', 'Manila', '09177777777', '2', 1, 'Branch Manager', 4, 0, 0, '', 'active', '2016-03-07 11:16:49', '2016-03-07 03:16:49');

-- --------------------------------------------------------

--
-- Table structure for table `jb_warranty`
--

CREATE TABLE `jb_warranty` (
`id` int(11) NOT NULL,
  `jobid` varchar(50) NOT NULL,
  `warranty_type` varchar(50) NOT NULL,
  `warranty_date` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jb_warranty`
--

INSERT INTO `jb_warranty` (`id`, `jobid`, `warranty_type`, `warranty_date`, `created_at`, `updated_at`) VALUES
(1, '53645030', '', '2016-03-09 00:00:00', '2016-03-15 01:49:14', '0000-00-00 00:00:00'),
(2, '01470820', '', '2016-03-02 00:00:00', '2016-03-15 02:02:47', '0000-00-00 00:00:00'),
(3, '70912625', '', '2016-03-02 00:00:00', '2016-03-15 08:47:00', '0000-00-00 00:00:00'),
(4, '72195206', '', '2016-03-02 00:00:00', '2016-03-15 10:24:53', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `notitemp`
--

CREATE TABLE `notitemp` (
`notif_id` int(11) NOT NULL,
  `jobid` varchar(400) NOT NULL,
  `branch_id` varchar(500) NOT NULL,
  `user` varchar(400) NOT NULL,
  `status_type` varchar(500) NOT NULL,
  `isViewed` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notitemp`
--

INSERT INTO `notitemp` (`notif_id`, `jobid`, `branch_id`, `user`, `status_type`, `isViewed`, `created_at`, `updated_at`) VALUES
(1, '53645030', '1', 'Sta Rosa', 'created new job', 0, '2016-03-15 01:49:11', '0000-00-00 00:00:00'),
(2, '53645030', '0', 'Admin', ' \nset item arrived', 0, '2016-03-15 01:49:31', '0000-00-00 00:00:00'),
(3, '53645030', '0', 'Admin', ' \ngenerate new SOA ', 0, '2016-03-15 01:49:50', '0000-00-00 00:00:00'),
(4, '53645030', '1', 'Sta Rosa', '\napproved job', 0, '2016-03-15 01:50:18', '0000-00-00 00:00:00'),
(5, '53645030', '0', 'Admin', ' \nset item arrived', 0, '2016-03-15 01:50:50', '0000-00-00 00:00:00'),
(6, '53645030', '0', 'Admin', '\nset repaired job', 0, '2016-03-15 01:51:16', '0000-00-00 00:00:00'),
(7, '53645030', '1', 'Sta Rosa', 'created new job', 0, '2016-03-15 01:52:51', '0000-00-00 00:00:00'),
(8, '01470820', '1', 'Sta Rosa', 'created new job', 0, '2016-03-15 02:02:45', '0000-00-00 00:00:00'),
(9, '01470820', '0', 'Admin', ' \nset item arrived', 0, '2016-03-15 02:03:04', '0000-00-00 00:00:00'),
(10, '01470820', '0', 'Admin', ' \ngenerate new SOA ', 0, '2016-03-15 02:03:28', '0000-00-00 00:00:00'),
(11, '01470820', '1', 'Sta Rosa', '\napproved job', 0, '2016-03-15 02:04:00', '0000-00-00 00:00:00'),
(12, '01470820', '0', 'Admin', ' \nset item arrived', 0, '2016-03-15 02:04:44', '0000-00-00 00:00:00'),
(13, '01470820', '0', 'Mc Clynrey A Arboleda', '\nset cant repair job', 0, '2016-03-15 02:06:40', '0000-00-00 00:00:00'),
(14, '01470820', '1', 'Sta Rosa', 'created new job', 0, '2016-03-15 02:07:39', '0000-00-00 00:00:00'),
(15, '70912625', '1', 'Sta Rosa', 'created new job', 0, '2016-03-15 08:47:00', '0000-00-00 00:00:00'),
(16, '70912625', '0', 'Admin', ' \nset item arrived', 0, '2016-03-15 08:47:31', '0000-00-00 00:00:00'),
(17, '70912625', '0', 'Admin', ' \ngenerate new SOA ', 0, '2016-03-15 08:47:59', '0000-00-00 00:00:00'),
(18, '70912625', '1', 'Sta Rosa', '\napproved job', 0, '2016-03-15 08:48:21', '0000-00-00 00:00:00'),
(19, '70912625', '0', 'Admin', ' \nset item arrived', 0, '2016-03-15 08:50:11', '0000-00-00 00:00:00'),
(20, '70912625', '0', 'Admin', '\nset repaired job', 0, '2016-03-15 08:50:42', '0000-00-00 00:00:00'),
(21, '72195206', '1', 'Sta Rosa', 'created new job', 0, '2016-03-15 10:24:52', '0000-00-00 00:00:00'),
(22, '72195206', '0', 'Admin', ' \nset item arrived', 0, '2016-03-15 10:25:14', '0000-00-00 00:00:00'),
(23, '72195206', '0', 'Admin', ' \ngenerate new SOA ', 0, '2016-03-15 10:25:36', '0000-00-00 00:00:00'),
(24, '72195206', '1', 'Sta Rosa', '\napproved job', 0, '2016-03-15 10:26:15', '0000-00-00 00:00:00'),
(25, '72195206', '0', 'Admin', ' \nset item arrived', 0, '2016-03-15 10:26:35', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `subjoborder`
--

CREATE TABLE `subjoborder` (
`idsubjoborder` int(11) NOT NULL,
  `subjobid` varchar(500) NOT NULL,
  `mainjob` varchar(500) NOT NULL,
  `subdiagnosis` varchar(500) NOT NULL,
  `subparts` varchar(500) NOT NULL,
  `subtech` varchar(500) NOT NULL,
  `subcost` varchar(500) NOT NULL,
  `subremarks` varchar(500) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_client`
--

CREATE TABLE `tb_client` (
`client_id` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `isactivated` int(11) NOT NULL,
  `linkgen` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_client`
--

INSERT INTO `tb_client` (`client_id`, `username`, `password`, `customer_id`, `isactivated`, `linkgen`, `created_at`, `updated_at`) VALUES
(1, 'argie', 'a2f9c0acee2a035b7d7850ab7cdaa586d9e1503e', 4, 0, '27a976f3ede9ad88e6f588b7d070d23af14631ab', '2016-03-06 00:00:34', '2016-03-06 01:44:19');

-- --------------------------------------------------------

--
-- Table structure for table `tech_statistic`
--

CREATE TABLE `tech_statistic` (
`tech_stats_id` int(11) NOT NULL,
  `techid` int(11) NOT NULL,
  `jobid` varchar(500) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_done` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tech_statistic`
--

INSERT INTO `tech_statistic` (`tech_stats_id`, `techid`, `jobid`, `date_start`, `date_done`, `created_at`, `updated_at`) VALUES
(1, 2, '53645030', '2016-03-15 01:50:50', '2016-03-15 01:51:16', '2016-03-15 01:50:50', '2016-03-15 01:51:16'),
(2, 2, '01470820', '2016-03-15 02:04:44', '0000-00-00 00:00:00', '2016-03-15 02:04:44', '0000-00-00 00:00:00'),
(3, 2, '70912625', '2016-03-15 08:50:11', '2016-03-15 08:50:42', '2016-03-15 08:50:11', '2016-03-15 08:50:42'),
(4, 2, '72195206', '2016-03-15 10:26:35', '0000-00-00 00:00:00', '2016-03-15 10:26:35', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer_type`
--
ALTER TABLE `customer_type`
 ADD PRIMARY KEY (`customer_type_id`);

--
-- Indexes for table `jb_branch`
--
ALTER TABLE `jb_branch`
 ADD PRIMARY KEY (`branch_id`);

--
-- Indexes for table `jb_brands`
--
ALTER TABLE `jb_brands`
 ADD PRIMARY KEY (`brandid`);

--
-- Indexes for table `jb_cost`
--
ALTER TABLE `jb_cost`
 ADD PRIMARY KEY (`cost_id`);

--
-- Indexes for table `jb_customer`
--
ALTER TABLE `jb_customer`
 ADD PRIMARY KEY (`customerid`);

--
-- Indexes for table `jb_diagnosis`
--
ALTER TABLE `jb_diagnosis`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jb_email`
--
ALTER TABLE `jb_email`
 ADD PRIMARY KEY (`email_id`);

--
-- Indexes for table `jb_history`
--
ALTER TABLE `jb_history`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jb_joborder`
--
ALTER TABLE `jb_joborder`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `JobID` (`jobid`);

--
-- Indexes for table `jb_models`
--
ALTER TABLE `jb_models`
 ADD PRIMARY KEY (`modelid`);

--
-- Indexes for table `jb_part`
--
ALTER TABLE `jb_part`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jb_partscat`
--
ALTER TABLE `jb_partscat`
 ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `jb_partssubcat`
--
ALTER TABLE `jb_partssubcat`
 ADD PRIMARY KEY (`subcat_id`);

--
-- Indexes for table `jb_payment`
--
ALTER TABLE `jb_payment`
 ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `jb_permission`
--
ALTER TABLE `jb_permission`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jb_permission_type`
--
ALTER TABLE `jb_permission_type`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jb_revenue`
--
ALTER TABLE `jb_revenue`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jb_soa`
--
ALTER TABLE `jb_soa`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `soa_id` (`soa_id`);

--
-- Indexes for table `jb_status`
--
ALTER TABLE `jb_status`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jb_technicians`
--
ALTER TABLE `jb_technicians`
 ADD PRIMARY KEY (`tech_id`);

--
-- Indexes for table `jb_user`
--
ALTER TABLE `jb_user`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`,`email`);

--
-- Indexes for table `jb_warranty`
--
ALTER TABLE `jb_warranty`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `jobid` (`jobid`);

--
-- Indexes for table `notitemp`
--
ALTER TABLE `notitemp`
 ADD PRIMARY KEY (`notif_id`);

--
-- Indexes for table `subjoborder`
--
ALTER TABLE `subjoborder`
 ADD PRIMARY KEY (`idsubjoborder`);

--
-- Indexes for table `tb_client`
--
ALTER TABLE `tb_client`
 ADD PRIMARY KEY (`client_id`), ADD UNIQUE KEY `customer_id` (`customer_id`);

--
-- Indexes for table `tech_statistic`
--
ALTER TABLE `tech_statistic`
 ADD PRIMARY KEY (`tech_stats_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer_type`
--
ALTER TABLE `customer_type`
MODIFY `customer_type_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `jb_branch`
--
ALTER TABLE `jb_branch`
MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `jb_brands`
--
ALTER TABLE `jb_brands`
MODIFY `brandid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `jb_cost`
--
ALTER TABLE `jb_cost`
MODIFY `cost_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `jb_customer`
--
ALTER TABLE `jb_customer`
MODIFY `customerid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `jb_diagnosis`
--
ALTER TABLE `jb_diagnosis`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `jb_email`
--
ALTER TABLE `jb_email`
MODIFY `email_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `jb_history`
--
ALTER TABLE `jb_history`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `jb_joborder`
--
ALTER TABLE `jb_joborder`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `jb_models`
--
ALTER TABLE `jb_models`
MODIFY `modelid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `jb_part`
--
ALTER TABLE `jb_part`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `jb_partscat`
--
ALTER TABLE `jb_partscat`
MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `jb_partssubcat`
--
ALTER TABLE `jb_partssubcat`
MODIFY `subcat_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=99;
--
-- AUTO_INCREMENT for table `jb_payment`
--
ALTER TABLE `jb_payment`
MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jb_permission`
--
ALTER TABLE `jb_permission`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=547;
--
-- AUTO_INCREMENT for table `jb_permission_type`
--
ALTER TABLE `jb_permission_type`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `jb_revenue`
--
ALTER TABLE `jb_revenue`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jb_soa`
--
ALTER TABLE `jb_soa`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `jb_status`
--
ALTER TABLE `jb_status`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `jb_technicians`
--
ALTER TABLE `jb_technicians`
MODIFY `tech_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `jb_user`
--
ALTER TABLE `jb_user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `jb_warranty`
--
ALTER TABLE `jb_warranty`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `notitemp`
--
ALTER TABLE `notitemp`
MODIFY `notif_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `subjoborder`
--
ALTER TABLE `subjoborder`
MODIFY `idsubjoborder` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_client`
--
ALTER TABLE `tb_client`
MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tech_statistic`
--
ALTER TABLE `tech_statistic`
MODIFY `tech_stats_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
