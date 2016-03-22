<?php
namespace Data\GameAction;
require_once(dirname(dirname(dirname(__DIR__))) . "/common.php");

class Note {
	public $noteId;
	protected $objectFields;
	
	public function __construct($noteId) {
		$this->noteId = $noteId;
		$query = "SELECT * FROM Challenge WHERE crossingId = :id";
		$query_params = array(':id' => $noteId);
		$results = executeSQLSingleRow($query, $query_params);
		
		foreach ($results as $col => $data) {
			$this->objectFields[$col] = $data;
		}
	}
	
	/**
	 * @param $matchId
	 * @param $teamNumber
	 * @param $contents
	 * @param $scoutTeamNumber
	 * @param $userId
	 * @return Note
	 */
	public static function create($matchId, $teamNumber, $contents, $scoutTeamNumber, $userId) {
		$query = "INSERT INTO Notes (matchId, teamNumber, contents, scoutTeamNumber, userId)"
			. "VALUES (:matchId, :teamNumber, :contents, :scoutTeamNumber, :userId)";
		$query_params = array(
			":matchId" => intval($matchId),
			":teamNumber" => intval($teamNumber),
			':contents' => $contents,
			":scoutTeamNumber" => intval($scoutTeamNumber),
			":userId" => intval($userId)
		);
		executeSQL($query, $query_params);
		
		global $db;
		return new Note($db->lastInsertId());
	}
}