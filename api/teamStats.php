<?php
require_once(dirname(dirname(__DIR__)) . "/common.php");
require_once($setting->getAppPath() . $setting::classPath . '/Data/GameStats/MatchTeam.php');

// Check all page conditions.
if ($_SERVER['REQUEST_METHOD'] != "GET") {
	errorResponse("Error, must GET to this page.", 2, 405);
}
validateAPIAccess($_GET);

$requiredPOSTKeys = array('compKey', 'teamNumber');

if (count(array_diff($requiredPOSTKeys, array_keys($_GET))) != 0) {
	errorResponse("Error, required fields are missing. Make sure to include 'compKey', and 'teamNumber'", 31);
}

echo json_encode(new Data\GameStats\MatchTeam($_GET['teamNumber'], $_GET['compKey'], $_GET['matchNumber'], $_GET['scoutTeamNumber']));