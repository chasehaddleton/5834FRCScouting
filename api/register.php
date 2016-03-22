<?php
require_once(dirname(dirname(__DIR__)) . "/common.php");
require_once($setting->getAppPath() . $setting::classPath . "Authentication/User.php");

// Check all page conditions.
if ($_SERVER['REQUEST_METHOD'] != "POST") {
	errorResponse("Error, must POST to this page.", 1, 405);
}
$requiredPOSTKeys = array('email', 'password', 'name', 'teamNumber');
if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
	errorResponse("Error, must POST name, email, team number, and password", 10);
}

// Do the stuff for this page.

Authentication\User::create($_POST['name'], $_POST['email'], $_POST['teamNumber'], $_POST['password'], $_POST['phoneNumber']);

$out = array("Success");
echo json_encode($out);