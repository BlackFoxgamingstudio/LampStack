CREATE DATABASE  IF NOT EXISTS `booksdb` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `BooksDB`;
-- MySQL dump 10.13  Distrib 5.5.24, for osx10.5 (i386)
--
-- Host: 127.0.0.1    Database: BooksDB
-- ------------------------------------------------------
-- Server version	5.5.38

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
-- Table structure for table `authors`
--

DROP TABLE IF EXISTS `authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authors`
--

LOCK TABLES `authors` WRITE;
/*!40000 ALTER TABLE `authors` DISABLE KEYS */;
INSERT INTO `authors` VALUES (1,'Eastman ','P.D. ','2015-02-28 20:19:49','2015-02-28 20:19:49'),(2,'Peter',' Parnell ','2015-02-28 20:19:49','2015-02-28 20:19:49'),(3,'Henry','Cole','2015-02-28 20:19:49','2015-02-28 20:19:49'),(4,'Laura','Lopez','2015-02-28 20:19:49','2015-02-28 20:19:49'),(5,'Sentoz','Sam','2015-02-28 20:19:49','2015-02-28 20:19:49'),(6,'Phill','Dean','2015-02-28 20:19:49','2015-02-28 20:19:49'),(7,'Judy','Blume','2015-02-28 20:19:49','2015-02-28 20:19:49'),(8,'LOPEZ','JENRY','2015-03-04 02:00:46','2015-03-04 02:00:46'),(9,'Huro',NULL,'2015-03-04 02:09:10','2015-03-04 02:09:10'),(10,'Huro',NULL,'2015-03-04 02:09:34','2015-03-04 02:09:34'),(11,'Samy','Tellka','2015-03-04 02:09:57','2015-03-04 02:09:57'),(12,'Samy','Tellka','2015-03-04 02:16:03','2015-03-04 02:16:03'),(39,'Pahris','williams','2015-03-04 17:56:43','2015-03-04 17:56:43'),(41,'Marie','Kondo','2015-03-04 19:50:10','2015-03-04 19:50:10'),(42,'Christiane','Northrup,','2015-03-04 19:51:39','2015-03-04 19:51:39'),(43,'Brent','Schlender','2015-03-04 21:20:26','2015-03-04 21:20:26'),(44,'Unkown','author','2015-03-05 00:26:46','2015-03-05 00:26:46');
/*!40000 ALTER TABLE `authors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
INSERT INTO `books` VALUES (23,'Codeigniter Ellislab',4,'2015-03-04 18:23:34','2015-03-04 18:23:34'),(24,'The Life-Changing Magic of Tidying Up: The Japanese Art of Decluttering and Organizing',41,'2015-03-04 19:50:10','2015-03-04 19:50:10'),(25,'All the Light We Cannot See: A Novel',4,'2015-03-04 19:50:52','2015-03-04 19:50:52'),(26,'Goddesses Never Age: The Secret Prescription for Radiance, Vitality, and Well-Being',42,'2015-03-04 19:51:39','2015-03-04 19:51:39'),(27,'Becoming Steve Jobs: The Evolution of a Reckless Upstart into a Visionary Leader',43,'2015-03-04 21:20:26','2015-03-04 21:20:26'),(28,'Little Blue Truck Board Book ',6,'2015-03-04 21:23:50','2015-03-04 21:23:50'),(29,'Born again',44,'2015-03-05 00:26:46','2015-03-05 00:26:46');
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8_unicode_ci,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (14,'I will admit to having a tortured relationship with stuff. I grew up in a cluttered house and married the King of Clutter (he\'s the type of person who\'ll open a credit card bill, pay it online, and then just leave the empty envelope, inserts, and bill itself randomly strewn on whatever surface happens to be nearby). I don\'t like the disorder of clutter, but dealing with it is such a soul-sucking experience that I haven\'t gotten very far. Many days I semi-wish the whole place would burn down and save me from having to deal with it.',24,5,5,'2015-03-04 19:50:10','2015-03-04 19:50:10'),(15,'ALL THE LIGHT WE CANNOT SEE is one of the best books you’ll read this year. On one hand, the title implies the lessons learned by a young German orphan boy about radio waves. On the other hand, as the author describes it, “It’s also a metaphorical suggestion that there are countless invisible stories still buried within World War II.” Add in a newly blinded French girl who is forced to leave her familiar surroundings, and you’ll soon find yourself in literary heaven.',25,5,2,'2015-03-04 19:50:52','2015-03-04 19:50:52'),(16,'‘Goddesses Never Age’ written by Christiane Northrup M.D. is a well-written book full of life wisdom, an interesting self-help title about achieving happiness in years when others see us as not so young any more – fighting the inevitable but still having so many reasons to feel good in our bodies and fully enjoy every day of life before us.\n',26,5,1,'2015-03-04 19:51:39','2015-03-04 19:51:39'),(17,'Anothe great book',26,5,4,'2015-03-04 20:40:58','2015-03-04 20:40:58'),(18,'Not vey good',26,5,4,'2015-03-04 20:42:38','2015-03-04 20:42:38'),(19,'Not vey good',26,5,4,'2015-03-04 20:44:06','2015-03-04 20:44:06'),(20,'Must read',26,5,5,'2015-03-04 20:44:18','2015-03-04 20:44:18'),(21,'Great read',25,5,3,'2015-03-04 21:02:29','2015-03-04 21:02:29'),(22,'not good',26,5,1,'2015-03-04 21:03:11','2015-03-04 21:03:11'),(23,'',26,5,1,'2015-03-04 21:18:41','2015-03-04 21:18:41'),(24,'Fanstatic',26,8,5,'2015-03-04 21:19:32','2015-03-04 21:19:32'),(25,'“Becoming Steve Jobs is fantastic. After working with Steve for over 25 years, I feel this book captures with great insight the growth and complexity of a truly extraordinary person. I hope that it will be recognized as the definitive history.”',27,8,3,'2015-03-04 21:20:26','2015-03-04 21:20:26'),(26,' is one of the premiere chroniclers of the personal computer revolution, writing about every major figure and company in the tech industry. He covered Steve Jobs for the Wall Street Journal and Fortune for nearly 25 years. ',27,8,1,'2015-03-04 21:22:11','2015-03-04 21:22:11'),(27,'Beep! Beep! Beep! Meet Blue. A muddy country road is no match for this little pick up--that is, until he gets stuck while pushing a dump truck out of the muck. Luckily, Blue has made a pack of farm animal friends along his route. And they\'re willing to do whatever it takes to get their pal back on the road.\nFilled with truck sounds and animals noises, here is a rollicking homage to the power of friendship and the rewards of helping others.',28,8,3,'2015-03-04 21:23:50','2015-03-04 21:23:50'),(28,'This book is chockful of excellent textual sound effects (audio quality depends upon the reader, of course). The little truck beeps its horn and numerous animals sound their moos, quacks, clucks, peeps, etc. A great read for young children, and a valuable tool for teaching animals and animal sounds. In addition, the story has great rhyme and rhythm and contains a lesson about nurturing relationships with others.',28,8,3,'2015-03-04 21:24:21','2015-03-04 21:24:21'),(29,'Excellent.. Worth a shot',28,9,5,'2015-03-05 00:25:36','2015-03-05 00:25:36'),(30,'Goddesses Never Age’ written by Christiane Northrup M.D. is a well-written book full of life wisdom, an interesting self-help title about achieving happiness in years ',26,9,1,'2015-03-05 00:26:00','2015-03-05 00:26:00'),(31,'Not much quality',29,9,1,'2015-03-05 00:26:46','2015-03-05 00:26:46');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'Rajamani','Josey','rajmj83@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','2015-03-03 17:30:16','2015-03-03 17:30:16'),(4,'joanna','asd','joannaraj@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','2015-03-03 18:18:32','2015-03-03 18:18:32'),(5,'Simi','asdf','simi@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','2015-03-03 18:49:15','2015-03-03 18:49:15'),(6,'bryan','tilt','123@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','2015-03-03 19:24:22','2015-03-03 19:24:22'),(7,'Simi','Tresa Antony','simi.tresa.antony@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','2015-03-03 20:29:25','2015-03-03 20:29:25'),(8,'Simi','Raj','simiraj2010@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','2015-03-04 21:19:13','2015-03-04 21:19:13'),(9,'joanna','Raj','joannaraj123@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','2015-03-05 00:24:51','2015-03-05 00:24:51');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-03-04 16:45:59
