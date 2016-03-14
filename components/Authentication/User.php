<?php

namespace Authentication {
	/**
	 * Class Users
	 *
	 * This class defines the basic structure for a site user.
	 */
	class User {
		private static $API_KEY_PEPPER = "Z2tZBUxV8*3d*vHOf@*nf5mBzrvEiq9k5M9E%!n&$4OtoDpy*M5vd!#^U4SZ5Qtmaw!frMG*JvfJ3CuDax83r6ijjwup846HjlRg";
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

		public static function generateAPIKeyFor($userId, $teamNumber) {
			$user = new Users($userId);
			return hash_hmac("sha512", $userId . $teamNumber, $user->uniqId . static::$API_KEY_PEPPER);
		}

		public function getAPIKey() {
			$query = "SELECT apiKey FROM APIKey WHERE userId = :userId";
			$query_params = array(':userId' => $this->userData['userId']);

			$stmt = executeSQL($query, $query_params);

			$row = $stmt->fetch();

			if ($row) {
				$this->APIKey = $row['apiKey'];
			} else {
				$this->APIKey = $this->generateAPIKey();
			}

			return $this->APIKey;
		}

		private function generateAPIKey() {
			$key = hash_hmac("sha512", $this->userData['userId'] . $this->userData['teamNumber'], $this->userData['uniqId'] . static::$API_KEY_PEPPER);

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

}