ALTER TABLE mytennisapp.AthleteGroup
    ADD COLUMN friendlyName VARCHAR(60) NOT NULL,
    ADD COLUMN includeMale BOOLEAN NOT NULL,
    ADD COLUMN includeFemale BOOLEAN NOT NULL,
    DROP COLUMN minPlayerLevel,
    ADD COLUMN minPlayerLevelID INT,
    ADD INDEX fk_AthleteGroup_Club2_idx (minPlayerLevelID ASC),
    ADD CONSTRAINT fk_AthleteGroup_Club2
    FOREIGN KEY (minPlayerLevelID) REFERENCES PlayerLevel(playerLevelID)
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    DROP COLUMN maxPlayerLevel,
    ADD COLUMN maxPlayerLevelID INT,
    ADD INDEX fk_AthleteGroup_Club3_idx (maxPlayerLevelID ASC),
    ADD CONSTRAINT fk_AthleteGroup_Club3
    FOREIGN KEY (maxPlayerLevelID) REFERENCES PlayerLevel(playerLevelID)
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    ADD COLUMN active BOOLEAN NOT NULL DEFAULT 1;

ALTER TABLE mytennisapp.AgeBand
    MODIFY COLUMN minAge INT,
    MODIFY COLUMN maxAge INT;

ALTER TABLE mytennisapp.TournamentType
    CHANGE gender male BOOLEAN;

ALTER TABLE mytennisapp.FederationClub
MODIFY COLUMN federationClubID INT AUTO_INCREMENT;

ALTER TABLE mytennisapp.FederationTournament
MODIFY COLUMN city VARCHAR(150);

CREATE TABLE mytennisapp.TournamentVariation (
    tournamentVariationID INT AUTO_INCREMENT PRIMARY KEY,
    abbreviation VARCHAR(3) UNIQUE NOT NULL,
    text VARCHAR(50) UNIQUE NOT NULL,
    singles BOOLEAN NOT NULL,
    allowMale BOOLEAN NOT NULL,
    allowFemale BOOLEAN NOT NULL,
    CONSTRAINT idx_unique_combo UNIQUE (singles, allowFemale, allowMale)
);

ALTER TABLE mytennisapp.FederationTournamentHasAgeBand
    ADD COLUMN tournamentVariationID INT NOT NULL,
    ADD CONSTRAINT fk_FederationTournament_has_AgeBand_TournamentVariation1_idx
        FOREIGN KEY (tournamentVariationID)
        REFERENCES TournamentVariation(tournamentVariationID),
    DROP PRIMARY KEY,
    ADD PRIMARY KEY (federationTournamentID,ageBandID,tournamentVariationID);

DROP TABLE mytennisapp.TournamentType;

CREATE TABLE mytennisapp.AgeBandGroup (
    ageBandGroupID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL
);

ALTER TABLE mytennisapp.AgeBand
    MODIFY COLUMN ageBandID INT AUTO_INCREMENT,
    ADD COLUMN ageBandGroupID INT NOT NULL,
    ADD CONSTRAINT fk_AgeBand_AgeBandGroup1_idx
        FOREIGN KEY (ageBandGroupID)
        REFERENCES AgeBandGroup(ageBandGroupID);
