<?php
namespace Data\GameStats;

class ScoreStats {
	public $high;
	public $low;
	public $percentage;

	public function __construct($teamNumber, $matchId) {
		$this->shotStat($teamNumber, $matchId, "high", $this->high);
		$this->shotStat($teamNumber, $matchId, "low", $this->low);
		$this->percentage = ($this->high['scored'] + $this->low['scored']) / ($this->high['attempted'] + $this->low['attempted']);
	}

	private function shotStat($teamId, $matchId, $towerGoal, $results) {
		$towerGoal = strtoupper($towerGoal);

		$query = "SELECT count(shotId) AS numberAttempted FROM Shot WHERE teamNumber = :teamId AND matchId = :matchId AND towerGoal = :towerGoal";
		$query_params = array(':teamId' => $teamId, ":matchId" => $matchId, ':towerGoal' => $towerGoal);
		$row = executeSQLSingleRow($query, $query_params);
		$results["attempted"] = $row['numberAttempted'];

		$query = "SELECT count(shotId) AS numberScored FROM Shot WHERE teamNumber = :teamId AND matchId = :matchId AND towerGoal = :towerGoal AND scored = 1";
		$row = executeSQLSingleRow($query, $query_params);
		$results["scored"] = $row['numberScored'];

		$result["percent"] = $results["scored"] / $results["attempted"];
	}
}