<?php

class Team {
	private $teamNumber;

	public function __construct($teamNumber) {
		$this->teamNumber = $teamNumber;
	}

	public function getNumberOfScoutMembers() {
		$query = "SELECT COUNT(userId) as number FROM Users WHERE teamNumber = :teamNumber";
		$query_params = array(':teamNumber' => $this->teamNumber);

		$stmt = executeSQL($query, $query_params);
		$result = $stmt->fetch();

		return $result['number'];
	}
}