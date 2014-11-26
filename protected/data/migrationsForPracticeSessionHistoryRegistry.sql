# adicionar campo 'male' na tabela user e definir o default como true
ALTER TABLE mytennisapp.User ADD male BOOL DEFAULT 1 NOT NULL;

#adicionarcampo em PracticeSessionHistory para indicação de aula cancelada
ALTER TABLE mytennisapp.PracticeSessionHistory ADD cancelled BOOL DEFAULT 0 NOT NULL;

#criar tabela dos tipos de presença
CREATE TABLE mytennisapp.PracticeSessionAttendanceType
(
  attendanceTypeID int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  description VARCHAR(45) NOT NULL UNIQUE
);

#inserts for table jut created
INSERT INTO mytennisapp.PracticeSessionAttendanceType (description) VALUES ('presença');
INSERT INTO mytennisapp.PracticeSessionAttendanceType (description) VALUES ('ausência com compensação');
INSERT INTO mytennisapp.PracticeSessionAttendanceType (description) VALUES ('ausência sem compensação');
INSERT INTO mytennisapp.PracticeSessionAttendanceType (description) VALUES ('presença de compensação');

#mudar o nome do campo na tabela que vai receber o ID
ALTER TABLE mytennisapp.PracticeSessionHistoryHasAthlete CHANGE attendanceType attendanceTypeID INT;

#indicar que este campo é chave estrangeira
ALTER TABLE mytennisapp.PracticeSessionHistoryHasAthlete ADD CONSTRAINT fk_PracticeSessionAttendanceType 
FOREIGN KEY (attendanceTypeID) REFERENCES PracticeSessionAttendanceType(attendanceTypeID);

#colocar auto_increment que não estava por lapso
ALTER TABLE PracticeSessionHistory MODIFY COLUMN practiceSessionHistoryID INT auto_increment;