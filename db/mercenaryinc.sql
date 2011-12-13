/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50136
Source Host           : localhost:3306
Source Database       : mercenaryinc

Target Server Type    : MYSQL
Target Server Version : 50136
File Encoding         : 65001

Date: 2011-11-02 20:14:46
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `achievements`
-- ----------------------------
DROP TABLE IF EXISTS `achievements`;
CREATE TABLE `achievements` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `req_type` varchar(45) NOT NULL,
  `req_number` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of achievements
-- ----------------------------
INSERT INTO `achievements` VALUES ('1', 'missions1', 'missions', '10');
INSERT INTO `achievements` VALUES ('2', 'missions2', 'missions', '50');

-- ----------------------------
-- Table structure for `agencies`
-- ----------------------------
DROP TABLE IF EXISTS `agencies`;
CREATE TABLE `agencies` (
  `user_one_id` int(11) unsigned NOT NULL,
  `user_two_id` int(11) unsigned NOT NULL,
  `accepted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`user_one_id`,`user_two_id`),
  KEY `user_one_id` (`user_one_id`),
  KEY `user_two_id` (`user_two_id`),
  CONSTRAINT `user_one_id` FOREIGN KEY (`user_one_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_two_id` FOREIGN KEY (`user_two_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of agencies
-- ----------------------------
INSERT INTO `agencies` VALUES ('12', '52', '1');
INSERT INTO `agencies` VALUES ('29', '13', '0');
INSERT INTO `agencies` VALUES ('29', '28', '0');
INSERT INTO `agencies` VALUES ('29', '30', '0');
INSERT INTO `agencies` VALUES ('29', '32', '0');
INSERT INTO `agencies` VALUES ('29', '52', '1');
INSERT INTO `agencies` VALUES ('51', '29', '1');
INSERT INTO `agencies` VALUES ('51', '40', '0');
INSERT INTO `agencies` VALUES ('51', '42', '0');

-- ----------------------------
-- Table structure for `apns_devices`
-- ----------------------------
DROP TABLE IF EXISTS `apns_devices`;
CREATE TABLE `apns_devices` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Store unique devices';

-- ----------------------------
-- Records of apns_devices
-- ----------------------------

-- ----------------------------
-- Table structure for `apns_device_history`
-- ----------------------------
DROP TABLE IF EXISTS `apns_device_history`;
CREATE TABLE `apns_device_history` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Store unique device history';

-- ----------------------------
-- Records of apns_device_history
-- ----------------------------

-- ----------------------------
-- Table structure for `apns_messages`
-- ----------------------------
DROP TABLE IF EXISTS `apns_messages`;
CREATE TABLE `apns_messages` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Messages to push to APNS';

-- ----------------------------
-- Records of apns_messages
-- ----------------------------

-- ----------------------------
-- Table structure for `battle_history`
-- ----------------------------
DROP TABLE IF EXISTS `battle_history`;
CREATE TABLE `battle_history` (
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
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of battle_history
-- ----------------------------
INSERT INTO `battle_history` VALUES ('1', '51', '1', '0', '15', '5834', '0', '2011-10-25 12:32:59', '35');
INSERT INTO `battle_history` VALUES ('2', '51', '1', '0', '15', '7001', '0', '2011-10-25 12:33:01', '35');
INSERT INTO `battle_history` VALUES ('3', '51', '1', '0', '15', '8401', '0', '2011-10-25 12:33:02', '35');
INSERT INTO `battle_history` VALUES ('4', '29', '1', '0', '15', '5027', '0', '2011-10-25 13:14:23', '49');
INSERT INTO `battle_history` VALUES ('5', '29', '1', '0', '15', '6032', '0', '2011-10-25 13:14:25', '49');
INSERT INTO `battle_history` VALUES ('6', '52', '0', '0', '15', '6848', '0', '2011-10-25 13:20:21', '29');
INSERT INTO `battle_history` VALUES ('7', '53', '0', '0', '15', '1481', '0', '2011-10-25 13:27:41', '14');
INSERT INTO `battle_history` VALUES ('8', '29', '1', '0', '15', '7465', '0', '2011-10-26 09:38:41', '26');
INSERT INTO `battle_history` VALUES ('9', '29', '1', '0', '15', '8958', '0', '2011-10-26 09:39:50', '11');
INSERT INTO `battle_history` VALUES ('10', '29', '1', '0', '15', '10749', '0', '2011-10-26 09:41:32', '11');
INSERT INTO `battle_history` VALUES ('11', '12', '1', '0', '15', '3795', '0', '2011-10-26 12:28:15', '8');
INSERT INTO `battle_history` VALUES ('12', '12', '1', '0', '15', '4554', '0', '2011-10-26 12:32:13', '8');
INSERT INTO `battle_history` VALUES ('13', '12', '1', '0', '15', '2733', '0', '2011-10-26 12:32:21', '8');
INSERT INTO `battle_history` VALUES ('14', '55', '1', '0', '20', '33231', '0', '2011-11-02 17:04:18', '2');
INSERT INTO `battle_history` VALUES ('15', '55', '1', '0', '12', '0', '0', '2011-11-02 17:09:32', '53');
INSERT INTO `battle_history` VALUES ('16', '55', '1', '0', '14', '36554', '0', '2011-11-02 17:11:15', '2');
INSERT INTO `battle_history` VALUES ('17', '55', '1', '0', '17', '40209', '0', '2011-11-02 17:15:16', '2');
INSERT INTO `battle_history` VALUES ('18', '55', '1', '0', '24', '88460', '0', '2011-11-02 17:17:13', '2');
INSERT INTO `battle_history` VALUES ('19', '55', '1', '0', '10', '0', '0', '2011-11-02 17:26:04', '53');
INSERT INTO `battle_history` VALUES ('20', '55', '1', '0', '8', '0', '0', '2011-11-02 17:26:11', '53');
INSERT INTO `battle_history` VALUES ('21', '55', '1', '0', '9', '0', '0', '2011-11-02 17:26:13', '53');
INSERT INTO `battle_history` VALUES ('22', '55', '1', '0', '12', '0', '0', '2011-11-02 17:26:26', '53');
INSERT INTO `battle_history` VALUES ('23', '55', '1', '0', '13', '0', '0', '2011-11-02 17:27:40', '53');
INSERT INTO `battle_history` VALUES ('24', '55', '1', '0', '8', '0', '0', '2011-11-02 17:28:07', '53');
INSERT INTO `battle_history` VALUES ('25', '55', '1', '0', '12', '0', '0', '2011-11-02 17:28:09', '53');
INSERT INTO `battle_history` VALUES ('26', '55', '1', '0', '10', '0', '0', '2011-11-02 17:28:10', '53');
INSERT INTO `battle_history` VALUES ('27', '55', '1', '0', '9', '0', '0', '2011-11-02 17:28:29', '53');
INSERT INTO `battle_history` VALUES ('28', '55', '1', '0', '16', '106152', '0', '2011-11-02 17:38:28', '2');
INSERT INTO `battle_history` VALUES ('29', '55', '1', '0', '15', '127382', '0', '2011-11-02 17:51:19', '2');
INSERT INTO `battle_history` VALUES ('30', '55', '1', '0', '14', '76429', '0', '2011-11-02 17:52:15', '2');
INSERT INTO `battle_history` VALUES ('31', '55', '1', '0', '20', '168145', '0', '2011-11-02 17:52:54', '2');
INSERT INTO `battle_history` VALUES ('32', '55', '1', '0', '17', '201774', '0', '2011-11-02 17:52:55', '2');
INSERT INTO `battle_history` VALUES ('33', '55', '1', '0', '24', '242128', '0', '2011-11-02 17:53:28', '2');
INSERT INTO `battle_history` VALUES ('34', '55', '1', '0', '22', '145277', '0', '2011-11-02 17:53:30', '2');
INSERT INTO `battle_history` VALUES ('35', '55', '1', '0', '7', '0', '0', '2011-11-02 17:54:00', '53');
INSERT INTO `battle_history` VALUES ('36', '55', '1', '0', '16', '319809', '0', '2011-11-02 18:53:07', '2');
INSERT INTO `battle_history` VALUES ('37', '55', '1', '0', '19', '383771', '0', '2011-11-02 18:53:45', '2');
INSERT INTO `battle_history` VALUES ('38', '55', '1', '0', '14', '230263', '0', '2011-11-02 18:53:55', '2');
INSERT INTO `battle_history` VALUES ('39', '55', '1', '0', '22', '506578', '0', '2011-11-02 18:54:10', '2');
INSERT INTO `battle_history` VALUES ('40', '55', '1', '0', '18', '303947', '0', '2011-11-02 18:54:13', '2');
INSERT INTO `battle_history` VALUES ('41', '55', '1', '0', '19', '334342', '0', '2011-11-02 18:54:23', '2');
INSERT INTO `battle_history` VALUES ('42', '55', '1', '0', '14', '0', '0', '2011-11-02 19:08:56', '49');

-- ----------------------------
-- Table structure for `blackmarket`
-- ----------------------------
DROP TABLE IF EXISTS `blackmarket`;
CREATE TABLE `blackmarket` (
  `bm_id` int(11) NOT NULL AUTO_INCREMENT,
  `bm_type` int(255) NOT NULL,
  `bm_item_type` int(255) NOT NULL,
  `bm_name` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `bm_string` varchar(255) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`bm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of blackmarket
-- ----------------------------

-- ----------------------------
-- Table structure for `bounties`
-- ----------------------------
DROP TABLE IF EXISTS `bounties`;
CREATE TABLE `bounties` (
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
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of bounties
-- ----------------------------
INSERT INTO `bounties` VALUES ('1', '10', '5', '10', '0');
INSERT INTO `bounties` VALUES ('2', '10', '5', '10', '0');
INSERT INTO `bounties` VALUES ('3', '10', '1', '100', '0');
INSERT INTO `bounties` VALUES ('4', '18', '10', '50', '0');
INSERT INTO `bounties` VALUES ('5', '10', '2', '100', '1');
INSERT INTO `bounties` VALUES ('6', '10', '5', '100', '0');
INSERT INTO `bounties` VALUES ('7', '10', '5', '100', '0');
INSERT INTO `bounties` VALUES ('8', '10', '5', '100', '0');
INSERT INTO `bounties` VALUES ('9', '10', '18', '100', '0');
INSERT INTO `bounties` VALUES ('10', '18', '10', '22', '0');
INSERT INTO `bounties` VALUES ('11', '18', '10', '3', '0');
INSERT INTO `bounties` VALUES ('12', '18', '10', '322', '0');
INSERT INTO `bounties` VALUES ('13', '18', '10', '333', '0');
INSERT INTO `bounties` VALUES ('14', '18', '10', '37', '0');
INSERT INTO `bounties` VALUES ('15', '18', '10', '39', '0');
INSERT INTO `bounties` VALUES ('16', '18', '10', '61', '0');
INSERT INTO `bounties` VALUES ('17', '18', '10', '61', '0');
INSERT INTO `bounties` VALUES ('18', '18', '10', '61', '0');
INSERT INTO `bounties` VALUES ('19', '18', '10', '61', '0');
INSERT INTO `bounties` VALUES ('20', '18', '10', '61', '0');
INSERT INTO `bounties` VALUES ('21', '18', '10', '61', '0');
INSERT INTO `bounties` VALUES ('22', '18', '10', '61', '0');
INSERT INTO `bounties` VALUES ('23', '18', '10', '61', '0');
INSERT INTO `bounties` VALUES ('24', '18', '10', '61', '0');
INSERT INTO `bounties` VALUES ('25', '18', '10', '61', '0');
INSERT INTO `bounties` VALUES ('26', '18', '10', '99', '0');
INSERT INTO `bounties` VALUES ('27', '18', '10', '15', '0');
INSERT INTO `bounties` VALUES ('28', '18', '10', '15', '0');
INSERT INTO `bounties` VALUES ('29', '18', '10', '12', '0');
INSERT INTO `bounties` VALUES ('30', '18', '10', '3', '0');
INSERT INTO `bounties` VALUES ('31', '18', '10', '3213', '0');
INSERT INTO `bounties` VALUES ('32', '18', '10', '3213', '0');
INSERT INTO `bounties` VALUES ('33', '18', '10', '3213', '0');
INSERT INTO `bounties` VALUES ('34', '18', '10', '3213', '0');
INSERT INTO `bounties` VALUES ('35', '18', '10', '3213', '0');
INSERT INTO `bounties` VALUES ('36', '18', '10', '3213', '0');
INSERT INTO `bounties` VALUES ('37', '18', '10', '3213', '0');
INSERT INTO `bounties` VALUES ('38', '18', '10', '3213', '0');
INSERT INTO `bounties` VALUES ('39', '18', '10', '3213', '0');
INSERT INTO `bounties` VALUES ('40', '18', '10', '3213', '0');
INSERT INTO `bounties` VALUES ('41', '18', '10', '3213', '0');
INSERT INTO `bounties` VALUES ('42', '18', '10', '3213', '0');
INSERT INTO `bounties` VALUES ('43', '18', '10', '3213', '0');
INSERT INTO `bounties` VALUES ('44', '18', '10', '3213', '0');
INSERT INTO `bounties` VALUES ('45', '18', '10', '3213', '0');
INSERT INTO `bounties` VALUES ('46', '18', '10', '3213', '0');
INSERT INTO `bounties` VALUES ('47', '18', '1', '123', '0');
INSERT INTO `bounties` VALUES ('48', '18', '1', '11', '0');
INSERT INTO `bounties` VALUES ('49', '18', '1', '11', '0');
INSERT INTO `bounties` VALUES ('50', '18', '1', '123', '0');
INSERT INTO `bounties` VALUES ('51', '18', '1', '123', '0');
INSERT INTO `bounties` VALUES ('52', '18', '1', '1', '0');
INSERT INTO `bounties` VALUES ('53', '18', '1', '1234', '0');
INSERT INTO `bounties` VALUES ('54', '18', '1', '1234', '0');
INSERT INTO `bounties` VALUES ('55', '18', '1', '1234', '0');
INSERT INTO `bounties` VALUES ('56', '18', '1', '10101', '0');
INSERT INTO `bounties` VALUES ('57', '18', '1', '11', '0');
INSERT INTO `bounties` VALUES ('58', '18', '18', '12', '0');
INSERT INTO `bounties` VALUES ('59', '18', '18', '11', '0');
INSERT INTO `bounties` VALUES ('60', '18', '10', '3', '0');
INSERT INTO `bounties` VALUES ('61', '18', '10', '31', '0');
INSERT INTO `bounties` VALUES ('62', '18', '10', '31', '0');
INSERT INTO `bounties` VALUES ('63', '18', '10', '31', '0');
INSERT INTO `bounties` VALUES ('64', '18', '10', '31', '0');
INSERT INTO `bounties` VALUES ('65', '18', '10', '31', '0');
INSERT INTO `bounties` VALUES ('66', '18', '10', '31', '0');
INSERT INTO `bounties` VALUES ('67', '18', '10', '31', '0');
INSERT INTO `bounties` VALUES ('68', '18', '10', '31', '0');
INSERT INTO `bounties` VALUES ('69', '18', '10', '22', '0');
INSERT INTO `bounties` VALUES ('70', '18', '10', '12', '0');
INSERT INTO `bounties` VALUES ('71', '18', '10', '8', '0');
INSERT INTO `bounties` VALUES ('72', '18', '1', '12341234', '0');
INSERT INTO `bounties` VALUES ('73', '18', '1', '999', '0');
INSERT INTO `bounties` VALUES ('74', '18', '18', '129', '0');
INSERT INTO `bounties` VALUES ('75', '18', '10', '1', '0');
INSERT INTO `bounties` VALUES ('76', '18', '4', '12', '0');
INSERT INTO `bounties` VALUES ('77', '18', '10', '1', '0');
INSERT INTO `bounties` VALUES ('78', '18', '10', '12', '0');
INSERT INTO `bounties` VALUES ('79', '18', '10', '100000', '0');
INSERT INTO `bounties` VALUES ('80', '18', '1', '10', '0');
INSERT INTO `bounties` VALUES ('81', '18', '1', '10', '0');
INSERT INTO `bounties` VALUES ('82', '18', '1', '10', '0');
INSERT INTO `bounties` VALUES ('83', '18', '1', '90', '0');
INSERT INTO `bounties` VALUES ('84', '18', '1', '10', '0');
INSERT INTO `bounties` VALUES ('85', '18', '1', '10', '0');
INSERT INTO `bounties` VALUES ('86', '24', '2', '10', '1');
INSERT INTO `bounties` VALUES ('87', '51', '34', '500', '0');
INSERT INTO `bounties` VALUES ('88', '29', '49', '200', '1');
INSERT INTO `bounties` VALUES ('89', '11', '22', '6546', '0');
INSERT INTO `bounties` VALUES ('90', '11', '1', '34', '0');
INSERT INTO `bounties` VALUES ('91', '29', '49', '456', '0');
INSERT INTO `bounties` VALUES ('92', '29', '27', '12', '0');

-- ----------------------------
-- Table structure for `broadcast`
-- ----------------------------
DROP TABLE IF EXISTS `broadcast`;
CREATE TABLE `broadcast` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `content` varchar(250) NOT NULL,
  `time_posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of broadcast
-- ----------------------------
INSERT INTO `broadcast` VALUES ('51', '55', 'hhey nigga ! \r\n', '2011-10-31 14:57:38');

-- ----------------------------
-- Table structure for `cities`
-- ----------------------------
DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cities
-- ----------------------------
INSERT INTO `cities` VALUES ('1', 'East');
INSERT INTO `cities` VALUES ('2', 'West');
INSERT INTO `cities` VALUES ('3', 'North');
INSERT INTO `cities` VALUES ('4', 'South');

-- ----------------------------
-- Table structure for `comments`
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `comments` varchar(250) NOT NULL,
  `time_posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of comments
-- ----------------------------
INSERT INTO `comments` VALUES ('1', '55', '3', 'hello', '2011-10-31 14:52:41', '1');
INSERT INTO `comments` VALUES ('2', '55', '3', 'heellllloo\n', '2011-10-31 14:54:57', '1');
INSERT INTO `comments` VALUES ('3', '55', '55', 'asd', '2011-10-31 15:48:21', '1');

-- ----------------------------
-- Table structure for `items`
-- ----------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` int(11) unsigned NOT NULL,
  `atk_boost` int(11) unsigned NOT NULL,
  `def_boost` int(11) unsigned NOT NULL,
  `upkeep` int(11) unsigned DEFAULT '0',
  `is_buyable` tinyint(1) unsigned DEFAULT '1',
  `min_level` int(11) unsigned NOT NULL,
  `price` int(11) unsigned NOT NULL,
  `chance_of_loss` double unsigned DEFAULT '0',
  `image_url` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of items
-- ----------------------------
INSERT INTO `items` VALUES ('1', 'Assault Rifle', '1', '3', '4', '0', '1', '1', '70', '0', 'img/assaultrifle.png');
INSERT INTO `items` VALUES ('2', 'Uzi Rifle', '1', '2', '5', '1000', '1', '3', '80', '0.7', 'img/uzi.png');
INSERT INTO `items` VALUES ('3', 'maserati', '3', '4', '4', '4', '1', '250', '100', '0.6', null);
INSERT INTO `items` VALUES ('4', 'riot shield', '2', '0', '10', '0', '1', '67', '120', '0.3', null);
INSERT INTO `items` VALUES ('5', 'mini gun', '1', '7', '7', '200', '1', '200', '2000', '0', null);
INSERT INTO `items` VALUES ('6', 'mini gun 2', '1', '2', '2', '2', '1', '200', '100', '0', null);
INSERT INTO `items` VALUES ('7', 'mini gun 3', '1', '7', '7', '7', '1', '201', '20000', '0', null);
INSERT INTO `items` VALUES ('8', 'helmet', '2', '2', '2', '2', '1', '67', '100', '0', null);
INSERT INTO `items` VALUES ('9', 'kevlar', '2', '100', '12', '0', '1', '100', '1000', '0', null);

-- ----------------------------
-- Table structure for `level_experience_points`
-- ----------------------------
DROP TABLE IF EXISTS `level_experience_points`;
CREATE TABLE `level_experience_points` (
  `level_id` int(11) NOT NULL DEFAULT '0',
  `required_experience_points` int(11) DEFAULT NULL,
  PRIMARY KEY (`level_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of level_experience_points
-- ----------------------------
INSERT INTO `level_experience_points` VALUES ('2', '15');
INSERT INTO `level_experience_points` VALUES ('3', '33');
INSERT INTO `level_experience_points` VALUES ('4', '57');
INSERT INTO `level_experience_points` VALUES ('5', '92');
INSERT INTO `level_experience_points` VALUES ('6', '143');
INSERT INTO `level_experience_points` VALUES ('7', '221');
INSERT INTO `level_experience_points` VALUES ('8', '341');
INSERT INTO `level_experience_points` VALUES ('9', '524');
INSERT INTO `level_experience_points` VALUES ('10', '749');
INSERT INTO `level_experience_points` VALUES ('11', '1001');
INSERT INTO `level_experience_points` VALUES ('12', '1269');
INSERT INTO `level_experience_points` VALUES ('13', '1548');
INSERT INTO `level_experience_points` VALUES ('14', '1838');
INSERT INTO `level_experience_points` VALUES ('15', '2138');
INSERT INTO `level_experience_points` VALUES ('16', '2448');
INSERT INTO `level_experience_points` VALUES ('17', '2769');
INSERT INTO `level_experience_points` VALUES ('18', '3101');
INSERT INTO `level_experience_points` VALUES ('19', '3443');
INSERT INTO `level_experience_points` VALUES ('20', '3797');
INSERT INTO `level_experience_points` VALUES ('21', '4163');
INSERT INTO `level_experience_points` VALUES ('22', '4541');
INSERT INTO `level_experience_points` VALUES ('23', '4931');
INSERT INTO `level_experience_points` VALUES ('24', '5333');
INSERT INTO `level_experience_points` VALUES ('25', '5747');
INSERT INTO `level_experience_points` VALUES ('26', '6173');
INSERT INTO `level_experience_points` VALUES ('27', '6611');
INSERT INTO `level_experience_points` VALUES ('28', '7061');
INSERT INTO `level_experience_points` VALUES ('29', '7523');
INSERT INTO `level_experience_points` VALUES ('30', '7998');
INSERT INTO `level_experience_points` VALUES ('31', '8487');
INSERT INTO `level_experience_points` VALUES ('32', '8990');
INSERT INTO `level_experience_points` VALUES ('33', '9506');
INSERT INTO `level_experience_points` VALUES ('34', '10035');
INSERT INTO `level_experience_points` VALUES ('35', '10578');
INSERT INTO `level_experience_points` VALUES ('36', '11135');
INSERT INTO `level_experience_points` VALUES ('37', '11705');
INSERT INTO `level_experience_points` VALUES ('38', '12288');
INSERT INTO `level_experience_points` VALUES ('39', '12885');
INSERT INTO `level_experience_points` VALUES ('40', '13497');
INSERT INTO `level_experience_points` VALUES ('41', '14124');
INSERT INTO `level_experience_points` VALUES ('42', '14766');
INSERT INTO `level_experience_points` VALUES ('43', '15423');
INSERT INTO `level_experience_points` VALUES ('44', '16095');
INSERT INTO `level_experience_points` VALUES ('45', '16782');
INSERT INTO `level_experience_points` VALUES ('46', '17484');
INSERT INTO `level_experience_points` VALUES ('47', '18201');
INSERT INTO `level_experience_points` VALUES ('48', '18933');
INSERT INTO `level_experience_points` VALUES ('49', '19680');
INSERT INTO `level_experience_points` VALUES ('50', '20447');
INSERT INTO `level_experience_points` VALUES ('51', '21233');
INSERT INTO `level_experience_points` VALUES ('52', '22038');
INSERT INTO `level_experience_points` VALUES ('53', '22863');
INSERT INTO `level_experience_points` VALUES ('54', '23708');
INSERT INTO `level_experience_points` VALUES ('55', '24572');
INSERT INTO `level_experience_points` VALUES ('56', '25455');
INSERT INTO `level_experience_points` VALUES ('57', '26358');
INSERT INTO `level_experience_points` VALUES ('58', '27281');
INSERT INTO `level_experience_points` VALUES ('59', '28223');
INSERT INTO `level_experience_points` VALUES ('60', '29184');
INSERT INTO `level_experience_points` VALUES ('61', '30165');
INSERT INTO `level_experience_points` VALUES ('62', '31166');
INSERT INTO `level_experience_points` VALUES ('63', '32186');
INSERT INTO `level_experience_points` VALUES ('64', '33225');
INSERT INTO `level_experience_points` VALUES ('65', '34284');
INSERT INTO `level_experience_points` VALUES ('66', '35363');
INSERT INTO `level_experience_points` VALUES ('67', '36461');
INSERT INTO `level_experience_points` VALUES ('68', '37578');
INSERT INTO `level_experience_points` VALUES ('69', '38715');
INSERT INTO `level_experience_points` VALUES ('70', '39872');
INSERT INTO `level_experience_points` VALUES ('71', '41048');
INSERT INTO `level_experience_points` VALUES ('72', '42243');
INSERT INTO `level_experience_points` VALUES ('73', '43458');
INSERT INTO `level_experience_points` VALUES ('74', '44693');
INSERT INTO `level_experience_points` VALUES ('75', '45947');
INSERT INTO `level_experience_points` VALUES ('76', '47220');
INSERT INTO `level_experience_points` VALUES ('77', '48513');
INSERT INTO `level_experience_points` VALUES ('78', '49826');
INSERT INTO `level_experience_points` VALUES ('79', '51158');
INSERT INTO `level_experience_points` VALUES ('80', '52509');
INSERT INTO `level_experience_points` VALUES ('81', '53880');
INSERT INTO `level_experience_points` VALUES ('82', '55271');
INSERT INTO `level_experience_points` VALUES ('83', '56681');
INSERT INTO `level_experience_points` VALUES ('84', '58110');
INSERT INTO `level_experience_points` VALUES ('85', '59559');
INSERT INTO `level_experience_points` VALUES ('86', '61028');
INSERT INTO `level_experience_points` VALUES ('87', '62516');
INSERT INTO `level_experience_points` VALUES ('88', '64023');
INSERT INTO `level_experience_points` VALUES ('89', '65550');
INSERT INTO `level_experience_points` VALUES ('90', '67097');
INSERT INTO `level_experience_points` VALUES ('91', '68663');
INSERT INTO `level_experience_points` VALUES ('92', '70248');
INSERT INTO `level_experience_points` VALUES ('93', '71853');
INSERT INTO `level_experience_points` VALUES ('94', '73478');
INSERT INTO `level_experience_points` VALUES ('95', '75122');
INSERT INTO `level_experience_points` VALUES ('96', '76785');
INSERT INTO `level_experience_points` VALUES ('97', '78468');
INSERT INTO `level_experience_points` VALUES ('98', '80171');
INSERT INTO `level_experience_points` VALUES ('99', '81893');
INSERT INTO `level_experience_points` VALUES ('100', '83643');
INSERT INTO `level_experience_points` VALUES ('101', '85422');
INSERT INTO `level_experience_points` VALUES ('102', '87230');
INSERT INTO `level_experience_points` VALUES ('103', '89066');
INSERT INTO `level_experience_points` VALUES ('104', '90930');
INSERT INTO `level_experience_points` VALUES ('105', '92823');
INSERT INTO `level_experience_points` VALUES ('106', '94745');
INSERT INTO `level_experience_points` VALUES ('107', '96695');
INSERT INTO `level_experience_points` VALUES ('108', '98673');
INSERT INTO `level_experience_points` VALUES ('109', '100680');
INSERT INTO `level_experience_points` VALUES ('110', '102716');
INSERT INTO `level_experience_points` VALUES ('111', '104780');
INSERT INTO `level_experience_points` VALUES ('112', '106872');
INSERT INTO `level_experience_points` VALUES ('113', '108993');
INSERT INTO `level_experience_points` VALUES ('114', '111143');
INSERT INTO `level_experience_points` VALUES ('115', '113321');
INSERT INTO `level_experience_points` VALUES ('116', '115527');
INSERT INTO `level_experience_points` VALUES ('117', '117762');
INSERT INTO `level_experience_points` VALUES ('118', '120026');
INSERT INTO `level_experience_points` VALUES ('119', '122318');
INSERT INTO `level_experience_points` VALUES ('120', '124638');
INSERT INTO `level_experience_points` VALUES ('121', '126987');
INSERT INTO `level_experience_points` VALUES ('122', '129365');
INSERT INTO `level_experience_points` VALUES ('123', '131771');
INSERT INTO `level_experience_points` VALUES ('124', '134205');
INSERT INTO `level_experience_points` VALUES ('125', '136668');
INSERT INTO `level_experience_points` VALUES ('126', '139160');
INSERT INTO `level_experience_points` VALUES ('127', '141680');
INSERT INTO `level_experience_points` VALUES ('128', '144228');
INSERT INTO `level_experience_points` VALUES ('129', '146805');
INSERT INTO `level_experience_points` VALUES ('130', '149411');
INSERT INTO `level_experience_points` VALUES ('131', '152045');
INSERT INTO `level_experience_points` VALUES ('132', '154707');
INSERT INTO `level_experience_points` VALUES ('133', '157398');
INSERT INTO `level_experience_points` VALUES ('134', '160118');
INSERT INTO `level_experience_points` VALUES ('135', '162866');
INSERT INTO `level_experience_points` VALUES ('136', '165642');
INSERT INTO `level_experience_points` VALUES ('137', '168447');
INSERT INTO `level_experience_points` VALUES ('138', '171281');
INSERT INTO `level_experience_points` VALUES ('139', '174143');
INSERT INTO `level_experience_points` VALUES ('140', '177033');
INSERT INTO `level_experience_points` VALUES ('141', '179952');
INSERT INTO `level_experience_points` VALUES ('142', '182900');
INSERT INTO `level_experience_points` VALUES ('143', '185876');
INSERT INTO `level_experience_points` VALUES ('144', '188880');
INSERT INTO `level_experience_points` VALUES ('145', '191913');
INSERT INTO `level_experience_points` VALUES ('146', '194975');
INSERT INTO `level_experience_points` VALUES ('147', '198065');
INSERT INTO `level_experience_points` VALUES ('148', '201183');
INSERT INTO `level_experience_points` VALUES ('149', '204330');
INSERT INTO `level_experience_points` VALUES ('150', '207515');
INSERT INTO `level_experience_points` VALUES ('151', '210737');
INSERT INTO `level_experience_points` VALUES ('152', '213996');
INSERT INTO `level_experience_points` VALUES ('153', '217293');
INSERT INTO `level_experience_points` VALUES ('154', '220628');
INSERT INTO `level_experience_points` VALUES ('155', '224000');
INSERT INTO `level_experience_points` VALUES ('156', '227409');
INSERT INTO `level_experience_points` VALUES ('157', '230856');
INSERT INTO `level_experience_points` VALUES ('158', '234341');
INSERT INTO `level_experience_points` VALUES ('159', '237863');
INSERT INTO `level_experience_points` VALUES ('160', '241422');
INSERT INTO `level_experience_points` VALUES ('161', '245019');
INSERT INTO `level_experience_points` VALUES ('162', '248654');
INSERT INTO `level_experience_points` VALUES ('163', '252326');
INSERT INTO `level_experience_points` VALUES ('164', '256035');
INSERT INTO `level_experience_points` VALUES ('165', '259782');
INSERT INTO `level_experience_points` VALUES ('166', '263567');
INSERT INTO `level_experience_points` VALUES ('167', '267389');
INSERT INTO `level_experience_points` VALUES ('168', '271248');
INSERT INTO `level_experience_points` VALUES ('169', '275145');
INSERT INTO `level_experience_points` VALUES ('170', '279080');
INSERT INTO `level_experience_points` VALUES ('171', '283052');
INSERT INTO `level_experience_points` VALUES ('172', '287061');
INSERT INTO `level_experience_points` VALUES ('173', '291108');
INSERT INTO `level_experience_points` VALUES ('174', '295193');
INSERT INTO `level_experience_points` VALUES ('175', '299315');
INSERT INTO `level_experience_points` VALUES ('176', '303474');
INSERT INTO `level_experience_points` VALUES ('177', '307671');
INSERT INTO `level_experience_points` VALUES ('178', '311906');
INSERT INTO `level_experience_points` VALUES ('179', '316178');
INSERT INTO `level_experience_points` VALUES ('180', '320487');
INSERT INTO `level_experience_points` VALUES ('181', '324834');
INSERT INTO `level_experience_points` VALUES ('182', '329219');
INSERT INTO `level_experience_points` VALUES ('183', '333641');
INSERT INTO `level_experience_points` VALUES ('184', '338100');
INSERT INTO `level_experience_points` VALUES ('185', '342597');
INSERT INTO `level_experience_points` VALUES ('186', '347132');
INSERT INTO `level_experience_points` VALUES ('187', '351704');
INSERT INTO `level_experience_points` VALUES ('188', '356313');
INSERT INTO `level_experience_points` VALUES ('189', '360960');
INSERT INTO `level_experience_points` VALUES ('190', '365645');
INSERT INTO `level_experience_points` VALUES ('191', '370367');
INSERT INTO `level_experience_points` VALUES ('192', '375126');
INSERT INTO `level_experience_points` VALUES ('193', '379923');
INSERT INTO `level_experience_points` VALUES ('194', '384758');
INSERT INTO `level_experience_points` VALUES ('195', '389630');
INSERT INTO `level_experience_points` VALUES ('196', '394539');
INSERT INTO `level_experience_points` VALUES ('197', '399486');
INSERT INTO `level_experience_points` VALUES ('198', '404471');
INSERT INTO `level_experience_points` VALUES ('199', '409493');
INSERT INTO `level_experience_points` VALUES ('200', '414561');
INSERT INTO `level_experience_points` VALUES ('201', '419828');
INSERT INTO `level_experience_points` VALUES ('202', '425246');
INSERT INTO `level_experience_points` VALUES ('203', '430815');
INSERT INTO `level_experience_points` VALUES ('204', '436536');
INSERT INTO `level_experience_points` VALUES ('205', '442409');
INSERT INTO `level_experience_points` VALUES ('206', '448433');
INSERT INTO `level_experience_points` VALUES ('207', '454608');
INSERT INTO `level_experience_points` VALUES ('208', '460935');
INSERT INTO `level_experience_points` VALUES ('209', '467414');
INSERT INTO `level_experience_points` VALUES ('210', '474044');
INSERT INTO `level_experience_points` VALUES ('211', '480825');
INSERT INTO `level_experience_points` VALUES ('212', '487758');
INSERT INTO `level_experience_points` VALUES ('213', '494843');
INSERT INTO `level_experience_points` VALUES ('214', '502079');
INSERT INTO `level_experience_points` VALUES ('215', '509466');
INSERT INTO `level_experience_points` VALUES ('216', '517005');
INSERT INTO `level_experience_points` VALUES ('217', '524696');
INSERT INTO `level_experience_points` VALUES ('218', '532538');
INSERT INTO `level_experience_points` VALUES ('219', '540531');
INSERT INTO `level_experience_points` VALUES ('220', '548676');
INSERT INTO `level_experience_points` VALUES ('221', '556973');
INSERT INTO `level_experience_points` VALUES ('222', '565421');
INSERT INTO `level_experience_points` VALUES ('223', '574020');
INSERT INTO `level_experience_points` VALUES ('224', '582771');
INSERT INTO `level_experience_points` VALUES ('225', '591674');
INSERT INTO `level_experience_points` VALUES ('226', '600881');
INSERT INTO `level_experience_points` VALUES ('227', '610392');
INSERT INTO `level_experience_points` VALUES ('228', '620208');
INSERT INTO `level_experience_points` VALUES ('229', '630329');
INSERT INTO `level_experience_points` VALUES ('230', '640754');
INSERT INTO `level_experience_points` VALUES ('231', '651483');
INSERT INTO `level_experience_points` VALUES ('232', '662517');
INSERT INTO `level_experience_points` VALUES ('233', '673856');
INSERT INTO `level_experience_points` VALUES ('234', '685499');
INSERT INTO `level_experience_points` VALUES ('235', '697446');
INSERT INTO `level_experience_points` VALUES ('236', '709698');
INSERT INTO `level_experience_points` VALUES ('237', '722255');
INSERT INTO `level_experience_points` VALUES ('238', '735116');
INSERT INTO `level_experience_points` VALUES ('239', '748281');
INSERT INTO `level_experience_points` VALUES ('240', '761751');
INSERT INTO `level_experience_points` VALUES ('241', '775526');
INSERT INTO `level_experience_points` VALUES ('242', '789605');
INSERT INTO `level_experience_points` VALUES ('243', '803988');
INSERT INTO `level_experience_points` VALUES ('244', '818676');
INSERT INTO `level_experience_points` VALUES ('245', '833669');
INSERT INTO `level_experience_points` VALUES ('246', '848966');
INSERT INTO `level_experience_points` VALUES ('247', '864567');
INSERT INTO `level_experience_points` VALUES ('248', '880473');
INSERT INTO `level_experience_points` VALUES ('249', '896684');
INSERT INTO `level_experience_points` VALUES ('250', '913199');
INSERT INTO `level_experience_points` VALUES ('251', '930923');
INSERT INTO `level_experience_points` VALUES ('252', '949856');
INSERT INTO `level_experience_points` VALUES ('253', '969998');
INSERT INTO `level_experience_points` VALUES ('254', '991349');
INSERT INTO `level_experience_points` VALUES ('255', '1013909');
INSERT INTO `level_experience_points` VALUES ('256', '1037678');
INSERT INTO `level_experience_points` VALUES ('257', '1062656');
INSERT INTO `level_experience_points` VALUES ('258', '1088843');
INSERT INTO `level_experience_points` VALUES ('259', '1116239');
INSERT INTO `level_experience_points` VALUES ('260', '1144844');
INSERT INTO `level_experience_points` VALUES ('261', '1174658');
INSERT INTO `level_experience_points` VALUES ('262', '1205681');
INSERT INTO `level_experience_points` VALUES ('263', '1237913');
INSERT INTO `level_experience_points` VALUES ('264', '1271354');
INSERT INTO `level_experience_points` VALUES ('265', '1306004');
INSERT INTO `level_experience_points` VALUES ('266', '1341863');
INSERT INTO `level_experience_points` VALUES ('267', '1378931');
INSERT INTO `level_experience_points` VALUES ('268', '1417208');
INSERT INTO `level_experience_points` VALUES ('269', '1456694');
INSERT INTO `level_experience_points` VALUES ('270', '1497389');
INSERT INTO `level_experience_points` VALUES ('271', '1539293');
INSERT INTO `level_experience_points` VALUES ('272', '1582406');
INSERT INTO `level_experience_points` VALUES ('273', '1626728');
INSERT INTO `level_experience_points` VALUES ('274', '1672259');
INSERT INTO `level_experience_points` VALUES ('275', '1718999');
INSERT INTO `level_experience_points` VALUES ('276', '1766948');
INSERT INTO `level_experience_points` VALUES ('277', '1816106');
INSERT INTO `level_experience_points` VALUES ('278', '1866473');
INSERT INTO `level_experience_points` VALUES ('279', '1918049');
INSERT INTO `level_experience_points` VALUES ('280', '1970834');
INSERT INTO `level_experience_points` VALUES ('281', '2024828');
INSERT INTO `level_experience_points` VALUES ('282', '2081235');
INSERT INTO `level_experience_points` VALUES ('283', '2140056');
INSERT INTO `level_experience_points` VALUES ('284', '2201291');
INSERT INTO `level_experience_points` VALUES ('285', '2264939');
INSERT INTO `level_experience_points` VALUES ('286', '2331000');
INSERT INTO `level_experience_points` VALUES ('287', '2401880');
INSERT INTO `level_experience_points` VALUES ('288', '2477577');
INSERT INTO `level_experience_points` VALUES ('289', '2558093');
INSERT INTO `level_experience_points` VALUES ('290', '2643426');
INSERT INTO `level_experience_points` VALUES ('291', '2733578');
INSERT INTO `level_experience_points` VALUES ('292', '2833352');
INSERT INTO `level_experience_points` VALUES ('293', '2942748');
INSERT INTO `level_experience_points` VALUES ('294', '3061767');
INSERT INTO `level_experience_points` VALUES ('295', '3190409');
INSERT INTO `level_experience_points` VALUES ('296', '3328673');
INSERT INTO `level_experience_points` VALUES ('297', '3486236');
INSERT INTO `level_experience_points` VALUES ('298', '3663098');
INSERT INTO `level_experience_points` VALUES ('299', '3859259');
INSERT INTO `level_experience_points` VALUES ('300', '4074719');

-- ----------------------------
-- Table structure for `missions`
-- ----------------------------
DROP TABLE IF EXISTS `missions`;
CREATE TABLE `missions` (
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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of missions
-- ----------------------------
INSERT INTO `missions` VALUES ('1', 'Master Brazilian Jujitsu', '', '1', '150', '450', '1', '1', '0.25', '1', '3', '5', '7', '1', '1');
INSERT INTO `missions` VALUES ('2', 'Learn Street Combat', '', '3', '450', '1350', '1', '2', '0.25', '4', '3', '5', '6', '1', '1');
INSERT INTO `missions` VALUES ('3', 'Hack Encrypted Database', '', '4', '2250', '6750', '2', '3', '0.25', '7', '3', '5', '7', '1', '1');
INSERT INTO `missions` VALUES ('4', 'Master Close Combat Weapons', '', '6', '2700', '7200', '2', '4', '0.25', '10', '3', '3', '4', '1', '1');
INSERT INTO `missions` VALUES ('5', 'Perfect Pistol Precision', '', '4', '2400', '8100', '3', '3', '0.25', '6', '3', '5', '6', '1', '1');
INSERT INTO `missions` VALUES ('6', 'Learn to Interrogate', '', '6', '3600', '10800', '3', '5', '0.25', '9', '3', '5', '6', '1', '1');
INSERT INTO `missions` VALUES ('7', 'Complete Obstacle Course', '', '7', '6300', '36000', '4', '0', '0', '10', '3', '5', '4', '1', '1');
INSERT INTO `missions` VALUES ('8', 'Blood Test: Kill Dealer', '', '10', '12000', '24000', '5', '0', '0', '15', '3', '3', '4', '1', '1');
INSERT INTO `missions` VALUES ('9', 'Perform Drive-By', '', '3', '4500', '19500', '6', '0', '0', '7', '7', '9', '12', '2', '1');
INSERT INTO `missions` VALUES ('10', 'Rackateer Dealers', '', '5', '7500', '22500', '6', '6', '0.25', '10', '7', '9', '12', '2', '1');
INSERT INTO `missions` VALUES ('11', 'Secure Weapons Shipment', '', '6', '9000', '25500', '7', '0', '0', '16', '7', '12', '17', '2', '1');
INSERT INTO `missions` VALUES ('12', 'Eliminate Rival Spies', '', '4', '9000', '37500', '8', '7', '0.25', '22', '11', '18', '23', '2', '1');
INSERT INTO `missions` VALUES ('13', 'Snipe: Oil Tycoon', '', '8', '12000', '28500', '8', '0', '0', '19', '10', '12', '14', '2', '1');
INSERT INTO `missions` VALUES ('14', 'Siege the Docks', '', '4', '9000', '27000', '9', '7', '0.25', '16', '10', '12', '14', '2', '1');
INSERT INTO `missions` VALUES ('15', 'Siege the Golden Gate Bridge', '', '3', '7500', '13500', '10', '0', '0', '15', '17', '19', '21', '2', '2');
INSERT INTO `missions` VALUES ('16', 'Protect Zark Muckerberg', '', '9', '24000', '72000', '10', '0', '0', '21', '5', '6', '8', '2', '1');
INSERT INTO `missions` VALUES ('17', 'Extort Production Studios', '', '5', '15000', '73500', '11', '7', '0.25', '22', '13', '17', '21', '3', '2');
INSERT INTO `missions` VALUES ('18', 'Track Rogue Spy', '', '7', '24000', '57000', '11', '0', '0', '19', '13', '17', '21', '3', '3');
INSERT INTO `missions` VALUES ('19', 'Bank Heist', '', '5', '19500', '45000', '11', '0', '0', '24', '16', '20', '24', '3', '3');
INSERT INTO `missions` VALUES ('20', 'Burn Rival Bunker', '', '10', '36000', '105000', '11', '0', '0', '33', '16', '20', '24', '3', '4');
INSERT INTO `missions` VALUES ('21', 'Kill Riot Leader', '', '6', '37500', '105000', '11', '0', '0', '30', '6', '8', '10', '3', '6');
INSERT INTO `missions` VALUES ('22', 'Highway Chase', '', '5', '36000', '120000', '17', '0', '0', '31', '18', '22', '26', '3', '7');
INSERT INTO `missions` VALUES ('23', 'Raid Enemy Armory', '', '9', '120000', '345000', '19', '0', '0', '34', '8', '10', '12', '3', '8');
INSERT INTO `missions` VALUES ('24', 'Kidnap Ashton Butcher', '', '13', '285000', '705000', '20', '8', '0.25', '42', '10', '14', '18', '3', '9');
INSERT INTO `missions` VALUES ('25', 'Smuggle Drugs', '', '7', '210000', '840000', '21', '0', '0', '36', '13', '17', '21', '4', '9');
INSERT INTO `missions` VALUES ('26', 'Torture Captured Spy', '', '8', '240000', '615000', '22', '0', '0', '46', '13', '17', '21', '4', '10');
INSERT INTO `missions` VALUES ('27', 'Snipe Snitch', '', '9', '360000', '1050000', '23', '0', '0', '45', '7', '8', '9', '4', '11');
INSERT INTO `missions` VALUES ('28', 'Kill Arms Dealer', '', '11', '510000', '1350000', '24', '0', '0', '40', '8', '10', '12', '4', '12');
INSERT INTO `missions` VALUES ('29', 'Transport Warhead', '', '14', '915000', '1200000', '25', '0', '0', '45', '10', '14', '18', '4', '12');
INSERT INTO `missions` VALUES ('30', 'Bomb Subway', '', '7', '480000', '2700000', '26', '0', '0', '43', '15', '18', '21', '4', '12');
INSERT INTO `missions` VALUES ('31', 'Secure Drug Shipment', '', '6', '420000', '1500000', '27', '0', '0', '43', '20', '24', '28', '4', '14');
INSERT INTO `missions` VALUES ('32', 'Siege Sears Tower', '', '20', '2250000', '2640000', '29', '0', '0', '52', '10', '13', '16', '4', '16');
INSERT INTO `missions` VALUES ('33', 'Burn the Docks', '', '10', '1065000', '6750000', '31', '9', '0.25', '54', '21', '24', '27', '5', '18');
INSERT INTO `missions` VALUES ('34', 'Steal Weapons Cargo', '', '14', '2160000', '1935000', '34', '0', '0', '58', '18', '21', '24', '5', '20');
INSERT INTO `missions` VALUES ('35', 'Hi-Jack Jet', '', '23', '2820000', '4950000', '37', '0', '0', '52', '5', '10', '15', '5', '21');
INSERT INTO `missions` VALUES ('36', 'Extort Drug Lords', '', '15', '3450000', '7200000', '40', '0', '0', '69', '20', '25', '30', '5', '22');
INSERT INTO `missions` VALUES ('37', 'Kidnap Kingpin', '', '9', '2700000', '6570000', '45', '19', '0.25', '75', '15', '20', '25', '5', '22');
INSERT INTO `missions` VALUES ('38', 'Secure Enemy Bunker', '', '12', '2400000', '6570000', '50', '0', '0', '84', '10', '12', '14', '5', '23');
INSERT INTO `missions` VALUES ('39', 'Assassinate Rival Leader', '', '30', '12150000', '102000000', '54', '0', '0', '91', '10', '12', '14', '5', '25');
INSERT INTO `missions` VALUES ('40', 'Burn: Stock Exchange', '', '12', '4500000', '28350000', '60', '0', '0', '102', '12', '16', '20', '6', '27');
INSERT INTO `missions` VALUES ('41', 'Destroy: Rival HQ', '', '15', '9000000', '12600000', '60', '0', '0', '108', '20', '25', '30', '6', '29');
INSERT INTO `missions` VALUES ('42', 'Kill: Wallstreet Hotshot', '', '17', '24060000', '300000000', '60', '0', '0', '123', '15', '17', '21', '6', '32');
INSERT INTO `missions` VALUES ('43', 'Burn: CIA Base', '', '10', '5700000', '9300000', '60', '11', '0.25', '111', '18', '22', '26', '6', '33');
INSERT INTO `missions` VALUES ('44', 'Protect: Godfather', '', '18', '9000000', '15450000', '60', '0', '0', '114', '10', '15', '20', '6', '37');
INSERT INTO `missions` VALUES ('45', 'Burn: Brooklyn Bridge', '', '28', '16500000', '240000000', '90', '0', '0', '117', '5', '10', '15', '6', '43');
INSERT INTO `missions` VALUES ('46', 'Kill: Alex Volkoff', '', '35', '42000000', '1275000000', '100', '0', '0', '138', '7', '10', '15', '6', '47');

-- ----------------------------
-- Table structure for `missions_itemreqs`
-- ----------------------------
DROP TABLE IF EXISTS `missions_itemreqs`;
CREATE TABLE `missions_itemreqs` (
  `mission_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  `item_quantity` int(11) unsigned DEFAULT '1',
  PRIMARY KEY (`mission_id`,`item_id`),
  KEY `hi` (`mission_id`),
  KEY `hi2` (`item_id`),
  CONSTRAINT `missions_itemreqs_ibfk_1` FOREIGN KEY (`mission_id`) REFERENCES `missions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `missions_itemreqs_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of missions_itemreqs
-- ----------------------------

-- ----------------------------
-- Table structure for `offline_achievements`
-- ----------------------------
DROP TABLE IF EXISTS `offline_achievements`;
CREATE TABLE `offline_achievements` (
  `achievement_name` varchar(15) NOT NULL,
  `achievement_level` tinyint(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`achievement_name`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of offline_achievements
-- ----------------------------

-- ----------------------------
-- Table structure for `pma_bookmark`
-- ----------------------------
DROP TABLE IF EXISTS `pma_bookmark`;
CREATE TABLE `pma_bookmark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dbase` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `query` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- ----------------------------
-- Records of pma_bookmark
-- ----------------------------

-- ----------------------------
-- Table structure for `pma_column_info`
-- ----------------------------
DROP TABLE IF EXISTS `pma_column_info`;
CREATE TABLE `pma_column_info` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `table_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `column_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `transformation` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `transformation_options` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- ----------------------------
-- Records of pma_column_info
-- ----------------------------

-- ----------------------------
-- Table structure for `pma_designer_coords`
-- ----------------------------
DROP TABLE IF EXISTS `pma_designer_coords`;
CREATE TABLE `pma_designer_coords` (
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `table_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `x` int(11) DEFAULT NULL,
  `y` int(11) DEFAULT NULL,
  `v` tinyint(4) DEFAULT NULL,
  `h` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`db_name`,`table_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for Designer';

-- ----------------------------
-- Records of pma_designer_coords
-- ----------------------------

-- ----------------------------
-- Table structure for `pma_history`
-- ----------------------------
DROP TABLE IF EXISTS `pma_history`;
CREATE TABLE `pma_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `db` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `table` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sqlquery` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`,`db`,`table`,`timevalue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- ----------------------------
-- Records of pma_history
-- ----------------------------

-- ----------------------------
-- Table structure for `pma_pdf_pages`
-- ----------------------------
DROP TABLE IF EXISTS `pma_pdf_pages`;
CREATE TABLE `pma_pdf_pages` (
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `page_nr` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_descr` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`page_nr`),
  KEY `db_name` (`db_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- ----------------------------
-- Records of pma_pdf_pages
-- ----------------------------

-- ----------------------------
-- Table structure for `pma_relation`
-- ----------------------------
DROP TABLE IF EXISTS `pma_relation`;
CREATE TABLE `pma_relation` (
  `master_db` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `master_table` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `master_field` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `foreign_db` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `foreign_table` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `foreign_field` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  KEY `foreign_field` (`foreign_db`,`foreign_table`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- ----------------------------
-- Records of pma_relation
-- ----------------------------

-- ----------------------------
-- Table structure for `pma_table_coords`
-- ----------------------------
DROP TABLE IF EXISTS `pma_table_coords`;
CREATE TABLE `pma_table_coords` (
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `table_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT '0',
  `x` float unsigned NOT NULL DEFAULT '0',
  `y` float unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- ----------------------------
-- Records of pma_table_coords
-- ----------------------------

-- ----------------------------
-- Table structure for `pma_table_info`
-- ----------------------------
DROP TABLE IF EXISTS `pma_table_info`;
CREATE TABLE `pma_table_info` (
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `table_name` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `display_field` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`db_name`,`table_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- ----------------------------
-- Records of pma_table_info
-- ----------------------------

-- ----------------------------
-- Table structure for `realestate`
-- ----------------------------
DROP TABLE IF EXISTS `realestate`;
CREATE TABLE `realestate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `income` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `min_level` int(11) NOT NULL,
  `image_url` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of realestate
-- ----------------------------
INSERT INTO `realestate` VALUES ('1', 'Conrad\'s House', '300', '50', '1', '');
INSERT INTO `realestate` VALUES ('2', 'Lemonade Stand', '100', '60', '2', '');

-- ----------------------------
-- Table structure for `soldier_code`
-- ----------------------------
DROP TABLE IF EXISTS `soldier_code`;
CREATE TABLE `soldier_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(6) NOT NULL COMMENT 'all active codes to be assigned to users.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=2002 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of soldier_code
-- ----------------------------
INSERT INTO `soldier_code` VALUES ('1', 'EU4S96');
INSERT INTO `soldier_code` VALUES ('2', 'L0GWZK');
INSERT INTO `soldier_code` VALUES ('3', 'S507BR');
INSERT INTO `soldier_code` VALUES ('4', 'JT0VSG');
INSERT INTO `soldier_code` VALUES ('5', '4VW90K');
INSERT INTO `soldier_code` VALUES ('6', '5PLI49');
INSERT INTO `soldier_code` VALUES ('7', '5IKT01');
INSERT INTO `soldier_code` VALUES ('8', '1865J7');
INSERT INTO `soldier_code` VALUES ('9', '2I8N4E');
INSERT INTO `soldier_code` VALUES ('10', '460W70');
INSERT INTO `soldier_code` VALUES ('11', '7153JS');
INSERT INTO `soldier_code` VALUES ('12', '7464V9');
INSERT INTO `soldier_code` VALUES ('13', '39761B');
INSERT INTO `soldier_code` VALUES ('14', 'FJXM9T');
INSERT INTO `soldier_code` VALUES ('15', '2A6QBK');
INSERT INTO `soldier_code` VALUES ('16', '16ZI1S');
INSERT INTO `soldier_code` VALUES ('17', '8QM4H0');
INSERT INTO `soldier_code` VALUES ('18', '861FXB');
INSERT INTO `soldier_code` VALUES ('19', '1A54C1');
INSERT INTO `soldier_code` VALUES ('20', 'L56J3B');
INSERT INTO `soldier_code` VALUES ('21', 'PL08S0');
INSERT INTO `soldier_code` VALUES ('22', '257B79');
INSERT INTO `soldier_code` VALUES ('23', 'K12V4A');
INSERT INTO `soldier_code` VALUES ('24', 'OY6M3A');
INSERT INTO `soldier_code` VALUES ('25', 'T3L7N2');
INSERT INTO `soldier_code` VALUES ('26', '5ARZ44');
INSERT INTO `soldier_code` VALUES ('27', '9H88E9');
INSERT INTO `soldier_code` VALUES ('28', 'NZ072O');
INSERT INTO `soldier_code` VALUES ('29', 'G2636M');
INSERT INTO `soldier_code` VALUES ('30', 'SBYZ95');
INSERT INTO `soldier_code` VALUES ('31', '85GLFA');
INSERT INTO `soldier_code` VALUES ('32', 'I57096');
INSERT INTO `soldier_code` VALUES ('33', 'CJ7M1V');
INSERT INTO `soldier_code` VALUES ('34', '67O0I2');
INSERT INTO `soldier_code` VALUES ('35', '19N6Q1');
INSERT INTO `soldier_code` VALUES ('36', 'Z7T4MK');
INSERT INTO `soldier_code` VALUES ('37', '2O72Y2');
INSERT INTO `soldier_code` VALUES ('38', '5952OM');
INSERT INTO `soldier_code` VALUES ('39', '7KQ4JJ');
INSERT INTO `soldier_code` VALUES ('40', '4P1987');
INSERT INTO `soldier_code` VALUES ('41', 'UU5OC7');
INSERT INTO `soldier_code` VALUES ('42', 'EV85Q3');
INSERT INTO `soldier_code` VALUES ('43', 'R95U46');
INSERT INTO `soldier_code` VALUES ('44', 'VZ85CQ');
INSERT INTO `soldier_code` VALUES ('45', 'RE1M6K');
INSERT INTO `soldier_code` VALUES ('46', '6HMZ5U');
INSERT INTO `soldier_code` VALUES ('47', 'ZQK78C');
INSERT INTO `soldier_code` VALUES ('48', 'A99111');
INSERT INTO `soldier_code` VALUES ('49', 'W6P9MC');
INSERT INTO `soldier_code` VALUES ('50', 'B34LH8');
INSERT INTO `soldier_code` VALUES ('51', 'PJAIQ6');
INSERT INTO `soldier_code` VALUES ('52', 'BOI90F');
INSERT INTO `soldier_code` VALUES ('53', '41X1R9');
INSERT INTO `soldier_code` VALUES ('54', 'GYU0W4');
INSERT INTO `soldier_code` VALUES ('55', 'P2NNTG');
INSERT INTO `soldier_code` VALUES ('56', 'Y708W9');
INSERT INTO `soldier_code` VALUES ('57', 'I7BV2V');
INSERT INTO `soldier_code` VALUES ('58', '31MRI7');
INSERT INTO `soldier_code` VALUES ('59', '91822Y');
INSERT INTO `soldier_code` VALUES ('60', 'Q4184F');
INSERT INTO `soldier_code` VALUES ('61', '550CC8');
INSERT INTO `soldier_code` VALUES ('62', '1ZNL55');
INSERT INTO `soldier_code` VALUES ('63', 'OEAPA7');
INSERT INTO `soldier_code` VALUES ('64', '2868YC');
INSERT INTO `soldier_code` VALUES ('65', '13X03X');
INSERT INTO `soldier_code` VALUES ('66', 'Y383SZ');
INSERT INTO `soldier_code` VALUES ('67', '5H05E6');
INSERT INTO `soldier_code` VALUES ('68', '684A25');
INSERT INTO `soldier_code` VALUES ('70', '5KPG6A');
INSERT INTO `soldier_code` VALUES ('71', '9N80RP');
INSERT INTO `soldier_code` VALUES ('72', '83OG36');
INSERT INTO `soldier_code` VALUES ('73', 'JHD58J');
INSERT INTO `soldier_code` VALUES ('74', 'Y9Q256');
INSERT INTO `soldier_code` VALUES ('75', 'QVPFNR');
INSERT INTO `soldier_code` VALUES ('76', 'ZN063H');
INSERT INTO `soldier_code` VALUES ('77', '9J52B7');
INSERT INTO `soldier_code` VALUES ('78', '2EALE8');
INSERT INTO `soldier_code` VALUES ('79', '62D96V');
INSERT INTO `soldier_code` VALUES ('80', '92801N');
INSERT INTO `soldier_code` VALUES ('81', '957NRF');
INSERT INTO `soldier_code` VALUES ('82', 'IZU6RQ');
INSERT INTO `soldier_code` VALUES ('83', 'B7NGF0');
INSERT INTO `soldier_code` VALUES ('84', '6P0O83');
INSERT INTO `soldier_code` VALUES ('85', '28736T');
INSERT INTO `soldier_code` VALUES ('86', 'G38ZAA');
INSERT INTO `soldier_code` VALUES ('87', 'U371C4');
INSERT INTO `soldier_code` VALUES ('88', 'INOEJ8');
INSERT INTO `soldier_code` VALUES ('89', 'X27884');
INSERT INTO `soldier_code` VALUES ('90', 'BIH410');
INSERT INTO `soldier_code` VALUES ('91', 'W98RH9');
INSERT INTO `soldier_code` VALUES ('92', '7039SW');
INSERT INTO `soldier_code` VALUES ('93', 'XT48NE');
INSERT INTO `soldier_code` VALUES ('94', '78B92P');
INSERT INTO `soldier_code` VALUES ('95', '2777W4');
INSERT INTO `soldier_code` VALUES ('97', 'F3B55A');
INSERT INTO `soldier_code` VALUES ('98', 'E0EN17');
INSERT INTO `soldier_code` VALUES ('99', 'A65L70');
INSERT INTO `soldier_code` VALUES ('100', '2WD69S');

-- ----------------------------
-- Table structure for `soldier_code_backup`
-- ----------------------------
DROP TABLE IF EXISTS `soldier_code_backup`;
CREATE TABLE `soldier_code_backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(6) NOT NULL COMMENT 'all soldier codes generated till date consmued and not consumed',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=2001 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of soldier_code_backup
-- ----------------------------
INSERT INTO `soldier_code_backup` VALUES ('1', 'L0GWZK');
INSERT INTO `soldier_code_backup` VALUES ('2', 'S507BR');
INSERT INTO `soldier_code_backup` VALUES ('3', 'JT0VSG');
INSERT INTO `soldier_code_backup` VALUES ('4', '4VW90K');
INSERT INTO `soldier_code_backup` VALUES ('5', '5PLI49');
INSERT INTO `soldier_code_backup` VALUES ('6', '5IKT01');
INSERT INTO `soldier_code_backup` VALUES ('7', '1865J7');
INSERT INTO `soldier_code_backup` VALUES ('8', '2I8N4E');
INSERT INTO `soldier_code_backup` VALUES ('9', '460W70');
INSERT INTO `soldier_code_backup` VALUES ('10', '7153JS');
INSERT INTO `soldier_code_backup` VALUES ('11', '7464V9');
INSERT INTO `soldier_code_backup` VALUES ('12', '39761B');
INSERT INTO `soldier_code_backup` VALUES ('13', 'FJXM9T');
INSERT INTO `soldier_code_backup` VALUES ('14', '2A6QBK');
INSERT INTO `soldier_code_backup` VALUES ('15', '16ZI1S');
INSERT INTO `soldier_code_backup` VALUES ('16', '8QM4H0');
INSERT INTO `soldier_code_backup` VALUES ('17', '861FXB');
INSERT INTO `soldier_code_backup` VALUES ('18', '1A54C1');
INSERT INTO `soldier_code_backup` VALUES ('19', 'L56J3B');
INSERT INTO `soldier_code_backup` VALUES ('20', 'PL08S0');
INSERT INTO `soldier_code_backup` VALUES ('21', '257B79');
INSERT INTO `soldier_code_backup` VALUES ('22', 'K12V4A');
INSERT INTO `soldier_code_backup` VALUES ('23', 'OY6M3A');
INSERT INTO `soldier_code_backup` VALUES ('24', 'T3L7N2');
INSERT INTO `soldier_code_backup` VALUES ('25', '5ARZ44');
INSERT INTO `soldier_code_backup` VALUES ('26', '9H88E9');
INSERT INTO `soldier_code_backup` VALUES ('27', 'NZ072O');
INSERT INTO `soldier_code_backup` VALUES ('28', 'G2636M');
INSERT INTO `soldier_code_backup` VALUES ('29', 'SBYZ95');
INSERT INTO `soldier_code_backup` VALUES ('30', '85GLFA');
INSERT INTO `soldier_code_backup` VALUES ('31', 'I57096');
INSERT INTO `soldier_code_backup` VALUES ('32', 'CJ7M1V');
INSERT INTO `soldier_code_backup` VALUES ('33', '67O0I2');
INSERT INTO `soldier_code_backup` VALUES ('34', '19N6Q1');
INSERT INTO `soldier_code_backup` VALUES ('35', 'Z7T4MK');
INSERT INTO `soldier_code_backup` VALUES ('36', '2O72Y2');
INSERT INTO `soldier_code_backup` VALUES ('37', '5952OM');
INSERT INTO `soldier_code_backup` VALUES ('38', '7KQ4JJ');
INSERT INTO `soldier_code_backup` VALUES ('39', '4P1987');
INSERT INTO `soldier_code_backup` VALUES ('40', 'UU5OC7');
INSERT INTO `soldier_code_backup` VALUES ('41', 'EV85Q3');
INSERT INTO `soldier_code_backup` VALUES ('42', 'R95U46');
INSERT INTO `soldier_code_backup` VALUES ('43', 'VZ85CQ');
INSERT INTO `soldier_code_backup` VALUES ('44', 'RE1M6K');
INSERT INTO `soldier_code_backup` VALUES ('45', '6HMZ5U');
INSERT INTO `soldier_code_backup` VALUES ('46', 'ZQK78C');
INSERT INTO `soldier_code_backup` VALUES ('47', 'A99111');
INSERT INTO `soldier_code_backup` VALUES ('48', 'W6P9MC');
INSERT INTO `soldier_code_backup` VALUES ('49', 'B34LH8');
INSERT INTO `soldier_code_backup` VALUES ('50', 'PJAIQ6');
INSERT INTO `soldier_code_backup` VALUES ('51', 'BOI90F');
INSERT INTO `soldier_code_backup` VALUES ('52', '41X1R9');
INSERT INTO `soldier_code_backup` VALUES ('53', 'GYU0W4');
INSERT INTO `soldier_code_backup` VALUES ('54', 'P2NNTG');
INSERT INTO `soldier_code_backup` VALUES ('55', 'Y708W9');
INSERT INTO `soldier_code_backup` VALUES ('56', 'I7BV2V');
INSERT INTO `soldier_code_backup` VALUES ('57', '31MRI7');
INSERT INTO `soldier_code_backup` VALUES ('58', '91822Y');
INSERT INTO `soldier_code_backup` VALUES ('59', 'Q4184F');
INSERT INTO `soldier_code_backup` VALUES ('60', '550CC8');
INSERT INTO `soldier_code_backup` VALUES ('61', '1ZNL55');
INSERT INTO `soldier_code_backup` VALUES ('62', 'OEAPA7');
INSERT INTO `soldier_code_backup` VALUES ('63', '2868YC');
INSERT INTO `soldier_code_backup` VALUES ('64', '13X03X');
INSERT INTO `soldier_code_backup` VALUES ('65', 'Y383SZ');
INSERT INTO `soldier_code_backup` VALUES ('66', '5H05E6');
INSERT INTO `soldier_code_backup` VALUES ('67', '684A25');
INSERT INTO `soldier_code_backup` VALUES ('68', '0GP4X0');
INSERT INTO `soldier_code_backup` VALUES ('69', '5KPG6A');
INSERT INTO `soldier_code_backup` VALUES ('70', '9N80RP');
INSERT INTO `soldier_code_backup` VALUES ('71', '83OG36');
INSERT INTO `soldier_code_backup` VALUES ('72', 'JHD58J');
INSERT INTO `soldier_code_backup` VALUES ('73', 'Y9Q256');
INSERT INTO `soldier_code_backup` VALUES ('74', 'QVPFNR');
INSERT INTO `soldier_code_backup` VALUES ('75', 'ZN063H');
INSERT INTO `soldier_code_backup` VALUES ('76', '9J52B7');
INSERT INTO `soldier_code_backup` VALUES ('77', '2EALE8');
INSERT INTO `soldier_code_backup` VALUES ('78', '62D96V');
INSERT INTO `soldier_code_backup` VALUES ('79', '92801N');
INSERT INTO `soldier_code_backup` VALUES ('80', '957NRF');
INSERT INTO `soldier_code_backup` VALUES ('81', 'IZU6RQ');
INSERT INTO `soldier_code_backup` VALUES ('82', 'B7NGF0');
INSERT INTO `soldier_code_backup` VALUES ('83', '6P0O83');
INSERT INTO `soldier_code_backup` VALUES ('84', '28736T');
INSERT INTO `soldier_code_backup` VALUES ('85', 'G38ZAA');
INSERT INTO `soldier_code_backup` VALUES ('86', 'U371C4');
INSERT INTO `soldier_code_backup` VALUES ('87', 'INOEJ8');
INSERT INTO `soldier_code_backup` VALUES ('88', 'X27884');
INSERT INTO `soldier_code_backup` VALUES ('89', 'BIH410');
INSERT INTO `soldier_code_backup` VALUES ('90', 'W98RH9');
INSERT INTO `soldier_code_backup` VALUES ('91', '7039SW');
INSERT INTO `soldier_code_backup` VALUES ('92', 'XT48NE');
INSERT INTO `soldier_code_backup` VALUES ('93', '78B92P');
INSERT INTO `soldier_code_backup` VALUES ('94', '2777W4');
INSERT INTO `soldier_code_backup` VALUES ('95', '01PSY4');
INSERT INTO `soldier_code_backup` VALUES ('96', 'F3B55A');
INSERT INTO `soldier_code_backup` VALUES ('97', 'E0EN17');
INSERT INTO `soldier_code_backup` VALUES ('98', 'A65L70');
INSERT INTO `soldier_code_backup` VALUES ('99', '2WD69S');
INSERT INTO `soldier_code_backup` VALUES ('100', 'X94789');

-- ----------------------------
-- Table structure for `special_looted_items`
-- ----------------------------
DROP TABLE IF EXISTS `special_looted_items`;
CREATE TABLE `special_looted_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `effected_attribute` varchar(50) NOT NULL,
  `percent_effect` tinyint(4) NOT NULL,
  `image_url` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of special_looted_items
-- ----------------------------

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
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
  `is_first_battle` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `recruit_code_UNIQUE` (`agency_code`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('100', '', 'test1', '10', '1', '12', '0', '0', '0', '10', '0', '100', '0', '10', '0', '1', '5', '0', '0', '0', '0', '100', '100', '3', '1', '2', '2011-08-03 23:51:39', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('200', '', 'test2', '10', '1', '14', '0', '0', '6161', '2', '0', '100', '2', '190', '0', '0', '5', '0', '0', '0', '0', '100', '100', '3', '2', '1', '2011-11-02 13:16:24', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('300', '', 'test3', '10', '1', '15', '4', '0', '0', '12', '0', '100', '0', '81', '0', '7', '0', '0', '0', '0', '0', '100', '100', '3', '3', '1', null, '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('4', '', 'test4', '6', '1', '16', '4', '0', '1000', '0', '0', '100', '0', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', '4', '2', '2011-08-17 01:18:35', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('5', '', 'test5', '6', '1', '15', '3', '0', '3077', '0', '100', '100', '0', '70', '0', '0', '2', '0', '0', '0', '0', '100', '100', '3', '5', '2', '2011-11-02 13:15:38', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('6', '', 'test6', '1', '1', '12', '10', '0', '0', '0', '100', '100', '0', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', '6', '1', null, '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('7', '', 'test7', '1', '1', '12', '10', '0', '0', '0', '100', '100', '0', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', '7', '1', null, '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('8', '', 'test8', '1', '1', '12', '10', '0', '0', '0', '100', '100', '0', '55', '0', '0', '3', '0', '0', '0', '0', '100', '100', '3', '8', '1', null, '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('9', '', 'test9', '1', '1', '12', '10', '0', '0', '0', '100', '100', '0', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', '9', '1', null, '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('10', '', 'test10', '12', '1', '30', '15', '2960', '166431', '123', '41', '160', '97', '970', '99', '47', '8', '0', '0', '0', '0', '1000', '100', '5', '10', '7', '2011-10-01 00:15:16', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('11', '', 'test11', '11', '1', '12', '10', '0', '3508', '0', '100', '100', '0', '835', '0', '0', '17', '0', '0', '0', '0', '100', '100', '3', '11', '3', '2011-10-26 09:40:20', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('12', '1FFSEF', 'test12', '12', '2', '12', '10', '0', '30059', '6', '97', '100', '0', '10', '0', '3', '3', '0', '0', '1400', '0', '100', '100', '3', '12', '2', '2011-10-26 13:17:16', '1', '0', '0', '1', '1', '0', null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('13', '', 'test13', '16', '2', '13', '9', '0', '1736', '37', '89', '100', '0', '85', '0', '11', '0', '0', '0', '0', '0', '100', '100', '3', '66kl66', '2', '2011-10-21 11:05:55', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('14', '', 'test14', '1', '2', '12', '10', '0', '0', '1', '100', '100', '0', '85', '0', '1', '0', '0', '0', '0', '0', '100', '100', '3', '14', '1', null, '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('15', '', 'test15', '7', '2', '12', '10', '0', '0', '0', '100', '100', '0', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', '15', '1', null, '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('16', '', 'test16', '1', '2', '12', '10', '0', '0', '0', '3', '100', '0', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', '16', '1', null, '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('17', '', 'test17', '1', '2', '16', '13', '0', '0', '0', '3', '100', '41', '100', '0', '0', '0', '0', '0', '0', '0', '102', '101', '4', '17', '1', null, '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('18', '', 'test18', '2305', '2', '12', '15', '210', '2840', '23058', '199', '97100', '6894', '40', '365', '1', '3', '0', '0', '0', '0', '170', '1003', '9', '18', '3', '2011-10-01 00:27:33', '1', '4', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('19', '', 'test19', '1', '2', '13', '11', '0', '1000', '0', '3', '100', '7', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '4', '19', '1', '2011-09-03 19:12:22', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('20', '', 'Calvin', '1', '2', '14', '8', '0', '4012', '2', '3', '100', '10', '85', '0', '1', '0', '0', '0', '0', '0', '100', '100', '3', '20', '1', '2011-10-17 13:29:16', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('21', '', 'Alex', '39', '2', '17', '10', '900', '47213', '397', '20', '103', '99', '70', '63', '2', '0', '0', '0', '0', '0', '130', '101', '400', '21', '8', '2011-10-12 12:27:36', '3', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('22', '', 'Conrad', '2', '2', '14', '10', '0', '1529', '0', '3', '100', '0', '85', '0', '0', '1', '0', '0', '0', '0', '100', '100', '3', '22', '1', '2011-10-12 12:23:00', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('23', '', 'test23', '1', '2', '12', '10', '0', '0', '0', '3', '100', '10', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', '23', '1', '2011-08-07 00:46:50', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('24', '', 'test24', '6', '2', '13', '9', '0', '505', '0', '3', '100', '10', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', '24', '1', '2011-10-06 13:57:53', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('25', '', 'test25', '1', '2', '0', '0', '0', '4441', '0', '3', '100', '10', '85', '0', '0', '1', '0', '0', '0', '0', '100', '100', '3', '25', '1', '2011-09-20 16:38:30', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('26', '', 'test26', '1', null, '0', '0', '0', '17483', '0', '3', '100', '10', '85', '0', '0', '1', '0', '0', '0', '0', '100', '100', '3', '26', '1', '2011-10-26 13:17:10', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('27', '', 'test27', '1', null, '0', '0', '0', '0', '0', '3', '100', '10', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', '27', '1', '2011-08-11 19:41:07', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('28', '', 'test28', '1', null, '0', '0', '0', '588', '80', '3', '80', '10', '100', '2', '0', '0', '0', '0', '0', '0', '100', '100', '3', '44kk44', '0', '2011-08-11 19:50:33', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('29', '', 'test29', '37', '1', '19', '16', '54900', '38155', '375', '95', '99', '5', '10', '31', '6', '0', '0', '0', '5000', '2000', '320', '101', '4', '44hh44', '2', '2011-10-31 07:52:57', '1', '0', '1', '0', '1', '1', null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('30', '', 'test30', '4', '1', '0', '0', '0', '6406', '15', '3', '60', '10', '100', '2', '0', '0', '0', '0', '0', '0', '100', '100', '3', '30', '1', '2011-10-12 11:42:58', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('31', '', 'test31', '3', '2', '0', '0', '0', '29706', '0', '3', '100', '10', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', '31', '0', '2011-10-21 10:53:06', '2', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('32', '', 'test32', '1', null, '0', '0', '0', '973', '0', '3', '100', '10', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', '32', '0', '2011-10-18 07:21:14', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('33', '', 'test33', '1', null, '0', '0', '0', '6543', '0', '3', '100', '10', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', '33', '1', '2011-10-18 07:22:01', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('34', '', 'test34', '1', null, '0', '0', '0', '2833', '0', '3', '100', '10', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', '34', '1', '2011-10-26 12:25:00', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('35', '', 'test35', '1', '3', '0', '0', '0', '2357', '0', '3', '100', '10', '55', '0', '0', '3', '0', '0', '0', '0', '100', '100', '3', '35', '0', '2011-10-18 07:41:32', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('36', '', 'test36', '1', null, '0', '0', '0', '1183', '0', '3', '100', '10', '85', '0', '0', '1', '0', '0', '0', '0', '100', '100', '3', '36', '1', '2011-10-25 10:51:34', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('37', '', '18', '1', null, '0', '0', '0', '5605', '1', '3', '93', '10', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', '37', '1', '2011-10-25 10:52:18', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('38', '', 'ruby', '1', null, '0', '0', '0', '0', '0', '3', '100', '10', '70', '0', '0', '2', '0', '0', '0', '0', '100', '100', '3', '38', '1', '2011-09-12 04:00:01', '0', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('39', '', 'q', '1', null, '0', '0', '0', '0', '0', '3', '100', '10', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', '39', '1', '2011-09-17 18:04:45', '0', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('40', '', 'water', '2', null, '0', '0', '0', '10266', '28', '3', '40', '13', '100', '6', '0', '0', '0', '0', '0', '0', '100', '100', '3', '40', '1', '2011-10-17 13:26:01', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('41', '', 'Fire', '1', null, '0', '0', '0', '295', '4', '3', '90', '10', '100', '1', '0', '0', '0', '0', '0', '0', '100', '100', '3', '41', '0', '2011-10-01 06:56:33', '0', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('42', '', 'conrad', '1', null, '0', '0', '0', '771', '12', '3', '80', '10', '85', '2', '0', '1', '0', '0', '0', '0', '100', '100', '3', null, '1', '2011-10-01 07:19:40', '0', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('43', '', 'Asher', '38', null, '0', '0', '145', '40909', '386', '100', '0', '302', '0', '71', '0', '0', '0', '0', '0', '0', '100', '100', '3', null, '10', '2011-10-13 14:48:10', '1', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('44', 'Array', '', '1', null, '0', '0', '0', '0', '0', '3', '100', '10', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', null, '1', '2011-10-24 22:24:34', '0', '0', null, null, null, null, '1asdfasdfasdf', null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('45', '00274F', '', '1', null, '0', '0', '0', '599', '0', '3', '100', '10', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', null, '1', '2011-10-26 12:24:55', '1', '0', null, null, null, null, '1asdfasdfasdf1', null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('46', '002SPY', '', '1', null, '0', '0', '0', '12195', '15', '3', '0', '10', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', null, '1', '2011-10-26 14:56:35', '2', '0', null, null, null, null, '1asdfasdfasdf465465465', null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('47', '0043U7', '', '1', null, '0', '0', '0', '8913', '0', '3', '100', '10', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', null, '1', '2011-10-26 14:56:42', '1', '0', null, null, null, null, '1asdfasdfasdf46546546564646', null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('48', '0043U8', 'Waseem Mansha', '1', null, '0', '0', '0', '0', '0', '3', '100', '10', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', null, '1', '2011-10-25 10:19:56', '0', '0', null, null, null, null, null, null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('49', '004B3F', 'Gondal', '5', '2', '0', '0', '0', '156688', '98', '3', '100', '25', '100', '15', '1', '0', '1', '0', '0', '2000', '100', '100', '3', null, '1', '2011-11-02 14:22:33', '1', '0', null, null, null, null, 'tjmrumuek', null, null, null, '143', '0', '0', '0');
INSERT INTO `users` VALUES ('50', '00G5XG', '', '1', null, '0', '0', '0', '125', '1', '3', '93', '10', '100', '0', '0', '0', '0', '0', '0', '1000', '100', '100', '3', null, '1', '2011-10-25 11:36:03', '0', '0', null, null, null, null, '4564ddfasdfasdf465', null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('51', '017XFP', 'Gondal1', '8', '3', '9', '5', '413', '64825', '85', '0', '50', '0', '100', '17', '3', '0', '0', '0', '0', '0', '100', '107', '8', null, '2', '2011-10-25 12:24:36', '0', '0', null, null, null, null, '456465SDFASD', null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('52', '019T03', 'sasasa', '10', '1', '4', '7', '0', '68420', '104', '0', '37', '0', '55', '16', '2', '1', '0', '0', '1800', '0', '100', '100', '16', null, '3', '2011-10-26 14:57:09', '1', '0', null, null, null, null, '65464FFFSDF', null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('53', '01H1Q5', 'Gondal12', '5', '2', '0', '0', '0', '16018', '78', '2', '56', '22', '24954', '15', '1', '0', '0', '0', '0', '0', '100', '100', '3', null, '1', '2011-10-25 13:21:51', '0', '0', null, null, null, null, '4564sdfasfASDF', null, null, '65465DFSDFsdf', null, '0', '0', '0');
INSERT INTO `users` VALUES ('54', '01PSY4', '', '1', null, '0', '0', '0', '0', '0', '3', '100', '10', '100', '0', '0', '0', '0', '0', '0', '0', '100', '100', '3', null, '1', '2011-10-25 15:35:11', '0', '0', null, null, null, null, '65464FFFSDF45646', null, null, null, null, '0', '0', '0');
INSERT INTO `users` VALUES ('55', '0GP4X0', 'Faizan', '9', '3', '0', '0', '0', '3677757', '566', '24989', '100', '58', '62', '98', '6', '1', '0', '1', '0', '1000', '100', '100', '3', null, '1', '2011-11-02 14:05:29', '1', '0', null, null, null, null, '03938948400', null, null, null, '749', '42', '0', '1');

-- ----------------------------
-- Table structure for `users_achievements`
-- ----------------------------
DROP TABLE IF EXISTS `users_achievements`;
CREATE TABLE `users_achievements` (
  `user_id` int(11) NOT NULL,
  `victor_level` tinyint(1) NOT NULL DEFAULT '0',
  `fall_guy_level` tinyint(1) NOT NULL DEFAULT '0',
  `marksman_level` tinyint(1) NOT NULL DEFAULT '0',
  `master_level` tinyint(1) NOT NULL DEFAULT '0',
  `monster_level` tinyint(1) NOT NULL DEFAULT '0',
  `prodigy_level` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users_achievements
-- ----------------------------
INSERT INTO `users_achievements` VALUES ('53', '0', '0', '0', '0', '0', '0');
INSERT INTO `users_achievements` VALUES ('55', '2', '1', '0', '0', '0', '0');
INSERT INTO `users_achievements` VALUES ('2', '0', '2', '0', '0', '0', '0');
INSERT INTO `users_achievements` VALUES ('49', '1', '0', '2', '1', '0', '1');

-- ----------------------------
-- Table structure for `users_cities`
-- ----------------------------
DROP TABLE IF EXISTS `users_cities`;
CREATE TABLE `users_cities` (
  `user_id` int(11) unsigned NOT NULL,
  `city_id` int(11) unsigned NOT NULL,
  `rank_avail` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users_cities
-- ----------------------------
INSERT INTO `users_cities` VALUES ('10', '1', '4');
INSERT INTO `users_cities` VALUES ('18', '1', '1');
INSERT INTO `users_cities` VALUES ('18', '2', '4');
INSERT INTO `users_cities` VALUES ('23', '1', '1');
INSERT INTO `users_cities` VALUES ('24', '1', '1');
INSERT INTO `users_cities` VALUES ('25', '1', '1');
INSERT INTO `users_cities` VALUES ('26', '1', '1');
INSERT INTO `users_cities` VALUES ('27', '1', '1');
INSERT INTO `users_cities` VALUES ('28', '1', '1');
INSERT INTO `users_cities` VALUES ('29', '1', '4');
INSERT INTO `users_cities` VALUES ('29', '2', '2');
INSERT INTO `users_cities` VALUES ('29', '3', '1');
INSERT INTO `users_cities` VALUES ('29', '4', '1');
INSERT INTO `users_cities` VALUES ('30', '1', '2');
INSERT INTO `users_cities` VALUES ('31', '1', '1');
INSERT INTO `users_cities` VALUES ('32', '1', '1');
INSERT INTO `users_cities` VALUES ('33', '1', '1');
INSERT INTO `users_cities` VALUES ('34', '1', '1');
INSERT INTO `users_cities` VALUES ('35', '1', '1');
INSERT INTO `users_cities` VALUES ('36', '1', '1');
INSERT INTO `users_cities` VALUES ('37', '1', '1');
INSERT INTO `users_cities` VALUES ('38', '1', '1');
INSERT INTO `users_cities` VALUES ('39', '1', '1');
INSERT INTO `users_cities` VALUES ('40', '1', '1');
INSERT INTO `users_cities` VALUES ('41', '1', '1');
INSERT INTO `users_cities` VALUES ('42', '1', '1');
INSERT INTO `users_cities` VALUES ('43', '1', '2');
INSERT INTO `users_cities` VALUES ('43', '2', '1');
INSERT INTO `users_cities` VALUES ('43', '3', '1');
INSERT INTO `users_cities` VALUES ('43', '4', '1');
INSERT INTO `users_cities` VALUES ('44', '1', '1');
INSERT INTO `users_cities` VALUES ('44', '2', '1');
INSERT INTO `users_cities` VALUES ('44', '3', '1');
INSERT INTO `users_cities` VALUES ('44', '4', '1');
INSERT INTO `users_cities` VALUES ('45', '1', '1');
INSERT INTO `users_cities` VALUES ('45', '2', '1');
INSERT INTO `users_cities` VALUES ('45', '3', '1');
INSERT INTO `users_cities` VALUES ('45', '4', '1');
INSERT INTO `users_cities` VALUES ('46', '1', '1');
INSERT INTO `users_cities` VALUES ('46', '2', '1');
INSERT INTO `users_cities` VALUES ('46', '3', '1');
INSERT INTO `users_cities` VALUES ('46', '4', '1');
INSERT INTO `users_cities` VALUES ('47', '1', '1');
INSERT INTO `users_cities` VALUES ('47', '2', '1');
INSERT INTO `users_cities` VALUES ('47', '3', '1');
INSERT INTO `users_cities` VALUES ('47', '4', '1');
INSERT INTO `users_cities` VALUES ('48', '1', '1');
INSERT INTO `users_cities` VALUES ('48', '2', '1');
INSERT INTO `users_cities` VALUES ('48', '3', '1');
INSERT INTO `users_cities` VALUES ('48', '4', '1');
INSERT INTO `users_cities` VALUES ('49', '1', '1');
INSERT INTO `users_cities` VALUES ('49', '2', '1');
INSERT INTO `users_cities` VALUES ('49', '3', '1');
INSERT INTO `users_cities` VALUES ('49', '4', '1');
INSERT INTO `users_cities` VALUES ('50', '1', '1');
INSERT INTO `users_cities` VALUES ('50', '2', '1');
INSERT INTO `users_cities` VALUES ('50', '3', '1');
INSERT INTO `users_cities` VALUES ('50', '4', '1');
INSERT INTO `users_cities` VALUES ('51', '1', '1');
INSERT INTO `users_cities` VALUES ('51', '2', '1');
INSERT INTO `users_cities` VALUES ('51', '3', '1');
INSERT INTO `users_cities` VALUES ('51', '4', '1');
INSERT INTO `users_cities` VALUES ('52', '1', '1');
INSERT INTO `users_cities` VALUES ('52', '2', '1');
INSERT INTO `users_cities` VALUES ('52', '3', '1');
INSERT INTO `users_cities` VALUES ('52', '4', '1');
INSERT INTO `users_cities` VALUES ('53', '1', '1');
INSERT INTO `users_cities` VALUES ('53', '2', '1');
INSERT INTO `users_cities` VALUES ('53', '3', '1');
INSERT INTO `users_cities` VALUES ('53', '4', '1');
INSERT INTO `users_cities` VALUES ('54', '1', '1');
INSERT INTO `users_cities` VALUES ('54', '2', '1');
INSERT INTO `users_cities` VALUES ('54', '3', '1');
INSERT INTO `users_cities` VALUES ('54', '4', '1');
INSERT INTO `users_cities` VALUES ('55', '1', '1');
INSERT INTO `users_cities` VALUES ('55', '2', '1');
INSERT INTO `users_cities` VALUES ('55', '3', '1');
INSERT INTO `users_cities` VALUES ('55', '4', '1');

-- ----------------------------
-- Table structure for `users_dailybonuses`
-- ----------------------------
DROP TABLE IF EXISTS `users_dailybonuses`;
CREATE TABLE `users_dailybonuses` (
  `user_id` int(11) unsigned NOT NULL,
  `day1` int(11) unsigned DEFAULT '0',
  `day2` int(11) unsigned DEFAULT '0',
  `day3` int(11) unsigned DEFAULT '0',
  `day4` int(11) unsigned DEFAULT '0',
  `day5` int(11) unsigned DEFAULT '0',
  `day6` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users_dailybonuses
-- ----------------------------
INSERT INTO `users_dailybonuses` VALUES ('5', '1000', '0', '0', '0', '0', '0');
INSERT INTO `users_dailybonuses` VALUES ('10', '7145', '0', '0', '0', '0', '0');
INSERT INTO `users_dailybonuses` VALUES ('11', '1000', '0', '0', '0', '0', '0');
INSERT INTO `users_dailybonuses` VALUES ('12', '1000', '7326', '917', '0', '0', '0');
INSERT INTO `users_dailybonuses` VALUES ('18', '0', '0', '0', '0', '0', '0');
INSERT INTO `users_dailybonuses` VALUES ('19', '1000', '0', '0', '0', '0', '0');
INSERT INTO `users_dailybonuses` VALUES ('21', '0', '4889', '6924', '0', '0', '0');
INSERT INTO `users_dailybonuses` VALUES ('26', '0', '6432', '0', '0', '0', '0');
INSERT INTO `users_dailybonuses` VALUES ('29', '0', '14476', '15003', '9456', '4880', '4396');
INSERT INTO `users_dailybonuses` VALUES ('31', '0', '9483', '0', '0', '0', '0');
INSERT INTO `users_dailybonuses` VALUES ('43', '8391', '0', '0', '0', '0', '0');
INSERT INTO `users_dailybonuses` VALUES ('46', '6549', '2428', '0', '0', '0', '0');
INSERT INTO `users_dailybonuses` VALUES ('52', '6725', '0', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for `users_items`
-- ----------------------------
DROP TABLE IF EXISTS `users_items`;
CREATE TABLE `users_items` (
  `user_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned NOT NULL,
  `quantity` int(11) unsigned NOT NULL,
  `is_looted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`item_id`),
  KEY `user_id` (`user_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users_items
-- ----------------------------
INSERT INTO `users_items` VALUES ('10', '1', '29', '0');
INSERT INTO `users_items` VALUES ('10', '2', '4', '0');
INSERT INTO `users_items` VALUES ('12', '1', '1', '0');
INSERT INTO `users_items` VALUES ('13', '1', '3', '0');
INSERT INTO `users_items` VALUES ('13', '2', '1', '0');
INSERT INTO `users_items` VALUES ('18', '1', '1', '0');
INSERT INTO `users_items` VALUES ('18', '2', '64', '0');
INSERT INTO `users_items` VALUES ('21', '1', '13', '0');
INSERT INTO `users_items` VALUES ('21', '2', '5', '0');
INSERT INTO `users_items` VALUES ('24', '1', '2', '0');
INSERT INTO `users_items` VALUES ('29', '1', '815', '0');
INSERT INTO `users_items` VALUES ('29', '2', '164', '0');
INSERT INTO `users_items` VALUES ('29', '4', '3', '1');
INSERT INTO `users_items` VALUES ('40', '1', '3', '0');
INSERT INTO `users_items` VALUES ('41', '1', '1', '0');
INSERT INTO `users_items` VALUES ('42', '1', '1', '0');
INSERT INTO `users_items` VALUES ('43', '1', '6', '0');
INSERT INTO `users_items` VALUES ('43', '2', '1', '0');
INSERT INTO `users_items` VALUES ('43', '3', '2', '0');
INSERT INTO `users_items` VALUES ('49', '1', '9', '0');
INSERT INTO `users_items` VALUES ('50', '2', '1', '0');
INSERT INTO `users_items` VALUES ('51', '1', '5', '0');
INSERT INTO `users_items` VALUES ('51', '2', '1', '0');
INSERT INTO `users_items` VALUES ('51', '3', '1', '0');
INSERT INTO `users_items` VALUES ('52', '1', '507', '0');
INSERT INTO `users_items` VALUES ('52', '2', '1', '0');
INSERT INTO `users_items` VALUES ('53', '1', '2', '0');
INSERT INTO `users_items` VALUES ('53', '2', '2', '0');
INSERT INTO `users_items` VALUES ('55', '1', '10', '0');
INSERT INTO `users_items` VALUES ('55', '2', '1', '0');

-- ----------------------------
-- Table structure for `users_missions`
-- ----------------------------
DROP TABLE IF EXISTS `users_missions`;
CREATE TABLE `users_missions` (
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

-- ----------------------------
-- Records of users_missions
-- ----------------------------
INSERT INTO `users_missions` VALUES ('29', '1', '1', '1', '0', '0', '1');
INSERT INTO `users_missions` VALUES ('49', '1', '4', '4', '0', '0', '2');
INSERT INTO `users_missions` VALUES ('49', '3', '1', '1', '0', '0', '1');
INSERT INTO `users_missions` VALUES ('49', '7', '4', '4', '0', '0', '2');
INSERT INTO `users_missions` VALUES ('51', '1', '8', '8', '0', '0', '2');
INSERT INTO `users_missions` VALUES ('51', '2', '3', '3', '0', '0', '2');
INSERT INTO `users_missions` VALUES ('51', '3', '4', '4', '0', '0', '2');
INSERT INTO `users_missions` VALUES ('51', '7', '1', '1', '0', '0', '1');
INSERT INTO `users_missions` VALUES ('51', '9', '1', '1', '0', '0', '1');
INSERT INTO `users_missions` VALUES ('52', '1', '4', '4', '0', '0', '2');
INSERT INTO `users_missions` VALUES ('52', '2', '4', '4', '0', '0', '2');
INSERT INTO `users_missions` VALUES ('52', '3', '5', '5', '0', '0', '2');
INSERT INTO `users_missions` VALUES ('52', '4', '1', '1', '0', '0', '1');
INSERT INTO `users_missions` VALUES ('52', '7', '2', '2', '0', '0', '1');
INSERT INTO `users_missions` VALUES ('53', '1', '5', '5', '0', '0', '2');
INSERT INTO `users_missions` VALUES ('53', '2', '8', '8', '0', '0', '2');
INSERT INTO `users_missions` VALUES ('53', '3', '2', '2', '0', '0', '1');
INSERT INTO `users_missions` VALUES ('55', '1', '42', '42', '0', '0', '2');
INSERT INTO `users_missions` VALUES ('55', '2', '14', '14', '0', '0', '2');
INSERT INTO `users_missions` VALUES ('55', '6', '42', '42', '0', '0', '2');

-- ----------------------------
-- Table structure for `users_realestates`
-- ----------------------------
DROP TABLE IF EXISTS `users_realestates`;
CREATE TABLE `users_realestates` (
  `user_id` int(10) unsigned NOT NULL,
  `realestate_id` int(10) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`realestate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users_realestates
-- ----------------------------
INSERT INTO `users_realestates` VALUES ('12', '1', '4');
INSERT INTO `users_realestates` VALUES ('12', '2', '2');
INSERT INTO `users_realestates` VALUES ('52', '1', '6');

-- ----------------------------
-- Table structure for `users_special_looted_items`
-- ----------------------------
DROP TABLE IF EXISTS `users_special_looted_items`;
CREATE TABLE `users_special_looted_items` (
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quanity` int(11) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`user_id`,`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users_special_looted_items
-- ----------------------------

-- ----------------------------
-- Table structure for `user_blackmarket_items`
-- ----------------------------
DROP TABLE IF EXISTS `user_blackmarket_items`;
CREATE TABLE `user_blackmarket_items` (
  `user_id` int(11) unsigned NOT NULL,
  `bm_id` int(11) unsigned NOT NULL,
  `quantity` int(255) DEFAULT '0',
  PRIMARY KEY (`user_id`,`bm_id`),
  KEY `user_id` (`user_id`),
  KEY `bm_id` (`bm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of user_blackmarket_items
-- ----------------------------

-- ----------------------------
-- Table structure for `user_looted_items`
-- ----------------------------
DROP TABLE IF EXISTS `user_looted_items`;
CREATE TABLE `user_looted_items` (
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `is_special` tinyint(4) NOT NULL,
  PRIMARY KEY (`user_id`,`item_id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of user_looted_items
-- ----------------------------
INSERT INTO `user_looted_items` VALUES ('55', '5', '7', '0');
INSERT INTO `user_looted_items` VALUES ('55', '1', '2', '0');

-- ----------------------------
-- Table structure for `user_timers`
-- ----------------------------
DROP TABLE IF EXISTS `user_timers`;
CREATE TABLE `user_timers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `energy_timer` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `health_timer` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `income_timer` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `stamina_timer` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of user_timers
-- ----------------------------
INSERT INTO `user_timers` VALUES ('4', '55', '0000-00-00 00:00:00', '2011-11-02 19:21:24', '2011-11-02 18:48:21', '0000-00-00 00:00:00');
DELIMITER ;;
CREATE TRIGGER `Archive` BEFORE UPDATE ON `apns_devices` FOR EACH ROW INSERT INTO `apns_device_history` VALUES (
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
;;
DELIMITER ;
