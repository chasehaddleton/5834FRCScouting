<?php

/**
 * Class Users
 *
 * This class defines the basic structure for a site user.
 */
class Users {
	private $userData = array();

	// Determine and store the ID of the user.
	public function __construct($id) {
		$query = (is_numeric($id)) ? "SELECT * FROM users WHERE id = :id" : "SELECT * FROM users WHERE UCASE(email) = UCASE(:id)";

		$query_params = array(':id' => $id);

		$stmt = executeSQL($query, $query_params);
		$results = $stmt->fetch();

		foreach ($results as $col => $data) {
			$this->userData[$col] = $data;
		}
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

		$query = "UPDATE users SET $name = :value WHERE id = :id";
		$query_params = array(':id' => $this->userData['id'], ':value' => $value);

		executeSQL($query, $query_params);
	}

	public function delete() {
		$query = "DELETE FROM users WHERE id = :id";

		$query_params = array(':id' => $this->userData['id']);

		executeSQL($query, $query_params);
	}
}
