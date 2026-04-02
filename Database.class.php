-- MySQL dump 10.16  Distrib 10.1.48-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: linkup
-- ------------------------------------------------------
-- Server version	10.1.48-MariaDB-0+deb9u2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,6,17,'salut','2026-04-01 14:41:34'),(2,6,17,'salut','2026-04-01 14:45:50'),(3,6,17,'saluttt','2026-04-01 14:46:09'),(4,6,17,'ouii moi jfais du piano comme thadée','2026-04-01 15:05:23'),(5,6,17,'ah oeeeeee','2026-04-01 15:09:12'),(6,7,17,'bjkgbzrlkgbrzhlig','2026-04-01 15:47:26');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_participants`
--

DROP TABLE IF EXISTS `event_participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_participants` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`event_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_participants`
--

LOCK TABLES `event_participants` WRITE;
/*!40000 ALTER TABLE `event_participants` DISABLE KEYS */;
INSERT INTO `event_participants` VALUES (4,0);
/*!40000 ALTER TABLE `event_participants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `visibility` enum('private','shared','public') COLLATE utf8mb4_unicode_ci DEFAULT 'public',
  PRIMARY KEY (`id`),
  KEY `creator_id` (`creator_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (3,0,'Audition Batteur','On cherche une brute pour notre prochain album.','2026-04-25 10:00:00','2026-04-25 18:00:00','public'),(4,0,'Répé Funk 70s','Session privée pour le groupe.','2026-04-05 19:00:00','2026-04-05 22:00:00','private'),(6,8,'rthreytj','ytje','2026-04-15 22:22:00','2026-04-15 04:04:00','public'),(7,21,'kkkkkkkkkkkk','kkkkkkkkkkkkkkk','2026-04-02 16:38:00','2026-04-02 16:42:00','public'),(8,11,'test','tes','2026-04-02 17:25:00','2026-04-02 17:24:00','private'),(9,11,' tfg','g','2026-04-07 17:24:00','2026-04-07 17:24:00','public');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `follows`
--

DROP TABLE IF EXISTS `follows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `follows` (
  `follower_id` int(11) NOT NULL,
  `followed_id` int(11) NOT NULL,
  `status` enum('pending','accepted') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  PRIMARY KEY (`follower_id`,`followed_id`),
  KEY `followed_id` (`followed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follows`
--

LOCK TABLES `follows` WRITE;
/*!40000 ALTER TABLE `follows` DISABLE KEYS */;
INSERT INTO `follows` VALUES (11,17,''),(17,8,''),(17,9,''),(17,10,''),(17,11,''),(17,12,''),(17,13,''),(17,16,''),(17,22,''),(17,23,''),(21,17,''),(21,18,''),(21,19,''),(21,20,''),(23,17,'');
/*!40000 ALTER TABLE `follows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hashtags`
--

DROP TABLE IF EXISTS `hashtags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hashtags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hashtags`
--

LOCK TABLES `hashtags` WRITE;
/*!40000 ALTER TABLE `hashtags` DISABLE KEYS */;
INSERT INTO `hashtags` VALUES (4,'Ableton'),(3,'Funk'),(5,'JamSession'),(1,'Jazz'),(2,'Metal'),(7,'Piano'),(6,'Vinyl');
/*!40000 ALTER TABLE `hashtags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,'Français',''),(2,'English',''),(3,'Español',''),(4,'Svenska','');
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,17,11,'Salut Rihab ! Tu es dispo pour enregistrer un solo de saxo ?',0,'2026-04-01 13:33:46'),(2,11,17,'hey',0,'2026-04-01 13:34:04'),(3,17,11,'salutttttt',0,'2026-04-01 13:34:17'),(4,11,17,'ca fonctionneeeee',0,'2026-04-01 13:34:30'),(5,17,11,'ouiiii',0,'2026-04-01 13:34:38'),(6,17,11,'salut',0,'2026-04-01 13:36:33'),(7,11,17,'fgjgho',0,'2026-04-01 13:36:41'),(8,17,11,'salutttt',0,'2026-04-01 14:23:48'),(9,11,17,'heyyy....',0,'2026-04-01 14:23:57'),(10,17,23,'oeeeeeeeeee',0,'2026-04-01 15:40:35'),(11,17,23,'😔',0,'2026-04-01 15:40:49'),(12,17,23,'❤️',0,'2026-04-01 15:40:55'),(13,23,17,'Okeyyyy',0,'2026-04-01 15:41:06'),(14,23,17,'tru',0,'2026-04-01 15:46:51'),(15,23,17,'a',0,'2026-04-01 15:48:10'),(16,23,17,'a',0,'2026-04-01 15:48:10'),(17,23,17,'a',0,'2026-04-01 15:48:11'),(18,23,17,'a',0,'2026-04-01 15:48:11'),(19,23,17,'a',0,'2026-04-01 15:48:11'),(20,23,17,'a',0,'2026-04-01 15:48:11'),(21,23,17,'a',0,'2026-04-01 15:48:11'),(22,23,17,'a',0,'2026-04-01 15:48:11'),(23,23,17,'a',0,'2026-04-01 15:48:11'),(24,23,17,'a',0,'2026-04-01 15:48:12'),(25,23,17,'a',0,'2026-04-01 15:48:12'),(26,23,17,'a',0,'2026-04-01 15:48:12'),(27,23,17,'a',0,'2026-04-01 15:48:12'),(28,23,17,'a',0,'2026-04-01 15:48:12'),(29,23,17,'a',0,'2026-04-01 15:48:12'),(30,23,17,'a',0,'2026-04-01 15:48:12'),(31,23,17,'a',0,'2026-04-01 15:48:12'),(32,23,17,'a',0,'2026-04-01 15:48:12'),(33,23,17,'a',0,'2026-04-01 15:48:13'),(34,23,17,'a',0,'2026-04-01 15:48:13'),(35,23,17,'a',0,'2026-04-01 15:48:13'),(36,23,17,'aa',0,'2026-04-01 15:48:13'),(37,23,17,'a',0,'2026-04-01 15:48:13'),(38,23,17,'a',0,'2026-04-01 15:48:13'),(39,23,17,'a',0,'2026-04-01 15:48:13'),(40,23,17,'a',0,'2026-04-01 15:48:14'),(41,23,17,'a',0,'2026-04-01 15:48:14'),(42,23,17,'a',0,'2026-04-01 15:48:14'),(43,23,17,'a',0,'2026-04-01 15:48:14'),(44,23,17,'a',0,'2026-04-01 15:48:14'),(45,23,17,'a',0,'2026-04-01 15:48:14'),(46,23,17,'a',0,'2026-04-01 15:48:14'),(47,23,17,'a',0,'2026-04-01 15:48:15'),(48,23,17,'aa',0,'2026-04-01 15:48:15'),(49,23,17,'a',0,'2026-04-01 15:48:15'),(50,23,17,'a',0,'2026-04-01 15:48:15'),(51,23,17,'a',0,'2026-04-01 15:48:15'),(52,23,17,'a',0,'2026-04-01 15:48:16'),(53,23,17,'a',0,'2026-04-01 15:48:16'),(54,23,17,'a',0,'2026-04-01 15:48:16');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `music_boards`
--

DROP TABLE IF EXISTS `music_boards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `music_boards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `creator_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `creator_id` (`creator_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `music_boards`
--

LOCK TABLES `music_boards` WRITE;
/*!40000 ALTER TABLE `music_boards` DISABLE KEYS */;
/*!40000 ALTER TABLE `music_boards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` enum('follow','mention','event','message') COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_id` int(11) DEFAULT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,0,'follow',NULL,'Clara Slap a commencé à vous suivre.',0,'2026-04-01 09:32:40');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_likes`
--

DROP TABLE IF EXISTS `post_likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_like` (`post_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_likes`
--

LOCK TABLES `post_likes` WRITE;
/*!40000 ALTER TABLE `post_likes` DISABLE KEYS */;
INSERT INTO `post_likes` VALUES (16,6,17,'2026-04-01 15:05:08'),(17,7,17,'2026-04-01 15:25:50');
/*!40000 ALTER TABLE `post_likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_media`
--

DROP TABLE IF EXISTS `post_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `media_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_type` enum('image','video') COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  CONSTRAINT `post_media_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_media`
--

LOCK TABLES `post_media` WRITE;
/*!40000 ALTER TABLE `post_media` DISABLE KEYS */;
INSERT INTO `post_media` VALUES (1,5,'img/post_piano.png','image');
/*!40000 ALTER TABLE `post_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `board_id` int(11) DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('standard','collaboration','transmission') COLLATE utf8mb4_unicode_ci DEFAULT 'standard',
  `location_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `board_id` (`board_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,0,NULL,'Quelqu\'un pour une jam session jazz ce soir au Duc des Lombards ? 🎷','standard','Paris, FR','2026-04-01 09:30:01'),(2,0,NULL,'Je viens de finir d\'accorder ma Strato, le son est incroyable. 🎸✨','standard','London, UK','2026-04-01 09:30:01'),(3,0,NULL,'Est-ce que quelqu\'un a une partition de Marcus Miller sous le coude ? SOS groove ! 🎸','standard','Marseille, FR','2026-04-01 09:32:29'),(4,0,NULL,'Je cherche un local de répé sur Stockholm qui accepte le bruit après 22h. 🤘','standard','Stockholm, SE','2026-04-01 09:32:29'),(5,0,NULL,'Je viens de poster une cover de Robert Glasper sur mon MusicBoard. Allez checker ! 🎹✨','standard','Lyon, FR','2026-04-01 09:32:29'),(6,11,NULL,'Salut LinkUp ! Rihab au saxo, je cherche des gens pour une jam session ce week-end sur Paris ! 🎷','standard',NULL,'2026-04-01 11:01:37'),(7,12,NULL,'Est-ce que quelqu\'un connaît un bon luthier pour régler ma basse ? Le slap ça use les cordes... 🎸✨','standard',NULL,'2026-04-01 11:01:37'),(8,13,NULL,'Répétition terminée avec mon groupe de métal. On cherche une deuxième guitare, des intéressés ? 🥁🔥','standard',NULL,'2026-04-01 11:01:37'),(9,14,NULL,'Le flamenco, c\'est plus qu\'une musique, c\'est une émotion. Travail de l\'acoustique ce matin. 💃','standard',NULL,'2026-04-01 11:01:37'),(10,15,NULL,'Je viens de finir de patcher mon nouveau synthé modulaire. Le son est lunaire ! 🎹🚀','standard',NULL,'2026-04-01 11:01:37'),(11,16,NULL,'Besoin d\'un trompettiste pour un enregistrement studio ? Je suis dispo toute la semaine ! 🎺','standard',NULL,'2026-04-01 11:01:37'),(12,9,NULL,'Super contente de voir que la communauté LinkUp s\'agrandit ! Bienvenue à tous les musiciens. 🎵⚡','standard',NULL,'2026-04-01 11:01:37');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_hashtags`
--

DROP TABLE IF EXISTS `user_hashtags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_hashtags` (
  `user_id` int(11) NOT NULL,
  `hashtag_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`hashtag_id`),
  KEY `hashtag_id` (`hashtag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_hashtags`
--

LOCK TABLES `user_hashtags` WRITE;
/*!40000 ALTER TABLE `user_hashtags` DISABLE KEYS */;
INSERT INTO `user_hashtags` VALUES (0,3),(0,7);
/*!40000 ALTER TABLE `user_hashtags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_languages`
--

DROP TABLE IF EXISTS `user_languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_languages` (
  `user_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`language_id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_languages`
--

LOCK TABLES `user_languages` WRITE;
/*!40000 ALTER TABLE `user_languages` DISABLE KEYS */;
INSERT INTO `user_languages` VALUES (0,1),(0,2);
/*!40000 ALTER TABLE `user_languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `profile_pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (8,'selmagossetpetelet@gmail.com','$2y$10$EDydjHk9THmpXNoNfoH9b.DySVHfSdEnWRg7hVeeuTljQ6yrK.ecG','bezjtb lzti\'r','selma','','Paris','FR',NULL,NULL,'img/profiles/avatar_8.png','img/banners/banner_8.png','2026-03-26 15:24:38'),(9,'selmagelet@gmail.com','$2y$10$jAqjx/5lG9wCmC0O5JW9O.0SzZmq7IaLLjLKNGh.dgW0hY8VFTmN.','Selma Gosset--Petelet','jtyjnthy','','Paris','FR',NULL,NULL,'default_user.jpg','default_banner.jpg','2026-03-26 15:25:02'),(10,'selmagosetelet@gmail.com','$2y$10$u8f5juKLDO7CZrBrxvo0E.EJ6fhEdLv24zbWlQIjmkS1atCkcecQ.','Selma Gosset--Petelet','aaaaa','','Paris','FR',NULL,NULL,'default_user.jpg','default_banner.jpg','2026-03-26 15:36:34'),(11,'rihab.doukkali@gmail.com','$2y$10$sm1DEFSlO8/4TMADh7zp8.Kkb.KIaPLacrEwVNFOUlYzdlWQGMEFe','Rihab','Rihab','hey','Bordeaux','France',NULL,NULL,'img/profiles/avatar_11.png','img/banners/banner_11.png','2026-04-01 10:59:04'),(12,'clara@linkup.fr','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Clara Bass','clara_slap','Bassiste funk. Le groove avant tout. 🎸✨','Marseille','FR',43.29650000,5.36980000,'img/ppWoman.png',NULL,'2026-04-01 11:00:15'),(13,'bjorn@linkup.fr','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Björn Ironside','viking_metal','Batteur de death metal. J\'ai ma propre double pédale. 🥁','Stockholm','SE',59.32930000,18.06860000,'img/ppMan.png',NULL,'2026-04-01 11:00:15'),(14,'miguel@linkup.fr','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Miguel Guitar','flamenco_king','Guitare flamenca et acoustique. 💃','Madrid','ES',40.41680000,-3.70380000,'img/ppMan_second.png',NULL,'2026-04-01 11:00:15'),(15,'yuki@linkup.fr','$2y$10$92IXUNpk$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Yuki Synth','analog_dream','Fan de synthétiseurs modulaires et de techno. 🎹','Tokyo','JP',NULL,NULL,'img/ppWoman_second.png',NULL,'2026-04-01 11:00:15'),(16,'leo@linkup.fr','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Leo Trumpet','leo_brass','Trompettiste jazz, disponible pour fanfares ou jams. 🎺','Bordeaux','FR',44.83780000,-0.57920000,'img/ppMan.png',NULL,'2026-04-01 11:00:15'),(17,'selelet@gmail.com','$2y$10$3r8PkQCDUiXypOhYf8R76OwNWF0UaBsE1RxddNN0pi9ncQWwJ1TIO','dfvdf','brthryt','rth','rthr','htyh',NULL,NULL,'img/profiles/avatar_17.png','img/banners/banner_17.png','2026-04-01 11:15:42'),(18,'fcnguyenhoangdat@gmail.com','','Hoàng Đạt Nguyễn','hoàng_Đạt_nguyễn_569',NULL,NULL,NULL,NULL,NULL,'https://lh3.googleusercontent.com/a/ACg8ocJOs6V8NM2cYk0ijaPy_1JvWFoHXOMafSNugFvXY1BiD_du-npk=s96-c','default_banner.jpg','2026-04-01 14:28:52'),(19,'anhoangah2005@gmail.com','$2y$10$UHorIs7IP8vn1NMRvs/wVOhaCFl287N.Uz5zfKsg1Z7OASQcAJ7ca','An Hoang','anhoang','pop','Ha noi','Vietnam',NULL,NULL,'img/profiles/avatar_19.jpg','default_banner.jpg','2026-04-01 14:35:07'),(20,'medoubenouda@gmail.com','$2y$10$rJ5JrA18mBFcxb6Dbuqjf.jtmiR.QKz2kMRtsKQPFF28n/js53pFe','medou','gggg','gggggggggggggggg','VILLEMOMBLE','France',NULL,NULL,'1775054127.png','default_banner.jpg','2026-04-01 14:35:27'),(21,'med@gmail.com','$2y$10$i85Pe9cDf.xvqt5r0yU/g.JwBV0nR9ecnADoMMS9nkZOocX1bE8KC','koko','kikiki','ssssssssd ddddddddd ffffff','Paris','France',NULL,NULL,'1775054209.png','default_banner.jpg','2026-04-01 14:36:49'),(22,'s_doukkali@stu-esg.fr','$2y$10$UZCvvX8MoQsI6PlGk.G/wOmd2t1E3uZlVgrIpLpVt.IHZKsiOWe.u','ryt','rtyu','ezy','uyi','France',NULL,NULL,'img/profiles/1775057238.png','img/profiles/default_banner.jpg','2026-04-01 15:27:18'),(23,'rihab.doukkalkpi@gmail.com','$2y$10$jDuIdPcEXsK6LFsOp6YV9.SfasFcQRLzRiARGouKaXjQdHKbecUt.','tjrs','ety','f tu','maroc','France',NULL,NULL,'img/photo_defaut.png','img/banniere_defaut.png','2026-04-01 15:35:12');
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

-- Dump completed on 2026-04-02  9:54:24
