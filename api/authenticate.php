<?php
include_once("../components/Settings.php");
$setting = new Settings();

require_once($setting->getAppPath() . '/components/common.php');
require_once($setting->getAppPath() . '/components/User.php');

// Check all page conditions.

if ($_SERVER['REQUEST_METHOD'] != "POST") {
	errorResponse("Error, must POST to this page.", 1);
}

$requiredPOSTKeys = array('email', 'password');

if (count(array_diff($requiredPOSTKeys, array_keys($_POST))) != 0) {
	errorResponse("Error, email and/or password is missing.", 2);
}

// Do the stuff for this page.

$user = new Users($_POST['email']);

if ($user->exists()) {
	if (password_verify($_POST['password'], $user->password)) {
		$user->getAPIKey();
		$out = array("login" => "good", "user" => $user);

		echo json_encode($out);
		die();
	}
}

errorResponse("Error, bad login or user does not exist.", 3);