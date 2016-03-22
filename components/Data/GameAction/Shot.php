<?php

namespace Data\GameAction;
require_once(dirname(dirname(dirname(dirname(__DIR__)))) . "/common.php");

class Shot {
	public $shotId;
	protected $objectFields;
	
	public function __construct($shotId) {
		$this->shotId = $shotId;
		$query = "SELECT * FROM Shot WHERE crossingId = :id";
		$query_params = array(':id' => $shotId);
		$results = executeSQLSingleRow($query, $query_params);
		
		foreach ($results as $col => $data) {
			$this->objectFields[$col] = $data;
		}
	}
	
	/**
	 * @param $matchId
	 * @param $teamNumber
	 * @param $scored
	 * @param $auto
	 * @param $rampShot
	 * @param $towerSide
	 * @param $towerGoal
	 * @param $scoutTeamNumber
	 * @param $userId
	 * @return Shot
	 */
	public static function create($matchId, $teamNumber, $scored, $auto, $rampShot, $towerSide, $towerGoal, $scoutTeamNumber, $userId) {
		$query = "INSERT INTO Shot (matchId, teamNumber, scored, auto, rampShot, towerSide, towerGoal, scoutTeamNumber, userId) "
			. "VALUES (:matchId, :teamNumber, :scored, :auto, :rampShot, :towerSide, :towerGoal, :scoutTeamNumber, :userId)";
		$query_params = array(
			":matchId" => intval($matchId),
			":teamNumber" => intval($teamNumber),
			":scored" => (filter_var($scored, FILTER_VALIDATE_BOOLEAN)) ? 1 : 0,
			":auto" => (filter_var($auto, FILTER_VALIDATE_BOOLEAN)) ? 1 : 0,
			":rampShot" => (filter_var($rampShot, FILTER_VALIDATE_BOOLEAN)) ? 1 : 0,
			":towerSide" => strtoupper($towerSide),
			":towerGoal" => strtoupper($towerGoal),
			":scoutTeamNumber" => intval($scoutTeamNumber),
			":userId" => intval($userId)
		);
		executeSQL($query, $query_params);
		
		global $db;
		return new Shot($db->lastInsertId());
	}
}