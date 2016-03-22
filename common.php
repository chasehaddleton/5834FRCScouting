<?php

class Settings {
	const applicationRoot = "/scouting";
	const applicationName = "Scoutr";
	const applicationVersionNumber = 0.35;
	const classPath = "/components";
	const dbPrefix = "scouting";
	protected $TBAHeader = "X-TBA-App-Id: RedAlliance:";
	protected $dbHost;
	protected $dbName;
	protected $username;
	protected $password;
	
	public function __construct() {
		$this->dbHost = "localhost";
		$this->dbName = "riverdalerobotics";
		$this->username = "riverdalerobotic";
		$this->password = "SnkkVC4MaVRbgcqx";
		$this->TBAHeader .= $this::applicationName . ":v" . $this::applicationVersionNumber;
	}
	
	public function getAppPath() {
		return $_SERVER['DOCUMENT_ROOT'] . $this::applicationRoot;
	}
	
	public function getAppURL() {
		return "//" . $_SERVER['SERVER_NAME'] . $this::applicationRoot;
	}
	
	/**
	 * @return string
	 */
	public function getTBAHeader() {
		return $this->TBAHeader;
	}
	
	/**
	 * @return string database host address.
	 */
	public function getDbHost() {
		return $this->dbHost;
	}
	
	/**
	 * @return string name of the database.
	 */
	public function getDbName() {
		return $this->dbName;
	}
	
	/**
	 * @return string authentication username.
	 */
	public function getUsername() {
		return $this->username;
	}
	
	/**
	 * @return string authentication password.
	 */
	public function getPassword() {
		return $this->password;
	}
}

$setting = new Settings();

include_once($setting->getAppPath() . $setting::classPath . "/functions.php");

if (!isset($_SESSION)) {
	session_start();
}

try {
	$db = new PDO("mysql:host=" . $setting->getDbHost() . "; dbname=" . $setting->getDbName(), $setting->getUsername(), $setting->getPassword());
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
	error_log("Unable to connect to the database: " . $ex->getMessage());
	die("Failed to connect to the database. Please contact the webmaster if this persists.");
}

$self = $_SERVER['REQUEST_URI'];