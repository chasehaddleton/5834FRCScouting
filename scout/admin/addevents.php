<?php
/**
 * This file fills the Match database with all FRC championship matches. THIS NEED ONLY BE RUN ONCE.
 */
require_once('../../components/common.php');

verifyPermission($_SESSION['level'], 2);
set_time_limit(60);

$query = "SELECT COUNT(eventId) AS numEvents FROM Events";
$result = executeSQLSingleRow($query, null);

if ($result['numEvents'] > 0) {
	echo "Event DB has already been filled.";
	die();
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	$setting->getTBAHeader(),
));
curl_setopt($ch, CURLOPT_URL, "http://www.thebluealliance.com/api/v2/events/");
$result = curl_exec($ch);
$obj = json_decode($result);

$query = "INSERT INTO Events (compKey, isChampionship, compName) VALUES (:compKey, :isChampionship, :compName)";

for ($j = 0; $j < count($obj); $j++) {
	$compKey = $obj[$j]->event_code;
	$isChampionship = ($obj[$j]->event_type_string == "Championship Division") ? 1 : 0;
	$compName = $obj[$j]->name;

	$query_params = array(':compKey' => $compKey, ':isChampionship' => $isChampionship, ':compName' => $compName);

	executeSQL($query, $query_params);
}

curl_close($ch);