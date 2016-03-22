<?php

namespace Data\GameAction;
require_once(dirname(dirname(dirname(dirname(__DIR__)))) . "/common.php");

class Crossing {
	public $crossingId;
	protected $objectFields;
	
	public function __construct($crossingId) {
		$this->crossingId = $crossingId;
		$query = "SELECT * FROM Crossing WHERE crossingId = :id";
		$query_params = array(':id' => $crossingId);
		$results = executeSQLSingleRow($query, $query_params);
		
		foreach ($results as $col => $data) {
			$this->objectFields[$col] = $data;
		}
	}
	
	/**
	 * @param $matchId int
	 * @param $teamNumber int
	 * @param $slot int
	 * @param $auto boolean
	 * @param $assist boolean
	 * @param $scoutTeamNumber int
	 * @param $userId int
	 * @return Crossing
	 */
	public static function create($matchId, $teamNumber, $slot, $auto, $assist, $scoutTeamNumber, $userId) {
		$query = "INSERT INTO Crossing (matchId, teamNumber, slot, auto, assist, scoutTeamNumber, userId) "
			. "VALUES (:matchId, :teamNumber, :slot, :auto, :assist, :scoutTeamNumber, :userId)";
		$query_params = array(
			":matchId" => intval($matchId),
			":teamNumber" => intval($teamNumber),
			":defenseName" => intval($slot),
			":auto" => (filter_var($auto, FILTER_VALIDATE_BOOLEAN)) ? 1 : 0,
			":assist" => strtoupper($assist),
			":scoutTeamNumber" => intval($scoutTeamNumber),
			":userId" => intval($userId)
		);
		executeSQL($query, $query_params);
		
		global $db;
		return new Crossing($db->lastInsertId());
	}
}