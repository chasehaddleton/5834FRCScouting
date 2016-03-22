<?php
namespace Data\GameStats;
require_once(dirname(dirname(dirname(dirname(__DIR__)))) . "/common.php");

class MatchTeam {
	public $teamNumber;
	public $matchNumber;
	public $compKey;
	public $scoring;
	public $scale;
	public $crossing;

	public function __construct($teamNumber, $compKey, $matchNumber, $scoutTeamNumber) {
		$teamNumber = intval($teamNumber);
		$matchNumber = intval($matchNumber);

		$query = "SELECT matchId
					FROM Matches
					WHERE compKey = :compKey
					AND matchNumber = :matchNumber";
		$query_params = array(
			':compKey' => $compKey,
			':matchNumber' => $matchNumber
		);
		$result = executeSQLSingleRow($query, $query_params);
		$matchId = $result['matchId'];

		$this->teamNumber = $teamNumber;
		$this->matchNumber = $matchNumber;
		$this->compKey = $compKey;
		$this->scoring = new ScoreStats($teamNumber, $matchId, $scoutTeamNumber);
		$this->crossing = new CrossingStats($teamNumber, $matchId, $scoutTeamNumber);
		$this->scale = new ScaleStats($teamNumber, $matchId, $scoutTeamNumber);
	}
}
