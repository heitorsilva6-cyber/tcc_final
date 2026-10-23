-- MySQL dump 10.13  Distrib 8.0.46, for Win64 (x86_64)
--
-- Host: localhost    Database: orbita_numeros
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `attempts`
--

DROP TABLE IF EXISTS `attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attempts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `game_key` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` int NOT NULL,
  `correct` int NOT NULL,
  `total` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `attempts_user_idx` (`user_id`),
  CONSTRAINT `attempts_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attempts`
--

LOCK TABLES `attempts` WRITE;
/*!40000 ALTER TABLE `attempts` DISABLE KEYS */;
INSERT INTO `attempts` VALUES (1,2,'integers',300,3,3,'2026-09-09 19:19:36'),(2,3,'orbit',200,2,3,'2026-10-02 11:24:30'),(3,3,'energy',300,3,3,'2026-10-02 11:28:53'),(4,3,'orbit',300,3,3,'2026-10-02 11:29:17'),(5,3,'submarine',100,1,3,'2026-10-02 11:31:31'),(6,10,'integers',100,1,3,'2026-10-07 18:04:24'),(7,10,'submarine',200,2,3,'2026-10-07 18:04:48');
/*!40000 ALTER TABLE `attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `badges`
--

DROP TABLE IF EXISTS `badges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `badges` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `badge_key` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unlocked_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_badge_unique` (`user_id`,`badge_key`),
  CONSTRAINT `badges_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `badges`
--

LOCK TABLES `badges` WRITE;
/*!40000 ALTER TABLE `badges` DISABLE KEYS */;
INSERT INTO `badges` VALUES (1,2,'first-flight','2026-09-09 19:19:36'),(2,2,'perfect','2026-09-09 19:19:36'),(3,3,'first-flight','2026-10-02 11:24:30'),(4,3,'perfect','2026-10-02 11:28:53'),(6,10,'first-flight','2026-10-07 18:04:24');
/*!40000 ALTER TABLE `badges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'astronauta',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Cadete MySQL','mysql161339@orbita.local','$2y$10$NML1uj8bT6VAqW22kt6BYuPy5o1UibuuYbsJV4qf/Z1G8OenaKvhu','astronauta','2026-09-09 19:13:40'),(2,'hxtfps \'','sff71154@gmail.com','$2y$10$gK.dGmAU7KonqcgV5obA9.6EHv.Hp2IxRQrRCUm2M9RiZL0GB5s/m','astronauta','2026-09-09 19:14:24'),(3,'potassio','heitor@gmail.com','$2y$10$WDivPJdDCQXuJRwqUWWCTuClTth0Xt84YcRp9Ctrv67UkJyhKajh6','astronauta','2026-10-02 11:20:59'),(10,'heitorrrr','heitooor@gmail.com','$2y$10$jjLw99nnfTdi5QNBnd84ouPrTwRytSQliBhxgV7bCHTVg/KlylGG2','astronauta','2026-10-07 18:04:04');
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

-- Dump completed on 2026-10-07 15:07:41
