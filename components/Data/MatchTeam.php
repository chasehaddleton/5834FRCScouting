<?php

namespace Data {
	class MatchTeam {
		public $teamNumber;
		public $matchNumber;
		public $shotsScored;
		public $shotsAttempted;
		public $crossingsMade;
		public $didScale;

		public function __construct($teamNumber, $matchNumber) {
			$this->teamNumber = $teamNumber;
			$this->matchNumber = $matchNumber;

			$query = "SELECT count(shotId) AS numberAttempted FROM Shot WHERE teamId = :teamId AND matchId = :matchId";
			$query_params = array(':teamId' => $teamNumber, ":matchId" => $matchNumber);
			$result = executeSQLSingleRow($query, $query_params);
			$this->shotsAttempted = $result['numberAttempted'];

			$query = "SELECT count(shotId) AS numberScored FROM Shot WHERE teamId = :teamId AND matchId = :matchId AND scored = 1";
			$result = executeSQLSingleRow($query, $query_params);
			$this->shotsScored = $result['numberScored'];

			$query = "SELECT  AS numberAttempted FROM Shot WHERE teamId = :teamId AND matchId = :matchId";
			$query_params = array(':teamId' => $teamNumber, ":matchId" => $matchNumber);
			$result = executeSQLSingleRow($query, $query_params);
			$this->shotsAttempted = $result['numberAttempted'];
		}
	}
}