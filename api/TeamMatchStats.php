<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/scouting/components/common.php");
require_once($setting->getAppPath() . $setting->getClassPath() . '/Data/GameStats/MatchTeam.php');

// Check all page conditions.
if ($_SERVER['REQUEST_METHOD'] != "GET") {
	errorResponse("Error, must GET to this page.", 2, 405);
}
validateAPIAccess($_GET);

$requiredPOSTKeys = array('compKey', 'teamNumber', 'matchNumber');

if (count(array_diff($requiredPOSTKeys, array_keys($_GET))) != 0) {
	errorResponse("Error, required fields are missing. Make sure to include 'compKey', 'teamNumber', and 'matchNumber'", 31);
}

echo json_encode(new Data\GameStats\MatchTeam($_GET['teamNumber'], $_GET['compKey'], $_GET['matchNumber'], $_GET['scoutTeamNumber']));