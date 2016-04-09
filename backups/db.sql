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
INSERT INTO `defender_lesson` VALUES ('Lessons',0,1,108,0,1),('God upholds everything',5,2,7,1,2),('God\'s Word upholds',2,3,4,2,3),('By Jesus are all things',1,5,6,2,4),('Fear the Lord',1,8,17,1,5),('The fear of the Lord is the beginning of wisdom',3,9,12,2,6),('And knowledge',1,10,11,3,7),('Fools despise wisdom',3,18,21,1,8),('Fools despise instruction',1,22,23,1,9),('And knowledge',2,19,20,2,10),('Fools despise correction and reproof',1,24,25,1,11),('The Lord gives wisdom',2,26,29,1,12),('His mouth speaks knowledge and understanding',1,27,28,2,13),('Trust in the Lord',1,30,31,1,14),('Don\'t rely on your own understanding',2,32,33,1,15),('Let God be God over everything',2,34,35,1,16),('Fearing the Lord is a blessing',1,13,14,2,17),('Give God your firstfruits',1,36,39,1,18),('Doing so is a blessing',1,37,38,2,19),('Don\'t despise God\'s correction',2,40,43,1,20),('The Lord loves those He corrects',1,41,42,2,21),('Happy is he who finds wisdom',2,62,65,3,22),('Happy is he who gets understanding',1,44,45,1,23),('Seek wisdom as treasure',2,47,52,2,24),('Wisdom is worth more than all riches',4,48,51,3,25),('Nothing to be desired can be compared to wisdom',2,49,50,4,26),('Seek wisdom',2,46,71,1,27),('Finds wisdom',5,60,61,3,28),('And keeps her',1,63,64,4,29),('God created the earth',1,72,79,1,30),('God created the heavens',1,80,83,1,31),('By wisdom',1,73,74,2,32),('By understanding',1,81,82,2,33),('He broke up the depths',1,75,78,2,34),('By his knowledge',1,76,77,3,35),('Keep wisdom',1,84,87,1,36),('Happy is he who keeps her',1,85,86,2,37),('Jesus is God',2,88,95,1,38),('Jesus is Lord',3,89,90,2,39),('Jesus is our Saviour',1,91,92,2,40),('Jesus is the Christ',2,93,94,2,41),('Don\'t alter God\'s Word',1,96,103,1,42),('Don\'t add to it',2,97,98,2,43),('Don\'t take away from it',1,99,100,2,44),('Don\'t take away from it',0,101,102,2,45),('Jesus is eternal',2,104,105,1,46),('The fear of the Lord is ...',1,15,16,2,47),('Those that seek wisdom will find it',1,53,54,2,48),('Wisdom is durable riches',2,55,56,2,49),('Wisdom is eternal',5,57,58,2,50),('God\'s Word is scientific',1,106,107,1,51),('Blessed is he who ...',0,59,70,2,52),('Keeps wisdom',2,66,67,3,53),('Hears wisdom',1,68,69,3,54);
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
INSERT INTO `defender_lesson_tag` VALUES (2,1,1),(3,2,2),(2,3,3),(3,4,4),(2,5,5),(2,6,6),(2,7,7),(4,8,8),(6,10,9),(6,11,10),(7,12,11),(6,13,12),(8,14,13),(9,15,14),(10,16,15),(8,17,16),(8,18,17),(10,19,18),(11,20,19),(12,21,20),(13,22,21),(12,23,22),(14,24,23),(15,25,24),(16,26,25),(15,27,26),(5,28,27),(17,29,28),(16,30,29),(18,31,30),(19,32,31),(20,33,32),(21,34,33),(23,35,34),(22,36,35),(24,37,36),(25,38,37),(25,39,38),(26,40,39),(28,41,40),(28,42,41),(28,43,42),(22,44,43),(29,45,44),(30,46,45),(32,47,46),(31,48,47),(33,49,48),(34,50,49),(35,51,50),(36,52,51),(37,53,52),(38,54,53),(38,55,54),(39,56,55),(39,57,56),(39,58,57),(40,59,58),(41,60,59),(41,61,60),(43,62,61),(43,63,62),(44,64,63),(42,65,64),(20,66,65),(46,67,66),(46,68,67),(27,69,68),(24,70,69),(25,71,70),(26,72,71),(47,73,72),(48,74,73),(25,75,74),(49,76,75),(49,77,76),(50,78,77),(50,79,78),(50,80,79),(50,81,80),(50,82,81),(51,83,82),(28,84,83),(53,85,84),(53,86,85),(27,87,86),(54,88,87),(28,89,88);
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
INSERT INTO `defender_tag` VALUES (30530,0,1),(30530,0,2),(29967,0,3),(29967,0,4),(29483,0,5),(28246,0,6),(28534,0,7),(28534,0,8),(15804,0,9),(15804,0,10),(16408,0,11),(16408,0,12),(16649,0,13),(16408,0,14),(16408,0,15),(16423,0,16),(16423,0,17),(16430,0,18),(16430,0,19),(16431,0,20),(16440,0,21),(16440,0,22),(16441,0,23),(16461,0,24),(16461,0,25),(16462,0,26),(16463,0,27),(16463,0,28),(16464,0,29),(16465,0,30),(16465,0,31),(16466,0,32),(16467,0,33),(16468,0,34),(16469,0,35),(16469,0,36),(16438,0,37),(16470,0,38),(16471,0,39),(16471,0,40),(16472,0,41),(16473,0,42),(16474,0,43),(16474,0,44),(16474,0,45),(16475,0,46),(16475,0,47),(16475,0,48),(16475,0,49),(16476,0,50),(16476,0,51),(16477,0,52),(16474,0,53),(24910,0,54),(24911,0,55),(24937,0,56),(24970,0,57),(24985,0,58),(24985,0,59),(24985,0,60),(25000,0,61),(17258,0,62),(31099,0,63),(31100,0,64),(28842,0,65),(30218,0,66),(22636,0,67),(26048,0,68),(16608,0,69),(16613,0,70),(16614,0,71),(16614,0,72),(16616,0,73),(16620,0,74),(16622,0,75),(16621,0,76),(16624,0,77),(16625,0,78),(16626,0,79),(16627,0,80),(16628,0,81),(16629,0,82),(16630,0,83),(16469,0,84),(16474,0,85),(16635,0,86),(16636,0,87),(16637,0,88),(16638,0,89);
/*!40000 ALTER TABLE `defender_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `defender_tag_translation`
--

LOCK TABLES `defender_tag_translation` WRITE;
/*!40000 ALTER TABLE `defender_tag_translation` DISABLE KEYS */;
INSERT INTO `defender_tag_translation` VALUES (1,'2-6,10-17',1,1),(1,'1-30',2,2),(1,'16-24',3,3),(1,'1-43',4,4),(1,'8-12',5,5),(1,'5-6,10-12',6,6),(1,'1-33',7,7),(1,'1-33',8,8),(1,'1-10',10,9),(1,'1-10',11,10),(1,'1-10',12,11),(1,'1-10',13,12),(1,'12-14',14,13),(1,'12-13,16',15,14),(1,'',16,15),(1,'1-2,6,18-20',17,16),(1,'3-5',18,17),(1,'1-14',19,18),(1,'1-11',20,19),(1,'1-13',21,20),(1,'1-13',22,21),(1,'1-17',23,22),(1,'1-15',24,23),(1,'10-15',25,24),(1,'1-12',26,25),(1,'1-7',27,26),(1,'8-10',28,27),(1,'1-12',29,28),(1,'1-14',30,29),(1,'1-14',31,30),(1,'1-17',32,31),(1,'3-15',33,32),(1,'2-7',34,33),(1,'1-13',35,34),(1,'1-13',36,35),(1,'1-14',37,36),(1,'2-12,14-19',38,37),(1,'1-6,1-6',39,38),(1,'8-20',40,39),(1,'1-16',41,40),(1,'1-12',42,41),(1,'1-21',43,42),(1,'1-21',44,43),(1,'1-21',45,44),(1,'1-15',46,45),(1,'1-15',47,46),(1,'1-15',48,47),(1,'1-15',49,48),(1,'1-15',50,49),(1,'1-15',51,50),(1,'1-14',52,51),(1,'1-21',53,52),(1,'1-15',54,53),(1,'1-41',55,54),(1,'1-16',56,55),(1,'1-25',57,56),(1,'1-19',58,57),(1,'2-5,13-14',59,58),(1,'1-19',60,59),(1,'1-23',61,60),(1,'1-16',62,61),(1,'17-37',63,62),(1,'2-44',64,63),(1,'2-12',65,64),(1,'2-6,16-31',66,65),(1,'15-21,31-35,39-40',67,66),(1,'1-6,8-17',68,67),(1,'1-5,7-14',69,68),(1,'1-12',70,69),(1,'2-6',71,70),(1,'8-21',72,71),(1,'1-9',73,72),(1,'8-15',74,73),(1,'1-16',75,74),(1,'1,1,4-9,4-9',76,75),(1,'2,4-11,13-17',77,76),(1,'1-15',78,77),(1,'1-14',79,78),(1,'1-17',80,79),(1,'1-12',81,80),(1,'1-22',82,81),(1,'10-19',83,82),(1,'1-7',84,83),(1,'15-21',85,84),(1,'10-16',86,85),(1,'1-9',87,86),(1,'1-7',88,87),(1,'2-13,2-13',89,88);
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
INSERT INTO `defender_topic` VALUES ('Topics',0,1,118,0,1),('Beliefs',0,2,7,1,8),('Monotheism',0,3,4,2,9),('Geocentrism',0,5,6,2,10),('Religions',0,8,17,1,11),('Athiesm',0,9,10,2,12),('Budhism',0,11,12,2,13),('Jehovah Witnesses',0,13,14,2,14),('Islam',0,15,16,2,15),('Beings',0,18,27,1,16),('Angels',0,19,20,2,17),('Demons',0,21,22,2,18),('Satan',0,23,26,2,19),('Names',0,24,25,3,20),('God',0,28,73,1,21),('The Father',0,29,34,2,22),('The Son',0,35,48,2,23),('The Holy Spirit',0,49,52,2,24),('Events',0,74,99,1,25),('God\'s Word',0,100,113,1,26),('Attributes',0,101,104,2,27),('Timeline',0,105,112,2,28),('Pre flood',0,106,107,3,30),('Creation',0,75,76,2,32),('The flood',0,77,78,2,33),('The judgement seat of Christ',0,82,83,3,34),('The second coming',0,84,85,3,35),('The day of Christ',0,80,81,3,36),('The day of the Lord',0,88,89,3,37),('The end times',0,79,90,2,38),('The exodus',0,91,92,2,39),('The tribulation',0,86,87,3,40),('Attributes',0,53,72,2,41),('Faithfulness',0,54,55,3,42),('Glory',0,56,57,3,43),('Grace',0,58,59,3,44),('Greatness',0,60,61,3,45),('Mercy',0,62,63,3,46),('Love',0,64,65,3,47),('Strength',0,66,67,3,48),('Voice',0,68,69,3,49),('Attributes',0,30,33,3,50),('Voice',0,31,32,4,51),('Attributes',0,36,39,3,52),('Blood',0,37,38,4,53),('The crucifixion',0,93,94,2,54),('The resurrection',0,95,96,2,55),('Names',0,40,41,3,56),('Life',0,42,47,3,57),('Parables',0,45,46,4,58),('Miracles',0,43,44,4,59),('Gifts',0,50,51,3,60),('Truth',0,102,103,3,61),('Post flood',0,108,109,3,62),('Sojourn of Israel',0,110,111,3,63),('The fall of man',0,97,98,2,64),('Places',0,114,117,1,65),('Sodom and Gomorrah',0,115,116,2,66),('Sovereignty',0,70,71,3,67);
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
INSERT INTO `defender_topic_synonym` VALUES ('Beliefs',8,7),('Monotheism',9,8),('Geocentrism',10,9),('Religions',11,10),('Athiesm',12,11),('Budhism',13,12),('Jehovah Witnesses',14,13),('Islam',15,14),('Beings',16,15),('Angels',17,16),('Demons',18,17),('Satan',19,18),('Names',20,19),('God',21,20),('The Father',22,21),('The Son',23,22),('The Holy Spirit',24,23),('Events',25,24),('God\'s Word',26,25),('Attributes',27,26),('Timeline',28,27),('Pre flood',30,29),('Creation',32,31),('The flood',33,32),('The judgement seat of Christ',34,33),('The second coming',35,34),('The day of Christ',36,35),('The day of the Lord',37,36),('The end times',38,37),('The exodus',39,38),('The tribulation',40,39),('Attributes',41,40),('Faithfulness',42,41),('Glory',43,42),('Grace',44,43),('Greatness',45,44),('Mercy',46,45),('Love',47,46),('Strength',48,47),('Voice',49,48),('Attributes',50,49),('Voice',51,50),('Attributes',52,51),('Blood',53,52),('The crucifixion',54,53),('The resurrection',55,54),('Names',56,55),('Life',57,56),('Parables',58,57),('Miracles',59,58),('Gifts',60,59),('Truth',61,60),('Post flood',62,61),('Sojourn of Israel',63,62),('The fall of man',64,63),('Places',65,64),('Sodom and Gomorrah',66,65),('Sovereignty',67,66);
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

-- Dump completed on 2016-04-09 14:23:27
