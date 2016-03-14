<?php
class Settings {
	protected $applicationRoot = "/scouting";
	protected $applicationName = "The Red Alliance";
	protected $TBAHeader = "X-TBA-App-Id: 5834:scouting-site:v0.01";
	protected $dbPrefix = "scouting";
	protected $dbHost;
	protected $dbName;
	protected $username;
	protected $password;

	public function __construct() {
		$this->dbHost = "localhost";
		$this->dbName = "riverdalerobotics";
		$this->username = "riverdalerobotic";
		$this->password = "SnkkVC4MaVRbgcqx";
	}

	public function getAppPath() {
		return $_SERVER['DOCUMENT_ROOT'] . $this->applicationRoot;
	}

	public function getAppURL() {
		return "//" . $_SERVER['SERVER_NAME'] . $this->applicationRoot;
	}

	public function getDbPrefix() {
		return $this->dbPrefix;
	}

	/**
	 * @return string
	 */
	public function getTBAHeader() {
		return $this->TBAHeader;
	}

	/**
	 * @return string name of the application
	 */
	public function getApplicationName() {
		return $this->applicationName;
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