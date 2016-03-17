<?php
namespace Data\GameStats;


class CrossingStats {

	public function __construct($teamNumber, $matchId) {
		$query = "SELECT defenseName, count(defenseName) FROM scoutingCrossing GROUP BY defenseName";
	}
}