-- MySQL dump 10.13  Distrib 5.5.62, for Win64 (AMD64)
--
-- Host: localhost    Database: fitnesspot_tpi
-- ------------------------------------------------------
-- Server version	5.7.24

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
-- Base de données : `fitnesspot_tpi`
--
CREATE DATABASE IF NOT EXISTS `fitnesspot_tpi` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `fitnesspot_tpi`;

-- --------------------------------------------------------


--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `idComment` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) DEFAULT NULL COMMENT 'null autorise, pour la FK',
  `idSpot` int(11) NOT NULL,
  `content` varchar(300) NOT NULL,
  `createdAt` datetime NOT NULL,
  `editedAt` datetime NOT NULL,
  PRIMARY KEY (`idComment`),
  KEY `comments_on_spots_FK` (`idSpot`),
  KEY `comments_on_users_FK` (`idUser`),
  CONSTRAINT `comments_on_spots_FK` FOREIGN KEY (`idSpot`) REFERENCES `spots` (`idSpot`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comments_on_users_FK` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `likes` (
  `idSpot` int(11) NOT NULL,
  `idUser` int(11) DEFAULT NULL,
  `liked` tinyint(1) NOT NULL,
  UNIQUE KEY `likes_un` (`idSpot`,`idUser`),
  KEY `likes_idSpot_IDX` (`idSpot`) USING BTREE,
  KEY `likes_idUser_IDX` (`idUser`) USING BTREE,
  CONSTRAINT `likes_FK` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `likes_FK_1` FOREIGN KEY (`idSpot`) REFERENCES `spots` (`idSpot`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `medias`
--

DROP TABLE IF EXISTS `medias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medias` (
  `idMedia` int(11) NOT NULL AUTO_INCREMENT,
  `idSpot` int(11) NOT NULL,
  `fileName` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `size` int(20) NOT NULL,
  PRIMARY KEY (`idMedia`),
  KEY `medias_on_spots_FK` (`idSpot`),
  CONSTRAINT `medias_on_spots_FK` FOREIGN KEY (`idSpot`) REFERENCES `spots` (`idSpot`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `spots`
--

DROP TABLE IF EXISTS `spots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `spots` (
  `idSpot` int(11) NOT NULL AUTO_INCREMENT,
  `idUser` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `detail` varchar(100) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `lat` double NOT NULL,
  `lon` double NOT NULL,
  `averageRate` double NOT NULL DEFAULT '0',
  `createdAt` datetime NOT NULL,
  `editedAt` datetime NOT NULL,
  `numberLike` int(11) NOT NULL DEFAULT '0',
  `validate` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idSpot`),
  KEY `spots_on_users_FK` (`idUser`),
  FULLTEXT KEY `spots_name-address-detail_IDX` (`name`,`address`,`detail`),
  CONSTRAINT `spots_on_users_FK` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `permission` int(11) NOT NULL DEFAULT '1',
  `createdAt` datetime NOT NULL,
  PRIMARY KEY (`idUser`),
  FULLTEXT KEY `users_pseudo-email_IDX` (`pseudo`,`email`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `votes` (
  `idSpot` int(11) NOT NULL,
  `idUser` int(11) DEFAULT NULL,
  `rate` varchar(100) NOT NULL,
  UNIQUE KEY `votes_un` (`idSpot`,`idUser`),
  KEY `votes_idUser_IDX` (`idUser`) USING BTREE,
  KEY `votes_idSpot_IDX` (`idSpot`) USING BTREE,
  CONSTRAINT `votes_FK` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `votes_FK_1` FOREIGN KEY (`idSpot`) REFERENCES `spots` (`idSpot`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Create User
--

CREATE USER 'dbAdminFitnesspot'@'localhost';
ALTER USER 'dbAdminFitnesspot'@'localhost'
IDENTIFIED BY 'change-me';

GRANT Alter ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT Create ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT Create view ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT Delete ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT Drop ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT Grant option ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT Index ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT Insert ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT References ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT Select ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT Show view ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT Trigger ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT Update ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT Alter routine ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT Create routine ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT Create temporary tables ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT Execute ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';
GRANT Lock tables ON fitnesspot_tpi.* TO 'dbAdminFitnesspot'@'localhost';

--
-- Dumping routines for database 'fitnesspot_tpi'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-05-18 11:11:10
