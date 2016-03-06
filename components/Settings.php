<?php
class Settings {
	protected $_APPLICATION_PATH = "/scouting";
	protected $dbHost;
	protected $dbName;
	protected $username;
	protected $password;

	public function __construct() {
		$this->dbHost = $_ENV['dbHost'];
		$this->dbName = $_ENV['dbName'];
		$this->username = $_ENV['dbUsername'];
		$this->password = $_ENV['dbPassword'];
	}

	public function getAppPath() {
		return $_SERVER['DOCUMENT_ROOT'] . $this->_APPLICATION_PATH;
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