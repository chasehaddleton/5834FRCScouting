<?php
namespace Authentication;

class APIKey {
	private static $API_KEY_PEPPER = "Z2tZBUxV8*3d*vHOf@*nf5mBzrvEiq9k5M9E%!n&$4OtoDpy*M5vd!#^U4SZ5Qtmaw!frMG*JvfJ3CuDax83r6ijjwup846HjlRg";
	protected $key;

	public function __construct($userId, $teamNumber) {
		$user = new User($userId);

		$key = hash_hmac("sha512", $userId . $teamNumber, $user->uniqId . static::$API_KEY_PEPPER);
		$this->key = $key;
	}

	/**
	 * Get the API key from the class.
	 *
	 * @return string The API Key
	 */
	public function getKey() {
		return $this->key;
	}
}