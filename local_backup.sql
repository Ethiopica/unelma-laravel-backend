/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for osx10.20 (arm64)
--
-- Host: localhost    Database: unelma-laravel-backend
-- ------------------------------------------------------
-- Server version	11.7.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `applicant_replies`
--

DROP TABLE IF EXISTS `applicant_replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `applicant_replies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `career_apply_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `sent_to_email` varchar(255) NOT NULL,
  `message` longtext NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `applicant_replies_career_apply_id_foreign` (`career_apply_id`),
  KEY `applicant_replies_user_id_foreign` (`user_id`),
  CONSTRAINT `applicant_replies_career_apply_id_foreign` FOREIGN KEY (`career_apply_id`) REFERENCES `career_applied` (`id`) ON DELETE CASCADE,
  CONSTRAINT `applicant_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `applicant_replies`
--

LOCK TABLES `applicant_replies` WRITE;
/*!40000 ALTER TABLE `applicant_replies` DISABLE KEYS */;
INSERT INTO `applicant_replies` VALUES
(1,19,1,'eliobais@gmail.com','Greetings Mr. Elias \r\n\r\nYour Application for the Job post Junior Full Stack developers position is Received, we will get back to you too soon.\r\n\r\nkind regards','2025-12-19 07:38:24','2025-12-19 07:38:24','2025-12-19 07:38:24'),
(2,16,1,'basudevpokharelfin@gmail.com','Greetings Basu \r\n\r\nWe have received your application for the Job post Junior Full Stack weB developers post and we will get back to you soon once the application period is closed. \r\n\r\nKind regards','2025-12-19 07:41:29','2025-12-19 07:41:29','2025-12-19 07:41:29');
/*!40000 ALTER TABLE `applicant_replies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_comments`
--

DROP TABLE IF EXISTS `blog_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blog_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blog_comments_blog_id_foreign` (`blog_id`),
  CONSTRAINT `blog_comments_blog_id_foreign` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_comments`
--

LOCK TABLES `blog_comments` WRITE;
/*!40000 ALTER TABLE `blog_comments` DISABLE KEYS */;
INSERT INTO `blog_comments` VALUES
(1,6,'eliobais@gmail.com','eliobais@gmail.com','fgf',1,'2025-11-19 08:26:28','2025-11-19 08:26:28'),
(2,6,'Elias','eliobais@gmail.com','Go ahead .....',1,'2025-11-19 10:20:24','2025-11-19 10:20:24');
/*!40000 ALTER TABLE `blog_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `featured_image_url` varchar(255) DEFAULT NULL,
  `author_id` bigint(20) unsigned NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `favorite_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blogs_slug_unique` (`slug`),
  KEY `blogs_author_id_foreign` (`author_id`),
  CONSTRAINT `blogs_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES
(1,'Hello','h','whdhsdgsd','dhsdjq','blogs/efktZcL9TyqwOXVeRrfKPEGxA1M1UwI6KhKQr8Ji.jpg',NULL,1,'sws','[\"ss\"]',1,'2025-10-31 10:06:05',0,0,'sd',NULL,NULL,0,'2025-10-31 10:06:05','2025-11-26 07:14:52'),
(2,'React Conf 2025 Recap','https://react.dev/blog/2025/10/16/react-conf-2025-recap',NULL,'Jorge Cohen and Nicola Corti kicked off day 2 highlighting React Native’s incredible growth with 4M weekly downloads (100% growth YoY), and some notable app migrations from Shopify, Zalando, and HelloFresh, award-winning apps like RISE, RUNNA, and Partyful, and AI apps from Mistral, Replit, and v0.','blogs/Klu2Zt4uU0ZRTMvFyY3gOr9T7ySzlubeVMgcoJUl.png',NULL,1,NULL,NULL,1,'2025-10-31 10:58:58',0,1,NULL,NULL,NULL,0,'2025-10-31 10:58:58','2025-12-16 06:21:40'),
(3,'ffff','ffff',NULL,'fff','blogs/RGZLbVOcjvptsbajPe5TcS0l5WNyirj2YZlwC8cx.webp',NULL,1,NULL,NULL,1,'2025-10-31 11:06:45',0,1,NULL,NULL,NULL,0,'2025-10-31 11:02:54','2025-12-16 06:21:34'),
(4,'[NEW] Laravel Modules and DDD','https://laraveldaily.com/course/laravel-modules-ddd',NULL,'How to structure LARGER Laravel projects? This is one of the common questions from the community.\r\n\r\nThere are two common ways, discussed in this course:\r\n\r\nSeparate code into Modules, so that devs/teams could potentially work on separate parts, independently\r\nDomain-Driven Design (DDD), so that business logic would be separated from the implementation\r\nIn this 2-hour video+text course, I will show/compare both, so you would decide whether you need Modules/DDD for your next Laravel project.\r\n\r\nFor Modules, we will discuss three different ways to modularize a Laravel project:\r\n\r\nnwidart/laravel-modules package\r\ninternachi/modular package\r\nno 3rd-party package at all\r\nFor DDD, we will try to re-create the same Modular project from the first section, with Domain-Driven approach.\r\n\r\nBut, we need to start with planning. So, let\'s dive into the first lesson?','blogs/hgo57eQUV5dX7d40Mcm2wy0ZypBUqxLp4cyQ2Xa0.png',NULL,1,'Tech','[\"Back-end\"]',1,'2025-11-04 19:28:03',0,1,NULL,NULL,NULL,0,'2025-11-04 19:16:45','2025-12-16 06:21:30'),
(5,'Test 2','test-2',NULL,'latest blog will be here','blogs/zCKPB1nX4023WHoiU8ONbvrzN8NQ1GOgBOUtZfzY.jpg',NULL,1,NULL,'[]',1,'2025-11-17 08:32:59',0,1,NULL,NULL,NULL,0,'2025-11-17 08:11:32','2025-12-12 06:56:17'),
(6,'yhjhjkjhkn','yhjhjkjhkn',NULL,'v nb nm n m,j hgvn','blogs/5nJMK8XfSNwYWhZZq2PGTCESy0Nm4Nrg3c4Q7hKc.jpg',NULL,1,NULL,'[]',1,'2025-11-17 08:32:54',0,1,NULL,NULL,NULL,0,'2025-11-17 08:16:03','2025-12-18 10:08:17'),
(7,'fhgjgjhgjkj','fhgjgjhgjkj',NULL,'hgfhfhgjhgjh','blogs/uRSDINqV8vcBvt98EKhOUEnLRlsbkSd3xczOoDDe.webp',NULL,1,NULL,'[]',1,'2025-11-17 08:32:48',0,0,NULL,NULL,NULL,0,'2025-11-17 08:32:40','2025-12-18 13:03:29'),
(8,'Greta Ethiopian Run','greta-ethiopian-run',NULL,'Created by Haike G/silassie','blogs/N4FpJttkmcQ70N0Mn87yccpJOLorxrkVI5Eu6EGn.jpg',NULL,1,NULL,NULL,1,'2025-11-26 07:22:07',0,1,NULL,NULL,NULL,0,'2025-11-26 07:22:07','2025-12-08 15:13:40'),
(9,'fgfgd','fgfgd',NULL,'fgfdg','blogs/eQx07PuGpT69E12Db8nBnRR9i9H4SaI5WiLLX8Cy.jpg',NULL,1,NULL,NULL,1,'2025-11-26 07:53:15',0,1,NULL,NULL,NULL,0,'2025-11-26 07:53:15','2025-12-16 06:21:37'),
(10,'Mariam','mariam',NULL,'Enkuwan Aderesachu','blogs/6AlmZqayEge9e9vRnUAwQvNejTWJhwTR7lghInZm.jpg',NULL,1,NULL,NULL,1,'2025-12-12 06:54:20',0,2,NULL,NULL,NULL,0,'2025-12-12 06:54:20','2025-12-16 13:58:26'),
(11,'EOTC','eotc',NULL,'ስብሐት ለአብ ስብሐት ለወልድ ስብሐት ለመንፈስ ቅዱስ (፫ ጊዜ በል) ይደልዎሙ ለአብ ወወልድ ወመንፈስ ቅዱስ \r\nስብሐት ለእግዝእትነ ማርያም ድንግል ወላዲተ አምላክ፡ \r\nይደልዋ ለእግዝእትነ ማርያም ድንግል ወላዲተ አምላክ \r\nስብሐት ለመስቀለ እግዚእነ ኢየሱስ ክርስቶስ \r\nይደልዎ ለመስቀለ እግዚእነ ኢየሱስ ክርስቶስ \r\nክርስቶስ በምሕረቱ ይዘከረነ። \r\nአሜን \r\nአመ ዳግም ምጽአቱ ኢያስተኅፍረነ፡፡ \r\nአሜን \r\nለሰብሖተ ስሙ ያንቅሀነ\r\n አሜን\r\n ወበአምልኮቱ ያጽንአነ \r\nአሜን \r\nእግዝእትነ ማርያም አዕርጊ ጸሎተነ፣\r\nአሜን \r\nወአስተሥርዪ ኲሎ ኃጢአተነ\r\nአሜን \r\nቅድመ መንበሩ ለእግዚእነ \r\nአሜን \r\nለዘአብልዐነ ዘንተ ኅብስተ \r\nእስመ ለዓለም ምሕረቱ \r\nወለዘአስተየነ ዘንተ ጽዋዓ \r\nእስመ ለዓለም ምሕረቱ \r\nወለዘሠርዐ ለነ ሲሳየነ ወአራዘነ \r\nእስመ ለዓለም ምሕረቱ \r\nወለዘተዐገሠ ለነ ኵሎ ኃጢአተነ \r\nእስመ ለዓለም ምሕረቱ \r\nወለዘወሀበነ ሥጋሁ ቅዱሰ ወደሞ ክቡረ \r\nእስመ ለዓለም ምሕረቱ \r\nወለዘአብጽሐነ እስከ ዛቲ ሰዓት። \r\nእስመ ለዓለም ምሕረቱ\r\n\r\nነሀብ ሎቱ ስብሐተ ወአኰቴተ ለእግዚአብሔር ልዑል ወለወላዲቱ ድንግል፡፡ ወለመስቀሉ ክቡር ይትአኰት ወይሰባሕ ስሙ ለእግዚአብሔር ወትረ በኵሉ ጊዜ ወበኵሉ ሰዓት።','blogs/vYpO9VYpTycHXPSGkav46TdRZei2JH7LOir0Hh4U.png',NULL,1,NULL,'[]',1,'2025-12-18 11:37:47',0,1,NULL,NULL,NULL,0,'2025-12-16 14:14:40','2025-12-18 11:37:47');
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `career_applied`
--

DROP TABLE IF EXISTS `career_applied`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `career_applied` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `career_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `CV` varchar(255) NOT NULL,
  `cover_letter` varchar(255) DEFAULT NULL,
  `cover_text` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `career_applied_career_id_foreign` (`career_id`),
  KEY `career_applied_user_id_foreign` (`user_id`),
  CONSTRAINT `career_applied_career_id_foreign` FOREIGN KEY (`career_id`) REFERENCES `careers` (`id`),
  CONSTRAINT `career_applied_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `career_applied`
--

LOCK TABLES `career_applied` WRITE;
/*!40000 ALTER TABLE `career_applied` DISABLE KEYS */;
INSERT INTO `career_applied` VALUES
(3,'eliobais@gmail.com','eliobais@gmail.com',1,NULL,'jobs_applied/Elias CV2.pdf',NULL,'hvjdhjhdkhbkdkb','2025-12-17 06:32:12','2025-12-17 06:32:12'),
(4,'eliobais@gmail.com','eliobais@gmail.com',1,NULL,'jobs_applied/Elias CV2.pdf',NULL,'hvjdhjhdkhbkdkb','2025-12-17 06:32:26','2025-12-17 06:32:26'),
(5,'eliobais@gmail.com','eliobais@gmail.com',1,NULL,'jobs_applied/Elias CV2.pdf',NULL,'jvksldjkjvöos.','2025-12-17 06:50:59','2025-12-17 06:50:59'),
(6,'eliobais@gmail.com','eliobais@gmail.com',1,NULL,'jobs_applied/Elias CV2.pdf',NULL,'gghjgjgj','2025-12-17 07:19:40','2025-12-17 07:19:40'),
(7,'Selam','gitelias28@gmail.com',2,NULL,'jobs_applied/Elias CV2.pdf',NULL,'jjfdfkgjkd','2025-12-17 07:28:07','2025-12-17 07:28:07'),
(8,'Selam','gitelias28@gmail.com',2,NULL,'jobs_applied/Elias CV2.pdf',NULL,'jjfdfkgjkd','2025-12-17 07:28:53','2025-12-17 07:28:53'),
(9,'eliobais@gmail.com','eliobais@gmail.com',2,NULL,'jobs_applied/Elias CV2.pdf',NULL,'bvbfbg','2025-12-17 17:43:53','2025-12-17 17:43:53'),
(10,'eliobais@gmail.com','eliobais@gmail.com',2,NULL,'jobs_applied/Elias CV2.pdf',NULL,'hi','2025-12-17 18:12:06','2025-12-17 18:12:06'),
(11,'Elias Bekele Tekle','eliobais@gmail.com',1,NULL,'jobs_applied/Elias CV2.pdf',NULL,'Hi Hello','2025-12-18 09:36:48','2025-12-18 09:36:48'),
(12,'Kerlos Eshete','gitelias28@gmail.com',6,NULL,'jobs_applied/Elias CV2.pdf',NULL,'I am excited to apply for the Junior Laravel Backend Developer position at [Company Name]. With hands-on experience in Laravel, PHP, and MySQL, I have built small web applications and APIs that are clean, efficient, and scalable. I am eager to contribute my skills to your team, learn from experienced developers, and help deliver high-quality backend solutions.  I am confident that my dedication, quick learning ability, and passion for web development make me a strong fit for this role. I would welcome the opportunity to discuss how I can contribute to [Company Name].  Thank you for your time and consideration.  Sincerely,','2025-12-18 10:06:22','2025-12-18 10:06:22'),
(13,'Elias Bekele Tekle','eliobais@gmail.com',1,NULL,'jobs_applied/Elias CV2.pdf',NULL,'Hi Hello','2025-12-18 11:50:47','2025-12-18 11:50:47'),
(14,'Elias Bekele Tekle','eliobais@gmail.com',7,NULL,'jobs_applied/Elias CV2.pdf',NULL,'/private/var/tmp/php69urt4lciabkdjEIyzW','2025-12-18 12:30:04','2025-12-18 12:30:04'),
(15,'Elias Bekele Tekle','eliobais@gmail.com',2,NULL,'applications/cvs/1766069698_Elias_CV2.pdf',NULL,'applications/cover_letters/1766069698_cover_eliobais_gmail.com_CV.pdf','2025-12-18 12:54:58','2025-12-18 12:54:58'),
(16,'Basu','basudevpokharelfin@gmail.com',4,NULL,'applications/cvs/1766076531_Elias_CV2.pdf',NULL,'applications/cover_letters/1766076531_cover_Elias_CV2.pdf','2025-12-18 14:48:51','2025-12-18 14:48:51'),
(17,'Elias Bekele Tekle','eliobais@gmail.com',1,NULL,'jobs_applied/Elias CV2.pdf',NULL,'/private/var/tmp/phpj6u2rgc2bggfbCgVmrj','2025-12-19 07:11:24','2025-12-19 07:11:24'),
(18,'Elias Bekele Tekle','eliobais@gmail.com',1,NULL,'jobs_applied/Elias Bekele Tekle_CoverLetter.pdf',NULL,'/private/var/tmp/phpuoh71jffepambICYUW3','2025-12-19 07:21:12','2025-12-19 07:21:12'),
(19,'Elias Bekele','eliobais@gmail.com',4,NULL,'jobs_applied/eliobais@gmail.com_CV.pdf',NULL,'/private/var/tmp/phpm6nc6sqjvo455NvqI0T','2025-12-19 07:28:27','2025-12-19 07:28:27');
/*!40000 ALTER TABLE `career_applied` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `careers`
--

DROP TABLE IF EXISTS `careers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `careers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `careers`
--

LOCK TABLES `careers` WRITE;
/*!40000 ALTER TABLE `careers` DISABLE KEYS */;
INSERT INTO `careers` VALUES
(1,'Cyber Security Specialist','Accenture Cybersecurity - meillä työpäivät eivät ole koskaan samanlaisia ja juuri siksi viihdymme täällä! Tarjoamme mahdollisuuden rakentaa palkitseva ura ja kukoistaa rennon rohkeassa porukassa, lahjakkaiden ja menestystäsi tukevien kollegoiden kanssa. Tuemme yksilöllistä osaamistasi mukaansa tempaavalla työyhteisöllä, jossa jokainen saa olla oma itsensä. Accenturella pääset työskentelemään asiakasprojekteissa maailman johtavissa organisaatioissa.\r\n\r\nHaemme joukkoomme erilaisilla taustoilla olevia tieto-/kyberturvaosaajia\r\n\r\nEtsimme niin kokeneita kuin uransa alkuvaiheessa olevia tietoturva-asiantuntijoita - henkilöitä, jotka haluavat kehittyä alansa huippuammattilaisiksi. Tietoturvakonsultoinnin roolissa työskentelet osana kokenutta paikallista tiimiä, jota tukee maailman johtavan kyberturvallisuuskonsultoinnin globaali asiantuntemus ja verkostot.\r\nTarjoamme merkityksellisiä työtehtäviä, kilpailukykyisen palkan ja työsuhde-etuja. Etsimme henkilöitä, joilla on kaupallinen, teknologia-, IT- tai turvallisuustausta ja muuten soveltuvuutta rooliin sekä ymmärrystä kyberturvallisuuden monipuolisesta alasta.\r\nOlipa taustasi hallinnollisessa turvallisuudessa, arkkitehtuurissa, infrastruktuurissa, kyberpuolustuksessa tai sovellusturvallisuudessa, hae rohkeasti mukaan kasvavan tiimimme monipuolisiin tehtäviin!\r\n\r\nSuuntautumisvaihtoehtoja:\r\nTaustastasi riippuen sinulla on mahdollisuus suuntautua erilaisille urapoluille ja auttaa asiakkaitamme esimerkiksi:\r\nHallinnollisen tietoturvan ja kyberturvallisuusstrategian tehtävissä; Turvallisuusvaatimusten analysointi ja noudattaminen, mukaan lukien tekniset ja sääntelyyn perustuvat vaatimukset. Organisaatioiden nykytilan ja tavoitetilan määrittely. Organisaation riskienhallinta, tietosuoja tai koulutus. Teknologiatrendien, tuotteiden ja palveluiden ymmärtäminen sekä mahdollisten hyötyjen analysointi.\r\nTietoturva-arkkitehtuuriin liittyvissä tehtävissä; Tietoturvan varmistaminen laajoissa teknologiahankkeissa ja osana laajempaa IT/OT arkkitehtuuria. Turvallisuusratkaisujen, nykyisten prosessien tai arkkitehtonisten ratkaisujen arviointi. Pilven ja on-prem -ympäristöjen tietoturva.\r\nKyberturvaan liittyvissä teknisissä tehtävissä; Tietoturvatestaus (SAST, DAST, Pentest). Tietoturvavalvonta ja vastaaminen (SOC, DFIR, Threat Intel). Offensiiviset tietoturvapalvelut (RedTeaming, Purple Teaming, Cyber Attack Simulation), Käyttäjä- ja pääsynhallinta (IGA, PAM).',NULL,'2025-12-15 09:06:49','2025-12-15 09:06:49'),
(2,'CNC','Job Requirements\r\nEducation:Vocational school\r\nWork experience:6 months\r\nLanguage skills:Finnish\r\nJob Summary\r\nContract Type:Full time, Temporary\r\nHaluatko olla ensimmäisten joukossa löytämässä kesän 2026 myyntityön operaattorialalla? Hae jo nyt!\r\n\r\n\r\nResponsibilities\r\nHaemme operaattorialalle asiakasyrityksillemme vuorovaikutuksen mestareita, tuloksiin tähtääviä tekijöitä ja kehittymishaluisia persoonia myyntirooleihin kesälle 2026!\r\n\r\nKesätyöt sijoittuvat operaattorialalla vaihdellen huhtikuun alusta, syyskuun loppuun. Kerrothan hakemuksella käytettävyydestäsi lisää.\r\n\r\n\r\n\r\nMyynnin parista löytyy usein kolmea erilaista roolia, valitse hakemuksella itsellesi kiinnostavin.\r\n\r\n\r\n\r\nMitä tehtäviä haussa?:\r\n\r\n🛍️ Myymälämyyjä\r\n\r\n🎪 Liikkuva myyntineuvottelija (promootio ja tapahtumat)\r\n\r\n📞 Puhelinmyyntineuvottelija\r\n\r\n\r\n\r\nKenelle myynti sopii?\r\n\r\nSinulle, joka olet luonteva vuorovaikuttaja, kaipaat työltäsi tavoitteellisuutta ja haluat nähdä tuloksia. Haluat kasvaa ja kehittyä, olet oma-aloitteinen ja ymmärrät tiimityön merkityksen.\r\n\r\nAikaisempaa kokemusta ei tarvita – asenne ratkaisee!\r\n\r\n\r\n\r\n🚀 Mitä tarjoamme?\r\n\r\n· Kesätyöpaikan jatkuvasti kehittyvällä operaattorialalla\r\n\r\n· Kattavan perehdytyksen ja jatkuvan tuen kehittymiseen\r\n\r\n· Kilpailukykyisen palkkamallin – pohjapalkka + provisiot ja mahdolliset bonukset (ICT-alan TES takaa jokaiseen rooliin vähimmäispalkan)\r\n\r\n· Vaihtelevat työajat ja mahdollisuuden vaikuttaa omaan arkeen\r\n\r\n· Työyhteisöjä, jossa sparraus, kasvu ja hyvä fiilis ovat osa arkea\r\n\r\n· Mainiot työsuhde-edut\r\n\r\n\r\n\r\nKuinka prosessi etenee:\r\n\r\n1. Jätä hakemus – yksi lomake, monta erilaista roolia\r\n\r\n2. Rekrytoijamme ovat sinuun yhteydessä tammikuun aikana\r\n\r\n3. Mahdollinen jatkohaastattelu työllistävän yrityksen kanssa\r\n\r\n4. Kesäduuni taskussa – let’s go!\r\n\r\n\r\n\r\nNäin jätät hakemuksen\r\n\r\n· Kirjaudu sisään tai luo uusi profiili.\r\n\r\n· Täydennä työnhakuprofiilisi\r\n\r\n· Lisää CV profiiliisi\r\n\r\n· Vastaa hakemuksen kysymyksiin ja lähetä hakemus.\r\n\r\n\r\n\r\nHuom: Saat sähköpostiisi vahvistusviestin, kun hakemuksesi on vastaanotettu.\r\n\r\nMiksi valita Barona?\r\n\r\nBarona tarjoaa sinulle vakaan työsuhteen, monipuoliset uramahdollisuudet ja kattavat edut.\r\n\r\nJos haluat kesätyön, jossa pääset kehittymään, kohtaamaan ihmisiä ja näkemään tuloksia – tämä on sinun mahdollisuutesi','Helsinki','2025-12-17 07:27:37','2025-12-17 07:27:37'),
(4,'Junior Full Stack Web Developer','Description:\r\nAs a Junior Full Stack Web Developer, you will work closely with senior developers to build and maintain responsive web applications. You’ll contribute to both frontend and backend features, fix bugs, and learn best practices in modern web development.\r\n\r\nKey Responsibilities:\r\n\r\nBuild UI components using React\r\n\r\nDevelop REST APIs using Node.js and Express\r\n\r\nWork with MongoDB and SQL databases\r\n\r\nCollaborate using Git and Agile workflows','Helsinki','2025-12-18 10:01:48','2025-12-18 10:01:48'),
(5,'Full Stack Web Engineer (E-commerce)','Description:\r\nJoin ShopSphere to help build high-performance e-commerce platforms. You’ll work on user-facing features, payment integrations, and backend systems that handle thousands of daily transactions.\r\n\r\nKey Responsibilities:\r\n\r\nBuild shopping and checkout flows\r\n\r\nDevelop APIs for product and order management\r\n\r\nWork with PostgreSQL and Redis\r\n\r\nOptimize application performance and scalability','Helsinki','2025-12-18 10:02:49','2025-12-18 10:02:49'),
(6,'Junior Laravel Backend Developer','Description:\r\nWe are looking for a Junior Laravel Backend Developer to support the development of backend services for modern web applications. You’ll work under senior developers and gain hands-on experience with Laravel best practices.\r\n\r\nKey Responsibilities:\r\n\r\nDevelop and maintain RESTful APIs using Laravel\r\n\r\nWork with MySQL databases and Eloquent ORM\r\n\r\nWrite clean, testable PHP code\r\n\r\nFix bugs and improve existing backend logic','Helsinki','2025-12-18 10:04:04','2025-12-18 10:04:04'),
(7,'Laravel Backend Engineer (SaaS Platform)','Description:\r\nJoin CloudNest to help develop a growing SaaS platform. As a Laravel Backend Engineer, you’ll work on subscription systems, user management, and scalable backend architecture.\r\n\r\nKey Responsibilities:\r\n\r\nBuild backend features for SaaS applications\r\n\r\nDesign database schemas and migrations\r\n\r\nImplement background jobs and queues\r\n\r\nEnsure application security and data integrity','Helsinki','2025-12-18 10:04:41','2025-12-18 10:04:41');
/*!40000 ALTER TABLE `careers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carrers`
--

DROP TABLE IF EXISTS `carrers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `carrers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrers`
--

LOCK TABLES `carrers` WRITE;
/*!40000 ALTER TABLE `carrers` DISABLE KEYS */;
INSERT INTO `carrers` VALUES
(2,'Full Stack web Developer','Yhdessä viisaasti tehty\r\n\"Jos sulla on joku ongelma, niin aina löytyy joku joka auttaa.\"\r\n\r\nTervetuloa Netumille – viisaaseen ja vaikuttavaan IT-taloon. Meillä pääset osaksi ammattilaisten viihtyisää työyhteisöä, jossa tekemisen ytimessä ovat yhteistyö, luottamus ja jatkuva oppiminen. Netumilla työskentely on rentoa ja joustavaa. Tuemme työn ja elämän tasapainoa ja annamme tilaa kasvaa omannäköiseksi asiantuntijaksi.\r\n\r\nCodeMatch -rekrytointipalvelun kautta työllistämme junior- ja middletason kehittäjiä sekä automaatiotestaajia suoraan asiakasprojekteihimme. CodeMatchin tavoitteena on rekrytoitua asiakasyritykseen. Tutustu CodeMatch -rekrytointipolkuun ja löydä oma reittisi.\r\n\r\nLue lisää meistä ja jätä hakemus!','2025-11-13 10:05:49','2025-11-13 10:05:49');
/*!40000 ALTER TABLE `carrers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `blog_id` bigint(20) unsigned NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_user_id_foreign` (`user_id`),
  KEY `comments_blog_id_foreign` (`blog_id`),
  CONSTRAINT `comments_blog_id_foreign` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES
(1,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:19','2025-12-04 14:24:19'),
(2,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:23','2025-12-04 14:24:23'),
(3,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:26','2025-12-04 14:24:26'),
(4,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:27','2025-12-04 14:24:27'),
(5,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:27','2025-12-04 14:24:27'),
(6,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:27','2025-12-04 14:24:27'),
(7,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:28','2025-12-04 14:24:28'),
(8,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:28','2025-12-04 14:24:28'),
(9,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:28','2025-12-04 14:24:28'),
(10,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:31','2025-12-04 14:24:31'),
(11,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:32','2025-12-04 14:24:32'),
(12,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:32','2025-12-04 14:24:32'),
(13,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:32','2025-12-04 14:24:32'),
(14,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:32','2025-12-04 14:24:32'),
(15,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:32','2025-12-04 14:24:32'),
(16,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:32','2025-12-04 14:24:32'),
(17,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:33','2025-12-04 14:24:33'),
(18,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:37','2025-12-04 14:24:37'),
(19,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:38','2025-12-04 14:24:38'),
(20,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:38','2025-12-04 14:24:38'),
(21,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:38','2025-12-04 14:24:38'),
(22,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:39','2025-12-04 14:24:39'),
(23,1,4,'gsgdasjdqjhsd','2025-12-04 14:24:48','2025-12-04 14:24:48'),
(24,1,9,'fgfgfg','2025-12-04 14:28:14','2025-12-04 14:28:14'),
(25,1,5,'Nice and delicious','2025-12-04 14:32:26','2025-12-04 14:32:26'),
(26,1,10,'hhgv','2025-12-12 06:57:00','2025-12-12 06:57:00');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES
(1,'Elias Bekele Tekle','eliobais@gmail.com','asasSSSSSSSSWQS','127.0.0.1',1,'2025-10-28 11:51:12','2025-11-04 07:40:03'),
(2,'Elias Bekele Tekle','eliobais@gmail.com','ghdfghftjhfjhfhthergtrherghesrges','127.0.0.1',1,'2025-11-04 16:02:49','2025-11-04 16:02:58'),
(3,'Elias Bekele Tekle','b@gmail.com','rtgregdhthrthrthrthrthr','127.0.0.1',1,'2025-11-04 16:03:49','2025-11-04 16:04:03'),
(5,'Elias Bekele Tekle','eliobais@gmail.com','Check number five','127.0.0.1',1,'2025-11-05 06:08:03','2025-11-05 06:08:17'),
(6,'Elias Bekele Tekle','eliobais@gmail.com','Hello , How are you.','127.0.0.1',1,'2025-11-06 06:51:35','2025-11-06 06:51:39'),
(7,'Basu','eliobais@gmail.com','hellooooggbfghf','127.0.0.1',1,'2025-11-06 06:52:14','2025-11-06 06:53:48'),
(8,'Elias Bekele Tekle','eliobais@gmail.com','highgfuygugoyuouiyoi','127.0.0.1',1,'2025-11-06 18:32:08','2025-11-06 18:32:26'),
(9,'Elias Bekele Tekle','eliobais@gmail.com','how are you doing.','127.0.0.1',1,'2025-11-07 11:59:19','2025-11-07 11:59:25'),
(11,'Selam','gitelias28@gmail.com','fggjhgjhhjkhkjhjkjkjlkj','127.0.0.1',1,'2025-11-13 15:16:51','2025-11-13 15:17:16'),
(12,'Elias Bekele Tekle','eliobais@gmail.com','hgfhfsxsxsxcs','127.0.0.1',1,'2025-11-17 06:30:13','2025-11-17 07:32:09'),
(13,'Elias Bekele Tekle','eliobais@gmail.com','Hidsasdadqd','127.0.0.1',1,'2025-12-09 16:57:22','2025-12-09 16:57:47'),
(14,'Elias Bekele Tekle','eliobais@gmail.com','Hi hello how are you','127.0.0.1',1,'2025-12-16 14:12:23','2025-12-17 18:14:10'),
(15,'Elias Bekele Tekle','eliobais@gmail.com','heloo ... hhhuuhhuhuh','127.0.0.1',1,'2025-12-18 11:09:26','2025-12-18 11:09:48'),
(16,'Shihab','selam@gmail.com','Hello Shihab Where have you been','127.0.0.1',1,'2025-12-18 13:00:42','2025-12-18 13:01:29');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupon_usages`
--

DROP TABLE IF EXISTS `coupon_usages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupon_usages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `order_reference` varchar(255) DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coupon_usages_coupon_id_used_at_index` (`coupon_id`,`used_at`),
  KEY `coupon_usages_user_id_coupon_id_index` (`user_id`,`coupon_id`),
  CONSTRAINT `coupon_usages_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `coupon_usages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupon_usages`
--

LOCK TABLES `coupon_usages` WRITE;
/*!40000 ALTER TABLE `coupon_usages` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupon_usages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `discount_type` enum('percent','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT NULL,
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int(10) unsigned DEFAULT NULL,
  `usage_limit_per_user` int(10) unsigned DEFAULT NULL,
  `usage_count` int(10) unsigned NOT NULL DEFAULT 0,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `product_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`product_ids`)),
  `category_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`category_ids`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`),
  KEY `coupons_created_by_foreign` (`created_by`),
  KEY `coupons_is_active_start_date_end_date_index` (`is_active`,`start_date`,`end_date`),
  KEY `coupons_code_index` (`code`),
  CONSTRAINT `coupons_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES
(1,'4KZLX4H8',NULL,'fixed',0.06,NULL,NULL,NULL,NULL,0,'2025-11-10','2026-01-10','[8]',NULL,1,1,'2025-11-10 15:12:12','2025-11-10 15:12:12',NULL);
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorites` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `favorite_type` varchar(20) NOT NULL,
  `item_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `favorites_unique_user_content` (`user_id`,`favorite_type`,`item_id`),
  KEY `favorites_favorite_type_item_id_index` (`favorite_type`,`item_id`),
  CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
INSERT INTO `favorites` VALUES
(3,1,'product',8,'2025-11-25 10:07:59','2025-11-25 10:07:59'),
(4,1,'product',7,'2025-11-25 10:08:00','2025-11-25 10:08:00'),
(5,1,'product',6,'2025-11-25 10:08:01','2025-11-25 10:08:01'),
(6,1,'product',5,'2025-11-25 10:08:02','2025-11-25 10:08:02'),
(7,1,'product',1,'2025-11-25 10:08:03','2025-11-25 10:08:03'),
(8,1,'product',2,'2025-11-25 10:08:04','2025-11-25 10:08:04'),
(9,1,'product',3,'2025-11-25 10:08:05','2025-11-25 10:08:05'),
(12,1,'service',2,'2025-11-25 10:08:26','2025-11-25 10:08:26'),
(32,1,'product',10,'2025-11-26 10:50:33','2025-11-26 10:50:33'),
(33,1,'product',9,'2025-11-26 10:50:34','2025-11-26 10:50:34'),
(43,1,'service',1,'2025-12-04 06:03:41','2025-12-04 06:03:41'),
(47,1,'product',13,'2025-12-04 14:32:48','2025-12-04 14:32:48'),
(53,1,'blog',8,'2025-12-08 15:13:40','2025-12-08 15:13:40'),
(54,1,'service',3,'2025-12-08 15:18:48','2025-12-08 15:18:48'),
(65,1,'product',19,'2025-12-10 05:05:32','2025-12-10 05:05:32'),
(70,1,'blog',5,'2025-12-12 06:56:17','2025-12-12 06:56:17'),
(71,1,'service',7,'2025-12-12 06:56:26','2025-12-12 06:56:26'),
(79,1,'product',17,'2025-12-12 21:43:12','2025-12-12 21:43:12'),
(83,1,'blog',4,'2025-12-16 06:21:30','2025-12-16 06:21:30'),
(85,1,'blog',3,'2025-12-16 06:21:34','2025-12-16 06:21:34'),
(86,1,'blog',9,'2025-12-16 06:21:37','2025-12-16 06:21:37'),
(87,1,'blog',2,'2025-12-16 06:21:40','2025-12-16 06:21:40'),
(91,1,'blog',10,'2025-12-16 13:58:26','2025-12-16 13:58:26'),
(92,1,'blog',11,'2025-12-16 14:14:54','2025-12-16 14:14:54'),
(93,1,'product',20,'2025-12-17 18:24:48','2025-12-17 18:24:48'),
(94,1,'blog',6,'2025-12-18 10:08:17','2025-12-18 10:08:17'),
(95,1,'service',9,'2025-12-18 11:11:30','2025-12-18 11:11:30');
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_10_28_081716_add_is_admin_to_users_table',2),
(5,'2025_10_28_083517_create_personal_access_tokens_table',3),
(6,'2025_10_28_092355_add_profile_picture_to_users_table',4),
(7,'2025_10_28_104616_create_pages_table',5),
(8,'2025_10_28_111150_create_contact_messages_table',6),
(9,'2025_10_28_112215_create_products_table',7),
(10,'2025_10_29_102105_add_role_to_users_table',8),
(11,'2025_10_30_083923_add_email_verification_to_users_table',9),
(12,'2025_10_30_093825_add_email_verification_columns_to_users_table',9),
(13,'2025_10_31_120027_create_blogs_table',10),
(14,'2025_10_31_184929_create_services_table',11),
(15,'2025_11_04_085539_add_google_id_to_users_table',12),
(16,'2025_11_07_000001_add_image_url_columns',13),
(17,'2025_11_07_191747_create_carrers_table',14),
(18,'2025_11_10_165139_create_coupons_table',15),
(19,'2025_11_10_165147_create_coupon_usages_table',15),
(20,'2025_11_11_084046_create_customer_columns',16),
(21,'2025_11_11_084047_create_subscriptions_table',16),
(22,'2025_11_11_084048_create_subscription_items_table',16),
(23,'2025_11_11_084049_add_meter_id_to_subscription_items_table',16),
(24,'2025_11_11_084050_add_meter_event_name_to_subscription_items_table',16),
(25,'2025_11_12_084952_rename_type_to_name_in_subscriptions_table',17),
(26,'2024_11_07_000000_create_blog_comments_table',18),
(27,'2025_11_24_200000_create_favorites_table',19),
(28,'2025_11_24_200100_add_favorite_counts_to_content_tables',19),
(29,'2025_11_26_100000_rename_favorite_id_to_item_id_on_favorites_table',20),
(30,'2025_11_19_120027_create_blogs_table',21),
(31,'2025_11_19_184929_create_services_table',22),
(32,'2025_11_21_073020_create_comments_table',22),
(33,'2025_11_26_120000_add_missing_columns_to_products_table',22),
(34,'2025_12_02_135011_add_stripe_price_id_to_products_table',23),
(35,'2025_12_08_132756_add_amount_to_subscriptions_table',24),
(36,'2025_12_08_213042_add_stripe_price_id_to_services_table',25),
(37,'2025_12_09_200000_create_newsletter_subscribers_table',26),
(38,'2025_12_10_064154_create_product_ratings_table',27),
(39,'2025_12_10_064347_add_rating_fields_to_products_table',28),
(40,'2025_12_12_093303_create_careers_table',29),
(41,'2025_12_12_132321_create_purchases_table',30),
(42,'2025_11_07_191747_create_careers_table',30),
(43,'2025_12_12_123410_add_payment_type_to_products_table',31),
(44,'2025_12_12_123410_add_payment_type_to_services_table',31),
(45,'2025_12_15_180104_create_carrer_applied_table',31),
(46,'2025_12_17_083052_make_user_id_nullable_in_career_applied_table',32),
(47,'2025_12_15_180104_create_career_applied_table',32),
(48,'2025_12_17_092549_add_location_to_careers_table',33),
(49,'2025_12_19_092947_add_cover_letter_file_to_career_applied_table',34),
(50,'2025_12_19_093414_create_applicant_replies_table',34);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_subscribers`
--

DROP TABLE IF EXISTS `newsletter_subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `newsletter_subscribers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `status` enum('pending','subscribed','unsubscribed','synced') NOT NULL DEFAULT 'pending',
  `unelma_uid` varchar(255) DEFAULT NULL,
  `synced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletter_subscribers_email_unique` (`email`),
  KEY `newsletter_subscribers_status_index` (`status`),
  KEY `newsletter_subscribers_email_index` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_subscribers`
--

LOCK TABLES `newsletter_subscribers` WRITE;
/*!40000 ALTER TABLE `newsletter_subscribers` DISABLE KEYS */;
INSERT INTO `newsletter_subscribers` VALUES
(1,'testuser123@example.com','Test','User','pending',NULL,NULL,'2025-12-09 16:39:53','2025-12-09 16:39:53');
/*!40000 ALTER TABLE `newsletter_subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES
(1,'Home','home','<h1>Welcome to Unelma</h1><p>Your premier cloud backend solution.</p>','Welcome to Unelma - Cloud Backend Server','unelma, cloud, backend, home','pages/4RvHce0h8J5v54RvVjTPGK1StEqmVQbVP89XhCG8.jpg',1,1,'2025-10-28 08:49:25','2025-10-28 10:53:38'),
(2,'About Us','about','<h1>About Us</h1><p>Learn more about our company and mission.</p>\r\nWelcome to Unelma Platforms, where our mission is to \"empower people.\" Funny thing is nobody were talking about \"empowering people\" in IT industry some 15 years ago and when we started a slogan of \"empower people\" with our business many big corporate giants and other consulting companies started copying our slogan and now almost everybody is talking about how to help and empower clients.\r\n\r\nWith over 15 years of experience in the tech landscape, Unelma Platforms has grown into a household name heralding the digital revolution. We proudly strut a rich legacy that intersects innovation, user experience, and transformative power, borne out of our unwavering commitment to empower the lives of people through technology.\r\n\r\nWe believe in the profound potential technology holds to create positive change, and we channel this belief into creating platforms and software that are user-friendly, efficient, and groundbreaking. Our team comprises seasoned professionals who are passionate about harnessing the power of technology to optimize processes, solve complex problems, and ultimately, transform lives.\r\n\r\nOur product portfolio - including UnelmaMail, UnelmaBrowser, and Unelma-Code Translator - is a testament to our motivation to build technology that makes a difference. Each of our cutting-edge products comes with full support, maintenance, and security provisions to ensure a seamless user experience.\r\n\r\nAt Unelma Platforms, our journey is always about more than just developing technology. It is about empowering people, fostering growth, and pushing boundaries. We don\'t just create platforms; we create opportunities where none existed before.\r\n\r\nSo come with us on this exciting journey and allow us to empower you. Welcome to Unelma Platforms. Embrace the power of technology to transform your world.','Learn about Unelma and our mission','about, company, mission, unelma','pages/KXjUQCyAb9yaItpycOaR65RxYcIUftYcbSUpRaEI.jpg',1,2,'2025-10-28 08:49:25','2025-10-28 13:27:22'),
(3,'Services','services','<h1>Our Services</h1><p>Discover the services we offer.</p>','Professional services offered by Unelma','services, solutions, cloud services',NULL,1,3,'2025-10-28 08:49:25','2025-10-28 08:49:25'),
(4,'Products','products','<h1>Our Products</h1><p>Browse our product catalog.</p>','Explore Unelma products and solutions','products, catalog, solutions','pages/e3t9IRkBLpX5Trswm0XGhoRG3z5WsxOpmZWabK3i.png',1,4,'2025-10-28 08:49:25','2025-10-28 09:29:34'),
(5,'Contact Us','contact','<h1>Contact Us</h1><p>Get in touch with our team.</p>','Contact Unelma for inquiries and support','contact, support, inquiries',NULL,1,5,'2025-10-28 08:49:25','2025-10-28 08:49:25');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES
(2,'App\\Models\\User',2,'auth-token','3c3e6fd9a7cfd1513dac40f5c004603128d556f37957eb1b7d385df501584dee','[\"*\"]','2025-10-28 14:56:46',NULL,'2025-10-28 14:52:32','2025-10-28 14:56:46'),
(5,'App\\Models\\User',14,'auth-token','2ea3cbdb0f8361c66032f41130d4a190ac5a1e5765cadc04a040090260711c25','[\"*\"]',NULL,NULL,'2025-11-04 08:09:05','2025-11-04 08:09:05'),
(14,'App\\Models\\User',15,'auth-token','ba83ef6c0e1c145478566312f367b4bdc01c56c6526e3c1a69100aadc08a4d8a','[\"*\"]',NULL,NULL,'2025-11-04 08:33:24','2025-11-04 08:33:24'),
(16,'App\\Models\\User',17,'test-token','45cc259158397a805aa194622db9d0bfccaf9c1b0a9bdec37c252350e43a4dea','[\"*\"]',NULL,NULL,'2025-11-04 15:23:01','2025-11-04 15:23:01'),
(20,'App\\Models\\User',16,'auth-token','adae2b69817245165f7418bf86840bb691d366be8264381f9ef3d22dd266c4d7','[\"*\"]',NULL,NULL,'2025-11-04 19:28:34','2025-11-04 19:28:34'),
(21,'App\\Models\\User',18,'auth-token','b67bb0d71e1a45f3c80f79017727478ca606957aafb2e6ed2236bb7d85122a86','[\"*\"]',NULL,NULL,'2025-11-05 05:41:04','2025-11-05 05:41:04'),
(26,'App\\Models\\User',22,'auth-token','a7114f521b024c6f6ed68373a2ca8a8ce37b457611998a482688a37aa195a822','[\"*\"]',NULL,NULL,'2025-11-06 08:34:10','2025-11-06 08:34:10'),
(31,'App\\Models\\User',24,'auth-token','91cde6aabb0e50b539998bc61bdedfd91542e24818d4cec81c82f4c7af660118','[\"*\"]','2025-11-12 08:19:54',NULL,'2025-11-12 07:52:56','2025-11-12 08:19:54'),
(33,'App\\Models\\User',25,'auth-token','077edf669696747d8caef7e025974f84041efde8b061fe12e26259d16b7db35b','[\"*\"]','2025-11-13 14:00:51',NULL,'2025-11-13 13:59:28','2025-11-13 14:00:51'),
(35,'App\\Models\\User',23,'auth-token','a30532786ba0d524c6b5f9924b3b0cbfb00fdccde0a1098e6eaf6afaf4f24d71','[]','2025-11-17 06:36:50','2025-11-17 08:31:31','2025-11-17 06:31:31','2025-11-17 06:36:50'),
(49,'App\\Models\\User',26,'auth-token','56b75845f2018843d3373a7f05b89a1ddbd5b1eac69ccc0f61a831ce5a038e03','[]','2025-12-02 18:10:43','2025-12-02 19:58:12','2025-12-02 17:58:12','2025-12-02 18:10:43'),
(96,'App\\Models\\User',27,'auth-token','da845714fb52810c9b769ccf8f0d2f239d7be3d205fecbab9aade55e620d95c0','[]','2025-12-15 10:05:19','2025-12-15 11:30:25','2025-12-15 09:30:25','2025-12-15 10:05:19'),
(100,'App\\Models\\User',30,'auth-token','ba8c45426e6d204f512265b900e6db6df7eb4b2ed97091aebfe63b2938db4e5e','[\"*\"]',NULL,NULL,'2025-12-16 11:56:40','2025-12-16 11:56:40'),
(110,'App\\Models\\User',1,'auth-token','219d714811c97c748a3befea94556e1c63523f5b73a92a70e067c2bacc75348c','[]','2025-12-19 11:33:34','2025-12-19 13:25:34','2025-12-19 11:25:34','2025-12-19 11:33:34');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plans`
--

DROP TABLE IF EXISTS `plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `period` varchar(255) DEFAULT NULL,
  `stripe_price_id` varchar(255) DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `plans_service_id_foreign` (`service_id`),
  CONSTRAINT `plans_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plans`
--

LOCK TABLES `plans` WRITE;
/*!40000 ALTER TABLE `plans` DISABLE KEYS */;
INSERT INTO `plans` VALUES
(14,9,'Business',99.00,'month','price_1SZrQjJg4Qxq8pC4q6wr4tDc','[\"Unlimited Pages\",\"All Team Members\",\"Unlimited Leads\",\"Unlimited Page\",\"Views Export in HTML\\/CSS\"]','2025-12-12 20:25:16','2025-12-12 20:35:47'),
(15,9,'Professional',198.00,'month','price_1SdfIqJg4Qxq8pC4HRtaid75','[\"Unlimited Pages\",\"All Team Members\",\"Unlimited Leads\",\"Unlimited Page\",\"Views Export in HTML\\/CSS\"]','2025-12-12 20:25:16','2025-12-12 20:59:46');
/*!40000 ALTER TABLE `plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_ratings`
--

DROP TABLE IF EXISTS `product_ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_ratings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `feedback` longtext DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_ratings_product_id_user_id_unique` (`product_id`,`user_id`),
  KEY `product_ratings_user_id_foreign` (`user_id`),
  CONSTRAINT `product_ratings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_ratings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_ratings`
--

LOCK TABLES `product_ratings` WRITE;
/*!40000 ALTER TABLE `product_ratings` DISABLE KEYS */;
INSERT INTO `product_ratings` VALUES
(5,20,1,'I hate this product',4,'2025-12-10 06:15:04','2025-12-18 07:56:08'),
(6,19,1,NULL,5,'2025-12-10 06:51:49','2025-12-17 07:15:43'),
(11,16,1,NULL,2,'2025-12-12 07:22:10','2025-12-12 07:22:10'),
(12,8,1,NULL,5,'2025-12-12 21:51:50','2025-12-12 21:51:50'),
(14,15,1,NULL,2,'2025-12-17 06:12:09','2025-12-17 06:12:09'),
(15,7,1,NULL,5,'2025-12-17 06:13:25','2025-12-17 06:13:25');
/*!40000 ALTER TABLE `product_ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `rating_count` int(10) unsigned NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL,
  `stripe_price_id` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL COMMENT 'Payment type: subscription, one_time, or null (auto-detect from Stripe)',
  `sku` varchar(255) DEFAULT NULL,
  `highlights` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `favorite_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES
(1,'UnelmaCloud',NULL,NULL,NULL,0,45.00,NULL,NULL,NULL,NULL,'products/DqgftXkLatrTHSCzCghyYhQp1ZfjAJ5rslEGNEbs.png',NULL,1,1,0,1,'2025-10-28 09:34:00','2025-11-25 10:08:03'),
(2,'UnelmaCRM',NULL,'Introducing UnelmaCRM - your ultimate customer relationship management solution! With UnelmaCRM, we offer more than just a CRM system; we provide a comprehensive package that includes 1 year of full support, maintenance, hosting, SaaS (Software as a Service), top-notch security, and bug fixes.',NULL,0,67.00,NULL,NULL,NULL,NULL,'products/xSgDt5uRw2sVYoKGFolifDJc80CdfqAtYoQyA9HZ.jpg',NULL,0,1,0,1,'2025-10-28 10:30:19','2025-11-25 10:08:04'),
(3,'Clodue',NULL,NULL,NULL,0,700.00,NULL,NULL,NULL,NULL,'products/kFY9GaPtpVPpaNBbxWIgguTEtw7ytzowGNLZfsCd.png',NULL,0,1,0,1,'2025-10-29 05:50:59','2025-11-25 10:08:05'),
(5,'rtedghd',NULL,'fdfsdfs',NULL,0,10.00,NULL,NULL,NULL,NULL,'products/5sjFtDvLiqeVwekeuvEsZ9bTiAdtBMGnIZTdBMh6.jpg',NULL,0,1,0,1,'2025-11-04 06:25:28','2025-11-25 10:08:02'),
(7,'www',NULL,NULL,5.0,1,56.00,NULL,NULL,NULL,NULL,'products/rMWgQSaZiJZ2zopl3Ah554lRYEGDmcHBdP0zIRh6.png',NULL,0,1,0,1,'2025-11-06 10:42:05','2025-12-17 06:13:25'),
(8,'Integration service',NULL,NULL,5.0,1,23567.00,NULL,NULL,NULL,NULL,'products/VEPqpC2HPYNQnTIgqrlz5JlRCud1jGEE3ol4z8UY.png',NULL,0,1,0,1,'2025-11-06 11:16:01','2025-12-12 21:51:50'),
(13,'Elias Bekele Tekle','tech',NULL,5.0,1,12.00,NULL,NULL,'12345',NULL,'products/8Tgsu7DGA88ZI7dT2nYzOncLBOrh3P4gf4tYtZLY.jpg',NULL,0,1,0,1,'2025-11-26 10:39:14','2025-12-15 09:40:45'),
(15,'Coding','Tech',NULL,2.0,1,50.00,'price_1SZtv4Jg4Qxq8pC4yT9kDMrG',NULL,'Tech',NULL,'products/3aj4sJBhoWFFmXIVmgt9yJiA31e1On73SGn9BmkP.jpg',NULL,0,1,0,0,'2025-12-02 12:00:30','2025-12-17 06:12:09'),
(16,'New Product','Tech',NULL,1.5,2,70.00,NULL,NULL,'12345',NULL,'products/qKalnaQlQk8Vk2lZ1pN6Ncw6dcgc0ZYPU0zglIfX.jpg',NULL,0,1,0,0,'2025-12-08 14:09:19','2025-12-15 09:40:03'),
(17,'UnelmaCloud','cloud',NULL,2.0,1,99.00,'price_1SZttSJg4Qxq8pC4IuNK6nTT',NULL,'unelma',NULL,'products/ZjogChziAP7q5ZZKrdKR4KgfagT2kfTK0ySTNWx0.jpg',NULL,0,1,0,1,'2025-12-08 19:05:18','2025-12-12 21:43:12'),
(19,'UnelmaCRM','Tech',NULL,5.0,1,198.00,'price_1ScCJrJg4Qxq8pC4s5oEcApd',NULL,'2345',NULL,'products/doacRlFJcka6Zznfs22vnZObscWof8aZgAFWALCd.png',NULL,0,1,0,1,'2025-12-08 19:51:15','2025-12-17 07:15:43'),
(20,'Test Product 1','Tech',NULL,4.0,1,20.00,'price_1ScP61Jg4Qxq8pC4BWPHKaiJ',NULL,'12345',NULL,'products/qvhDPhyzjIq8O5gUE3QlhfRGB6xfxu6Br8L6MMXg.jpg',NULL,0,1,0,1,'2025-12-09 09:29:38','2025-12-18 07:56:08');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchases` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `stripe_payment_intent_id` varchar(255) DEFAULT NULL,
  `stripe_session_id` varchar(255) DEFAULT NULL,
  `stripe_price_id` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'usd',
  `status` varchar(255) NOT NULL DEFAULT 'completed',
  `product_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `purchased_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchases_stripe_payment_intent_id_unique` (`stripe_payment_intent_id`),
  KEY `purchases_user_id_purchased_at_index` (`user_id`,`purchased_at`),
  KEY `purchases_product_id_service_id_plan_id_index` (`product_id`,`service_id`,`plan_id`),
  KEY `purchases_status_index` (`status`),
  CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchases`
--

LOCK TABLES `purchases` WRITE;
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `stripe_price_id` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL COMMENT 'Payment type: subscription, one_time, or null (auto-detect from Stripe)',
  `favorite_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES
(1,'Cloud Service','We are masters of cloud services as we have developed one of the platforms called \"Unelma Cloud\". Cloud service as the term may refer to a wide range of services delivered on demand to companies and customers over the internet. These services are designed to provide easy, affordable access to applications and resources, without the need for internal infrastructure or hardware.',NULL,'services/V8TFz4QRhbkjgkgsWAL561fQo4X4bZ4Lzqs1Ur9R.jpg',NULL,1,0,0,NULL,NULL,1,'2025-10-31 16:55:20','2025-12-04 06:03:41'),
(2,'Data Science','Our core set of skills lies in data science and artificial intelligence. We know that data science is an interdisciplinary field that uses scientific methods, processes, algorithms and systems to extract or extrapolate knowledge and insights from noisy, structured and unstructured data, and apply knowledge from data across a broad range of application domains. Previously, we have developed AI-powered email applications which have scaled to millions of users and subscribers. Feel free to contact us if you would need help with data science-related services.',NULL,'services/zZFdytFeCYKRqNzRmssEhX6H1zlxfkLzJ79KbYsT.jpg',NULL,1,0,0,NULL,NULL,1,'2025-10-31 17:07:19','2025-11-25 10:08:26'),
(3,'Data Management','Data management is collecting, organizing, protecting, and storing an organization\'s data so it can be analyzed for business decisions. As organizations create and consume data at unprecedented rates, data management solutions become essential for making sense of the vast data. We at Unelma Platforms can help you with different data management products and services. Some common data management platforms we have developed include UnelmaCRM, analytics and social proof tools and services.',NULL,'services/jDMZOiSbAA8JzAVlKsbpma0TqXtblekfxSYhkGsE.jpg',NULL,1,0,0,NULL,NULL,1,'2025-11-06 08:33:07','2025-12-08 15:18:48'),
(9,'Cyber Security','Cyber security is fundamental in today\'s day and age. We provide you with cyber security tools and services with a wide range of Open Web Application Security Project®toolbox that can help you and your company in security-related tasks efficiently and conveniently.',NULL,'services/ApeZDc4sQw0wyGALhvORReRnzowNG4LjEAn5Jm9r.jpg',NULL,1,0,0,'price_1SZrQjJg4Qxq8pC4q6wr4tDc',NULL,2,'2025-12-12 10:34:10','2025-12-18 11:11:30');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('pXB3e4cBX2mZx3msF0ubKw3zjYnSqFYIFLtfFa14',1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWXVEdDlUR01RVWZab05ubEdQeW9TRVk5Vk5wTVZaY0tVRmtvdFZucyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wcm9kdWN0cyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1766178290);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscription_items`
--

DROP TABLE IF EXISTS `subscription_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscription_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint(20) unsigned NOT NULL,
  `stripe_id` varchar(255) NOT NULL,
  `stripe_product` varchar(255) NOT NULL,
  `stripe_price` varchar(255) NOT NULL,
  `meter_id` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `meter_event_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscription_items_stripe_id_unique` (`stripe_id`),
  KEY `subscription_items_subscription_id_stripe_price_index` (`subscription_id`,`stripe_price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription_items`
--

LOCK TABLES `subscription_items` WRITE;
/*!40000 ALTER TABLE `subscription_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscription_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `stripe_id` varchar(255) NOT NULL,
  `stripe_status` varchar(255) NOT NULL,
  `stripe_price` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscriptions_stripe_id_unique` (`stripe_id`),
  KEY `subscriptions_user_id_stripe_status_index` (`user_id`,`stripe_status`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
INSERT INTO `subscriptions` VALUES
(13,26,'unelmacloud','sub_1ST56NF326sjI6Gj7yXEGb3z','active','price_1SSDbIF326sjI6Gj8bS7CURE',NULL,1,NULL,NULL,'2025-11-13 16:18:38','2025-11-13 16:18:38'),
(14,26,'unelmacloud','sub_1ST5KXF326sjI6Gjs4rCiVN4','active','price_1SSDbIF326sjI6Gj8bS7CURE',NULL,1,NULL,NULL,'2025-11-13 16:33:15','2025-11-13 16:33:15'),
(15,26,'unelmacloud','sub_1ST5R5F326sjI6GjPZJTdiab','active','price_1SSDbIF326sjI6Gj8bS7CURE',NULL,1,NULL,NULL,'2025-11-13 16:40:01','2025-11-13 16:40:01'),
(16,26,'cyber_security','sub_1ST5SJF326sjI6GjpKh4QQre','active','price_1SSyZBF326sjI6GjRaZKihJt',NULL,1,NULL,NULL,'2025-11-13 16:41:17','2025-11-13 16:41:17'),
(17,26,'cyber_security','sub_1ST5eQF326sjI6GjSQoZdYGy','active','price_1SSyZBF326sjI6GjRaZKihJt',NULL,1,NULL,NULL,'2025-11-13 16:53:47','2025-11-13 16:53:47'),
(22,1,'cyber_security','sub_1SXMhsF326sjI6Gj6YWwODe4','active','price_1SSyZBF326sjI6GjRaZKihJt',NULL,1,NULL,NULL,'2025-11-25 11:55:02','2025-11-25 11:55:02'),
(23,1,'cyber_security','sub_1SZrVtF326sjI6Gj4t39hONY','active','price_1SSyZBF326sjI6GjRaZKihJt',NULL,1,NULL,NULL,'2025-12-02 09:12:59','2025-12-02 09:12:59'),
(24,1,'unelmacloud','sub_1SZrq1F326sjI6GjA9H42Wng','active','price_1SSDbIF326sjI6Gj8bS7CURE',NULL,1,NULL,NULL,'2025-12-02 09:33:47','2025-12-02 09:33:47'),
(25,1,'Coding','sub_1SaKNzJg4Qxq8pC4Z3aoexFT','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',NULL,1,NULL,NULL,'2025-12-03 16:03:55','2025-12-03 16:03:55'),
(26,1,'Coding','sub_1SaKTkJg4Qxq8pC4IqkBiKLa','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',NULL,1,NULL,NULL,'2025-12-03 16:10:19','2025-12-03 16:10:19'),
(27,1,'Coding','sub_1SaKo1Jg4Qxq8pC4WF8jCK6e','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',NULL,1,NULL,NULL,'2025-12-03 16:35:21','2025-12-03 16:35:21'),
(28,1,'Coding','sub_1SaKwnJg4Qxq8pC4YKVv1njQ','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',NULL,1,NULL,NULL,'2025-12-03 16:38:45','2025-12-03 16:38:45'),
(29,1,'Coding','sub_1SaKyKJg4Qxq8pC4gVCbu0TX','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',NULL,1,NULL,NULL,'2025-12-03 16:40:20','2025-12-03 16:40:20'),
(30,1,'Coding','sub_1SaXTsJg4Qxq8pC4ltAaxvN7','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',NULL,1,NULL,NULL,'2025-12-04 06:01:43','2025-12-04 06:01:43'),
(31,1,'Coding','sub_1Sad4nJg4Qxq8pC4UAKr8Yrp','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',NULL,2,NULL,NULL,'2025-12-04 12:00:11','2025-12-04 12:00:11'),
(32,1,'Coding','sub_1Sad5aJg4Qxq8pC4lpS3psZC','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',NULL,5,NULL,NULL,'2025-12-04 12:01:00','2025-12-04 12:01:00'),
(33,1,'Coding','sub_1Saey4Jg4Qxq8pC4pFGgPvE7','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',NULL,3,NULL,NULL,'2025-12-04 14:01:23','2025-12-04 14:01:23'),
(34,1,'Coding','sub_1SauApJg4Qxq8pC4iwAuzigh','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',NULL,1,NULL,NULL,'2025-12-05 06:15:35','2025-12-05 06:15:35'),
(35,1,'Coding','sub_1SauZpJg4Qxq8pC40xYNkfIc','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',NULL,2,NULL,NULL,'2025-12-05 06:41:25','2025-12-05 06:41:25'),
(36,1,'Coding','sub_1SbiKNJg4Qxq8pC4ileVn1Xr','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',NULL,2,NULL,NULL,'2025-12-07 11:48:48','2025-12-07 11:48:48'),
(37,1,'Coding','sub_1Sc3fCJg4Qxq8pC4buPq4cNE','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',NULL,1,NULL,NULL,'2025-12-08 10:35:42','2025-12-08 10:35:42'),
(38,1,'Coding','sub_1Sc4DLJg4Qxq8pC4tfERwRn3','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',NULL,1,NULL,NULL,'2025-12-08 11:11:03','2025-12-08 11:11:03'),
(39,1,'Coding','sub_1Sc4XMJg4Qxq8pC4sRdBKIlV','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',50.00,6,NULL,NULL,'2025-12-08 11:31:40','2025-12-08 11:53:26'),
(40,1,'Coding','sub_1Sc4u6Jg4Qxq8pC4E7Jak04d','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',50.00,2,NULL,NULL,'2025-12-08 11:55:10','2025-12-08 11:58:00'),
(41,1,'Coding','sub_1Sc85oJg4Qxq8pC4siH6l2K6','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',50.00,4,NULL,NULL,'2025-12-08 15:19:38','2025-12-08 15:19:38'),
(42,1,'Coding','sub_1ScBStJg4Qxq8pC4oFA0FRQ9','active','price_1SZtv4Jg4Qxq8pC4yT9kDMrG',NULL,1,NULL,NULL,'2025-12-08 18:55:31','2025-12-08 18:55:31'),
(43,1,'UnelmaCloud','sub_1ScBctJg4Qxq8pC45TN7hZpS','active','price_1SZttSJg4Qxq8pC4IuNK6nTT',NULL,1,NULL,NULL,'2025-12-08 19:05:51','2025-12-08 19:05:51'),
(44,1,'UnelmaCloud','sub_1ScCF4Jg4Qxq8pC46Tx6rdoM','active','price_1SZttSJg4Qxq8pC4IuNK6nTT',55.00,2,NULL,NULL,'2025-12-08 19:45:18','2025-12-08 19:45:18'),
(45,1,'UnelmaCloud','sub_1ScCH2Jg4Qxq8pC47SUNNkc5','active','price_1SZttSJg4Qxq8pC4IuNK6nTT',55.00,3,NULL,NULL,'2025-12-08 19:47:20','2025-12-08 19:47:20'),
(46,1,'UnelmaCRM','sub_1ScCLMJg4Qxq8pC4JX3Vdp4O','active','price_1ScCJrJg4Qxq8pC4s5oEcApd',198.00,1,NULL,NULL,'2025-12-08 19:51:49','2025-12-08 19:51:49'),
(47,27,'UnelmaCRM','sub_1ScCO6Jg4Qxq8pC4WOEBUEFp','active','price_1ScCJrJg4Qxq8pC4s5oEcApd',198.00,1,NULL,NULL,'2025-12-08 19:54:38','2025-12-08 19:54:38'),
(51,1,'UnelmaCRM','sub_1ScP2wJg4Qxq8pC4nGHWALUq','active','price_1ScCJrJg4Qxq8pC4s5oEcApd',198.00,2,NULL,NULL,'2025-12-09 09:25:39','2025-12-09 09:25:39'),
(52,1,'UnelmaCRM','sub_1ScP3wJg4Qxq8pC4fcssMvjd','active','price_1ScCJrJg4Qxq8pC4s5oEcApd',198.00,3,NULL,NULL,'2025-12-09 09:26:41','2025-12-09 09:26:41'),
(53,1,'Test Product 1','sub_1ScP7PJg4Qxq8pC4nWrk3WgX','active','price_1ScP61Jg4Qxq8pC4BWPHKaiJ',20.00,1,NULL,NULL,'2025-12-09 09:30:16','2025-12-09 09:30:16'),
(54,1,'Test Product 1','sub_1ScPoYJg4Qxq8pC4Kt9Yv4Py','active','price_1ScP61Jg4Qxq8pC4BWPHKaiJ',20.00,3,NULL,NULL,'2025-12-09 10:14:51','2025-12-09 10:14:51'),
(57,1,'UnelmaCRM','sub_1SdSDgJg4Qxq8pC4T5BqY81M','active','price_1ScCJrJg4Qxq8pC4s5oEcApd',198.00,1,NULL,NULL,'2025-12-12 07:01:04','2025-12-12 07:01:04'),
(58,1,'Test Product 1','sub_1Sdb2rJg4Qxq8pC4iFxggAxQ','active','price_1ScP61Jg4Qxq8pC4BWPHKaiJ',20.00,1,NULL,NULL,'2025-12-12 16:26:29','2025-12-12 16:26:29'),
(59,1,'Test Product 1','sub_1SdeubJg4Qxq8pC4X6G3qGa9','active','price_1ScP61Jg4Qxq8pC4BWPHKaiJ',20.00,2,NULL,NULL,'2025-12-12 20:34:13','2025-12-12 20:35:56'),
(60,1,'Test Product 1','sub_1SdfCIJg4Qxq8pC4BuqE9iWH','active','price_1ScP61Jg4Qxq8pC4BWPHKaiJ',20.00,3,NULL,NULL,'2025-12-12 20:52:31','2025-12-12 20:52:31'),
(61,1,'Cyber Security - Business','sub_1SdfGhJg4Qxq8pC4R5etumzA','active','price_1SZrQjJg4Qxq8pC4q6wr4tDc',99.00,1,NULL,NULL,'2025-12-12 20:57:03','2025-12-12 20:57:03'),
(62,1,'Test Product 1','sub_1SdfHJJg4Qxq8pC4NhnMG0Fz','active','price_1ScP61Jg4Qxq8pC4BWPHKaiJ',20.00,5,NULL,NULL,'2025-12-12 20:57:41','2025-12-12 20:57:41'),
(63,1,'Cyber Security - Professional','sub_1SdfJfJg4Qxq8pC4fAwhL4kZ','active','price_1SdfIqJg4Qxq8pC4HRtaid75',198.00,1,NULL,NULL,'2025-12-12 21:00:08','2025-12-12 21:00:08'),
(64,1,'Cyber Security - Professional','sub_1Sdg8dJg4Qxq8pC4DTDlGwBr','active','price_1SdfIqJg4Qxq8pC4HRtaid75',198.00,1,NULL,NULL,'2025-12-12 21:52:47','2025-12-12 21:52:47'),
(65,1,'Cyber Security - Professional','sub_1SegpZJg4Qxq8pC4vxvoSPo7','active','price_1SdfIqJg4Qxq8pC4HRtaid75',198.00,1,NULL,NULL,'2025-12-15 16:49:17','2025-12-15 16:55:31'),
(66,1,'Cyber Security - Business','sub_1SetWIJg4Qxq8pC4CdNMYiuq','active','price_1SZrQjJg4Qxq8pC4q6wr4tDc',99.00,1,NULL,NULL,'2025-12-16 06:22:14','2025-12-16 06:22:15'),
(67,1,'Cyber Security - Business','sub_1Sf0XbJg4Qxq8pC4Q77IGE86','active','price_1SZrQjJg4Qxq8pC4q6wr4tDc',99.00,1,NULL,NULL,'2025-12-16 13:52:03','2025-12-16 13:52:04'),
(68,1,'UnelmaCRM','sub_1Sf0YsJg4Qxq8pC4LhG0MlVX','active','price_1ScCJrJg4Qxq8pC4s5oEcApd',198.00,1,NULL,NULL,'2025-12-16 13:53:22','2025-12-16 13:53:23'),
(69,1,'UnelmaCloud','sub_1Sf0cAJg4Qxq8pC4KvTYZGkU','active','price_1SZttSJg4Qxq8pC4IuNK6nTT',55.00,2,NULL,NULL,'2025-12-16 13:56:46','2025-12-16 13:56:47'),
(70,1,'Cyber Security - Business','sub_1Sf0s0Jg4Qxq8pC4MQpgO9OT','active','price_1SZrQjJg4Qxq8pC4q6wr4tDc',99.00,1,NULL,NULL,'2025-12-16 14:13:08','2025-12-16 14:13:09'),
(71,1,'Cyber Security - Business','sub_1SfGnCJg4Qxq8pC4JGwHGGxd','active','price_1SZrQjJg4Qxq8pC4q6wr4tDc',99.00,1,NULL,NULL,'2025-12-17 07:13:14','2025-12-17 07:13:15'),
(72,1,'Test Product 1','sub_1SfdweJg4Qxq8pC4Ddo36ULO','active','price_1ScP61Jg4Qxq8pC4BWPHKaiJ',20.00,1,NULL,NULL,'2025-12-18 07:56:35','2025-12-18 07:56:36'),
(73,1,'Cyber Security - Business','sub_1SffJ4Jg4Qxq8pC4JVtImi3J','active','price_1SZrQjJg4Qxq8pC4q6wr4tDc',99.00,1,NULL,NULL,'2025-12-18 09:23:47','2025-12-18 09:23:47'),
(74,1,'Test Product 1','sub_1Sg3glJg4Qxq8pC4DzH0m9n4','active','price_1ScP61Jg4Qxq8pC4BWPHKaiJ',20.00,1,NULL,NULL,'2025-12-19 11:25:54','2025-12-19 11:25:54');
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `email_verification_token` varchar(255) DEFAULT NULL,
  `email_verification_sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stripe_id` varchar(255) DEFAULT NULL,
  `pm_type` varchar(255) DEFAULT NULL,
  `pm_last_four` varchar(4) DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_google_id_unique` (`google_id`),
  KEY `users_stripe_id_index` (`stripe_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'ELIAS BEKELE TEKLE','eliobais@gmail.com',NULL,'profile_pictures/UZMaErOQO49BFaERwWdcF7OyypRzdwVu1GA2WSPY.jpg',1,'admin','2025-10-28 06:18:42','$2y$12$89AHrRhwaJ8TSCBMH3x9Z.Gcn4ViE6Eu7dx/vwjARP9m3TnXTsys6','WATSjICQqZdqY0wxgTuJdRklCXe850PcOVjDTg8vZovucbBOQyco7fVkJ6vO',NULL,NULL,'2025-10-28 06:18:42','2025-12-02 12:45:34','cus_TWync3jop3RvYj',NULL,NULL,NULL),
(23,'Elias Bekele Tekle','ttt@gmail.com',NULL,NULL,0,'admin',NULL,'$2y$12$/MWB8QY3xvJiiXa4ELWyUuoLFyh9KsJ7Z5T/YWVZ3icZoQokRiHHK',NULL,NULL,NULL,'2025-11-11 09:36:26','2025-11-11 12:56:39','cus_TP7EGe5krWSX9p',NULL,NULL,NULL),
(25,'Elias Bekele Tekle','ethiojobs@gmail.com',NULL,NULL,0,'admin',NULL,'$2y$12$JHvSUlgrKbBaCFkNuA4UDuW6bO/uyLqsVE8O7v6B8wJjuj/o1kyzC',NULL,NULL,NULL,'2025-11-13 13:59:28','2025-11-13 14:00:51','cus_TPsi4ulBgTji0f',NULL,NULL,NULL),
(28,'eli','gitelias28@gmail.com',NULL,'profile_pictures/62J02Uc8OX3cuW1cG7htgrNzSPFVLwvd8jUl87XI.jpg',1,'admin','2025-12-16 07:17:54','$2y$12$PrmpmGKqHUvphupeKXsmEuNnEYz.6E0pCI8TF6.cK6P8.scu1XQoW',NULL,NULL,NULL,'2025-12-16 07:06:54','2025-12-16 07:17:54',NULL,NULL,NULL,NULL),
(30,'Elias Bekele Tekle','bluenile890@gmail.com',NULL,NULL,0,'admin',NULL,'$2y$12$wUF/lV5w109AqeVlnrMHiOf1Wr8niA2m/MN/2qQ30XqMyGVVuFfMa',NULL,NULL,NULL,'2025-12-16 11:56:40','2025-12-16 11:56:40',NULL,NULL,NULL,NULL),
(31,'Admin User','admin@example.com',NULL,NULL,1,'admin','2025-12-17 06:56:38','$2y$12$pUPUHnZnYlCCJAsyoV5nd.yFyQpomNc39BeKWHca/79AT/9lkxpbi',NULL,NULL,NULL,'2025-12-17 06:56:38','2025-12-17 06:56:38',NULL,NULL,NULL,NULL),
(56,'Elias Bekele Tekle','bluenile.ojulu890@gmail.com',NULL,'profile_pictures/tLWtp8DfNZjzgA8PadVaGGPFs3Hjl8mc0lkHkkE4.jpg',1,'admin',NULL,'$2y$12$KmLJQARzhk8uuyVpLqeVsudc3QT.y4mf0SQCwkpTeJ4OzWID9.VMO',NULL,NULL,NULL,'2025-12-19 18:37:26','2025-12-19 18:37:26',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-12-20 18:19:07
