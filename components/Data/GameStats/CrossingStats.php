<?php
namespace Data\GameStats;
require_once("../../../common.php");

class CrossingStats {
	public $defenses;

	public function __construct($teamNumber, $matchId, $scoutTeamNumber) {
		$query = "SELECT defenseName, count(defenseName) AS crossingCount
					FROM scoutingCrossing
					WHERE teamNumber = :teamNumber
					AND matchId = :matchId
					AND scoutTeamNumber = :scoutTeamNumber
					AND assist = 'NONE'
					GROUP BY defenseName";
		$query_params = array(
			':teamNumber' => $teamNumber,
			':matchId' => $matchId,
			':scoutTeamNumber' => $scoutTeamNumber
		);
		$stmt = executeSQL($query, $query_params);
		$this->defenses = $stmt->fetchAll();
	}
}