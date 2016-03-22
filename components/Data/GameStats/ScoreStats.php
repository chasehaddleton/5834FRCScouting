<?php
namespace Data\GameStats;
require_once(dirname(dirname(dirname(__DIR__))) . "/common.php");

class ScoreStats {
	public $high = array();
	public $low = array();
	public $percentage;
	protected $scoutTeamNumber;

	public function __construct($teamNumber, $matchId, $scoutTeamNumber) {
		$this->scoutTeamNumber = $scoutTeamNumber;
		$this->high = $this->shotStat($teamNumber, $matchId, "high");
		$this->low = $this->shotStat($teamNumber, $matchId, "low");
		$this->percentage = ($this->high['scored'] + $this->low['scored']) / ($this->high['attempted'] + $this->low['attempted']);
	}
	
	private function shotStat($teamNumber, $matchId, $towerGoal) {
		$result = array();
		$towerGoal = strtoupper($towerGoal);

		$query = "SELECT count(shotId) AS numberAttempted
					FROM Shot
					WHERE teamNumber = :teamId
					AND matchId = :matchId
					AND scoutTeamNumber = :scoutTeamNumber
					AND towerGoal = :towerGoal";
		$query_params = array(
			':teamId' => $teamNumber,
			":matchId" => $matchId,
			':towerGoal' => $towerGoal,
			':scoutTeamNumber' => $this->scoutTeamNumber
		);
		$row = executeSQLSingleRow($query, $query_params);
		$results["attempted"] = $row['numberAttempted'];

		$query = "SELECT count(shotId) AS numberScored
					FROM Shot
					WHERE teamNumber = :teamId
					AND matchId = :matchId
					AND scoutTeamNumber = :scoutTeamNumber
					AND towerGoal = :towerGoal
					AND scored = 1";
		$row = executeSQLSingleRow($query, $query_params);
		$results["scored"] = $row['numberScored'];

		$result["percent"] = $results["scored"] / $results["attempted"];
		
		return $result;
	}
}