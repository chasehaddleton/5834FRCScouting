<?php
namespace Data\GameStats;
require_once(dirname(dirname(dirname(__DIR__))) . "/common.php");

class ScaleStats {
	public $scale;

	public function __construct($teamNumber, $matchNumber, $scoutTeamNumber) {
		$query = "SELECT towerSide
					FROM Scale
					WHERE teamNumber = :teamNumber
					AND matchId = :matchId
					AND scoutTeamNumber = :scoutTeamNumber";
		$query_params = array(
			':teamNumber' => $teamNumber,
			':matchId' => $matchNumber,
			':scoutTeamNumber' => $scoutTeamNumber
		);
		$this->scale = executeSQLSingleRow($query, $query_params);
	}
}