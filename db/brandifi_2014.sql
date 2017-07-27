-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2014 at 08:06 AM
-- Server version: 5.5.32
-- PHP Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `brandifi_2014`
--
CREATE DATABASE IF NOT EXISTS `brandifi_2014` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `brandifi_2014`;

-- --------------------------------------------------------

--
-- Table structure for table `my_profile`
--

DROP TABLE IF EXISTS `my_profile`;
CREATE TABLE IF NOT EXISTS `my_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ownerid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `type` int(11) NOT NULL COMMENT 'type of this pages',
  `brand` int(11) NOT NULL,
  `city` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `closed_date` datetime NOT NULL,
  `n_status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ownerid` (`ownerid`),
  KEY `name` (`name`),
  KEY `type` (`type`),
  KEY `created_date` (`created_date`),
  KEY `closed_date` (`closed_date`),
  KEY `n_status` (`n_status`),
  KEY `city` (`city`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `my_profile`
--

INSERT INTO `my_profile` (`id`, `ownerid`, `name`, `description`, `type`, `brand`, `city`, `created_date`, `closed_date`, `n_status`) VALUES
(1, 1, 'sba', 'SBA Kana', 100, 4, 140, '2014-05-09 00:00:00', '2014-05-09 00:00:00', 0),
(2, 42, 'sba', 'SBA Kana', 1, 4, 140, '2014-05-09 00:00:00', '2014-05-09 00:00:00', 0),
(3, 43, 'sba', 'SBA Kana', 666, 4, 140, '2014-05-09 00:00:00', '2014-05-09 00:00:00', 1),
(4, 44, 'sba', 'SBA Kana', 1, 1, 140, '2014-05-09 00:00:00', '2014-05-09 00:00:00', 0),
(5, 45, 'sba', 'SBA Kana', 1, 1, 140, '2014-05-09 00:00:00', '2014-05-09 00:00:00', 1),
(6, 46, 'sba', 'SBA Kana', 1, 1, 140, '2014-05-09 00:00:00', '2014-05-09 00:00:00', 1),
(7, 47, 'sba', 'SBA Kana', 1, 1, 140, '2014-05-09 00:00:00', '2014-05-09 00:00:00', 1),
(8, 48, 'Client', 'Client Gogirl', 100, 1, 140, '2014-05-09 00:00:00', '2014-05-09 00:00:00', 1),
(9, 50, 'cendekia', 'sadsad', 100, 1, 140, '2014-05-09 00:00:00', '2014-05-09 00:00:00', 1),
(10, 51, 'project 1', '', 100, 11, 0, '2014-06-19 11:46:39', '2014-06-19 11:46:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `my_profile_type`
--

DROP TABLE IF EXISTS `my_profile_type`;
CREATE TABLE IF NOT EXISTS `my_profile_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `Definition` varchar(200) DEFAULT NULL,
  `n_status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `n_status` (`n_status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=667 ;

--
-- Dumping data for table `my_profile_type`
--

INSERT INTO `my_profile_type` (`id`, `name`, `Definition`, `n_status`) VALUES
(1, 'Promoter', 'Sales Promotion User', 1),
(2, 'Client', 'Client User', 1),
(3, 'Brand', 'Brand departmental member', 1),
(4, '121', 'direct communication marketing department', 1),
(5, 'Area(SAM)', 'Supervisor Area Marketing', 1),
(6, 'IS', 'Information System', 1),
(7, 'DST', 'DST', 1),
(100, 'Agency', 'appointed agency/ vendors involved with portal development and infrastructure', 1),
(666, 'God Like', 'God Like', 1);

-- --------------------------------------------------------

--
-- Table structure for table `social_member`
--

DROP TABLE IF EXISTS `social_member`;
CREATE TABLE IF NOT EXISTS `social_member` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `registerid` varchar(200) NOT NULL,
  `name` varchar(46) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `img` varchar(200) DEFAULT NULL,
  `small_img` varchar(200) DEFAULT NULL,
  `username` varchar(46) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `city` varchar(110) DEFAULT NULL,
  `sex` varchar(11) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `last_name` varchar(46) DEFAULT NULL,
  `StreetName` varchar(150) DEFAULT NULL,
  `phone_number` bigint(15) DEFAULT NULL,
  `n_status` int(3) NOT NULL DEFAULT '0',
  `login_count` int(11) NOT NULL DEFAULT '0',
  `login_mop` int(11) NOT NULL DEFAULT '0',
  `try_to_login` int(11) NOT NULL,
  `last_log_id` int(11) NOT NULL,
  `verified` tinyint(3) DEFAULT '0' COMMENT '0->no hp blm verified, 1->sudah verified.',
  `salt` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `deviceid` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `registerid` (`registerid`),
  KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

--
-- Dumping data for table `social_member`
--

INSERT INTO `social_member` (`id`, `registerid`, `name`, `nickname`, `email`, `register_date`, `img`, `small_img`, `username`, `last_login`, `city`, `sex`, `birthday`, `last_name`, `StreetName`, `phone_number`, `n_status`, `login_count`, `login_mop`, `try_to_login`, `last_log_id`, `verified`, `salt`, `password`, `deviceid`) VALUES
(1, '193', 'Bummi', 'Bummi', 'bummi@kana.co.id', '2014-01-13 11:37:47', NULL, NULL, 'bummi@kana.co.id', '2014-05-26 15:35:52', NULL, 'Male', NULL, 'D Putera', NULL, NULL, 0, 256, 0, 3, 0, 0, '12345678', 'Bs7SJvS0ag1geoaVMkm7FF5naPMLMTL6zTE0CFtQkPc=', 'sba@kana.co.id'),
(42, '194', 'Bummi2', 'Bummi2', 'bummi2@kana.co.id', '2014-01-13 11:37:47', NULL, NULL, 'bummi2@kana.co.id', '2014-05-09 18:04:42', NULL, 'Male', NULL, 'D Putera', NULL, NULL, 0, 0, 0, 0, 0, 0, '12345678', 'qOyRN+0yz2PHJWCQ0VPIg09ejbY0YUK6kY757z1QB0U=', 'sba@kana.co.id'),
(43, '195', 'Touch Base', 'TBUser', 'touchbase', '2014-01-13 11:37:47', NULL, NULL, 'touchbase', '2014-07-07 13:43:23', NULL, 'Male', NULL, 'Users', NULL, NULL, 1, 0, 0, 0, 0, 0, '12345678', 'qOyRN+0yz2PHJWCQ0VPIg09ejbY0YUK6kY757z1QB0U=', 'sba@kana.co.id'),
(44, '196', 'Inong', 'inong', 'inong', '2014-01-13 11:37:47', NULL, NULL, 'inong', '2014-05-28 12:06:23', NULL, 'Male', NULL, '', NULL, NULL, 1, 6, 0, 0, 0, 0, '12345678', 'qOyRN+0yz2PHJWCQ0VPIg09ejbY0YUK6kY757z1QB0U=', 'sba@kana.co.id'),
(45, '195', 'Go Girl 1', 'GGuser', 'gogirl1', '2014-01-13 11:37:47', NULL, NULL, 'gogirl1', '2014-06-03 15:00:56', NULL, 'Female', NULL, '', NULL, NULL, 1, 0, 0, 0, 0, 0, '12345678', 'qOyRN+0yz2PHJWCQ0VPIg09ejbY0YUK6kY757z1QB0U=', 'gogirl1@kana.co.id'),
(46, '196', 'Go Girl 2', 'GGuser', 'gogirl2', '2014-01-13 11:37:47', NULL, NULL, 'gogirl2', '2014-05-18 17:42:51', NULL, 'Female', NULL, '', NULL, NULL, 1, 0, 0, 0, 0, 0, '12345678', 'qOyRN+0yz2PHJWCQ0VPIg09ejbY0YUK6kY757z1QB0U=', 'gogirl2@kana.co.id'),
(47, '197', 'Go Girl 3', 'GGuser', 'gogirl3', '2014-01-13 11:37:47', NULL, NULL, 'gogirl3', '2014-06-01 12:41:12', NULL, 'Female', NULL, '', NULL, NULL, 1, 0, 0, 0, 0, 0, '12345678', 'qOyRN+0yz2PHJWCQ0VPIg09ejbY0YUK6kY757z1QB0U=', 'gogirl3@kana.co.id'),
(48, '197', 'Go Girl Admin', 'GGuser Admin', 'gogirl', '2014-01-13 11:37:47', NULL, NULL, 'gogirl', '2014-07-08 10:15:35', NULL, 'Female', NULL, '', NULL, NULL, 1, 0, 0, 0, 0, 0, '12345678', 'qOyRN+0yz2PHJWCQ0VPIg09ejbY0YUK6kY757z1QB0U=', 'gogirl@kana.co.id'),
(50, '', 'cendekia', NULL, 'cendiqkrn@gmail.com', '2014-06-06 08:22:05', NULL, NULL, 'cendiqkrn@gmail.com', '2014-06-10 14:14:49', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 2, 0, 0, '12345', 'qOyRN+0yz2PHJWCQ0VPIg09ejbY0YUK6kY757z1QB0U=', ''),
(51, '', 'project 1', NULL, 'aspi@kana.co.id', '2014-06-19 04:46:39', NULL, NULL, 'aspi@kana.co.id', '2014-06-19 11:47:10', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 0, '12345', 'OItCasq2adc01/u34wtjydLkUNZAgMp0rWREYrVhfmE=', ''),
(52, '', 'asd ', '', '', '2014-06-20 08:05:40', NULL, NULL, 'adada', NULL, NULL, '', NULL, '', NULL, NULL, 1, 0, 0, 0, 0, 0, '12345678', 'aTH7bdk/UT0eY9dAL8GGGVwrOvX4a1KwamlEHCXCf5Y=', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_template`
--

DROP TABLE IF EXISTS `tbl_template`;
CREATE TABLE IF NOT EXISTS `tbl_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `login_bg` varchar(250) NOT NULL,
  `thankyou_bg` varchar(250) NOT NULL,
  `login_type` int(11) NOT NULL,
  `redirect_url` varchar(250) NOT NULL,
  `submit_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `n_status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_template`
--

INSERT INTO `tbl_template` (`id`, `userid`, `login_bg`, `thankyou_bg`, `login_type`, `redirect_url`, `submit_date`, `modified_date`, `n_status`) VALUES
(1, NULL, 'a:2:{i:0;s:36:"846ed5949b768d120fd6b79d6c6a4eb0.jpg";i:1;s:36:"02577f0332fe7b10ade3b9219fab4c20.jpg";}', 'a:2:{i:0;s:36:"94903304c89343327b0a57bfba4a4027.jpg";i:1;s:36:"0adee9c4aa868ed7b6dc8175ef6b756f.jpg";}', 1, 'adas', '2014-07-08 11:32:59', '2014-07-08 11:32:59', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
