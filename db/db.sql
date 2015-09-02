# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.21)
# Database: sportalize
# Generation Time: 2015-09-02 21:41:29 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table menu_link
# ------------------------------------------------------------

DROP TABLE IF EXISTS `menu_link`;

CREATE TABLE `menu_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  `parent_menu_link_id` int(11) unsigned DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_ml_ml` (`parent_menu_link_id`),
  CONSTRAINT `FK_ml_ml` FOREIGN KEY (`parent_menu_link_id`) REFERENCES `menu_link` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `menu_link` WRITE;
/*!40000 ALTER TABLE `menu_link` DISABLE KEYS */;

INSERT INTO `menu_link` (`id`, `title`, `url`, `parent_menu_link_id`, `display_order`)
VALUES
    (1,'Notifications','javascript:void(0)',NULL,1),
    (2,'Refresh Me','javascript:void(0)',NULL,2),
    (3,'My Posts','javascript:void(0)',NULL,3),
    (4,'Games','javascript:void(0)',NULL,4),
    (5,'More','javascript:void(0)',NULL,5),
    (6,'Fantasy','javascript:void(0)',4,1),
    (7,'Quiz','javascript:void(0)',4,2),
    (8,'Virtual Booking','javascript:void(0)',4,3),
    (9,'New Page','javascript:void(0)',5,1),
    (10,'Galery','javascript:void(0)',5,2),
    (11,'Personal Settings','javascript:void(0)',5,3),
    (12,'Favorites','javascript:void(0)',5,4),
    (13,'Sign Out','/login/index/sign-out',5,5);

/*!40000 ALTER TABLE `menu_link` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table page
# ------------------------------------------------------------

DROP TABLE IF EXISTS `page`;

CREATE TABLE `page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table page_widget
# ------------------------------------------------------------

DROP TABLE IF EXISTS `page_widget`;

CREATE TABLE `page_widget` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(11) unsigned DEFAULT NULL,
  `widget_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pw_pa` (`page_id`),
  KEY `FK_pw_wi` (`widget_id`),
  CONSTRAINT `FK_pw_pa` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`),
  CONSTRAINT `FK_pw_wi` FOREIGN KEY (`widget_id`) REFERENCES `widget` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(256) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `token` varchar(256) DEFAULT NULL,
  `token_time_generation` int(11) DEFAULT NULL,
  `activated` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table user_widget
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_widget`;

CREATE TABLE `user_widget` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `widget_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_uw_us` (`user_id`),
  KEY `FK_uw` (`widget_id`),
  CONSTRAINT `FK_uw` FOREIGN KEY (`widget_id`) REFERENCES `widget` (`id`),
  CONSTRAINT `FK_uw_us` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table widget
# ------------------------------------------------------------

DROP TABLE IF EXISTS `widget`;

CREATE TABLE `widget` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `is_system` tinyint(4) DEFAULT '0',
  `page_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `widget` WRITE;
/*!40000 ALTER TABLE `widget` DISABLE KEYS */;

INSERT INTO `widget` (`id`, `title`, `is_system`, `page_id`)
VALUES
    (1,'Favourite Pages',1,NULL),
    (2,'Upcoming Games',1,NULL),
    (3,'Best Scorers',1,NULL),
    (4,'Premier League Table',1,NULL);

/*!40000 ALTER TABLE `widget` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table widget_item_favourite_page
# ------------------------------------------------------------

DROP TABLE IF EXISTS `widget_item_favourite_page`;

CREATE TABLE `widget_item_favourite_page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `widget_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `linked_page_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_wif_wi` (`widget_id`),
  KEY `FK_wif_lp` (`linked_page_id`),
  CONSTRAINT `FK_wif_lp` FOREIGN KEY (`linked_page_id`) REFERENCES `page` (`id`),
  CONSTRAINT `FK_wif_wi` FOREIGN KEY (`widget_id`) REFERENCES `widget` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table widget_item_list_option
# ------------------------------------------------------------

DROP TABLE IF EXISTS `widget_item_list_option`;

CREATE TABLE `widget_item_list_option` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `widget_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_wil_wi` (`widget_id`),
  CONSTRAINT `FK_wil_wi` FOREIGN KEY (`widget_id`) REFERENCES `widget` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table widget_item_list_option_item
# ------------------------------------------------------------

DROP TABLE IF EXISTS `widget_item_list_option_item`;

CREATE TABLE `widget_item_list_option_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `value` varchar(256) DEFAULT NULL,
  `widget_item_list_option_id` int(11) unsigned DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_wiloi_wilo` (`widget_item_list_option_id`),
  CONSTRAINT `FK_wiloi_wilo` FOREIGN KEY (`widget_item_list_option_id`) REFERENCES `widget_item_list_option` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table widget_item_lweb_option
# ------------------------------------------------------------

DROP TABLE IF EXISTS `widget_item_lweb_option`;

CREATE TABLE `widget_item_lweb_option` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `widget_id` int(11) unsigned DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_wilwebo_wi` (`widget_id`),
  CONSTRAINT `FK_wilwebo_wi` FOREIGN KEY (`widget_id`) REFERENCES `widget` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table widget_item_lweb_option_item
# ------------------------------------------------------------

DROP TABLE IF EXISTS `widget_item_lweb_option_item`;

CREATE TABLE `widget_item_lweb_option_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `widget_item_lweb_option_id` int(11) unsigned DEFAULT NULL,
  `left_image` varchar(256) DEFAULT NULL,
  `right_image` varchar(256) DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_wilweboi_wilwebo` (`widget_item_lweb_option_id`),
  CONSTRAINT `FK_wilweboi_wilwebo` FOREIGN KEY (`widget_item_lweb_option_id`) REFERENCES `widget_item_lweb_option_item` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table widget_item_lweb_option_item_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `widget_item_lweb_option_item_data`;

CREATE TABLE `widget_item_lweb_option_item_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `widget_item_lweb_option_item_id` int(11) unsigned DEFAULT NULL,
  `placement` enum('MAIN','ADDITIONAL') DEFAULT NULL,
  `left_value` varchar(256) DEFAULT NULL,
  `right_value` varchar(256) DEFAULT NULL,
  `additional_value` varchar(256) DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_wilweboid_wilweboi` (`widget_item_lweb_option_item_id`),
  CONSTRAINT `FK_wilweboid_wilweboi` FOREIGN KEY (`widget_item_lweb_option_item_id`) REFERENCES `widget_item_lweb_option_item` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table widget_item_plain
# ------------------------------------------------------------

DROP TABLE IF EXISTS `widget_item_plain`;

CREATE TABLE `widget_item_plain` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `widget_id` int(10) unsigned DEFAULT NULL,
  `text` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_wip_wi` (`widget_id`),
  CONSTRAINT `FK_wip_wi` FOREIGN KEY (`widget_id`) REFERENCES `widget` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table widget_item_table
# ------------------------------------------------------------

DROP TABLE IF EXISTS `widget_item_table`;

CREATE TABLE `widget_item_table` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `value` varchar(256) DEFAULT NULL,
  `widget_id` int(11) unsigned DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_wit_wi` (`widget_id`),
  CONSTRAINT `FK_wit_wi` FOREIGN KEY (`widget_id`) REFERENCES `widget` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
