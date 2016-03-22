<?php
namespace Data\GameAction;
require_once(dirname(dirname(dirname(__DIR__))) . "/common.php");

class Scale {
	public $scaleId;
	protected $objectFields;
	
	public function __construct($scaleId) {
		$this->scaleId = $scaleId;
		$query = "SELECT * FROM Scale WHERE crossingId = :id";
		$query_params = array(':id' => $scaleId);
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
	 * @return Scale
	 */
	public static function create($matchId, $teamNumber, $towerSide, $scoutTeamNumber, $userId) {
		$query = "INSERT INTO Scale (matchId, teamNumber, towerSide, scoutTeamNumber, userId) "
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
		return new Scale($db->lastInsertId());
	}
}