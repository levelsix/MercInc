-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 28, 2011 at 12:33 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

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
