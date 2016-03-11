<?php
require_once("Settings.php");

/**
 * Class Users
 *
 * This class defines the basic structure for a site user.
 */
class Users {
	private static $API_KEY_PEPPER = "@(*TRYU(HNSDF*(1324{}:>{}AF1";
	public $userId;
	public $fullName;
	public $key;
	protected $userData = array();
	
	// Determine and store the ID of the user.

	public function __construct($id) {
		$setting = new Settings();

		$query = (is_numeric($id)) ? "SELECT * FROM Users WHERE id = :id" : "SELECT * FROM Users WHERE UCASE(email) = UCASE(:id)";
		$query_params = array(':id' => $id);

		$stmt = executeSQL($query, $query_params);
		$results = $stmt->fetch();

		foreach ($results as $col => $data) {
			$this->userData[$col] = $data;
		}

		$this->fullName = $this->userData['fullName'];
		$this->userId = $this->userData['userId'];
	}

	public function getAPIKey() {
		$setting = new Settings();

		$query = "SELECT apiKey FROM APIKey WHERE userId = :userId";
		$query_params = array(':userId' => $this->userData['userId']);

		$stmt = executeSQL($query, $query_params);

		$row = $stmt->fetch();

		if ($row) {
			$this->key = $row['apiKey'];
		} else {
			$this->key = $this->generateAPIKey();
		}

		return $this->key;
	}

	private function generateAPIKey() {
		$setting = new Settings();

		$key = hash_hmac("sha512", $this->userData['userId'] . $this->userData['teamNumber'], $this->userData['email'] . static::$API_KEY_PEPPER);

		$query = "INSERT INTO APIKey (apiKey, userId, creationDate) VALUES (:apiKey, :userId, :curDate)";
		$query_params = array(":apiKey" => $key, ":userId" => $this->userData['userId'], ":curDate" => date('Y-m-d G:i:s'));

		executeSQL($query, $query_params);

		return $key;
	}

	public function exists() {
		return !isset($userData);
	}

	public function __get($name) {
		return $this->userData[$name];
	}

	public function __set($name, $value) {
		$setting = new Settings();

		if ($name == "password") {
			$value = password_hash($value, PASSWORD_DEFAULT);
		} else {
			$value = htmlspecialchars($value);
		}

		$query = "UPDATE " . $setting->getDbPrefix() . "users SET $name = :value WHERE id = :id";
		$query_params = array(':id' => $this->userData['id'], ':value' => $value);

		executeSQL($query, $query_params);
	}

	public function delete() {
		$setting = new Settings();

		$query = "DELETE FROM Users WHERE id = :id";
		$query_params = array(':id' => $this->userData['id']);

		executeSQL($query, $query_params);
	}
}
