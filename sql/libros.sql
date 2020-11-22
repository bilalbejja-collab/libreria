-- MySQL dump 10.13  Distrib 8.0.20, for Win64 (x86_64)
--
-- Host: eu-cdbr-west-03.cleardb.net    Database: heroku_c1ce710b3a14a7d
-- ------------------------------------------------------
-- Server version	5.6.49-log

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
-- Table structure for table `libros`
--

DROP TABLE IF EXISTS `libros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `libros` (
  `ISBN` varchar(13) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `titulo` varchar(20) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `subtitulo` varchar(45) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `descripcion` varchar(45) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `autor` varchar(45) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `editorial` varchar(20) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `categoria` varchar(10) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `portada` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `total_ejemplares` int(11) NOT NULL,
  `ejemplares_disponibles` int(11) NOT NULL,
  PRIMARY KEY (`ISBN`),
  UNIQUE KEY `ISBN_UNIQUE` (`ISBN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `libros`
--

LOCK TABLES `libros` WRITE;
/*!40000 ALTER TABLE `libros` DISABLE KEYS */;
INSERT INTO `libros` VALUES ('012345678','La vida','sufrance','eqeqe','fafasf','afasfafasf','novela','https://i.pinimg.com/474x/db/e1/1f/dbe11fabcc48d9990d46ba6c5aa559d7.jpg',32,21),('123456789','Blal','dasdasdad','adsdadasda','dsasdasd','dsadasdad','viajes','https://i.pinimg.com/474x/30/75/eb/3075eb24cf153445431012928c30816b.jpg',14,5),('234567891','ddad','dadi','ddad','ddad','ddad','novela','http://es.web.img3.acsta.net/pictures/17/08/25/11/58/463146.jpg',12,11),('345678901','qwrqwr','qttett','tertert','gdgdgd','xvxv','ensayo','https://i.pinimg.com/474x/78/19/0f/78190fb8bc338f65668b0e1141d840dc.jpg',3,1);
/*!40000 ALTER TABLE `libros` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-11-22 20:20:16
