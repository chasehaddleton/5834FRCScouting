# FRC Stronghold Scouting Site

```
CREATE TABLE riverdalerobotics.scoutingUsers (
    userId INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    firstName VARCHAR(30) NOT NULL,
    lastName VARCHAR(40) NOT NULL,
    email VARCHAR(60) NOT NULL,
    team VARCHAR(10) NOT NULL
);

CREATE TABLE riverdalerobotics.scoutingTeams (
    teamNumber INT PRIMARY KEY NOT NULL,
    teamName VARCHAR(30)
);
CREATE UNIQUE INDEX scoutingTeams_teamNumber_uindex ON riverdalerobotics.scoutingTeams (teamNumber);
CREATE TABLE riverdalerobotics.scoutingMatch
(
    matchId INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    red1 INT,
    red2 INT,
    red3 INT,
    blue1 INT,
    blue2 INT,
    blue3 INT,
    matchTime DATETIME NOT NULL,
    CONSTRAINT red1 FOREIGN KEY (red1) REFERENCES scoutingTeams (teamNumber),
    CONSTRAINT red2 FOREIGN KEY (red2) REFERENCES scoutingTeams (teamNumber),
    CONSTRAINT red3 FOREIGN KEY (red3) REFERENCES scoutingTeams (teamNumber),
    CONSTRAINT blue1 FOREIGN KEY (blue1) REFERENCES scoutingTeams (teamNumber),
    CONSTRAINT blue2 FOREIGN KEY (blue2) REFERENCES scoutingTeams (teamNumber),
    CONSTRAINT blue3 FOREIGN KEY (blue3) REFERENCES scoutingTeams (teamNumber)
);

CREATE TABLE riverdalerobotics.scoutingShot
(
    shotId INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    teamId INT NOT NULL,
    towerSide ENUM('LEFT', 'CENTER', 'RIGHT') NOT NULL,
    towerGoal ENUM('TOP', 'BOTTOM') NOT NULL,
    scored TINYINT DEFAULT 0,
    CONSTRAINT teamId FOREIGN KEY (teamId) REFERENCES scoutingTeams (teamNumber)
);

CREATE TABLE riverdalerobotics.scoutingCrossing
(
	crossingId INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	teamNumber INT NOT NULL,
	matchId INT NOT NULL,
	defenseName ENUM('PC', 'CDF', 'RP', 'M', 'DB', 'SP', 'RW', 'RT', 'LB') NOT NULL,
	defenseType ENUM('A', 'B', 'C', 'D') NOT NULL,
	CONSTRAINT teamId FOREIGN KEY (teamId) REFERENCES scoutingTeams (teamNumber)
);

CREATE TABLE riverdalerobotics.scoutingScale
(
    scaleId INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    teamNumber INT NOT NULL,
    didScale TINYINT DEFAULT 0,
    matchId INT
);
```