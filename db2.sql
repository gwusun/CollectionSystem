-- MySQL dump 10.14  Distrib 5.5.65-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: school_homework
-- ------------------------------------------------------
-- Server version	5.5.65-MariaDB

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
-- Current Database: `school_homework`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `school_homework` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `school_homework`;

--
-- Table structure for table `sh_admin`
--

DROP TABLE IF EXISTS `sh_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sh_admin` (
  `pk_admin` char(32) NOT NULL,
  `fk_user` char(45) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `status` enum('able','disable','deleted') DEFAULT NULL,
  PRIMARY KEY (`pk_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sh_admin`
--

LOCK TABLES `sh_admin` WRITE;
/*!40000 ALTER TABLE `sh_admin` DISABLE KEYS */;
INSERT INTO `sh_admin` VALUES ('1','1',0,0,'able');
/*!40000 ALTER TABLE `sh_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sh_classes`
--

DROP TABLE IF EXISTS `sh_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sh_classes` (
  `pk_classes` char(32) NOT NULL,
  `class_name` varchar(255) DEFAULT NULL COMMENT '班级名称\n',
  `create_time` int(11) DEFAULT NULL,
  `status` enum('able','disable','deleted') DEFAULT NULL,
  PRIMARY KEY (`pk_classes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sh_classes`
--

LOCK TABLES `sh_classes` WRITE;
/*!40000 ALTER TABLE `sh_classes` DISABLE KEYS */;
INSERT INTO `sh_classes` VALUES ('1','表格收集',0,'able');
/*!40000 ALTER TABLE `sh_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sh_homeworks`
--

DROP TABLE IF EXISTS `sh_homeworks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sh_homeworks` (
  `pk_homeworks` char(32) NOT NULL,
  `fk_classes` char(32) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `auto_name_rule` varchar(255) DEFAULT NULL COMMENT '命名规则\n',
  `create_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL COMMENT '截止日期',
  `desc` varchar(255) DEFAULT NULL,
  `status` enum('able','disable','deleted') DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk_homeworks`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sh_homeworks`
--

LOCK TABLES `sh_homeworks` WRITE;
/*!40000 ALTER TABLE `sh_homeworks` DISABLE KEYS */;
/*!40000 ALTER TABLE `sh_homeworks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sh_user`
--

DROP TABLE IF EXISTS `sh_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sh_user` (
  `pk_user` char(32) NOT NULL,
  `fk_class` char(32) DEFAULT NULL,
  `login_name` varchar(45) DEFAULT NULL,
  `login_passwork` varchar(45) DEFAULT NULL,
  `login_email` varchar(45) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `status` enum('able','disable','deleted') DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `stu_no` varchar(45) DEFAULT NULL COMMENT '学号',
  PRIMARY KEY (`pk_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sh_user`
--

LOCK TABLES `sh_user` WRITE;
/*!40000 ALTER TABLE `sh_user` DISABLE KEYS */;
INSERT INTO `sh_user` VALUES ('1','1','20150104010225','20150104010225','1228746736@qq.com',0,0,'able','毛麟','20150104010225');
INSERT INTO `sh_user` VALUES ('2','1','2019021964','2019021964','1228746736@qq.com',0,0,'able','孙武','2019021964');
/*!40000 ALTER TABLE `sh_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sh_works_items`
--

DROP TABLE IF EXISTS `sh_works_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sh_works_items` (
  `pk_works_items` char(32) NOT NULL,
  `fk_homeworks` char(32) DEFAULT NULL,
  `fk_user` char(32) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL COMMENT '文件路径',
  `create_time` enum('able','disable','deleted') DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk_works_items`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sh_works_items`
--

LOCK TABLES `sh_works_items` WRITE;
/*!40000 ALTER TABLE `sh_works_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `sh_works_items` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-03  5:07:24
