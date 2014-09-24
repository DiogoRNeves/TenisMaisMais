CREATE DATABASE  IF NOT EXISTS `mytennisapp` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `mytennisapp`;
-- MySQL dump 10.13  Distrib 5.6.17, for osx10.6 (i386)
--
-- Host: 127.0.0.1    Database: mytennisapp
-- ------------------------------------------------------
-- Server version	5.5.34

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
-- Table structure for table `AgeBand`
--

DROP TABLE IF EXISTS `AgeBand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AgeBand` (
  `ageBandID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `maxAge` varchar(45) NOT NULL,
  `minAge` varchar(45) NOT NULL,
  PRIMARY KEY (`ageBandID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AgeBand`
--

LOCK TABLES `AgeBand` WRITE;
/*!40000 ALTER TABLE `AgeBand` DISABLE KEYS */;
/*!40000 ALTER TABLE `AgeBand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AthleteGroup`
--

DROP TABLE IF EXISTS `AthleteGroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AthleteGroup` (
  `athleteGroupID` int(11) NOT NULL,
  `minAge` int(11) DEFAULT NULL,
  `maxAge` int(11) DEFAULT NULL,
  `minPlayerLevel` varchar(45) DEFAULT NULL,
  `maxPlayerLevel` varchar(45) DEFAULT NULL,
  `clubID` int(11) NOT NULL,
  PRIMARY KEY (`athleteGroupID`),
  KEY `fk_AthleteGroup_Club1_idx` (`clubID`),
  CONSTRAINT `fk_AthleteGroup_Club1` FOREIGN KEY (`clubID`) REFERENCES `Club` (`clubID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AthleteGroup`
--

LOCK TABLES `AthleteGroup` WRITE;
/*!40000 ALTER TABLE `AthleteGroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `AthleteGroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Club`
--

DROP TABLE IF EXISTS `Club`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Club` (
  `clubID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `homeID` int(11) NOT NULL,
  `contactID` int(11) NOT NULL,
  `adminUserID` int(11) NOT NULL,
  PRIMARY KEY (`clubID`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `fk_Club_Home1_idx` (`homeID`),
  KEY `fk_Club_Contact1_idx` (`contactID`),
  KEY `fk_Club_User1_idx` (`adminUserID`),
  CONSTRAINT `fk_Club_Contact1` FOREIGN KEY (`contactID`) REFERENCES `Contact` (`contactID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Club_Home1` FOREIGN KEY (`homeID`) REFERENCES `Home` (`homeID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Club_User1` FOREIGN KEY (`adminUserID`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Club`
--

LOCK TABLES `Club` WRITE;
/*!40000 ALTER TABLE `Club` DISABLE KEYS */;
INSERT INTO `Club` VALUES (1,'TestClub',2,3,1);
/*!40000 ALTER TABLE `Club` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ClubHasUser`
--

DROP TABLE IF EXISTS `ClubHasUser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ClubHasUser` (
  `clubHasUserID` int(11) NOT NULL AUTO_INCREMENT,
  `clubID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `userTypeID` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date DEFAULT NULL,
  PRIMARY KEY (`clubHasUserID`),
  UNIQUE KEY `uniqueCombination` (`clubID`,`userID`,`userTypeID`,`startDate`),
  KEY `fk_Club_has_User_User1_idx` (`userID`),
  KEY `fk_Club_has_User_Club1_idx` (`clubID`),
  KEY `fk_UserType1_idx` (`userTypeID`),
  CONSTRAINT `fk_Club_has_User_Club1` FOREIGN KEY (`clubID`) REFERENCES `Club` (`clubID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Club_has_User_User1` FOREIGN KEY (`userID`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_UserType1` FOREIGN KEY (`userTypeID`) REFERENCES `UserType` (`userTypeID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ClubHasUser`
--

LOCK TABLES `ClubHasUser` WRITE;
/*!40000 ALTER TABLE `ClubHasUser` DISABLE KEYS */;
INSERT INTO `ClubHasUser` VALUES (5,1,1,1,'2014-01-01',NULL),(6,1,42,2,'2014-08-10',NULL),(7,1,44,1,'2014-08-10',NULL),(8,1,45,2,'2014-08-10',NULL),(9,1,46,2,'2014-08-10',NULL),(11,1,49,2,'2014-08-10',NULL),(12,1,50,2,'2014-08-10',NULL),(13,1,51,2,'2014-08-10',NULL);
/*!40000 ALTER TABLE `ClubHasUser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CoachLevel`
--

DROP TABLE IF EXISTS `CoachLevel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CoachLevel` (
  `coachLevelID` int(11) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  `group` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`coachLevelID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CoachLevel`
--

LOCK TABLES `CoachLevel` WRITE;
/*!40000 ALTER TABLE `CoachLevel` DISABLE KEYS */;
INSERT INTO `CoachLevel` VALUES (0,'No coach training','Other'),(1,'Level 1 FPT','FPT'),(2,'Level 2 FPT','FPT'),(3,'Level 3 FPT','FPT'),(4,'Other','Other');
/*!40000 ALTER TABLE `CoachLevel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CompetitivePlan`
--

DROP TABLE IF EXISTS `CompetitivePlan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CompetitivePlan` (
  `athleteGroupID` int(11) NOT NULL,
  `federationTournamentID` int(11) NOT NULL,
  PRIMARY KEY (`athleteGroupID`,`federationTournamentID`),
  KEY `fk_CompetitivePlan_AthleteGroup1_idx` (`athleteGroupID`),
  KEY `fk_CompetitivePlan_FederationTournament1_idx` (`federationTournamentID`),
  CONSTRAINT `fk_CompetitivePlan_AthleteGroup1` FOREIGN KEY (`athleteGroupID`) REFERENCES `AthleteGroup` (`athleteGroupID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_CompetitivePlan_FederationTournament1` FOREIGN KEY (`federationTournamentID`) REFERENCES `FederationTournament` (`federationTournamentID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CompetitivePlan`
--

LOCK TABLES `CompetitivePlan` WRITE;
/*!40000 ALTER TABLE `CompetitivePlan` DISABLE KEYS */;
/*!40000 ALTER TABLE `CompetitivePlan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CompetitiveResultHistory`
--

DROP TABLE IF EXISTS `CompetitiveResultHistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CompetitiveResultHistory` (
  `competitiveResultHistoryID` int(11) NOT NULL,
  `winnerUserID` int(11) NOT NULL,
  `loserUserID` int(11) NOT NULL,
  `score` varchar(45) NOT NULL,
  `federationTournamentID` int(11) NOT NULL,
  PRIMARY KEY (`competitiveResultHistoryID`),
  KEY `fk_CompetitiveResultHistory_FederationTournament1_idx` (`federationTournamentID`),
  KEY `fk_Winner_idx` (`winnerUserID`),
  KEY `fk_Loser_idx` (`loserUserID`),
  CONSTRAINT `fk_CompetitiveResultHistory_FederationTournament1` FOREIGN KEY (`federationTournamentID`) REFERENCES `FederationTournament` (`federationTournamentID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Loser` FOREIGN KEY (`loserUserID`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Winner` FOREIGN KEY (`winnerUserID`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CompetitiveResultHistory`
--

LOCK TABLES `CompetitiveResultHistory` WRITE;
/*!40000 ALTER TABLE `CompetitiveResultHistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `CompetitiveResultHistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Contact`
--

DROP TABLE IF EXISTS `Contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Contact` (
  `contactID` int(11) NOT NULL AUTO_INCREMENT,
  `cellularPhone` varchar(45) DEFAULT NULL,
  `workPhone` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  `website` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`contactID`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Contact`
--

LOCK TABLES `Contact` WRITE;
/*!40000 ALTER TABLE `Contact` DISABLE KEYS */;
INSERT INTO `Contact` VALUES (1,'917017687','213130207','neo16@sapo.pt','','http://www.sapo.pt'),(3,NULL,NULL,'teste@club.com',NULL,NULL),(46,'','','athlete1@test.com','',''),(47,'','','sponsor1@test.com','',''),(48,'','','coach1@test.com','',''),(49,'','','athlete2@test.com','',''),(50,'','','athlete3@test.com','',''),(53,'','','athlete4@test.com','',''),(54,'','','athlete5@test.com','',''),(55,'','','athlete6@test.com','',''),(56,'','213128172','sponsor2@test.com','','');
/*!40000 ALTER TABLE `Contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `FederationClub`
--

DROP TABLE IF EXISTS `FederationClub`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FederationClub` (
  `federationClubID` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `phoneNumber` varchar(45) DEFAULT NULL,
  `fax` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`federationClubID`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `FederationClub`
--

LOCK TABLES `FederationClub` WRITE;
/*!40000 ALTER TABLE `FederationClub` DISABLE KEYS */;
/*!40000 ALTER TABLE `FederationClub` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `FederationTournament`
--

DROP TABLE IF EXISTS `FederationTournament`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FederationTournament` (
  `federationTournamentID` int(11) NOT NULL,
  `level` varchar(2) NOT NULL,
  `qualyStartDate` date DEFAULT NULL,
  `qualyEndDate` date DEFAULT NULL,
  `mainDrawStartDate` date NOT NULL,
  `mainDrawEndDate` date NOT NULL,
  `name` varchar(150) NOT NULL,
  `city` varchar(45) NOT NULL,
  `surface` varchar(45) NOT NULL,
  `accommodation` varchar(45) DEFAULT NULL,
  `meals` int(11) DEFAULT NULL,
  `prizeMoney` int(11) DEFAULT NULL,
  `federationClubID` int(11) NOT NULL,
  PRIMARY KEY (`federationTournamentID`),
  KEY `fk_FederationTournament_FederationClub1_idx` (`federationClubID`),
  CONSTRAINT `fk_FederationTournament_FederationClub1` FOREIGN KEY (`federationClubID`) REFERENCES `FederationClub` (`federationClubID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `FederationTournament`
--

LOCK TABLES `FederationTournament` WRITE;
/*!40000 ALTER TABLE `FederationTournament` DISABLE KEYS */;
/*!40000 ALTER TABLE `FederationTournament` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `FederationTournamentHasAgeBand`
--

DROP TABLE IF EXISTS `FederationTournamentHasAgeBand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FederationTournamentHasAgeBand` (
  `federationTournamentID` int(11) NOT NULL,
  `ageBandID` int(11) NOT NULL,
  PRIMARY KEY (`federationTournamentID`,`ageBandID`),
  KEY `fk_FederationTournament_has_AgeBand_AgeBand1_idx` (`ageBandID`),
  KEY `fk_FederationTournament_has_AgeBand_FederationTournament1_idx` (`federationTournamentID`),
  CONSTRAINT `fk_FederationTournament_has_AgeBand_AgeBand1` FOREIGN KEY (`ageBandID`) REFERENCES `AgeBand` (`ageBandID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_FederationTournament_has_AgeBand_FederationTournament1` FOREIGN KEY (`federationTournamentID`) REFERENCES `FederationTournament` (`federationTournamentID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `FederationTournamentHasAgeBand`
--

LOCK TABLES `FederationTournamentHasAgeBand` WRITE;
/*!40000 ALTER TABLE `FederationTournamentHasAgeBand` DISABLE KEYS */;
/*!40000 ALTER TABLE `FederationTournamentHasAgeBand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Home`
--

DROP TABLE IF EXISTS `Home`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Home` (
  `homeID` int(11) NOT NULL AUTO_INCREMENT,
  `phoneNumber` varchar(45) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  `postCode` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`homeID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Home`
--

LOCK TABLES `Home` WRITE;
/*!40000 ALTER TABLE `Home` DISABLE KEYS */;
INSERT INTO `Home` VALUES (1,'','','','Faro'),(2,'','','','Lisboa'),(3,'','','','Porto'),(4,'','','','Porto');
/*!40000 ALTER TABLE `Home` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MainCoach`
--

DROP TABLE IF EXISTS `MainCoach`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MainCoach` (
  `coachID` int(11) NOT NULL,
  `athleteID` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date DEFAULT NULL,
  `clubID` int(11) NOT NULL,
  PRIMARY KEY (`coachID`,`athleteID`,`startDate`),
  KEY `fk_MainCoach_User2_idx` (`athleteID`),
  KEY `fk_MainCoach_Club1_idx` (`clubID`),
  CONSTRAINT `fk_MainCoach_Club1` FOREIGN KEY (`clubID`) REFERENCES `Club` (`clubID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_MainCoach_User1` FOREIGN KEY (`coachID`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_MainCoach_User2` FOREIGN KEY (`athleteID`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MainCoach`
--

LOCK TABLES `MainCoach` WRITE;
/*!40000 ALTER TABLE `MainCoach` DISABLE KEYS */;
/*!40000 ALTER TABLE `MainCoach` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PlayerLevel`
--

DROP TABLE IF EXISTS `PlayerLevel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PlayerLevel` (
  `playerLevelID` int(11) NOT NULL,
  `generalReference` varchar(45) NOT NULL,
  `levelWithinReference` varchar(45) NOT NULL,
  PRIMARY KEY (`playerLevelID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PlayerLevel`
--

LOCK TABLES `PlayerLevel` WRITE;
/*!40000 ALTER TABLE `PlayerLevel` DISABLE KEYS */;
INSERT INTO `PlayerLevel` VALUES (1,'Elite','High Performance'),(2,'Advanced','High'),(3,'Advanced','Medium'),(4,'Advanced','Low'),(5,'Intermediate','High'),(6,'Intermediate','Medium'),(7,'Intermediate','Low'),(8,'Recreational','High'),(9,'Recreational','Low'),(10,'Starter','Starter');
/*!40000 ALTER TABLE `PlayerLevel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PracticeSession`
--

DROP TABLE IF EXISTS `PracticeSession`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PracticeSession` (
  `practiceSessionID` int(11) NOT NULL AUTO_INCREMENT,
  `coachID` int(11) NOT NULL,
  `clubID` int(11) NOT NULL,
  `activePracticeSession` tinyint(1) NOT NULL DEFAULT '1',
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `groupLevel` int(11) DEFAULT NULL,
  `dayOfWeek` int(11) NOT NULL,
  PRIMARY KEY (`practiceSessionID`),
  KEY `fk_PracticeSession_Club1_idx` (`clubID`),
  KEY `fk_PraticeSession_Club2_idx` (`coachID`),
  CONSTRAINT `fk_PracticeSession_Club1` FOREIGN KEY (`clubID`) REFERENCES `Club` (`clubID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PraticeSession_Club2` FOREIGN KEY (`coachID`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PracticeSession`
--

LOCK TABLES `PracticeSession` WRITE;
/*!40000 ALTER TABLE `PracticeSession` DISABLE KEYS */;
/*!40000 ALTER TABLE `PracticeSession` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PracticeSessionHasAthlete`
--

DROP TABLE IF EXISTS `PracticeSessionHasAthlete`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PracticeSessionHasAthlete` (
  `practiceSessionID` int(11) NOT NULL,
  `athleteID` int(11) NOT NULL,
  PRIMARY KEY (`practiceSessionID`,`athleteID`),
  KEY `fk_PracticeSession_has_User_User1_idx` (`athleteID`),
  KEY `fk_PracticeSession_has_User_PracticeSession1_idx` (`practiceSessionID`),
  CONSTRAINT `fk_PracticeSession_has_User_PracticeSession1` FOREIGN KEY (`practiceSessionID`) REFERENCES `PracticeSession` (`practiceSessionID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PracticeSession_has_User_User1` FOREIGN KEY (`athleteID`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PracticeSessionHasAthlete`
--

LOCK TABLES `PracticeSessionHasAthlete` WRITE;
/*!40000 ALTER TABLE `PracticeSessionHasAthlete` DISABLE KEYS */;
/*!40000 ALTER TABLE `PracticeSessionHasAthlete` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PracticeSessionHistory`
--

DROP TABLE IF EXISTS `PracticeSessionHistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PracticeSessionHistory` (
  `practiceSessionHistoryID` int(11) NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `date` date NOT NULL,
  `coachID` int(11) NOT NULL,
  `clubID` int(11) NOT NULL,
  PRIMARY KEY (`practiceSessionHistoryID`),
  KEY `fk_PracticeSessionHistory_User1_idx` (`coachID`),
  KEY `fk_PracticeSessionHistory_Club1_idx` (`clubID`),
  CONSTRAINT `fk_PracticeSessionHistory_Club1` FOREIGN KEY (`clubID`) REFERENCES `Club` (`clubID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PracticeSessionHistory_User1` FOREIGN KEY (`coachID`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PracticeSessionHistory`
--

LOCK TABLES `PracticeSessionHistory` WRITE;
/*!40000 ALTER TABLE `PracticeSessionHistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `PracticeSessionHistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PracticeSessionHistoryHasAthlete`
--

DROP TABLE IF EXISTS `PracticeSessionHistoryHasAthlete`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PracticeSessionHistoryHasAthlete` (
  `practiceSessionHistoryID` int(11) NOT NULL,
  `athleteID` int(11) NOT NULL,
  `attendanceType` int(11) NOT NULL,
  PRIMARY KEY (`practiceSessionHistoryID`,`athleteID`),
  KEY `fk_PracticeSessionHistory_has_User_User1_idx` (`athleteID`),
  KEY `fk_PracticeSessionHistory_has_User_PracticeSessionHistory1_idx` (`practiceSessionHistoryID`),
  CONSTRAINT `fk_PracticeSessionHistory_has_User_PracticeSessionHistory1` FOREIGN KEY (`practiceSessionHistoryID`) REFERENCES `PracticeSessionHistory` (`practiceSessionHistoryID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_PracticeSessionHistory_has_User_User1` FOREIGN KEY (`athleteID`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PracticeSessionHistoryHasAthlete`
--

LOCK TABLES `PracticeSessionHistoryHasAthlete` WRITE;
/*!40000 ALTER TABLE `PracticeSessionHistoryHasAthlete` DISABLE KEYS */;
/*!40000 ALTER TABLE `PracticeSessionHistoryHasAthlete` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Sponsor`
--

DROP TABLE IF EXISTS `Sponsor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Sponsor` (
  `sponsorID` int(11) NOT NULL,
  `athleteID` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `relationshipType` int(11) NOT NULL,
  `endDate` date DEFAULT NULL,
  PRIMARY KEY (`sponsorID`,`athleteID`,`startDate`),
  KEY `fk_Sponsor_User2_idx` (`athleteID`),
  KEY `fk_Sponsor_relationshipType_idx` (`relationshipType`),
  CONSTRAINT `fk_Sponsor_relationshipType` FOREIGN KEY (`relationshipType`) REFERENCES `SponsorAthleteRelationshipType` (`relationshipTypeId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Sponsor_User1` FOREIGN KEY (`sponsorID`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Sponsor_User2` FOREIGN KEY (`athleteID`) REFERENCES `User` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Sponsor`
--

LOCK TABLES `Sponsor` WRITE;
/*!40000 ALTER TABLE `Sponsor` DISABLE KEYS */;
INSERT INTO `Sponsor` VALUES (43,42,'2014-08-10',1,NULL),(52,51,'2014-08-10',3,NULL);
/*!40000 ALTER TABLE `Sponsor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SponsorAthleteRelationshipType`
--

DROP TABLE IF EXISTS `SponsorAthleteRelationshipType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SponsorAthleteRelationshipType` (
  `relationshipTypeId` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(25) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`relationshipTypeId`),
  UNIQUE KEY `label_UNIQUE` (`label`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SponsorAthleteRelationshipType`
--

LOCK TABLES `SponsorAthleteRelationshipType` WRITE;
/*!40000 ALTER TABLE `SponsorAthleteRelationshipType` DISABLE KEYS */;
INSERT INTO `SponsorAthleteRelationshipType` VALUES (1,'Parent',NULL),(2,'Grand-parent',NULL),(3,'Sibling',NULL),(4,'Aunt/Uncle',NULL),(5,'Sponsor',NULL),(6,'Other (related)',NULL),(7,'Other (non-related)',NULL);
/*!40000 ALTER TABLE `SponsorAthleteRelationshipType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TournamentType`
--

DROP TABLE IF EXISTS `TournamentType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TournamentType` (
  `variation` varchar(45) NOT NULL,
  `gender` varchar(45) NOT NULL,
  `federationTournamentID` int(11) NOT NULL,
  PRIMARY KEY (`variation`,`gender`,`federationTournamentID`),
  KEY `fk_TournamentType_FederationTournament1_idx` (`federationTournamentID`),
  CONSTRAINT `fk_TournamentType_FederationTournament1` FOREIGN KEY (`federationTournamentID`) REFERENCES `FederationTournament` (`federationTournamentID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TournamentType`
--

LOCK TABLES `TournamentType` WRITE;
/*!40000 ALTER TABLE `TournamentType` DISABLE KEYS */;
/*!40000 ALTER TABLE `TournamentType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `contactID` int(11) DEFAULT NULL,
  `homeID` int(11) DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  `password` varchar(60) DEFAULT NULL,
  `birthDate` date DEFAULT NULL,
  `federationNumber` varchar(45) DEFAULT NULL,
  `coachLevelID` int(11) DEFAULT NULL,
  `playerLevelID` int(11) DEFAULT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `activationHash` varchar(512) DEFAULT NULL,
  `activationMailSent` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userID`),
  UNIQUE KEY `contactID_UNIQUE` (`contactID`),
  KEY `fk_User_Home1_idx` (`homeID`),
  KEY `fk_User_Contact1_idx` (`contactID`),
  KEY `fk_User_PlayerLevel_idx` (`playerLevelID`),
  KEY `fk_User_CoachLevel_idx` (`coachLevelID`),
  CONSTRAINT `fk_User_PlayerLevel` FOREIGN KEY (`playerLevelID`) REFERENCES `PlayerLevel` (`playerLevelID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_CoachLevel` FOREIGN KEY (`coachLevelID`) REFERENCES `CoachLevel` (`coachLevelId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_Contact1` FOREIGN KEY (`contactID`) REFERENCES `Contact` (`contactID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_Home1` FOREIGN KEY (`homeID`) REFERENCES `Home` (`homeID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (1,1,NULL,'Diogo Neves','$2a$13$H89.4zid3il2Su3o37MOQ.S9lEojloBjogT/PdOOjwTiYhHIn7NEW','1986-05-23','24214',NULL,NULL,1,'$2a$13$GQzn9i0qJBjBwA2vx95.zL',1),(42,46,NULL,'Athlete 1','$2a$13$MuFdxkHYJgoWdwgBy/a/1uotruHFXkp3z.Ty0I8gzUHD/NdMNETlW','2008-08-05',NULL,NULL,NULL,1,'$2a$13$YakPrYgoQ5qNGNr4N9kZ/K',1),(43,47,NULL,'Sponsor 1','$2a$13$vVlp4RLC.6bQ4C7XOaUcdeNb2onR9M8DW7/aFpRxFsDaRW5/mrr/C','1986-08-21',NULL,NULL,NULL,1,'$2a$13$ECTlz3e0Ekck/evGBbijGj',1),(44,48,NULL,'Coach 1','$2a$13$qa3z/W/7R4kR4cY8weWuce2yapB/u9YBq3PrcJ/UjOwLwPHHNmZr.','1980-08-13',NULL,NULL,NULL,1,'$2a$13$VmPqGTrZ5jyWfzNew29Ryg',1),(45,49,NULL,'Athlete 2',NULL,NULL,NULL,NULL,NULL,0,'$2a$13$FihwY7DAaGNTLlEPcXk6/3',1),(46,50,NULL,'Athlete 3',NULL,NULL,NULL,NULL,NULL,0,'$2a$13$LdLyRaeoAASoRDSZcIcGdu',1),(49,53,NULL,'Athlete 4',NULL,NULL,NULL,NULL,NULL,0,'$2a$13$tGAMSHOy5DOiFOddWCDadW',1),(50,54,NULL,'Athlete 5',NULL,NULL,NULL,NULL,NULL,0,'$2a$13$878Cf1ZahInUNKLWi.1me7',1),(51,55,NULL,'Athlete 6',NULL,NULL,NULL,NULL,NULL,0,'$2a$13$Okzx36mCs3muCy5ncyZP0Z',1),(52,56,NULL,'Sponsor 2',NULL,NULL,NULL,NULL,NULL,0,'$2a$13$bJMs9fJ096m0X.1RTl8k8i',1);
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `UserType`
--

DROP TABLE IF EXISTS `UserType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `UserType` (
  `userTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  `description` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`userTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `UserType`
--

LOCK TABLES `UserType` WRITE;
/*!40000 ALTER TABLE `UserType` DISABLE KEYS */;
INSERT INTO `UserType` VALUES (1,'coach','coach or technical director'),(2,'athlete',NULL),(3,'sponsor','sponsor or relative of the athlete');
/*!40000 ALTER TABLE `UserType` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-08-15 18:31:17
