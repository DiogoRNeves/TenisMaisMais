# adicionar campo 'male' na tabela user e definir o default como true
ALTER TABLE mytennisapp.User ADD male BOOL DEFAULT 1 NOT NULL;

#criar tabela dos tipos de presença
CREATE TABLE mytennisapp.PracticeSessionAttendanceType
(
  attendanceTypeID int PRIMARY KEY NOT NULL,
  description VARCHAR(45) NOT NULL UNIQUE
);

#inserts for table jut created
INSERT INTO mytennisapp.PracticeSessionAttendanceType (attendanceTypeID,description) VALUES (1,'presença');
INSERT INTO mytennisapp.PracticeSessionAttendanceType (attendanceTypeID,description) VALUES (2,'ausência justificada');
INSERT INTO mytennisapp.PracticeSessionAttendanceType (attendanceTypeID,description) VALUES (3,'ausência injustificada');

#mudar o nome do campo na tabela que vai receber o ID
ALTER TABLE mytennisapp.PracticeSessionHistoryHasAthlete CHANGE attendanceType attendanceTypeID INT;

#indicar que este campo é chave estrangeira
ALTER TABLE mytennisapp.PracticeSessionHistoryHasAthlete ADD CONSTRAINT fk_PracticeSessionAttendanceType 
FOREIGN KEY (attendanceTypeID) REFERENCES PracticeSessionAttendanceType(attendanceTypeID);