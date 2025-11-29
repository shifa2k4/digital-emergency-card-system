-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: digicard
-- ------------------------------------------------------
-- Server version	8.0.36

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
-- Table structure for table `emergency_details`
--

DROP TABLE IF EXISTS `emergency_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `emergency_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(45) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `dob` date DEFAULT NULL,
  `bloodgroup` varchar(5) DEFAULT NULL,
  `emergency1` varchar(20) DEFAULT NULL,
  `emergency2` varchar(20) DEFAULT NULL,
  `emergency3` varchar(20) DEFAULT NULL,
  `allergies` varchar(255) DEFAULT NULL,
  `conditions` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emergency_details`
--

LOCK TABLES `emergency_details` WRITE;
/*!40000 ALTER TABLE `emergency_details` DISABLE KEYS */;
INSERT INTO `emergency_details` VALUES (1,'1','9876543210','anji@gmail.com','2025-09-22','A-','9089786540','9089786757','9890776611','nuts','none'),(4,'','9087654321','testt@gmail.com','2025-10-10','A+','9089786540','9089786757','9890776611','nuts','cholestrol'),(5,'','09087654555','test@gmail.com','2025-10-15','A+','9089786540','9089786757','9890776611','nuts','cholestrol'),(6,'8','09087654555','john@gmail.com','2000-01-17','AB-','9089786540','9089786757','9890776611','nuts','none'),(7,'2','9087654300','lek@gmail.com','2000-03-02','O+','9089786540','9089786757','9890776611','nuts','cholestrol'),(8,'5','9856734521','test@gmail.com','2005-02-17','B+','9089786540','9089786757','9890776611','nuts','none'),(9,'','09087654555','test@gmail.com','2003-03-13','A+','9089786540','9089786757','9890776611','nuts','none'),(10,'','09087654555','test6@gmail.com','2006-10-17','AB+','9089786540','9089786757','9890776611','nuts','none'),(11,'','09087654555','test6@gmail.com','2006-10-17','AB+','9089786540','9089786757','9890776611','nuts','none');
/*!40000 ALTER TABLE `emergency_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qr_code`
--

DROP TABLE IF EXISTS `qr_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qr_code` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `qr_data` text,
  `qr_image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qr_code`
--

LOCK TABLES `qr_code` WRITE;
/*!40000 ALTER TABLE `qr_code` DISABLE KEYS */;
INSERT INTO `qr_code` VALUES (1,2,'http://localhost/stu_pros_php/MediTrust/admin_profile.php?id=2','qr_images/qr_2.png','2025-10-10 10:15:54'),(2,5,'http://localhost/stu_pros_php/MediTrust/admin_profile.php?id=5','qr_images/qr_5.png','2025-10-10 06:02:51'),(3,1,'http://localhost/stu_pros_php/MediTrust/profile.php?id=1','qr_images/qr_1.png','2025-10-11 00:51:47'),(4,3,'http://localhost/stu_pros_php/MediTrust/admin_profile.php?id=3','qr_images/qr_3.png','2025-10-10 06:08:31'),(5,4,'http://localhost/stu_pros_php/MediTrust/admin_profile.php?id=4','qr_images/qr_4.png','2025-10-10 10:30:02'),(6,8,'http://localhost/stu_pros_php/MediTrust/profile.php?id=8','qr_images/qr_8.png','2025-10-10 06:04:22');
/*!40000 ALTER TABLE `qr_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qr_codes`
--

DROP TABLE IF EXISTS `qr_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qr_codes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `emergency_id` int NOT NULL,
  `qr_code_path` varchar(255) NOT NULL,
  `qr_content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qr_codes`
--

LOCK TABLES `qr_codes` WRITE;
/*!40000 ALTER TABLE `qr_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `qr_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'anjitha','9876543210','anji@gmail.com',' test'),(2,'lekshmi','9087654321','lek@gmail.com',' test'),(4,'athira','8909478767','athi@gmail.com',' test'),(5,'seetha','9856734521','test@gmail.com',' test'),(6,'boss','9087654321','test2@gmail.com',' test'),(8,'john','09087654555','john@gmail.com',' test'),(9,'savitha','9087654321','savi@gmail.com',' test');
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

-- Dump completed on 2025-10-11 12:22:02
