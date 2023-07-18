# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.39)
# Database: LLT
# Generation Time: 2023-07-18 21:15:22 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*CREATE DATABASE LLT*/;

# Dump of table club
# ------------------------------------------------------------

DROP TABLE IF EXISTS `club`;

CREATE TABLE `club` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `budget` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `club` WRITE;
/*!40000 ALTER TABLE `club` DISABLE KEYS */;

INSERT INTO `club` (`id`, `name`, `budget`)
VALUES
	(1,'Real Madrid',30000000.00),
	(3,'PSG',20000.00),
	(4,'Barca',20000000.00);

/*!40000 ALTER TABLE `club` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table player
# ------------------------------------------------------------

DROP TABLE IF EXISTS `player`;

CREATE TABLE `player` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_club` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `salary` float(10,2) NOT NULL,
  `email` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `player` WRITE;
/*!40000 ALTER TABLE `player` DISABLE KEYS */;

INSERT INTO `player` (`id`, `id_club`, `name`, `salary`, `email`)
VALUES
	(1,1,'Roberto Carlos',20000.00,'spartan2994@hotmail.com'),
	(2,1,'Diego Armando Maradona',20000.00,'maradona@fifa.com');

/*!40000 ALTER TABLE `player` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table trainer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `trainer`;

CREATE TABLE `trainer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_club` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `salary` float(10,2) NOT NULL,
  `email` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `trainer` WRITE;
/*!40000 ALTER TABLE `trainer` DISABLE KEYS */;

INSERT INTO `trainer` (`id`, `id_club`, `name`, `salary`, `email`)
VALUES
	(1,1,'Carlos Bonilla',20000.00,'spartan2994@hotmail.com'),
	(2,1,'Roberto Carlos',20000.00,'ing.carlos_bonilla@outlook.com'),
	(4,1,'Roberto Carlos',20000.00,'ing.carlos_bonillaa@outlook.com');

/*!40000 ALTER TABLE `trainer` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
