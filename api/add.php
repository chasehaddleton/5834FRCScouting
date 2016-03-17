<?php
require_once('../components/common.php');

// Check all page conditions.

if ($_SERVER['REQUEST_METHOD'] != "POST") {
	errorResponse("Error, must POST to this page.", 2, 405);
}

validateAPIAccess($_POST);

if (!key_exists("type", $_POST)) {
	errorResponse("Error, must specify crossing type.", 30);
}

$towerSides = array('LEFT', 'CENTER', 'RIGHT');
$towerGoals = array('TOP', 'BOTTOM');
$defenseNames = array('PORTCULLIS' => 'PC', 'CHEVALDEFRISE' => 'CF', 'MOAT' => 'MT', 'RAMPARTS' => 'RP', 'DRAWBRIDGE' => 'DB', 'SALLYPORT' => 'SP', 'ROCKWALL' => 'RW', 'ROUGHTERRAIN' => 'RT', 'LOWBAR' => 'LB');

switch (strtoupper($_POST['type'])) {
	case "SHOT":
		$requiredPOSTKeys = array('matchId', 'teamNumber', 'towerSide', 'towerGoal', 'scored');
		
		if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
			errorResponse("Error, fields required to add a shot are missing. Make sure to include 'type', 'matchId', 'teamNumber', 'towerSide', 'towerGoal', and 'scored'", 31);
		} else {
			if (!in_array(strtoupper($_POST['towerSide']), $towerSides, true)) {
				errorResponse("Error, malformed tower side in request. Valid options: 'LEFT', 'CENTER', 'RIGHT'", 32);
			} else {
				if (!in_array(strtoupper($_POST['towerGoal']), $towerGoals, true)) {
					errorResponse("Error, malformed goal in request. Valid options: 'TOP', 'BOTTOM'", 32);
				}
			}
		}

		$query = "INSERT INTO Shot (matchId, teamNumber, towerSide, towerGoal, scored, scoutTeamNumber) VALUES (:matchId, :teamNumber, :towerSide, :towerGoal, :scored, :scoutTeamNumber)";
		$query_params = array(":matchId" => intval($_POST['matchId']), ":teamNumber" => intval($_POST['teamNumber']), ":scoutTeamNumber" => intval($_POST['scoutTeamNumber']));
		$query_params[':scored'] = (strtoupper($_POST['scored']) === "TRUE") ? 1 : 0;
		$query_params[':towerSide'] = strtoupper($_POST['towerSide']);
		$query_params[':towerGoal'] = strtoupper($_POST['towerGoal']);

		executeSQL($query, $query_params);

		$out = array("Addition" => "Successfully added");
		echo json_encode($out);

		break;
	case "Crossing":
		$requiredPOSTKeys = array('matchId', 'teamNumber', 'defenseName');
		if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
			errorResponse("Error, fields required to add a crossing are missing. Make sure to include 'matchId', 'teamNumber', and 'defenseName'", 31);
		} else {
			if (!in_array(strtoupper($_POST['defenseName']), $defenseNames, true)) {
				errorResponse("Error, malformed defense name in request. Valid options: 'PORTCULLIS', 'CHEVALDEFRISE', 'MOAT', 'RAMPARTS', 'DRAWBRIDGE', 'SALLYPORT', 'ROCKWALL', 'ROUGHTERRAIN', 'LOWBAR'", 32);
			}
		}

		$query = "INSERT INTO Crossing (matchId, teamNumber, defenseName, scoutTeamNumber) VALUES (:matchId, :teamNumber, :defenseName, :scoutTeamNumber)";
		$query_params = array(":matchId" => intval($_POST['matchId']), ":teamNumber" => intval($_POST['teamNumber']), ":scoutTeamNumber" => intval($_POST['scoutTeamNumber']));
		$query_params[':defenseName'] = $defenseNames[strtoupper($_POST['defenseName'])];

		executeSQL($query, $query_params);

		$out = array("Addition" => "Successfully added");
		echo json_encode($out);

		break;
	case "Scale":
		$requiredPOSTKeys = array('matchId', 'teamNumber', 'towerSide');
		if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
			errorResponse("Error, fields required to add a crossing are missing. Make sure to include 'matchId', 'teamNumber', and 'towerSide'", 31);
		} else {
			if (!in_array(strtoupper($_POST['towerSide']), $towerSides, true)) {
				errorResponse("Error, malformed tower side in request. Valid options: 'LEFT', 'CENTER', 'RIGHT'", 32);
			}
		}

		$query = "INSERT INTO Scale (matchId, teamNumber, towerSide, scoutTeamNumber) VALUES (:matchId, :teamNumber, :towerSide, :scoutTeamNumber)";
		$query_params = array(":matchId" => intval($_POST['matchId']), ":teamNumber" => intval($_POST['teamNumber']), ":scoutTeamNumber" => intval($_POST['scoutTeamNumber']));

		executeSQL($query, $query_params);

		$out = array("Addition" => "Successfully added");
		echo json_encode($out);

		break;
	case "NOTE":
		$requiredPOSTKeys = array('teamNumber', 'contents');
		if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
			errorResponse("Error, fields required to add a note are missing. Make sure to include 'teamNumber' and 'contents'", 31);
		}

		$query = "INSERT INTO Notes (matchId, teamNumber, contents, scoutTeamNumber) VALUES (:matchId, :teamNumber, :contents, :scoutTeamNumber)";
		$query_params = array(":matchId" => intval($_POST['matchId']), ":teamNumber" => intval($_POST['teamNumber']), ":scoutTeamNumber" => intval($_POST['scoutTeamNumber']), ':contents' => $_POST['contents']);

		executeSQL($query, $query_params);

		$out = array("Addition" => "Successfully added");
		echo json_encode($out);

		break;
}
