CREATE TABLE scoutingTeams
(
	teamNumber INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	teamName   VARCHAR(30)
);
CREATE INDEX scoutingTeams_teamNumber_index ON scoutingTeams (teamNumber);

CREATE TABLE scoutingEvents
(
	eventId      INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	compName     VARCHAR(50)                        NOT NULL,
	compKey      VARCHAR(10)                        NOT NULL,
	championship TINYINT(4) DEFAULT '0'             NOT NULL
);
CREATE INDEX scoutingEvents_championship_index ON scoutingEvents (championship);
CREATE UNIQUE INDEX scoutingEvents_compKey_uindex ON scoutingEvents (compKey);

CREATE TABLE scoutingUsers
(
	userId      INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	fullName    VARCHAR(80)                        NOT NULL,
	email       VARCHAR(60)                        NOT NULL,
	teamNumber  INT(11)                            NOT NULL,
	password    VARCHAR(255)                       NOT NULL,
	uniqId      VARCHAR(24)                        NOT NULL,
	APIKey      VARCHAR(128),
	level       TINYINT(4) DEFAULT '0'             NOT NULL,
	phoneNumber VARCHAR(20) DEFAULT '-1',
	CONSTRAINT scoutingUsers_scoutingTeams_teamNumber_fk FOREIGN KEY (teamNumber) REFERENCES scoutingTeams (teamNumber)
		ON DELETE CASCADE
);
CREATE UNIQUE INDEX scoutingUsers_APIKey_uindex ON scoutingUsers (APIKey);
CREATE INDEX scoutingUsers_scoutingTeams_teamNumber_fk_index ON scoutingUsers (teamNumber);
CREATE INDEX scoutingUsers_userId_index ON scoutingUsers (userId);

CREATE TABLE scoutingMatches
(
	matchId     INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	red1        INT(11)                            NOT NULL,
	red2        INT(11)                            NOT NULL,
	red3        INT(11)                            NOT NULL,
	blue1       INT(11)                            NOT NULL,
	blue2       INT(11)                            NOT NULL,
	blue3       INT(11)                            NOT NULL,
	finals      TINYINT(1) DEFAULT '1'             NOT NULL,
	compKey     VARCHAR(10)                        NOT NULL,
	matchNumber INT(11)                            NOT NULL,
	CONSTRAINT scoutingMatches_scoutingTeams_blue1_fk FOREIGN KEY (blue1) REFERENCES scoutingTeams (teamNumber)
		ON DELETE NO ACTION,
	CONSTRAINT scoutingMatches_scoutingTeams_blue2_fk FOREIGN KEY (blue2) REFERENCES scoutingTeams (teamNumber)
		ON DELETE NO ACTION,
	CONSTRAINT scoutingMatches_scoutingTeams_blue3_fk FOREIGN KEY (blue3) REFERENCES scoutingTeams (teamNumber)
		ON DELETE NO ACTION,
	CONSTRAINT scoutingMatches_scoutingEvents_compKey_fk FOREIGN KEY (compKey) REFERENCES scoutingEvents (compKey)
		ON DELETE CASCADE,
	CONSTRAINT scoutingMatches_scoutingTeams_red1_fk FOREIGN KEY (red1) REFERENCES scoutingTeams (teamNumber)
		ON DELETE NO ACTION,
	CONSTRAINT scoutingMatches_scoutingTeams_red2_fk FOREIGN KEY (red2) REFERENCES scoutingTeams (teamNumber)
		ON DELETE NO ACTION,
	CONSTRAINT scoutingMatches_scoutingTeams_red3_fk FOREIGN KEY (red3) REFERENCES scoutingTeams (teamNumber)
		ON DELETE NO ACTION
);
CREATE INDEX scoutingMatches_scoutingTeams_blue1_fk_index ON scoutingMatches (blue1);
CREATE INDEX scoutingMatches_scoutingTeams_blue2_fk_index ON scoutingMatches (blue2);
CREATE INDEX scoutingMatches_scoutingTeams_blue3_fk_index ON scoutingMatches (blue3);
CREATE INDEX scoutingMatches_scoutingEvents_compKey_fk_index ON scoutingMatches (compKey);
CREATE INDEX scoutingMatches_scoutingTeams_red1_fk_index ON scoutingMatches (red1);
CREATE INDEX scoutingMatches_scoutingTeams_red2_fk_index ON scoutingMatches (red2);
CREATE INDEX scoutingMatches_scoutingTeams_red3_fk_index ON scoutingMatches (red3);

CREATE TABLE scoutingDefenseLineup
(
	defenseLineupId INT(11) PRIMARY KEY AUTO_INCREMENT                          NOT NULL,
	matchId         INT(11)                                                     NOT NULL,
	redSlot1        ENUM ('LB') DEFAULT 'LB'                                    NOT NULL,
	redSlot2        ENUM ('PC', 'CF', 'RP', 'MT', 'DB', 'SP', 'RW', 'RT', 'LB') NOT NULL,
	redSlot3        ENUM ('PC', 'CF', 'RP', 'MT', 'DB', 'SP', 'RW', 'RT', 'LB') NOT NULL,
	redSlot4        ENUM ('PC', 'CF', 'RP', 'MT', 'DB', 'SP', 'RW', 'RT', 'LB') NOT NULL,
	redSlot5        ENUM ('PC', 'CF', 'RP', 'MT', 'DB', 'SP', 'RW', 'RT', 'LB') NOT NULL,
	blueSlot1       ENUM ('LB') DEFAULT 'LB'                                    NOT NULL,
	blueSlot2       ENUM ('PC', 'CF', 'RP', 'MT', 'DB', 'SP', 'RW', 'RT', 'LB') NOT NULL,
	blueSlot3       ENUM ('PC', 'CF', 'RP', 'MT', 'DB', 'SP', 'RW', 'RT', 'LB') NOT NULL,
	blueSlot4       ENUM ('PC', 'CF', 'RP', 'MT', 'DB', 'SP', 'RW', 'RT', 'LB') NOT NULL,
	blueSlot5       ENUM ('PC', 'CF', 'RP', 'MT', 'DB', 'SP', 'RW', 'RT', 'LB') NOT NULL,
	scoutTeamNumber INT(11)                                                     NOT NULL,
	userId          INT(11)                                                     NOT NULL,
	CONSTRAINT scoutingDefenseLineup_scoutingUsers_userId_fk FOREIGN KEY (userId) REFERENCES scoutingUsers (userId)
		ON DELETE NO ACTION
);
CREATE INDEX scoutingDefenseLineup_scoutingUsers_userId_fk_index ON scoutingDefenseLineup (userId);
CREATE INDEX scoutingDefenseLineup_scoutTeamNumber_index ON scoutingDefenseLineup (scoutTeamNumber);

CREATE TABLE scoutingCrossing
(
	crossingId      INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	matchId         INT(11)                            NOT NULL,
	teamNumber      INT(11)                            NOT NULL,
	slot            ENUM ('1', '2', '3', '4', '5')     NOT NULL,
	auto            TINYINT(1) DEFAULT '0'             NOT NULL,
	assist          ENUM ('NONE', 'OPEN', 'PUSH')      NOT NULL,
	scoutTeamNumber INT(11)                            NOT NULL,
	userId          INT(11)                            NOT NULL,
	CONSTRAINT scoutingCrossing_scoutingMatches_matchId_fk FOREIGN KEY (matchId) REFERENCES scoutingMatches (matchId)
		ON DELETE CASCADE,
	CONSTRAINT scoutingCrossing_scoutingTeams_teamNumber_fk FOREIGN KEY (teamNumber) REFERENCES scoutingTeams (teamNumber)
		ON DELETE CASCADE,
	CONSTRAINT scoutingCrossing_scoutingUsers_userId_fk FOREIGN KEY (userId) REFERENCES scoutingUsers (userId)
		ON DELETE NO ACTION
);
CREATE INDEX scoutingCrossing_scoutingMatches_matchId_fk_index ON scoutingCrossing (matchId);
CREATE INDEX scoutingCrossing_scoutTeamNumber_index ON scoutingCrossing (scoutTeamNumber);
CREATE INDEX scoutingCrossing_scoutingTeams_teamNumber_fk_index ON scoutingCrossing (teamNumber);
CREATE INDEX scoutingCrossing_scoutingUsers_userId_fk_index ON scoutingCrossing (userId);

CREATE TABLE scoutingScale
(
	scaleId         INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	matchId         INT(11)                            NOT NULL,
	teamNumber      INT(11)                            NOT NULL,
	scoutTeamNumber INT(11)                            NOT NULL,
	towerSide       ENUM ('LEFT', 'CENTER', 'RIGHT')   NOT NULL,
	userId          INT(11)                            NOT NULL,
	CONSTRAINT scoutingScale_scoutingMatches_matchId_fk FOREIGN KEY (matchId) REFERENCES scoutingMatches (matchId)
		ON DELETE CASCADE,
	CONSTRAINT scoutingScale_scoutingTeams_teamNumber_fk FOREIGN KEY (teamNumber) REFERENCES scoutingTeams (teamNumber)
		ON DELETE CASCADE,
	CONSTRAINT scoutingScale_scoutingUsers_userId_fk FOREIGN KEY (userId) REFERENCES scoutingUsers (userId)
		ON DELETE NO ACTION
);
CREATE INDEX scoutingScale_scoutingMatches_matchId_fk_index ON scoutingScale (matchId);
CREATE INDEX scoutingScale_scoutTeamNumber_index ON scoutingScale (scoutTeamNumber);
CREATE INDEX scoutingScale_scoutingTeams_teamNumber_fk_index ON scoutingScale (teamNumber);
CREATE INDEX scoutingScale_scoutingUsers_userId_fk_index ON scoutingScale (userId);

CREATE TABLE scoutingChallenge
(
	challengeId     INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	matchId         INT(11)                            NOT NULL,
	teamNumber      INT(11)                            NOT NULL,
	towerSide       ENUM ('LEFT', 'CENTER', 'RIGHT')   NOT NULL,
	scoutTeamNumber INT(11)                            NOT NULL,
	userId          INT(11)                            NOT NULL,
	CONSTRAINT scoutingChallenge_scoutingMatches_matchId_fk FOREIGN KEY (matchId) REFERENCES scoutingMatches (matchId)
		ON DELETE CASCADE,
	CONSTRAINT scoutingChallenge_scoutingTeams_teamNumber_fk FOREIGN KEY (teamNumber) REFERENCES scoutingTeams (teamNumber)
		ON DELETE CASCADE,
	CONSTRAINT scoutingChallenge_scoutingUsers_userId_fk FOREIGN KEY (userId) REFERENCES scoutingUsers (userId)
		ON DELETE NO ACTION
);
CREATE INDEX scoutingChallenge_scoutingMatches_matchId_fk_index ON scoutingChallenge (matchId);
CREATE INDEX scoutingChallenge_scoutingTeams_teamNumber_fk_index ON scoutingChallenge (teamNumber);
CREATE INDEX scoutingChallenge_scoutingUsers_userId_fk_index ON scoutingChallenge (userId);
CREATE INDEX scoutingChallenge_scoutTeamNumber_index ON scoutingChallenge (scoutTeamNumber);


CREATE TABLE scoutingShot
(
	shotId          INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	matchId         INT(11)                            NOT NULL,
	teamNumber      INT(11)                            NOT NULL,
	scored          TINYINT(1) DEFAULT '0'             NOT NULL,
	auto            TINYINT(1) DEFAULT '0'             NOT NULL,
	rampShot        TINYINT(1) DEFAULT '0'             NOT NULL,
	towerSide       ENUM ('LEFT', 'CENTER', 'RIGHT')   NOT NULL,
	towerGoal       ENUM ('HIGH', 'LOW')               NOT NULL,
	scoutTeamNumber INT(11)                            NOT NULL,
	userId          INT(11)                            NOT NULL,
	CONSTRAINT scoutingShot_scoutingMatches_matchId_fk FOREIGN KEY (matchId) REFERENCES scoutingMatches (matchId)
		ON DELETE CASCADE,
	CONSTRAINT scoutingShot_scoutingTeams_teamNumber_fk FOREIGN KEY (teamNumber) REFERENCES scoutingTeams (teamNumber)
		ON DELETE CASCADE,
	CONSTRAINT scoutingShot_scoutingUsers_userId_fk FOREIGN KEY (userId) REFERENCES scoutingUsers (userId)
		ON DELETE NO ACTION
);
CREATE INDEX scoutingShot_scoutingMatches_matchId_fk_index ON scoutingShot (matchId);
CREATE INDEX scoutingShot_scoutTeamNumber_index ON scoutingShot (scoutTeamNumber);
CREATE INDEX scoutingShot_scoutingTeams_teamNumber_fk_index ON scoutingShot (teamNumber);
CREATE INDEX scoutingShot_scoutingUsers_userId_fk_index ON scoutingShot (userId);

CREATE TABLE scoutingAlliances
(
	allianceId      INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	compKey         VARCHAR(10)                        NOT NULL,
	allianceNumber  INT(11)                            NOT NULL,
	team1           INT(11)                            NOT NULL,
	team2           INT(11)                            NOT NULL,
	team3           INT(11)                            NOT NULL,
	alt             INT(11),
	scoutTeamNumber INT(11)                            NOT NULL,
	userId          INT(11)                            NOT NULL,
	CONSTRAINT scoutingAlliances_scoutingEvents_compKey_fk FOREIGN KEY (compKey) REFERENCES scoutingEvents (compKey)
		ON DELETE CASCADE,
	CONSTRAINT scoutingAlliances_scoutingTeams_team1_fk FOREIGN KEY (team1) REFERENCES scoutingTeams (teamNumber)
		ON DELETE NO ACTION,
	CONSTRAINT scoutingAlliances_scoutingTeams_team2_fk FOREIGN KEY (team2) REFERENCES scoutingTeams (teamNumber)
		ON DELETE NO ACTION,
	CONSTRAINT scoutingAlliances_scoutingTeams_team3_fk FOREIGN KEY (team3) REFERENCES scoutingTeams (teamNumber)
		ON DELETE NO ACTION,
	CONSTRAINT scoutingAlliances_scoutingTeams_alt_fk FOREIGN KEY (alt) REFERENCES scoutingTeams (teamNumber)
		ON DELETE NO ACTION,
	CONSTRAINT scoutingAlliances_scoutingUsers_userId_fk FOREIGN KEY (userId) REFERENCES scoutingUsers (userId)
		ON DELETE NO ACTION
);
CREATE INDEX scoutingAlliances_scoutTeamNumber_index ON scoutingAlliances (scoutTeamNumber);
CREATE INDEX scoutingAlliances_scoutingEvents_compKey_fk_index ON scoutingAlliances (compKey);
CREATE INDEX scoutingAlliances_scoutingTeams_team1_fk_index ON scoutingAlliances (team1);
CREATE INDEX scoutingAlliances_scoutingTeams_team2_fk_index ON scoutingAlliances (team2);
CREATE INDEX scoutingAlliances_scoutingTeams_team3_fk_index ON scoutingAlliances (team3);
CREATE INDEX scoutingAlliances_scoutingTeams_alt_fk_index ON scoutingAlliances (alt);
CREATE INDEX scoutingAlliances_scoutingUsers_userId_fk_index ON scoutingAlliances (userId);

CREATE TABLE scoutingNotes
(
	noteId          INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	matchId         INT(11),
	teamNumber      INT(11)                            NOT NULL,
	contents        TEXT                               NOT NULL,
	scoutTeamNumber INT(11)                            NOT NULL,
	userId          INT(11)                            NOT NULL,
	CONSTRAINT scoutingNotes_scoutingMatches_matchId_fk FOREIGN KEY (matchId) REFERENCES scoutingMatches (matchId)
		ON DELETE CASCADE,
	CONSTRAINT scoutingNotes_scoutingMatches_teamNumber_fk FOREIGN KEY (teamNumber) REFERENCES scoutingTeams (teamNumber)
		ON DELETE CASCADE,
	CONSTRAINT scoutingNotes_scoutingUsers_userId_fk FOREIGN KEY (userId) REFERENCES scoutingUsers (userId)
		ON DELETE NO ACTION
);
CREATE INDEX scoutingNotes_scoutingMatches_matchId_fk_index ON scoutingNotes (matchId);
CREATE INDEX scoutingNotes_scoutTeamNumber_index ON scoutingNotes (scoutTeamNumber);
CREATE INDEX scoutingNotes_scoutingMatches_teamNumber_fk_index ON scoutingNotes (teamNumber);
CREATE INDEX scoutingNotes_scoutingUsers_userId_fk_index ON scoutingNotes (userId);

CREATE TABLE scoutingLogs
(
	logId    INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	logTime  DATETIME                           NOT NULL,
	severity TINYINT(4)                         NOT NULL,
	message  TEXT                               NOT NULL,
	userId   INT(11),
	ip       VARCHAR(16)                        NOT NULL
);
CREATE INDEX scoutingLogs_logTime_index ON scoutingLogs (logTime);
CREATE INDEX scoutingLogs_severity_index ON scoutingLogs (severity);