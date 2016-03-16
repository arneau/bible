-- MySQL dump 10.13  Distrib 5.6.27, for osx10.10 (x86_64)
--
-- Host: localhost    Database: defender-app
-- ------------------------------------------------------
-- Server version	5.6.27

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
-- Current Database: `defender-app`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `defender-app` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `defender-app`;

--
-- Table structure for table `defender_answer`
--

DROP TABLE IF EXISTS `defender_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_answer` (
  `answer_type_id` int(11) NOT NULL,
  `response_id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `defender_answer_fi_bdd93b` (`answer_type_id`),
  KEY `defender_answer_fi_210158` (`response_id`),
  CONSTRAINT `defender_answer_fk_210158` FOREIGN KEY (`response_id`) REFERENCES `defender_response` (`id`),
  CONSTRAINT `defender_answer_fk_bdd93b` FOREIGN KEY (`answer_type_id`) REFERENCES `defender_answer_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_answer`
--

LOCK TABLES `defender_answer` WRITE;
/*!40000 ALTER TABLE `defender_answer` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_answer_type`
--

DROP TABLE IF EXISTS `defender_answer_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_answer_type` (
  `value` varchar(255) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_answer_type`
--

LOCK TABLES `defender_answer_type` WRITE;
/*!40000 ALTER TABLE `defender_answer_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_answer_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_bible`
--

DROP TABLE IF EXISTS `defender_bible`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_bible` (
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_bible`
--

LOCK TABLES `defender_bible` WRITE;
/*!40000 ALTER TABLE `defender_bible` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_bible` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_book`
--

DROP TABLE IF EXISTS `defender_book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_book` (
  `chapter_count` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_book`
--

LOCK TABLES `defender_book` WRITE;
/*!40000 ALTER TABLE `defender_book` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_lesson`
--

DROP TABLE IF EXISTS `defender_lesson`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_lesson` (
  `summary` varchar(1000) NOT NULL,
  `tag_count` int(11) NOT NULL DEFAULT '0',
  `tree_left` int(11) DEFAULT NULL,
  `tree_right` int(11) DEFAULT NULL,
  `tree_level` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_lesson`
--

LOCK TABLES `defender_lesson` WRITE;
/*!40000 ALTER TABLE `defender_lesson` DISABLE KEYS */;
INSERT INTO `defender_lesson` VALUES ('Lessons',0,1,6,0,1),('Test lesson',0,2,5,1,2),('Another test',0,3,4,2,3);
/*!40000 ALTER TABLE `defender_lesson` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_lesson_tag`
--

DROP TABLE IF EXISTS `defender_lesson_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_lesson_tag` (
  `lesson_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `defender_lesson_tag_fi_df0bc4` (`lesson_id`),
  KEY `defender_lesson_tag_fi_f5ffad` (`tag_id`),
  CONSTRAINT `defender_lesson_tag_fk_df0bc4` FOREIGN KEY (`lesson_id`) REFERENCES `defender_lesson` (`id`),
  CONSTRAINT `defender_lesson_tag_fk_f5ffad` FOREIGN KEY (`tag_id`) REFERENCES `defender_tag` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_lesson_tag`
--

LOCK TABLES `defender_lesson_tag` WRITE;
/*!40000 ALTER TABLE `defender_lesson_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_lesson_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_response`
--

DROP TABLE IF EXISTS `defender_response`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_response` (
  `explanation` varchar(255) DEFAULT NULL,
  `text` varchar(255) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_response`
--

LOCK TABLES `defender_response` WRITE;
/*!40000 ALTER TABLE `defender_response` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_response` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_statement`
--

DROP TABLE IF EXISTS `defender_statement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_statement` (
  `response_id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `defender_statement_fi_210158` (`response_id`),
  CONSTRAINT `defender_statement_fk_210158` FOREIGN KEY (`response_id`) REFERENCES `defender_response` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_statement`
--

LOCK TABLES `defender_statement` WRITE;
/*!40000 ALTER TABLE `defender_statement` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_statement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_tag`
--

DROP TABLE IF EXISTS `defender_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_tag` (
  `verse_id` int(11) NOT NULL,
  `vote_count` int(11) DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `defender_tag_fi_a9051e` (`verse_id`),
  CONSTRAINT `defender_tag_fk_a9051e` FOREIGN KEY (`verse_id`) REFERENCES `defender_verse` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_tag`
--

LOCK TABLES `defender_tag` WRITE;
/*!40000 ALTER TABLE `defender_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_tag_translation`
--

DROP TABLE IF EXISTS `defender_tag_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_tag_translation` (
  `bible_id` int(11) NOT NULL,
  `relevant_words` varchar(255) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `defender_tag_translation_fi_34bfc9` (`bible_id`),
  KEY `defender_tag_translation_fi_f5ffad` (`tag_id`),
  CONSTRAINT `defender_tag_translation_fk_34bfc9` FOREIGN KEY (`bible_id`) REFERENCES `defender_bible` (`id`),
  CONSTRAINT `defender_tag_translation_fk_f5ffad` FOREIGN KEY (`tag_id`) REFERENCES `defender_tag` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_tag_translation`
--

LOCK TABLES `defender_tag_translation` WRITE;
/*!40000 ALTER TABLE `defender_tag_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_tag_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_tag_vote`
--

DROP TABLE IF EXISTS `defender_tag_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_tag_vote` (
  `tag_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `defender_tag_vote_fi_f5ffad` (`tag_id`),
  CONSTRAINT `defender_tag_vote_fk_f5ffad` FOREIGN KEY (`tag_id`) REFERENCES `defender_tag` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_tag_vote`
--

LOCK TABLES `defender_tag_vote` WRITE;
/*!40000 ALTER TABLE `defender_tag_vote` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_tag_vote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_topic`
--

DROP TABLE IF EXISTS `defender_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_topic` (
  `name` varchar(255) NOT NULL,
  `tag_count` int(11) NOT NULL DEFAULT '0',
  `tree_left` int(11) DEFAULT NULL,
  `tree_right` int(11) DEFAULT NULL,
  `tree_level` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_topic`
--

LOCK TABLES `defender_topic` WRITE;
/*!40000 ALTER TABLE `defender_topic` DISABLE KEYS */;
INSERT INTO `defender_topic` VALUES ('Topics',0,1,100,0,1),('Beliefs',0,2,9,1,2),('Athiesm',0,3,4,2,3),('Geocentrism',0,5,6,2,4),('Monotheism',0,7,8,2,5),('Religions',0,10,21,1,6),('Athiesm',0,11,12,2,7),('Budhism',0,13,14,2,8),('Jehovah Witnesses',0,15,16,2,9),('Islam',0,17,18,2,10),('Christianity',0,19,20,2,11),('Beings',0,22,31,1,12),('Angels',0,23,24,2,13),('Demons',0,25,26,2,14),('Satan',0,27,30,2,15),('God',0,32,67,1,16),('The Father',0,33,38,2,17),('The Son',0,39,46,2,18),('The Holy Spirit',0,47,48,2,19),('Events',0,68,93,1,20),('Creation',0,69,70,2,21),('The flood',0,71,72,2,22),('The judgement seat of Christ',0,73,74,2,23),('The second coming',0,75,76,2,24),('The day of Christ',0,77,78,2,25),('The day of the Lord',0,79,80,2,26),('The end times',0,81,82,2,27),('The exodus',0,83,84,2,28),('The tribulation',0,85,86,2,29),('Names',0,28,29,3,30),('Attributes',0,49,66,2,31),('Faithfulness',0,50,51,3,32),('Glory',0,52,53,3,33),('Grace',0,54,55,3,34),('Greatness',0,56,57,3,35),('Mercy',0,58,59,3,36),('Love',0,60,61,3,37),('Strength',0,62,63,3,38),('Voice',0,64,65,3,39),('Attributes',0,34,37,3,40),('Voice',0,35,36,4,41),('Attributes',0,40,43,3,42),('Blood',0,41,42,4,43),('The crucifixion',0,87,90,2,44),('The resurrection',0,91,92,2,45),('The cross',0,88,89,3,46),('Names',0,44,45,3,47),('God\'s Word',0,94,99,1,48),('Attributes',0,95,98,2,49),('Truth',0,96,97,3,50);
/*!40000 ALTER TABLE `defender_topic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_topic_adoptee`
--

DROP TABLE IF EXISTS `defender_topic_adoptee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_topic_adoptee` (
  `adoptee_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `defender_topic_adoptee_fi_e13667` (`topic_id`),
  CONSTRAINT `defender_topic_adoptee_fk_e13667` FOREIGN KEY (`topic_id`) REFERENCES `defender_topic` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_topic_adoptee`
--

LOCK TABLES `defender_topic_adoptee` WRITE;
/*!40000 ALTER TABLE `defender_topic_adoptee` DISABLE KEYS */;
INSERT INTO `defender_topic_adoptee` VALUES (16,12,1),(44,18,2),(45,18,3);
/*!40000 ALTER TABLE `defender_topic_adoptee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_topic_lesson`
--

DROP TABLE IF EXISTS `defender_topic_lesson`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_topic_lesson` (
  `lesson_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `defender_topic_lesson_fi_df0bc4` (`lesson_id`),
  KEY `defender_topic_lesson_fi_e13667` (`topic_id`),
  CONSTRAINT `defender_topic_lesson_fk_df0bc4` FOREIGN KEY (`lesson_id`) REFERENCES `defender_lesson` (`id`),
  CONSTRAINT `defender_topic_lesson_fk_e13667` FOREIGN KEY (`topic_id`) REFERENCES `defender_topic` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_topic_lesson`
--

LOCK TABLES `defender_topic_lesson` WRITE;
/*!40000 ALTER TABLE `defender_topic_lesson` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_topic_lesson` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_topic_synonym`
--

DROP TABLE IF EXISTS `defender_topic_synonym`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_topic_synonym` (
  `name` varchar(255) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `defender_topic_synonym_fi_e13667` (`topic_id`),
  CONSTRAINT `defender_topic_synonym_fk_e13667` FOREIGN KEY (`topic_id`) REFERENCES `defender_topic` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_topic_synonym`
--

LOCK TABLES `defender_topic_synonym` WRITE;
/*!40000 ALTER TABLE `defender_topic_synonym` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_topic_synonym` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_topic_tag`
--

DROP TABLE IF EXISTS `defender_topic_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_topic_tag` (
  `tag_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `defender_topic_tag_fi_f5ffad` (`tag_id`),
  KEY `defender_topic_tag_fi_e13667` (`topic_id`),
  CONSTRAINT `defender_topic_tag_fk_e13667` FOREIGN KEY (`topic_id`) REFERENCES `defender_topic` (`id`),
  CONSTRAINT `defender_topic_tag_fk_f5ffad` FOREIGN KEY (`tag_id`) REFERENCES `defender_tag` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_topic_tag`
--

LOCK TABLES `defender_topic_tag` WRITE;
/*!40000 ALTER TABLE `defender_topic_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_topic_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_translation`
--

DROP TABLE IF EXISTS `defender_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_translation` (
  `bible_id` int(11) NOT NULL,
  `text` varchar(1000) NOT NULL,
  `verse_id` int(11) NOT NULL,
  `word_count` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `defender_translation_fi_34bfc9` (`bible_id`),
  KEY `defender_translation_fi_a9051e` (`verse_id`),
  CONSTRAINT `defender_translation_fk_34bfc9` FOREIGN KEY (`bible_id`) REFERENCES `defender_bible` (`id`),
  CONSTRAINT `defender_translation_fk_a9051e` FOREIGN KEY (`verse_id`) REFERENCES `defender_verse` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_translation`
--

LOCK TABLES `defender_translation` WRITE;
/*!40000 ALTER TABLE `defender_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `defender_verse`
--

DROP TABLE IF EXISTS `defender_verse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defender_verse` (
  `book_id` int(11) NOT NULL,
  `chapter_number` int(11) NOT NULL,
  `verse_number` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `defender_verse_fi_570e00` (`book_id`),
  CONSTRAINT `defender_verse_fk_570e00` FOREIGN KEY (`book_id`) REFERENCES `defender_book` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `defender_verse`
--

LOCK TABLES `defender_verse` WRITE;
/*!40000 ALTER TABLE `defender_verse` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_verse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `propel_migration`
--

DROP TABLE IF EXISTS `propel_migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `propel_migration` (
  `version` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `propel_migration`
--

LOCK TABLES `propel_migration` WRITE;
/*!40000 ALTER TABLE `propel_migration` DISABLE KEYS */;
INSERT INTO `propel_migration` VALUES (1458149671);
/*!40000 ALTER TABLE `propel_migration` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-16 22:46:51
