<?php

namespace Authentication;
require_once("../../common.php");
require_once("APIKey.php");

/**
 * Class Users
 *
 * This class defines the basic structure for a site user.
 *
 * @property string userId
 * @property string fullName
 * @property string teamNumber
 * @property string password
 * @property string uniqId
 * @property string APIKey
 * @property string level
 * @property string phoneNumber
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

	/**
	 * Retrieve a user's unique ID.
	 *
	 * @param $userId int User's ID.
	 * @return string User's unique ID
	 */
	public static function getUniqueIdFromUserId($userId) {
		$query = "SELECT uniqId FROM Users WHERE userId = :id";
		$query_params = array(':id' => $userId);
		$row = executeSQLSingleRow($query, $query_params);
		return $row['uniqId'];
	}
	
	public static function create($name, $email, $teamNumber, $password, $phoneNumber) {
		global $db;
		
		$query = "SELECT 1 FROM Users WHERE email = :email";
		$query_params = array(':email' => $email);
		$row = executeSQLSingleRow($query, $query_params);
		
		// Return an error message if the email address is already in use.
		if ($row) {
			errorResponse("Error, This email address is already registered", 11);
		}
		
		// Prepare the password.
		$query = "INSERT INTO scoutingUsers (fullName, email, teamNumber, password, uniqId, phoneNumber) " .
			" VALUES (:name, :email, :teamNumber, :password, :uniqId, :phoneNumber)";
		$query_params = array(
			':name' => htmlspecialchars(ucwords($name)),
			':email' => htmlspecialchars(strtolower($email)),
			':teamNumber' => intval($teamNumber),
			':password' => password_hash($password, PASSWORD_DEFAULT),
			':uniqId' => User::genNewUniqueId(),
			':phoneNumber' => preg_replace("([^0-9])", "", $phoneNumber)
		);
		executeSQL($query, $query_params);
		
		$id = $db->lastInsertId();
		logMessage("Account created through API", $id);
	}
	
	/**
	 * Generate a new unique ID.
	 *
	 * @return string A new unique ID
	 */
	public static function genNewUniqueId() {
		return uniqid(mt_rand(), true);
	}

	/**
	 * Get a user's API key.
	 *
	 * @return string The user's API key
	 */
	public function getAPIKey() {
		if (is_null($this->userData['APIKey']) || $this->userData['APIKey'] == "" || intval($this->userData['APIKey']) < 0) {
			$key = new APIKey($this->userData['userId'], $this->userData['teamNumber'], $this->userData['uniqId']);
			$this->__set("APIKey", $key->getKey());
			$this->APIKey = $key->getKey();
		} else {
			$this->APIKey = $this->userData['APIKey'];
		}

		return $this->APIKey;
	}

	/**
	 * Check if the user exists.
	 *
	 * @return bool If the APIKey exists
	 */
	public function exists() {
		return !isset($userData);
	}

	/**
	 * Retrieve a value from the user's row.
	 *
	 * @param $name string Name of the field.
	 * @return mixed Data requested.
	 */
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

	/**
	 * Delete a user from the table.
	 */
	public function delete() {
		$query = "DELETE FROM Users WHERE userId = :id";
		$query_params = array(':id' => $this->userData['userId']);
		executeSQL($query, $query_params);
	}
}

