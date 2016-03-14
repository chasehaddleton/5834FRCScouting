<?php
require_once('../components/common.php');

$loggedOut = session_destroy();

if ($loggedOut) {
	redirect($setting->getAppURL());
} else {
	die("Error logging you out, please contact webmaster@chasehaddleton.com");
}