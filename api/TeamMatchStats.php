<?php
require_once('../components/common.php');
require_once($setting->getAppPath() . '/components/Data/MatchTeam.php');

// Check all page conditions.
if ($_SERVER['REQUEST_METHOD'] != "GET") {
	errorResponse("Error, must GET to this page.", 2, 405);
}
validateAPIAccess($_GET);

/*
 * How many ways can we ask for match stats?
 *
 * Team number is always needed.
 * matchId is internal and should not be revealed outside of the API, so all requests should operate based off
 * competition and match number. Internal lookup can be done to find the matchId given those fields.
 */

$requiredPOSTKeys = array('compKey', 'teamNumber', 'matchNumber');

if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
	errorResponse("Error, required fields are missing. Make sure to include 'compKey', 'teamNumber', and 'matchNumber'", 31);
}

