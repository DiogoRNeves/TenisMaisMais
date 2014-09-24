SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mytennisapp
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `u899625820_cert` DEFAULT CHARACTER SET utf8 ;
USE `u899625820_cert` ;

-- -----------------------------------------------------
-- Table `u899625820_cert`.`Home`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`Home` (
  `homeID` INT NOT NULL AUTO_INCREMENT,
  `phoneNumber` VARCHAR(45) NULL,
  `address` VARCHAR(45) NULL,
  `postCode` VARCHAR(45) NULL,
  `city` VARCHAR(45) NULL,
  PRIMARY KEY (`homeID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`Contact`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`Contact` (
  `contactID` INT NOT NULL AUTO_INCREMENT,
  `cellularPhone` VARCHAR(45) NULL,
  `workPhone` VARCHAR(45) NULL,
  `email` VARCHAR(45) NULL,
  `fax` VARCHAR(45) NULL,
  `website` VARCHAR(45) NULL,
  PRIMARY KEY (`contactID`),
  UNIQUE INDEX `cellularPhone_UNIQUE` (`cellularPhone` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`PlayerLevel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`PlayerLevel` (
  `playerLevelID` INT NOT NULL,
  `generalReference` VARCHAR(45) NOT NULL,
  `levelWithinReference` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`playerLevelID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`CoachLevel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`CoachLevel` (
  `coachLevelID` INT NOT NULL,
  `description` VARCHAR(45) NULL,
  `group` VARCHAR(45) NULL,
  PRIMARY KEY (`coachLevelID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`User`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`User` (
  `userID` INT NOT NULL AUTO_INCREMENT,
  `contactID` INT NULL,
  `homeID` INT NULL,
  `name` VARCHAR(45) NOT NULL,
  `password` VARCHAR(60) NULL,
  `birthDate` DATE NULL,
  `federationNumber` VARCHAR(45) NULL,
  `coachLevelID` INT NULL,
  `playerLevelID` INT NULL,
  `activated` TINYINT(1) NOT NULL DEFAULT 0,
  `activationHash` VARCHAR(512) NULL,
  `activationMailSent` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`userID`),
  INDEX `fk_User_Home1_idx` (`homeID` ASC),
  INDEX `fk_User_Contact1_idx` (`contactID` ASC),
  UNIQUE INDEX `contactID_UNIQUE` (`contactID` ASC),
  INDEX `fk_User_PlayerLevel_idx` (`playerLevelID` ASC),
  INDEX `fk_User_CoachLevel_idx` (`coachLevelID` ASC),
  CONSTRAINT `fk_User_Home1`
    FOREIGN KEY (`homeID`)
    REFERENCES `u899625820_cert`.`Home` (`homeID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_Contact1`
    FOREIGN KEY (`contactID`)
    REFERENCES `u899625820_cert`.`Contact` (`contactID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_PlayerLevel`
    FOREIGN KEY (`playerLevelID`)
    REFERENCES `u899625820_cert`.`PlayerLevel` (`playerLevelID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_CoachLevel`
    FOREIGN KEY (`coachLevelID`)
    REFERENCES `u899625820_cert`.`CoachLevel` (`coachLevelID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`SponsorAthleteRelationshipType`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`SponsorAthleteRelationshipType` (
  `relationshipTypeId` INT NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(25) NOT NULL,
  `description` VARCHAR(100) NULL,
  PRIMARY KEY (`relationshipTypeId`),
  UNIQUE INDEX `label_UNIQUE` (`label` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`Sponsor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`Sponsor` (
  `sponsorID` INT NOT NULL,
  `athleteID` INT NOT NULL,
  `startDate` DATE NOT NULL,
  `relationshipType` INT NOT NULL,
  `endDate` DATE NULL,
  PRIMARY KEY (`sponsorID`, `athleteID`, `startDate`),
  INDEX `fk_Sponsor_User2_idx` (`athleteID` ASC),
  INDEX `fk_Sponsor_relationshipType_idx` (`relationshipType` ASC),
  CONSTRAINT `fk_Sponsor_User1`
    FOREIGN KEY (`sponsorID`)
    REFERENCES `u899625820_cert`.`User` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Sponsor_User2`
    FOREIGN KEY (`athleteID`)
    REFERENCES `u899625820_cert`.`User` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Sponsor_relationshipType`
    FOREIGN KEY (`relationshipType`)
    REFERENCES `u899625820_cert`.`SponsorAthleteRelationshipType` (`relationshipTypeId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`Club`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`Club` (
  `clubID` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `homeID` INT NOT NULL,
  `contactID` INT NOT NULL,
  `adminUserID` INT NOT NULL,
  PRIMARY KEY (`clubID`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  INDEX `fk_Club_Home1_idx` (`homeID` ASC),
  INDEX `fk_Club_Contact1_idx` (`contactID` ASC),
  INDEX `fk_Club_User1_idx` (`adminUserID` ASC),
  CONSTRAINT `fk_Club_Home1`
    FOREIGN KEY (`homeID`)
    REFERENCES `u899625820_cert`.`Home` (`homeID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Club_Contact1`
    FOREIGN KEY (`contactID`)
    REFERENCES `u899625820_cert`.`Contact` (`contactID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Club_User1`
    FOREIGN KEY (`adminUserID`)
    REFERENCES `u899625820_cert`.`User` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`UserType`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`UserType` (
  `userTypeID` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(15) NOT NULL,
  `description` VARCHAR(150) NULL,
  PRIMARY KEY (`userTypeID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`ClubHasUser`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`ClubHasUser` (
  `clubHasUserID` INT NOT NULL AUTO_INCREMENT,
  `clubID` INT NOT NULL,
  `userID` INT NOT NULL,
  `userTypeID` INT NOT NULL,
  `startDate` DATE NOT NULL,
  `endDate` DATE NULL,
  INDEX `fk_Club_has_User_User1_idx` (`userID` ASC),
  INDEX `fk_Club_has_User_Club1_idx` (`clubID` ASC),
  PRIMARY KEY (`clubHasUserID`),
  UNIQUE INDEX `uniqueCombination` (`clubID` ASC, `userID` ASC, `userTypeID` ASC, `startDate` ASC),
  INDEX `fk_UserType1_idx` (`userTypeID` ASC),
  CONSTRAINT `fk_Club_has_User_Club1`
    FOREIGN KEY (`clubID`)
    REFERENCES `u899625820_cert`.`Club` (`clubID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Club_has_User_User1`
    FOREIGN KEY (`userID`)
    REFERENCES `u899625820_cert`.`User` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_UserType1`
    FOREIGN KEY (`userTypeID`)
    REFERENCES `u899625820_cert`.`UserType` (`userTypeID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`MainCoach`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`MainCoach` (
  `coachID` INT NOT NULL,
  `athleteID` INT NOT NULL,
  `startDate` DATE NOT NULL,
  `endDate` DATE NULL,
  `clubID` INT NOT NULL,
  PRIMARY KEY (`coachID`, `athleteID`, `startDate`),
  INDEX `fk_MainCoach_User2_idx` (`athleteID` ASC),
  INDEX `fk_MainCoach_Club1_idx` (`clubID` ASC),
  CONSTRAINT `fk_MainCoach_User1`
    FOREIGN KEY (`coachID`)
    REFERENCES `u899625820_cert`.`User` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_MainCoach_User2`
    FOREIGN KEY (`athleteID`)
    REFERENCES `u899625820_cert`.`User` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_MainCoach_Club1`
    FOREIGN KEY (`clubID`)
    REFERENCES `u899625820_cert`.`Club` (`clubID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`PracticeSession`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`PracticeSession` (
  `practiceSessionID` INT NOT NULL AUTO_INCREMENT,
  `coachID` INT NOT NULL,
  `clubID` INT NOT NULL,
  `activePracticeSession` TINYINT(1) NOT NULL DEFAULT TRUE,
  `startTime` TIME NOT NULL,
  `endTime` TIME NOT NULL,
  `groupLevel` INT NULL,
  `dayOfWeek` INT NOT NULL,
  PRIMARY KEY (`practiceSessionID`),
  INDEX `fk_PracticeSession_Club1_idx` (`clubID` ASC),
  INDEX `fk_PraticeSession_Club2_idx` (`coachID` ASC),
  INDEX `fk_PraticeSession_PlayerLevel_idx` (`groupLevel` ASC),
  CONSTRAINT `fk_PracticeSession_Club1`
    FOREIGN KEY (`clubID`)
    REFERENCES `u899625820_cert`.`Club` (`clubID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_PraticeSession_Club2`
    FOREIGN KEY (`coachID`)
    REFERENCES `u899625820_cert`.`User` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_PraticeSession_PlayerLevel`
    FOREIGN KEY (`groupLevel`)
    REFERENCES `u899625820_cert`.`PlayerLevel` (`playerLevelID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`PracticeSessionHasAthlete`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`PracticeSessionHasAthlete` (
  `practiceSessionID` INT NOT NULL,
  `athleteID` INT NOT NULL,
  PRIMARY KEY (`practiceSessionID`, `athleteID`),
  INDEX `fk_PracticeSession_has_User_User1_idx` (`athleteID` ASC),
  INDEX `fk_PracticeSession_has_User_PracticeSession1_idx` (`practiceSessionID` ASC),
  CONSTRAINT `fk_PracticeSession_has_User_PracticeSession1`
    FOREIGN KEY (`practiceSessionID`)
    REFERENCES `u899625820_cert`.`PracticeSession` (`practiceSessionID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_PracticeSession_has_User_User1`
    FOREIGN KEY (`athleteID`)
    REFERENCES `u899625820_cert`.`User` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`PracticeSessionHistory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`PracticeSessionHistory` (
  `practiceSessionHistoryID` INT NOT NULL,
  `startTime` TIME NOT NULL,
  `endTime` TIME NOT NULL,
  `date` DATE NOT NULL,
  `coachID` INT NOT NULL,
  `clubID` INT NOT NULL,
  PRIMARY KEY (`practiceSessionHistoryID`),
  INDEX `fk_PracticeSessionHistory_User1_idx` (`coachID` ASC),
  INDEX `fk_PracticeSessionHistory_Club1_idx` (`clubID` ASC),
  CONSTRAINT `fk_PracticeSessionHistory_User1`
    FOREIGN KEY (`coachID`)
    REFERENCES `u899625820_cert`.`User` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_PracticeSessionHistory_Club1`
    FOREIGN KEY (`clubID`)
    REFERENCES `u899625820_cert`.`Club` (`clubID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`PracticeSessionHistoryHasAthlete`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`PracticeSessionHistoryHasAthlete` (
  `practiceSessionHistoryID` INT NOT NULL,
  `athleteID` INT NOT NULL,
  `attendanceType` INT NOT NULL,
  PRIMARY KEY (`practiceSessionHistoryID`, `athleteID`),
  INDEX `fk_PracticeSessionHistory_has_User_User1_idx` (`athleteID` ASC),
  INDEX `fk_PracticeSessionHistory_has_User_PracticeSessionHistory1_idx` (`practiceSessionHistoryID` ASC),
  CONSTRAINT `fk_PracticeSessionHistory_has_User_PracticeSessionHistory1`
    FOREIGN KEY (`practiceSessionHistoryID`)
    REFERENCES `u899625820_cert`.`PracticeSessionHistory` (`practiceSessionHistoryID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_PracticeSessionHistory_has_User_User1`
    FOREIGN KEY (`athleteID`)
    REFERENCES `u899625820_cert`.`User` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`FederationClub`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`FederationClub` (
  `federationClubID` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `phoneNumber` VARCHAR(45) NULL,
  `fax` VARCHAR(45) NULL,
  PRIMARY KEY (`federationClubID`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`FederationTournament`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`FederationTournament` (
  `federationTournamentID` INT NOT NULL,
  `level` VARCHAR(2) NOT NULL,
  `qualyStartDate` DATE NULL,
  `qualyEndDate` DATE NULL,
  `mainDrawStartDate` DATE NOT NULL,
  `mainDrawEndDate` DATE NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `city` VARCHAR(45) NOT NULL,
  `surface` VARCHAR(45) NOT NULL,
  `accommodation` VARCHAR(45) NULL,
  `meals` INT NULL,
  `prizeMoney` INT NULL,
  `federationClubID` INT NOT NULL,
  PRIMARY KEY (`federationTournamentID`),
  INDEX `fk_FederationTournament_FederationClub1_idx` (`federationClubID` ASC),
  CONSTRAINT `fk_FederationTournament_FederationClub1`
    FOREIGN KEY (`federationClubID`)
    REFERENCES `u899625820_cert`.`FederationClub` (`federationClubID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`AgeBand`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`AgeBand` (
  `ageBandID` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `maxAge` VARCHAR(45) NOT NULL,
  `minAge` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`ageBandID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`TournamentType`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`TournamentType` (
  `variation` VARCHAR(45) NOT NULL,
  `gender` VARCHAR(45) NOT NULL,
  `federationTournamentID` INT NOT NULL,
  INDEX `fk_TournamentType_FederationTournament1_idx` (`federationTournamentID` ASC),
  PRIMARY KEY (`variation`, `gender`, `federationTournamentID`),
  CONSTRAINT `fk_TournamentType_FederationTournament1`
    FOREIGN KEY (`federationTournamentID`)
    REFERENCES `u899625820_cert`.`FederationTournament` (`federationTournamentID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`FederationTournamentHasAgeBand`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`FederationTournamentHasAgeBand` (
  `federationTournamentID` INT NOT NULL,
  `ageBandID` INT NOT NULL,
  PRIMARY KEY (`federationTournamentID`, `ageBandID`),
  INDEX `fk_FederationTournament_has_AgeBand_AgeBand1_idx` (`ageBandID` ASC),
  INDEX `fk_FederationTournament_has_AgeBand_FederationTournament1_idx` (`federationTournamentID` ASC),
  CONSTRAINT `fk_FederationTournament_has_AgeBand_FederationTournament1`
    FOREIGN KEY (`federationTournamentID`)
    REFERENCES `u899625820_cert`.`FederationTournament` (`federationTournamentID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_FederationTournament_has_AgeBand_AgeBand1`
    FOREIGN KEY (`ageBandID`)
    REFERENCES `u899625820_cert`.`AgeBand` (`ageBandID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`AthleteGroup`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`AthleteGroup` (
  `athleteGroupID` INT NOT NULL,
  `minAge` INT NULL,
  `maxAge` INT NULL,
  `minPlayerLevel` VARCHAR(45) NULL,
  `maxPlayerLevel` VARCHAR(45) NULL,
  `clubID` INT NOT NULL,
  PRIMARY KEY (`athleteGroupID`),
  INDEX `fk_AthleteGroup_Club1_idx` (`clubID` ASC),
  CONSTRAINT `fk_AthleteGroup_Club1`
    FOREIGN KEY (`clubID`)
    REFERENCES `u899625820_cert`.`Club` (`clubID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`CompetitivePlan`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`CompetitivePlan` (
  `athleteGroupID` INT NOT NULL,
  `federationTournamentID` INT NOT NULL,
  INDEX `fk_CompetitivePlan_AthleteGroup1_idx` (`athleteGroupID` ASC),
  INDEX `fk_CompetitivePlan_FederationTournament1_idx` (`federationTournamentID` ASC),
  PRIMARY KEY (`athleteGroupID`, `federationTournamentID`),
  CONSTRAINT `fk_CompetitivePlan_AthleteGroup1`
    FOREIGN KEY (`athleteGroupID`)
    REFERENCES `u899625820_cert`.`AthleteGroup` (`athleteGroupID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_CompetitivePlan_FederationTournament1`
    FOREIGN KEY (`federationTournamentID`)
    REFERENCES `u899625820_cert`.`FederationTournament` (`federationTournamentID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `u899625820_cert`.`CompetitiveResultHistory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `u899625820_cert`.`CompetitiveResultHistory` (
  `competitiveResultHistoryID` INT NOT NULL,
  `winnerUserID` INT NOT NULL,
  `loserUserID` INT NOT NULL,
  `score` VARCHAR(45) NOT NULL,
  `federationTournamentID` INT NOT NULL,
  PRIMARY KEY (`competitiveResultHistoryID`),
  INDEX `fk_CompetitiveResultHistory_FederationTournament1_idx` (`federationTournamentID` ASC),
  INDEX `fk_Winner_idx` (`winnerUserID` ASC),
  INDEX `fk_Loser_idx` (`loserUserID` ASC),
  CONSTRAINT `fk_CompetitiveResultHistory_FederationTournament1`
    FOREIGN KEY (`federationTournamentID`)
    REFERENCES `u899625820_cert`.`FederationTournament` (`federationTournamentID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Winner`
    FOREIGN KEY (`winnerUserID`)
    REFERENCES `u899625820_cert`.`User` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Loser`
    FOREIGN KEY (`loserUserID`)
    REFERENCES `u899625820_cert`.`User` (`userID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `u899625820_cert`.`PlayerLevel`
-- -----------------------------------------------------
START TRANSACTION;
USE `u899625820_cert`;
INSERT INTO `u899625820_cert`.`PlayerLevel` (`playerLevelID`, `generalReference`, `levelWithinReference`) VALUES (1, 'Elite', 'Alto Desempenho');
INSERT INTO `u899625820_cert`.`PlayerLevel` (`playerLevelID`, `generalReference`, `levelWithinReference`) VALUES (2, 'Avançado', 'Alto');
INSERT INTO `u899625820_cert`.`PlayerLevel` (`playerLevelID`, `generalReference`, `levelWithinReference`) VALUES (3, 'Avançado', 'Médio');
INSERT INTO `u899625820_cert`.`PlayerLevel` (`playerLevelID`, `generalReference`, `levelWithinReference`) VALUES (4, 'Avançado', 'Baixo');
INSERT INTO `u899625820_cert`.`PlayerLevel` (`playerLevelID`, `generalReference`, `levelWithinReference`) VALUES (5, 'Intermédio', 'Alto');
INSERT INTO `u899625820_cert`.`PlayerLevel` (`playerLevelID`, `generalReference`, `levelWithinReference`) VALUES (6, 'Intermédio', 'Médio');
INSERT INTO `u899625820_cert`.`PlayerLevel` (`playerLevelID`, `generalReference`, `levelWithinReference`) VALUES (7, 'Intermédio', 'Baixo');
INSERT INTO `u899625820_cert`.`PlayerLevel` (`playerLevelID`, `generalReference`, `levelWithinReference`) VALUES (8, 'Recreativo', 'Alto');
INSERT INTO `u899625820_cert`.`PlayerLevel` (`playerLevelID`, `generalReference`, `levelWithinReference`) VALUES (9, 'Recreativo', 'Baixo');
INSERT INTO `u899625820_cert`.`PlayerLevel` (`playerLevelID`, `generalReference`, `levelWithinReference`) VALUES (10, 'Iniciante', 'Iniciante');

COMMIT;


-- -----------------------------------------------------
-- Data for table `u899625820_cert`.`CoachLevel`
-- -----------------------------------------------------
START TRANSACTION;
USE `u899625820_cert`;
INSERT INTO `u899625820_cert`.`CoachLevel` (`coachLevelID`, `description`, `group`) VALUES (0, 'Sem certificado', 'Outro');
INSERT INTO `u899625820_cert`.`CoachLevel` (`coachLevelID`, `description`, `group`) VALUES (1, 'Nível 1 FPT', 'FPT');
INSERT INTO `u899625820_cert`.`CoachLevel` (`coachLevelID`, `description`, `group`) VALUES (2, 'Nível 2 FPT', 'FPT');
INSERT INTO `u899625820_cert`.`CoachLevel` (`coachLevelID`, `description`, `group`) VALUES (3, 'Nível 3 FPT', 'FPT');
INSERT INTO `u899625820_cert`.`CoachLevel` (`coachLevelID`, `description`, `group`) VALUES (4, 'Outro', 'Outro');

COMMIT;


-- -----------------------------------------------------
-- Data for table `u899625820_cert`.`SponsorAthleteRelationshipType`
-- -----------------------------------------------------
START TRANSACTION;
USE `u899625820_cert`;
INSERT INTO `u899625820_cert`.`SponsorAthleteRelationshipType` (`relationshipTypeId`, `label`, `description`) VALUES (NULL, 'Pai/Mãe', NULL);
INSERT INTO `u899625820_cert`.`SponsorAthleteRelationshipType` (`relationshipTypeId`, `label`, `description`) VALUES (NULL, 'Avô/Avó', NULL);
INSERT INTO `u899625820_cert`.`SponsorAthleteRelationshipType` (`relationshipTypeId`, `label`, `description`) VALUES (NULL, 'Irmão/Irmã', NULL);
INSERT INTO `u899625820_cert`.`SponsorAthleteRelationshipType` (`relationshipTypeId`, `label`, `description`) VALUES (NULL, 'Tio/Tia', NULL);
INSERT INTO `u899625820_cert`.`SponsorAthleteRelationshipType` (`relationshipTypeId`, `label`, `description`) VALUES (NULL, 'Patrocinador', NULL);
INSERT INTO `u899625820_cert`.`SponsorAthleteRelationshipType` (`relationshipTypeId`, `label`, `description`) VALUES (NULL, 'Outro (Familiar)', NULL);
INSERT INTO `u899625820_cert`.`SponsorAthleteRelationshipType` (`relationshipTypeId`, `label`, `description`) VALUES (NULL, 'Outro (Não Familiar)', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `u899625820_cert`.`UserType`
-- -----------------------------------------------------
START TRANSACTION;
USE `u899625820_cert`;
INSERT INTO `u899625820_cert`.`UserType` (`userTypeID`, `name`, `description`) VALUES (NULL, 'treinador', 'treinador ou diretor técnico');
INSERT INTO `u899625820_cert`.`UserType` (`userTypeID`, `name`, `description`) VALUES (NULL, 'atleta', NULL);
INSERT INTO `u899625820_cert`.`UserType` (`userTypeID`, `name`, `description`) VALUES (NULL, 'patrocinador', 'patrocinador ou familiar do atleta');

COMMIT;

-- -----------------------------------------------------
-- Data for table `mytennisapp`.`Contact`
-- -----------------------------------------------------
START TRANSACTION;
USE `u899625820_cert`;
INSERT INTO `u899625820_cert`.`Contact` (`contactID`, `cellularPhone`, `workPhone`, `email`, `fax`, `website`) VALUES (1, NULL, NULL, 'admin', NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `mytennisapp`.`User`
-- Password is admin
-- -----------------------------------------------------
START TRANSACTION;
USE `u899625820_cert`;
INSERT INTO `u899625820_cert`.`User` (`userID`, `contactID`, `homeID`, `name`, `password`, `birthDate`, `federationNumber`, `coachLevelID`, `playerLevelID`, `activated`, `activationHash`, `activationMailSent`) VALUES (1, 1, NULL, 'admin', '$1$FOsjRjOg$qB12QZLUru9y9yNqc3nL9/', NULL, NULL, NULL, NULL, 1, NULL, 1);

COMMIT;
