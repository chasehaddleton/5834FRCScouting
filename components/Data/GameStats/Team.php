<?php
namespace Data\GameStats;
require_once("../../common.php");

class Team {
	public $shotPercentage;
	private $teamNumber;
	private $scoutTeamNumber;

	public function __construct($teamNumber) {
		$this->teamNumber = $teamNumber;
	}

	public function getStatistics() {
		$this->getShotStatistics();
	}

	private function getShotStatistics() {
		$query = "SELECT COUNT(shotId)
					AS shotAttempts
					FROM Shot
					WHERE teamNumber = :teamNumber
					AND scoutTeamNumber = :scoutTeamNumber";
		$query_params = array(
			':teamNumber' => $this->teamNumber,
			':scoutTeamNumber' => $this->scoutTeamNumber
		);
		$row = executeSQLSingleRow($query, $query_params);
		$attempts = $row['shotAttempts'];

		$query = "SELECT COUNT(shotId) AS shotScored
					FROM Shot
					WHERE teamNumber = :teamNumber
					AND scoutTeamNumber = :scoutTeamNumber
					AND scored = 1";
		$row = executeSQLSingleRow($query, $query_params);
		$scored = $row['shotScored'];
		$this->shotPercentage = $scored / $attempts;
	}
}