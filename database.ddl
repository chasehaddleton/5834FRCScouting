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
CREATE TABLE scoutingMatch
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
	matchNumber INT(11)             NOT NULL
);
CREATE INDEX blue1 ON scoutingMatch (blue1);
CREATE INDEX blue2 ON scoutingMatch (blue2);
CREATE INDEX blue3 ON scoutingMatch (blue3);
CREATE INDEX compKey ON scoutingMatch (compKey);
CREATE INDEX red1 ON scoutingMatch (red1);
CREATE INDEX red2 ON scoutingMatch (red2);
CREATE INDEX red3 ON scoutingMatch (red3);
CREATE TABLE scoutingShot
(
	shotId          INT(11) PRIMARY KEY             NOT NULL,
	teamNumber      INT(11)                         NOT NULL,
	towerSide       ENUM('LEFT', 'CENTER', 'RIGHT') NOT NULL,
	towerGoal       ENUM('TOP', 'BOTTOM')           NOT NULL,
	scored          TINYINT(1) DEFAULT '0',
	matchId         INT(11)                         NOT NULL,
	scoutTeamNumber INT(11)
);
CREATE INDEX matchId ON scoutingShot (matchId);
CREATE INDEX teamId ON scoutingShot (teamNumber);
CREATE TABLE scoutingCrossing
(
	crossingId      INT(11) PRIMARY KEY                                        NOT NULL,
	teamNumber      INT(11),
	matchId         INT(11),
	defenseName     ENUM('PC', 'CDF', 'RP', 'M', 'DB', 'SP', 'RW', 'RT', 'LB') NOT NULL,
	defenseType     ENUM('A', 'B', 'C', 'D')                                   NOT NULL,
	scoutTeamNumber INT(11)                                                    NOT NULL
);
CREATE INDEX teamNumber ON scoutingCrossing (teamNumber);
CREATE TABLE scoutingScale
(
	scaleId         INT(11) PRIMARY KEY             NOT NULL,
	teamNumber      INT(11)                         NOT NULL,
	matchId         INT(11),
	scoutTeamNumber INT(11)                         NOT NULL,
	towerSide       ENUM('LEFT', 'CENTER', 'RIGHT') NOT NULL
);
CREATE TABLE scoutingLogs
(
	logId    INT(11) PRIMARY KEY NOT NULL,
	logTime  DATETIME,
	message  TEXT,
	userId   INT(11),
	ip       VARCHAR(16)         NOT NULL,
	severity TINYINT(4)          NOT NULL
);
CREATE INDEX severity ON scoutingLogs (severity);
CREATE INDEX userId ON scoutingLogs (userId);
CREATE TABLE scoutingAPIKey
(
	keyId        INT(11) PRIMARY KEY NOT NULL,
	apiKey       VARCHAR(128)        NOT NULL,
	userId       INT(11)             NOT NULL,
	creationDate DATETIME            NOT NULL
);
CREATE TABLE scoutingNote
(
	noteId             INT(11) PRIMARY KEY NOT NULL,
	teamNumber         INT(11)             NOT NULL,
	scoutingTeamNumber INT(11)             NOT NULL,
	contents           TEXT                NOT NULL,
	matchId            INT(11)
);
CREATE INDEX matchId ON scoutingNote (matchId);