<?php
require($_SERVER['DOCUMENT_ROOT'] . '/components/common.php');
$loggedOut = session_destroy();

if ($loggedOut) {
	header("Location: /");
} else {
	die("Error logging you out, please contact webmaster@chasehaddleton.com");
}
?>