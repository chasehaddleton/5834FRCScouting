<?php
namespace Data\GameStats;
require_once("ScoreStats.php");
require_once("ScaleStats.php");
require_once("CrossingStats.php");

class MatchTeam {
	public $teamNumber;
	public $matchNumber;
	public $compKey;
	public $scoring;
	public $scale;
	public $crossing;

	public function __construct($teamNumber, $matchNumber, $compKey) {
		$this->teamNumber = $teamNumber;
		$this->matchNumber = $matchNumber;
		$this->compkey = $compKey;

		$query = "SELECT matchId FROM Matches WHERE compKey = :compkey AND matchNumber = :matchNum";
		$query_params = array(':compKey' => $compKey, ':matchNum' => $matchNumber);
		$result = executeSQLSingleRow($query, $query_params);
		$matchId = $result['matchId'];

		$this->scoring = new ScoreStats($teamNumber, $matchId);
	}
}
