<?php
namespace Authentication;

class APIKey {
	private static $API_KEY_PEPPER = "Z2tZBUxV8*3d*vHOf@*nf5mBzrvEiq9k5M9E%!n&$4OtoDpy*M5vd!#^U4SZ5Qtmaw!frMG*JvfJ3CuDax83r6ijjwup846HjlRg";
	public $key;
	protected $userId;

	public function __construct($userId, $teamNumber) {
		$user = new User($userId);
		$this->userId = $userId;

		$key = hash_hmac("sha512", $userId . $teamNumber, $user->uniqId . static::$API_KEY_PEPPER);
		$this->key = $key;
	}

	public function setAPIKey() {
		$query = "INSERT INTO APIKey (apiKey, userId, creationDate) VALUES (:apiKey, :userId, :curDate)";
		$query_params = array(":apiKey" => $this->key, ":userId" => $this->userId, ":curDate" => date('Y-m-d G:i:s'));
		executeSQL($query, $query_params);
	}
}