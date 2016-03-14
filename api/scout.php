<?php
include_once("../components/Settings.php");
$setting = new Settings();

require_once($setting->getAppPath() . '/components/common.php');
require_once($setting->getAppPath() . '/components/User.php');

// Check all page conditions.

if ($_SERVER['REQUEST_METHOD'] != "POST") {
	errorResponse("Error, must POST to this page.", 1);
}

$requiredPOSTKeys = array('apiKey', 'userId', 'teamNumber');

if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
	errorResponse("Error, email and/or password is missing.", 2);
}

// Do the stuff for this page.

if (verifyAPIKey($_POST['apiKey'], $_POST['userId'], $_POST['teamNumber'])) {
	errorResponse("Key matches", -1);
}

errorResponse("No match", 4);