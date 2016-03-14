CREATE TABLE Users
(
	userId      INT(11) PRIMARY KEY    NOT NULL,
	fullName    VARCHAR(80)            NOT NULL,
	email       VARCHAR(60)            NOT NULL,
	teamNumber  INT(11)                NOT NULL,
	password    VARCHAR(255)           NOT NULL,
	level       TINYINT(4) DEFAULT '0' NOT NULL,
	uniqId      VARCHAR(24)            NOT NULL,
	phoneNumber VARCHAR(20) DEFAULT '-1',
	CONSTRAINT teamNumber FOREIGN KEY (teamNumber) REFERENCES Teams (teamNumber)
);
CREATE INDEX teamNumber ON Users (teamNumber);
CREATE INDEX userId ON Users (userId);
CREATE TABLE Teams
(
	teamNumber INT(11) PRIMARY KEY NOT NULL,
	teamName   VARCHAR(30)
);
CREATE INDEX teamNumber ON Teams (teamNumber);
CREATE TABLE Match
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
	CONSTRAINT blue1 FOREIGN KEY (blue1) REFERENCES Teams (teamNumber),
	CONSTRAINT blue2 FOREIGN KEY (blue2) REFERENCES Teams (teamNumber),
	CONSTRAINT blue3 FOREIGN KEY (blue3) REFERENCES Teams (teamNumber),
	CONSTRAINT red1 FOREIGN KEY (red1) REFERENCES Teams (teamNumber),
	CONSTRAINT red2 FOREIGN KEY (red2) REFERENCES Teams (teamNumber),
	CONSTRAINT red3 FOREIGN KEY (red3) REFERENCES Teams (teamNumber)
);
CREATE INDEX blue1 ON Match (blue1);
CREATE INDEX blue2 ON Match (blue2);
CREATE INDEX blue3 ON Match (blue3);
CREATE INDEX compKey ON Match (compKey);
CREATE INDEX red1 ON Match (red1);
CREATE INDEX red2 ON Match (red2);
CREATE INDEX red3 ON Match (red3);
CREATE TABLE Shot
(
	shotId          INT(11) PRIMARY KEY             NOT NULL,
	teamNumber      INT(11)                         NOT NULL,
	towerSide       ENUM('LEFT', 'CENTER', 'RIGHT') NOT NULL,
	towerGoal       ENUM('TOP', 'BOTTOM')           NOT NULL,
	scored          TINYINT(1) DEFAULT '0',
	matchId         INT(11)                         NOT NULL,
	scoutTeamNumber INT(11)
);
CREATE INDEX matchId ON Shot (matchId);
CREATE INDEX teamId ON Shot (teamNumber);
CREATE TABLE Crossing
(
	crossingId      INT(11) PRIMARY KEY                                        NOT NULL,
	teamNumber      INT(11),
	matchId         INT(11),
	defenseName     ENUM('PC', 'CF', 'RP', 'MT', 'DB', 'SP', 'RW', 'RT', 'LB') NOT NULL,
	defenseType     ENUM('A', 'B', 'C', 'D')                                   NOT NULL,
	scoutTeamNumber INT(11)                                                    NOT NULL
);
CREATE INDEX teamNumber ON Crossing (teamNumber);
CREATE TABLE Scale
(
	scaleId         INT(11) PRIMARY KEY             NOT NULL,
	teamNumber      INT(11)                         NOT NULL,
	matchId         INT(11),
	scoutTeamNumber INT(11)                         NOT NULL,
	towerSide       ENUM('LEFT', 'CENTER', 'RIGHT') NOT NULL
);
CREATE TABLE Logs
(
	logId    INT(11) PRIMARY KEY NOT NULL,
	logTime  DATETIME,
	message  TEXT,
	userId   INT(11),
	ip       VARCHAR(16)         NOT NULL,
	severity TINYINT(4)          NOT NULL
);
CREATE INDEX severity ON Logs (severity);
CREATE INDEX userId ON Logs (userId);
CREATE TABLE APIKey
(
	keyId        INT(11) PRIMARY KEY NOT NULL,
	apiKey       VARCHAR(128)        NOT NULL,
	userId       INT(11)             NOT NULL,
	creationDate DATETIME            NOT NULL
);
CREATE TABLE Note
(
	noteId             INT(11) PRIMARY KEY NOT NULL,
	teamNumber         INT(11)             NOT NULL,
	scoutingTeamNumber INT(11)             NOT NULL,
	contents           TEXT                NOT NULL,
	matchId            INT(11)
);
CREATE INDEX matchId ON Note (matchId);