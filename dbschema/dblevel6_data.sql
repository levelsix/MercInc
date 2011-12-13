# --------------------------------------------------------
# Host:                         50.18.188.59
# Server version:               5.1.54-1ubuntu4
# Server OS:                    debian-linux-gnu
# HeidiSQL version:             6.0.0.3603
# Date/time:                    2011-10-03 15:45:46
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
# Dumping data for table mercenaryinc.achievements: ~2 rows (approximately)
/*!40000 ALTER TABLE `achievements` DISABLE KEYS */;
INSERT INTO `achievements` (`id`, `name`, `req_type`, `req_number`) VALUES
	(1, 'missions1', 'missions', 10),
	(2, 'missions2', 'missions', 50);
/*!40000 ALTER TABLE `achievements` ENABLE KEYS */;

# Dumping data for table mercenaryinc.agencies: ~8 rows (approximately)
/*!40000 ALTER TABLE `agencies` DISABLE KEYS */;
INSERT INTO `agencies` (`user_one_id`, `user_two_id`, `accepted`) VALUES
	(5, 10, 1),
	(10, 1, 1),
	(10, 4, 1),
	(10, 12, 1),
	(10, 18, 1),
	(11, 10, 1),
	(18, 10, 1),
	(18, 11, 1);
/*!40000 ALTER TABLE `agencies` ENABLE KEYS */;

# Dumping data for table mercenaryinc.battle_history: ~0 rows (approximately)
/*!40000 ALTER TABLE `battle_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `battle_history` ENABLE KEYS */;

# Dumping data for table mercenaryinc.bounties: ~85 rows (approximately)
/*!40000 ALTER TABLE `bounties` DISABLE KEYS */;
INSERT INTO `bounties` (`id`, `requester_id`, `target_id`, `payment`, `is_complete`) VALUES
	(1, 10, 5, 10, 0),
	(2, 10, 5, 10, 0),
	(3, 10, 1, 100, 0),
	(4, 18, 10, 50, 0),
	(5, 10, 2, 100, 0),
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
	(85, 18, 1, 10, 0);
/*!40000 ALTER TABLE `bounties` ENABLE KEYS */;

# Dumping data for table mercenaryinc.items: ~4 rows (approximately)
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` (`id`, `name`, `type`, `atk_boost`, `def_boost`, `upkeep`, `min_level`, `price`, `chance_of_loss`) VALUES
	(1, 'buster sword', 1, 3, 4, 0, 1, 70, 0),
	(2, 'masamune', 1, 2, 5, 0, 7, 80, 0.7),
	(3, 'maserati', 3, 4, 4, 4, 2239, 100, 0.6),
	(4, 'riot shield', 2, 0, 10, 0, 67, 120, 0.3);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;

# Dumping data for table mercenaryinc.missions: ~4 rows (approximately)
/*!40000 ALTER TABLE `missions` DISABLE KEYS */;
INSERT INTO `missions` (`id`, `name`, `description`, `energy_cost`, `min_cash_gained`, `max_cash_gained`, `min_level`, `loot_item_id`, `chance_of_loot`, `exp_gained`, `rank_one_times`, `rank_two_times`, `rank_three_times`, `city_id`, `min_agency_size`) VALUES
	(1, 'Fledgling Mission', 'Description 1', 20, 400, 500, 1, 1, 0.5, 5, 3, 5, 7, 1, 4),
	(2, 'Mission 2', 'Description 2', 30, 500, 600, 2, 2, 0.9, 6, 3, 4, 5, 2, 0),
	(3, 'third mission', 'Description 3', 10, 300, 400, 6, 2, 0.3, 9, 3, 5, 7, 2, 0),
	(4, 'First mission', 'Basic training', 10, 200, 300, 1, 1, 0.3, 4, 3, 4, 5, 1, 0);
/*!40000 ALTER TABLE `missions` ENABLE KEYS */;

# Dumping data for table mercenaryinc.missions_itemreqs: ~3 rows (approximately)
/*!40000 ALTER TABLE `missions_itemreqs` DISABLE KEYS */;
INSERT INTO `missions_itemreqs` (`mission_id`, `item_id`, `item_quantity`) VALUES
	(1, 1, 4),
	(1, 2, 1),
	(2, 2, 1);
/*!40000 ALTER TABLE `missions_itemreqs` ENABLE KEYS */;

# Dumping data for table mercenaryinc.realestate: 2 rows
/*!40000 ALTER TABLE `realestate` DISABLE KEYS */;
INSERT INTO `realestate` (`id`, `name`, `income`, `price`, `min_level`) VALUES
	(1, 'Conrad\'s House', 300, 50, 1),
	(2, 'Lemonade Stand', 100, 60, 2);
/*!40000 ALTER TABLE `realestate` ENABLE KEYS */;

# Dumping data for table mercenaryinc.users: ~42 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `level`, `type`, `attack`, `defense`, `bank_balance`, `cash`, `experience`, `stamina`, `energy`, `skill_points`, `health`, `missions_completed`, `fights_won`, `fights_lost`, `kills`, `deaths`, `income`, `upkeep`, `health_max`, `energy_max`, `stamina_max`, `agency_code`, `agency_size`, `last_login`, `num_consecutive_days_played`, `diamonds`) VALUES
	(1, 'test1', 10, '1', 12, 0, 0, 0, 10, 0, 100, 0, 10, 0, 1, 5, 0, 0, 0, 0, 100, 100, 3, '1', 2, '2011-08-03 23:51:39', 1, 0),
	(2, 'test2', 10, '1', 14, 0, 0, 1000, 2, 0, 100, 0, 625, 0, 1, 28, 0, 0, 0, 0, 100, 100, 3, '2', 1, '2011-08-16 02:47:08', 1, 0),
	(3, 'test3', 10, '1', 15, 4, 0, 0, 10, 0, 100, 0, 100, 0, 6, 0, 0, 0, 0, 0, 100, 100, 3, '3', 1, NULL, 1, 0),
	(4, 'test4', 6, '1', 16, 4, 0, 1000, 0, 0, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '4', 2, '2011-08-17 01:18:35', 1, 0),
	(5, 'test5', 6, '1', 15, 3, 0, 2000, 0, 100, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '5', 2, '2011-08-24 23:55:01', 1, 0),
	(6, 'test6', 1, '1', 12, 10, 0, 0, 0, 100, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '6', 1, NULL, 1, 0),
	(7, 'test7', 1, '1', 12, 10, 0, 0, 0, 100, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '7', 1, NULL, 1, 0),
	(8, 'test8', 1, '1', 12, 10, 0, 0, 0, 100, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '8', 1, NULL, 1, 0),
	(9, 'test9', 1, '1', 12, 10, 0, 0, 0, 100, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '9', 1, NULL, 1, 0),
	(10, 'test10', 12, '1', 30, 15, 2960, 166431, 123, 41, 160, 97, 970, 99, 47, 8, 0, 0, 0, 0, 1000, 100, 5, '10', 7, '2011-10-01 00:15:16', 1, 0),
	(11, 'test11', 11, '1', 12, 10, 0, 1000, 0, 100, 100, 0, 910, 0, 0, 12, 0, 0, 0, 0, 100, 100, 3, '11', 3, '2011-09-19 11:40:38', 1, 0),
	(12, 'test12', 12, '2', 12, 10, 0, 9731, 0, 100, 100, 0, 85, 0, 0, 1, 0, 0, 1400, 0, 100, 100, 3, '12', 2, '2011-09-21 11:22:47', 3, 0),
	(13, 'test13', 1, '2', 13, 9, 0, 0, 0, 100, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '13', 1, NULL, 1, 0),
	(14, 'test14', 1, '2', 12, 10, 0, 0, 0, 100, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '14', 1, NULL, 1, 0),
	(15, 'test15', 7, '2', 12, 10, 0, 0, 0, 100, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '15', 1, NULL, 1, 0),
	(16, 'test16', 1, '2', 12, 10, 0, 0, 0, 3, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '16', 1, NULL, 1, 0),
	(17, 'test17', 1, '2', 16, 13, 0, 0, 0, 3, 100, 41, 100, 0, 0, 0, 0, 0, 0, 0, 102, 101, 4, '17', 1, NULL, 1, 0),
	(18, 'test18', 2305, '2', 12, 15, 210, 2840, 23058, 199, 97100, 6894, 40, 365, 1, 3, 0, 0, 0, 0, 170, 1003, 9, '18', 3, '2011-10-01 00:27:33', 1, 4),
	(19, 'test19', 1, '2', 13, 11, 0, 1000, 0, 3, 100, 7, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 4, '19', 1, '2011-09-03 19:12:22', 1, 0),
	(20, 'Calvin', 1, '2', 14, 8, 0, 1000, 2, 3, 100, 10, 85, 0, 1, 0, 0, 0, 0, 0, 100, 100, 3, '20', 1, '2011-08-11 12:45:43', 1, 0),
	(21, 'Alex', 1, '2', 17, 10, 0, 0, 0, 3, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 130, 101, 4, '21', 1, NULL, 1, 0),
	(22, 'Conrad', 2, '2', 14, 10, 0, 1010, 0, 3, 100, 0, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '22', 1, '2011-08-10 00:31:41', 1, 0),
	(23, 'test23', 1, '2', 12, 10, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '23', 1, '2011-08-07 00:46:50', 1, 0),
	(24, 'test24', 1, '2', 13, 9, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '24', 1, '2011-08-10 07:08:24', 1, 0),
	(25, 'test25', 1, '2', 0, 0, 0, 4441, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '25', 1, '2011-09-20 16:38:30', 1, 0),
	(26, 'test26', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '26', 1, '2011-08-10 07:39:43', 1, 0),
	(27, 'test27', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '27', 1, '2011-08-11 19:41:07', 1, 0),
	(28, 'test28', 1, NULL, 0, 0, 0, 588, 80, 3, 80, 10, 100, 2, 0, 0, 0, 0, 0, 0, 100, 100, 3, '28', 1, '2011-08-11 19:50:33', 1, 0),
	(29, 'test29', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '29', 1, '2011-08-17 06:06:09', 1, 0),
	(30, 'test30', 4, '1', 0, 0, 0, 4458, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '30', 1, '2011-09-20 16:38:43', 1, 0),
	(31, 'test31', 3, '2', 0, 0, 0, 9882, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '31', 1, '2011-09-20 17:11:52', 1, 0),
	(32, 'test32', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '32', 1, '2011-08-25 08:52:19', 0, 0),
	(33, 'test33', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '33', 1, '2011-08-31 08:40:39', 0, 0),
	(34, 'test34', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '34', 1, '2011-09-01 00:15:42', 0, 0),
	(35, 'test35', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '35', 1, '2011-09-04 00:39:28', 0, 0),
	(36, 'test36', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '36', 1, '2011-09-04 01:49:13', 0, 0),
	(37, '18', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '37', 1, '2011-09-11 17:42:51', 0, 0),
	(38, 'ruby', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '38', 1, '2011-09-12 04:00:01', 0, 0),
	(39, 'q', 1, NULL, 0, 0, 0, 0, 0, 3, 100, 10, 100, 0, 0, 0, 0, 0, 0, 0, 100, 100, 3, '39', 1, '2011-09-17 18:04:45', 0, 0),
	(40, 'water', 2, NULL, 0, 0, 0, 1889, 28, 3, 40, 13, 100, 6, 0, 0, 0, 0, 0, 0, 100, 100, 3, '40', 1, '2011-10-01 06:54:28', 0, 0),
	(41, 'Fire', 1, NULL, 0, 0, 0, 295, 4, 3, 90, 10, 100, 1, 0, 0, 0, 0, 0, 0, 100, 100, 3, '41', 1, '2011-10-01 06:56:33', 0, 0),
	(42, 'conrad', 1, NULL, 0, 0, 0, 771, 12, 3, 80, 10, 100, 2, 0, 0, 0, 0, 0, 0, 100, 100, 3, NULL, 1, '2011-10-01 07:19:40', 0, 0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

# Dumping data for table mercenaryinc.users_achievements: ~0 rows (approximately)
/*!40000 ALTER TABLE `users_achievements` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_achievements` ENABLE KEYS */;

# Dumping data for table mercenaryinc.users_cities: ~24 rows (approximately)
/*!40000 ALTER TABLE `users_cities` DISABLE KEYS */;
INSERT INTO `users_cities` (`user_id`, `city_id`, `rank_avail`) VALUES
	(10, 1, 4),
	(10, 2, 1),
	(18, 1, 1),
	(18, 2, 4),
	(23, 1, 1),
	(24, 1, 1),
	(25, 1, 1),
	(26, 1, 1),
	(27, 1, 1),
	(28, 1, 1),
	(29, 1, 1),
	(30, 1, 1),
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
	(42, 1, 1);
/*!40000 ALTER TABLE `users_cities` ENABLE KEYS */;

# Dumping data for table mercenaryinc.users_dailybonuses: ~6 rows (approximately)
/*!40000 ALTER TABLE `users_dailybonuses` DISABLE KEYS */;
INSERT INTO `users_dailybonuses` (`user_id`, `day1`, `day2`, `day3`, `day4`, `day5`, `day6`) VALUES
	(5, 1000, 0, 0, 0, 0, 0),
	(10, 7145, 0, 0, 0, 0, 0),
	(11, 1000, 0, 0, 0, 0, 0),
	(12, 1000, 7326, 917, 0, 0, 0),
	(18, 0, 0, 0, 0, 0, 0),
	(19, 1000, 0, 0, 0, 0, 0);
/*!40000 ALTER TABLE `users_dailybonuses` ENABLE KEYS */;

# Dumping data for table mercenaryinc.users_items: ~8 rows (approximately)
/*!40000 ALTER TABLE `users_items` DISABLE KEYS */;
INSERT INTO `users_items` (`user_id`, `item_id`, `quantity`) VALUES
	(10, 1, 29),
	(10, 2, 4),
	(12, 1, 1),
	(18, 1, 1),
	(18, 2, 64),
	(40, 1, 3),
	(41, 1, 1),
	(42, 1, 1);
/*!40000 ALTER TABLE `users_items` ENABLE KEYS */;

# Dumping data for table mercenaryinc.users_missions: ~10 rows (approximately)
/*!40000 ALTER TABLE `users_missions` DISABLE KEYS */;
INSERT INTO `users_missions` (`user_id`, `mission_id`, `times_complete`, `rank_one_times`, `rank_two_times`, `rank_three_times`, `curr_rank`) VALUES
	(10, 1, 73, 29, 14, 7, 4),
	(10, 2, 6, 6, 0, 0, 2),
	(10, 4, 12, 3, 4, 5, 4),
	(18, 2, 40, 7, 4, 5, 4),
	(18, 3, 16, 3, 5, 7, 4),
	(18, 4, 7, 7, 0, 0, 2),
	(28, 4, 2, 2, 0, 0, 1),
	(40, 4, 6, 6, 0, 0, 2),
	(41, 4, 1, 1, 0, 0, 1),
	(42, 4, 2, 2, 0, 0, 2);
/*!40000 ALTER TABLE `users_missions` ENABLE KEYS */;

# Dumping data for table mercenaryinc.users_realestates: 2 rows
/*!40000 ALTER TABLE `users_realestates` DISABLE KEYS */;
INSERT INTO `users_realestates` (`user_id`, `realestate_id`, `quantity`) VALUES
	(12, 1, 4),
	(12, 2, 2);
/*!40000 ALTER TABLE `users_realestates` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
