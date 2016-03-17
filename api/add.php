<?php
require_once('../components/common.php');
require_once($setting->getAppPath() . '/components/Authenticate/User.php');

// Check all page conditions.

if ($_SERVER['REQUEST_METHOD'] != "GET") {
	errorResponse("Error, must GET to this page.", 2);
}

$requiredPOSTKeys = array('apiKey', 'userId', 'scoutTeamNumber');
if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
	errorResponse("Error, apiKey and/or related data missing from request. Must provide apiKey, userId and teamNumber", 2);
}


if (!verifyAPIKey($_GET['apiKey'], $_GET['userId'], $_GET['scoutTeamNumber'])) {
	errorResponse("Key not valid", 12);
}

if (!key_exists("type", $_GET)) {
	errorResponse("Error, must specify crossing type.", 30);
}

$towerSides = array('LEFT', 'CENTER', 'RIGHT');
$towerGoals = array('TOP', 'BOTTOM');
$defenseNames = array('PORTCULLIS', 'CHEVALDEFRISE', 'MOAT', 'RAMPARTS', 'DRAWBRIDGE', 'SALLYPORT', 'ROCKWALL', 'ROUGHTERRAIN');

switch (strtoupper($_GET['type'])) {
	case "SHOT":
		$requiredPOSTKeys = array('type', 'matchId', 'teamNumber', 'towerSide', 'towerGoal', 'scored', 'scoutTeamNumber');
		if (count(array_diff($requiredPOSTKeys, array_keys($_GET))) != 0) {
			errorResponse("Error, email and/or password is missing.", 31);
		}

		$query = "INSERT INTO Shot (matchId, teamNumber, towerSide, towerGoal, scored, scoutTeamNumber) VALUES (:matchId, :teamNumber, :towerSide, :towerGoal, :scored, :scoutTeamNumber)";
		$query_params = array(":matchId" => intval($_GET['matchId']), ":teamNumber" => intval($_GET['teamNumber']), ":scoutTeamNumber" => intval($_GET['scoutTeamNumber']));

		$scored = (bool)$_GET['scored'];
		$query_params[':scored'] = ($scored) ? 1 : 0;

		if (!in_array(strtoupper($_GET['towerSide']), $towerSides, true)) {
			errorResponse("Error, malformed tower side in request.", 32);
		}

		if (!in_array(strtoupper($_GET['towerGoal']), $towerGoals, true)) {
			errorResponse("Error, malformed goal in request.", 32);
		}

		$query_params[':towerSide'] = strtoupper($_GET['towerSide']);
		$query_params[':towerGoal'] = strtoupper($_GET['towerGoal']);

		executeSQL($query, $query_params);

		$query = "SELECT count(shotId) AS numberScored FROM Shot WHERE teamNumber = :teamId AND matchId = :matchId AND scored = 1";
		$query_params = array(':teamId' => intval($_GET['teamNumber']), ":matchId" => intval($_GET['matchId']));
		$result = executeSQLSingleRow($query, $query_params);

		$out = array("Addition" => "Successful", "");
		echo json_encode($out);

		break;
	case "Crossing":
		$requiredPOSTKeys = array('type', 'matchId', 'teamNumber', 'defenseName', 'scoutTeamNumber');
		if (count(array_diff($requiredPOSTKeys, array_keys($_GET))) != 0) {
			errorResponse("Error, email and/or password is missing.", 31);
		}

		$query = "INSERT INTO Crossing (type, matchId, teamNumber, defenseName, scoutTeamNumber) VALUES (:matchId, :teamNumber, :defenseName, :scoutTeamNumber)";
		$query_params = array(":matchId" => intval($_GET['matchId']), ":teamNumber" => intval($_GET['teamNumber']), ":scoutTeamNumber" => intval($_GET['scoutTeamNumber']));

		if (!in_array(strtoupper($_GET['defenseName']), $defenseNames, true)) {
			errorResponse("Error, malformed defense name in request.", 32);
		}

		$query_params[':defenseName'] = strtoupper($_GET['defenseName']);

		executeSQL($query, $query_params);

		$query = "SELECT count(crossingId) AS defense FROM Crossing WHERE teamNumber = :teamId AND matchId = :matchId";
		$query_params = array(':teamId' => intval($_GET['teamNumber']), ":matchId" => intval($_GET['matchId']));
		$result = executeSQLSingleRow($query, $query_params);

		$out = array("Addition" => "Successful", "");
		echo json_encode($out);

		break;
	case "Scale":
		$requiredPOSTKeys = array('type', 'matchId', 'teamNumber', 'towerSide', 'scoutTeamNumber');
		if (count(array_diff($requiredPOSTKeys, array_keys($_GET))) != 0) {
			errorResponse("Error, email and/or password is missing.", 31);
		}

		if (!in_array(strtoupper($_GET['towerSide']), $towerSides, true)) {
			errorResponse("Error, malformed tower side in request.", 32);
		}

		break;
	case "NOTE":
		$requiredPOSTKeys = array('type', 'teamNumber', 'contents', 'scoutTeamNumber');
		if (count(array_diff($requiredPOSTKeys, array_keys($_GET))) != 0) {
			errorResponse("Error, email and/or password is missing.", 31);
		}


		break;
}