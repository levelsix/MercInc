# --------------------------------------------------------
# Host:                         50.18.188.59
# Server version:               5.1.54-1ubuntu4
# Server OS:                    debian-linux-gnu
# HeidiSQL version:             6.0.0.3603
# Date/time:                    2011-10-03 15:42:15
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

# Dumping database structure for mercenaryinc
CREATE DATABASE IF NOT EXISTS `mercenaryinc` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `mercenaryinc`;


# Dumping structure for table mercenaryinc.achievements
CREATE TABLE IF NOT EXISTS `achievements` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `req_type` varchar(45) NOT NULL,
  `req_number` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table mercenaryinc.agencies
CREATE TABLE IF NOT EXISTS `agencies` (
  `user_one_id` int(11) unsigned NOT NULL,
  `user_two_id` int(11) unsigned NOT NULL,
  `accepted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`user_one_id`,`user_two_id`),
  KEY `user_one_id` (`user_one_id`),
  KEY `user_two_id` (`user_two_id`),
  CONSTRAINT `user_one_id` FOREIGN KEY (`user_one_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_two_id` FOREIGN KEY (`user_two_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table mercenaryinc.battle_history
CREATE TABLE IF NOT EXISTS `battle_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `won` int(1) unsigned NOT NULL DEFAULT '0',
  `bounty` int(1) unsigned DEFAULT '0',
  `damage_taken` int(11) unsigned DEFAULT '0',
  `cash_lost` int(11) unsigned DEFAULT '0',
  `exp_gained` int(11) unsigned DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table mercenaryinc.bounties
CREATE TABLE IF NOT EXISTS `bounties` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `requester_id` int(11) unsigned NOT NULL,
  `target_id` int(11) unsigned NOT NULL,
  `payment` int(11) NOT NULL,
  `is_complete` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`,`requester_id`,`target_id`),
  KEY `requester_id` (`requester_id`),
  KEY `target_id` (`target_id`),
  CONSTRAINT `requester_id` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `target_id` FOREIGN KEY (`target_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table mercenaryinc.items
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` int(11) unsigned NOT NULL,
  `atk_boost` int(11) unsigned NOT NULL,
  `def_boost` int(11) unsigned NOT NULL,
  `upkeep` int(11) unsigned DEFAULT '0',
  `min_level` int(11) unsigned NOT NULL,
  `price` int(11) unsigned NOT NULL,
  `chance_of_loss` double unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table mercenaryinc.missions
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table mercenaryinc.missions_itemreqs
CREATE TABLE IF NOT EXISTS `missions_itemreqs` (
  `mission_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  `item_quantity` int(11) unsigned DEFAULT '1',
  PRIMARY KEY (`mission_id`,`item_id`),
  KEY `hi` (`mission_id`),
  KEY `hi2` (`item_id`),
  CONSTRAINT `missions_itemreqs_ibfk_1` FOREIGN KEY (`mission_id`) REFERENCES `missions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `missions_itemreqs_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table mercenaryinc.realestate
CREATE TABLE IF NOT EXISTS `realestate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `income` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `min_level` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table mercenaryinc.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `recruit_code_UNIQUE` (`agency_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table mercenaryinc.users_achievements
CREATE TABLE IF NOT EXISTS `users_achievements` (
  `user_id` int(11) unsigned NOT NULL,
  `achievement_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`achievement_id`),
  KEY `user_id` (`user_id`),
  KEY `achievement_id` (`achievement_id`),
  CONSTRAINT `users_achievements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_achievements_ibfk_2` FOREIGN KEY (`achievement_id`) REFERENCES `achievements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table mercenaryinc.users_cities
CREATE TABLE IF NOT EXISTS `users_cities` (
  `user_id` int(11) unsigned NOT NULL,
  `city_id` int(11) unsigned NOT NULL,
  `rank_avail` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table mercenaryinc.users_dailybonuses
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

# Data exporting was unselected.


# Dumping structure for table mercenaryinc.users_items
CREATE TABLE IF NOT EXISTS `users_items` (
  `user_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  `quantity` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`item_id`),
  KEY `user_id` (`user_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table mercenaryinc.users_missions
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
  KEY `hello` (`mission_id`),
  CONSTRAINT `users_missions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `users_missions_ibfk_2` FOREIGN KEY (`mission_id`) REFERENCES `missions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table mercenaryinc.users_realestates
CREATE TABLE IF NOT EXISTS `users_realestates` (
  `user_id` int(10) unsigned NOT NULL,
  `realestate_id` int(10) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`realestate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# Data exporting was unselected.
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
