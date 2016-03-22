<?php
require_once(dirname(__DIR__) . "/common.php");
require_once($setting->getAppPath() . $setting::classPath . "Data/GameAction/Challenge.php");
require_once($setting->getAppPath() . $setting::classPath . "Data/GameAction/Crossing.php");
require_once($setting->getAppPath() . $setting::classPath . "Data/GameAction/Note.php");
require_once($setting->getAppPath() . $setting::classPath . "Data/GameAction/Scale.php");
require_once($setting->getAppPath() . $setting::classPath . "Data/GameAction/Shot.php");

// Check all page conditions.

if ($_SERVER['REQUEST_METHOD'] != "POST") {
	errorResponse("Error, must POST to this page.", 2, 405);
}

validateAPIAccess($_POST);

$requiredPOSTKeys = array('type', 'matchNumber', 'compKey');
if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
	errorResponse("Error, fields required to add are missing. Make sure to include 'type', 'matchNumber', and 'compKey'", 31);
}



switch (strtoupper($_POST['type'])) {
	case "SHOT":
		$requiredPOSTKeys = array('teamNumber', 'towerSide', 'towerGoal', 'scored', 'auto', 'rampShot');
		if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
			errorResponse("Error, fields required to add a shot are missing. Make sure to include 'teamNumber', 'towerSide', 'towerGoal', 'scored', 'auto', and 'rampShot'", 31);
		} else {
			if (!in_array(strtoupper($_POST['towerSide']), Data\GameAction\GameAction::TOWER_SIDES, true)) {
				errorResponse("Error, malformed tower side in request. Valid options: 'LEFT', 'CENTER', and 'RIGHT'", 32);
			} else {
				if (!in_array(strtoupper($_POST['towerGoal']), Data\GameAction\GameAction::TOWER_GOALS, true)) {
					errorResponse("Error, malformed goal in request. Valid options: 'TOP', and 'BOTTOM'", 32);
				}
			}
		}
		
		$shot = Data\GameAction\Shot::create(
			$_POST['matchId'],
			$_POST['teamNumber'],
			$_POST['scored'],
			$_POST['auto'],
			$_POST['rampShot'],
			$_POST['towerSide'],
			$_POST['towerGoal'],
			$_POST['scoutTeamNumber'],
			$_POST['userId']
		);
		
		$out = array("Successfully added" => $shot);
		echo json_encode($out);
		
		break;
	
	case "CROSSING":
		$requiredPOSTKeys = array('teamNumber', 'defenseName', 'auto', 'assist');
		if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
			errorResponse("Error, fields required to add a crossing are missing. Make sure to include 'teamNumber', 'defenseName', 'auto', and 'assist'", 31);
		} else {
			if (!in_array(strtoupper($_POST['defenseName']), Data\GameAction\GameAction::DEFENSE_NAMES, true)) {
				errorResponse("Error, malformed defense name in request. Valid options: 'PORTCULLIS', 'CHEVALDEFRISE', 'MOAT', 'RAMPARTS', 'DRAWBRIDGE', 'SALLYPORT', 'ROCKWALL', 'ROUGHTERRAIN', 'LOWBAR'", 32);
			} else {
				if (!in_array(strtoupper($_POST['assist']), Data\GameAction\GameAction::TOWER_GOALS, true)) {
					errorResponse("Error, malformed goal in request. Valid options: 'NONE', 'PUSH', and 'OPEN'", 32);
				} else if (intval($_POST['slot']) > 5 && intval($_POST['slot']) < 1) {
					errorResponse("Error, malformed slot in request. Valid options: 1, 2, 3, 4, and 5", 32);
				}
			}
		}
		
		$crossing = Data\GameAction\Crossing::create(
			$_POST['matchId'],
			$_POST['teamNumber'],
			$_POST['slot'],
			$_POST['auto'],
			$_POST['assist'],
			$_POST['scoutTeamNumber'],
			$_POST['userId']
		);
		
		$out = array("Successfully added" => $crossing);
		echo json_encode($out);
		
		break;
	
	case "SCALE":
		$requiredPOSTKeys = array('teamNumber', 'towerSide');
		if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
			errorResponse("Error, fields required to add a scale are missing. Make sure to include 'teamNumber', and 'towerSide'", 31);
		} else {
			if (!in_array(strtoupper($_POST['towerSide']), Data\GameAction\GameAction::TOWER_SIDES, true)) {
				errorResponse("Error, malformed tower side in request. Valid options: 'LEFT', 'CENTER', 'RIGHT'", 32);
			}
		}
		
		$scale = Data\GameAction\Scale::create(
			$_POST['matchId'],
			$_POST['teamNumber'],
			$_POST['towerSide'],
			$_POST['scoutTeamNumber'],
			$_POST['userId']
		);
		
		$out = array("Successfully added" => $scale);
		echo json_encode($out);
		
		break;
	
	case "CHALLENGE":
		$requiredPOSTKeys = array('teamNumber', 'towerSide');
		if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
			errorResponse("Error, fields required to add a challenge are missing. Make sure to include 'teamNumber', and 'towerSide'", 31);
		} else {
			if (!in_array(strtoupper($_POST['towerSide']), Data\GameAction\GameAction::TOWER_SIDES, true)) {
				errorResponse("Error, malformed tower side in request. Valid options: 'LEFT', 'CENTER', 'RIGHT'", 32);
			}
		}
		
		$challenge = Data\GameAction\Challenge::create(
			$_POST['matchId'],
			$_POST['teamNumber'],
			$_POST['towerSide'],
			$_POST['scoutTeamNumber'],
			$_POST['userId']
		);
		
		$out = array("Successfully added" => $challenge);
		echo json_encode($out);
		
		break;
	
	case "NOTE":
		$requiredPOSTKeys = array('teamNumber', 'contents');
		if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
			errorResponse("Error, fields required to add a note are missing. Make sure to include 'teamNumber' and 'contents'", 31);
		}
		
		$note = Data\GameAction\Note::create(
			$_POST['matchId'],
			$_POST['teamNumber'],
			$_POST['contents'],
			$_POST['scoutTeamNumber'],
			$_POST['userId']
		);
		
		$out = array("Successfully added" => $note);
		echo json_encode($out);
		
		break;
}
