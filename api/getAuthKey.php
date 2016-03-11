<?php
include_once("../components/Settings.php");
$setting = new Settings();

require_once($setting->getAppPath() . '/components/common.php');
require_once($setting->getAppPath() . '/components/Users.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if (isset($_POST['email']) && isset($_POST['password'])) {
		$user = new Users($_POST['email']);

		if ($user->exists()) {
			if (password_verify($_POST['password'], $user->password)) {
				$user->getAPIKey();
				$out = array("login" => "good", "user" => $user);

				echo json_encode($out);
				die();
			}
		}

		print_r($user->exists());

		errorResponse("Error, bad login or user does not exist.", 3);

	} else {
		errorResponse("Error, email and/or password is missing.", 2);
	}
} else {
	errorResponse("Error, must POST to this page.", 1);
}
