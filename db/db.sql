# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.21)
# Database: sportalize
# Generation Time: 2015-09-30 21:46:16 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table comment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `comment`;

CREATE TABLE `comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `commenter_id` int(11) unsigned DEFAULT NULL,
  `parent_comment_id` int(11) unsigned DEFAULT NULL,
  `type` enum('POST','GALERY') DEFAULT 'POST',
  `commented_post_id` int(11) unsigned DEFAULT NULL,
  `commented_image_id` int(11) unsigned DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  `text` longtext,
  `forwarded` int(11) DEFAULT '0',
  `likes` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_co_us` (`commenter_id`),
  KEY `FK_co_co` (`parent_comment_id`),
  KEY `FK_co_po` (`commented_post_id`),
  KEY `FK_co_im` (`commented_image_id`),
  CONSTRAINT `FK_co_co` FOREIGN KEY (`parent_comment_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `FK_co_im` FOREIGN KEY (`commented_image_id`) REFERENCES `image` (`id`),
  CONSTRAINT `FK_co_po` FOREIGN KEY (`commented_post_id`) REFERENCES `post` (`id`),
  CONSTRAINT `FK_co_us` FOREIGN KEY (`commenter_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;

INSERT INTO `comment` (`id`, `commenter_id`, `parent_comment_id`, `type`, `commented_post_id`, `commented_image_id`, `date`, `text`, `forwarded`, `likes`)
VALUES
  (1,1,NULL,'POST',1,NULL,1434363010,'You can view comments!',0,0),
  (2,1,NULL,'POST',2,NULL,1434363010,'Comments are everywhere!',0,0);

/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table country
# ------------------------------------------------------------

DROP TABLE IF EXISTS `country`;

CREATE TABLE `country` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `country_code` varchar(2) NOT NULL DEFAULT '',
  `country_name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `country` WRITE;
/*!40000 ALTER TABLE `country` DISABLE KEYS */;

INSERT INTO `country` (`id`, `country_code`, `country_name`)
VALUES
  (1,'US','United States'),
  (2,'CA','Canada'),
  (3,'AF','Afghanistan'),
  (4,'AL','Albania'),
  (5,'DZ','Algeria'),
  (6,'DS','American Samoa'),
  (7,'AD','Andorra'),
  (8,'AO','Angola'),
  (9,'AI','Anguilla'),
  (10,'AQ','Antarctica'),
  (11,'AG','Antigua and/or Barbuda'),
  (12,'AR','Argentina'),
  (13,'AM','Armenia'),
  (14,'AW','Aruba'),
  (15,'AU','Australia'),
  (16,'AT','Austria'),
  (17,'AZ','Azerbaijan'),
  (18,'BS','Bahamas'),
  (19,'BH','Bahrain'),
  (20,'BD','Bangladesh'),
  (21,'BB','Barbados'),
  (22,'BY','Belarus'),
  (23,'BE','Belgium'),
  (24,'BZ','Belize'),
  (25,'BJ','Benin'),
  (26,'BM','Bermuda'),
  (27,'BT','Bhutan'),
  (28,'BO','Bolivia'),
  (29,'BA','Bosnia and Herzegovina'),
  (30,'BW','Botswana'),
  (31,'BV','Bouvet Island'),
  (32,'BR','Brazil'),
  (33,'IO','British lndian Ocean Territory'),
  (34,'BN','Brunei Darussalam'),
  (35,'BG','Bulgaria'),
  (36,'BF','Burkina Faso'),
  (37,'BI','Burundi'),
  (38,'KH','Cambodia'),
  (39,'CM','Cameroon'),
  (40,'CV','Cape Verde'),
  (41,'KY','Cayman Islands'),
  (42,'CF','Central African Republic'),
  (43,'TD','Chad'),
  (44,'CL','Chile'),
  (45,'CN','China'),
  (46,'CX','Christmas Island'),
  (47,'CC','Cocos (Keeling) Islands'),
  (48,'CO','Colombia'),
  (49,'KM','Comoros'),
  (50,'CG','Congo'),
  (51,'CK','Cook Islands'),
  (52,'CR','Costa Rica'),
  (53,'HR','Croatia (Hrvatska)'),
  (54,'CU','Cuba'),
  (55,'CY','Cyprus'),
  (56,'CZ','Czech Republic'),
  (57,'DK','Denmark'),
  (58,'DJ','Djibouti'),
  (59,'DM','Dominica'),
  (60,'DO','Dominican Republic'),
  (61,'TP','East Timor'),
  (62,'EC','Ecuador'),
  (63,'EG','Egypt'),
  (64,'SV','El Salvador'),
  (65,'GQ','Equatorial Guinea'),
  (66,'ER','Eritrea'),
  (67,'EE','Estonia'),
  (68,'ET','Ethiopia'),
  (69,'FK','Falkland Islands (Malvinas)'),
  (70,'FO','Faroe Islands'),
  (71,'FJ','Fiji'),
  (72,'FI','Finland'),
  (73,'FR','France'),
  (74,'FX','France, Metropolitan'),
  (75,'GF','French Guiana'),
  (76,'PF','French Polynesia'),
  (77,'TF','French Southern Territories'),
  (78,'GA','Gabon'),
  (79,'GM','Gambia'),
  (80,'GE','Georgia'),
  (81,'DE','Germany'),
  (82,'GH','Ghana'),
  (83,'GI','Gibraltar'),
  (84,'GR','Greece'),
  (85,'GL','Greenland'),
  (86,'GD','Grenada'),
  (87,'GP','Guadeloupe'),
  (88,'GU','Guam'),
  (89,'GT','Guatemala'),
  (90,'GN','Guinea'),
  (91,'GW','Guinea-Bissau'),
  (92,'GY','Guyana'),
  (93,'HT','Haiti'),
  (94,'HM','Heard and Mc Donald Islands'),
  (95,'HN','Honduras'),
  (96,'HK','Hong Kong'),
  (97,'HU','Hungary'),
  (98,'IS','Iceland'),
  (99,'IN','India'),
  (100,'ID','Indonesia'),
  (101,'IR','Iran (Islamic Republic of)'),
  (102,'IQ','Iraq'),
  (103,'IE','Ireland'),
  (104,'IL','Israel'),
  (105,'IT','Italy'),
  (106,'CI','Ivory Coast'),
  (107,'JM','Jamaica'),
  (108,'JP','Japan'),
  (109,'JO','Jordan'),
  (110,'KZ','Kazakhstan'),
  (111,'KE','Kenya'),
  (112,'KI','Kiribati'),
  (113,'KP','Korea, Democratic People\'s Republic of'),
  (114,'KR','Korea, Republic of'),
  (115,'XK','Kosovo'),
  (116,'KW','Kuwait'),
  (117,'KG','Kyrgyzstan'),
  (118,'LA','Lao People\'s Democratic Republic'),
  (119,'LV','Latvia'),
  (120,'LB','Lebanon'),
  (121,'LS','Lesotho'),
  (122,'LR','Liberia'),
  (123,'LY','Libyan Arab Jamahiriya'),
  (124,'LI','Liechtenstein'),
  (125,'LT','Lithuania'),
  (126,'LU','Luxembourg'),
  (127,'MO','Macau'),
  (128,'MK','Macedonia'),
  (129,'MG','Madagascar'),
  (130,'MW','Malawi'),
  (131,'MY','Malaysia'),
  (132,'MV','Maldives'),
  (133,'ML','Mali'),
  (134,'MT','Malta'),
  (135,'MH','Marshall Islands'),
  (136,'MQ','Martinique'),
  (137,'MR','Mauritania'),
  (138,'MU','Mauritius'),
  (139,'TY','Mayotte'),
  (140,'MX','Mexico'),
  (141,'FM','Micronesia, Federated States of'),
  (142,'MD','Moldova, Republic of'),
  (143,'MC','Monaco'),
  (144,'MN','Mongolia'),
  (145,'ME','Montenegro'),
  (146,'MS','Montserrat'),
  (147,'MA','Morocco'),
  (148,'MZ','Mozambique'),
  (149,'MM','Myanmar'),
  (150,'NA','Namibia'),
  (151,'NR','Nauru'),
  (152,'NP','Nepal'),
  (153,'NL','Netherlands'),
  (154,'AN','Netherlands Antilles'),
  (155,'NC','New Caledonia'),
  (156,'NZ','New Zealand'),
  (157,'NI','Nicaragua'),
  (158,'NE','Niger'),
  (159,'NG','Nigeria'),
  (160,'NU','Niue'),
  (161,'NF','Norfork Island'),
  (162,'MP','Northern Mariana Islands'),
  (163,'NO','Norway'),
  (164,'OM','Oman'),
  (165,'PK','Pakistan'),
  (166,'PW','Palau'),
  (167,'PA','Panama'),
  (168,'PG','Papua New Guinea'),
  (169,'PY','Paraguay'),
  (170,'PE','Peru'),
  (171,'PH','Philippines'),
  (172,'PN','Pitcairn'),
  (173,'PL','Poland'),
  (174,'PT','Portugal'),
  (175,'PR','Puerto Rico'),
  (176,'QA','Qatar'),
  (177,'RE','Reunion'),
  (178,'RO','Romania'),
  (179,'RU','Russian Federation'),
  (180,'RW','Rwanda'),
  (181,'KN','Saint Kitts and Nevis'),
  (182,'LC','Saint Lucia'),
  (183,'VC','Saint Vincent and the Grenadines'),
  (184,'WS','Samoa'),
  (185,'SM','San Marino'),
  (186,'ST','Sao Tome and Principe'),
  (187,'SA','Saudi Arabia'),
  (188,'SN','Senegal'),
  (189,'RS','Serbia'),
  (190,'SC','Seychelles'),
  (191,'SL','Sierra Leone'),
  (192,'SG','Singapore'),
  (193,'SK','Slovakia'),
  (194,'SI','Slovenia'),
  (195,'SB','Solomon Islands'),
  (196,'SO','Somalia'),
  (197,'ZA','South Africa'),
  (198,'GS','South Georgia South Sandwich Islands'),
  (199,'ES','Spain'),
  (200,'LK','Sri Lanka'),
  (201,'SH','St. Helena'),
  (202,'PM','St. Pierre and Miquelon'),
  (203,'SD','Sudan'),
  (204,'SR','Suriname'),
  (205,'SJ','Svalbarn and Jan Mayen Islands'),
  (206,'SZ','Swaziland'),
  (207,'SE','Sweden'),
  (208,'CH','Switzerland'),
  (209,'SY','Syrian Arab Republic'),
  (210,'TW','Taiwan'),
  (211,'TJ','Tajikistan'),
  (212,'TZ','Tanzania, United Republic of'),
  (213,'TH','Thailand'),
  (214,'TG','Togo'),
  (215,'TK','Tokelau'),
  (216,'TO','Tonga'),
  (217,'TT','Trinidad and Tobago'),
  (218,'TN','Tunisia'),
  (219,'TR','Turkey'),
  (220,'TM','Turkmenistan'),
  (221,'TC','Turks and Caicos Islands'),
  (222,'TV','Tuvalu'),
  (223,'UG','Uganda'),
  (224,'UA','Ukraine'),
  (225,'AE','United Arab Emirates'),
  (226,'GB','United Kingdom'),
  (227,'UM','United States minor outlying islands'),
  (228,'UY','Uruguay'),
  (229,'UZ','Uzbekistan'),
  (230,'VU','Vanuatu'),
  (231,'VA','Vatican City State'),
  (232,'VE','Venezuela'),
  (233,'VN','Vietnam'),
  (234,'VG','Virgin Islands (British)'),
  (235,'VI','Virgin Islands (U.S.)'),
  (236,'WF','Wallis and Futuna Islands'),
  (237,'EH','Western Sahara'),
  (238,'YE','Yemen'),
  (239,'YU','Yugoslavia'),
  (240,'ZR','Zaire'),
  (241,'ZM','Zambia'),
  (242,'ZW','Zimbabwe');

/*!40000 ALTER TABLE `country` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table dream_team
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dream_team`;

CREATE TABLE `dream_team` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(256) DEFAULT NULL,
  `data` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_dt_us` (`user_id`),
  CONSTRAINT `FK_dt_us` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table ex_favorite_pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ex_favorite_pages`;

CREATE TABLE `ex_favorite_pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `page_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_efp_pa` (`page_id`),
  KEY `FK_us_pa` (`user_id`),
  CONSTRAINT `FK_efp_pa` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`),
  CONSTRAINT `FK_us_pa` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table favourite_item
# ------------------------------------------------------------

DROP TABLE IF EXISTS `favourite_item`;

CREATE TABLE `favourite_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(256) DEFAULT NULL,
  `page_id` int(11) unsigned DEFAULT NULL,
  `type` enum('PLAYER','TEAM') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_fi_pa` (`page_id`),
  CONSTRAINT `FK_fi_pa` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table friend_list
# ------------------------------------------------------------

DROP TABLE IF EXISTS `friend_list`;

CREATE TABLE `friend_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_system` tinyint(1) DEFAULT '0',
  `title` varchar(256) DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_fl_us` (`user_id`),
  CONSTRAINT `FK_fl_us` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `friend_list` WRITE;
/*!40000 ALTER TABLE `friend_list` DISABLE KEYS */;

INSERT INTO `friend_list` (`id`, `is_system`, `title`, `user_id`)
VALUES
  (1,1,'WORK',NULL),
  (2,1,'FAMILY',NULL),
  (3,1,'BEST_FRIENDS',NULL);

/*!40000 ALTER TABLE `friend_list` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table galery
# ------------------------------------------------------------

DROP TABLE IF EXISTS `galery`;

CREATE TABLE `galery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_ga_us` (`user_id`),
  CONSTRAINT `FK_ga_us` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table image
# ------------------------------------------------------------

DROP TABLE IF EXISTS `image`;

CREATE TABLE `image` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `galery_id` int(11) unsigned DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_im_ga` (`galery_id`),
  CONSTRAINT `FK_im_ga` FOREIGN KEY (`galery_id`) REFERENCES `galery` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table language
# ------------------------------------------------------------

DROP TABLE IF EXISTS `language`;

CREATE TABLE `language` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  `file` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `language` WRITE;
/*!40000 ALTER TABLE `language` DISABLE KEYS */;

INSERT INTO `language` (`id`, `name`, `file`)
VALUES
  (1,'ENGLISH','en'),
  (2,'SERBIAN','sr');

/*!40000 ALTER TABLE `language` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table menu_link
# ------------------------------------------------------------

DROP TABLE IF EXISTS `menu_link`;

CREATE TABLE `menu_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  `parent_menu_link_id` int(11) unsigned DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  `class` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_ml_ml` (`parent_menu_link_id`),
  CONSTRAINT `FK_ml_ml` FOREIGN KEY (`parent_menu_link_id`) REFERENCES `menu_link` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `menu_link` WRITE;
/*!40000 ALTER TABLE `menu_link` DISABLE KEYS */;

INSERT INTO `menu_link` (`id`, `title`, `url`, `parent_menu_link_id`, `display_order`, `class`)
VALUES
  (1,'NOTIFICATIONS','javascript:void(0)',NULL,1,NULL),
  (2,'REFRESH_ME','/',NULL,2,NULL),
  (3,'MY_POSTS','/index/profile',NULL,3,NULL),
  (4,'GAMES','javascript:void(0)',NULL,4,NULL),
  (5,'MORE','javascript:void(0)',NULL,5,NULL),
  (6,'FANTASY','javascript:void(0)',4,1,NULL),
  (7,'QUIZ','javascript:void(0)',4,2,NULL),
  (8,'VIRTUAL_BOOKING','javascript:void(0)',4,3,NULL),
  (9,'NEW_PAGE','javascript:void(0)',5,1,'modal-open'),
  (10,'GALERY','galery/index',5,3,NULL),
  (11,'PERSONAL_SETTINGS','settings/index',5,4,NULL),
  (12,'FAVORITES','favorites/index',5,5,NULL),
  (13,'SIGN_OUT','login/index/sign-out',5,6,NULL),
  (14,'MY_PAGES','javascript:void(0)',5,2,NULL);

/*!40000 ALTER TABLE `menu_link` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table page
# ------------------------------------------------------------

DROP TABLE IF EXISTS `page`;

CREATE TABLE `page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `logo` varchar(256) DEFAULT NULL,
  `type` enum('PLAYER','TEAM','SPORT','OTHER','LEAGUE') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pa_us` (`user_id`),
  CONSTRAINT `FK_pa_us` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;

INSERT INTO `page` (`id`, `title`, `user_id`, `logo`, `type`)
VALUES
  (1,'Sportalize',1,'widget_football.png','OTHER');

/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table post
# ------------------------------------------------------------

DROP TABLE IF EXISTS `post`;

CREATE TABLE `post` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `page_id` int(11) unsigned DEFAULT NULL,
  `original_user_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `text` longtext,
  `original_page_id` int(10) unsigned DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `date` int(11) DEFAULT NULL,
  `video` varchar(256) DEFAULT NULL,
  `post_type` enum('TEXT','IMAGE','VIDEO') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_po_us1` (`user_id`),
  KEY `FK_po_pa1` (`page_id`),
  CONSTRAINT `FK_po_pa` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`),
  CONSTRAINT `FK_po_pa1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`),
  CONSTRAINT `FK_po_us` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_po_us1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;

INSERT INTO `post` (`id`, `user_id`, `page_id`, `original_user_id`, `title`, `text`, `original_page_id`, `image`, `date`, `video`, `post_type`)
VALUES
  (1,NULL,1,NULL,'Welcome to Sportalize!','As a demo, this is automated post by official Sportalize Page.',NULL,NULL,1434363010,NULL,'TEXT'),
  (2,1,NULL,NULL,'Welcome to Sportalize!','You can also receive updates from your friends - you are friend with Sportalize user!',NULL,NULL,1434363010,NULL,'TEXT');

/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table privacy_setting
# ------------------------------------------------------------

DROP TABLE IF EXISTS `privacy_setting`;

CREATE TABLE `privacy_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `setting` enum('ALL','FRIENDS_OF_FRIENDS','FRIENDS_ONLY','SPECIFIC_LISTS','SPECIFIC_FRIENDS','NONE') DEFAULT NULL,
  `type` enum('POST','PROFILE','GALERY') DEFAULT NULL,
  `options` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_ps_us` (`user_id`),
  CONSTRAINT `FK_ps_us` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;

INSERT INTO `role` (`id`, `name`)
VALUES
  (1,'ADMIN'),
  (2,'USER');

/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table sport
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sport`;

CREATE TABLE `sport` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `field_players` int(11) DEFAULT NULL,
  `name` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `sport` WRITE;
/*!40000 ALTER TABLE `sport` DISABLE KEYS */;

INSERT INTO `sport` (`id`, `field_players`, `name`)
VALUES
  (1,11,'FOOTBALL'),
  (2,5,'BASKETBALL'),
  (3,7,'WATERPOLO'),
  (4,6,'VOLEYBALL'),
  (5,6,'HANDBALL'),
  (6,1,'TENIS');

/*!40000 ALTER TABLE `sport` ENABLE KEYS */;
UNLOCK TABLES;


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

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `email`, `password`, `token`, `token_time_generation`, `activated`)
VALUES
  (1,'admin@sportalize.com','945896b5bbe0a412751ce0bd6468239b92f4a3d0e1d36fe6ed673d498f1e6289',NULL,NULL,1);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_favorite
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_favorite`;

CREATE TABLE `user_favorite` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `comment_id` int(11) unsigned DEFAULT NULL,
  `post_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_uf_us` (`user_id`),
  KEY `FK_uf_co` (`comment_id`),
  KEY `FK_uf_po` (`post_id`),
  CONSTRAINT `FK_uf_co` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `FK_uf_po` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`),
  CONSTRAINT `FK_uf_us` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table user_info
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_info`;

CREATE TABLE `user_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `first_name` varchar(256) DEFAULT NULL,
  `last_name` varchar(256) DEFAULT NULL,
  `date_of_birth` int(11) DEFAULT NULL,
  `phone` varchar(256) DEFAULT NULL,
  `city` varchar(256) DEFAULT NULL,
  `country_id` int(11) unsigned NOT NULL,
  `avatar` varchar(256) DEFAULT NULL,
  `language_id` int(11) unsigned DEFAULT NULL,
  `role_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_ui_us` (`user_id`),
  KEY `FK_ui_co` (`country_id`),
  KEY `FK_ui_la` (`language_id`),
  KEY `FK_ui_ro` (`role_id`),
  CONSTRAINT `FK_ui_co` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`),
  CONSTRAINT `FK_ui_la` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`),
  CONSTRAINT `FK_ui_ro` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  CONSTRAINT `FK_ui_us` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `user_info` WRITE;
/*!40000 ALTER TABLE `user_info` DISABLE KEYS */;

INSERT INTO `user_info` (`id`, `user_id`, `first_name`, `last_name`, `date_of_birth`, `phone`, `city`, `country_id`, `avatar`, `language_id`, `role_id`)
VALUES
  (7,1,'Sportalize','Admin',NULL,NULL,NULL,1,NULL,1,1);

/*!40000 ALTER TABLE `user_info` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_like
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_like`;

CREATE TABLE `user_like` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned DEFAULT NULL,
  `comment_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_ul_po` (`post_id`),
  KEY `FK_ul_co` (`comment_id`),
  KEY `FK_ul_us` (`user_id`),
  CONSTRAINT `FK_ul_co` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `FK_ul_po` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`),
  CONSTRAINT `FK_ul_us` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table user_page
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_page`;

CREATE TABLE `user_page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `page_id` int(11) unsigned DEFAULT NULL,
  `show_in_favorite_pages_widget` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_up_us` (`user_id`),
  KEY `FK_up_pa` (`page_id`),
  CONSTRAINT `FK_up_pa` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`),
  CONSTRAINT `FK_up_us` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table user_sport
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_sport`;

CREATE TABLE `user_sport` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `sport_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_us_us` (`user_id`),
  KEY `FK_us_sp` (`sport_id`),
  CONSTRAINT `FK_us_sp` FOREIGN KEY (`sport_id`) REFERENCES `sport` (`id`),
  CONSTRAINT `FK_us_us` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table user_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_user`;

CREATE TABLE `user_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `friend_id` int(11) unsigned DEFAULT NULL,
  `status` enum('PENDING','ACCEPTED') DEFAULT 'PENDING',
  PRIMARY KEY (`id`),
  KEY `FK_uu_u1` (`user_id`),
  KEY `FK_uu_u2` (`friend_id`),
  CONSTRAINT `FK_uu_u1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_uu_u2` FOREIGN KEY (`friend_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table user_widget
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_widget`;

CREATE TABLE `user_widget` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `widget_id` int(11) unsigned DEFAULT NULL,
  `placement` enum('LEFT','RIGHT','DO_NOT_SHOW') DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_uw_us` (`user_id`),
  KEY `FK_uw` (`widget_id`),
  CONSTRAINT `FK_uw` FOREIGN KEY (`widget_id`) REFERENCES `widget` (`id`),
  CONSTRAINT `FK_uw_us` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table user_widget_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_widget_data`;

CREATE TABLE `user_widget_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_uwd_us` (`user_id`),
  CONSTRAINT `FK_uwd_us` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table widget
# ------------------------------------------------------------

DROP TABLE IF EXISTS `widget`;

CREATE TABLE `widget` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(256) DEFAULT NULL,
  `is_system` tinyint(1) DEFAULT '0',
  `page_id` int(11) unsigned DEFAULT NULL,
  `type` enum('PAGE','PLAIN','LIST','TABLE','LIST_WEB') DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_wi_pa` (`page_id`),
  CONSTRAINT `FK_wi_pa` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `widget` WRITE;
/*!40000 ALTER TABLE `widget` DISABLE KEYS */;

INSERT INTO `widget` (`id`, `title`, `is_system`, `page_id`, `type`)
VALUES
  (1,'FAVORITE_PAGES',1,1,'PAGE'),
  (2,'UPCOMING_GAMES',1,1,'LIST_WEB'),
  (3,'BEST_SCORERS',1,1,'LIST'),
  (4,'LEAGUE_TABLE',1,1,'TABLE');

/*!40000 ALTER TABLE `widget` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table widget_option
# ------------------------------------------------------------

DROP TABLE IF EXISTS `widget_option`;

CREATE TABLE `widget_option` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `widget_id` int(10) unsigned DEFAULT NULL,
  `type` enum('SUB_PAGE','SUB_LIST_WEB','SUB_LIST','SUB_LIST_WEB_DATA','SUB_TABLE','SUB_PLAIN','SUB_LIST_WEB_OPTION','SUB_LIST_OPTION') DEFAULT NULL,
  `parent_widget_option_id` int(11) unsigned DEFAULT NULL,
  `linked_page_id` int(11) unsigned DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `value_1` varchar(256) DEFAULT NULL,
  `value_2` varchar(256) DEFAULT NULL,
  `placement` enum('MAIN','ADDITIONAL') DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  `image_1` varchar(256) DEFAULT NULL,
  `image_2` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_wo_wi` (`widget_id`),
  KEY `FK_wo_wo1` (`parent_widget_option_id`),
  KEY `FK_wo_pa` (`linked_page_id`),
  CONSTRAINT `FK_wo_pa` FOREIGN KEY (`linked_page_id`) REFERENCES `page` (`id`),
  CONSTRAINT `FK_wo_wi` FOREIGN KEY (`widget_id`) REFERENCES `widget` (`id`),
  CONSTRAINT `FK_wo_wo1` FOREIGN KEY (`parent_widget_option_id`) REFERENCES `widget_option` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `widget_option` WRITE;
/*!40000 ALTER TABLE `widget_option` DISABLE KEYS */;

INSERT INTO `widget_option` (`id`, `widget_id`, `type`, `parent_widget_option_id`, `linked_page_id`, `title`, `value_1`, `value_2`, `placement`, `display_order`, `image_1`, `image_2`)
VALUES
  (1,2,'SUB_LIST_WEB',NULL,NULL,'Football',NULL,NULL,NULL,1,NULL,NULL),
  (2,2,'SUB_LIST_WEB_OPTION',1,NULL,NULL,NULL,NULL,NULL,1,'widget_football.png','widget_football.png'),
  (3,2,'SUB_LIST_WEB_DATA',2,NULL,'Name','Red Star Belgrade','Partizan Belgrade','MAIN',1,NULL,NULL),
  (4,2,'SUB_LIST_WEB_DATA',2,NULL,'Venue','Stadion Rajko Mitic','','ADDITIONAL',1,NULL,NULL),
  (5,3,'SUB_LIST',NULL,NULL,'Premier League',NULL,NULL,NULL,1,'widget_football.png',NULL),
  (6,3,'SUB_LIST_OPTION',5,NULL,NULL,'Wayne Rooney','10',NULL,1,NULL,NULL),
  (7,3,'SUB_LIST_OPTION',5,NULL,NULL,'Edin Hazard','9',NULL,2,NULL,NULL),
  (8,4,'SUB_TABLE',NULL,NULL,NULL,'Manchester United','{\"W\":\"5\"}',NULL,1,NULL,NULL),
  (9,4,'SUB_TABLE',NULL,NULL,NULL,'Manchester City','{\"W\":\"4\"}',NULL,2,NULL,NULL);
  (10,1,'SUB_PAGE',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*!40000 ALTER TABLE `widget_option` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table widget_table_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `widget_table_data`;

CREATE TABLE `widget_table_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `short` varchar(10) DEFAULT NULL,
  `long` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_wtd_us` (`user_id`),
  CONSTRAINT `FK_wtd_us` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `widget_table_data` WRITE;
/*!40000 ALTER TABLE `widget_table_data` DISABLE KEYS */;

INSERT INTO `widget_table_data` (`id`, `user_id`, `short`, `long`)
VALUES
  (2,NULL,'GF','GOALS_FOR'),
  (3,NULL,'GA','GOALS_AGAINST'),
  (4,NULL,'PT','POINTS'),
  (5,NULL,'GD','GOALS_DIFFERENCE'),
  (6,NULL,'RB','REBOUNDS'),
  (7,NULL,'AS','ASSISTS'),
  (8,NULL,'ST','STEALS'),
  (9,NULL,'W','WINS'),
  (10,NULL,'L','LOSES'),
  (11,NULL,'D','DRAWS');

/*!40000 ALTER TABLE `widget_table_data` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
