<?php
namespace Data\GameStats;


class CrossingStats {
	public $defenses;

	public function __construct($teamNumber, $matchId, $scoutTeamNumber) {
		$query = "SELECT defenseName, count(defenseName) as crossingCount FROM Crossing WHERE teamNumber = :teamNumber AND matchId = :matchId AND scoutTeamNumber = :scoutTeamNumber GROUP BY defenseName";
		$query_params = array(':teamNumber' => $teamNumber, ':matchId' => $matchId, ':scoutTeamNumber' => $scoutTeamNumber);

		$stmt = executeSQL($query, $query_params);
		$this->defenses = $stmt->fetchAll();
	}
}