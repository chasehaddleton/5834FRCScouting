<?php
/**
 * This file fills the Teams database with all FRC teams in the world. THIS NEED ONLY BE RUN ONCE.
 */
require_once('../../components/common.php');

verifyPermission($_SESSION['level'], 2);
set_time_limit(60);

$query = "SELECT COUNT(teamNumber) AS numTeams FROM Teams";
$result = executeSQLSingleRow($query, null);

if ($result['numTeams'] > 0) {
	echo "Team DB has already been filled.";
	die();
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	$setting->getTBAHeader(),
));

$i = 0;
$query = "INSERT INTO Teams (teamNumber, teamName) VALUES (:teamNumber, :teamName)";

do {
	curl_setopt($ch, CURLOPT_URL, "http://www.thebluealliance.com/api/v2/teams/" . $i++);
	$result = curl_exec($ch);

	$obj = json_decode($result);

	for ($j = 0; $j < count($obj); $j++) {
		if (!is_null($obj[$j]->nickname)) {
			$query_params = array(':teamNumber' => $obj[$j]->team_number, ':teamName' => $obj[$j]->nickname);
			executeSQL($query, $query_params);
		}
	}
} while (!is_null($obj));

curl_close($ch);
