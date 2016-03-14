<?php

namespace Authentication;
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
		$query = "SELECT apiKey FROM APIKey WHERE userId = :userId";
		$query_params = array(':userId' => $this->userData['userId']);
		$stmt = executeSQL($query, $query_params);
		$row = $stmt->fetch();

		if ($row) {
			$this->APIKey = $row['apiKey'];
		} else {
			$key = new APIKey($this->userData['userId'], $this->userData['teamNumber'], $this->userData['uniqId']);
			$key->setAPIKey();
			$this->APIKey = $key;
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

		$query = "UPDATE Users SET $name = :value WHERE id = :id";
		$query_params = array(':id' => $this->userData['id'], ':value' => $value);
		executeSQL($query, $query_params);
	}

	public function delete() {
		$query = "DELETE FROM Users WHERE id = :id";
		$query_params = array(':id' => $this->userData['id']);
		executeSQL($query, $query_params);
	}
}

