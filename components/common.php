<?php
include_once("Settings.php");
$setting = new Settings();

if (!isset($_SESSION)) {
	session_start();
}

try {
	$db = new PDO("mysql:host=" . $setting->getDbHost() . "; dbname=" . $setting->getDbName(), $setting->getUsername(), $setting->getPassword());
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
	//logMessage("Failed to run query: " . $ex->getMessage() . ". Query: " . $query, $_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR']);

	die("Failed to connect to the database: " . $ex->getMessage());
}

$self = $_SERVER['REQUEST_URI'];
include_once($setting->getAppPath() . "/components/functions.php");