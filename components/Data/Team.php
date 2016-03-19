<?php
namespace Data;

class Team {
	private $teamNumber;

	public function __construct($teamNumber) {
		$this->teamNumber = $teamNumber;
	}

	public function getNumberOfScoutMembers() {
		$query = "SELECT COUNT(userId) AS number FROM Users WHERE teamNumber = :teamNumber";
		$query_params = array(':teamNumber' => $this->teamNumber);

		$result = executeSQLSingleRow($query, $query_params);

		return $result['number'];
	}
}
