<?php

namespace Data\GameAction;
require_once("../../../common.php");

class Challenge {
	public $challengeId;
	protected $objectFields;
	
	public function __construct($challengeId) {
		$this->challengeId = $challengeId;
		$query = "SELECT * FROM Challenge WHERE crossingId = :id";
		$query_params = array(':id' => $challengeId);
		$results = executeSQLSingleRow($query, $query_params);
		
		foreach ($results as $col => $data) {
			$this->objectFields[$col] = $data;
		}
	}
	
	/**
	 * @param $matchId
	 * @param $teamNumber
	 * @param $towerSide
	 * @param $scoutTeamNumber
	 * @param $userId
	 * @return Challenge
	 */
	public static function create($matchId, $teamNumber, $towerSide, $scoutTeamNumber, $userId) {
		$query = "INSERT INTO Challenge (matchId, teamNumber, towerSide, scoutTeamNumber, userId) "
			. "VALUES (:matchId, :teamNumber, :towerSide, :scoutTeamNumber, :userId)";
		$query_params = array(
			":matchId" => intval($matchId),
			":teamNumber" => intval($teamNumber),
			":towerSide" => strtoupper($towerSide),
			":scoutTeamNumber" => intval($scoutTeamNumber),
			":userId" => intval($userId)
		);
		executeSQL($query, $query_params);
		
		global $db;
		return new Challenge($db->lastInsertId());
	}
}