<?php
require_once('../components/common.php');

// Check all page conditions.

if ($_SERVER['REQUEST_METHOD'] != "POST") {
	errorResponse("Error, must POST to this page.", 1);
}

$requiredPOSTKeys = array('apiKey', 'userId', 'scoutTeamNumber');

if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
	errorResponse("Error, apiKey and/or related data missing from request. Must provide apiKey, userId and teamNumber", 2);
}

// Do the stuff for this page.

if (verifyAPIKey($_POST['apiKey'], $_POST['userId'], $_POST['scoutTeamNumber'])) {
	errorResponse("Key matches", -1);
}

errorResponse("Key invalid.", 12);