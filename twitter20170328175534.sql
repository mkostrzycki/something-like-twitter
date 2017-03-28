-- MySQL dump 10.13  Distrib 5.7.17, for Linux (x86_64)
--
-- Host: localhost    Database: twitter
-- ------------------------------------------------------
-- Server version	5.7.17-0ubuntu0.16.04.1

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
-- Table structure for table `Comments`
--

DROP TABLE IF EXISTS `Comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tweet_id` int(11) NOT NULL,
  `comment_text` text COLLATE utf8_polish_ci,
  `creation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `tweet_id` (`tweet_id`),
  CONSTRAINT `Comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`),
  CONSTRAINT `Comments_ibfk_2` FOREIGN KEY (`tweet_id`) REFERENCES `Tweets` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Comments`
--

LOCK TABLES `Comments` WRITE;
/*!40000 ALTER TABLE `Comments` DISABLE KEYS */;
INSERT INTO `Comments` VALUES (1,13,5,'sadadasd','2017-03-25 18:38:06');
/*!40000 ALTER TABLE `Comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Messages`
--

DROP TABLE IF EXISTS `Messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `message_text` text COLLATE utf8_polish_ci,
  `is_read` tinyint(4) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `recipient_id` (`recipient_id`),
  CONSTRAINT `Messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `Users` (`id`),
  CONSTRAINT `Messages_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Messages`
--

LOCK TABLES `Messages` WRITE;
/*!40000 ALTER TABLE `Messages` DISABLE KEYS */;
INSERT INTO `Messages` VALUES (1,13,1,'Widomosc numer trzy :)',0,'2017-03-25 17:45:41'),(2,13,1,'Wiadomość',0,'2017-03-25 17:53:40'),(3,13,1,'asdasd',0,'2017-03-25 18:29:35'),(4,13,1,'asdscasxwvx',0,'2017-03-25 18:29:38'),(5,13,1,'sdfd sfsaf afafrftgyhyh',0,'2017-03-25 18:29:43'),(6,14,13,'Młodości! dodaj mi skrzydła!\r\nNiech nad martwym wzlecę światem...',1,'2017-03-26 12:54:49'),(7,14,13,'Tam sięgaj, gdzie wzrok nie sięga;\r\nŁam, czego rozum nie złamie:\r\nMłodości! orla twych lotów potęga,\r\nJako piorun twoje ramię.',0,'2017-03-26 12:55:40');
/*!40000 ALTER TABLE `Messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Tweets`
--

DROP TABLE IF EXISTS `Tweets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Tweets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tweet_text` tinytext COLLATE utf8_polish_ci,
  `creation_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `Tweets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Tweets`
--

LOCK TABLES `Tweets` WRITE;
/*!40000 ALTER TABLE `Tweets` DISABLE KEYS */;
INSERT INTO `Tweets` VALUES (1,1,'Lorem ipsum dolor','2017-03-03 15:04:53'),(2,1,'Tralalala\r\n','2017-03-03 15:05:07'),(3,1,'YOLO','2017-03-03 20:02:51'),(4,13,'asdsadf','2017-03-25 17:11:34'),(5,13,'ssdfdxa','2017-03-25 18:26:28'),(6,14,'Litwo, ojczyzno moja...','2017-03-26 12:53:08');
/*!40000 ALTER TABLE `Tweets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `hash_pass` varchar(60) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES (1,'Maciek','lorem@ipsum.pl','$2y$10$dUxWqRkr8CkUVTLEw9jRuejiMqynsGA3NqId3ux8D7kU6Fr/f2FmS'),(10,'Majk','lorerfefrfm@ipsum.pl','$2y$10$GjR6Z.OFiuAwsng22b7w3uZzAzGvRp6.gtz/5zmY1A2m/K7HVmsqG'),(11,'user33','test@test.pl','$2y$10$axAXDzxviVLGd9kvixEKneAbA9QYtbJjglHtpYQmvMnPuwOAJi4mW'),(13,'Jan III Testowy','jan@testowy.pl','$2y$10$LMb6J7iMm45iGa.NBrIuRuUme3.v2uII/1HksE0nVjfktO77WLPZG'),(14,'Adam Mickiewicz','adam@mickiewicz.pl','$2y$10$i5e7Krg9XcTZpX38RmJAJ.fYV8kYPi3HKvnYfBKkQOn1w/dElldHG');
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-03-28 17:55:34
