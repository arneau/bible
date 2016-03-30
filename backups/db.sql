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
-- Dumping data for table `defender_answer`
--

LOCK TABLES `defender_answer` WRITE;
/*!40000 ALTER TABLE `defender_answer` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_answer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_answer_type`
--

LOCK TABLES `defender_answer_type` WRITE;
/*!40000 ALTER TABLE `defender_answer_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_answer_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_lesson`
--

LOCK TABLES `defender_lesson` WRITE;
/*!40000 ALTER TABLE `defender_lesson` DISABLE KEYS */;
INSERT INTO `defender_lesson` VALUES ('Lessons',0,1,2,0,1);
/*!40000 ALTER TABLE `defender_lesson` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_lesson_note`
--

LOCK TABLES `defender_lesson_note` WRITE;
/*!40000 ALTER TABLE `defender_lesson_note` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_lesson_note` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_lesson_tag`
--

LOCK TABLES `defender_lesson_tag` WRITE;
/*!40000 ALTER TABLE `defender_lesson_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_lesson_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_note`
--

LOCK TABLES `defender_note` WRITE;
/*!40000 ALTER TABLE `defender_note` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_note` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_note_content`
--

LOCK TABLES `defender_note_content` WRITE;
/*!40000 ALTER TABLE `defender_note_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_note_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_response`
--

LOCK TABLES `defender_response` WRITE;
/*!40000 ALTER TABLE `defender_response` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_response` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_statement`
--

LOCK TABLES `defender_statement` WRITE;
/*!40000 ALTER TABLE `defender_statement` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_statement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_tag`
--

LOCK TABLES `defender_tag` WRITE;
/*!40000 ALTER TABLE `defender_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_tag_translation`
--

LOCK TABLES `defender_tag_translation` WRITE;
/*!40000 ALTER TABLE `defender_tag_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_tag_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_tag_vote`
--

LOCK TABLES `defender_tag_vote` WRITE;
/*!40000 ALTER TABLE `defender_tag_vote` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_tag_vote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_topic`
--

LOCK TABLES `defender_topic` WRITE;
/*!40000 ALTER TABLE `defender_topic` DISABLE KEYS */;
INSERT INTO `defender_topic` VALUES ('Topics',0,1,116,0,1),('Beliefs',0,2,7,1,8),('Monotheism',0,3,4,2,9),('Geocentrism',0,5,6,2,10),('Religions',0,8,17,1,11),('Athiesm',0,9,10,2,12),('Budhism',0,11,12,2,13),('Jehovah Witnesses',0,13,14,2,14),('Islam',0,15,16,2,15),('Beings',0,18,27,1,16),('Angels',0,19,20,2,17),('Demons',0,21,22,2,18),('Satan',0,23,26,2,19),('Names',0,24,25,3,20),('God',0,28,71,1,21),('The Father',0,29,34,2,22),('The Son',0,35,48,2,23),('The Holy Spirit',0,49,52,2,24),('Events',0,72,97,1,25),('God\'s Word',0,98,111,1,26),('Attributes',0,99,102,2,27),('Timeline',0,103,110,2,28),('Pre flood',0,104,105,3,30),('Creation',0,73,74,2,32),('The flood',0,75,76,2,33),('The judgement seat of Christ',0,80,81,3,34),('The second coming',0,82,83,3,35),('The day of Christ',0,78,79,3,36),('The day of the Lord',0,86,87,3,37),('The end times',0,77,88,2,38),('The exodus',0,89,90,2,39),('The tribulation',0,84,85,3,40),('Attributes',0,53,70,2,41),('Faithfulness',0,54,55,3,42),('Glory',0,56,57,3,43),('Grace',0,58,59,3,44),('Greatness',0,60,61,3,45),('Mercy',0,62,63,3,46),('Love',0,64,65,3,47),('Strength',0,66,67,3,48),('Voice',0,68,69,3,49),('Attributes',0,30,33,3,50),('Voice',0,31,32,4,51),('Attributes',0,36,39,3,52),('Blood',0,37,38,4,53),('The crucifixion',0,91,92,2,54),('The resurrection',0,93,94,2,55),('Names',0,40,41,3,56),('Life',0,42,47,3,57),('Parables',0,45,46,4,58),('Miracles',0,43,44,4,59),('Gifts',0,50,51,3,60),('Truth',0,100,101,3,61),('Post flood',0,106,107,3,62),('Sojourn of Israel',0,108,109,3,63),('The fall of man',0,95,96,2,64),('Places',0,112,115,1,65),('Sodom and Gomorrah',0,113,114,2,66);
/*!40000 ALTER TABLE `defender_topic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_topic_adoptee`
--

LOCK TABLES `defender_topic_adoptee` WRITE;
/*!40000 ALTER TABLE `defender_topic_adoptee` DISABLE KEYS */;
INSERT INTO `defender_topic_adoptee` VALUES (12,8,1),(21,16,2),(54,57,3),(55,57,4),(32,28,5),(33,28,6),(39,28,7),(66,30,8);
/*!40000 ALTER TABLE `defender_topic_adoptee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_topic_lesson`
--

LOCK TABLES `defender_topic_lesson` WRITE;
/*!40000 ALTER TABLE `defender_topic_lesson` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_topic_lesson` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_topic_note`
--

LOCK TABLES `defender_topic_note` WRITE;
/*!40000 ALTER TABLE `defender_topic_note` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_topic_note` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_topic_synonym`
--

LOCK TABLES `defender_topic_synonym` WRITE;
/*!40000 ALTER TABLE `defender_topic_synonym` DISABLE KEYS */;
INSERT INTO `defender_topic_synonym` VALUES ('Beliefs',8,7),('Monotheism',9,8),('Geocentrism',10,9),('Religions',11,10),('Athiesm',12,11),('Budhism',13,12),('Jehovah Witnesses',14,13),('Islam',15,14),('Beings',16,15),('Angels',17,16),('Demons',18,17),('Satan',19,18),('Names',20,19),('God',21,20),('The Father',22,21),('The Son',23,22),('The Holy Spirit',24,23),('Events',25,24),('God\'s Word',26,25),('Attributes',27,26),('Timeline',28,27),('Pre flood',30,29),('Creation',32,31),('The flood',33,32),('The judgement seat of Christ',34,33),('The second coming',35,34),('The day of Christ',36,35),('The day of the Lord',37,36),('The end times',38,37),('The exodus',39,38),('The tribulation',40,39),('Attributes',41,40),('Faithfulness',42,41),('Glory',43,42),('Grace',44,43),('Greatness',45,44),('Mercy',46,45),('Love',47,46),('Strength',48,47),('Voice',49,48),('Attributes',50,49),('Voice',51,50),('Attributes',52,51),('Blood',53,52),('The crucifixion',54,53),('The resurrection',55,54),('Names',56,55),('Life',57,56),('Parables',58,57),('Miracles',59,58),('Gifts',60,59),('Truth',61,60),('Post flood',62,61),('Sojourn of Israel',63,62),('The fall of man',64,63),('Places',65,64),('Sodom and Gomorrah',66,65);
/*!40000 ALTER TABLE `defender_topic_synonym` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_topic_tag`
--

LOCK TABLES `defender_topic_tag` WRITE;
/*!40000 ALTER TABLE `defender_topic_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `defender_topic_tag` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-30 21:11:25
