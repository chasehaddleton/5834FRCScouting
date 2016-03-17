<?php
class Settings {
	protected $applicationRoot = "/scouting";
	protected $applicationName = "Scoutr";
	protected $applicationVersionNumber = 0.2;
	protected $TBAHeader = "X-TBA-App-Id: RedAlliance:";
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
		$this->TBAHeader .= $this->applicationName . ":v" . $this->applicationVersionNumber;
	}

	public function getAppPath() {
		return $_SERVER['DOCUMENT_ROOT'] . $this->applicationRoot;
	}

	public function getAppURL() {
		return "//" . $_SERVER['SERVER_NAME'] . $this->applicationRoot;
	}

	/**
	 * @return string
	 */
	public function getApplicationVersionNumber() {
		return "" . $this->applicationVersionNumber;
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