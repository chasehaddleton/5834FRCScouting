CREATE TABLE scoutingUsers
(
	userId      INT(11) PRIMARY KEY    NOT NULL,
	fullName    VARCHAR(80)            NOT NULL,
	email       VARCHAR(60)            NOT NULL,
	teamNumber  INT(11)                NOT NULL,
	password    VARCHAR(255)           NOT NULL,
	level       TINYINT(4) DEFAULT '0' NOT NULL,
	uniqId      VARCHAR(24)            NOT NULL,
	phoneNumber VARCHAR(20) DEFAULT '-1',
	CONSTRAINT teamNumber FOREIGN KEY (teamNumber) REFERENCES scoutingTeams (teamNumber)
);
CREATE INDEX teamNumber ON scoutingUsers (teamNumber);
CREATE INDEX userId ON scoutingUsers (userId);
CREATE TABLE scoutingTeams
(
	teamNumber INT(11) PRIMARY KEY NOT NULL,
	teamName   VARCHAR(30)
);
CREATE INDEX teamNumber ON scoutingTeams (teamNumber);
CREATE TABLE scoutingShot
(
	shotId          INT(11) PRIMARY KEY             NOT NULL,
	teamNumber      INT(11)                         NOT NULL,
	towerSide       ENUM('LEFT', 'CENTER', 'RIGHT') NOT NULL,
	towerGoal       ENUM('TOP', 'BOTTOM')           NOT NULL,
	scored          TINYINT(1) DEFAULT '0',
	matchId         INT(11)                         NOT NULL,
	scoutTeamNumber INT(11),
	CONSTRAINT Sh_matchId FOREIGN KEY (matchId) REFERENCES scoutingMatches (matchId),
	CONSTRAINT Sh_teamNumber FOREIGN KEY (teamNumber) REFERENCES scoutingTeams (teamNumber)
);
CREATE INDEX Sh_matchId ON scoutingShot (matchId);
CREATE INDEX Sh_scoutTeamNumber ON scoutingShot (scoutTeamNumber);
CREATE INDEX teamId ON scoutingShot (teamNumber);
CREATE TABLE scoutingCrossing
(
	crossingId      INT(11) PRIMARY KEY                                        NOT NULL,
	teamNumber      INT(11),
	matchId         INT(11),
	defenseName     ENUM('PC', 'CF', 'RP', 'MT', 'DB', 'SP', 'RW', 'RT', 'LB') NOT NULL,
	defenseType     ENUM('A', 'B', 'C', 'D')                                   NOT NULL,
	scoutTeamNumber INT(11)                                                    NOT NULL,
	CONSTRAINT C_matchId FOREIGN KEY (matchId) REFERENCES scoutingMatches (matchId),
	CONSTRAINT C_teamNumber FOREIGN KEY (teamNumber) REFERENCES scoutingTeams (teamNumber)
);
CREATE INDEX C_matchId ON scoutingCrossing (matchId);
CREATE INDEX C_scoutTeamNumber ON scoutingCrossing (scoutTeamNumber);
CREATE INDEX C_teamNumber ON scoutingCrossing (teamNumber);
CREATE TABLE scoutingScale
(
	scaleId         INT(11) PRIMARY KEY             NOT NULL,
	teamNumber      INT(11)                         NOT NULL,
	matchId         INT(11),
	scoutTeamNumber INT(11)                         NOT NULL,
	towerSide       ENUM('LEFT', 'CENTER', 'RIGHT') NOT NULL,
	CONSTRAINT S_matchId FOREIGN KEY (matchId) REFERENCES scoutingMatches (matchId),
	CONSTRAINT S_teamNumber FOREIGN KEY (teamNumber) REFERENCES scoutingTeams (teamNumber)
);
CREATE INDEX S_matchId ON scoutingScale (matchId);
CREATE INDEX S_scoutTeamNumber ON scoutingScale (scoutTeamNumber);
CREATE INDEX S_teamNumber ON scoutingScale (teamNumber);
CREATE TABLE scoutingLogs
(
	logId    INT(11) PRIMARY KEY NOT NULL,
	logTime  DATETIME,
	message  TEXT,
	userId   INT(11),
	ip       VARCHAR(16)         NOT NULL,
	severity TINYINT(4)          NOT NULL
);
CREATE INDEX logTime ON scoutingLogs (logTime);
CREATE INDEX severity ON scoutingLogs (severity);
CREATE INDEX userId ON scoutingLogs (userId);
CREATE TABLE scoutingAPIKey
(
	keyId        INT(11) PRIMARY KEY NOT NULL,
	apiKey       VARCHAR(128)        NOT NULL,
	userId       INT(11)             NOT NULL,
	creationDate DATETIME            NOT NULL
);
CREATE TABLE scoutingMatches
(
	matchId     INT(11) PRIMARY KEY NOT NULL,
	red1        INT(11)             NOT NULL,
	red2        INT(11),
	red3        INT(11),
	blue1       INT(11),
	blue2       INT(11),
	blue3       INT(11),
	finals      TINYINT(1) DEFAULT '1',
	compKey     VARCHAR(10),
	matchNumber INT(11)             NOT NULL,
	CONSTRAINT blue1 FOREIGN KEY (blue1) REFERENCES scoutingTeams (teamNumber),
	CONSTRAINT blue2 FOREIGN KEY (blue2) REFERENCES scoutingTeams (teamNumber),
	CONSTRAINT blue3 FOREIGN KEY (blue3) REFERENCES scoutingTeams (teamNumber),
	CONSTRAINT compKey FOREIGN KEY (compKey) REFERENCES scoutingEvents (compKey),
	CONSTRAINT red1 FOREIGN KEY (red1) REFERENCES scoutingTeams (teamNumber),
	CONSTRAINT red2 FOREIGN KEY (red2) REFERENCES scoutingTeams (teamNumber),
	CONSTRAINT red3 FOREIGN KEY (red3) REFERENCES scoutingTeams (teamNumber)
);
CREATE INDEX blue1 ON scoutingMatches (blue1);
CREATE INDEX blue2 ON scoutingMatches (blue2);
CREATE INDEX blue3 ON scoutingMatches (blue3);
CREATE INDEX compKey ON scoutingMatches (compKey);
CREATE INDEX red1 ON scoutingMatches (red1);
CREATE INDEX red2 ON scoutingMatches (red2);
CREATE INDEX red3 ON scoutingMatches (red3);
CREATE TABLE scoutingNotes
(
	noteId             INT(11) PRIMARY KEY NOT NULL,
	teamNumber         INT(11)             NOT NULL,
	scoutingTeamNumber INT(11)             NOT NULL,
	contents           TEXT                NOT NULL,
	matchId            INT(11),
	CONSTRAINT N_matchId FOREIGN KEY (matchId) REFERENCES scoutingMatches (matchId),
	CONSTRAINT N_teamNumber FOREIGN KEY (teamNumber) REFERENCES scoutingTeams (teamNumber)
);
CREATE INDEX matchId ON scoutingNotes (matchId);
CREATE INDEX N_scoutingTeamNumber ON scoutingNotes (scoutingTeamNumber);
CREATE INDEX N_teamNumber ON scoutingNotes (teamNumber);
CREATE TABLE scoutingEvents
(
	eventId        INT(11) PRIMARY KEY    NOT NULL,
	compKey        VARCHAR(10)            NOT NULL,
	isChampionship TINYINT(4) DEFAULT '0' NOT NULL,
	compName       VARCHAR(50)            NOT NULL
);
CREATE INDEX isChampionship ON scoutingEvents (isChampionship);
CREATE UNIQUE INDEX scoutingEvents_compKey_uindex ON scoutingEvents (compKey);
CREATE TABLE scoutingAlliances
(
	allianceId     INT(11) PRIMARY KEY NOT NULL,
	team1          INT(11),
	team2          INT(11),
	team3          INT(11),
	alt            INT(11),
	allianceNumber INT(11),
	compKey        VARCHAR(10),
	CONSTRAINT alt FOREIGN KEY (alt) REFERENCES scoutingTeams (teamNumber),
	CONSTRAINT A_compKey FOREIGN KEY (compKey) REFERENCES scoutingEvents (compKey),
	CONSTRAINT team1 FOREIGN KEY (team1) REFERENCES scoutingTeams (teamNumber),
	CONSTRAINT team2 FOREIGN KEY (team2) REFERENCES scoutingTeams (teamNumber),
	CONSTRAINT team3 FOREIGN KEY (team3) REFERENCES scoutingTeams (teamNumber)
);
CREATE INDEX alt ON scoutingAlliances (alt);
CREATE INDEX A_compKey ON scoutingAlliances (compKey);
CREATE INDEX team1 ON scoutingAlliances (team1);
CREATE INDEX team2 ON scoutingAlliances (team2);
CREATE INDEX team3 ON scoutingAlliances (team3);