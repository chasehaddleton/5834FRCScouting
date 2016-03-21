<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/scouting/components/common.php");

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
$assist = array('NONE', 'PUSH', 'OPEN');

switch (strtoupper($_POST['type'])) {
	case "SHOT":
		$requiredPOSTKeys = array('matchId', 'teamNumber', 'towerSide', 'towerGoal', 'scored', 'auto', 'rampShot');
		if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
			errorResponse("Error, fields required to add a shot are missing. Make sure to include 'matchId', 'teamNumber', 'towerSide', 'towerGoal', 'scored', 'auto', and 'rampShot'", 31);
		} else {
			if (!in_array(strtoupper($_POST['towerSide']), $towerSides, true)) {
				errorResponse("Error, malformed tower side in request. Valid options: 'LEFT', 'CENTER', and 'RIGHT'", 32);
			} else {
				if (!in_array(strtoupper($_POST['towerGoal']), $towerGoals, true)) {
					errorResponse("Error, malformed goal in request. Valid options: 'TOP', and 'BOTTOM'", 32);
				}
			}
		}

		$query = "INSERT INTO Shot (matchId, teamNumber, scored, auto, rampShot, towerSide, towerGoal, scoutTeamNumber, userId) "
			. "VALUES (:matchId, :teamNumber, :scored, :auto, :rampShot, :towerSide, :towerGoal, :scoutTeamNumber, :userId)";
		$query_params = array(
			":matchId" => intval($_POST['matchId']),
			":teamNumber" => intval($_POST['teamNumber']),
			":scored" => (strtoupper($_POST['scored']) === "TRUE") ? 1 : 0,
			":auto" => (strtoupper($_POST['auto']) === "TRUE") ? 1 : 0,
			":rampShot" => (strtoupper($_POST['rampShot']) === "TRUE") ? 1 : 0,
			":towerSide" => strtoupper($_POST['towerSide']),
			":towerGoal" => strtoupper($_POST['towerGoal']),
			":scoutTeamNumber" => intval($_POST['scoutTeamNumber']),
			":userId" => intval($_POST['userId'])
		);
		executeSQL($query, $query_params);

		$out = array("Addition" => "Successfully added");
		echo json_encode($out);

		break;

	case "CROSSING":
		$requiredPOSTKeys = array('matchId', 'teamNumber', 'defenseName', 'auto', 'assist');
		if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
			errorResponse("Error, fields required to add a crossing are missing. Make sure to include 'matchId', 'teamNumber', 'defenseName', 'auto', and 'assist'", 31);
		} else {
			if (!in_array(strtoupper($_POST['defenseName']), $defenseNames, true)) {
				errorResponse("Error, malformed defense name in request. Valid options: 'PORTCULLIS', 'CHEVALDEFRISE', 'MOAT', 'RAMPARTS', 'DRAWBRIDGE', 'SALLYPORT', 'ROCKWALL', 'ROUGHTERRAIN', 'LOWBAR'", 32);
			} else {
				if (!in_array(strtoupper($_POST['assist']), $assist, true)) {
					errorResponse("Error, malformed goal in request. Valid options: 'NONE', 'PUSH', and 'OPEN'", 32);
				}
			}
		}

		$query = "INSERT INTO Crossing (matchId, teamNumber, defenseName, auto, assist, scoutTeamNumber, userId) " .
			"VALUES (:matchId, :teamNumber, :defenseName, :auto, :assist, :scoutTeamNumber, :userId)";
		$query_params = array(
			":matchId" => intval($_POST['matchId']),
			":teamNumber" => intval($_POST['teamNumber']),
			":defenseName" => $defenseNames[strtoupper($_POST['defenseName'])],
			":auto" => (strtoupper($_POST['auto']) === "TRUE") ? 1 : 0,
			":assist" => strtoupper($_POST['assist']),
			":scoutTeamNumber" => intval($_POST['scoutTeamNumber']),
			":userId" => intval($_POST['userId'])
		);
		executeSQL($query, $query_params);

		$out = array("Addition" => "Successfully added");
		echo json_encode($out);

		break;

	case "SCALE":
		$requiredPOSTKeys = array('matchId', 'teamNumber', 'towerSide');
		if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
			errorResponse("Error, fields required to add a scale are missing. Make sure to include 'matchId', 'teamNumber', and 'towerSide'", 31);
		} else {
			if (!in_array(strtoupper($_POST['towerSide']), $towerSides, true)) {
				errorResponse("Error, malformed tower side in request. Valid options: 'LEFT', 'CENTER', 'RIGHT'", 32);
			}
		}

		$query = "INSERT INTO Scale (matchId, teamNumber, towerSide, scoutTeamNumber, userId) "
			. "VALUES (:matchId, :teamNumber, :towerSide, :scoutTeamNumber, :userId)";
		$query_params = array(
			":matchId" => intval($_POST['matchId']),
			":teamNumber" => intval($_POST['teamNumber']),
			":towerSide" => strtoupper($_POST['towerSide']),
			":scoutTeamNumber" => intval($_POST['scoutTeamNumber']),
			":userId" => intval($_POST['userId'])
		);
		executeSQL($query, $query_params);

		$out = array("Addition" => "Successfully added");
		echo json_encode($out);

		break;

	case "CHALLENGE":
		$requiredPOSTKeys = array('matchId', 'teamNumber', 'towerSide');
		if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
			errorResponse("Error, fields required to add a challenge are missing. Make sure to include 'matchId', 'teamNumber', and 'towerSide'", 31);
		} else {
			if (!in_array(strtoupper($_POST['towerSide']), $towerSides, true)) {
				errorResponse("Error, malformed tower side in request. Valid options: 'LEFT', 'CENTER', 'RIGHT'", 32);
			}
		}

		$query = "INSERT INTO Challenge (matchId, teamNumber, towerSide, scoutTeamNumber, userId) "
			. "VALUES (:matchId, :teamNumber, :towerSide, :scoutTeamNumber, :userId)";
		$query_params = array(
			":matchId" => intval($_POST['matchId']),
			":teamNumber" => intval($_POST['teamNumber']),
			":towerSide" => strtoupper($_POST['towerSide']),
			":scoutTeamNumber" => intval($_POST['scoutTeamNumber']),
			":userId" => intval($_POST['userId'])
		);
		executeSQL($query, $query_params);

		$out = array("Addition" => "Successfully added");
		echo json_encode($out);

		break;

	case "NOTE":
		$requiredPOSTKeys = array('teamNumber', 'contents');
		if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
			errorResponse("Error, fields required to add a note are missing. Make sure to include 'teamNumber' and 'contents'", 31);
		}

		$query = "INSERT INTO Notes (matchId, teamNumber, contents, scoutTeamNumber, userId)"
			. "VALUES (:matchId, :teamNumber, :contents, :scoutTeamNumber, :userId)";
		$query_params = array(
			":matchId" => intval($_POST['matchId']),
			":teamNumber" => intval($_POST['teamNumber']),
			':contents' => $_POST['contents'],
			":scoutTeamNumber" => intval($_POST['scoutTeamNumber']),
			":userId" => intval($_POST['userId'])
		);
		executeSQL($query, $query_params);

		$out = array("Addition" => "Successfully added");
		echo json_encode($out);

		break;
}
