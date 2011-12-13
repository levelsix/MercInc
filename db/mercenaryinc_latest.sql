-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 31, 2011 at 08:28 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mercenaryinc`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE IF NOT EXISTS `achievements` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `req_type` varchar(45) NOT NULL,
  `req_number` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `achievements`
--

INSERT INTO `achievements` (`id`, `name`, `req_type`, `req_number`) VALUES
(1, 'missions1', 'missions', 10),
(2, 'missions2', 'missions', 50);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `soldier_code` char(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `level` int(11) unsigned DEFAULT '1',
  `type` varchar(255) DEFAULT NULL,
  `attack` int(11) DEFAULT '0',
  `defense` int(11) DEFAULT '0',
  `bank_balance` int(11) unsigned DEFAULT '0',
  `cash` int(11) unsigned DEFAULT '0',
  `experience` int(11) unsigned DEFAULT '0',
  `stamina` int(11) unsigned DEFAULT '3',
  `energy` int(11) unsigned DEFAULT '100',
  `skill_points` int(11) unsigned DEFAULT '10',
  `health` int(11) unsigned DEFAULT '100',
  `missions_completed` int(11) unsigned DEFAULT '0',
  `fights_won` int(11) unsigned DEFAULT '0',
  `fights_lost` int(11) unsigned DEFAULT '0',
  `kills` int(11) unsigned DEFAULT '0',
  `deaths` int(11) unsigned DEFAULT '0',
  `income` int(11) unsigned DEFAULT '0',
  `upkeep` int(11) unsigned DEFAULT '0',
  `health_max` int(11) unsigned DEFAULT '100',
  `energy_max` int(11) unsigned DEFAULT '100',
  `stamina_max` int(11) unsigned DEFAULT '3',
  `agency_code` varchar(255) DEFAULT NULL,
  `agency_size` int(11) unsigned DEFAULT '1',
  `last_login` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `num_consecutive_days_played` int(11) unsigned DEFAULT '0',
  `diamonds` int(11) unsigned DEFAULT '0',
  `notification_settings` tinyint(1) DEFAULT NULL,
  `comment_settings` tinyint(1) DEFAULT NULL,
  `vibration_settings` tinyint(1) DEFAULT NULL,
  `sound_settings` tinyint(1) DEFAULT NULL,
  `udid` char(250) DEFAULT NULL,
  `device` char(50) DEFAULT NULL,
  `device_os` char(50) DEFAULT NULL,
  `c2dm_token` char(250) DEFAULT NULL,
  `next_level_experince_points` int(11) DEFAULT NULL,
  `num_attacks` int(11) NOT NULL DEFAULT '0',
  `dummy_user_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `recruit_code_UNIQUE` (`agency_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `soldier_code`, `name`, `level`, `type`, `attack`, `defense`, `bank_balance`, `cash`, `experience`, `stamina`, `energy`, `skill_points`, `health`, `missions_completed`, `fights_won`, `fights_lost`, `kills`, `deaths`, `income`, `upkeep`, `health_max`, `energy_max`, `stamina_max`, `agency_code`, `agency_size`, `last_login`, `num_consecutive_days_played`, `diamonds`, `notification_settings`, `comment_settings`, `vibration_settings`, `sound_settings`, `udid`, `device`, `device_os`, `c2dm_token`, `next_level_experince_points`, `num_attacks`, `dummy_user_count`) VALUES
(1, '', 'test1', 10, '1', 12, 0, 0, 0, 10, 0, 100, 0, 10, 0, 1, 5, 0, 0, 0, 0, 100, 100, 3, '1', 2, '2011-08-03 23:51:39', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(2, '', 'test2', 10, '1', 14, 0, 0, 1000, 2, 0, 100, 0, 610, 0, 1, 29, 0, 0, 0, 0, 100, 100, 3, '2', 1, '2011-08-16 02:47:08', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(3, '', 'test3', 10, '1', 15, 4, 0, 0, 10, 0, 100, 0, 100, 0, 6, 0, 0, 0, 0, 0, 100, 100, 3, '3', 1, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(4, '', 'test4', 6, '1', 16, 4, 0, 1000, 0, 0, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '4', 2, '2011-08-17 01:18:35', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(5, '', 'test5', 6, '1', 15, 3, 0, 2000, 0, 100, 100, 0, 70, 0, 0, 2, 0, 0, 0, 0, 100, 100, 3, '5', 2, '2011-08-24 23:55:01', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(6, '', 'test6', 1, '1', 12, 10, 0, 0, 0, 100, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '6', 1, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(7, '', 'test7', 1, '1', 12, 10, 0, 0, 0, 100, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '7', 1, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(8, '', 'test8', 1, '1', 12, 10, 0, 0, 0, 100, 100, 0, 55, 0, 0, 3, 0, 0, 0, 0, 100, 100, 3, '8', 1, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(9, '', 'test9', 1, '1', 12, 10, 0, 0, 0, 100, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '9', 1, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(10, '', 'test10', 12, '1', 30, 15, 2960, 166431, 123, 41, 160, 97, 970, 99, 47, 8, 0, 0, 0, 0, 1000, 100, 5, '10', 7, '2011-10-01 00:15:16', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(11, '', 'test11', 11, '1', 12, 10, 0, 3508, 0, 100, 100, 0, 835, 0, 0, 17, 0, 0, 0, 0, 100, 100, 3, '11', 3, '2011-10-26 09:40:20', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(12, '1FFSEF', 'test12', 12, '2', 12, 10, 0, 30059, 6, 97, 100, 0, 10, 0, 3, 3, 0, 0, 1400, 0, 100, 100, 3, '12', 2, '2011-10-26 13:17:16', 1, 0, 0, 1, 1, 0, NULL, NULL, NULL, NULL, NULL, 0, 0),
(13, '', 'test13', 16, '2', 13, 9, 0, 1736, 37, 89, 100, 0, 85, 0, 11, 0, 0, 0, 0, 0, 100, 100, 3, '66kl66', 2, '2011-10-21 11:05:55', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(14, '', 'test14', 1, '2', 12, 10, 0, 0, 1, 100, 100, 0, 85, 0, 1, 0, 0, 0, 0, 0, 100, 100, 3, '14', 1, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(15, '', 'test15', 7, '2', 12, 10, 0, 0, 0, 100, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '15', 1, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(16, '', 'test16', 1, '2', 12, 10, 0, 0, 0, 3, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '16', 1, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(17, '', 'test17', 1, '2', 16, 13, 0, 0, 0, 3, 100, 41, 100, 0, 0, 0, 0, 0, 0, 0, 102, 101, 4, '17', 1, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(18, '', 'test18', 2305, '2', 12, 15, 210, 2840, 23058, 199, 97100, 6894, 40, 365, 1, 3, 0, 0, 0, 0, 170, 1003, 9, '18', 3, '2011-10-01 00:27:33', 1, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(19, '', 'test19', 1, '2', 13, 11, 0, 1000, 0, 3, 100, 7, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 4, '19', 1, '2011-09-03 19:12:22', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(20, '', 'Calvin', 1, '2', 14, 8, 0, 4012, 2, 3, 100, 10, 85, 0, 1, 0, 0, 0, 0, 0, 100, 100, 3, '20', 1, '2011-10-17 13:29:16', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(21, '', 'Alex', 39, '2', 17, 10, 900, 47213, 397, 20, 103, 99, 70, 63, 2, 0, 0, 0, 0, 0, 130, 101, 400, '21', 8, '2011-10-12 12:27:36', 3, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(22, '', 'Conrad', 2, '2', 14, 10, 0, 1529, 0, 3, 100, 0, 85, 0, 0, 1, 0, 0, 0, 0, 100, 100, 3, '22', 1, '2011-10-12 12:23:00', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(23, '', 'test23', 1, '2', 12, 10, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '23', 1, '2011-08-07 00:46:50', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(24, '', 'test24', 6, '2', 13, 9, 0, 505, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '24', 1, '2011-10-06 13:57:53', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(25, '', 'test25', 1, '2', 0, 0, 0, 4441, 0, 3, 100, 10, 85, 0, 0, 1, 0, 0, 0, 0, 100, 100, 3, '25', 1, '2011-09-20 16:38:30', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(26, '', 'test26', 1, NULL, 0, 0, 0, 17483, 0, 3, 100, 10, 85, 0, 0, 1, 0, 0, 0, 0, 100, 100, 3, '26', 1, '2011-10-26 13:17:10', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(27, '', 'test27', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '27', 1, '2011-08-11 19:41:07', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(28, '', 'test28', 1, NULL, 0, 0, 0, 588, 80, 3, 80, 10, 100, 2, 0, 0, 0, 0, 0, 0, 100, 100, 3, '44kk44', 0, '2011-08-11 19:50:33', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(29, '', 'test29', 37, '1', 19, 15, 54900, 29208, 375, 95, 99, 6, 10, 31, 6, 0, 0, 0, 5000, 2000, 320, 101, 4, '44hh44', 2, '2011-10-26 16:36:18', 4, 0, 1, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, 0, 0),
(30, '', 'test30', 4, '1', 0, 0, 0, 6406, 15, 3, 60, 10, 100, 2, 0, 0, 0, 0, 0, 0, 100, 100, 3, '30', 1, '2011-10-12 11:42:58', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(31, '', 'test31', 3, '2', 0, 0, 0, 29706, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '31', 0, '2011-10-21 10:53:06', 2, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(32, '', 'test32', 1, NULL, 0, 0, 0, 973, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '32', 0, '2011-10-18 07:21:14', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(33, '', 'test33', 1, NULL, 0, 0, 0, 6543, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '33', 1, '2011-10-18 07:22:01', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(34, '', 'test34', 1, NULL, 0, 0, 0, 2833, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '34', 1, '2011-10-26 12:25:00', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(35, '', 'test35', 1, '3', 0, 0, 0, 2357, 0, 3, 100, 10, 55, 0, 0, 3, 0, 0, 0, 0, 100, 100, 3, '35', 0, '2011-10-18 07:41:32', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(36, '', 'test36', 1, NULL, 0, 0, 0, 1183, 0, 3, 100, 10, 85, 0, 0, 1, 0, 0, 0, 0, 100, 100, 3, '36', 1, '2011-10-25 10:51:34', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(37, '', '18', 1, NULL, 0, 0, 0, 5605, 1, 3, 93, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '37', 1, '2011-10-25 10:52:18', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(38, '', 'ruby', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 70, 0, 0, 2, 0, 0, 0, 0, 100, 100, 3, '38', 1, '2011-09-12 04:00:01', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(39, '', 'q', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '39', 1, '2011-09-17 18:04:45', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(40, '', 'water', 2, NULL, 0, 0, 0, 10266, 28, 3, 40, 13, 100, 6, 0, 0, 0, 0, 0, 0, 100, 100, 3, '40', 1, '2011-10-17 13:26:01', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(41, '', 'Fire', 1, NULL, 0, 0, 0, 295, 4, 3, 90, 10, 100, 1, 0, 0, 0, 0, 0, 0, 100, 100, 3, '41', 0, '2011-10-01 06:56:33', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(42, '', 'conrad', 1, NULL, 0, 0, 0, 771, 12, 3, 80, 10, 85, 2, 0, 1, 0, 0, 0, 0, 100, 100, 3, NULL, 1, '2011-10-01 07:19:40', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(43, '', 'Asher', 38, NULL, 0, 0, 145, 40909, 386, 100, 0, 302, 0, 71, 0, 0, 0, 0, 0, 0, 100, 100, 3, NULL, 10, '2011-10-13 14:48:10', 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(44, 'Array', '', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, NULL, 1, '2011-10-24 22:24:34', 0, 0, NULL, NULL, NULL, NULL, '1asdfasdfasdf', NULL, NULL, NULL, NULL, 0, 0),
(45, '00274F', '', 1, NULL, 0, 0, 0, 599, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, NULL, 1, '2011-10-26 12:24:55', 1, 0, NULL, NULL, NULL, NULL, '1asdfasdfasdf1', NULL, NULL, NULL, NULL, 0, 0),
(46, '002SPY', '', 1, NULL, 0, 0, 0, 12195, 15, 3, 0, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, NULL, 1, '2011-10-26 14:56:35', 2, 0, NULL, NULL, NULL, NULL, '1asdfasdfasdf465465465', NULL, NULL, NULL, NULL, 0, 0),
(47, '0043U7', '', 1, NULL, 0, 0, 0, 8913, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, NULL, 1, '2011-10-26 14:56:42', 1, 0, NULL, NULL, NULL, NULL, '1asdfasdfasdf46546546564646', NULL, NULL, NULL, NULL, 0, 0),
(48, '0043U8', 'Waseem Mansha', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, NULL, 1, '2011-10-25 10:19:56', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(49, '004B3F', 'Gondal', 3, '2', 0, 0, 0, 1872, 31, 3, 13, 16, 55, 6, 0, 3, 0, 0, 0, 2000, 100, 100, 3, NULL, 1, '2011-10-25 10:56:42', 0, 0, NULL, NULL, NULL, NULL, 'tjmrumuek', NULL, NULL, NULL, NULL, 0, 0),
(50, '00G5XG', '', 1, NULL, 0, 0, 0, 125, 1, 3, 93, 10, 100, 0, 0, 0, 0, 0, 0, 1000, 100, 100, 3, NULL, 1, '2011-10-25 11:36:03', 0, 0, NULL, NULL, NULL, NULL, '4564ddfasdfasdf465', NULL, NULL, NULL, NULL, 0, 0),
(51, '017XFP', 'Gondal1', 8, '3', 9, 5, 413, 64825, 85, 0, 50, 0, 100, 17, 3, 0, 0, 0, 0, 0, 100, 107, 8, NULL, 2, '2011-10-25 12:24:36', 0, 0, NULL, NULL, NULL, NULL, '456465SDFASD', NULL, NULL, NULL, NULL, 0, 0),
(52, '019T03', 'Gondal', 10, '1', 4, 7, 0, 68420, 104, 0, 37, 0, 55, 16, 2, 1, 0, 0, 1800, 0, 100, 100, 16, NULL, 3, '2011-10-26 14:57:09', 1, 0, NULL, NULL, NULL, NULL, '65464FFFSDF', NULL, NULL, NULL, NULL, 0, 0),
(53, '01H1Q5', 'Gondal12', 5, '2', 0, 0, 0, 16018, 57, 2, 56, 22, 85, 15, 0, 1, 0, 0, 0, 0, 100, 100, 3, NULL, 1, '2011-10-25 13:21:51', 0, 0, NULL, NULL, NULL, NULL, '4564sdfasfASDF', NULL, NULL, '65465DFSDFsdf', NULL, 0, 0),
(54, '01PSY4', '', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, NULL, 1, '2011-10-25 15:35:11', 0, 0, NULL, NULL, NULL, NULL, '65464FFFSDF45646', NULL, NULL, NULL, NULL, 0, 0)

-- --------------------------------------------------------

--
-- Table structure for table `agencies`
--

CREATE TABLE IF NOT EXISTS `agencies` (
  `user_one_id` int(11) unsigned NOT NULL,
  `user_two_id` int(11) unsigned NOT NULL,
  `accepted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`user_one_id`,`user_two_id`),
  KEY `user_one_id` (`user_one_id`),
  KEY `user_two_id` (`user_two_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agencies`
--

INSERT INTO `agencies` (`user_one_id`, `user_two_id`, `accepted`) VALUES
(12, 52, 1),
(29, 13, 0),
(29, 28, 0),
(29, 30, 0),
(29, 32, 0),
(29, 52, 1),
(51, 29, 1),
(51, 40, 0),
(51, 42, 0);

-- --------------------------------------------------------

--
-- Table structure for table `apns_devices`
--

CREATE TABLE IF NOT EXISTS `apns_devices` (
  `pid` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `appname` varchar(255) NOT NULL,
  `appversion` varchar(25) DEFAULT NULL,
  `deviceuid` char(40) NOT NULL,
  `devicetoken` char(64) NOT NULL,
  `devicename` varchar(255) NOT NULL,
  `devicemodel` varchar(100) NOT NULL,
  `deviceversion` varchar(25) NOT NULL,
  `pushbadge` enum('disabled','enabled') DEFAULT 'disabled',
  `pushalert` enum('disabled','enabled') DEFAULT 'disabled',
  `pushsound` enum('disabled','enabled') DEFAULT 'disabled',
  `development` enum('production','sandbox') CHARACTER SET latin1 NOT NULL DEFAULT 'production',
  `status` enum('active','uninstalled') NOT NULL DEFAULT 'active',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pid`),
  UNIQUE KEY `appname` (`appname`,`appversion`,`deviceuid`),
  KEY `devicetoken` (`devicetoken`),
  KEY `devicename` (`devicename`),
  KEY `devicemodel` (`devicemodel`),
  KEY `deviceversion` (`deviceversion`),
  KEY `pushbadge` (`pushbadge`),
  KEY `pushalert` (`pushalert`),
  KEY `pushsound` (`pushsound`),
  KEY `development` (`development`),
  KEY `status` (`status`),
  KEY `created` (`created`),
  KEY `modified` (`modified`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Store unique devices' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `apns_devices`
--


--
-- Triggers `apns_devices`
--
DROP TRIGGER IF EXISTS `Archive`;
DELIMITER //
CREATE TRIGGER `Archive` BEFORE UPDATE ON `apns_devices`
 FOR EACH ROW INSERT INTO `apns_device_history` VALUES (
	NULL,
	OLD.`appname`,
	OLD.`appversion`,
	OLD.`deviceuid`,
	OLD.`devicetoken`,
	OLD.`devicename`,
	OLD.`devicemodel`,
	OLD.`deviceversion`,
	OLD.`pushbadge`,
	OLD.`pushalert`,
	OLD.`pushsound`,
	OLD.`development`,
	OLD.`status`,
	NOW()
)
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `apns_device_history`
--

CREATE TABLE IF NOT EXISTS `apns_device_history` (
  `pid` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `appname` varchar(255) NOT NULL,
  `appversion` varchar(25) DEFAULT NULL,
  `deviceuid` char(40) NOT NULL,
  `devicetoken` char(64) NOT NULL,
  `devicename` varchar(255) NOT NULL,
  `devicemodel` varchar(100) NOT NULL,
  `deviceversion` varchar(25) NOT NULL,
  `pushbadge` enum('disabled','enabled') DEFAULT 'disabled',
  `pushalert` enum('disabled','enabled') DEFAULT 'disabled',
  `pushsound` enum('disabled','enabled') DEFAULT 'disabled',
  `development` enum('production','sandbox') CHARACTER SET latin1 NOT NULL DEFAULT 'production',
  `status` enum('active','uninstalled') NOT NULL DEFAULT 'active',
  `archived` datetime NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `devicetoken` (`devicetoken`),
  KEY `devicename` (`devicename`),
  KEY `devicemodel` (`devicemodel`),
  KEY `deviceversion` (`deviceversion`),
  KEY `pushbadge` (`pushbadge`),
  KEY `pushalert` (`pushalert`),
  KEY `pushsound` (`pushsound`),
  KEY `development` (`development`),
  KEY `status` (`status`),
  KEY `appname` (`appname`),
  KEY `appversion` (`appversion`),
  KEY `deviceuid` (`deviceuid`),
  KEY `archived` (`archived`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Store unique device history' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `apns_device_history`
--


-- --------------------------------------------------------

--
-- Table structure for table `apns_messages`
--

CREATE TABLE IF NOT EXISTS `apns_messages` (
  `pid` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `fk_device` int(9) unsigned NOT NULL,
  `message` varchar(255) NOT NULL,
  `delivery` datetime NOT NULL,
  `status` enum('queued','delivered','failed') CHARACTER SET latin1 NOT NULL DEFAULT 'queued',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pid`),
  KEY `fk_device` (`fk_device`),
  KEY `status` (`status`),
  KEY `created` (`created`),
  KEY `modified` (`modified`),
  KEY `message` (`message`),
  KEY `delivery` (`delivery`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Messages to push to APNS' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `apns_messages`
--


-- --------------------------------------------------------

--
-- Table structure for table `battle_history`
--

CREATE TABLE IF NOT EXISTS `battle_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `won` int(1) unsigned NOT NULL DEFAULT '0',
  `bounty` int(1) unsigned DEFAULT '0',
  `damage_taken` int(11) unsigned DEFAULT '0',
  `cash_lost` int(11) unsigned DEFAULT '0',
  `exp_gained` int(11) unsigned DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `opponent_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `battle_history`
--

INSERT INTO `battle_history` (`id`, `user_id`, `won`, `bounty`, `damage_taken`, `cash_lost`, `exp_gained`, `date`, `opponent_id`) VALUES
(1, 51, 1, 0, 15, 5834, 0, '2011-10-25 12:32:59', 35),
(2, 51, 1, 0, 15, 7001, 0, '2011-10-25 12:33:01', 35),
(3, 51, 1, 0, 15, 8401, 0, '2011-10-25 12:33:02', 35),
(4, 29, 1, 0, 15, 5027, 0, '2011-10-25 13:14:23', 49),
(5, 29, 1, 0, 15, 6032, 0, '2011-10-25 13:14:25', 49),
(6, 52, 0, 0, 15, 6848, 0, '2011-10-25 13:20:21', 29),
(7, 53, 0, 0, 15, 1481, 0, '2011-10-25 13:27:41', 14),
(8, 29, 1, 0, 15, 7465, 0, '2011-10-26 09:38:41', 26),
(9, 29, 1, 0, 15, 8958, 0, '2011-10-26 09:39:50', 11),
(10, 29, 1, 0, 15, 10749, 0, '2011-10-26 09:41:32', 11),
(11, 12, 1, 0, 15, 3795, 0, '2011-10-26 12:28:15', 8),
(12, 12, 1, 0, 15, 4554, 0, '2011-10-26 12:32:13', 8),
(13, 12, 1, 0, 15, 2733, 0, '2011-10-26 12:32:21', 8);

-- --------------------------------------------------------

--
-- Table structure for table `blackmarket`
--

CREATE TABLE IF NOT EXISTS `blackmarket` (
  `bm_id` int(11) NOT NULL AUTO_INCREMENT,
  `bm_type` int(255) NOT NULL,
  `bm_item_type` int(255) NOT NULL,
  `bm_name` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `bm_string` varchar(255) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`bm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `blackmarket`
--


-- --------------------------------------------------------

--
-- Table structure for table `bounties`
--

CREATE TABLE IF NOT EXISTS `bounties` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `requester_id` int(11) unsigned NOT NULL,
  `target_id` int(11) unsigned NOT NULL,
  `payment` int(11) NOT NULL,
  `is_complete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`,`requester_id`,`target_id`),
  KEY `requester_id` (`requester_id`),
  KEY `target_id` (`target_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=93 ;

--
-- Dumping data for table `bounties`
--

INSERT INTO `bounties` (`id`, `requester_id`, `target_id`, `payment`, `is_complete`) VALUES
(1, 10, 5, 10, 0),
(2, 10, 5, 10, 0),
(3, 10, 1, 100, 0),
(4, 18, 10, 50, 0),
(5, 10, 2, 100, 1),
(6, 10, 5, 100, 0),
(7, 10, 5, 100, 0),
(8, 10, 5, 100, 0),
(9, 10, 18, 100, 0),
(10, 18, 10, 22, 0),
(11, 18, 10, 3, 0),
(12, 18, 10, 322, 0),
(13, 18, 10, 333, 0),
(14, 18, 10, 37, 0),
(15, 18, 10, 39, 0),
(16, 18, 10, 61, 0),
(17, 18, 10, 61, 0),
(18, 18, 10, 61, 0),
(19, 18, 10, 61, 0),
(20, 18, 10, 61, 0),
(21, 18, 10, 61, 0),
(22, 18, 10, 61, 0),
(23, 18, 10, 61, 0),
(24, 18, 10, 61, 0),
(25, 18, 10, 61, 0),
(26, 18, 10, 99, 0),
(27, 18, 10, 15, 0),
(28, 18, 10, 15, 0),
(29, 18, 10, 12, 0),
(30, 18, 10, 3, 0),
(31, 18, 10, 3213, 0),
(32, 18, 10, 3213, 0),
(33, 18, 10, 3213, 0),
(34, 18, 10, 3213, 0),
(35, 18, 10, 3213, 0),
(36, 18, 10, 3213, 0),
(37, 18, 10, 3213, 0),
(38, 18, 10, 3213, 0),
(39, 18, 10, 3213, 0),
(40, 18, 10, 3213, 0),
(41, 18, 10, 3213, 0),
(42, 18, 10, 3213, 0),
(43, 18, 10, 3213, 0),
(44, 18, 10, 3213, 0),
(45, 18, 10, 3213, 0),
(46, 18, 10, 3213, 0),
(47, 18, 1, 123, 0),
(48, 18, 1, 11, 0),
(49, 18, 1, 11, 0),
(50, 18, 1, 123, 0),
(51, 18, 1, 123, 0),
(52, 18, 1, 1, 0),
(53, 18, 1, 1234, 0),
(54, 18, 1, 1234, 0),
(55, 18, 1, 1234, 0),
(56, 18, 1, 10101, 0),
(57, 18, 1, 11, 0),
(58, 18, 18, 12, 0),
(59, 18, 18, 11, 0),
(60, 18, 10, 3, 0),
(61, 18, 10, 31, 0),
(62, 18, 10, 31, 0),
(63, 18, 10, 31, 0),
(64, 18, 10, 31, 0),
(65, 18, 10, 31, 0),
(66, 18, 10, 31, 0),
(67, 18, 10, 31, 0),
(68, 18, 10, 31, 0),
(69, 18, 10, 22, 0),
(70, 18, 10, 12, 0),
(71, 18, 10, 8, 0),
(72, 18, 1, 12341234, 0),
(73, 18, 1, 999, 0),
(74, 18, 18, 129, 0),
(75, 18, 10, 1, 0),
(76, 18, 4, 12, 0),
(77, 18, 10, 1, 0),
(78, 18, 10, 12, 0),
(79, 18, 10, 100000, 0),
(80, 18, 1, 10, 0),
(81, 18, 1, 10, 0),
(82, 18, 1, 10, 0),
(83, 18, 1, 90, 0),
(84, 18, 1, 10, 0),
(85, 18, 1, 10, 0),
(86, 24, 2, 10, 1),
(87, 51, 34, 500, 0),
(88, 29, 49, 200, 1),
(89, 11, 22, 6546, 0),
(90, 11, 1, 34, 0),
(91, 29, 49, 456, 0),
(92, 29, 27, 12, 0);

-- --------------------------------------------------------

--
-- Table structure for table `broadcast`
--

CREATE TABLE IF NOT EXISTS `broadcast` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `content` varchar(250) NOT NULL,
  `time_posted` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

--
-- Dumping data for table `broadcast`
--

INSERT INTO `broadcast` (`id`, `sender_id`, `content`, `time_posted`) VALUES
(49, 51, 'Test broad cast', '0000-00-00 00:00:00'),
(50, 52, 'test', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`) VALUES
(1, 'East'),
(2, 'West'),
(3, 'North'),
(4, 'South');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `comments` varchar(250) NOT NULL,
  `time_posted` datetime DEFAULT NULL,
  `type` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `sender_id`, `receiver_id`, `comments`, `time_posted`, `type`) VALUES
(8, 31, 29, 'hey there nigga.. we got a beating tonight', '2011-10-19 17:59:07', 2),
(14, 52, 29, 'Pass commetnasdf asdf asdf asdf asdffffffffffffffffffffffffffffffffffffffffffffffffffffffffffasdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdfasd', '0000-00-00 00:00:00', 1),
(15, 29, 26, 'test ', '0000-00-00 00:00:00', 1),
(16, 29, 11, 'test 11 post comment', '0000-00-00 00:00:00', 1),
(17, 29, 11, 'new comment on test 11', '0000-00-00 00:00:00', 1),
(18, 29, 11, 'tesdf asdfasdfasdf asdf ', '0000-00-00 00:00:00', 1),
(19, 29, 11, 'asdf asdfa sdf asdf', '0000-00-00 00:00:00', 1),
(20, 29, 25, 'casdf', '0000-00-00 00:00:00', 1),
(21, 29, 25, 'asdf asdf', '0000-00-00 00:00:00', 1),
(22, 12, 25, 'asdfas df', '0000-00-00 00:00:00', 1),
(23, 12, 25, 'azsdf asdf ', '0000-00-00 00:00:00', 1),
(24, 29, 25, 'asdf \r\nasdf asdf', '0000-00-00 00:00:00', 1),
(25, 29, 25, '', '0000-00-00 00:00:00', 1),
(26, 29, 25, 'asdf asd', '0000-00-00 00:00:00', 1),
(27, 29, 25, '', '0000-00-00 00:00:00', 1),
(28, 29, 25, '', '0000-00-00 00:00:00', 1),
(29, 29, 25, '', '0000-00-00 00:00:00', 1),
(30, 29, 25, '', '0000-00-00 00:00:00', 1),
(31, 29, 25, 'asdf asd', '0000-00-00 00:00:00', 1),
(32, 29, 25, 'asdf ', '0000-00-00 00:00:00', 1),
(33, 29, 25, 'asdf asdf ', '0000-00-00 00:00:00', 1),
(34, 29, 25, 'asdf asdf asd', '0000-00-00 00:00:00', 1),
(35, 29, 25, 'asdf asdf asd', '0000-00-00 00:00:00', 1),
(36, 29, 25, '', '0000-00-00 00:00:00', 1),
(37, 29, 25, 'asdf asdf ', '0000-00-00 00:00:00', 1),
(38, 29, 25, '', '0000-00-00 00:00:00', 1),
(39, 12, 8, 'asdf asdfas dfffffffffffffffffffffffffffffffffffffffasdf asdfasdf asdf asdfasdf asdf asdf asdf asdf', '0000-00-00 00:00:00', 1),
(40, 12, 8, 'asdf asdf asdf', '0000-00-00 00:00:00', 1),
(41, 12, 8, 'sdf asdf asd', '0000-00-00 00:00:00', 1),
(42, 12, 8, 'asdf asdf asdf', '0000-00-00 00:00:00', 1),
(43, 12, 52, 'asdf asdf ', '0000-00-00 00:00:00', 1),
(44, 12, 52, 'asdf ', '0000-00-00 00:00:00', 1),
(45, 12, 52, 'asd', '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` int(11) unsigned NOT NULL,
  `atk_boost` int(11) unsigned NOT NULL,
  `def_boost` int(11) unsigned NOT NULL,
  `upkeep` int(11) unsigned DEFAULT '0',
  `is_special` tinyint(1) unsigned DEFAULT '0',
  `min_level` int(11) unsigned NOT NULL,
  `price` int(11) unsigned NOT NULL,
  `chance_of_loss` double unsigned DEFAULT '0',
  `image_url` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `type`, `atk_boost`, `def_boost`, `upkeep`, `is_special`, `min_level`, `price`, `chance_of_loss`, `image_url`) VALUES
(1, 'Assault Rifle', 1, 3, 4, 0, 0, 1, 70, 0, 'img/assaultrifle.png'),
(2, 'Uzi Rifle', 1, 2, 5, 1000, 0, 3, 80, 0.7, 'img/uzi.png'),
(3, 'maserati', 3, 4, 4, 4, 0, 250, 100, 0.6, NULL),
(4, 'riot shield', 2, 0, 10, 0, 0, 67, 120, 0.3, NULL),
(5, 'mini gun', 1, 7, 7, 200, 0, 200, 2000, 0, NULL),
(6, 'mini gun 2', 1, 2, 2, 2, 0, 200, 100, 0, NULL),
(7, 'mini gun 3', 1, 7, 7, 7, 0, 201, 20000, 0, NULL),
(8, 'helmet', 2, 2, 2, 2, 0, 67, 100, 0, NULL),
(9, 'kevlar', 2, 100, 12, 0, 0, 100, 1000, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `level_experience_points`
--

CREATE TABLE IF NOT EXISTS `level_experience_points` (
  `level_id` int(11) NOT NULL DEFAULT '0',
  `required_experience_points` int(11) DEFAULT NULL,
  PRIMARY KEY (`level_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `level_experience_points`
--

INSERT INTO `level_experience_points` (`level_id`, `required_experience_points`) VALUES
(2, 15),
(3, 33),
(4, 57),
(5, 92),
(6, 143),
(7, 221),
(8, 341),
(9, 524),
(10, 749),
(11, 1001),
(12, 1269),
(13, 1548),
(14, 1838),
(15, 2138),
(16, 2448),
(17, 2769),
(18, 3101),
(19, 3443),
(20, 3797),
(21, 4163),
(22, 4541),
(23, 4931),
(24, 5333),
(25, 5747),
(26, 6173),
(27, 6611),
(28, 7061),
(29, 7523),
(30, 7998),
(31, 8487),
(32, 8990),
(33, 9506),
(34, 10035),
(35, 10578),
(36, 11135),
(37, 11705),
(38, 12288),
(39, 12885),
(40, 13497),
(41, 14124),
(42, 14766),
(43, 15423),
(44, 16095),
(45, 16782),
(46, 17484),
(47, 18201),
(48, 18933),
(49, 19680),
(50, 20447),
(51, 21233),
(52, 22038),
(53, 22863),
(54, 23708),
(55, 24572),
(56, 25455),
(57, 26358),
(58, 27281),
(59, 28223),
(60, 29184),
(61, 30165),
(62, 31166),
(63, 32186),
(64, 33225),
(65, 34284),
(66, 35363),
(67, 36461),
(68, 37578),
(69, 38715),
(70, 39872),
(71, 41048),
(72, 42243),
(73, 43458),
(74, 44693),
(75, 45947),
(76, 47220),
(77, 48513),
(78, 49826),
(79, 51158),
(80, 52509),
(81, 53880),
(82, 55271),
(83, 56681),
(84, 58110),
(85, 59559),
(86, 61028),
(87, 62516),
(88, 64023),
(89, 65550),
(90, 67097),
(91, 68663),
(92, 70248),
(93, 71853),
(94, 73478),
(95, 75122),
(96, 76785),
(97, 78468),
(98, 80171),
(99, 81893),
(100, 83643),
(101, 85422),
(102, 87230),
(103, 89066),
(104, 90930),
(105, 92823),
(106, 94745),
(107, 96695),
(108, 98673),
(109, 100680),
(110, 102716),
(111, 104780),
(112, 106872),
(113, 108993),
(114, 111143),
(115, 113321),
(116, 115527),
(117, 117762),
(118, 120026),
(119, 122318),
(120, 124638),
(121, 126987),
(122, 129365),
(123, 131771),
(124, 134205),
(125, 136668),
(126, 139160),
(127, 141680),
(128, 144228),
(129, 146805),
(130, 149411),
(131, 152045),
(132, 154707),
(133, 157398),
(134, 160118),
(135, 162866),
(136, 165642),
(137, 168447),
(138, 171281),
(139, 174143),
(140, 177033),
(141, 179952),
(142, 182900),
(143, 185876),
(144, 188880),
(145, 191913),
(146, 194975),
(147, 198065),
(148, 201183),
(149, 204330),
(150, 207515),
(151, 210737),
(152, 213996),
(153, 217293),
(154, 220628),
(155, 224000),
(156, 227409),
(157, 230856),
(158, 234341),
(159, 237863),
(160, 241422),
(161, 245019),
(162, 248654),
(163, 252326),
(164, 256035),
(165, 259782),
(166, 263567),
(167, 267389),
(168, 271248),
(169, 275145),
(170, 279080),
(171, 283052),
(172, 287061),
(173, 291108),
(174, 295193),
(175, 299315),
(176, 303474),
(177, 307671),
(178, 311906),
(179, 316178),
(180, 320487),
(181, 324834),
(182, 329219),
(183, 333641),
(184, 338100),
(185, 342597),
(186, 347132),
(187, 351704),
(188, 356313),
(189, 360960),
(190, 365645),
(191, 370367),
(192, 375126),
(193, 379923),
(194, 384758),
(195, 389630),
(196, 394539),
(197, 399486),
(198, 404471),
(199, 409493),
(200, 414561),
(201, 419828),
(202, 425246),
(203, 430815),
(204, 436536),
(205, 442409),
(206, 448433),
(207, 454608),
(208, 460935),
(209, 467414),
(210, 474044),
(211, 480825),
(212, 487758),
(213, 494843),
(214, 502079),
(215, 509466),
(216, 517005),
(217, 524696),
(218, 532538),
(219, 540531),
(220, 548676),
(221, 556973),
(222, 565421),
(223, 574020),
(224, 582771),
(225, 591674),
(226, 600881),
(227, 610392),
(228, 620208),
(229, 630329),
(230, 640754),
(231, 651483),
(232, 662517),
(233, 673856),
(234, 685499),
(235, 697446),
(236, 709698),
(237, 722255),
(238, 735116),
(239, 748281),
(240, 761751),
(241, 775526),
(242, 789605),
(243, 803988),
(244, 818676),
(245, 833669),
(246, 848966),
(247, 864567),
(248, 880473),
(249, 896684),
(250, 913199),
(251, 930923),
(252, 949856),
(253, 969998),
(254, 991349),
(255, 1013909),
(256, 1037678),
(257, 1062656),
(258, 1088843),
(259, 1116239),
(260, 1144844),
(261, 1174658),
(262, 1205681),
(263, 1237913),
(264, 1271354),
(265, 1306004),
(266, 1341863),
(267, 1378931),
(268, 1417208),
(269, 1456694),
(270, 1497389),
(271, 1539293),
(272, 1582406),
(273, 1626728),
(274, 1672259),
(275, 1718999),
(276, 1766948),
(277, 1816106),
(278, 1866473),
(279, 1918049),
(280, 1970834),
(281, 2024828),
(282, 2081235),
(283, 2140056),
(284, 2201291),
(285, 2264939),
(286, 2331000),
(287, 2401880),
(288, 2477577),
(289, 2558093),
(290, 2643426),
(291, 2733578),
(292, 2833352),
(293, 2942748),
(294, 3061767),
(295, 3190409),
(296, 3328673),
(297, 3486236),
(298, 3663098),
(299, 3859259),
(300, 4074719);

-- --------------------------------------------------------

--
-- Table structure for table `missions`
--

CREATE TABLE IF NOT EXISTS `missions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `energy_cost` int(11) unsigned NOT NULL,
  `min_cash_gained` int(11) unsigned NOT NULL,
  `max_cash_gained` int(11) unsigned NOT NULL,
  `min_level` int(11) unsigned NOT NULL,
  `loot_item_id` int(11) unsigned NOT NULL,
  `chance_of_loot` double unsigned NOT NULL,
  `exp_gained` int(11) unsigned NOT NULL,
  `rank_one_times` int(11) unsigned NOT NULL,
  `rank_two_times` int(11) unsigned NOT NULL,
  `rank_three_times` int(11) unsigned NOT NULL,
  `city_id` int(11) unsigned NOT NULL,
  `min_agency_size` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `missions`
--

INSERT INTO `missions` (`id`, `name`, `description`, `energy_cost`, `min_cash_gained`, `max_cash_gained`, `min_level`, `loot_item_id`, `chance_of_loot`, `exp_gained`, `rank_one_times`, `rank_two_times`, `rank_three_times`, `city_id`, `min_agency_size`) VALUES
(1, 'Master Brazilian Jujitsu', '', 1, 150, 450, 1, 1, 0.25, 1, 3, 5, 7, 1, 1),
(2, 'Learn Street Combat', '', 3, 450, 1350, 1, 2, 0.25, 4, 3, 5, 6, 1, 1),
(3, 'Hack Encrypted Database', '', 4, 2250, 6750, 2, 3, 0.25, 7, 3, 5, 7, 1, 1),
(4, 'Master Close Combat Weapons', '', 6, 2700, 7200, 2, 4, 0.25, 10, 3, 3, 4, 1, 1),
(5, 'Perfect Pistol Precision', '', 4, 2400, 8100, 3, 3, 0.25, 6, 3, 5, 6, 1, 1),
(6, 'Learn to Interrogate', '', 6, 3600, 10800, 3, 5, 0.25, 9, 3, 5, 6, 1, 1),
(7, 'Complete Obstacle Course', '', 7, 6300, 36000, 4, 0, 0, 10, 3, 5, 4, 1, 1),
(8, 'Blood Test: Kill Dealer', '', 10, 12000, 24000, 5, 0, 0, 15, 3, 3, 4, 1, 1),
(9, 'Perform Drive-By', '', 3, 4500, 19500, 6, 0, 0, 7, 7, 9, 12, 2, 1),
(10, 'Rackateer Dealers', '', 5, 7500, 22500, 6, 6, 0.25, 10, 7, 9, 12, 2, 1),
(11, 'Secure Weapons Shipment', '', 6, 9000, 25500, 7, 0, 0, 16, 7, 12, 17, 2, 1),
(12, 'Eliminate Rival Spies', '', 4, 9000, 37500, 8, 7, 0.25, 22, 11, 18, 23, 2, 1),
(13, 'Snipe: Oil Tycoon', '', 8, 12000, 28500, 8, 0, 0, 19, 10, 12, 14, 2, 1),
(14, 'Siege the Docks', '', 4, 9000, 27000, 9, 7, 0.25, 16, 10, 12, 14, 2, 1),
(15, 'Siege the Golden Gate Bridge', '', 3, 7500, 13500, 10, 0, 0, 15, 17, 19, 21, 2, 2),
(16, 'Protect Zark Muckerberg', '', 9, 24000, 72000, 10, 0, 0, 21, 5, 6, 8, 2, 1),
(17, 'Extort Production Studios', '', 5, 15000, 73500, 11, 7, 0.25, 22, 13, 17, 21, 3, 2),
(18, 'Track Rogue Spy', '', 7, 24000, 57000, 11, 0, 0, 19, 13, 17, 21, 3, 3),
(19, 'Bank Heist', '', 5, 19500, 45000, 11, 0, 0, 24, 16, 20, 24, 3, 3),
(20, 'Burn Rival Bunker', '', 10, 36000, 105000, 11, 0, 0, 33, 16, 20, 24, 3, 4),
(21, 'Kill Riot Leader', '', 6, 37500, 105000, 11, 0, 0, 30, 6, 8, 10, 3, 6),
(22, 'Highway Chase', '', 5, 36000, 120000, 17, 0, 0, 31, 18, 22, 26, 3, 7),
(23, 'Raid Enemy Armory', '', 9, 120000, 345000, 19, 0, 0, 34, 8, 10, 12, 3, 8),
(24, 'Kidnap Ashton Butcher', '', 13, 285000, 705000, 20, 8, 0.25, 42, 10, 14, 18, 3, 9),
(25, 'Smuggle Drugs', '', 7, 210000, 840000, 21, 0, 0, 36, 13, 17, 21, 4, 9),
(26, 'Torture Captured Spy', '', 8, 240000, 615000, 22, 0, 0, 46, 13, 17, 21, 4, 10),
(27, 'Snipe Snitch', '', 9, 360000, 1050000, 23, 0, 0, 45, 7, 8, 9, 4, 11),
(28, 'Kill Arms Dealer', '', 11, 510000, 1350000, 24, 0, 0, 40, 8, 10, 12, 4, 12),
(29, 'Transport Warhead', '', 14, 915000, 1200000, 25, 0, 0, 45, 10, 14, 18, 4, 12),
(30, 'Bomb Subway', '', 7, 480000, 2700000, 26, 0, 0, 43, 15, 18, 21, 4, 12),
(31, 'Secure Drug Shipment', '', 6, 420000, 1500000, 27, 0, 0, 43, 20, 24, 28, 4, 14),
(32, 'Siege Sears Tower', '', 20, 2250000, 2640000, 29, 0, 0, 52, 10, 13, 16, 4, 16),
(33, 'Burn the Docks', '', 10, 1065000, 6750000, 31, 9, 0.25, 54, 21, 24, 27, 5, 18),
(34, 'Steal Weapons Cargo', '', 14, 2160000, 1935000, 34, 0, 0, 58, 18, 21, 24, 5, 20),
(35, 'Hi-Jack Jet', '', 23, 2820000, 4950000, 37, 0, 0, 52, 5, 10, 15, 5, 21),
(36, 'Extort Drug Lords', '', 15, 3450000, 7200000, 40, 0, 0, 69, 20, 25, 30, 5, 22),
(37, 'Kidnap Kingpin', '', 9, 2700000, 6570000, 45, 19, 0.25, 75, 15, 20, 25, 5, 22),
(38, 'Secure Enemy Bunker', '', 12, 2400000, 6570000, 50, 0, 0, 84, 10, 12, 14, 5, 23),
(39, 'Assassinate Rival Leader', '', 30, 12150000, 102000000, 54, 0, 0, 91, 10, 12, 14, 5, 25),
(40, 'Burn: Stock Exchange', '', 12, 4500000, 28350000, 60, 0, 0, 102, 12, 16, 20, 6, 27),
(41, 'Destroy: Rival HQ', '', 15, 9000000, 12600000, 60, 0, 0, 108, 20, 25, 30, 6, 29),
(42, 'Kill: Wallstreet Hotshot', '', 17, 24060000, 300000000, 60, 0, 0, 123, 15, 17, 21, 6, 32),
(43, 'Burn: CIA Base', '', 10, 5700000, 9300000, 60, 11, 0.25, 111, 18, 22, 26, 6, 33),
(44, 'Protect: Godfather', '', 18, 9000000, 15450000, 60, 0, 0, 114, 10, 15, 20, 6, 37),
(45, 'Burn: Brooklyn Bridge', '', 28, 16500000, 240000000, 90, 0, 0, 117, 5, 10, 15, 6, 43),
(46, 'Kill: Alex Volkoff', '', 35, 42000000, 1275000000, 100, 0, 0, 138, 7, 10, 15, 6, 47);

-- --------------------------------------------------------

--
-- Table structure for table `missions_itemreqs`
--

CREATE TABLE IF NOT EXISTS `missions_itemreqs` (
  `mission_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  `item_quantity` int(11) unsigned DEFAULT '1',
  PRIMARY KEY (`mission_id`,`item_id`),
  KEY `hi` (`mission_id`),
  KEY `hi2` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `missions_itemreqs`
--


-- --------------------------------------------------------

--
-- Table structure for table `realestate`
--

CREATE TABLE IF NOT EXISTS `realestate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `income` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `min_level` int(11) NOT NULL,
  `image_url` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `realestate`
--

INSERT INTO `realestate` (`id`, `name`, `income`, `price`, `min_level`, `image_url`) VALUES
(1, 'Conrad''s House', 300, 50, 1, ''),
(2, 'Lemonade Stand', 100, 60, 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `soldier_code`
--

CREATE TABLE IF NOT EXISTS `soldier_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(6) NOT NULL COMMENT 'all active codes to be assigned to users.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2002 ;

--
-- Dumping data for table `soldier_code`
--

INSERT INTO `soldier_code` (`id`, `code`) VALUES
(1, 'EU4S96'),
(2, 'L0GWZK'),
(3, 'S507BR'),
(4, 'JT0VSG'),
(5, '4VW90K'),
(6, '5PLI49'),
(7, '5IKT01'),
(8, '1865J7'),
(9, '2I8N4E'),
(10, '460W70'),
(11, '7153JS'),
(12, '7464V9'),
(13, '39761B'),
(14, 'FJXM9T'),
(15, '2A6QBK'),
(16, '16ZI1S'),
(17, '8QM4H0'),
(18, '861FXB'),
(19, '1A54C1'),
(20, 'L56J3B'),
(21, 'PL08S0'),
(22, '257B79'),
(23, 'K12V4A'),
(24, 'OY6M3A'),
(25, 'T3L7N2'),
(26, '5ARZ44'),
(27, '9H88E9'),
(28, 'NZ072O'),
(29, 'G2636M'),
(30, 'SBYZ95'),
(31, '85GLFA'),
(32, 'I57096'),
(33, 'CJ7M1V'),
(34, '67O0I2'),
(35, '19N6Q1'),
(36, 'Z7T4MK'),
(37, '2O72Y2'),
(38, '5952OM'),
(39, '7KQ4JJ'),
(40, '4P1987'),
(41, 'UU5OC7'),
(42, 'EV85Q3'),
(43, 'R95U46'),
(44, 'VZ85CQ'),
(45, 'RE1M6K'),
(46, '6HMZ5U'),
(47, 'ZQK78C'),
(48, 'A99111'),
(49, 'W6P9MC'),
(50, 'B34LH8'),
(51, 'PJAIQ6'),
(52, 'BOI90F'),
(53, '41X1R9'),
(54, 'GYU0W4'),
(55, 'P2NNTG'),
(56, 'Y708W9'),
(57, 'I7BV2V'),
(58, '31MRI7'),
(59, '91822Y'),
(60, 'Q4184F'),
(61, '550CC8'),
(62, '1ZNL55'),
(63, 'OEAPA7'),
(64, '2868YC'),
(65, '13X03X'),
(66, 'Y383SZ'),
(67, '5H05E6'),
(68, '684A25'),
(70, '5KPG6A'),
(71, '9N80RP'),
(72, '83OG36'),
(73, 'JHD58J'),
(74, 'Y9Q256'),
(75, 'QVPFNR'),
(76, 'ZN063H'),
(77, '9J52B7'),
(78, '2EALE8'),
(79, '62D96V'),
(80, '92801N'),
(81, '957NRF'),
(82, 'IZU6RQ'),
(83, 'B7NGF0'),
(84, '6P0O83'),
(85, '28736T'),
(86, 'G38ZAA'),
(87, 'U371C4'),
(88, 'INOEJ8'),
(89, 'X27884'),
(90, 'BIH410'),
(91, 'W98RH9'),
(92, '7039SW'),
(93, 'XT48NE'),
(94, '78B92P'),
(95, '2777W4'),
(97, 'F3B55A'),
(98, 'E0EN17'),
(99, 'A65L70'),
(100, '2WD69S');

-- --------------------------------------------------------

--
-- Table structure for table `soldier_code_backup`
--

CREATE TABLE IF NOT EXISTS `soldier_code_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(6) NOT NULL COMMENT 'all soldier codes generated till date consmued and not consumed',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2001 ;

--
-- Dumping data for table `soldier_code_backup`
--

INSERT INTO `soldier_code_backup` (`id`, `code`) VALUES
(1, 'L0GWZK'),
(2, 'S507BR'),
(3, 'JT0VSG'),
(4, '4VW90K'),
(5, '5PLI49'),
(6, '5IKT01'),
(7, '1865J7'),
(8, '2I8N4E'),
(9, '460W70'),
(10, '7153JS'),
(11, '7464V9'),
(12, '39761B'),
(13, 'FJXM9T'),
(14, '2A6QBK'),
(15, '16ZI1S'),
(16, '8QM4H0'),
(17, '861FXB'),
(18, '1A54C1'),
(19, 'L56J3B'),
(20, 'PL08S0'),
(21, '257B79'),
(22, 'K12V4A'),
(23, 'OY6M3A'),
(24, 'T3L7N2'),
(25, '5ARZ44'),
(26, '9H88E9'),
(27, 'NZ072O'),
(28, 'G2636M'),
(29, 'SBYZ95'),
(30, '85GLFA'),
(31, 'I57096'),
(32, 'CJ7M1V'),
(33, '67O0I2'),
(34, '19N6Q1'),
(35, 'Z7T4MK'),
(36, '2O72Y2'),
(37, '5952OM'),
(38, '7KQ4JJ'),
(39, '4P1987'),
(40, 'UU5OC7'),
(41, 'EV85Q3'),
(42, 'R95U46'),
(43, 'VZ85CQ'),
(44, 'RE1M6K'),
(45, '6HMZ5U'),
(46, 'ZQK78C'),
(47, 'A99111'),
(48, 'W6P9MC'),
(49, 'B34LH8'),
(50, 'PJAIQ6'),
(51, 'BOI90F'),
(52, '41X1R9'),
(53, 'GYU0W4'),
(54, 'P2NNTG'),
(55, 'Y708W9'),
(56, 'I7BV2V'),
(57, '31MRI7'),
(58, '91822Y'),
(59, 'Q4184F'),
(60, '550CC8'),
(61, '1ZNL55'),
(62, 'OEAPA7'),
(63, '2868YC'),
(64, '13X03X'),
(65, 'Y383SZ'),
(66, '5H05E6'),
(67, '684A25'),
(68, '0GP4X0'),
(69, '5KPG6A'),
(70, '9N80RP'),
(71, '83OG36'),
(72, 'JHD58J'),
(73, 'Y9Q256'),
(74, 'QVPFNR'),
(75, 'ZN063H'),
(76, '9J52B7'),
(77, '2EALE8'),
(78, '62D96V'),
(79, '92801N'),
(80, '957NRF'),
(81, 'IZU6RQ'),
(82, 'B7NGF0'),
(83, '6P0O83'),
(84, '28736T'),
(85, 'G38ZAA'),
(86, 'U371C4'),
(87, 'INOEJ8'),
(88, 'X27884'),
(89, 'BIH410'),
(90, 'W98RH9'),
(91, '7039SW'),
(92, 'XT48NE'),
(93, '78B92P'),
(94, '2777W4'),
(95, '01PSY4'),
(96, 'F3B55A'),
(97, 'E0EN17'),
(98, 'A65L70'),
(99, '2WD69S'),
(100, 'X94789');

-- --------------------------------------------------------

--
-- Table structure for table `users_achievements`
--

CREATE TABLE IF NOT EXISTS `users_achievements` (
  `user_id` int(11) NOT NULL,
  `victor_level` tinyint(1) NOT NULL DEFAULT '0',
  `fall_guy_level` tinyint(1) NOT NULL DEFAULT '0',
  `marksman_level` tinyint(1) NOT NULL DEFAULT '0',
  `master_level` tinyint(1) NOT NULL DEFAULT '0',
  `monster_level` tinyint(1) NOT NULL DEFAULT '0',
  `prodigy_level` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_achievements`
--


-- --------------------------------------------------------

--
-- Table structure for table `users_cities`
--

CREATE TABLE IF NOT EXISTS `users_cities` (
  `user_id` int(11) unsigned NOT NULL,
  `city_id` int(11) unsigned NOT NULL,
  `rank_avail` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_cities`
--

INSERT INTO `users_cities` (`user_id`, `city_id`, `rank_avail`) VALUES
(10, 1, 4),
(18, 1, 1),
(18, 2, 4),
(23, 1, 1),
(24, 1, 1),
(25, 1, 1),
(26, 1, 1),
(27, 1, 1),
(28, 1, 1),
(29, 1, 4),
(29, 2, 2),
(29, 3, 1),
(29, 4, 1),
(30, 1, 2),
(31, 1, 1),
(32, 1, 1),
(33, 1, 1),
(34, 1, 1),
(35, 1, 1),
(36, 1, 1),
(37, 1, 1),
(38, 1, 1),
(39, 1, 1),
(40, 1, 1),
(41, 1, 1),
(42, 1, 1),
(43, 1, 2),
(43, 2, 1),
(43, 3, 1),
(43, 4, 1),
(44, 1, 1),
(44, 2, 1),
(44, 3, 1),
(44, 4, 1),
(45, 1, 1),
(45, 2, 1),
(45, 3, 1),
(45, 4, 1),
(46, 1, 1),
(46, 2, 1),
(46, 3, 1),
(46, 4, 1),
(47, 1, 1),
(47, 2, 1),
(47, 3, 1),
(47, 4, 1),
(48, 1, 1),
(48, 2, 1),
(48, 3, 1),
(48, 4, 1),
(49, 1, 1),
(49, 2, 1),
(49, 3, 1),
(49, 4, 1),
(50, 1, 1),
(50, 2, 1),
(50, 3, 1),
(50, 4, 1),
(51, 1, 1),
(51, 2, 1),
(51, 3, 1),
(51, 4, 1),
(52, 1, 1),
(52, 2, 1),
(52, 3, 1),
(52, 4, 1),
(53, 1, 1),
(53, 2, 1),
(53, 3, 1),
(53, 4, 1),
(54, 1, 1),
(54, 2, 1),
(54, 3, 1),
(54, 4, 1),
(55, 1, 1),
(55, 2, 1),
(55, 3, 1),
(55, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_dailybonuses`
--

CREATE TABLE IF NOT EXISTS `users_dailybonuses` (
  `user_id` int(11) unsigned NOT NULL,
  `day1` int(11) unsigned DEFAULT '0',
  `day2` int(11) unsigned DEFAULT '0',
  `day3` int(11) unsigned DEFAULT '0',
  `day4` int(11) unsigned DEFAULT '0',
  `day5` int(11) unsigned DEFAULT '0',
  `day6` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_dailybonuses`
--

INSERT INTO `users_dailybonuses` (`user_id`, `day1`, `day2`, `day3`, `day4`, `day5`, `day6`) VALUES
(5, 1000, 0, 0, 0, 0, 0),
(10, 7145, 0, 0, 0, 0, 0),
(11, 1000, 0, 0, 0, 0, 0),
(12, 1000, 7326, 917, 0, 0, 0),
(18, 0, 0, 0, 0, 0, 0),
(19, 1000, 0, 0, 0, 0, 0),
(21, 0, 4889, 6924, 0, 0, 0),
(26, 0, 6432, 0, 0, 0, 0),
(29, 0, 14476, 15003, 9456, 4880, 4396),
(31, 0, 9483, 0, 0, 0, 0),
(43, 8391, 0, 0, 0, 0, 0),
(46, 6549, 2428, 0, 0, 0, 0),
(52, 6725, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_items`
--

CREATE TABLE IF NOT EXISTS `users_items` (
  `user_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  `quantity` int(11) unsigned NOT NULL,
  `is_looted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`item_id`),
  KEY `user_id` (`user_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_items`
--

INSERT INTO `users_items` (`user_id`, `item_id`, `quantity`, `is_looted`) VALUES
(10, 1, 29, 0),
(10, 2, 4, 0),
(12, 1, 1, 0),
(13, 1, 3, 0),
(13, 2, 1, 0),
(18, 1, 1, 0),
(18, 2, 64, 0),
(21, 1, 13, 0),
(21, 2, 5, 0),
(24, 1, 2, 0),
(29, 1, 815, 0),
(29, 2, 164, 0),
(29, 4, 3, 1),
(40, 1, 3, 0),
(41, 1, 1, 0),
(42, 1, 1, 0),
(43, 1, 6, 0),
(43, 2, 1, 0),
(43, 3, 2, 0),
(49, 1, 9, 0),
(50, 2, 1, 0),
(51, 1, 5, 0),
(51, 2, 1, 0),
(51, 3, 1, 0),
(52, 1, 507, 0),
(52, 2, 1, 0),
(53, 1, 2, 0),
(53, 2, 2, 0),
(55, 1, 2, 0),
(55, 2, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_missions`
--

CREATE TABLE IF NOT EXISTS `users_missions` (
  `user_id` int(11) unsigned NOT NULL,
  `mission_id` int(11) unsigned NOT NULL,
  `times_complete` int(11) unsigned DEFAULT '1',
  `rank_one_times` int(11) unsigned NOT NULL DEFAULT '1',
  `rank_two_times` int(11) unsigned NOT NULL DEFAULT '0',
  `rank_three_times` int(11) unsigned NOT NULL DEFAULT '0',
  `curr_rank` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`,`mission_id`),
  KEY `hi` (`user_id`),
  KEY `hello` (`mission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_missions`
--

INSERT INTO `users_missions` (`user_id`, `mission_id`, `times_complete`, `rank_one_times`, `rank_two_times`, `rank_three_times`, `curr_rank`) VALUES
(29, 1, 1, 1, 0, 0, 1),
(51, 1, 8, 8, 0, 0, 2),
(51, 2, 3, 3, 0, 0, 2),
(51, 3, 4, 4, 0, 0, 2),
(51, 7, 1, 1, 0, 0, 1),
(51, 9, 1, 1, 0, 0, 1),
(52, 1, 4, 4, 0, 0, 2),
(52, 2, 4, 4, 0, 0, 2),
(52, 3, 5, 5, 0, 0, 2),
(52, 4, 1, 1, 0, 0, 1),
(52, 7, 2, 2, 0, 0, 1),
(53, 1, 5, 5, 0, 0, 2),
(53, 2, 8, 8, 0, 0, 2),
(53, 3, 2, 2, 0, 0, 1),
(55, 1, 33, 33, 0, 0, 2),
(55, 2, 11, 11, 0, 0, 2),
(55, 3, 1, 1, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users_realestates`
--

CREATE TABLE IF NOT EXISTS `users_realestates` (
  `user_id` int(10) unsigned NOT NULL,
  `realestate_id` int(10) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`realestate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_realestates`
--

INSERT INTO `users_realestates` (`user_id`, `realestate_id`, `quantity`) VALUES
(12, 1, 4),
(12, 2, 2),
(52, 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `user_blackmarket_items`
--

CREATE TABLE IF NOT EXISTS `user_blackmarket_items` (
  `user_id` int(11) unsigned NOT NULL,
  `bm_id` int(11) unsigned NOT NULL,
  `quantity` int(255) DEFAULT '0',
  PRIMARY KEY (`user_id`,`bm_id`),
  KEY `user_id` (`user_id`),
  KEY `bm_id` (`bm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_blackmarket_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `user_looted_items`
--

CREATE TABLE IF NOT EXISTS `user_looted_items` (
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `is_special` tinyint(4) NOT NULL,
  PRIMARY KEY (`user_id`,`item_id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_looted_items`
--

INSERT INTO `user_looted_items` (`user_id`, `item_id`, `quantity`, `is_special`) VALUES
(55, 2, 4, 0),
(55, 1, 11, 0),
(55, 3, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_timers`
--

CREATE TABLE IF NOT EXISTS `user_timers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `energy_timer` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `health_timer` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `income_timer` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `stamina_timer` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user_timers`
--

INSERT INTO `user_timers` (`id`, `user_id`, `energy_timer`, `health_timer`, `income_timer`, `stamina_timer`) VALUES
(4, 55, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agencies`
--
ALTER TABLE `agencies`
  ADD CONSTRAINT `user_one_id` FOREIGN KEY (`user_one_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_two_id` FOREIGN KEY (`user_two_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bounties`
--
ALTER TABLE `bounties`
  ADD CONSTRAINT `requester_id` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `target_id` FOREIGN KEY (`target_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `missions_itemreqs`
--
ALTER TABLE `missions_itemreqs`
  ADD CONSTRAINT `missions_itemreqs_ibfk_1` FOREIGN KEY (`mission_id`) REFERENCES `missions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `missions_itemreqs_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_items`
--
ALTER TABLE `users_items`
  ADD CONSTRAINT `item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_missions`
--
ALTER TABLE `users_missions`
  ADD CONSTRAINT `users_missions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_missions_ibfk_2` FOREIGN KEY (`mission_id`) REFERENCES `missions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
