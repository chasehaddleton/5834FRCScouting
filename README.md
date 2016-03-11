# FRC Stronghold Scouting Site

```
CREATE TABLE `scoutingAPIKey` (
  `keyId` int(11) NOT NULL AUTO_INCREMENT,
  `apiKey` varchar(128) NOT NULL,
  `userId` int(11) NOT NULL,
  `creationDate` datetime NOT NULL,
  PRIMARY KEY (`keyId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1

CREATE TABLE `scoutingCrossing` (
  `crossingId` int(11) NOT NULL AUTO_INCREMENT,
  `teamNumber` int(11) DEFAULT NULL,
  `matchId` int(11) DEFAULT NULL,
  `defenseName` enum('PC','CDF','RP','M','DB','SP','RW','RT','LB') NOT NULL,
  `defenseType` enum('A','B','C','D') NOT NULL,
  PRIMARY KEY (`crossingId`),
  KEY `teamNumber` (`teamNumber`),
  CONSTRAINT `teamNumber` FOREIGN KEY (`teamNumber`) REFERENCES `scoutingTeams` (`teamNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

CREATE TABLE `scoutingLogs` (
  `logId` int(11) NOT NULL AUTO_INCREMENT,
  `logTime` datetime DEFAULT NULL,
  `message` text,
  `userId` int(11) DEFAULT NULL,
  `ip` varchar(16) NOT NULL,
  `severity` tinyint(4) NOT NULL,
  PRIMARY KEY (`logId`),
  KEY `userId` (`userId`),
  KEY `severity` (`severity`),
  CONSTRAINT `userId` FOREIGN KEY (`userId`) REFERENCES `scoutingUsers` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1

CREATE TABLE `scoutingMatch` (
  `matchId` int(11) NOT NULL AUTO_INCREMENT,
  `red1` int(11) NOT NULL,
  `red2` int(11) DEFAULT NULL,
  `red3` int(11) DEFAULT NULL,
  `blue1` int(11) DEFAULT NULL,
  `blue2` int(11) DEFAULT NULL,
  `blue3` int(11) DEFAULT NULL,
  `matchTime` datetime NOT NULL,
  PRIMARY KEY (`matchId`),
  KEY `red1` (`red1`),
  KEY `red2` (`red2`),
  KEY `red3` (`red3`),
  KEY `blue1` (`blue1`),
  KEY `blue2` (`blue2`),
  KEY `blue3` (`blue3`),
  CONSTRAINT `blue1` FOREIGN KEY (`blue1`) REFERENCES `scoutingTeams` (`teamNumber`),
  CONSTRAINT `blue2` FOREIGN KEY (`blue2`) REFERENCES `scoutingTeams` (`teamNumber`),
  CONSTRAINT `blue3` FOREIGN KEY (`blue3`) REFERENCES `scoutingTeams` (`teamNumber`),
  CONSTRAINT `red1` FOREIGN KEY (`red1`) REFERENCES `scoutingTeams` (`teamNumber`),
  CONSTRAINT `red2` FOREIGN KEY (`red2`) REFERENCES `scoutingTeams` (`teamNumber`),
  CONSTRAINT `red3` FOREIGN KEY (`red3`) REFERENCES `scoutingTeams` (`teamNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

CREATE TABLE `scoutingScale` (
  `scaleId` int(11) NOT NULL AUTO_INCREMENT,
  `teamNumber` int(11) NOT NULL,
  `didScale` tinyint(4) DEFAULT '0',
  `matchId` int(11) DEFAULT NULL,
  PRIMARY KEY (`scaleId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

CREATE TABLE `scoutingShot` (
  `shotId` int(11) NOT NULL AUTO_INCREMENT,
  `teamId` int(11) NOT NULL,
  `towerSide` enum('LEFT','CENTER','RIGHT') NOT NULL,
  `towerGoal` enum('TOP','BOTTOM') NOT NULL,
  `scored` tinyint(4) DEFAULT '0',
  `matchId` int(11) NOT NULL,
  PRIMARY KEY (`shotId`),
  KEY `teamId` (`teamId`),
  KEY `matchId` (`matchId`),
  CONSTRAINT `matchId` FOREIGN KEY (`matchId`) REFERENCES `scoutingMatch` (`matchId`),
  CONSTRAINT `teamId` FOREIGN KEY (`teamId`) REFERENCES `scoutingTeams` (`teamNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

CREATE TABLE `scoutingTeams` (
  `teamNumber` int(11) NOT NULL,
  `teamName` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`teamNumber`),
  KEY `teamNumber` (`teamNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

CREATE TABLE `scoutingUsers` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `fullName` varchar(80) NOT NULL,
  `email` varchar(60) NOT NULL,
  `teamNumber` int(11) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `level` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userId`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1
```