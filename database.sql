SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `raid_team` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `rank` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=27 ;

CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `last_modified` double DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `realm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `battlegroup` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `race` int(11) unsigned DEFAULT NULL,
  `gender` int(11) unsigned DEFAULT NULL,
  `level` int(11) unsigned DEFAULT NULL,
  `achievement_points` int(11) unsigned DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `calc_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `items` text COLLATE utf8mb4_unicode_ci,
  `talents` text COLLATE utf8mb4_unicode_ci,
  `total_honorable_kills` int(11) unsigned DEFAULT NULL,
  `item_level_equipped` int(11) unsigned DEFAULT NULL,
  `item_level` int(11) unsigned DEFAULT NULL,
  `stats` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `last_modified` (`last_modified`),
  KEY `name` (`name`(191))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=8603 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
