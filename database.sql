-- MySQL dump 10.16  Distrib 10.1.48-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: 
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
-- Current Database: `linkup`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `linkup` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `linkup`;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,1,6,'Cool !!','2026-04-02 22:49:49');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,6,'Ma Répétition Solo','Session intensive sur les nouveaux morceaux PHP/JS.','2026-04-05 14:00:00','2026-04-05 18:00:00','private'),(2,1,'Jam Session Jazz','Ramenez vos instruments au Caveau des Oubliés, on improvise toute la soirée !','2026-04-10 19:00:00','2026-04-10 23:00:00','public'),(3,15,'Workshop Metal Shred','Apprendre les techniques de sweep picking à la Rock School.','2026-04-12 10:00:00','2026-04-12 12:00:00','public'),(4,12,'Synth Modulaire Meeting','Présentation de mon rack de Tirana en visio live.','2026-04-15 20:00:00','2026-04-15 21:30:00','shared'),(5,11,'Apéro Musiciens','Réseautage et discussion matos au Lieu Unique.','2026-04-20 18:30:00','2026-04-20 22:00:00','public'),(6,6,'Cours piano','','2026-04-24 22:00:00','2026-04-24 23:00:00','private');
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
INSERT INTO `follows` VALUES (1,6,'pending'),(3,6,'pending'),(6,1,'pending'),(6,2,'pending'),(6,3,'pending'),(6,4,'pending'),(6,5,'pending'),(6,11,'pending'),(6,12,'pending'),(6,13,'pending'),(6,16,'pending'),(6,17,'pending'),(6,18,'pending'),(11,6,'pending'),(12,6,'pending'),(15,6,'pending'),(18,6,'pending'),(18,11,'pending'),(18,16,'pending'),(18,17,'pending');
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,3,1,'Salut Léna ! Chaud pour poser un sax sur mon prochain track techno ?',0,'2026-04-02 22:22:10'),(2,1,3,'Wsh Enzo ! Grave chaud, envoie-moi le BPM !',0,'2026-04-02 22:27:10'),(3,1,6,'Wsh ! J’ai vu ton profil, t’es chaud pour une jam session ce week-end ?',0,'2026-04-02 21:45:11'),(4,6,1,'Grave ! Tu ramènes ton sax ?',0,'2026-04-02 21:55:11'),(5,1,6,'Bien sûr, on se capte au studio à 14h ! 🎷',0,'2026-04-02 22:00:11'),(6,15,6,'Yo, j’ai besoin d’un avis sur mon dernier mix, je t’envoie ça ?',0,'2026-04-02 20:45:11'),(7,12,6,'Pershendetje! (Salut !) Je cherche des samples de percussions françaises, t’as ça ?',0,'2026-04-01 22:45:12'),(8,6,18,'salut',0,'2026-04-03 07:12:37');
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
  `type` enum('follow','mention','event','message','like','comment') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,6,'follow',1,'Léna Jazzy a commencé à vous suivre. 🎷',0,'2026-04-02 22:46:54'),(2,6,'like',3,'Enzo VST a aimé votre dernier post sur le mixage. ❤️',0,'2026-04-02 22:36:54'),(3,6,'message',15,'Kevin Metal vous a envoyé une question sur votre setup. 🤘',0,'2026-04-02 22:16:54'),(4,6,'event',11,'Julien Rythme vous invite à l’événement \"Apéro Musiciens\". 🍻',0,'2026-04-02 20:46:54'),(5,6,'mention',12,'Sarah Synth vous a mentionné dans un commentaire ! 🇦🇱',0,'2026-04-01 22:46:54'),(6,1,'comment',6,'Cool !!',0,'2026-04-02 22:49:49'),(7,1,'like',6,NULL,0,'2026-04-02 22:49:51'),(8,5,'like',6,NULL,0,'2026-04-02 22:49:54'),(9,16,'follow',6,NULL,0,'2026-04-02 22:50:19'),(10,17,'follow',6,NULL,0,'2026-04-02 22:50:20'),(11,17,'follow',18,NULL,0,'2026-04-03 00:52:51'),(12,16,'follow',18,NULL,0,'2026-04-03 00:52:52'),(13,11,'follow',18,NULL,0,'2026-04-03 00:52:52'),(14,16,'follow',18,NULL,0,'2026-04-03 00:52:54'),(15,6,'follow',18,NULL,0,'2026-04-03 00:53:06'),(16,6,'like',18,NULL,0,'2026-04-03 00:56:23'),(17,18,'follow',6,NULL,0,'2026-04-03 06:45:44'),(18,6,'like',19,NULL,0,'2026-04-03 07:34:48');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_likes`
--

LOCK TABLES `post_likes` WRITE;
/*!40000 ALTER TABLE `post_likes` DISABLE KEYS */;
INSERT INTO `post_likes` VALUES (1,1,6,'2026-04-02 22:49:51'),(2,4,6,'2026-04-02 22:49:54'),(3,23,18,'2026-04-03 00:56:23'),(4,23,19,'2026-04-03 07:34:48');
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_media`
--

LOCK TABLES `post_media` WRITE;
/*!40000 ALTER TABLE `post_media` DISABLE KEYS */;
INSERT INTO `post_media` VALUES (1,1,'img/posts/post_saxo.jpg','image'),(2,2,'img/posts/post_studio.jpg','image'),(3,3,'img/posts/post_vinyl.jpg','image'),(4,4,'img/posts/post_concert.jpg','image'),(9,23,'img/posts/post_23_1775172665.webp','image');
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,1,NULL,'Réception de mon nouveau bec de Sax ! Le son est dingue 🎷','standard',NULL,'2026-04-02 22:38:08'),(2,3,NULL,'Petit setup du soir. On bosse sur un nouveau kick techno. 🔊','standard',NULL,'2026-03-30 22:38:08'),(3,2,NULL,'Ma collection de vinyles s agrandit. Rien ne vaut le grain de l analogique.','standard',NULL,'2026-04-01 22:38:08'),(4,5,NULL,'En pleine répétition pour le concert de samedi ! 🎸 #Rock #Live','standard',NULL,'2026-04-02 20:38:08'),(5,6,NULL,'Question aux batteurs : vous utilisez quoi comme application de métronome pour travailler vos polyrythmies ? J en cherche une simple mais efficace.','standard',NULL,'2026-04-02 22:36:08'),(6,7,NULL,'Est-ce que quelqu un ici a déjà testé le Korg Minilogue XD ? J hésite vraiment à l acheter pour mon prochain setup live.','standard',NULL,'2026-04-02 22:36:08'),(7,8,NULL,'Je viens de finir mon premier EP de 4 titres ! C est très lo-fi mais j y ai mis tout mon coeur. Je vous envoie le lien bientôt pour avoir vos retours.','standard',NULL,'2026-04-02 22:36:08'),(8,9,NULL,'Besoin d aide : je n arrive pas à accorder ma guitare en Open D sans que la corde de Sol ne frise... Une astuce de luthier ?','standard',NULL,'2026-04-02 22:36:08'),(9,10,NULL,'Cherche désespérément un local de répète sur Strasbourg pour juillet. Tout est complet partout, c est abusé ! Si vous avez un plan, MP moi.','standard',NULL,'2026-04-02 22:36:08'),(10,11,NULL,'Question aux batteurs : vous utilisez quoi comme application de métronome pour travailler vos polyrythmies ? J en cherche une simple mais efficace.','standard',NULL,'2026-04-02 17:38:08'),(11,12,NULL,'Est-ce que quelqu un ici a déjà testé le Korg Minilogue XD ? J hésite vraiment à l acheter pour mon prochain setup live.','standard',NULL,'2026-04-02 12:38:08'),(12,13,NULL,'Je viens de finir mon premier EP de 4 titres ! C est très lo-fi mais j y ai mis tout mon coeur. Je vous envoie le lien bientôt pour avoir vos retours.','standard',NULL,'2026-04-02 10:38:08'),(13,14,NULL,'Besoin d aide : je n arrive pas à accorder ma guitare en Open D sans que la corde de Sol ne frise... Une astuce de luthier ?','standard',NULL,'2026-04-02 07:38:08'),(14,15,NULL,'Cherche désespérément un local de répète sur Strasbourg pour juillet. Tout est complet partout, c est abusé ! Si vous avez un plan, MP moi.','standard',NULL,'2026-04-02 02:38:11'),(23,6,NULL,'Petit setup du soir... Je viens de recevoir mes nouvelles enceintes de monitoring (Yamaha HS7 pour les connaisseurs). Le son est d\'une clarté incroyable, je redécouvre mes propres morceaux ! 😍\r\n\r\nVous bossez avec quoi vous en ce moment ? Plutôt casque ou enceintes ?\r\n\r\n#StudioLife #SetupMusic #Yamaha #LinkUpNation','standard',NULL,'2026-04-02 23:31:05'),(24,18,NULL,'Je pense que jeune lion est étrangement agréable à écouter','standard',NULL,'2026-04-03 00:56:05');
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
  `languages` text COLLATE utf8mb4_unicode_ci,
  `profile_pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'lena@linkup.com','$2y$10$86XW.UeY69hXmZpYmE8vIu6q5O2rGfH1uS7tU8vV9wWxXyYzZaAbC','Léna Jazzy','lena_sax','Saxophoniste passionnée. Cherche groupe de Jazz Fusion. #Jazz #Sax','Paris','France',48.85660000,2.35220000,'French, English','img/profiles/avatar_1.jpg','img/banners/banner_1.jpg','2026-04-02 22:21:57'),(2,'robert@linkup.com','$2y$10$86XW.UeY69hXmZpYmE8vIu6q5O2rGfH1uS7tU8vV9wWxXyYzZaAbC','Robert Blues','blues_man_60','65 ans. Guitare vintage. Je partage mon expérience Blues. #Senior #Guitar','Rio de Janeiro','Brazil',-22.90680000,-43.17290000,'French','img/profiles/avatar_2.jpg','img/banners/banner_2.jpg','2026-04-02 22:21:57'),(3,'enzo@linkup.com','$2y$10$86XW.UeY69hXmZpYmE8vIu6q5O2rGfH1uS7tU8vV9wWxXyYzZaAbC','Enzo VST','digital_noise','Producteur MAO. Techno & Acid. Cherche chanteuse pour collab. #GenZ #Ableton','Tokyo','Japan',35.67620000,139.65030000,'French, English','img/profiles/avatar_3.jpg','img/banners/banner_3.jpg','2026-04-02 22:21:57'),(4,'maria@linkup.com','$2y$10$86XW.UeY69hXmZpYmE8vIu6q5O2rGfH1uS7tU8vV9wWxXyYzZaAbC','Maria Soprano','maria_opera','Chanteuse lyrique. Prof de chant. Adore l opéra italien.','Milan',NULL,45.46420000,9.19000000,'Italian, French','img/profiles/avatar_4.jpg','img/banners/banner_4.jpg','2026-04-02 22:21:57'),(5,'tom@linkup.com','$2y$10$86XW.UeY69hXmZpYmE8vIu6q5O2rGfH1uS7tU8vV9wWxXyYzZaAbC','Tom Indie','tom_fender','Guitare/Chant. Influences Radiohead. Dispo pour concerts bars.','Bordeaux',NULL,44.83780000,-0.57920000,'French, English','img/profiles/avatar_5.jpg','img/banners/banner_5.jpg','2026-04-02 22:21:57'),(6,'selmagossetpetelet@gmail.com','$2y$10$.sJlErazit9EOXAMUYFNCOHwAFe/Z.megALmYVtKI1JiRLMEXsctC','Selma','selma','Salut','','France',48.85349510,2.34839150,'','img/profiles/avatar_6_1775200553.jpg','img/banners/banner_6_1775200585.png','2026-04-02 22:24:24'),(11,'julien@linkup.com','$2y$10$86XW.UeY69hXmZpYmE8vIu6q5O2rGfH1uS7tU8vV9wWxXyYzZaAbC','Julien Rythme','juju_drums','Batteur autodidacte depuis 3 ans. Je galère un peu sur les mesures composées (5/4, 7/8). Quelqu un aurait des exercices ou des vidéos à me conseiller ? Je suis super motivé !','Lyon','France',45.76400000,4.83570000,'French','img/profiles/avatar_6.jpg','img/banners/banner_6.jpg','2026-04-02 22:36:52'),(12,'sarah@linkup.com','$2y$10$86XW.UeY69hXmZpYmE8vIu6q5O2rGfH1uS7tU8vV9wWxXyYzZaAbC','Sarah Synth','sarah_analog','Fan de synthétiseurs modulaires. Je passe trop de temps à patcher des câbles. Je cherche des gens pour discuter synthèse soustractive et FM. #Geek #Synth','Tirana','Albania',41.32750000,19.81870000,'French, English','img/profiles/avatar_7.jpg','img/banners/banner_7.jpg','2026-04-02 22:36:52'),(13,'yanis@linkup.com','$2y$10$86XW.UeY69hXmZpYmE8vIu6q5O2rGfH1uS7tU8vV9wWxXyYzZaAbC','Yanis Prod','yanis_beats','Beatmaker débutant sur FL Studio. J essaie de comprendre comment bien mixer mes kicks et mes basses pour que ça tape fort sans saturer. C est pas gagné haha !','Hanoi','Vietnam',21.02850000,105.85420000,'French','img/profiles/avatar_8.jpg','img/banners/banner_8.jpg','2026-04-02 22:36:52'),(14,'amandine@linkup.com','$2y$10$86XW.UeY69hXmZpYmE8vIu6q5O2rGfH1uS7tU8vV9wWxXyYzZaAbC','Amandine Folk','amandine_guitare','Je joue de la guitare acoustique et je chante un peu de tout. J aimerais bien trouver quelqu un qui joue de l harmonica ou du violon pour faire des duos acoustiques dans le 44.','Sydney','Australia',-33.86880000,151.20930000,'French, English','img/profiles/avatar_9.jpg','img/banners/banner_9.jpg','2026-04-02 22:36:52'),(15,'kevin@linkup.com','$2y$10$86XW.UeY69hXmZpYmE8vIu6q5O2rGfH1uS7tU8vV9wWxXyYzZaAbC','Kevin Metal','kevin_shred','Guitariste Metal / Djent. Je cherche un batteur qui a une double pédale et qui n a pas peur des BPM rapides (180+). No casuals please 🤘','New York','USA',40.71280000,-74.00600000,'French, German','img/profiles/avatar_10.jpg','img/banners/banner_10.jpg','2026-04-02 22:36:52'),(16,'rihab.doukkali@gmail.com','','Rihab Doukkali','rihab_doukkali_737',NULL,NULL,NULL,NULL,NULL,NULL,'https://lh3.googleusercontent.com/a/ACg8ocIKCKZEFDAS8-l66hwYExJdSBEdyJLCRP3sBRqWGxLZB9h9kQ=s96-c','img/banniere_defaut.png','2026-04-02 22:44:29'),(17,'rihab.doukkali06@gmail.com','$2y$10$FQz1.5SiSQhcWpTKn4oW9OwwCb4xS8ya0pRP4k1HjowBe5wKrOWeC','rihab','Rihab','','Paris, France','France',48.85349510,2.34839150,'','img/photo_defaut.png','img/banniere_defaut.png','2026-04-02 22:45:31'),(18,'toto@gmail.com','$2y$10$PJb7YVaf75lJRVdwrk08beXgg7GJOziI6A8ql37fxQi8GjaYTgDGm','Amine','AmineLeVré','J\'aime beaucoup le Jazz ainsi que Kaaris','','France',48.92298030,2.44552010,'Français,English','img/photo_defaut.png','img/banniere_defaut.png','2026-04-03 00:51:56'),(19,'rihab@gmail.com','$2y$10$5r5hHTCkJ6RAfH.tU5OtIePGzn1fJNhp5EBH.js.QJP/cTtczUMZC','rihabb','rihabb','','Bordeaux, France','France',44.84122500,-0.58003640,'Français','img/photo_defaut.png','img/banniere_defaut.png','2026-04-03 07:32:52');
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

-- Dump completed on 2026-04-03  9:35:07
