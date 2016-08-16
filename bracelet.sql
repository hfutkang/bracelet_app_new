-- MySQL dump 10.13  Distrib 5.5.47, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: sc_bracelet
-- ------------------------------------------------------
-- Server version	5.5.47-0+deb8u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bracelet`
--

DROP TABLE IF EXISTS `bracelet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bracelet` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` char(20) NOT NULL,
  `power` int(3) NOT NULL DEFAULT '100',
  `runtime_location` int(3) NOT NULL DEFAULT '0',
  `measure_hrate` enum('yes','no') NOT NULL DEFAULT 'no',
  `mac` char(18) DEFAULT NULL,
  `last_update_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  PRIMARY KEY (`_id`),
  UNIQUE KEY `device_id` (`device_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bracelet`
--

LOCK TABLES `bracelet` WRITE;
/*!40000 ALTER TABLE `bracelet` DISABLE KEYS */;
INSERT INTO `bracelet` VALUES (4,'223456789',95,1,'no','AC:EF:67:4D:3F:86','1970-01-01 00:00:00');
/*!40000 ALTER TABLE `bracelet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `heart_rate`
--

DROP TABLE IF EXISTS `heart_rate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `heart_rate` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` char(20) NOT NULL,
  `rate` int(3) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `type` enum('App','Manual') NOT NULL DEFAULT 'Manual',
  PRIMARY KEY (`device_id`,`time`),
  UNIQUE KEY `_id` (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `heart_rate`
--

LOCK TABLES `heart_rate` WRITE;
/*!40000 ALTER TABLE `heart_rate` DISABLE KEYS */;
INSERT INTO `heart_rate` VALUES (6,'123456455',66,'2016-04-22 09:53:00','Manual'),(5,'223456789',88,'2016-04-20 10:12:00','App');
/*!40000 ALTER TABLE `heart_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` char(20) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `type` enum('sos','low_power','notice','other') NOT NULL DEFAULT 'other',
  `content` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`device_id`,`time`),
  UNIQUE KEY `_id` (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message`
--

LOCK TABLES `message` WRITE;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
/*!40000 ALTER TABLE `message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `position`
--

DROP TABLE IF EXISTS `position`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `position` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` char(20) NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `mcc` int(3) DEFAULT NULL,
  `mnc` int(3) DEFAULT NULL,
  `lac` int(3) DEFAULT NULL,
  `cid` int(3) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `type` enum('gps','base') NOT NULL DEFAULT 'gps',
  PRIMARY KEY (`device_id`,`time`),
  UNIQUE KEY `_id` (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `position`
--

LOCK TABLES `position` WRITE;
/*!40000 ALTER TABLE `position` DISABLE KEYS */;
INSERT INTO `position` VALUES (7,'223456789',22.560798,113.949999,0,0,0,0,'2016-04-20 10:01:00','gps');
/*!40000 ALTER TABLE `position` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sleep`
--

DROP TABLE IF EXISTS `sleep`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sleep` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` char(20) NOT NULL,
  `total_time` int(5) NOT NULL DEFAULT '0',
  `deep_time` int(5) NOT NULL DEFAULT '0',
  `shallow_time` int(5) NOT NULL DEFAULT '0',
  `wake_time` int(5) NOT NULL DEFAULT '0',
  `lie_time` int(5) NOT NULL DEFAULT '0',
  `wake_times` int(3) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `end_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  PRIMARY KEY (`device_id`,`start_time`),
  UNIQUE KEY `_id` (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sleep`
--

LOCK TABLES `sleep` WRITE;
/*!40000 ALTER TABLE `sleep` DISABLE KEYS */;
INSERT INTO `sleep` VALUES (3,'223456789',550,120,300,0,0,3,'2016-04-19 23:00:00','2016-04-20 08:10:00');
/*!40000 ALTER TABLE `sleep` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sports`
--

DROP TABLE IF EXISTS `sports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sports` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` char(20) NOT NULL,
  `run_steps` int(6) NOT NULL DEFAULT '0',
  `walk_steps` int(6) NOT NULL DEFAULT '0',
  `longest_time` int(4) NOT NULL DEFAULT '0',
  `time` date NOT NULL DEFAULT '1970-01-01',
  PRIMARY KEY (`device_id`,`time`),
  UNIQUE KEY `_id` (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sports`
--

LOCK TABLES `sports` WRITE;
/*!40000 ALTER TABLE `sports` DISABLE KEYS */;
INSERT INTO `sports` VALUES (1,'123456455',1121,12223,0,'2016-04-22');
/*!40000 ALTER TABLE `sports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_number` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL DEFAULT '123456',
  `email` varchar(50) DEFAULT NULL,
  `user_name` varchar(30) DEFAULT NULL,
  `register_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `last_sync_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  PRIMARY KEY (`_id`),
  UNIQUE KEY `phone_number` (`phone_number`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'1858846080','123456',NULL,NULL,'1970-01-01 00:00:00','1970-01-01 00:00:00');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_bracelet`
--

DROP TABLE IF EXISTS `user_bracelet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_bracelet` (
  `_id` int(11) NOT NULL DEFAULT '0',
  `user_id` char(20) NOT NULL DEFAULT '',
  `device_id` char(20) NOT NULL DEFAULT '',
  `device_mac` char(18) DEFAULT NULL,
  `device_name` char(20) NOT NULL DEFAULT 'yoyo',
  `device_sex` char(7) NOT NULL DEFAULT 'female',
  `device_age` int(3) NOT NULL DEFAULT '18',
  `device_weight` int(3) NOT NULL DEFAULT '50',
  `device_height` int(3) NOT NULL DEFAULT '170',
  `add_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `image` char(50) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_bracelet`
--

LOCK TABLES `user_bracelet` WRITE;
/*!40000 ALTER TABLE `user_bracelet` DISABLE KEYS */;
INSERT INTO `user_bracelet` VALUES (8,'1858846080','123456789','AC:EF:67:4D:3F:86','Mike','Female',18,70,180,'2016-04-19 08:22:00',NULL),(7,'1858846080','223456789','AC:EF:67:4D:3F:86','Mike','Female',18,70,180,'2016-04-19 08:22:00',NULL);
/*!40000 ALTER TABLE `user_bracelet` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-04-22 18:13:22
