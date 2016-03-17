<?php
require_once('../components/common.php');
require_once($setting->getAppPath() . '/components/Authenticate/User.php');

// Check all page conditions.

if ($_SERVER['REQUEST_METHOD'] != "GET") {
	errorResponse("Error, must GET to this page.", 2);
}

$requiredGETKeys = array('apiKey', 'userId', 'scoutTeamNumber');
if (count(array_diff($requiredGETKeys, array_keys($_POST))) != 0) {
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
$defenseNames = array('PORTCULLIS' => 'PC', 'CHEVALDEFRISE' => 'CF', 'MOAT' => 'MT', 'RAMPARTS' => 'RP', 'DRAWBRIDGE' => 'DB', 'SALLYPORT' => 'SP', 'ROCKWALL' => 'RW', 'ROUGHTERRAIN' => 'RT', 'LOWBAR' => 'LB');

switch (strtoupper($_GET['type'])) {
	case "SHOT":
		$requiredGETKeys = array('matchId', 'teamNumber', 'towerSide', 'towerGoal', 'scored', 'scoutTeamNumber');
		
		if (count(array_diff($requiredGETKeys, array_keys($_GET))) != 0) {
			errorResponse("Error, fields required to add a shot are missing. Make sure to include 'type', 'matchId', 'teamNumber', 'towerSide', 'towerGoal', 'scored', and 'scoutTeamNumber'", 31);
		}

		$query = "INSERT INTO Shot (matchId, teamNumber, towerSide, towerGoal, scored, scoutTeamNumber) VALUES (:matchId, :teamNumber, :towerSide, :towerGoal, :scored, :scoutTeamNumber)";
		$query_params = array(":matchId" => intval($_GET['matchId']), ":teamNumber" => intval($_GET['teamNumber']), ":scoutTeamNumber" => intval($_GET['scoutTeamNumber']));

		$scored = (bool) $_GET['scored'];
		$query_params[':scored'] = ($scored) ? 1 : 0;

		if (!in_array(strtoupper($_GET['towerSide']), $towerSides, true)) {
			errorResponse("Error, malformed tower side in request. Valid options: 'LEFT', 'CENTER', 'RIGHT'", 32);
		}

		if (!in_array(strtoupper($_GET['towerGoal']), $towerGoals, true)) {
			errorResponse("Error, malformed goal in request. Valid options: 'TOP', 'BOTTOM'", 32);
		}

		$query_params[':towerSide'] = strtoupper($_GET['towerSide']);
		$query_params[':towerGoal'] = strtoupper($_GET['towerGoal']);

		executeSQL($query, $query_params);

		$out = array("Addition" => "Successfully added");
		echo json_encode($out);

		break;
	case "Crossing":
		$requiredGETKeys = array('matchId', 'teamNumber', 'defenseName', 'scoutTeamNumber');
		if (count(array_diff($requiredGETKeys, array_keys($_GET))) != 0) {
			errorResponse("Error, fields required to add a crossing are missing. Make sure to include 'matchId', 'teamNumber', 'defenseName', and 'scoutTeamNumber'", 31);
		}

		$query = "INSERT INTO Crossing (matchId, teamNumber, defenseName, scoutTeamNumber) VALUES (:matchId, :teamNumber, :defenseName, :scoutTeamNumber)";
		$query_params = array(":matchId" => intval($_GET['matchId']), ":teamNumber" => intval($_GET['teamNumber']), ":scoutTeamNumber" => intval($_GET['scoutTeamNumber']));

		if (!in_array(strtoupper($_GET['defenseName']), $defenseNames, true)) {
			errorResponse("Error, malformed defense name in request. Valid options: 'PORTCULLIS', 'CHEVALDEFRISE', 'MOAT', 'RAMPARTS', 'DRAWBRIDGE', 'SALLYPORT', 'ROCKWALL', 'ROUGHTERRAIN', 'LOWBAR'", 32);
		}

		$query_params[':defenseName'] = $defenseNames[strtoupper($_GET['defenseName'])];

		executeSQL($query, $query_params);

		$out = array("Addition" => "Successfully added");
		echo json_encode($out);

		break;
	case "Scale":
		$requiredGETKeys = array('matchId', 'teamNumber', 'towerSide', 'scoutTeamNumber');
		if (count(array_diff($requiredGETKeys, array_keys($_GET))) != 0) {
			errorResponse("Error, fields required to add a crossing are missing. Make sure to include 'matchId', 'teamNumber', 'towerSide', 'scoutTeamNumber'", 31);
		}

        $query = "INSERT INTO Scale (matchId, teamNumber, towerSide, scoutTeamNumber) VALUES (:matchId, :teamNumber, :towerSide, :scoutTeamNumber)";
        $query_params = array(":matchId" => intval($_GET['matchId']), ":teamNumber" => intval($_GET['teamNumber']), ":scoutTeamNumber" => intval($_GET['scoutTeamNumber']));

        if (!in_array(strtoupper($_GET['towerSide']), $towerSides, true)) {
            errorResponse("Error, malformed tower side in request.", 32);
        }

        executeSQL($query, $query_params);

        $out = array("Addition" => "Successfully added");
		echo json_encode($out);

		break;
	case "NOTE":
		$requiredGETKeys = array('teamNumber', 'contents', 'scoutTeamNumber');
		if (count(array_diff($requiredGETKeys, array_keys($_GET))) != 0) {
			errorResponse("Error, fields required to add a note are missing. Make sure to include 'teamNumber', 'contents', 'scoutTeamNumber'", 31);
		}

        $query = "INSERT INTO Notes (matchId, teamNumber, contents, scoutTeamNumber) VALUES (:matchId, :teamNumber, :contents, :scoutTeamNumber)";
        $query_params = array(":matchId" => intval($_GET['matchId']), ":teamNumber" => intval($_GET['teamNumber']), ":scoutTeamNumber" => intval($_GET['scoutTeamNumber']), ':contents' => $_GET['contents']);

        executeSQL($query, $query_params);

        $out = array("Addition" => "Successfully added");
		echo json_encode($out);

		break;
}
