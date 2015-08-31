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