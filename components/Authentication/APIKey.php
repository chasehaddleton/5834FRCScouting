<?php
namespace Authentication;


class APIKey {
	private static $API_KEY_PEPPER = "Z2tZBUxV8*3d*vHOf@*nf5mBzrvEiq9k5M9E%!n&$4OtoDpy*M5vd!#^U4SZ5Qtmaw!frMG*JvfJ3CuDax83r6ijjwup846HjlRg";
	public $key;

	public function __construct($userId, $teamNumber, $uniqueId) {
		$key = hash_hmac("sha512", $userId . $teamNumber, $uniqueId . static::$API_KEY_PEPPER);

		$query = "INSERT INTO APIKey (apiKey, userId, creationDate) VALUES (:apiKey, :userId, :curDate)";
		$query_params = array(":apiKey" => $key, ":userId" => $userId, ":curDate" => date('Y-m-d G:i:s'));

		executeSQL($query, $query_params);

		$this->key = $key;
	}
}