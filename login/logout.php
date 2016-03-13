<?php
include_once("../components/Settings.php");
$setting = new Settings();

require_once($setting->getAppPath() . '/components/common.php');

$loggedOut = session_destroy();

if ($loggedOut) {
	redirect($setting->getAppURL());
} else {
	die("Error logging you out, please contact webmaster@chasehaddleton.com");
}
?>