-- MySQL dump 10.13  Distrib 9.0.1, for Linux (x86_64)
--
-- Host: localhost    Database: Company
-- ------------------------------------------------------
-- Server version	9.0.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Department`
--

DROP TABLE IF EXISTS `Department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Department` (
  `DepartmentName` varchar(255) NOT NULL,
  `DepartmentNumber` int NOT NULL,
  `ManagerSSN` varchar(15) DEFAULT NULL,
  `ManagerStartDate` date NOT NULL,
  PRIMARY KEY (`DepartmentNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Department`
--

LOCK TABLES `Department` WRITE;
/*!40000 ALTER TABLE `Department` DISABLE KEYS */;
INSERT INTO `Department` VALUES ('Health',1,'657835743','2024-11-05'),('IT Department',2,'987-65-4321','2015-06-22'),('Sales',3,'111-22-3333','2018-03-18'),('Maintenance',4,'657835743','2024-11-05'),('Maintenance',6,'657835743','2024-11-06'),('Human Resources',8,'6578357431','2024-11-06');
/*!40000 ALTER TABLE `Department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `DepartmentLocations`
--

DROP TABLE IF EXISTS `DepartmentLocations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `DepartmentLocations` (
  `DepartmentNumber` int NOT NULL,
  `DepartmentLocation` varchar(255) NOT NULL,
  PRIMARY KEY (`DepartmentNumber`,`DepartmentLocation`),
  CONSTRAINT `DepartmentLocations_ibfk_1` FOREIGN KEY (`DepartmentNumber`) REFERENCES `Department` (`DepartmentNumber`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DepartmentLocations`
--

LOCK TABLES `DepartmentLocations` WRITE;
/*!40000 ALTER TABLE `DepartmentLocations` DISABLE KEYS */;
/*!40000 ALTER TABLE `DepartmentLocations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Dependent`
--

DROP TABLE IF EXISTS `Dependent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Dependent` (
  `Name` varchar(255) NOT NULL,
  `Sex` char(1) NOT NULL,
  `EmployeeSSN` char(9) NOT NULL,
  `Relation` varchar(100) NOT NULL,
  `Birthday` date NOT NULL,
  PRIMARY KEY (`EmployeeSSN`,`Name`),
  CONSTRAINT `Dependent_ibfk_1` FOREIGN KEY (`EmployeeSSN`) REFERENCES `Employee` (`SSN`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Dependent`
--

LOCK TABLES `Dependent` WRITE;
/*!40000 ALTER TABLE `Dependent` DISABLE KEYS */;
/*!40000 ALTER TABLE `Dependent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Employee`
--

DROP TABLE IF EXISTS `Employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Employee` (
  `SSN` char(9) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `MiddleInitials` char(1) DEFAULT NULL,
  `LastName` varchar(100) NOT NULL,
  `Sex` char(1) NOT NULL,
  `Salary` decimal(10,2) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Birthday` date NOT NULL,
  `SuperSSN` char(9) DEFAULT NULL,
  `DepartmentNumber` int DEFAULT NULL,
  PRIMARY KEY (`SSN`),
  KEY `SuperSSN` (`SuperSSN`),
  KEY `DepartmentNumber` (`DepartmentNumber`),
  CONSTRAINT `Employee_ibfk_1` FOREIGN KEY (`SuperSSN`) REFERENCES `Employee` (`SSN`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `Employee_ibfk_2` FOREIGN KEY (`DepartmentNumber`) REFERENCES `Department` (`DepartmentNumber`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Employee`
--

LOCK TABLES `Employee` WRITE;
/*!40000 ALTER TABLE `Employee` DISABLE KEYS */;
INSERT INTO `Employee` VALUES ('111-2212','Alice','C','Johnson','F',70000.00,'789 Pine St, Springfield','1990-08-25',NULL,2),('6782687','Jake','P','Phelps','M',60000.00,'2121, MorrowRd, Houston ','1988-09-11','111-2212',1),('98721','James','A','Jones','M',90000.00,'2212 Luis St','1999-06-15',NULL,3);
/*!40000 ALTER TABLE `Employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Project`
--

DROP TABLE IF EXISTS `Project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Project` (
  `ProjectName` varchar(255) NOT NULL,
  `ProjectNumber` int NOT NULL,
  `Location` varchar(255) DEFAULT NULL,
  `DepartmentNumber` int DEFAULT NULL,
  PRIMARY KEY (`ProjectNumber`),
  KEY `DepartmentNumber` (`DepartmentNumber`),
  CONSTRAINT `Project_ibfk_1` FOREIGN KEY (`DepartmentNumber`) REFERENCES `Department` (`DepartmentNumber`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Project`
--

LOCK TABLES `Project` WRITE;
/*!40000 ALTER TABLE `Project` DISABLE KEYS */;
INSERT INTO `Project` VALUES ('ProductX',1,'Houston',2),('NwProduct',2,'Dallas',2);
/*!40000 ALTER TABLE `Project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `WorksOn`
--

DROP TABLE IF EXISTS `WorksOn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `WorksOn` (
  `SSN` char(9) NOT NULL,
  `ProjectNumber` int NOT NULL,
  `Hours` decimal(5,2) NOT NULL,
  PRIMARY KEY (`SSN`,`ProjectNumber`),
  KEY `ProjectNumber` (`ProjectNumber`),
  CONSTRAINT `WorksOn_ibfk_1` FOREIGN KEY (`SSN`) REFERENCES `Employee` (`SSN`),
  CONSTRAINT `WorksOn_ibfk_2` FOREIGN KEY (`ProjectNumber`) REFERENCES `Project` (`ProjectNumber`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `WorksOn`
--

LOCK TABLES `WorksOn` WRITE;
/*!40000 ALTER TABLE `WorksOn` DISABLE KEYS */;
INSERT INTO `WorksOn` VALUES ('111-2212',1,11.00),('6782687',1,8.00);
/*!40000 ALTER TABLE `WorksOn` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-25  0:16:45
