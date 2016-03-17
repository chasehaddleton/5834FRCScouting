<?php

namespace Authentication;
require_once("APIKey.php");

/**
 * Class Users
 *
 * This class defines the basic structure for a site user.
 */
class User {
	public $userId;
	public $fullName;
	public $APIKey;
	public $scoutTeamNumber;
	protected $userData = array();

	// Determine and store the ID of the user.

	public function __construct($id) {
		$query = (is_numeric($id)) ? "SELECT * FROM Users WHERE userId = :id" : "SELECT * FROM Users WHERE UCASE(email) = UCASE(:id)";
		$query_params = array(':id' => $id);
		$stmt = executeSQL($query, $query_params);
		$results = $stmt->fetch();

		foreach ($results as $col => $data) {
			$this->userData[$col] = $data;
		}

		$this->fullName = $this->userData['fullName'];
		$this->userId = $this->userData['userId'];
		$this->scoutTeamNumber = $this->userData['teamNumber'];
	}

	public function getAPIKey() {
		if (is_null($this->userData['APIKey']) || $this->userData['APIKey'] == "") {
			$key = new APIKey($this->userData['userId'], $this->userData['teamNumber'], $this->userData['uniqId']);
			$this->__set("APIKey", $key->getKey());
			$this->APIKey = $key->getKey();
		} else {
			$this->APIKey = $this->userData['APIKey'];
		}

		return $this->APIKey;
	}

	public function exists() {
		return !isset($userData);
	}

	public function __get($name) {
		return $this->userData[$name];
	}

	public function __set($name, $value) {
		if ($name == "password") {
			$value = password_hash($value, PASSWORD_DEFAULT);
		} else {
			$value = htmlspecialchars($value);
		}

		$query = "UPDATE Users SET $name = :value WHERE userId = :id";
		$query_params = array(':id' => $this->userData['userId'], ':value' => $value);
		executeSQL($query, $query_params);
	}

	public function delete() {
		$query = "DELETE FROM Users WHERE userId = :id";
		$query_params = array(':id' => $this->userData['userId']);
		executeSQL($query, $query_params);
	}
}

